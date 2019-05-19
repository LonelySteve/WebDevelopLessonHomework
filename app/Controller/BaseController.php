<?php

namespace app\controller;

abstract class BaseController
{
    abstract function index($offset, $size);
}