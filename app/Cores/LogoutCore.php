<?php


namespace App\Cores;


use App\Http\Request;
use App\Controller\AuthController;

class LogoutCore extends Core
{
    function main(Request $request)
    {
        $controller = new AuthController($this->config->db_config);
        return $controller->logout();
    }
}