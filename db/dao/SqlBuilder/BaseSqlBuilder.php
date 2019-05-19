<?php


namespace JLoeve\BBS\db\dao\SqlBuilder;


abstract class BaseSqlBuilder
{
    protected $sql_meta_array = array();
    protected $sql_values_array = array();
    protected $sql_short_types_desc = "";

    protected const table_name = "";

    static function get_table_name()
    {
        // https://php.net/manual/zh/language.oop5.constants.php
        $c = get_called_class();
        return $c::table_name;
    }

    abstract function query($columns);

    abstract function insert($item);

    abstract function delete();

    abstract function update($update_items_array);

    abstract function where($conditions);

    abstract function or_where($conditions);

    abstract function limit($offset, $size = null);

    abstract function order_by($columns_array, $type);

    function tosql()
    {
        return implode("", $this->sql_meta_array);
    }
}