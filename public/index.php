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
        $page = $request->form["page"];
        $size = $request->form["size"];

        $controller = new PostController();

        $r = new Response();
        $r->jsonify($controller->index($page, $size));
    }

}

$c = new IndexCore();

$c->handle_request();

