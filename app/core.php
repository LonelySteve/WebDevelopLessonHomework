<?php

use App\Http\Request;
use app\models\BaseModel;

function init()
{
    // 加载环境变量
    $GLOBALS["bbs_conf"] = Config::get_config();
    // 初始化模型基类
    BaseModel::init($bbs_conf["DB_ADDR"], $bbs_conf["DB_USER"], $bbs_conf["DB_PASS"]);
}

// 定义过滤器数组
$FILTERS = [];

function main($request)
{
    // 包含的php实现该函数以完成业务逻辑
}

function apply_filters($request)
{
    foreach ($FILTERS as $filter) {
        // 如果过滤器对象调用结果为真则继续调用其余过滤器，直到返回假
        if (!$filter($request)) {
            return false;
        }
    }
    return true;
}
// 初始化
init();
// 包装当前请求
$r = Request::wrap();
// 依次应用过滤器
if(apply_filters($r)){
    // 通过所有过滤器的请求将进入主函数
    main($r);
}


