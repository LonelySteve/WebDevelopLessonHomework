<?php

require_once __DIR__ . "/../../../vendor/autoload.php";


use App\Cores\PostAddCore;

$core = new PostAddCore();
$core->handle_request();
