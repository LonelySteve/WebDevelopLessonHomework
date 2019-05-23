<?php


namespace App\Entity;

use App\Validators\DataValidator;

class Admin extends BaseEntity
{
    public $aid;
    public $username;
    public $password;

    public function set_username($username)
    {
        $this->username = (new DataValidator($username))
            ->is_string()
            ->max_len(2)
            ->max_len(30);
    }

    public function set_password($password)
    {
        $this->password = (new DataValidator($password))
            ->is_string()
            ->min_len(6)
            ->max_len(16)
            ->match_regex("^.*(?=.{6,16})(?=.*\d)(?=.*[A-Z]{2,})(?=.*[a-z]{2,})(?=.*[!@#$%^&*?\(\)]).*$")
            ->get_data();
    }
}