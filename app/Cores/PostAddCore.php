<?php


namespace App\Cores;


use App\Controller\PostController;
use App\Filters\InputFilter;
use App\Validators\DataValidator;
use App\Validators\StringDataValidator;

class PostAddCore extends Core
{
    public function __construct()
    {
        $this->filters += [
            (new InputFilter())
                ->require("name", (new StringDataValidator())->min_len(2)->max_len(50))
                ->default("email", [
                    (new DataValidator())->is_null(),
                    (new StringDataValidator())->max_len(50)->match_regex("/^[A-Za-z\d]+([-_.][A-Za-z\d]+)*@([A-Za-z\d]+[-.])+[A-Za-z\d]{2,4}$/")
                ])
                ->default("qq", [
                    (new DataValidator())->is_null(),
                    (new StringDataValidator())->min_len(5)->max_len(15)->match_regex("/^\d+$/")])
                ->default("homepage", [
                    (new DataValidator())->is_null(),
                    (new StringDataValidator())->max_len(50)->match_regex("/^((https|http):\/\/)[^\s]+/")
                ])
                ->require("title", (new StringDataValidator())->min_len(2)->max_len(30))
                ->require("content", (new StringDataValidator())->min_len(2)->max_len(233))
                ->hidden("state", 0)
        ];
    }

    function main(\App\Http\Request $request)
    {
        $controller = new PostController($this->config->db_config);
        $input = $request->get_input();
        return $controller->append($input["title"], $input["content"], $input["name"], $input["qq"], $input["email"], $input["homepage"], $input["state"]);
    }
}
