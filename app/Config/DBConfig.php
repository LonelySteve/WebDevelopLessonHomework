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

        return "$dbms:host=$host;dbname=$dbName";
    }

    function get_pdo()
    {
        $dsn = $this->get_dsn();
        $pdo = new \PDO($dsn, $this->db_user, $this->db_pass, array(\PDO::ERRMODE_EXCEPTION, \PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8mb4"));
        $pdo->setAttribute(\PDO::ATTR_STRINGIFY_FETCHES, false); // 防止读取时数值类型发生转换
        $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        return $pdo;
    }
}
