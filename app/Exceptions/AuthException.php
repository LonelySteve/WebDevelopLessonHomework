<?php


namespace App\Exceptions;


class AuthException extends BaseException
{
    protected $code = -500;
    protected $message = "unauthorized error!";
}