<?php


namespace App\Validators;



class StringDataValidator extends DataValidator
{
    function match_regex($regex)
    {
        return $this->append_callback(function ($data) use ($regex) {
            return preg_match($regex, $data);
        });
    }

    function min_len($min_len)
    {
        return $this->append_callback(function ($data) use ($min_len) {
            return strlen($data) >= $min_len;
        });
    }

    function max_len($max_len)
    {
        return $this->append_callback(function ($data) use ($max_len) {
            return strlen($data) <= $max_len;
        });
    }
}
