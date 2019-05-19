<?php


namespace JLoeve\BBS\db\dao;

require_once dirname(__FILE__) . "/SqlBuilder/MySqlBuilder.php";
require_once dirname(__FILE__) . "/../../util/exceptions.php";

use JLoeve\BBS\db\dao\SqlBuilder\MySqlBuilder;
use JLoeve\BBS\exceptions as ex;


class BaseDao extends MySqlBuilder
{
    public const primary_key_name = "";

    public static $conn;
    private static $_models = array();

    private function __construct()
    {
        // 私有的构造函数
    }

    private function __clone()
    {
        // 防止克隆
    }

    // 实现单例模式
    static function get_instance($className = __CLASS__)
    {
        if (!isset(self::$_models[$className])) {
            // 判断当前是否已经进行初始化，如果没有则抛出异常
            if (!self::$conn) {
                throw new ex\RuntimeErrorException("Database connection not initialized or failed to initialize!");
            }
            $model = self::$_models[$className] = new $className(null);
            return $model;
        }

        return self::$_models[$className];
    }

    // 初始化
    static function init($server_host, $user, $pwd = '', $db_name = '')
    {
        self::$conn = mysqli_connect($server_host, $user, $pwd, $db_name);
    }


    function execute_sql()
    {
        $sql = $this->tosql();

        $stmt = mysqli_prepare(self::$conn, $sql);
        if (!$stmt) {
            throw  new ex\SqlExecuteException(mysqli_error(self::$conn));
        }
        $stmt->bind_param($this->sql_short_types_desc, ...$this->sql_values_array);
        $stmt->execute();  // 执行SQL语句
        // 清空sql数据缓存
        $this->sql_meta_array = [];
        $this->sql_values_array = [];
        $this->sql_short_types_desc = "";

        return $stmt;
    }

    function find($id, $offset = null, $size = null)
    {
        if ($offset) {
            return $this->query(self::primary_key_name)->limit($offset, $size)->execute_sql()->get_result();
        } else {
            return $this->query(self::primary_key_name)->execute_sql()->get_result();
        }
    }

    protected function get_count()
    {
        return $this->query("*")->execute_sql()->affected_rows;
    }

    protected function clear_all()
    {
        return $this->delete();
    }

    protected function delete_by_id($id)
    {
        return $this->delete()->where([self::primary_key_name, $id])->execute_sql()->affected_rows === 1;
    }

    protected function update_item_by_id($id, $prop_name, $value)
    {
        return $this->update(array($prop_name => $value))->where([self::primary_key_name, $id])->execute_sql()->affected_rows === 1;
    }

    public function is_conn_success()
    {
        return boolval(self::$conn);
    }

    public function get_last_execute_sql_fail_msg()
    {
        return mysqli_error(self::$conn);
    }

    public function select_db($db_name)
    {
        return mysqli_select_db(self::$conn, $db_name);
    }
}