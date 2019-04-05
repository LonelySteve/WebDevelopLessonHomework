<?php
header('Content-Type:application/json; charset=utf-8');
$grade = $_GET["grade"];
if(!preg_match("/^-?\d+$/",$grade)){
    exit(json_encode(array('code'=>-233,'msg'=> '数值非法！')));
}

if ($grade < 0) {
    exit(json_encode(array('code' => -1, 'msg' => '成绩不能小于0！')));
} elseif ($grade > 100) {
    exit(json_encode(array('code' => -2, 'msg' => '成绩不能大于100！')));
} else {
    $level = floor($grade / 10);
    switch ($level) {
        case 6:
        exit(json_encode(array('code' => 0, 'msg' => '及格！')));
            break;
        case 7:
        exit(json_encode(array('code' => 0, 'msg' => '还行！')));
            break;
        case 8:
        case 9:
        exit(json_encode(array('code' => 0, 'msg' => '优秀！')));
            break;
        case 10:
            exit(json_encode(array('code' => 0, 'msg' => '满分！')));
            break;
        default:
            exit(json_encode(array('code' => 0, 'msg' => '未及格！')));
            break;
    }
}
 