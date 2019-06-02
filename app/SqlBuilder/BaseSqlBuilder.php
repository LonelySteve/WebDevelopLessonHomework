<?php

namespace App\SqlBuilder;

use App\Dao\BaseDao;

abstract class BaseSqlBuilder
{
    protected $segments = array();
    protected $values = array();

    protected $dao = null;

    protected $table_name = "";
    // 是否使用名称占位符
    protected $use_name_placeholders;
    protected $name_params_counter = 0;

    function __construct(BaseDao $dao)
    {
        $this->table_name = $dao::get_table_name();
        $this->dao = $dao;
    }

    public abstract function _date($timestamp = "time()");

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

    function execute($extra_pdo_value_types = null, $auto_pdo_value_types = true)
    {
        $sql = $this->dump();
        return $this->dao->execute_sql($sql, $this->get_values(), $extra_pdo_value_types, $auto_pdo_value_types);
    }

    function get_values()
    {
        return $this->values;
    }
}
