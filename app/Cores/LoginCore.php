<?php


namespace App\Cores;


use App\Filters\InputFilter;
use App\Filters\MethodFilter;
use App\Http\Request;
use App\Controller\AuthController;
use App\Validators\StringDataValidator;

class LoginCore extends Core
{
    public function __construct()
    {
        $this->filters += [
            new MethodFilter("POST"),
            (new InputFilter(function (Request $r) {
                return $r->form;
            }))
                ->require("username", (new StringDataValidator())->min_len(2)->max_len(50))
                ->require("password", (new StringDataValidator())->min_len(6)->max_len(16)->match_regex("/^[\w_-]{6,16}$/"))
        ];
    }

    function main(Request $request)
    {
        $controller = new AuthController($this->config->db_config);
        $input = $request->get_input();
        return $controller->login($input["username"], $input["password"]);
    }
}