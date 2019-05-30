<?php


namespace App\Dao;

use App\Entity\Post;

class PostDao extends BaseDao
{
    protected const table_name = "posts";
    protected const primary_key_name = "pid";
    protected const field_value_types = [
        "name" => \PDO::PARAM_STR,
        "email" => \PDO::PARAM_STR,
        "title" => \PDO::PARAM_STR,
        "content" => \PDO::PARAM_STR,
        "create_time" => \PDO::PARAM_STR,
        "replay" => \PDO::PARAM_STR,
        "replay_aid" => \PDO::PARAM_INT,
        "replay_create_time" => \PDO::PARAM_STR,
        "state" => \PDO::PARAM_INT
    ];

    public function append(Post $post)
    {
        $sql_builder = $this->get_sql_builder_instance();

        $sql = $sql_builder->insert([
            null,
            $post->name,
            $post->email,
            $post->title,
            $post->content,
            $post->create_time,
            $post->replay,
            $post->replay_aid,
            $post->replay_create_time,
            $post->state
        ])->dump();

        return $this->execute_sql($sql, $sql_builder->get_values(), self::get_field_value_types());
    }
}