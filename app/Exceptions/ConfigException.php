<?php


namespace App\Exceptions;


/**
 * ConfigException
 * 属于非用户操作导致的异常
 * @package App\Exceptions
 */
class ConfigException extends BaseException
{
    protected $code = -10;
    protected $message_prefix = "configuration load error";
}