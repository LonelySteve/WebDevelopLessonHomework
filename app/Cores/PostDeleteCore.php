<?php


namespace App\Cores;


use App\Controller\PostController;
use App\Exceptions\NotFoundException;
use App\Filters\InputFilter;
use App\Filters\LoginFilter;
use App\Validators\NumberDataValidator;

class PostDeleteCore extends Core
{
    public function __construct()
    {
        $this->filters += [
            (new LoginFilter()),
            (new InputFilter())
                ->require("pid", (new NumberDataValidator())->is_integer()->min(1))
        ];
    }

    function main(\App\Http\Request $request)
    {
        $controller = new PostController($this->config->db_config);
        $input = $request->get_input();

        if ($controller->delete($input["pid"]) == 0) {
            throw new NotFoundException();
        }

        return true;
    }
}