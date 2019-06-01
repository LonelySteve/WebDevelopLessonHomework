<?php


namespace App\Cores;


use App\Controller\PostController;
use App\Filters\InputFilter;
use App\Http\Response;
use App\Validators\DataValidator;
use App\Validators\StringDataValidator;

class PostAddCore extends BaseCore
{
    public function __construct()
    {
        $this->FILTERS += [
            (new InputFilter())
                ->require("name", (new StringDataValidator())->min_len(2)->max_len(50))
                ->default("email", [
                    (new DataValidator())->is_null(),
                    (new StringDataValidator())->match_regex("/^[A-Za-z\d]+([-_.][A-Za-z\d]+)*@([A-Za-z\d]+[-.])+[A-Za-z\d]{2,4}$/")
                ])
                ->default("homepage", [
                    (new DataValidator())->is_null(),
                    (new StringDataValidator())->match_regex("/^((https|http)?:\/\/)[^\s]+/")
                ])
                ->require("title", (new StringDataValidator())->min_len(2)->max_len(30))
                ->require("content", (new StringDataValidator())->min_len(2)->max_len(65535))
                ->hidden("state", 0)
        ];
    }

    function main(\App\Http\Request $request)
    {
        $controller = new PostController($this->config->db_config);
        $input = $request->get_input();
        $last_id = $controller->append($input["title"], $input["content"], $input["name"], $input["email"], $input["homepage"], $input["state"]);
        $r = new Response();
        $r->jsonify(["pid" => $last_id]);
    }
}
