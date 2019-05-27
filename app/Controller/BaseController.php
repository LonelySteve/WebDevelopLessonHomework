<?php

namespace App\Controller;

use App\Config\DBConfig;

abstract class BaseController
{
    protected $db_config;

    public function __construct(DBConfig $db_config)
    {
        $this->db_config = $db_config;
    }

    abstract function index($offset, $size);
}