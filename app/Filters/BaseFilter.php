<?php


namespace Filters;

use App\Http\Request;

abstract class BaseFilter
{
    function redirect($path)
    {
        header("Location: $path;");
    }

    function __call(Request $request)
    {
        return true;
    }
}