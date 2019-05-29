<?php


namespace App\Exceptions;


use Throwable;

class ParameterException extends BaseException
{
    protected $name;
    protected $code = -510;
    protected $message = "parameter error!";

    /**
     * MissingParameterException constructor.
     * @param $name string|null 导致异常的参数名字
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($name = null, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->name = $name;
        parent::__construct($message, $code, $previous);
    }
}
