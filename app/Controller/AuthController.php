<?php

namespace App\Controller;

use App\Dao\AdminDao;
use App\Entity\Admin;
use App\Exceptions\NotFoundException;
use App\Exceptions\PasswordException;

class AuthController extends BaseController
{
    public function login($username, $password)
    {
        $dao = new AdminDao($this->db_config);

        $result = $dao->get_sql_builder_instance()
            ->select()
            ->where(["username", $username])
            ->execute([\PDO::PARAM_INT, \PDO::PARAM_INT])->fetch(\PDO::FETCH_ASSOC);

        if ($result) {
            if (md5($password) === $result["password"]) {
                $_SESSION["admin_name"] = $result["username"];
            } else {
                throw new PasswordException();
            }
        } else {
            throw new NotFoundException("The specified user was not found");
        }

        return true;
    }

    public function logout()
    {
        unset($_SESSION["admin_name"]);

        return true;
    }

    function index($offset, $size)
    {
        $results = [];
        $stat = (new AdminDao($this->db_config))->query($offset - 1, $size);

        while ($result = $stat->fetchObject(Admin::class)) {
            $results[] = $result;
        }

        return $results;
    }
}