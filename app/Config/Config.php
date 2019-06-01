<?php

namespace App\Config;

use Dotenv\Dotenv;
use Dotenv\Exception\ValidationException;
use App\Exceptions\ConfigException;
use App\Http\Response;

class Config
{
    public $debug_mode;
    public $db_config;
    public $smarty;

    static $instance = null;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    static function load($paths = null)
    {
        // 如果配置缓存无效则读取配置并缓存配置
        if (!self::$instance) {

            self::$instance = new Config();
            self::$instance->debug_mode = false;
            // 设置时区
            date_default_timezone_set('Etc/GMT-8');

            try {
                $dotenv = Dotenv::create($paths ?: __DIR__ . "\..\..");

                $dotenv->load();

                $dotenv->required("DEBUG_MODE")->isBoolean();
                // 是否为调试模式，默认情况为False
                $is_debug_mode = @getenv("DEBUG_MODE") ?: false;

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
            } catch (ValidationException $ex) {
                throw new ConfigException("", 0, $ex);
            }
            self::$instance->debug_mode = $is_debug_mode;
            self::$instance->db_config = new DBConfig($db_addr, $db_name, $db_user, $db_pass, $db_type);
            self::$instance->smarty = new \Smarty();
            # 重新设置smarty的目录设置
            self::$instance->smarty->setTemplateDir(__DIR__ . "/../../templates");
            self::$instance->smarty->setCompileDir(__DIR__ . "/../../templates_c/");
            self::$instance->smarty->setConfigDir(__DIR__ . "/../../configs/");
            self::$instance->smarty->setCacheDir(__DIR__ . "/../../cache/");
            Response::$smarty = self::$instance->smarty;
        }

        return self::$instance;
    }
}
