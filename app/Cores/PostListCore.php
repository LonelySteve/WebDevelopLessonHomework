<?php


namespace App\Cores;


use App\Controller\PostController;
use App\Filters\InputFilter;
use App\Validators\NumberDataValidator;

class PostListCore extends Core
{
    function __construct()
    {
        $this->filters += [
            (new InputFilter())
                ->default("page", (new NumberDataValidator())->is_integer()->min(1), 1)
                ->default("size", (new NumberDataValidator())->is_integer()->min(10)->max(50), 10)
        ];
    }

    function main(\App\Http\Request $request)
    {
        $controller = new PostController($this->config->db_config);

        $input = $request->get_input();

        return $controller->index($input["page"], $input["size"]);
    }
}