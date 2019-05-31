<?php

namespace App\Controller;

use App\Dao\PostDao;
use App\Entity\Post;

class PostController extends BaseController
{
    public function append($title, $content, $name, $email, $homepage, $state = 0)
    {
        (new PostDao($this->db_config))->append($title, $content, $name, $email, $homepage, $state);
    }

    public function del($pid)
    {

    }

    public function update($pid, $update_data_array)
    {

    }

    function index($page, $size)
    {
        return (new PostDao($this->db_config))->index($page, $size);
    }
}