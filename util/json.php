<?php

namespace JLoeve\BBS\util\json;

function std_jsonify($msg = "ok!", $code = 0, $data = null)
{
    $buffer_arr = array("code" => $code, "msg" => $msg);
    if ($data) {
        $buffer_arr["data"] = $data;
    }
    return json_encode($buffer_arr, JSON_UNESCAPED_UNICODE);
}
