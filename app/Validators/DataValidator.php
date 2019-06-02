<?php


namespace App\Validators;

use App\Config\Config;

define("VERIFY_ALL", 0);
define("VERIFY_UNTIL_FAIL", 1);

class DataValidator
{
    protected $callback_stack = [];

    static function any($data)
    {
        foreach ($data as $item) {
            if ($item) {
                return true;
            }
        }
        return false;
    }

    static function all($data)
    {
        foreach ($data as $item) {
            if (!$item) {
                return false;
            }
        }
        return true;
    }
    // TODO 添加抛出异常的检验模式
    function verify($data, $mode = VERIFY_ALL)
    {
        foreach ($this->callback_stack as $callback) {
            try {
                $result = $callback($data);
            } catch (\Throwable $_) {
                $result = false;
                // 调试模式下应该关心回调异常是如何发生的
                // 理论上这里的代码不应该被执行到，回调函数理想情况下只返回布尔值
                $conf = Config::load();
                if ($conf->debug_mode) {
                    throw $_;
                }
            }
            if ($mode == VERIFY_UNTIL_FAIL && !$result) {
                return false;
            }
            $result_array[] = $result;
        }
        if ($mode == VERIFY_UNTIL_FAIL) {
            return true;
        }
        return $result_array;
    }

    function append_callback(callable $callback)
    {
        $this->callback_stack[] = $callback;

        return $this;
    }

    function equal($value, $strict = true)
    {
        return $this->append_callback(function ($data) use ($value, $strict) {
            return $strict ? $data === $value : $data == $value;
        });
    }

    function is_array()
    {
        return $this->append_callback(
            function ($data) {
                return is_array($data);
            });
    }

    function is_bool()
    {
        return $this->append_callback(function ($data) {
            return is_bool($data);
        });
    }


    function is_null()
    {
        return $this->append_callback(function ($data) {
            return is_null($data);
        });
    }

    function in_array(array $choices)
    {
        return $this->append_callback(function ($data) use ($choices) {
            return in_array($data, $choices, true);
        });
    }

    function no_empty()
    {
        return $this->append_callback(function ($data) {
            return !($data);
        });
    }

    function no_null()
    {
        return $this->append_callback(function ($data) {
            return !is_null($data);
        });
    }
}
