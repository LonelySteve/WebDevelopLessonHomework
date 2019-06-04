<?php


namespace App\Cores;

use App\Filters\MethodFilter;
use App\Http\Request;
use App\Config\Config;
use App\Http\Response;


/**
 * 后端核心类
 *
 * 负责初始化环境，处理请求，返回响应等功能
 *
 * Class Core
 * @package App\Cores
 */
class Core
{
    // 过滤器数组
    public $filters = [];
    // 后端配置对象
    public $config;
    // 允许的请求方法数组
    protected $allowed_methods = [];
    // 子流程函数数组
    protected $sub_process_callbacks = [];
    // 响应回调函数
    protected $response_callback;
    protected $error_response_callback;


    function init()
    {
        // 加载全局配置
        $this->config = Config::load();
        // 设置时区
        date_default_timezone_set('Etc/GMT-8');
        // 对需要配置对象的类赋值相应的静态变量
        Response::$config = $this->config;
    }

    /**
     * 处理请求对象，返回值将作为Response的数据域
     *
     * @param Request $request
     */
    function main(Request $request)
    {
        return null;
    }

    /**
     * 对请求应用所有过滤器
     *
     * @param Request $request
     * @return bool
     */
    protected function apply_filters(Request $request)
    {
        foreach ($this->filters as $filter) {
            if (!$filter->pass($request)) {
                return false;
            }
        }
        return true;
    }

    // 子类继承可重写
    protected function handle()
    {
        // 初始化
        $this->init();
        // 包装请求
        $r = Request::wrap();
        // 过滤器筛选
        if ($this->apply_filters($r)) {
            // 主业务逻辑
            $data = $this->main($r);
            foreach ($this->sub_process_callbacks as $sub_process) {
                $sub_process($r, $data);
            }
            ($this->response_callback)(new Response($data));
        }
    }

    function append_sub_process(callable $sub_process_callback)
    {
        $this->sub_process_callbacks[] = $sub_process_callback;

        return $this;
    }

    function method($names, callable $response_callback)
    {
        if (!is_array($names)) {
            $names = [$names];
        }
        $this->allowed_methods = $names;
        $this->response_callback = $response_callback;
        // 添加Method过滤器，优先级高于其他过滤器
        array_unshift($this->filters, new MethodFilter($names));

        return $this;
    }

    function as_api($accept_methods)
    {
        $this->method($accept_methods, function (Response $response) {
            $response->jsonify($response->data);
        });

        $this->error_response_callback = function (Response $response, \Throwable $th) {
            $response->error_jsonify($th->getCode(), $th->getMessage());
        };

        return $this;
    }

    function as_web_page($template_name, $extra_params = null)
    {
        $extra_params = $extra_params ?: [];
        $this->method("GET", function (Response $response) use ($template_name, $extra_params) {
            if (is_array($response->data)) {
                $extra_params += $response->data;
            }
            $response->render($template_name, $extra_params);
        });

        $this->error_response_callback = function (Response $response, \Throwable $th) {
            $response->render("info.tpl", ["__title__" => "留言板 - 错误", "type" => "error", "message" => $th, "go_url" => "/", "time" => 5]);
        };

        return $this;
    }

    // 提供给外部调用
    function handle_request()
    {
        try {
            $this->handle();
        } catch (\Throwable $th) {
            ($this->error_response_callback)(new Response(), $th);
        }
    }
}
