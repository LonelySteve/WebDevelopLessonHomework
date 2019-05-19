<?php
// config.php
// 数据库配置
// 根据当前是否为本地调试模式决定连接到的数据库
namespace JLoeve\BBS\db\config;


class DBConfig
{
    public $server_host;
    public $db_type;
    public $user;
    public $pwd;
    public $db_name;

    public static function from_local()
    {
        $conf = new DBConfig();
        $conf->server_host = "127.0.0.1";
        $conf->db_name = "bbs";
        $conf->db_type = "mysql";
        $conf->user = getenv("LOCAL_MYSQL_ACCOUNT");
        $conf->pwd = getenv("LOCAL_MYSQL_PASSWORD");
        return $conf;
    }

    public static function from_remote()
    {
        $conf = new DBConfig();
        $conf->server_host = "35.194.175.209";
        $conf->db_name = "unknown";
        $conf->db_type = "mysql";
        $conf->user = "root";
        $conf->pwd = "";
        return $conf;
    }

    public static function from_auto()
    {
        if (@getenv("DEBUG_MODE")) {
            return self::from_local();
        } else {
            return self::from_remote();
        }
    }
}
