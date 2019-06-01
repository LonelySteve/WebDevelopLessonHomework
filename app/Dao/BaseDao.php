<?php


namespace App\Dao;

use App\Config\DBConfig;
use App\SqlBuilder;
use App\SqlBuilder\SqlBuilderFactory;
use App\exceptions\SqlExecuteException;
use App\Util\Util;

abstract class BaseDao
{
    protected $db_config;
    protected $pdo;
    protected $sql_builder_cls;
    // 实现的子类应重写下面的常量
    protected const table_name = "";
    protected const primary_key_name = "id";
    protected const field_value_types = [];

    static function get_table_name()
    {
        $cls = get_called_class();
        return $cls::table_name;
    }

    static function get_primary_key_name()
    {
        $cls = get_called_class();
        return $cls::primary_key_name;
    }

    static function get_field_value_types($fields_name = null)
    {
        $cls = get_called_class();
        if (!$fields_name) {
            return $cls::field_value_types;
        }
        foreach ($fields_name as $field) {
            $temp_arr[] = $cls::field_value_types[$field];
        }
        return $temp_arr;
    }

    function __construct(DBConfig $db_config)
    {
        $this->db_config = $db_config;
        $this->sql_builder_cls = SqlBuilderFactory::from_type($this->db_config->db_type);
        $this->pdo = $db_config->get_pdo();
    }

    /**
     * 获取SqlBuilder的实例
     *
     * @return SqlBuilder\BaseSqlBuilder
     */
    function get_sql_builder_instance()
    {
        return new $this->sql_builder_cls(self::get_table_name());
    }

    function get_pdo_instance()
    {
        return $this->pdo;
    }

    protected function execute_sql($sql, $data, $pdo_value_types)
    {
        $stat = $this->pdo->prepare($sql);
        if ($stat) {
            if (Util::array_is_assoc($data)) {
                foreach ($data as $key => $value) {
                    $stat->bindValue($key, $value, $pdo_value_types[$key]);
                }
            } else {
                for ($i = 0; $i < count($data); $i++) {
                    // NOTE: 问号占位符从 1 开始计数
                    $stat->bindValue($i + 1, $data[$i], $pdo_value_types[$i]);
                }
            }
            // 执行SQL
            $stat->execute();
        }
        // 判断SQL是否执行成功，未成功则抛出异常
        $info = $stat->errorInfo();
        if ($info[0] !== "00000") {
            throw new SqlExecuteException($info[2]);
        }
        return $stat;
    }

    public function insert($data)
    {
        $sql_builder = $this->get_sql_builder_instance();

        $sql = $sql_builder->insert($data)->dump();

        return $this->execute_sql($sql, $sql_builder->get_values(), self::get_field_value_types());
    }

    public function query($offset, $size = null)
    {
        $sql_builder = $this->get_sql_builder_instance();

        $sql = $sql_builder->select()->order_by([self::get_primary_key_name() => "DESC"])->limit($offset, $size)->dump();

        return $this->execute_sql($sql, $sql_builder->get_values(), [\PDO::PARAM_INT, \PDO::PARAM_INT]);
    }

    public function delete($id)
    {
        $sql_builder = $this->get_sql_builder_instance();

        $sql = $sql_builder->delete()->where([self::get_primary_key_name(), $id]);

        return $this->execute_sql($sql, $sql_builder->get_values(), [\PDO::PARAM_INT]);
    }

    public function update($id, $data)
    {
        $sql_builder = $this->get_sql_builder_instance();

        $sql = $sql_builder->update($data)->where([self::get_primary_key_name(), $id])->dump();

        return $this->execute_sql($sql, $sql_builder->get_values(), self::get_field_value_types($data));
    }

    public function exist($id)
    {
        $sql_builder = $this->get_sql_builder_instance();

        $sql = $sql_builder->select()->where([self::get_primary_key_name(), $id])->limit(1)->dump();

        $stmt = $this->execute_sql($sql, $sql_builder->get_values(), [\PDO::PARAM_INT]);
        return boolval($stmt->rowCount());
    }
}