<?php

namespace App\SqlBuilder;


abstract class BaseSqlBuilder
{
    protected $segments = array();
    protected $values = array();

    public $table_name = "";

    function __construct($table_name = "")
    {
        $this->table_name = $table_name;
    }

    function select($columns = null)
    {
        return $this;
    }

    function insert($data)
    {
        return $this;
    }

    function update($data)
    {
        return $this;
    }

    function delete()
    {
        return $this;
    }

    function limit($offset, $size = null)
    {
        return $this;
    }

    function order_by($data)
    {
        return $this;
    }

    function where($conditions)
    {
        return $this;
    }

    function dump()
    {
        // 用空格间隔拼凑所有sql语句片段即可
        return implode(" ", $this->segments);
    }

    function get_values()
    {
        return $this->values;
    }
}
