<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Http\Request;
use app\models\BaseModel;
use App\Exceptions\BaseException;
use App\Config\Config;

abstract class Core
{
    // 定义过滤器数组
    public $FILTERS = [];
    public $config;

    function config()
    {
        $this->config = Config::load();
    }

    function init()
    {
        // 加载全局配置
        $this->config();
    }

    // 包含的php实现该函数以完成业务逻辑
    abstract function main(Request $request);

    function apply_filters(Request $request)
    {
        foreach ($this->FILTERS as $filter) {
            // 如果过滤器对象调用结果为真则继续调用其余过滤器，直到返回假
            try {
                $filter($request);
            } catch (BaseException $ex) {
                $code = $ex->get_code();
                $msg = $ex->get_msg();
            }
        }
    }

    // 子类继承可重写
    protected function handle()
    {
        // 初始化
        $this->init();
        // 包装请求
        $r = Request::wrap();
        // 过滤器筛选
        $this->apply_filters($r);
        // 主业务逻辑
        $this->main($r);
    }

    // 提供给外部调用
    function handle_request()
    {
        try {
            $this->handle();
        } catch (BaseException $ex) {
            // 如果是Debug模式，则直接抛出该异常
            if (@$this->config->debug_mode ?: false) {
                throw $th;
            }
            echo "出现自定义异常！！" . $th->get_msg();
        } catch (Throwable $th) {
            // 如果是Debug模式，则直接抛出该异常
            if (@$this->config->debug_mode ?: false) {
                throw $th;
            }
        }
    }
}
