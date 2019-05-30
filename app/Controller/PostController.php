<?php

namespace App\Controller;

use App\Dao\PostDao;
use App\Entity\Post;

class PostController extends BaseController
{
    public function append($post)
    {
        (new PostDao($this->db_config))->append($post);
    }

    public function del($pid)
    {

    }

    public function update($pid, $update_data_array)
    {

    }

    function index($offset, $size)
    {
        $result = (new PostDao($this->db_config))->query($offset, $size)->fetchObject(Post::class);
        if (!$result) {
            return [];
        }
        return $result;
    }
}