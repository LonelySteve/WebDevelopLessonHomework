<?php


namespace App\Dao;

use App\Entity\Post;

class PostDao extends BaseDao
{
    protected const table_name = "posts";
    protected const primary_key_name = "pid";

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

        return $this->execute_sql($sql, $sql_builder->get_values());
    }
}