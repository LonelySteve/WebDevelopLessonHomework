<?php

namespace App\Models;

class BaseModel
{

    protected $sql_meta_array = array();
    protected $sql_values_array = array();
    protected $sql_short_types_desc = "";

    protected const table_name = "";

    static function init($db_addr, $db_user, $db_pass)
    {
        
    }

    static function get_table_name()
    {
        // https://php.net/manual/zh/language.oop5.constants.php
        $c = get_called_class();
        return $c::table_name;
    }

    static function get_field_short_type($type)
    {
        // https://php.net/manual/zh/language.oop5.constants.php
        $c = get_called_class();
        return $c::field_short_types_array[$type];
    }

    static function get_field_names()
    {
        // https://php.net/manual/zh/language.oop5.constants.php
        $c = get_called_class();
        return array_keys($c::field_short_types_array);
    }

    function add_sql($sql)
    {
        $this->sql_meta_array[] = $sql;
    }

    function query($columns)
    {
        if (!is_array($columns)) {
            $columns = array($columns);
        }
        $cols_placement = implode(", ", $columns);

        $sql = "SELECT " . $cols_placement . " FROM " . self::get_table_name();

        $this->add_sql($sql);

        return $this;
    }

    function insert($item)
    {
        // 插入语句始终使用显式列名插入方案。比如
        // INSERT INTO table_name(col1,col2,col3) VALUES (val1,val2,val3);

        $field_names = self::get_field_names();
        $fields = $item->get_fields();

        foreach ($field_names as $field_name) {
            $this->sql_short_types_desc .= self::get_field_short_type($field_name);
            $this->sql_values_array[] = $fields[$field_name];
        }

        $field_names_count = count($field_names);
        $cols_placement = implode(", ", $field_names);
        $vals_placement = implode(", ", array_fill(0, $field_names_count, "?"));

        $sql = "INSERT INTO " . self::get_table_name() . "(" . $cols_placement . ")" . " VALUES " . "(" . $vals_placement . ")";

        $this->add_sql($sql);

        return $this;
    }

    function delete()
    {
        $sql = "DELETE FROM " . self::get_table_name();

        $this->add_sql($sql);

        return $this;
    }

    function update($update_items_array)
    {
        $update_keys = array_keys($update_items_array);
        $update_values = array_values($update_items_array);

        foreach ($update_keys as $key) {
            $this->sql_short_types_desc .= self::get_field_short_type($key);
            $this->sql_values_array[] = $update_values[$key];
        }

        $placement_parts = array_map(function ($key) {
            return "$key=?";
        }, $update_keys);

        $placement = implode(", ", $placement_parts);

        $sql = "UPDATE " . self::get_table_name() . " SET " . $placement;

        $this->add_sql($sql);

        return $this;
    }

    protected function get_where_sql_meta($conditions)
    {
        $sql_meta = array();

        foreach ($conditions as $condition) {
            $condition_meta_count = count($condition);
            switch ($condition_meta_count) {
                case 2:
                    $col_name = $condition[0];
                    $val = $condition[1];

                    $this->sql_short_types_desc .= self::get_field_short_type($col_name);
                    $this->sql_values_array[] = $val;

                    $sql_meta[] = "$col_name=?";
                    break;
                case 3:
                    $col_name = $condition[0];
                    $relation = $condition[1];
                    $val = $condition[2];

                    $this->sql_short_types_desc .= self::get_field_short_type($col_name);
                    $this->sql_values_array[] = $val;

                    $sql_meta[] = "$col_name$relation?";
                    break;
            }
        }

        return $sql_meta;
    }


    function where($conditions)
    {
        $sql = "WHERE ";

        $sql_meta = $this->get_where_sql_meta($conditions);

        $sql .= implode(" AND ", $sql_meta);

        $this->add_sql($sql);

        return $this;
    }

    function or_where($conditions)
    {
        $sql = "WHERE ";

        $sql_meta = $this->get_where_sql_meta($conditions);

        $sql .= implode(" OR ", $sql_meta);

        $this->add_sql($sql);

        return $this;
    }

    function limit($offset, $size = null)
    {
        $sql = "LIMIT $offset" . ($size ? $size : '');

        $this->add_sql($sql);

        return $this;
    }

    function order_by($columns_array, $type = "ASC")
    {
        $sql = "ORDER BY " . implode(", ", $columns_array) . " " . $type;

        $this->add_sql($sql);

        return $this;
    }

    function tosql()
    {
        return implode("", $this->sql_meta_array);
    }
}