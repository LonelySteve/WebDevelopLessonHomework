<!DOCTYPE html>
<html lang="zh-cn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <!-- 引用阿里图标 -->
    <link rel="stylesheet" href="https://at.alicdn.com/t/font_1170412_j8dpwz4h0vo.css">
    {if $__debug__}
        {* debug 模式下加载本地资源 *}
        <link rel="stylesheet" href="static/lib/bootstrap-3.3.7-dist/css/bootstrap.css">
        <link rel="stylesheet" href="static/lib/font-awesome-4.7.0/css/font-awesome.css">
    {else}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    {/if}
    <link rel="stylesheet" href="static/css/main.css">

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow-y: hidden;
        }

        .container {
            height: 75%;
        }

        .main-panel,
        .panel-body {
            height: 100%;
        }
    </style>

    <title>{$__title__}</title>

</head>
