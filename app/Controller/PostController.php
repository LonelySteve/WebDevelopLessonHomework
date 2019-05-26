<?php

namespace App\Controller;

use App\Services\PostService;
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
        return (new PostDao())->query($offset, $size)->fetchObject(Post::class);
    }
}