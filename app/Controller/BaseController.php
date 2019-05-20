<?php

namespace App\Controller;

abstract class BaseController
{
    abstract function index($offset, $size);
}