<?php


namespace App\Cores;


use App\Controller\PostController;
use App\Exceptions\NotFoundException;
use App\Filters\InputFilter;
use App\Filters\LoginFilter;
use App\Validators\NumberDataValidator;
use App\Validators\StringDataValidator;

class PostReplyCore extends Core
{
    public function __construct()
    {
        $this->filters += [
            (new LoginFilter()),
            (new InputFilter())
                ->require("pid", (new NumberDataValidator())->is_integer()->min(1))
                ->require("content", (new StringDataValidator())->min_len(2)->max_len(65535)),
        ];
    }

    function main(\App\Http\Request $request)
    {
        $controller = new PostController($this->config->db_config);
        $input = $request->get_input();

        if ($controller->reply($input["pid"], $request->admin_name, $input["content"]) == 0) {
            throw new NotFoundException();
        }

        return true;
    }
}
