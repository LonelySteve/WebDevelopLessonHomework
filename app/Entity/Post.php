<?php


namespace App\Entity;


class Post extends BaseEntity
{
    public $pid;
    public $name;
    public $email;
    public $title;
    public $content;
    public $create_time;
    public $replay;
    public $replay_aid;
    public $replay_create_time;
    public $state;

    /**
     * Post constructor.
     * @param $name
     * @param $email
     * @param $title
     * @param $content
     * @param $create_time
     * @param $state
     */
    public function __construct($name, $email, $title, $content, $create_time, $state)
    {
        $this->name = $name;
        $this->email = $email;
        $this->title = $title;
        $this->content = $content;
        $this->create_time = $create_time;
        $this->state = $state;
    }
}