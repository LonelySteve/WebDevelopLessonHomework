<?php


namespace App\Dao;

use App\Cores\PostAddCore;
use App\Entity\Admin;


class AdminDao extends BaseDao
{
    protected const table_name = "admins";
    protected const primary_key_name = "aid";
    protected const field_value_types = [
        "aid" => \PDO::PARAM_INT,
        "username" => \PDO::PARAM_STR,
        "password" => \PDO::PARAM_STR
    ];
}