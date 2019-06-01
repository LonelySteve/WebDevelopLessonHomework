<?php

namespace App\Controller;

use App\Dao\PostDao;
use App\Entity\Post;

class PostController extends BaseController
{
    function append($title, $content, $name, $email, $homepage, $state = 0)
    {
        $dao = new PostDao($this->db_config);

        $pdo = $dao->get_pdo_instance();
        $sql_builder = $dao->get_sql_builder_instance();

        $dao->insert([
            "pid" => null,
            "name" => $name,
            "email" => $email,
            "title" => $title,
            "content" => $content,
            "homepage" => $homepage,
            "create_time" => $sql_builder->_date(),
            "replay" => null,
            "replay_aid" => null,
            "replay_create_time" => null,
            "state" => $state,
        ]);

        return $pdo->lastInsertId();
    }

    function delete($pid)
    {
        return (new PostDao($this->db_config))->delete($pid)->rowCount();
    }

    function index($page, $size = null)
    {
        $results = [];
        $stat = (new PostDao($this->db_config))->query($page - 1, $size);

        while ($result = $stat->fetchObject(Post::class)) {
            $results[] = $result;
        }

        return $results;
    }

    function reply($pid, $aid, $content)
    {
        $dao = new PostDao($this->db_config);

        $sql_builder = $dao->get_sql_builder_instance();

        return $dao->update($pid, [
            "replay" => $content,
            "replay_aid" => $aid,
            "replay_create_time" => $sql_builder->_date()
        ])->rowCount();
    }
}