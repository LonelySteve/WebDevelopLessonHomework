<?php


namespace App\Http;

use App\Dao\AdminDao;

class Request
{
    public $method;
    public $header;
    public $cookie;
    public $path;
    public $form;
    public $args;
    public $admin_name;

    private static function nullal(array &$arr)
    {
        foreach ($arr as $k => &$v) {
            if ($v === "") {
                $v = null;
            }
        }
        return $arr;
    }

    public static function wrap()
    {
        $r = new Request();
        session_start();
        $r->args = self::nullal($_GET);
        $r->form = self::nullal($_POST);
        $r->method = $_SERVER["REQUEST_METHOD"];
        $r->path = $_SERVER["PHP_SELF"];
        $r->header = getallheaders();
        $r->cookie = $_COOKIE;
        $r->admin_name = @$_SESSION["admin_name"];
        return $r;
    }

    public function get_input()
    {
        // 根据不同的请求类型获取不同的输入参数
        switch ($this->method) {
            case "POST":
                return $this->form;
            default:
                return $this->args;
        }
    }

    public function set_input($name, $value)
    {
        // 根据不同的请求类型设置不同的输入参数
        switch ($this->method) {
            case "POST":
                return $this->form[$name] = $value;
            default:
                return $this->args[$name] = $value;
        }
    }
}