<?php

namespace App\Config;

class DBConfig
{
    public $db_type;
    public $db_addr;
    public $db_user;
    public $db_pass;

    function __construct($db_addr, $db_name, $db_user, $db_pass, $db_type = "mysql")
    {
        $this->db_addr = $db_addr;
        $this->db_name = $db_name;
        $this->db_user = $db_user;
        $this->db_pass = $db_pass;
        $this->db_type = $db_type;
    }

    function get_dsn()
    {
        $dbms = $this->db_type;     // 数据库的类型
        $dbName = $this->db_name;   // 使用的数据库名称
        $host = $this->db_addr;     // 使用的主机名称

        return "$dbms:host=$host;dbName=$dbName ";
    }

    function get_pdo()
    {
        return new \PDO($this->get_dsn(), $this->db_user, $this->db_pass, array(\PDO::ERRMODE_EXCEPTION));
    }
}
