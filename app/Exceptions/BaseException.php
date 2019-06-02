<?php


namespace App\Exceptions;

use Throwable;
use App\Config\Config;

/**
 * BaseException 类是本后台自定义的所有异常基类
 * 继承于\Exception
 * @package App\Exceptions
 */
class BaseException extends \Exception
{
    protected $default_code = -1;
    protected $message_prefix = "unknown error";

    public function __construct($message = "", $code = -1, Throwable $previous = null)
    {
        $config = Config::load();

        if ($config->debug_mode) {
            $message = $this->join_message($message);
        } else {
            $message = "";
        }

        if ($code === -1) {
            $code = $this->default_code;
        }
        parent::__construct($message, $code, $previous);
    }

    protected function join_message($message_content = "")
    {
        return $this->message_prefix . ($message_content ? ": " . strval($message_content) : "") . "!";
    }
}
