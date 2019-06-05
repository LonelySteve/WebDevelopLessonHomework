{* smarty *}
{include file="head.tpl"}
<body>
<div class="container">
    {include file="nav.tpl"}
    <div class="panel main-panel">
        <div class="panel-body">
            <form role="form">
                <div class="form-group text-left">
                    <label for="name" class="necessary-item">昵称</label>
                    <input type="text" class="form-control edit-box" id="name" name="name"
                           placeholder="请输入昵称">
                    <label for="qq">QQ</label>
                    <input type="text" class="form-control edit-box" id="qq" name="qq"
                           placeholder="请输入QQ">
                    <label for="email">Email</label>
                    <input type="text" class="form-control edit-box" id="email" name="email"
                           placeholder="请输入Email">
                    <label for="homepage">主页</label>
                    <input type="text" class="form-control edit-box" id="homepage" name="homepage"
                           placeholder="请输入主页地址">
                    <label for="title" class="necessary-item">标题</label>
                    <input type="text" class="form-control edit-box" id="title" name="title"
                           placeholder="请输入标题">
                    <label for="content" class="necessary-item">内容</label>
                    <textarea rows="3" class="form-control edit-box" id="content" name="content"
                              placeholder="请输入内容"></textarea>
                </div>
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary" style="width: 100%" id="submit">提交</button>
                </div>
            </form>
        </div>
    </div>
</div>
{include file="bootstrap.tpl"}
<script>
    $(document).ready(
        function () {
            $("#submit").click(
                function (event) {
                    // 拦截默认行为
                    event.preventDefault();

                    function too_short(arg_name) {
                        alert(arg_name + "字段太短了！");
                    }

                    function too_long(arg_name) {
                        alert(arg_name + "字段太长了！");
                    }

                    function invalid_data(arg_name, hint) {
                        alert("无效的" + arg_name + "字段！" + hint);
                    }

                    function require(arg_name) {
                        alert("必须填入" + arg_name + "字段！");
                    }


                    var name = $("#name").val().trim();
                    var email = $("#email").val().trim();
                    var qq = $("#qq").val().trim();
                    var homepage = $("#homepage").val().trim();
                    var title = $("#title").val().trim();
                    var content = $("#content").val().trim();

                    // 必要值检查
                    if (!name) {
                        require("昵称");
                        return;
                    }

                    if (!title) {
                        require("标题");
                        return;
                    }

                    if (!content) {
                        require("内容");
                        return;
                    }

                    // 值长度检查，使用默认编码（Utf-8）解码字符串来计算长度
                    var encoder = new TextEncoder();

                    var name_len = encoder.encode(name).length;
                    var email_len = encoder.encode(email).length;
                    var qq_len = encoder.encode(qq).length;
                    var homepage_len = encoder.encode(homepage).length;
                    var title_len = encoder.encode(title).length;
                    var content_len = encoder.encode(content).length;

                    if (name_len < 2) {
                        too_short("昵称");
                        return;
                    } else if (name_len > 50) {
                        too_long("昵称");
                        return;
                    }

                    if (email_len > 50) {
                        too_long("邮箱");
                        return;
                    }

                    if (qq_len > 15) {
                        too_long("QQ");
                        return;
                    }

                    if (homepage_len > 50) {
                        too_long("主页");
                        return;
                    }

                    if (title_len < 2) {
                        too_short("标题");
                        return;
                    } else if (title_len > 50) {
                        too_long("标题");
                        return;
                    }

                    if (content_len < 2) {
                        too_short("内容");
                        return;
                    } else if (content_len > 233) {
                        too_long("内容");
                        return;
                    }

                    // 值合法性检查
                    {literal}
                    if (email && !email.match(/^[A-Za-z\d]+([-_.][A-Za-z\d]+)*@([A-Za-z\d]+[-.])+[A-Za-z\d]{2,4}$/)) {
                        invalid_data("邮箱", "正确的格式例如：yourname@foxmail.com");
                        return;
                    }
                    {/literal}

                    if (qq && !qq.match(/^\d+$/)) {
                        invalid_data("QQ", "正确的格式例如：906769162(最长15位)");
                        return;
                    }

                    if (homepage && !homepage.match(/^((https|http):\/\/)[^\s]+/)) {
                        invalid_data("主页", "正确的格式例如：https://blog.jloeve.top(最长50位，必须指明http或https协议)");
                        return;
                    }

                    $.post("api/post/add.php", {
                        "name": name,
                        "email": email,
                        "qq": qq,
                        "homepage": homepage,
                        "title": title,
                        "content": content
                    }, function (data) {
                        if (data.code === 0) {
                            window.location.href = "info.php?message=提交成功！&go_url=/";
                        } else {
                            window.location.href = "info.php?type=error&go_url=post.php&message=提交失败！" + data.message;
                        }
                    });
                }
            );
        }
    );
</script>
</body>
</html>

