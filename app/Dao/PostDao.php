<?php


namespace App\Dao;


class PostDao extends BaseDao
{
    protected const table_name = "posts";
    protected const primary_key_name = "pid";
    protected const field_value_types = [
        "pid" => \PDO::PARAM_INT,
        "name" => \PDO::PARAM_STR,
        "qq" => \PDO::PARAM_STR,
        "email" => \PDO::PARAM_STR,
        "homepage" => \PDO::PARAM_STR,
        "title" => \PDO::PARAM_STR,
        "content" => \PDO::PARAM_STR,
        "create_time" => \PDO::PARAM_STR,
        "reply" => \PDO::PARAM_STR,
        "reply_admin_name" => \PDO::PARAM_STR,
        "reply_create_time" => \PDO::PARAM_STR,
        "state" => \PDO::PARAM_INT
    ];
}