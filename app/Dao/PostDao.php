<?php


namespace App\Dao;

use App\Entity\Post;

class PostDao extends BaseDao
{
    protected const table_name = "posts";
    protected const primary_key_name = "pid";
    protected const field_value_types = [
        "pid" => \PDO::PARAM_INT,
        "name" => \PDO::PARAM_STR,
        "email" => \PDO::PARAM_STR,
        "homepage" => \PDO::PARAM_STR,
        "title" => \PDO::PARAM_STR,
        "content" => \PDO::PARAM_STR,
        "create_time" => \PDO::PARAM_STR,
        "replay" => \PDO::PARAM_STR,
        "replay_aid" => \PDO::PARAM_INT,
        "replay_create_time" => \PDO::PARAM_STR,
        "state" => \PDO::PARAM_INT
    ];

    public function index($page, $size = null)
    {
        $result = (new PostDao($this->db_config))->query($page - 1, $size)->fetchObject(Post::class);

        if (!$result) {
            return [];
        }
        return $result;
    }

    public function append($title, $content, $name, $email, $homepage, $state = 0)
    {
        $sql_builder = $this->get_sql_builder_instance();

        $sql = $sql_builder->insert([
            "pid" => null,
            "name" => $name,
            "email" => $email,
            "title" => $title,
            "content" => $content,
            "homepage" => $homepage,
            "create_time" => time(),
            "replay" => null,
            "replay_aid" => null,
            "replay_create_time" => null,
            "state" => $state,
        ])->dump();

        return $this->execute_sql($sql, $sql_builder->get_values(), self::get_field_value_types());
    }
}