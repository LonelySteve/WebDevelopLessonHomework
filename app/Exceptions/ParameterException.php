<?php


namespace App\Exceptions;


use Throwable;

class ParameterException extends BaseException
{
    protected $name;
    protected $default_code = -510;
    protected $message_prefix = "parameter error";

    /**
     * MissingParameterException constructor.
     * @param $name string|null 导致异常的参数名字
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($name = null, $message = "", $code = -1, Throwable $previous = null)
    {
        $this->name = $name;
        $this->message .= " because of '$name'";
        parent::__construct($message, $code, $previous);
    }
}
