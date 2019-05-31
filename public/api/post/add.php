<?php

require_once __DIR__ . "/../../../app/core.php";

use App\Controller\PostController;
use App\Entity\Post;
use  App\Http\Response;
use App\Validators\StringDataValidator;
use App\Validators\DataValidator;
use App\Filters\InputFilter;


class PostAddCore extends Core
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
        $controller->append($input["title"], $input["content"], $input["name"], $input["email"], $input["homepage"], $input["state"]);
        $r = new Response();
        $r->jsonify();
    }
}

$core = new PostAddCore();
$core->handle_request();
