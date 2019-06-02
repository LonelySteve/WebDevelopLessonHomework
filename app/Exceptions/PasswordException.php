<?php


namespace App\Exceptions;


class PasswordException extends BaseException
{
    protected $default_code = -600;
    protected $message_prefix = "password error";
}