<?php

namespace App\Util;

class Util
{
    /**
     * 判断指定数组是否为关联数组
     *
     * @param $arr array 欲判断的数组
     * @return bool
     */
    static function array_is_assoc($arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
