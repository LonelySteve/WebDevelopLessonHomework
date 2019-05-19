<?php


namespace JLoeve\BBS\db\models;

require_once dirname(__FILE__) . "/../../util/value.php";
require_once "BaseModel.php";

use JLoeve\BBS\util\value as val;


class Post extends BaseModel
{
    protected $pid;
    protected $uid;
    protected $title;
    protected $content;
    protected $create_time;
    protected $update_time;

    function __construct($pid, $uid, $title, $content, $create_time, $update_time)
    {
        $this->pid = new val\IntValue($pid, "int(0...) null");
        $this->uid = new val\IntValue($uid, "int(0...)");
        $this->title = new val\StringValue($title, "string(...50)");
        $this->content = new val\StringValue($content, "string(...100000)");
        $this->create_time = new val\DateTimeValue($create_time);
        $this->update_time = new val\DateTimeValue($update_time);
    }

    function get_fields()
    {
        return array(
            "pid" => $this->pid->get(),
            "uid" => $this->uid->get(),
            "title" => $this->title->get(),
            "content" => $this->content->get(),
            "create_time" => $this->create_time->toDateTimeString(),
            "update_time" => $this->update_time->toDateTimeString(),
        );
    }
}