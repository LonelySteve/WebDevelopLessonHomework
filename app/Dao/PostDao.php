<?php


namespace App\Dao;

use App\Config\DBConfig;
use App\Entity\Post;
use App\SqlBuilder\SqlBuilderFactory;

class PostDao extends BaseDao
{
    public function __construct(DBConfig $db_config = null, SqlBuilderFactory $factory = null)
    {
        parent::__construct($db_config, $factory);
        // 设置表名
        $this->sql_builder->table_name = "posts";
    }

    public function insert(Post $post)
    {
        $sql = $this->sql_builder->insert((array)$post);
        $pdo = new \PDO($this->db_config->db_addr, $this->db_config->db_user, $this->db_config->db_pass);
    }
}