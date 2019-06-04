<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Cores\LogoutCore;

$core = new LogoutCore();
$core->as_web_page("info.tpl", ["message" => "登出成功！"]);
$core->handle_request();