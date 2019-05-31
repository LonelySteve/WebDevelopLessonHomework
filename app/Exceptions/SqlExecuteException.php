<?php


namespace App\Exceptions;


use Throwable;

class SqlExecuteException extends BaseException
{
    protected $code = -100;
    protected $message = "SQL execution error";

    public function __construct($message = "", $code = -1, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}