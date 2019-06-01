<?php


namespace App\Filters;


use App\Exceptions\InvalidParameterException;
use App\Exceptions\MissingParameterException;
use App\Http\Request;
use App\Validators\DataValidator;

class InputFilter extends BaseFilter
{
    protected $input_selector;

    public function __construct(\Closure $input_selector = null)
    {
        if (!is_callable($input_selector)) {
            $input_selector = function (Request $request) {
                return $request->get_input();
            };
        }
        $this->input_selector = $input_selector;
    }

    protected $format_params = [];

    public function pass(Request $request)
    {
        $input = ($this->input_selector)($request);

        foreach ($this->format_params as $p) {
            $name = $p->get_name();
            // 添加隐藏域的值
            if ($p->is_hidden()) {
                $request->set_input($p->get_name(), $p->get_value());
                continue;
            }
            // 当输入参数中没有指定键或者指定键值为null时
            if (!isset($input[$name])) {
                // 有默认值可选时，将数据值置为默认值
                if ($p->is_default()) {
                    $value = $p->get_value();
                    $request->set_input($name, $value);
                    // 就算是默认值也要通过所有数据验证
                    $input[$name] = $value;
                } else {
                    throw new MissingParameterException($name);
                }
            }
            $result = [];
            foreach ($p->validators as $validator) {
                // 验证参数的有效性，一旦失败便返回
                $result[] = $validator->verify($input[$name], VERIFY_UNTIL_FAIL);
            }
            // 如果没有任何一个验证结果为真，那么该参数验证不通过
            if (!DataValidator::any($result)) {
                throw new InvalidParameterException($name);
            }
        }
        return true;
    }

    public function require($param_name, $validators = null)
    {
        if (!$validators) {
            $validators = [];
        }
        if (!is_array($validators)) {
            $validators = [$validators];
        }
        $p = new FormatParameter($param_name);
        $p->extend_validators($validators);

        return $this->any($p);
    }

    public function default($param_name, $validators = null, $default = null)
    {
        if (!$validators) {
            $validators = [];
        }
        if (!is_array($validators)) {
            $validators = [$validators];
        }
        $p = new FormatParameter($param_name);
        $p->set_value($default)
            ->default()
            ->extend_validators($validators);

        return $this->any($p);
    }

    public function hidden($param_name, $value)
    {
        $p = new FormatParameter($param_name);
        $p->set_value($value)
            ->hide();

        return $this->any($p);
    }

    public function any(FormatParameter $format_param)
    {
        $this->format_params[] = $format_param;

        return $this;
    }
}

