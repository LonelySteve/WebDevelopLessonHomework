<?php

namespace app\sqlBuilder;

abstract class SqlBuilder
{
    protected $segments = array();
    protected $columns = array();
    protected $values = array();
    protected $table_name = "";

    abstract function table($name);

    abstract function select($columns=null);

    abstract function insert($data);

    abstract function update($data);

    abstract function delete();

    abstract function limit($offset, $size = null);

    abstract function order_by($data);

    abstract function where();

    function dump()
    {
        // 用空格间隔拼凑所有sql语句片段即可
        return implode(" ", $this->segments);
    }
}
