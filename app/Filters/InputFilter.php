<?php


namespace Filters;


use App\Exceptions\MissingParameterException;
use App\Http\Request;
use App\Validators\Validator;

class InputFilter extends BaseFilter
{
    protected $required_params_data;
    protected $default_params_data;

    public function __call(Request $request)
    {
        $input = $request->args + $request->form;
        // 首先检查必要不可省的参数规则
        foreach ($this->required_params_data as $data) {
            $name = $data[0];
            $validator = $data[1];
            if (!isset($input[$name])) {
                throw new MissingParameterException($name);
            }

        }


    }

    public function require($param_name, Validator $validator = null)
    {
        $this->required_params_data[] = [$param_name, $validator];
    }

    public function default($param_name, $validator = null, $default = null)
    {
        $this->default_params_data[] = [$param_name, $validator, $default];
    }
}

class FromInputFilter extends BaseFilter
{

}

class ArgsInputFilter extends BaseFilter
{

}