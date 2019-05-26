<?php


namespace App\Http;


class Response
{
    static $smarty;

    public $header;
    public $cookie;
    public $content;

    public function render($file, $kwargs)
    {
        foreach ($kwargs as $k => $v) {
            self::$smarty->assign($k, $v);
        }
        self::$smarty->display($file);
    }

    public function error_jsonify($code = -1, $msg = "unknown error!")
    {
        $this->jsonify(null, $code, $msg);
    }

    public function jsonify($data = null, $code = 0, $msg = "ok!")
    {
        header("content-type: application/json");
        $base = ["code" => $code, "message" => $msg];
        if ($data) {
            $base += ["data" => $data];
        }
        echo json_encode($base);
    }
}