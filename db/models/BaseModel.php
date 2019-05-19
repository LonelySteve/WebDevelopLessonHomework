<?php


namespace JLoeve\BBS\db\models;


abstract class BaseModel
{
    protected $field;

    abstract function get_fields();
}

