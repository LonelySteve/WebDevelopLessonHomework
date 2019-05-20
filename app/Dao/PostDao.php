<?php


namespace App\Dao;

use App\Config\DBConfig;
use App\Entity\Post;
use App\SqlBuilder\BaseSqlBuilder;

class PostDao extends BaseDao
{
    public function __construct(BaseSqlBuilder $sql_builder, DBConfig $db_config)
    {
        $sql_builder->table_name = "posts";
        parent::__construct($sql_builder, $db_config);
    }

    public function insert(Post $post)
    {
        $sql = $this->sql_builder->insert((array)$post);
        $pdo = new \PDO($this->db_config->db_addr, $this->db_config->db_user, $this->db_config->db_pass);

    }
}