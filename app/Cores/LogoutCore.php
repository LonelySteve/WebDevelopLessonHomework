<?php


namespace App\Cores;


use App\Http\Request;
use App\Controller\AuthController;
use App\Http\Response;

class LogoutCore extends BaseCore
{
    function main(Request $request)
    {
        $controller = new AuthController($this->config->db_config);
        $controller->logout();
        (new Response())->jsonify();
    }
}