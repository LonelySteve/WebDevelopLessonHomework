<?php


namespace JLoeve\BBS\db\dao;

require_once "BaseDao.php";
require_once dirname(__FILE__) . "/../models/User.php";
require_once dirname(__FILE__) . "/../../util/exceptions.php";

use JLoeve\BBS\db\models\User;
use JLoeve\BBS\exceptions as ex;


class UserDao extends BaseDao
{
    protected const table_name = "user";

    const primary_key_name = "uid";

    protected const field_short_types_array = array(
        "uid" => "i",
        "pwd" => "s",
        "rank" => "i",
        "nick" => "s",
        "qq" => "s",
        "email" => "s",
        "homepage" => "s",
        "face_id" => "s",
        "create_time" => "s"
    );

    function add($pwd, $rank, $nick, $qq = null, $email = null, $homepage = null, $face_id = null, $create_time = null)
    {
        if(!$face_id){
            $face_id = "0";
        }
        if(!$create_time){
            $create_time = time();
        }
        $user = new User(null, $pwd, $rank, $nick, $qq, $email, $homepage, $face_id, $create_time);
        $success = boolval($this->insert($user)->execute_sql()->affected_rows);
        if ($success) {
            $result = $this->query("LAST_INSERT_ID()")->execute_sql()->get_result();
            if ($result) {
                return $result->fetch_row();
            }
        }
        throw new ex\RuntimeErrorException("The user add operation failed!");
    }

    static function get_instance($className = __CLASS__)
    {
        return parent::get_instance($className);
    }
}