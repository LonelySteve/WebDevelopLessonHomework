<?php

require_once __DIR__ . "/../../../vendor/autoload.php";

use App\Cores\PostListCore;

$core = new PostListCore();

$core->handle_request();
