<!DOCTYPE html>
<html lang="zh-CN">

</html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>BBS</title>

    <link rel="stylesheet" href="https://at.alicdn.com/t/font_1170412_j8dpwz4h0vo.css">
    <?php
    // 根据SERVER_ADMIN来选择引用本地或者CDN上的资源
    // 引用的资源列表：
    // - Bootstrap
    // - Font Awesome
    if ($_SERVER["SERVER_ADMIN"] == "jianglufull@foxmail.com") {
        echo '<link rel="stylesheet" href="lib/bootstrap-3.3.7-dist/css/bootstrap.css">';
        echo '<link rel="stylesheet" href="lib/font-awesome-4.7.0/css/font-awesome.css">';
    } else {
        echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">';
        echo '<link href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">';
    }
    ?>
    <link rel="stylesheet" href="css/main.css">


    <!-- HTML5 shim 和 Respond.js 是为了让 IE8 支持 HTML5 元素和媒体查询（media queries）功能 -->
    <!-- 警告：通过 file:// 协议（就是直接将 html 页面拖拽到浏览器中）访问页面时 Respond.js 不起作用 -->
    <!--[if lt IE 9]>
    <script src="https://cdn.jsdelivr.net/npm/html5shiv@3.7.3/dist/html5shiv.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/respond.js@1.4.2/dest/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<div class="container">
    <div class="panel main-panel">
        <div class="panel-heading">
            <div class="panel-title">
                BBS - Power by BootStrap3
            </div>
        </div>
        <div class="panel-body">

<?php
require "sql/dao.php";
// 生成一页的card
$page = 1;
$page_size = 2;
if (isset($_GET["p"])){
    $page = $_GET["p"];
}
if(isset($_GET["psize"])){
    $page_size = $_GET["psize"];
}

$c = connect_mysql();

if (!$c->conn) {
    die('数据库连接出错！' . mysqli_error($conn));
}

// 设置编码，防止中文乱码
$c->query("set names utf8");
// 选择bbs数据库
$c->select_db("bbs");
// 查询
$result = $c->query("SELECT id, nick, qq, email, homepage, face, title, content, timestamp FROM message LIMIT " . ($page - 1) * $page_size . "," . $page_size);

while ($row = $c->fetch_array($result, MYSQLI_ASSOC)) {
    echo <<<___
                    <div class="card panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-2 col-md-2 text-center">
                                <!-- 头像 -->
                                <div class="row">
                                    <div class="col-xs-12">
                                        <a href="#">
                                            <img src="static/img/face/default.png" alt="{$row["face"]}" class="face img-circle center-block" width="50" height="50">
                                        </a>
                                    </div>
                                </div>
                                <!-- 各种其他信息入口 -->
                                <div class="row">
                                    <div class="col-xs-12">
                                        <!-- QQ -->
                                        <a href="tencent://message/?uin={$row["qq"]}&Site=qq&Menu=yes">
                                            <span class="fa-stack">
                                                <i class="fa fa-circle fa-stack-2x"></i>
                                                <i class="fa iconfont icon-QQ fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </a>
                                        <!-- Email -->
                                        <a href="mailto:{$row["email"]}">
                                            <span class="fa-stack">
                                                <i class="fa fa-circle fa-stack-2x"></i>
                                                <i class="fa iconfont icon-mail-fill fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </a>
                                        <!-- 主页 -->
                                        <a href="{$row["homepage"]}">
                                            <span class="fa-stack">
                                                <i class="fa fa-circle fa-stack-2x"></i>
                                                <i class="fa iconfont icon-home-fill fa-stack-1x fa-inverse"></i>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9 col-md-9">
                                <!-- 标题 -->
                                <div class="row">
                                    <div class="col-xs-12 text-justify text-left">
                                        <a href="index.php?id=1">
                                            <h4>{$row["title"]}</h4>
                                        </a>
                                    </div>
                                </div>
                                <!-- 昵称与发布时间-->
                                <div class="row">
                                    <div class="col-xs-12 text-justify text-left">
                                        <!-- 发布时间 -->
                                        <small>
                                            <!-- 昵称 -->
                                            <a href="#" class="text-primary">{$row["nick"]}</a>
                                            <span class="text-muted">发布于&nbsp;{$row["timestamp"]}</span>
                                        </small>
                                    </div>
                                </div>
                                <!-- 被截断的主体内容 -->
                                <div class="row">
                                    <div class="col-xs-12 text-justify text-left">
                                        <p class="brief">
                                            {$row["content"]}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="btn-group menu-group">
                            <a role="btn" class="btn" data-toggle="dropdown">
                                <i class="iconfont icon-more"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">回复</a></li>
                                <li class="divider"></li>
                                <li><a href="#">删除</a></li>
                                <li><a href="#">置顶</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
___;
// 注意上面的占位符一定要顶头写，如果VSCode安装了Bracket Pair Colorizer插件就会导致VSCode插件系统崩溃
} 
?>

         </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <ul class="pagination">
                            <li><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#" class="disabled">...</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#">5</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <span class="page-jump">
                            共
                            <span>5</span>
                            页，跳到
                            <input type="text" name="target-page" class="form-control" id="text-target-page">
                            页
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>
    <!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
    <script>
        var resize_callback = function() {
            $(".main-panel > .panel-body").css("height", $(window).height() - 125);
        };
        $(document).ready(function() {
            resize_callback();
            $(window).resize(resize_callback);
        });
    </script>
</body>

</html>