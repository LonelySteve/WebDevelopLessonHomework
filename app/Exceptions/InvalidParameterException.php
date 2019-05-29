<?php


namespace App\Exceptions;


use Throwable;

class InvalidParameterException extends ParameterException
{
    protected $code = -512;
    protected $message = "invalid parameter error!";

    public function __construct($name = null, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($name, $message, $code, $previous);
        if ($this->name) {
            $this->message = "invalid parameter:" . $this->name;
        }
    }
}