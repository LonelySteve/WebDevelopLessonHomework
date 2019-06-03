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
        return new $this->sql_builder_cls($this);
    }

    function get_pdo_instance()
    {
        return $this->pdo;
    }

    /**
     * 执行指定 SQL 语句
     *
     * @param $sql string 欲执行的SQL语句
     * @param $data array SQL语句占位符对应值的数组，可使用关联数组的键名指明占位符名称（无需带":"号）
     * @param $extra_pdo_value_types array|null 额外补充的pdo数据类型字典
     * @param bool $auto_pdo_value_types 根据当前的dao填充pdo数据字典，即使SQL语句使用的是问号占位符，
     * 开启此项也将生效，其效果是将先按照当前dao提供的数据类型字典填充问号占位符，再使用额外补充的pdo数据字典填充剩余问号占位符
     * @return bool|\PDOStatement
     * @throws SqlExecuteException
     */
    function execute_sql($sql, $data, $extra_pdo_value_types = null, $auto_pdo_value_types = true)
    {
        $pdo = $this->pdo;

        $pdo_value_types = [];
        if ($auto_pdo_value_types) {
            $pdo_value_types = self::get_field_value_types();
        }
        $pdo_value_types += $extra_pdo_value_types ?: [];

        $stat = $pdo->prepare($sql);

        if ($stat) {
            if (Util::array_is_assoc($data)) {
                foreach ($data as $key => $value) {
                    // NOTE: 命名占位符以 ":" 开头
                    $stat->bindValue(":" . $key, $value, $pdo_value_types[$key]);
                }
            } else {
                reset($pdo_value_types);
                for ($i = 0; $i < count($data); $i++) {
                    // NOTE: 问号占位符从 1 开始计数
                    $stat->bindValue($i + 1, $data[$i], current($pdo_value_types));
                    next($pdo_value_types);
                }
            }
            // 执行SQL
            $stat->execute();
            // 判断SQL是否执行成功，未成功则抛出异常
            $info = $stat->errorInfo();
            if ($info[0] !== "00000") {
                throw new SqlExecuteException($info[2]);
            }
        }
        // 通过PDO对象判断SQL是否合法，不合法则抛出异常
        $info = $pdo->errorInfo();
        if ($info[0] !== "00000") {
            throw new SqlExecuteException($info[2]);
        }
        return $stat;
    }

    public function count()
    {
        return ($this->get_sql_builder_instance())
            ->select("COUNT(*)")
            ->execute();
    }

    public function insert($data)
    {
        return ($this->get_sql_builder_instance())
            ->insert($data)
            ->execute();
    }

    public function query($offset, $size = null)
    {
        if ($offset) {
            $pts[] = \PDO::PARAM_INT;
        }
        if ($size) {
            $pts[] = \PDO::PARAM_INT;
        }
        return ($this->get_sql_builder_instance())
            ->select()
            ->order_by([self::get_primary_key_name() => "DESC"])
            ->limit($offset, $size)
            ->execute($pts);
    }

    public function delete($id)
    {
        return ($this->get_sql_builder_instance())
            ->delete()
            ->where([self::get_primary_key_name(), $id])
            ->execute([\PDO::PARAM_INT]);
    }

    public function update($id, $data)
    {
        return ($this->get_sql_builder_instance())
            ->update($data)
            ->where([self::get_primary_key_name(), $id])
            ->execute([\PDO::PARAM_INT]);
    }
}