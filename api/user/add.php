<?php

namespace JLoeve\BBS\api\user {
    require_once dirname(__FILE__) . "/../../db/dao/UserDao.php";
    require_once dirname(__FILE__) . "/../../db/config.php";
    require_once dirname(__FILE__) . "/../../util/json.php";

    use JLoeve\BBS\db\dao\UserDao;
    use JLoeve\BBS\db\config\DBConfig;
    use function JLoeve\BBS\util\json\std_jsonify;

    $conf = DBConfig::from_auto();

    UserDao::init($conf->server_host, $conf->user, $conf->pwd, $conf->db_name);

    $user = UserDao::get_instance();

    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        die(std_jsonify("Submission other than POST is not supported!", -3));
    }

    if (!isset($_POST["pwd"])) {
        $err_msg = "Missing password parameter!";
        die(std_jsonify($err_msg, -2));
    };
    if (!isset($_POST["nick"])) {
        $err_msg = "Missing nick parameter!";
        die(std_jsonify($err_msg, -2));
    };

    try {
        $user->add(
            $_POST["pwd"],
            isset($_POST["rank"]) ? $_POST["rank"] : 0,
            $_POST["nick"],
            isset($_POST["qq"]) ? $_POST["qq"] : null,
            isset($_POST["email"]) ? $_POST["email"] : null,
            isset($_POST["homepage"]) ? $_POST["homepage"] : null,
            isset($_POST["face_id"]) ? $_POST["face_id"] : null,
            );
        die(std_jsonify());
    } catch (\Exception $ex) {
        die(std_jsonify($ex->getMessage(), $ex->getCode()));
    }
}