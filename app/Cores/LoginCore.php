<?php


namespace App\Cores;


use App\Filters\InputFilter;
use App\Http\Request;
use App\Controller\AuthController;
use App\Http\Response;
use App\Validators\StringDataValidator;

class LoginCore extends BaseCore
{
    public function __construct()
    {
        $this->FILTERS += [
            (new InputFilter())
                ->require("username", (new StringDataValidator())->min_len(2)->max_len(50))
                ->require("password", (new StringDataValidator())->min_len(6)->max_len(16)->match_regex("/^[\w_-]{6,16}$/"))
        ];
    }

    function main(Request $request)
    {
        $controller = new AuthController($this->config->db_config);
        $input = $request->get_input();
        $controller->login($input["username"], $input["password"]);
        (new Response())->jsonify();
    }
}