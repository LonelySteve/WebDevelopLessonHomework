<?php


namespace App\Dao;

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

    public function append(Admin $admin)
    {
        $sql_builder = $this->get_sql_builder_instance();

        $sql = $sql_builder->insert([
            null,
            $admin->username,
            $admin->password
        ])->dump();

        return $this->execute_sql($sql, $sql_builder->get_values(), self::get_field_value_types());
    }
}