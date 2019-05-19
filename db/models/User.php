<?php


namespace JLoeve\BBS\db\models;

require_once dirname(__FILE__) . "/../../util/value.php";
require_once "BaseModel.php";

use JLoeve\BBS\util\value as val;

class User extends BaseModel
{
    protected $uid;
    protected $pwd;
    protected $rank;
    protected $nick;
    protected $qq;
    protected $email;
    protected $homepage;
    protected $face_id;
    protected $create_time;

    function __construct($uid, $pwd, $rank, $nick, $qq, $email, $homepage, $face_id, $create_time)
    {
        $this->uid = new val\IntValue($uid, "int(0...) null");
        $this->pwd = new val\PasswordValue($pwd, "password null");
        $this->rank = new val\IntValue($rank, "int(0,1)");
        $this->nick = new val\StringValue($nick, "string(2...30)");
        $this->qq = new val\StringValue($qq, "string(...20) null");
        $this->email = new val\EmailValue($email, "email null");
        $this->homepage = new val\HomePageValue($homepage, "homepage null");
        $this->face_id = new val\StringValue($face_id, "string(...32)");
        $this->create_time = new val\DateTimeValue($create_time);
    }

    function get_fields()
    {
        return array(
            "uid" => $this->uid->get(),
            "pwd" => $this->pwd->get(),
            "rank" => $this->rank->get(),
            "nick" => $this->nick->get(),
            "qq" => $this->qq->get(),
            "email" => $this->email->get(),
            "homepage" => $this->homepage->get(),
            "face_id" => $this->face_id->get(),
            "create_time" => $this->create_time->toDateTimeString(),
        );
    }
}