<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Cores\PostDeleteCore;

$core = new PostDeleteCore();

$core->handle_request();