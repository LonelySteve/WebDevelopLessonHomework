<?php

require_once __DIR__ . "/../app/core.php";

use App\Http\Response;
use App\Filters\InputFilter;
use App\Validators\NumberDataValidator;
use App\Controller\PostController;

class IndexCore extends Core
{
    function __construct()
    {
        $this->FILTERS += [
            (new InputFilter())
                ->default("p", (new NumberDataValidator())->is_integer()->min(1), 1)
                ->default("size", (new NumberDataValidator())->is_integer()->min(10), 10)
        ];
    }

    function main(\App\Http\Request $request)
    {
        $controller = new PostController($this->config->db_config);

        $input = $request->get_input();

        $data = $controller->index($input["p"], $input["size"]);
        $r = new Response();
        $r->jsonify($data);
    }
}

$c = new IndexCore();

$c->handle_request();

