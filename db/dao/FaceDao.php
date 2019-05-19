<?php


namespace JLoeve\BBS\db\dao;

require_once "BaseDao.php";

class FaceDao extends BaseDao
{
    protected static $table_name = "face";

    const primary_key_name = "fid";
    protected const field_short_types_array = array(
        "fid" => "i",
        "filename" => "s",
        "md5" => "s",
    );

    static function get_instance($className = __CLASS__)
    {
        return parent::get_instance($className);
    }
}