<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Cores\Core;

$core = new Core();
$core->append_sub_process(function (\App\Http\Request $request, &$data) {
    if ($request->admin_name) {
        $data["admin_name"] = $request->admin_name;
    }
});

$core->as_web_page("login.tpl", ["__title__" => "留言板 - 用户登录"]);
$core->handle_request();