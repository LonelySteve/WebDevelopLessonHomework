<?php


namespace App\Filters;


use App\Http\Request;
use App\Exceptions\AuthException;

class AuthFilter extends BaseFilter
{
    function __construct($login_url, $api_mode = true)
    {

    }

    function __call(Request $request)
    {
        session_start();
        if (isset($_SESSION["uid"])) {
            return true;
        }
        throw new AuthException("");
    }
}