<?php


namespace App\Dao;

use App\Config\DBConfig;
use App\SqlBuilder;
use App\SqlBuilder\SqlBuilderFactory;

abstract class BaseDao
{
    protected $db_config;
    protected $pdo;
    protected $sql_builder_cls;
    // 实现的子类应重写该常量
    protected const table_name = "";
    protected const primary_key_name = "id";

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
    protected function get_sql_builder_instance()
    {
        return new $this->sql_builder_cls(self::table_name);
    }

    protected function execute_sql($sql, $data)
    {
        $stat = $this->pdo->prepare($sql);
        if ($stat) {
            $stat->execute($data);
        }

        return $stat;
    }

    public function query($offset, $size = 20)
    {
        $sql_builder = $this->get_sql_builder_instance();

        $sql = $sql_builder->select()->limit($offset, $size)->order_by([self::primary_key_name => "DESC"]);

        return $this->execute_sql($sql, $sql_builder->get_values());
    }

    public function delete($id)
    {
        $sql_builder = $this->get_sql_builder_instance();

        $sql = $sql_builder->delete()->where([self::primary_key_name, $id]);

        return $this->execute_sql($sql, $sql_builder->get_values());
    }

    public function update($id, $data)
    {
        $sql_builder = $this->get_sql_builder_instance();

        $sql = $sql_builder->update($data)->where([self::primary_key_name, $id]);

        return $this->execute_sql($sql, $sql_builder->get_values());
    }

    public function exist($id)
    {
        $sql_builder = $this->get_sql_builder_instance();

        $sql = $sql_builder->select()->where([self::primary_key_name, $id])->limit(1);

        $stmt = $this->execute_sql($sql, $sql_builder->get_values());
        return boolval($stmt->rowCount());
    }
}