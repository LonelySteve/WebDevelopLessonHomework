<?php


namespace JLoeve\BBS\db\dao;

require_once "BaseDao.php";

class CommentDao extends BaseDao
{
    protected static $table_name = "comment";

    const primary_key_name = "cid";
    protected const field_short_types_array = array(
        "cid" => "i",
        "pid" => "i",
        "uid" => "i",
        "content" => "s",
        "create_time" => "s"
    );

    static function get_instance($className = __CLASS__)
    {
        return parent::get_instance($className);
    }
}