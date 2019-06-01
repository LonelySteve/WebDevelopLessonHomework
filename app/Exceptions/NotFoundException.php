<?php


namespace App\Exceptions;


class NotFoundException extends BaseException
{
    protected $default_code = -520;
    protected $message_prefix = "not found";
}