<?php


namespace App\Exceptions;


class MethodNotAllowedException extends BaseException
{
    protected $default_code = -530;
    protected $message_prefix = "Request methods that are not allowed";
}