<?php


namespace JLoeve\BBS\db\models;

require_once dirname(__FILE__) . "/../../util/value.php";
require_once "BaseModel.php";

use JLoeve\BBS\util\value as val;

class Comment extends BaseModel
{
    protected $cid;
    protected $pid; // 评论所在的帖子id
    protected $uid; // 评论人的id
    protected $content;
    protected $create_time; // 创建时间

    function __construct($cid, $pid, $uid, $content, $create_time)
    {
        $this->cid = new val\IntValue($cid, "int(0...) null");
        $this->pid = new val\IntValue($pid, "int(0...)");
        $this->uid = new val\IntValue($uid, "int(0...)");
        $this->content = new val\StringValue($content, "string(...233)");
        $this->create_time = new val\DateTimeValue($create_time);
    }

    function get_fields()
    {
        return array(
            "cid" => $this->cid->get(),
            "pid" => $this->pid->get(),
            "uid" => $this->uid->get(),
            "content" => $this->content->get(),
            "create_time" => $this->create_time->toDateTimeString(),
        );
    }
}