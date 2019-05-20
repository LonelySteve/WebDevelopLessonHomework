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