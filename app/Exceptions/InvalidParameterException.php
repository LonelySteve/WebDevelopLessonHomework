<?php


namespace App\Exceptions;


use Throwable;

class InvalidParameterException extends ParameterException
{
    protected $default_code = -512;
    protected $message_prefix = "invalid parameter error";
}