<?php


namespace App\Entity;


class Post extends BaseEntity
{
    public $pid;
    public $name;
    public $email;
    public $homepage;
    public $title;
    public $content;
    public $create_time;
    public $replay;
    public $replay_aid;
    public $replay_create_time;
    public $state;
}