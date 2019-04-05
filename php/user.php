<?php
header('Content-Type:application/json; charset=utf-8');
if ($_POST["username"] != "root") {
    exit(json_encode(array('code' => -1, 'msg' => '用户名错误！')));
} elseif ($_POST["password"] != "pwd123456") { 
    exit(json_encode(array('code' => -2, 'msg' => '密码错误！')));
} else {
    exit(json_encode(array('code' => 0, 'msg' => '登录成功')));
}
?>