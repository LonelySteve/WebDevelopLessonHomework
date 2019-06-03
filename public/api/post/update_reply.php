<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use App\Cores\PostReplyCore;

$core = new PostReplyCore();
$core->as_api("POST");
$core->handle_request();