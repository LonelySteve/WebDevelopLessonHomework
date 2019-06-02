<?php


namespace App\Exceptions;


class MissingParameterException extends ParameterException
{
    protected $default_code = -511;
    protected $message_prefix = "missing parameter";
}