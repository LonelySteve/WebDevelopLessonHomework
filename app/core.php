<?php

namespace app;

use app\models\BaseModel;

class Request
{

    public $method;
    public $header;
    public $cookie;
    public $path;
    public $form;
    public $args;

    public function __construct()
    {
        $this->args = $_GET;
        $this->form = $_POST;
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->path = $_SERVER["PHP_SELF"];
        $this->header = getallheaders();
        $this->cookie = $_COOKIE;
    }
}

function init()
{
    // 加载环境变量
    $GLOBALS["bbs_conf"] = Config::get_config();
    // 初始化模型基类
    BaseModel::init($bbs_conf["DB_ADDR"], $bbs_conf["DB_USER"], $bbs_conf["DB_PASS"]);
}

function load_module($module_name)
{
    // 加载指定模块
}

/**
 * 应用指定中间件
 *
 * @param $middlewares
 */
function apply_middleware($middlewares)
{

}

function get_controller($control_name)
{

}


init();

