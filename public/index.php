<?php

require_once __DIR__.'/../vendor/autoload.php';

use App\Http\Request;
use App\Config\Config;

$conf = Config::from_dot_env();

$r = new Request();

var_dump($r);