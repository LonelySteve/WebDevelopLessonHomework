<?php

require_once __DIR__ . "/../../app/core.php";

use App\Controller\PostController;
use App\Entity\Post;
use  App\Http\Response;
use App\Validators\StringDataValidator;
use App\Filters\InputFilter;


class PostAddCore extends Core
{
    public function __construct()
    {
        $this->FILTERS += [
            (new InputFilter())
                ->require("name", (new StringDataValidator())->min_len(2)->max_len(50))
                ->default("email", (new StringDataValidator())->match_regex("/^[A-Za-z\d]+([-_.][A-Za-z\d]+)*@([A-Za-z\d]+[-.])+[A-Za-z\d]{2,4}$/"))
                ->require("title", (new StringDataValidator())->min_len(2)->max_len(30))
                ->require("content", (new StringDataValidator())->min_len(2)->max_len(65535))
                ->hidden("state", 0)
                ->hidden("create_time", time())
        ];
    }

    function main(\App\Http\Request $request)
    {
        // TODO: Implement main() method.
        $controller = new PostController($this->config->db_config);
        $input = $request->get_input();
        $controller->append(new Post(
                $input["name"],
                $input["email"],
                $input["title"],
                $input["content"],
                $input["create_time"],
                $input["state"])
        );
        $r = new Response();
        $r->jsonify();
    }
}

$core = new PostAddCore();
$core->handle_request();
