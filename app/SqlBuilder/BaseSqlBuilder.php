<?php

namespace App\SqlBuilder;


abstract class BaseSqlBuilder
{
    protected $segments = array();
    protected $values = array();

    public $table_name = "";

    abstract function select($columns = null);

    abstract function insert($data);

    abstract function update($data);

    abstract function delete();

    abstract function limit($offset, $size = null);

    abstract function order_by($data);

    abstract function where($conditions);

    function dump()
    {
        // 用空格间隔拼凑所有sql语句片段即可
        return implode(" ", $this->segments);
    }
}

class SqlBuilderFactory
{
    static function from_type($name, $table_name = "")
    {
        switch ($name) {
            case "mysql":
                return new MySqlBuilder($table_name);
            default:
                throw new \InvalidArgumentException("Unsupported database type!");
        }
    }
}