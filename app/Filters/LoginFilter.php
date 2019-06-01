<?php


namespace App\Filters;


use App\Http\Request;
use App\Dao\AdminDao;
use App\Exceptions\NotLoginException;

class LoginFilter extends BaseFilter
{
    function pass(Request $request)
    {
        if ($request->aid == null) {
            throw new NotLoginException();
        }
        return true;
    }
}