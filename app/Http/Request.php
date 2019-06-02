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
    public $aid;

    public static function wrap()
    {
        $r = new Request();
        session_start();
        $r->args = $_GET;
        $r->form = $_POST;
        $r->method = $_SERVER["REQUEST_METHOD"];
        $r->path = $_SERVER["PHP_SELF"];
        $r->header = getallheaders();
        $r->cookie = $_COOKIE;
        $r->aid = @$_SESSION[AdminDao::get_primary_key_name()];
        return $r;
    }

    public function get_input()
    {
        return $this->form + $this->args;
    }

    public function set_input($name, $value)
    {
        if (array_key_exists($name, $this->args)) {
            $this->args[$name] = $value;
        } else {
            $this->form[$name] = $value;
        }
    }
}