<?php

namespace app;

use Dotenv\Dotenv;

class Config
{
    static function get_config()
    {
        $dotenv = Dotenv::create(__DIR__ . "\..");
        $dotenv->load();
        $dotenv->required("DEBUG_MODE")->isBoolean();

        $is_debug_mode = getenv("DEBUG_MODE");

        // 根据调试模式的值请求得到不同的数据库连接参数
        if ($is_debug_mode) {
            $dotenv->required("DEV_DB_ADDR")->notEmpty();
            $dotenv->required("DEV_DB_USER")->notEmpty();
            $dotenv->required("DEV_DB_PASS");
            $db_addr = getenv("DEV_DB_ADDR");
            $db_user = getenv("DEV_DB_USER");
            $db_pass = getenv("DEV_DB_PASS");
        } else {
            $dotenv->required("DB_ADDR")->notEmpty();
            $dotenv->required("DB_USER")->notEmpty();
            $dotenv->required("DB_PASS");
            $db_addr = getenv("DB_ADDR");
            $db_user = getenv("DB_USER");
            $db_pass = getenv("DB_PASS");
        }

        return [
            "DEBUG_MODE" => $is_debug_mode,
            "DB_ADDR" => $db_addr,
            "DB_USER" => $db_user,
            "DB_PASS" => $db_pass
        ];
    }


}

