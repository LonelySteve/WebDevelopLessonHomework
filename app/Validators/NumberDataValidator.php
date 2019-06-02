<?php

namespace App\Validators;


class NumberDataValidator extends DataValidator
{
    protected static $type = "number";

    function __construct()
    {
        $this->append_callback(function ($data) {
            return preg_match("/^-?([1-9]\d*|0)(\.\d+)?$/", $data);
        });
    }

    function is_integer()
    {
        return $this->append_callback(function ($data) {
            return preg_match("/^-?[1-9]\d*$/", $data);
        });
    }

    function min($min_value)
    {
        return $this->append_callback(function ($data) use ($min_value) {
            return floatval($data) >= $min_value;
        });
    }

    function max($max_value)
    {
        return $this->append_callback(function ($data) use ($max_value) {
            return floatval($data) <= $max_value;
        });
    }
}
