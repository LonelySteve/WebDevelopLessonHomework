<?php


namespace App\Validators;

use App\Dao\BaseDao;
use app\exceptions\VerificationException;

class Validator
{
    protected $data;
    protected static $type = null;


    public function __construct($data)
    {
        $this->data = $data;
    }

    protected function fail($name, $err_msg = null, $params = null, $type = null)
    {
        $type = $type ?: self::$type;
        throw new VerificationException("$name unable to pass validation" . $err_msg ? ":$err_msg." : ".", $type, $params);
    }

    function assert_callback(callable $callback, $err_msg = null, $params = null, $type = null)
    {
        if (!$callback()) {
            $this->fail($this->data, $err_msg, $type, $params);
        }

        return $this;
    }

    function get_data()
    {
        return $this->data;
    }
}

class RequestValidator extends Validator
{
    protected static $type = "request";

    function is_method($name)
    {
        return $this->assert_callback(function () {
        }, "request method not allowed: " . $this->data->method, func_get_args());
    }

    function in_methods($names)
    {
        return $this->assert_callback(function () use ($names) {
            return in_array($this->data->method, $names, true);
        }, sprintf("request method not one of [%s]", implode(', ', $names)), func_get_args());
    }

    function form_required($param_name)
    {
        $this->assert_callback(function () use ($param_name) {
            return in_array($param_name, $this->data->form, true);
        }, "required post param:$param_name", func_get_args());

        return new DataValidator($this->data->form[$param_name]);
    }

    function args_required($param_name)
    {
        $this->assert_callback(function () use ($param_name) {
            return in_array($param_name, $this->data->args, true);
        }, "required post param:$param_name", func_get_args());

        return new DataValidator($this->data->args[$param_name]);
    }
}

class DataValidator extends Validator
{
    protected static $type = "data";

    function equal($value, $strict = true)
    {
        return $this->assert_callback(function () use ($value, $strict) {
            return $strict ? $this->data === $value : $this->data == $value;
        }, "it has to be equal to $value", func_get_args());
    }

    function is_array()
    {
        return $this->assert_callback(
            function () {
                return is_array($this->data);
            },
            "it's not an array"
        );
    }

    function is_number()
    {
        $result = array();
        $this->assert_callback(
            function () use ($result) {
                return preg_match("^-?([1-9]\d*|0)(\.\d+)?$", $this->data, $result);
            },
            "it's not a number"
        );
        // 在确定数据字面量是数的前提下，对其进行类型转换
        if (isset($result[1])) {
            $data = floatval($this->data);
        } else {
            $data = intval($this->data);
        }
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
        $this->assert_callback(function () {
            return is_string($this->data);
        }, "it's not a string");
        // 返回字符串专用的验证器
        return new StringValidator($this->data);
    }

    function is_bool()
    {
        return $this->assert_callback(function () {
            return is_bool($this->data);
        }, "it's not a boolean value");
    }

    function is_timestamp()
    {
        $timestamp = $this->is_number()->min(0)->get_data();
        return new TimestampValidator($timestamp);
    }

    function is_null()
    {
        return $this->assert_callback(function () {
            return is_null($this->data);
        }, "it's not a null value");
    }

    function in_array(array $choices)
    {
        return $this->assert_callback(
            function ($value) use ($choices) {
                return in_array($value, $choices, true);
            },
            sprintf("it's not one of [%s]", implode(', ', $choices)),
            func_get_args()
        );
    }

    function no_empty()
    {
        return $this->assert_callback(function () {
            return !($this->data);
        }, "it's a empty value");
    }

    function no_null()
    {
        return $this->assert_callback(function () {
            return !is_null($this->data);
        }, "it's a null value");
    }
}

class NumberValidator extends Validator
{
    protected static $type = "number";

    function min($min_value)
    {
        return $this->assert_callback(function () use ($min_value) {
            return $this->data >= $min_value;
        }, "it has to be greater than or equal to $min_value",
            func_get_args());
    }

    function max($max_value)
    {
        return $this->assert_callback(function () use ($max_value) {
            return $this->data <= $max_value;
        }, "it has to be less than or equal to $max_value",
            func_get_args());
    }
}

class DaoRecordIdValidator extends NumberValidator
{
    public $dao_instance;

    protected static $type = "id";

    public function __construct($data, $dao_cls_name, $construct_params)
    {
        $construct_params = $construct_params ?: [];
        $this->dao_instance = new $dao_cls_name(...$construct_params);
        parent::__construct($data);
    }

    public function exist()
    {
        return $this->assert_callback(function () {
            return $this->dao_instance->exist($this->data);
        }, "specifies that the id record does not exist!");
    }
}

class StringValidator extends Validator
{
    protected static $type = "string";

    function match_regex($regex)
    {
        return $this->assert_callback(function () use ($regex) {
            return preg_match($regex, $this->data);
        }, "does not match the specified regular expression"
            , func_get_args(), "regex");
    }

    function min_len($min_len)
    {
        return $this->assert_callback(function () use ($min_len) {
            return strlen($this->data) >= $min_len;
        }, "the string length must be greater than or equal to $min_len", func_get_args());
    }

    function max_len($max_len)
    {
        return $this->assert_callback(function () use ($max_len) {
            return strlen($this->data) <= $max_len;
        }, "the string length must be less than or equal to $max_len", func_get_args());

    }
}

class TimestampValidator extends NumberValidator
{
    protected static $type = "timestamp";

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