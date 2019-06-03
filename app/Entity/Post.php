<?php


namespace App\Entity;


class Post extends BaseEntity
{
    public $pid;
    public $name;
    public $qq;
    public $email;
    public $homepage;
    public $title;
    public $content;
    public $create_time;
    public $reply;
    public $reply_admin_name;
    public $reply_create_time;
    public $state;
}