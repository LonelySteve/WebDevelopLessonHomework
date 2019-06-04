<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Cores\Core;

$core = new Core();
$core->as_web_page("post.tpl",["__title__"=>"留言板 - 我要留言"]);
$core->append_sub_process(function (\App\Http\Request $request, &$data) {
    if ($request->admin_name != null) {
        $data["admin_name"] = $request->admin_name;
    }
});
$core->handle_request();