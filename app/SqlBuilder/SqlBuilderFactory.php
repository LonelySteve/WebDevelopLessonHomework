<?php

namespace App\SqlBuilder;

class SqlBuilderFactory
{
    static function from_type($name)
    {
        switch ($name) {
            case "mysql":
                return MySqlBuilder::class;
            default:
                throw new \InvalidArgumentException("Unsupported database type!");
        }
    }
}