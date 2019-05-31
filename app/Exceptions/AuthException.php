<?php


namespace App\Exceptions;


class AuthException extends BaseException
{
    protected $default_code = -500;
    protected $message_prefix = "unauthorized error";
}