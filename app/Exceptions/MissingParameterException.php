<?php


namespace App\Exceptions;


use Throwable;

class MissingParameterException extends ParameterException
{
    protected $code = -511;
    protected $message = "missing parameter!";

    public function __construct($name = null, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($name, $message, $code, $previous);
        if ($this->name) {
            $this->message = "missing parameter:" . $this->$name;
        }
    }
}