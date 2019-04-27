<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>操作提示</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim 和 Respond.js 是为了让 IE8 支持 HTML5 元素和媒体查询（media queries）功能 -->
    <!-- 警告：通过 file:// 协议（就是直接将 html 页面拖拽到浏览器中）访问页面时 Respond.js 不起作用 -->
    <!--[if lt IE 9]>
      <script src="https://cdn.jsdelivr.net/npm/html5shiv@3.7.3/dist/html5shiv.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/respond.js@1.4.2/dest/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <div class="container text-center">
        <div class="panel panel-default">
            <div class="panel-heading">
                操作提示
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-6 col-xs-push-3">
                        <?php

                        session_start();

                        if (!isset($_SESSION["purchased_items"])) {
                            $_SESSION["purchased_items"] = array();
                        }
                        $action = $_POST["action"];
                        unset($_POST["action"]);
                        if ($action == "purchase") {
                            foreach ($_POST as $key => $value) {
                                if (!isset($_SESSION["purchased_items"][$key])) {
                                    $_SESSION["purchased_items"][$key] = 0;
                                }
                                foreach ($_SESSION["purchased_items"] as $key1 => $value1) {
                                    if ($key1 == $key) {
                                        ++$_SESSION["purchased_items"][$key1];
                                        continue;
                                    }
                                }
                            }
                            echo '<span class="badge text-primary" style="font-size: 20px;background-color: green;">操作成功</span>';
                        } else if ($action == "withdraw") {
                            foreach ($_POST as $key => $value) {
                                foreach ($_SESSION["purchased_items"] as $key1 => $value1) {
                                    if ($key == $key1) {
                                        unset($_SESSION["purchased_items"][$key1]);
                                        continue;
                                    }
                                }
                            }
                            echo '<span class="badge text-primary" style="font-size: 20px;background-color: green;">操作成功</span>';
                        } else {
                            echo '<span class="badge text-primary" style="font-size: 20px;background-color: red;">操作失败</span>';
                        }
                        ?>
                    </div>
                </div>
                <div class="row" style="margin-top: 20px;">
                    <div class="col-xs-6 col-xs-push-3">
                        <a href="index.html" class="btn btn-default btn-block" role="button">返回</a>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                本网页使用BootStrap3构建 BY JLoeve
            </div>
        </div>
    </div>

    <!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@1.12.4/dist/jquery.min.js"></script>
    <!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
</body>

</html>