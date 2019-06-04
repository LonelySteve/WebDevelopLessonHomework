<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Cores\InfoCore;

$core = new InfoCore();
$core->method(["GET", "POST"], function (\App\Http\Response $response) {
    $response->render("info.tpl", $response->data);
});
$core->handle_request();