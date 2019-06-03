<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Cores\PostDeleteCore;

$core = new PostDeleteCore();
$core->as_api(["GET", "POST"]);
$core->handle_request();