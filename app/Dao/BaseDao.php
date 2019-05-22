<?php


namespace App\Dao;

use App\Config\DBConfig;
use App\SqlBuilder\SqlBuilderFactory;

abstract class BaseDao
{
    protected $db_config;
    protected $pdo;
    protected $sql_builder;

    function __construct(DBConfig $db_config = null, SqlBuilderFactory $factory = null)
    {
        $this->db_config = $db_config ?: DBConfig::from_dot_env();
        $this->sql_builder = $factory ? $factory::from_type($this->db_config->db_type) : SqlBuilderFactory::from_type($this->db_config->db_type);
        $this->pdo = new \PDO($this->db_config->get_dsn(), $this->db_config->db_user, $this->db_config->db_pass, array(\PDO::ERRMODE_EXCEPTION));
    }
}