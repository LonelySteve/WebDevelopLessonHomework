<?php


namespace App\Exceptions;


use Throwable;

class VerificationException extends \RuntimeException
{
    public static $err_code;

    public $type;
    public $params;

    public function __construct($message = "", $type = null, $params = null, Throwable $previous = null)
    {
        $this->type = $type;
        $this->params = $params ?: array();
        parent::__construct($message, self::$err_code, $previous);
    }
}

VerificationException::$err_code = -5;