<?php


namespace App\Dao;

use App\Config\DBConfig;

abstract class BaseDao
{
    protected $db_config;
    protected $pdo;

    protected $segments = array();
    protected $values = array();

    public $table_name = "";

    function __construct(DBConfig $db_config = null)
    {
        $this->db_config = $db_config ?: DBConfig::from_dot_env();
        $this->pdo = new \PDO($this->db_config->get_dsn(), $this->db_config->db_user, $this->db_config->db_pass, array(\PDO::ERRMODE_EXCEPTION));
    }

    abstract function select($columns = null);

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