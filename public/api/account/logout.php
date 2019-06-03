<?php

require_once __DIR__ . "/../../../vendor/autoload.php";

use App\Cores\LogoutCore;

$core = new LogoutCore();
$core->as_api("GET");
$core->handle_request();