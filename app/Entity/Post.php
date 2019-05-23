<?php


namespace App\Entity;

use App\Validators\DataValidator;
use App\Dao\AdminDao;


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

    function __construct()
    {
        // 自动设置创建时间的属性值为当前时间戳
        $this->set_create_time(time());
        $this->replay = $this->replay_aid = $this->replay_create_time = null;
    }

    public function set_name($name)
    {
        $this->name = (new DataValidator($name))
            ->is_string()
            ->min_len(2)
            ->max_len(30)
            ->get_data();
    }

    public function set_email($email)
    {
        $this->email = (new DataValidator($email))
            ->is_string()
            ->match_regex("^[A-Za-z\d]+([-_.][A-Za-z\d]+)*@([A-Za-z\d]+[-.])+[A-Za-z\d]{2,4}$")->get_data();
    }

    public function set_title($title)
    {
        $this->title = (new DataValidator($title))
            ->is_string()
            ->min_len(2)
            ->max_len(50)
            ->get_data();
    }

    public function set_content($content)
    {
        $this->content = (new DataValidator($content))
            ->is_string()
            ->min_len(2)
            ->max_len(65535)
            ->get_data();
    }

    public function set_create_time($timestamp)
    {
        $this->create_time = (new DataValidator($timestamp))
            ->is_timestamp()
            ->is_latest()
            ->get_data();
    }

    public function set_replay($replay)
    {
        // 允许 $replay 参数为null
        if ($replay !== null) {
            $this->replay = (new DataValidator($replay))
                ->is_string()
                ->min_len(2)
                ->max_len(233);
        }
    }

    public function set_replay_aid($aid)
    {
        // 允许 $aid 参数为null
        if ($aid !== null) {
            $this->replay_aid = (new DataValidator($aid))
                ->is_id(AdminDao::class, null)
                ->exist()
                ->get_data();
        }
    }

    public function set_replay_create_time($timestamp)
    {
        // 允许 $timestamp 参数为null
        if ($timestamp !== null) {
            $this->replay_create_time = (new DataValidator($timestamp))
                ->is_timestamp()
                ->is_latest()
                ->get_data();
        }
    }

    public function set_state($state)
    {
        $this->state = (new DataValidator($state))
            ->in_array([0, 1])
            ->get_data();
    }
}