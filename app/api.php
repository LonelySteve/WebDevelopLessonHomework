<?php

function echo_json($code = 0, $message = "ok!", $data = null)
{
    header("content-type: application/json");
    echo json_encode(["code" => $code, "message" => $message, "data" => $data]);
}
