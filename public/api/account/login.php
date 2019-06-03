<?php

require_once __DIR__ . "/../../../vendor/autoload.php";

use App\Cores\LoginCore;

$core = new LoginCore();
$core->as_api("POST");
$core->handle_request();