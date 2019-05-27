<?php

require_once __DIR__ . "/../app/core.php";

use App\Http\Response;
use App\Controller\PostController;

class IndexCore extends Core
{
    public $FILTERS = [

    ];

    function main(\App\Http\Request $request)
    {
        $controller = new PostController($this->config->db_config);

        $r = new Response();
        $data = $controller->index(0, 10);
        $r->jsonify($data);
    }

}

$c = new IndexCore();

$c->handle_request();

