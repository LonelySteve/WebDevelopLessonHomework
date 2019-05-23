<?php


namespace App\Services;

use App\Dao\PostDao;
use App\Entity\Post;

class PostService
{
    public function append_post($name, $title, $content, $email, $state = 1)
    {
        $post = new Post();
        $post->set_name($name);
        $post->set_title($title);
        $post->set_content($content);
        $post->set_email($email);
        $post->set_state($state);
    }

    public function replay($pid, $reply_content, $reply_aid)
    {
        $stmt = (new PostDao())->update($pid, ["replay" => $reply_content, "replay_aid" => $reply_aid]);
        return $stmt->rowCount();
    }

    public function delete($pid)
    {
        $stmt = (new PostDao())->delete($pid)->rowCount();

    }

}