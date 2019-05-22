<?php

namespace App\SqlBuilder;

class MySqlBuilder extends BaseSqlBuilder
{
    function __construct($table_name = "")
    {
        $this->table_name = $table_name;
    }

    /**
     * 判断指定数组是否为关联数组
     *
     * @param $arr array 欲判断的数组
     * @return bool
     */
    protected function is_assoc($arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }


    protected function get_columns_str($columns, $placeholder = false)
    {
        // 如果 $columns 是字符串 则直接返回
        if (is_string($columns)) {
            return $columns;
        }
        if ($placeholder) {
            $columns = array_map(function ($col) {
                $col .= "=?";
            }, $columns);
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
                $columns = preg_split(", *", $columns);
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
        $placeholder_arr = array_fill(0, count($data), "?");

        if ($this->is_assoc($data)) {
            $cols = array_keys($data);
        } else {
            // 对于数字数组，列名数组置为空
            $cols = null;
        }

        $this->values += array_values($data);

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
     * @param array $data 欲更新的数据数组
     */
    function update($data)
    {
        if ($this->is_assoc($data)) {
            $cols = array_keys($data);
        } else {
            // TODO 支持数字数组作为参数
            throw new \InvalidArgumentException("Numeric arrays are not supported for the time being!");
        }

        $this->values += array_values($data);

        $columns_str = $this->get_columns_str($cols, true);

        $this->segments[] = "UPDATE " . $this->table_name . " SET " . $columns_str;
    }

    function delete()
    {
        $this->segments[] = "DELETE FROM " . $this->table_name;
    }

    function limit($offset, $size = null)
    {
        $this->segments[] = "LIMIT ?" . $size ? ",?" : "";
        $this->values += [$offset, $size];
    }

    /**
     * 按照指定关键字及排序规则进行排序
     *
     * @param array $data 既可以是数字数组，也可以是关联数组，甚至可以是两者的混合体
     * 作为数字数组时，默认以值表示的列进行升序。作为关联数组时，使用键表示的列进行排序，排序规则由值表示，对于MySQL而言，这可以为ASC或DESC
     */
    function order_by($data)
    {
        foreach ($data as $key => $value) {
            if (is_int($key)) {
                $parts[] = $value;
            } else {
                $parts[] = "$key $value";
            }
        }
        $this->segments[] = "ORDER BY " . implode(", ", $parts);
    }

    function where($conditions)
    {
        $conditions_count = count($conditions);

        if ($conditions_count > 3) {
            throw new \InvalidArgumentException("The number of conditional array parameters cannot be more than three!");
        }
        // 判断条件数组的长度，如果为2则在中间插入=
        if (count($conditions) === 2) {
            $conditions = $conditions[0] + ["="] + $conditions[1];
        }
        // 懒得判断符号是否有效了，就直接合到SQL语句里得了
        $this->segments[] = "WHRER " . $conditions[0] . $conditions[1] . "?";
        $this->values[] = $conditions[2];
    }
}
