{* smarty *}
{include file="head.tpl"}
{if !isset($type)}
{$type="hint"}
{/if}
{if !isset($go_url)}
{$go_url="/"}
{/if}
{if !isset($time)}
{$time=5}
{/if}
<body>

<div class="container">
    <div class="panel main-panel">
        <div class="panel-body text-center">
            <div class="row">
                {if $type == "hint"}
                    <h1>提示</h1>
                {else}
                    <h1 class="text-danger">错误</h1>
                {/if}
            </div>
            <div class="row">
                <p>{$message}</p>
            </div>
            {if $go_url}
                <div class="row">
                    <span class="text-muted" id="time-hint">还有{$time}秒将自动跳转</span>
                </div>
            {/if}
        </div>
    </div>
</div>

{if $go_url}
    {include file="bootstrap.tpl"}
    <script>
        $(document).ready(
            function () {
                setTimeout(function () {
                    window.location.href = "{$go_url}";
                    $("#time-hint").html("如果未能成功跳转，请点击<a herf='{$go_url}'>这里</a>手动跳转")
                }, {$time * 1000});
            }
        );
    </script>
{/if}

</body>

</html>