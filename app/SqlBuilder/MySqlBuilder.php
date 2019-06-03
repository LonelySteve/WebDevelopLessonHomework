<?php

namespace App\SqlBuilder;

use App\Util\Util;

class MySqlBuilder extends BaseSqlBuilder
{
    public function _date($timestamp = null)
    {
        $timestamp = $timestamp ?: time();
        return date("Y-m-d H:i:s", $timestamp);
    }

    protected function get_columns_str($columns, $placeholder = false)
    {
        // 如果 $columns 为空，返回空字符串
        if (!$columns) {
            return "";
        }
        // 如果 $columns 是字符串 则直接返回
        if (is_string($columns)) {
            return $columns;
        }
        if ($placeholder) {
            if ($this->use_name_placeholders) {
                $columns = array_map(function ($col) {
                    return $col .= "=:$col";
                }, $columns);
            } else {
                $columns = array_map(function ($col) {
                    return $col .= "=?";
                }, $columns);
            }
        }
        return implode(", ", $columns);
    }


    /**
     * 选择某些列
     *
     * @param null|array|string $columns 欲选择的列，留空或者用“*”表示所有列，用数字数组传入多个列名，也可用英文半角逗号分隔每个列名的字符串来表示。
     * @return $this
     */
    function select($columns = null)
    {
        // 将字符串形式表示的列转换为数组形式
        if (is_string($columns)) {
            if ($columns === "*") {
                $columns = array();
            } else {
                // 如果是字符串表示的列序列，以逗号（允许逗号后面有不定数量的空格）分隔得到列名数组
                $columns = preg_split("/, */", $columns, NULL, PREG_SPLIT_NO_EMPTY);
            }
        }

        $columns_str = $this->get_columns_str($columns);
        // 如果产生空字符串，说明列数组里没有元素，应当视作“我全都要”的含义
        $columns_str = $columns_str ? $columns_str : "*";

        $this->segments[] = "SELECT $columns_str FROM " . $this->table_name;

        return $this;
    }


    /**
     * 插入指定数据
     *
     * @param array $data 欲插入的数据数组，既可以是关联数组，也可以是数字数组
     * @return $this
     */
    function insert($data)
    {
        // 判断输入参数是否为关联数组，如果是，则SQL语句将采用命名参数，否则使用问号占位符
        // NOTE: PDO 的预处理语句要么使用问号占位符，要么使用命名参数，不允许混合使用
        if (Util::array_is_assoc($data)) {
            $placeholder_arr = array_map(function ($item) {
                return ":" . $item; // 命名参数是在原参数名基础上加上 ":" 构成的
            }, array_keys($data));
            $cols = array_keys($data);
            $this->use_name_placeholders = true;
        } else {
            $placeholder_arr = array_fill(0, count($data), "?");
            // 对于数字数组，列名数组置为空
            $cols = null;
            $this->use_name_placeholders = false;
        }

        $this->values += $data;

        $columns_str = "";
        if ($cols) {
            $columns_str = $this->get_columns_str($cols);
            $columns_str = "($columns_str)"; // NOTE: 包上括号，也许可以不加？
        }

        $values_str = implode(", ", $placeholder_arr);

        $this->segments[] = "INSERT INTO " . $this->table_name . $columns_str . " VALUES ($values_str)";

        return $this;
    }


    /**
     * 更新指定数据
     *
     * 【注意：Update 函数始终使用命名参数占位符】
     *
     * @param array $data 欲更新的数据数组，使用关联数组，键表示欲更新的字段名
     */
    function update($data)
    {
        // update 函数始终使用命名参数占位符
        $this->use_name_placeholders = true;

        $this->values += $data;

        $columns_str = $this->get_columns_str(array_keys($data), true);

        $this->segments[] = "UPDATE " . $this->table_name . " SET " . $columns_str;

        return $this;
    }

    function delete()
    {
        $this->segments[] = "DELETE FROM " . $this->table_name;

        return $this;
    }

    function limit($offset, $size = null)
    {
        // 根据  $use_name_placeholders 标志决定使用何种占位方式
        if ($this->use_name_placeholders) {
            $this->segments[] = "LIMIT :__offset__" . ($size ? ", :__size__" : "");
            $this->values += [
                "__offset__" => $offset,
                "__size__" => $size
            ];
        } else {
            $this->segments[] = "LIMIT ?" . ($size ? ",?" : "");
            $this->values += [$offset, $size];
        }

        return $this;
    }

    /**
     * 按照指定关键字及排序规则进行排序
     *
     * @param array $data 既可以是数字数组，也可以是关联数组，甚至可以是两者的混合体
     * 作为数字数组时，默认以值表示的列进行升序。作为关联数组时，使用键表示的列进行排序，排序规则由值表示，对于MySQL而言，这可以为ASC或DESC
     */
    function order_by($data)
    {
        if (Util::array_is_assoc($data)) {
            foreach ($data as $key => $value) {
                $parts[] = "$key $value";
            }
        } else {
            $parts = array_values($data);
        }
        $this->segments[] = "ORDER BY " . implode(", ", $parts);

        return $this;
    }

    function where($conditions)
    {
        $conditions_count = count($conditions);

        if ($conditions_count > 3) {
            throw new \InvalidArgumentException("The number of conditional array parameters cannot be more than three!");
        }
        // 判断条件数组的长度，如果为2则在中间插入=
        if (count($conditions) === 2) {
            $conditions = [$conditions[0], "=", $conditions[1]];
        }
        // 懒得判断符号是否有效了，就直接合到SQL语句里得了
        if ($this->use_name_placeholders) {
            $placeholder = "__param" . strval($this->name_params_counter) . "__";

            $this->segments[] = "WHERE " . $conditions[0] . $conditions[1] . ":" . $placeholder;
            $this->values[$placeholder] = $conditions[2];
        } else {
            $this->segments[] = "WHERE " . $conditions[0] . $conditions[1] . "?";
            $this->values[] = $conditions[2];
        }

        return $this;
    }
}
