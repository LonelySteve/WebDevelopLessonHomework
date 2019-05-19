<?php

namespace SteveBBS\sqlBuilder;

class MySqlBuilder extends SqlBuilder
{

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

    function __construct($table_name)
    {
        $this->table_name = $table_name;
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
                $columns = preg_split(", *", $columns);
            }
        }

        $this->columns += $columns;

        $columns_str = $this->get_columns_str($columns);
        // 如果产生空字符串，说明列数组里没有元素，应当视作“我全都要”的含义
        $columns_str = $columns_str ? $columns_str : "*";

        $this->segments[] = "SELECT $columns_str FROM " . $this->table_name;

        return $this;
    }

    function insert($data)
    {
        $placeholder_arr = array_fill(0, count($data), "?");

        if ($this->is_assoc($data)) {
            $cols = array_keys($data);
        } else {
            // 对于数字数组，使用“?”填充列名
            $cols = $placeholder_arr;
        }

        $vals = array_values($data);

        $this->columns += $cols;
        $this->values += $vals;

        $columns_str = $this->get_columns_str($cols);
        $values_str = implode(", ", $placeholder_arr);

        $this->segments[] = "INSERT INTO " . $this->table_name . "($columns_str)" . " VALUES ($values_str)";

        return $this;
    }

    function update($data)
    {

        $placeholder_arr = array_fill(0, count($data), "?");

        if ($this->is_assoc($data)) {
            $cols = array_keys($data);
        } else {
            // 对于数字数组，使用“?”填充列名
            $cols = $placeholder_arr;
        }

        $vals = array_values($data);

        $columns_str = $this->get_columns_str($cols);

        $columns_str = $this->get_columns_str($columns, true);

    }

    function delete()
    {
        // TODO: Implement delete() method.
    }

    function limit($offset, $size = null)
    {
        // TODO: Implement limit() method.
    }

    function order_by($columns, $type)
    {
        // TODO: Implement order_by() method.
    }

    function where()
    {
        // TODO: Implement where() method.
    }
}
