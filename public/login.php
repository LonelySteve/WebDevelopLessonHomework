<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Cores\Core;
use App\Http\Response;

$core = new Core();
$core->as_web_page("login.tpl",["__title__"=>"留言板 - 用户登录"]);
$core->handle_request();