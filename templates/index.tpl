{* Smarty *}
{include file="head.tpl"}
<body>
<div class="container">
    {include file="nav.tpl"}
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
                                <!-- 主体内容 -->
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
                        {if isset($admin_name) && $admin_name}
                            <div class="btn-group menu-group">
                                <a role="btn" class="btn" data-toggle="dropdown">
                                    <i class="iconfont icon-more"></i>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a onclick="reply_post({$data->pid})">回复</a></li>
                                    <li class="divider"></li>
                                    <li><a onclick="delete_post({$data->pid})">删除</a></li>
                                </ul>
                            </div>
                        {/if}
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
                            {* 第二种情况 ： 页数大于 5个 *}
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
{include file="bootstrap.tpl"}
<script>
    function delete_post(pid) {
        if (confirm("确定删除pid:" + pid + "的留言吗？")) {
            $.get("api/post/delete.php?pid=" + pid, function (data) {
                if (data.code === 0) {
                    window.location.href = "info.php?message=删除成功！&time=1";
                } else {
                    window.location.href = "info.php?message='删除失败！" + data.message;
                }
            });
        }
    }

    function reply_post(pid) {
        input = prompt("请输入对pid:" + pid + "的回复，注意：这将覆盖原有的回复！");
        if (input) {
            $.post("api/post/update_reply.php",
                {
                    "pid": pid,
                    "content": input
                },
                function (data) {
                    if (data.code === 0) {
                        window.location.href = "info.php?message=回复成功！&time=1";
                    } else {
                        window.location.href = "info.php?message='回复失败！" + data.message;
                    }
                });
        }
    }

    $(document).ready(function () {
        $("#text-target-page").keyup(function (event) {
            if (event.which === 13) {
                if (this.value >= 1)
                    window.open("/?page=" + this.value, "_self");
                else
                    window.open("/?page=" + 1, "_self");
            }
        })
    });
</script>
</body>

</html>