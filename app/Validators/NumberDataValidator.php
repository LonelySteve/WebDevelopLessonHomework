<?php

namespace App\Validators;


class NumberDataValidator extends DataValidator
{
    protected static $type = "number";

    function __construct()
    {
        $result = [];
        $this->append_callback(function ($data) use ($result) {
            return preg_match("/^-?([1-9]\d*|0)(\.\d+)?$/", $data, $result);
        });
    }

    function min($min_value)
    {
        return $this->append_callback(function ($data) use ($min_value) {
            return $data >= $min_value;
        });
    }

    function max($max_value)
    {
        return $this->append_callback(function ($data) use ($max_value) {
            return $data <= $max_value;
        });
    }
}
