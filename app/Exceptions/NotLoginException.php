<?php


namespace App\Exceptions;


class NotLoginException extends BaseException
{
    protected $default_code = -500;
    protected $message_prefix = "not login error";
}