<?php

abstract class A
{
    protected const a = array();
}

class B extends A
{
    public static function ec()
    {
        $c = get_called_class();
        var_dump($c::a);
    }
}

class C extends B{
    protected const a = array(123, 456);
}

$b = new C();

$b::ec();