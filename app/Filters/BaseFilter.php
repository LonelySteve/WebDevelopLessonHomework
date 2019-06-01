<?php


namespace App\Filters;

use App\Http\Request;

abstract class BaseFilter
{
    function pass(Request $request)
    {
        return true;
    }
}