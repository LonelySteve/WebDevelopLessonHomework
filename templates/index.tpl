{* Smarty *}
{include file="head.tpl"}
<body>
<div class="container">
    <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="/">留言板</a>
            </div>
            <div>
                <ul class="nav navbar-nav">
                    <li><a href="#">我要留言</a></li>
                    <li><a href="#">登录</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="panel main-panel">
        <div class="panel-body">
            {foreach $__data__.posts as $data}
                <div class="card panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-2 col-md-2 text-center">
                                <!-- 头像 -->
                                <div class="row">
                                    <div class="col-xs-12">
                                        <a href="#">
                                            <img src="img/face/default.png" alt="{$data->name}"
                                                 class="face img-circle center-block" width="50" height="50">
                                        </a>
                                    </div>
                                </div>
                                <!-- 各种其他信息入口 -->
                                <div class="row">
                                    <div class="col-xs-12">

                                        {if $data->qq}
                                            <!-- QQ -->
                                            <a href="tencent://message/?uin={$data->qq}&Site=qq&Menu=yes">
                                            <span class="fa-stack">
                                                <i class="fa fa-circle fa-stack-2x"></i>
                                                <i class="fa iconfont icon-QQ fa-stack-1x fa-inverse"></i>
                                            </span>
                                            </a>
                                        {/if}

                                        {if $data->email}
                                            <!-- Email -->
                                            <a href="mailto:{$data->email}">
                                            <span class="fa-stack">
                                                <i class="fa fa-circle fa-stack-2x"></i>
                                                <i class="fa iconfont icon-mail-fill fa-stack-1x fa-inverse"></i>
                                            </span>
                                            </a>
                                        {/if}

                                        {if $data->homepage}
                                            <!-- 主页 -->
                                            <a href="{$data->homepage}">
                                            <span class="fa-stack">
                                                <i class="fa fa-circle fa-stack-2x"></i>
                                                <i class="fa iconfont icon-home-fill fa-stack-1x fa-inverse"></i>
                                            </span>
                                            </a>
                                        {/if}

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9 col-md-9">
                                <!-- 标题 -->
                                <div class="row">
                                    <div class="col-xs-12 text-justify text-left">
                                        <h4>{$data->title}</h4>
                                    </div>
                                </div>
                                <!-- 昵称与发布时间-->
                                <div class="row">
                                    <div class="col-xs-12 text-justify text-left">
                                        <!-- 发布时间 -->
                                        <small>
                                            <!-- 昵称 -->
                                            <a href="#" class="text-primary">{$data->name}</a>
                                            <span class="text-muted">发布于&nbsp;{$data->create_time}</span>
                                        </small>
                                    </div>
                                </div>
                                <!-- 被截断的主体内容 -->
                                <div class="row">
                                    <div class="col-xs-12 text-justify text-left">
                                        <p class="brief">
                                            {$data->content}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {if $data->reply}
                                <div class="col-xs-12">
                                    <div class="card panel">
                                        <div class="col-xs-12">
                                            <h4>{$data->reply_admin_name}</h4>
                                        </div>
                                        <div class="col-xs-12">
                                            <small class="text-muted">{$data->reply_create_time}</small>
                                        </div>
                                        <div class="col-xs-12">
                                            <p>{$data->reply}</p>
                                        </div>
                                    </div>
                                </div>
                            {/if}
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
            {/foreach}
        </div>
        <div class="panel-footer">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <ul class="pagination">
                        {* 前后必须保留首页码和尾页码，当前选择的页码左右两边最多加两个页码，再多就使用禁用的省略号页码 *}
                        {* 第一种情况 ： 页数少于等于 5个 *}
                        {if $__data__.page_count <= 5}
                            {for $i=1 to $__data__.page_count}
                                <li><a href="/?page={$i}">{$i}</a></li>
                            {/for}
                        {else}
                            <li><a href="/?page=1">1</a></li>
                            {if $__data__.cur_page-1>2}
                                <li><a href="#" class="disabled">...</a></li>
                                <li><a href="/?page={$__data__.cur_page-1}">{$__data__.cur_page-1}</a></li>
                            {elseif $__data__.cur_page-1==2}
                                <li><a href="/?page={$__data__.cur_page-1}">{$__data__.cur_page-1}</a></li>
                            {/if}
                            {if $__data__.cur_page!=1 && $__data__.cur_page!=$__data__.page_count}
                                <li><a href="/?page={$__data__.cur_page}">{$__data__.cur_page}</a></li>
                            {/if}
                            {if $__data__.page_count-$__data__.cur_page>2}
                                <li><a href="/?page={$__data__.cur_page+1}">{$__data__.cur_page+1}</a></li>
                                <li><a href="#" class="disabled">...</a></li>
                            {elseif $__data__.page_count-$__data__.cur_page==2}
                                <li><a href="/?page={$__data__.cur_page+1}">{$__data__.cur_page+1}</a></li>
                            {/if}
                            <li><a href="/?page={$__data__.page_count}">{$__data__.page_count}</a></li>
                        {/if}
                    </ul>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                        <span class="page-jump">
                            目前在第 {$__data__.cur_page} 页
                            ，
                            共
                            <span>{$__data__.page_count}</span>
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
    var resize_callback = function () {
        $(".main-panel > .panel-body").css("height", $(window).height() - 200);
    };
    $(document).ready(function () {
        resize_callback();
        $(window).resize(resize_callback);
    });
</script>
</body>

</html>