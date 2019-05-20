<?php

namespace App\Config;

use Dotenv\Dotenv;

class Config
{
    static $conf_cache = null;

    static function from_dot_env($paths = null)
    {
        // 如果配置缓存无效则读取配置并缓存配置
        if (!self::$conf_cache) {
            $dotenv = Dotenv::create($paths ?: __DIR__ . "\..\..");
            $dotenv->load();
            $dotenv->required("DEBUG_MODE")->isBoolean();

            $is_debug_mode = getenv("DEBUG_MODE");

            // 根据调试模式的值请求得到不同的数据库连接参数
            if ($is_debug_mode) {
                $dotenv->required("DEV_DB_ADDR")->notEmpty();
                $dotenv->required("DEV_DB_USER")->notEmpty();
                $dotenv->required("DEV_DB_NAME")->notEmpty();
                $dotenv->required("DEV_DB_PASS");
                $db_name = getenv("DEV_DB_NAME");
                $db_addr = getenv("DEV_DB_ADDR");
                $db_user = getenv("DEV_DB_USER");
                $db_pass = getenv("DEV_DB_PASS");
                $db_type = @getenv("DEV_DB_TYPE") ?: "mysql";
            } else {
                $dotenv->required("DB_ADDR")->notEmpty();
                $dotenv->required("DB_USER")->notEmpty();
                $dotenv->required("DB_NAME")->notEmpty();
                $dotenv->required("DB_PASS");
                $db_name = getenv("DB_NAME");
                $db_addr = getenv("DB_ADDR");
                $db_user = getenv("DB_USER");
                $db_pass = getenv("DB_PASS");
                $db_type = @getenv("DB_TYPE") ?: "mysql";
            }

            self::$conf_cache = [
                "DEBUG_MODE" => $is_debug_mode,
                "DB_NAME" => $db_name,
                "DB_ADDR" => $db_addr,
                "DB_USER" => $db_user,
                "DB_PASS" => $db_pass,
                "DB_TYPE" => $db_type
            ];
        }

        return self::$conf_cache;
    }
}
