<?php


namespace JLoeve\BBS\db\dao;

require_once "BaseDao.php";

class PostDao extends BaseDao
{
    protected static $table_name = "post";

    const primary_key_name = "pid";
    protected const field_short_types_array = array(
        "pid" => "i",
        "uid" => "i",
        "title" => "s",
        "content" => "s",
        "create_time" => "s",
        "update_time" => "s"
    );

    static function get_instance($className = __CLASS__)
    {
        return parent::get_instance($className);
    }
}