<?php

require_once __DIR__ . "/../../../vendor/autoload.php";

use App\Cores\LogoutCore;

$core = new LogoutCore();
$core->handle_request();