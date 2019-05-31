<?php


namespace App\Exceptions;


use Throwable;

class MissingParameterException extends ParameterException
{
    protected $default_code = -511;
    protected $message_prefix = "missing parameter";
}