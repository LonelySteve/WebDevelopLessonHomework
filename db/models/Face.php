<?php


namespace JLoeve\BBS\db\models;

require_once dirname(__FILE__) . "/../../util/value.php";
require_once "BaseModel.php";

use JLoeve\BBS\util\value as val;

class Face extends BaseModel
{
    protected $fid;
    protected $filename;
    protected $md5;

    function __construct($fid, $filename, $md5)
    {
        $this->fid = new val\IntValue($fid, "int(0...) null");
        $this->filename = new val\IntValue($filename, "string(...255)");
        $this->md5 = new val\IntValue($md5, "string(...32)");
    }

    function get_fields()
    {
        return array(
            "cid" => $this->fid->get(),
            "filename" => $this->filename->get(),
            "md5" => $this->md5->get(),
        );
    }
}