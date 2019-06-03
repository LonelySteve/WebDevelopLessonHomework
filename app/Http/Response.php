<?php


namespace App\Http;


class Response
{
    // 配置对象
    static $config;

    public $header;
    public $cookie;

    public $data;

    public function __construct($data = null)
    {
        $this->data = $data;
    }

    public function render($file, $kwargs = null, $include_data = true)
    {
        $kwargs = $kwargs ?: [];
        $kwargs += ["__debug__" => self::$config->debug_mode];
        if ($include_data) {
            $kwargs += ["__data__" => $this->data];
        }
        foreach ($kwargs as $k => $v) {
            self::$config->smarty->assign($k, $v);
        }
        self::$config->smarty->display($file);
    }

    public function error_jsonify($code = -1, $msg = "unknown error!")
    {
        $this->jsonify(null, $code, $msg);
    }

    public function jsonify($data = null, $code = 0, $msg = "ok!")
    {
        header("content-type: application/json");
        $base = ["code" => $code, "message" => $msg];
        if ($data !== null) {
            $base += ["data" => $data];
        }
        echo json_encode($base, JSON_UNESCAPED_UNICODE);
    }
}