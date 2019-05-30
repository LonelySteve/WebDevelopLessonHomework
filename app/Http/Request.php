<?php


namespace App\Http;


class Request
{
    public $method;
    public $header;
    public $cookie;
    public $path;
    public $form;
    public $args;

    public static function wrap()
    {
        $r = new Request();
        $r->args = $_GET;
        $r->form = $_POST;
        $r->method = $_SERVER["REQUEST_METHOD"];
        $r->path = $_SERVER["PHP_SELF"];
        $r->header = getallheaders();
        $r->cookie = $_COOKIE;
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