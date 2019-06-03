<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Cores\PostListCore;


$core = new PostListCore();
$core->as_web_page("index.tpl", ["__title__" => "留言板"]);
$core->handle_request();
