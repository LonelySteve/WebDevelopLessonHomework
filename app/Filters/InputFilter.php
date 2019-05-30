<?php


namespace App\Filters;


use App\Exceptions\InvalidParameterException;
use App\Exceptions\MissingParameterException;
use App\Http\Request;
use App\Validators\DataValidator;

class InputFilter extends BaseFilter
{
    protected $params_data;
    protected $hidden_params_data;

    public function pass(Request $request)
    {
        // 添加隐藏域的值
        foreach ($this->hidden_params_data as $name => $value) {
            $request->set_input($name, $value);
        }
        $input = $request->get_input();
        // 首先检查必要不可省的参数规则
        foreach ($this->params_data as $data) {
            $name = $data[0];
            $validator = $data[1];
            if (!isset($input[$name])) {
                // 有默认值可选时，将数据值置为默认值
                if (isset($data[2])) {
                    $request->set_input($name, $data[2]);
                    $input[$name] = $data[2];
                } else {
                    throw new MissingParameterException($name);
                }
            }
            $result = $validator->verify($input[$name], VERIFY_UNTIL_FAIL);
            // 验证不通过
            if (!$result) {
                throw new InvalidParameterException($name);
            }
        }
        return true;
    }

    public function require($param_name, DataValidator $validator = null)
    {
        $this->params_data[] = [$param_name, $validator];

        return $this;
    }

    public function default($param_name, $validator = null, $default = null)
    {
        $this->params_data[] = [$param_name, $validator, $default];

        return $this;
    }

    public function hidden($param_name, $value)
    {
        $this->hidden_params_data[$param_name] = $value;

        return $this;
    }
}
