<?php


namespace App\Exceptions;

// 这里定义了自定义异常类与默认错误码
const ERROR_CODES = [
    VerificationException::class => [-100, "数据验证失败"],
    AuthException::class => [-403, "登录凭据验证失败"],
    ConfigException::class => [-503, "服务端配置读取失败"]
];


class BaseException extends \Exception
{
    function get_code($default_code = -1)
    {
        $code = $this->getCode();
        if ($code) {
            // 优先返回异常对象的Code
            return $code;
        }
        if (isset(ERROR_CODES[get_class($this)])) {
            return ERROR_CODES[get_class($this)][0];
        }
        return $default_code;
    }

    function get_msg($default_msg = "未知错误")
    {
        $msg = $this->getMessage();
        if ($msg) {
            // 优先返回异常对象的消息
            return $msg;
        }
        if (isset(ERROR_CODES[get_class($this)])) {
            return ERROR_CODES[get_class($this)][1];
        }
        return $default_msg;
    }
}
