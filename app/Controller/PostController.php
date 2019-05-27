<?php

namespace App\Controller;

use App\Dao\PostDao;
use App\Entity\Post;

class PostController extends BaseController
{
    public function append($request)
    {

    }

    public function del($pid)
    {

    }

    public function update($pid, $update_data_array)
    {

    }

    function index($offset, $size)
    {
        return (new PostDao($this->db_config))->query($offset, $size)->fetchAll(\PDO::FETCH_CLASS);
    }
}