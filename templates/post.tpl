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
                    event.preventDefault();
                    $.post("api/post/add.php", {
                        "name": $("#name").val().trim(),
                        "email": $("#email").val().trim(),
                        "qq": $("#qq").val().trim(),
                        "homepage": $("#homepage").val().trim(),
                        "title": $("#title").val().trim(),
                        "content": $("#content").val().trim()
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

