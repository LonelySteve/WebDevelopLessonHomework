<?php


namespace App\Validators;

use App\Config\Config;

class Validator
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

    function __call($data)
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
                    throw  $_;
                }
            }
            $result_array[] = $result;
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
                return is_array($this->data);
            });
    }

    function is_number()
    {

        // 返回数值专用的验证器
        return new NumberValidator($data);
    }

    function is_id($dao_cls_name, $construct_params)
    {
        $data = $this->is_number()->get_data();
        return new DaoRecordIdValidator($data, $dao_cls_name, $construct_params);
    }

    function is_string()
    {
        $this->append_callback(function ($data) {
            return is_string($this->data);
        }, "it's not a string");
        // 返回字符串专用的验证器
        return new StringValidator($this->data);
    }

    function is_bool()
    {
        return $this->append_callback(function ($data) {
            return is_bool($this->data);
        }, "it's not a boolean value");
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






class TimestampValidator extends NumberValidator
{
    protected static $type = "timestamp";

    function __call($data)
    {
        $this->is_number()->min(0);
        return parent::__call($data); // TODO: Change the autogenerated stub
    }

    function in_the_past()
    {
        return $this->max(time());
    }

    function in_the_future()
    {
        return $this->min(time());
    }

    function is_latest($allowed_error_range = 5)
    {
        $current_timestamp = time();
        return $this->min($current_timestamp - $allowed_error_range)->max($current_timestamp + $allowed_error_range);
    }
}