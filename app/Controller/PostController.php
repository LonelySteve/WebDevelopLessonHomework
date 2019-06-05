<?php

namespace App\Controller;

use App\Dao\PostDao;
use App\Entity\Post;

class PostController extends BaseController
{
    function append($title, $content, $name, $qq, $email, $homepage, $state = 0)
    {
        $dao = new PostDao($this->db_config);

        $pdo = $dao->get_pdo_instance();
        $sql_builder = $dao->get_sql_builder_instance();

        $dao->insert([
            "pid" => null,
            "name" => $name,
            "qq" => $qq,
            "email" => $email,
            "title" => $title,
            "content" => $content,
            "homepage" => $homepage,
            "create_time" => $sql_builder->_date(),
            "reply" => null,
            "reply_admin_name" => null,
            "reply_create_time" => null,
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

        $dao = new PostDao($this->db_config);

        $amount = $dao->count()->fetch(\PDO::FETCH_UNIQUE)[0];

        $page_count = ceil($amount / $size);

        if ($page > $page_count) {
            $cur_page = $page_count;
        } else {
            $cur_page = $page;
        }

        $stat = $dao->query(($cur_page - 1) * $size, $size);

        while ($result = $stat->fetchObject(Post::class)) {
            $results[] = $result;
        }
        
        return [
            "amount" => $amount,
            "page_count" => ceil($amount / $size),
            "cur_page" => $cur_page,
            "posts" => $results
        ];
    }

    function reply($pid, $name, $content)
    {
        $dao = new PostDao($this->db_config);

        $sql_builder = $dao->get_sql_builder_instance();

        return $sql_builder->update([
            "reply" => $content,
            "reply_admin_name" => $name,
            "reply_create_time" => $sql_builder->_date()
        ])
            ->where([$dao::get_primary_key_name(), $pid])
            ->execute(["__param0__" => \PDO::PARAM_INT])->rowCount();
    }
}