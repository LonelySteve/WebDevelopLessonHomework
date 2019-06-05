{* smarty *}
<nav class="navbar navbar-default" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
            <span class="sr-only">展开导航</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/">留言板</a>
    </div>
    <div class="collapse navbar-collapse" id="menu">
        <ul class="nav navbar-nav">
            <li class="active"><a href="/">留言板</a></li>
            <li><a href="post.php">我要留言</a></li>
            {if isset($admin_name)}
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        {$admin_name} ，欢迎您~
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="logout.php">登出</a></li>
                    </ul>
                </li>
            {else}
                <li><a href="login.php">管理登录</a></li>
            {/if}
        </ul>
    </div>
</nav>
