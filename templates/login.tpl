{* smarty *}
{include file="head.tpl"}
{if isset($admin_name)}
    <script>
        {* 用户已登录，跳转至主页 *}
        window.location.href = "/";
    </script>
{/if}
<body>
<div class="container">
    {include file="nav.tpl"}
    <div class="panel main-panel">
        <div class="panel-body text-center">
            <h1>登录</h1>
            <div class="col-lg-4 col-md-3 col-xs-2"></div>
            <div class="col-lg-4 col-md-6 col-xs-8">
                <form role="form">
                    <div class="form-group text-left">
                        <label for="username" class="sr-only"></label>
                        <input type="text" class="form-control edit-box" id="username" name="username"
                               placeholder="请输入名称">
                        <label for="password" class="sr-only"></label>
                        <input type="password" class="form-control edit-box" id="password" name="password"
                               placeholder="请输入密码">
                    </div>
                    <div class="col-xs-6">
                        <button type="submit" class="btn btn-primary" style="width: 100%" id="login-btn">登录</button>
                    </div>
                    <div class="col-xs-6" title="注册接口未开放">
                        <a class="btn btn-default disabled" style="width: 100%">注册</a>
                    </div>
                </form>
            </div>
            <div class="col-lg-4 col-md-3 col-xs-2"></div>
        </div>
    </div>
</div>
</body>
{include file="bootstrap.tpl"}
<script>
    $(document).ready(
        function () {
            $("#login-btn").click(
                function (event) {
                    event.preventDefault();
                    $.post("api/account/login.php",
                        {
                            "username": $("#username").val(),
                            "password": $("#password").val()
                        },
                        function (data) {
                            if (data.code !== 0) {
                                // 懒得做更高级的提示，直接跳转到指定提示页面得了
                                window.location.href = "info.php?type=error&message=" + data.message + "&go_url=login.php";
                            } else {
                                window.location.href = "info.php?type=hint&message=" + data.message + "&go_url=/";
                            }
                        }
                    );
                }
            );
        }
    );
</script>

</html>
