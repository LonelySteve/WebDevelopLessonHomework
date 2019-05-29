<?php


namespace App\Exceptions;

/**
 * BaseException 类是本后台自定义的所有异常基类
 * 继承于\Exception
 * @package App\Exceptions
 */
class BaseException extends \Exception
{
    protected $code = -1;
    protected $message = "unknown error!";
}
