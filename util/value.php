<?php


namespace JLoeve\BBS\util\value;

use JLoeve\BBS\exceptions as ex;

/**
 * Interface IValue
 * @package JLoeve\BBS\util\value
 */
interface IValue
{

    /**
     * 将指定值按指定类型名进行类型转换
     * -----------------------------------------
     * @param $value mixed 欲转换类型的变量
     * @param $type string 目标类型名
     * @param null $success 获取是否类型转换成功的引用变量
     * @return mixed 成功返回转换后的值，失败返回原值
     */
    function convert_by_type($value, $type, &$success = null);

    function is_in_range($value, $start_value = null, $end_value = null);

    function is_in_array($value, $arr);

    function set_prop($value,$r);

    /**
     * 使用对象约束语言对值进行类型转换以及有效性检验
     * -----------------------------------------
     * 如果指定值无法通过类型转换以及有效性校验，将抛出相应异常
     *
     * ## 对象约束语言（OCL） 语法概要：
     *
     * 最简单的变量描述规则字符串就是单个类型名，比如"string"或"int"，它们分别表示这个值应当以字符串或整数类型储存。
     *
     * 可以通过在类型名后加上一个圆括弧以使用扩展的类型描述，这可以做到范围约束或指定特定属性值。
     *
     * 1. 范围约束语法：<type_name>([start_value]...[end_value])
     *
     * 其中start_value表示范围的开始，end_value表示范围的结束，这是一个前闭后闭的区间，且两个参数均可省，
     * 省略表示无穷，如果是start_value被省略，表示负无穷大，如果是end_value被省略表示正无穷大，如果两者
     * 都被省略，表示不限定范围。
     *
     * 例如： "int(512...1024)" 表示一个不小于512且不大于1024的整数值，"string(...32)" 表示长度不大于32的字符串
     *
     * 2. 可选值语法：<type_name>([value1],[value2],[value3])
     *
     * 只有当值等于value1，value2或value3时，指定值才被判定有效。值得注意的是，可选值的数量是不限定的，可选值将会转换为同类型的值后进行
     * 比较。
     *
     * 例如："string(a,b,c)" 表示一个值为"a","b"或"c"的字符串
     *
     * 注意：当字符串内容与本语法冲突，则将先尝试以本语法解析，如果不成功则将当作字符串处理。但可以通过向字符串两端包围双引号的方式来强制
     * 指定该参数为字符串，如果想表示的字符串中出现了双引号，则需要用'\'转义双引号 TODO 双引号表示字符串以及转义待实现
     *
     * 另外，此语法可与范围约束语法配合使用，比如 "int(1...10,20...30)" 表示一个不小于1且不大于10或者不小于20且不大于30的整数值
     *
     * 3. 属性值指定语法（待实现）：<type_name>(<prop_name>:<prop_value>)
     *
     *
     *
     * 例如："password(encrypt_method=md5)" 表示以md5的方式对明文密码进行加密
     * "string(re:\d+)" 表示一个长度不小于1，可以转换为数值的字符串
     * "string(replace:(\d+$0))" 表示提取长度不小于1，可以转换为数值的字符串
     *
     * 上述的语法可以组合使用，即用空白符（空白，制表符，回车等）分隔各个类型声明。程序将依次尝试按照指定规则进行转换或校验
     *
     * @param $value mixed 欲转换的变量
     * @param null|string $ocl 对象约束语言字符串
     * @return mixed 通过类型转换以及有效性校验的变量
     */
    function convert_by_ocl($value, $ocl = null);
}

class Value
{
    protected $value;
    protected $desc;

    public function __construct($value, $desc)
    {
        $this->value = $this->convert_value_by_desc($value, $desc);
        $this->desc = $desc;
    }


    /**
     *
     * 将指定值按指定类型名进行类型转换
     *
     * @param $value mixed 欲转换类型的变量
     * @param $type string 目标类型名
     * @param null $success 获取是否类型转换成功的引用变量
     * @return mixed 成功返回转换后的值，失败返回原值
     */
    protected function convert_value_by_type($value, $type, &$success = null)
    {
        $ret_val = clone $value;
        $success = boolval(settype($ret_val, $type));
        return $ret_val;
    }


    /**
     * 将指定值转换为期待类型，如果无法转换将抛出异常。
     *
     * 对于类型描述字符串，可以使用空格分隔多个类型名，这样做的效果是优先以最先的类型储存和获取值
     * 如 "int string" 在储存值时，会优先以int类型储存值，如果目标值不能够转换为int类型，则将以string储存目标值
     *
     * 每种类型还可以用圆括弧语法描述值的范围，语法有两个：
     *
     * 1. (start_val...end_val) 从start_val开始到end_val结束，包括end_val，对于字符串类型，这是指其长度
     * 如 int(1...5) 在储存值时，优先以int类型储存值，仅允许值为1~5的量
     * start_val与end_val均可省，表无穷
     *
     * 2. (val1,val2,val3) 从val1，val2和val3中选取一个，对于字符串类型，必须将值用双引号包起来
     * 如 string(aaa,bbb,0) 在储存值时，优先以string类型储存值，仅允许值为"aaa","bbb"和"0"的量
     *
     * @param $value mixed 欲进行类型转换的值
     * @param $desc string|null 类型描述字符串
     * @return mixed
     */
    protected function convert_value_by_desc($value, $desc)
    {
        if (!$desc) {
            return $value;
        }

        $types = explode(" ", $desc);
        // 循环尝试转换目标变量类型，直到成功
        foreach ($types as $type) {
            if (preg_match("(\w+)\((.*?)\)", $type, $matches)) {
                // 首先转换类型
                $type_name = $matches[1];
                $value = $this->convert_value_by_type($value, $type_name, $success);
                if (!$success) {
                    continue;
                }
                // 再处理值的有效范围问题
                $type_desp = $matches[2];
                $part_vals = explode(",", $type_desp);
                foreach ($part_vals as $val) {
                    $range_val = explode("...", $val);
                    switch (count($range_val)) {
                        case 1:
                            if ($value === $this->convert_value_by_type($range_val[0], $type_name))
                                return $value;
                            break;
                        case 2:
                            if ($this->value_in_range($value, $type_name, $range_val[0], $range_val[1]))
                                return $value;
                            break;
                        default:
                            throw new ex\SyntaxErrorException("Unsupported type description statement:" . $type_desp);
                    }
                }
            }

            if (settype($value, $type)) {
                return $value;
            }
        }
        throw new ex\ParamTypeException($value . " cannot be converted to " . $desc);
    }


    public function get($except_types = null)
    {
        if ($except_types) {
            $ret_val = clone $this->value;
            return $this->convert_value_by_desc($ret_val, $except_types);
        }
        return $this->value;
    }

    public function toString()
    {
        return $this->get("string");
    }

    public function toInt()
    {
        return $this->get("int");
    }

    public function toFloat()
    {
        return $this->get("float");
    }

    public function toBool()
    {
        return $this->get("bool");
    }

    public function toObject()
    {
        return $this->get("object");
    }

    public function toArray()
    {
        return $this->get("array");
    }

    public function toNull()
    {
        return $this->get("null");
    }
}


class ConstValue extends Value
{
    protected function value_in_range($value, $type, $start_value, $end_value)
    {
        throw new ex\UnImplementedException("Range syntax is not supported for constant values!");
    }
}

class NullValue extends ConstValue
{
    public function __construct()
    {
        parent::__construct(null);
    }
}

class VariableValue extends ConstValue
{
    public $var_types;

    /**
     * VariableValue constructor.
     *
     * @param $value mixed 欲储存的值
     * @param $types string 类型描述字符串
     */
    public function __construct($value, $types)
    {
        parent::__construct($value);
        $this->var_types = $types;
    }


    protected function value_in_range($value, $type, $start_value, $end_value)
    {

        switch ($type) {
            case "int":
            case "integer":
            case "float":
            case "double":
            case "bool":
            case "boolean":
                $value = $this->convert_value_by_type($value, $type);
                if ($start_value && $end_value) {
                    $start_value = $this->convert_value_by_type($start_value, $type);
                    $end_value = $this->convert_value_by_type($end_value, $type);
                    return $start_value <= $value && $value <= $end_value;
                } else if ($start_value) {
                    $end_value = $this->convert_value_by_type($end_value, $type);
                    return $value <= $end_value;
                } else if ($end_value) {
                    $start_value = $this->convert_value_by_type($start_value, $type);
                    return $start_value <= $value;
                } else {
                    return true;
                }
            case "string":
                $str_len = strlen($this->convert_value_by_type($value, $type));
                if ($start_value && $end_value) {
                    $start_value = intval($start_value);
                    $end_value = intval($end_value);
                    return $start_value <= $str_len && $str_len <= $end_value;
                } else if ($start_value) {
                    $start_value = intval($start_value);
                    return $start_value <= $str_len;
                } else if ($end_value) {
                    $end_value = intval($end_value);
                    return $str_len <= $end_value;
                } else {
                    return true;
                }
            case "array":
                $array_count = count($this->convert_value_by_type($value, $type));
                if ($start_value && $end_value) {
                    $start_value = intval($start_value);
                    $end_value = intval($end_value);
                    return $start_value <= $array_count && $array_count <= $end_value;
                } else if ($start_value) {
                    $start_value = intval($start_value);

                    return $start_value <= $array_count;
                } else if ($end_value) {
                    $end_value = intval($end_value);
                    return $array_count <= $end_value;
                } else {
                    return true;
                }
            case "object":
                return true;
            default:
                throw  new ex\UnImplementedException("Unsupported type!");
        }
    }

    public function set($value, $raw = false)
    {
        if (!$raw) {
            $value = $this->convert_type($value, $this->var_types);
        }
        $this->value = $value;
    }
}

class IntValue extends VariableValue
{
    public function __construct($value, $types = "int")
    {
        parent::__construct($value, $types);
    }
}

class StringValue extends VariableValue
{
    public function __construct($value, $types = "string")
    {
        parent::__construct($value, $types);
    }
}

class FloatValue extends VariableValue
{
    public function __construct($value, $types = "float")
    {
        parent::__construct($value, $types);
    }
}

class BoolValue extends VariableValue
{
    public function __construct($value, $types = "bool")
    {
        parent::__construct($value, $types);
    }
}

class PasswordValue extends StringValue
{
    public function __construct($value, $types = "password")
    {
        parent::__construct($value, $types);
    }

    protected function convert_value_by_type($value, $type, &$success = null)
    {
        if ($type === "password") {
            $success = true;
            return md5($value);
        }
        return parent::convert_value_by_type($value, $type, $success);
    }

    protected function value_in_range($value, $type, $start_value, $end_value)
    {
        throw new ex\UnImplementedException("Description is not supported for passwords!");
    }
}

class EmailValue extends StringValue
{
    public function __construct($value, $types = "email")
    {
        parent::__construct($value, $types);
    }

    protected function convert_value_by_type($value, $type, &$success = null)
    {
        if ($type === "email") {
            $success = preg_match("^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$", $value);
            return strval($value);
        }
        return parent::convert_value_by_type($value, $type, $success);

    }

    protected function value_in_range($value, $type, $start_value, $end_value)
    {
        throw new ex\UnImplementedException("Description is not supported for email!");
    }
}

class HomePageValue extends StringValue
{
    public function __construct($value, $types = "homepage")
    {
        parent::__construct($value, $types);
    }

    protected function convert_value_by_type($value, $type, &$success = null)
    {
        if ($type === "homepage") {
            $success = preg_match("^[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$", $value);
            return strval($value);
        }
        return parent::convert_value_by_type($value, $type, $success); // TODO: Change the autogenerated stub
    }

    protected function value_in_range($value, $type, $start_value, $end_value)
    {
        throw new ex\UnImplementedException("Description is not supported for homepage!");
    }
}

class DateTimeValue extends StringValue
{
    public function __construct($value, $types = "datetime")
    {
        parent::__construct($value, $types);
    }

    protected function convert_value_by_type($value, $type, &$success = null)
    {
        $success = false;
        if ($type === "datetime") {
            if (settype($value, "int")) {
                // 可转换为数字的视作 Unix 时间戳
                $success = true;
                return $value;
            }
            $result = strtotime($value);
            if ($result !== -1) {
                $success = true;
                return $result;
            }
            return $value;
        }
        return parent::convert_value_by_type($value, $type);
    }

    protected function value_in_range($value, $type, $start_value, $end_value)
    {
        throw new ex\UnImplementedException("Description is not supported for DateTime!");
    }

    function toDateTimeString()
    {
        return date("Y-m-d H:i:s", $this->value);
    }
}