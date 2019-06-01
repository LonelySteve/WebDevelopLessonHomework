<?php


namespace App\Filters;


class FormatParameter
{
    public $name;
    public $validators;
    protected $is_hidden;
    protected $is_default;
    protected $value;

    public function __construct($name)
    {
        $this->name = $name;
        $this->validators = [];
        $this->value = null;
        $this->require();
    }

    public function is_hidden()
    {
        return $this->is_hidden;
    }

    public function is_default()
    {
        return $this->is_default;
    }

    public function hide()
    {
        $this->is_hidden = true;
        $this->is_default = false;

        return $this;
    }

    public function default()
    {
        $this->is_hidden = false;
        $this->is_default = true;

        return $this;
    }

    public function require()
    {
        $this->is_hidden = false;
        $this->is_default = false;

        return $this;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_value($value)
    {
        $this->value = $value;

        return $this;
    }

    public function get_value()
    {
        return $this->value;
    }

    public function append_validator($validator)
    {
        $this->validators[] = $validator;

        return $this;
    }

    public function extend_validators(array $validators)
    {
        $this->validators += $validators;

        return $this;
    }

    public function set_default_value($default_value)
    {
        $this->have_default_value = true;
        $this->default = $default_value;

        return $this;
    }
}