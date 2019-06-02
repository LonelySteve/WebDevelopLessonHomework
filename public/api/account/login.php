<?php

require_once __DIR__ . "/../../../vendor/autoload.php";

use App\Cores\LoginCore;

$core = new LoginCore();
$core->handle_request();