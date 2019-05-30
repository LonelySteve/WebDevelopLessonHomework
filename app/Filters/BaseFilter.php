<?php


namespace App\Filters;

use App\Http\Request;

abstract class BaseFilter
{
    function redirect($path)
    {
        header("Location: $path;");
    }

    function pass(Request $request)
    {
        return true;
    }
}