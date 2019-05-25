<?php

use App\Exceptions\BaseException;

// 这里定义了自定义异常类与默认错误码
const ERROR_CODES = [
    "VerificationException" => [-100],
    "AuthException" => [-403,""]
];

function get_code(BaseException $exception, $default_code = -1)
{
    $code = $exception->getCode();
    if ($code) {
        // 优先返回异常对象的Code
        return $code;
    }
    if (isset(ERROR_CODES[get_class($exception)])) {
        return ERROR_CODES[get_class($exception)];
    }
    return $default_code;
}
