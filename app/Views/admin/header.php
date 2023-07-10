<?php
if (!isset($links) && !isset($is_login)) return;
?>
<!doctype html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width"/>
    <title>Template</title>

    <link rel="stylesheet" type="text/css" href="/asset/font/fonts.css">
    <link rel="stylesheet" type="text/css" href="/asset/css/default.css">
    <link rel="stylesheet" type="text/css" href="/asset/css/style.css"/>
    <link rel="stylesheet" type="text/css" href="/asset/css/admin/include.css">
    <?php
    if (isset($css)) echo $css;
    ?>

    <script type="text/javascript" src="/asset/js/fullpage/jquery.min.js"></script>
    <script type="text/javascript" src="/asset/js/fullpage/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/asset/js/admin/navigation.js"></script>
    <?php
    if (isset($js)) echo $js;
    if (isset($javascript)) echo $javascript;
    ?>
</head>

<body>
<div id="wrap">
    <header id="header">
        <div class="header-inner">
            <div class="utill">
                <ul class="cf">
                    <?php if ($is_login) { ?>
                        <li><a href="/profile">Profile</a></li>
                        <li class="last"><a href="/logout">Logout</a></li>
                    <?php } else { ?>
                        <li><a href="/register">Register</a></li>
                        <li class="last"><a href="/login">Login</a></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="gnb-wrap fixed">
                <div class="gnb">
                    <ul class="cf">
                        <?php foreach ($links as $name => $link) { ?>
                            <li><a href="<?= $link ?>" class="button"><?= $name ?></a></li>
                        <?php } ?>
                    </ul>
                    <a href="javascript:closeNavigation()" class="button navigation close">
                        <span class="top"></span>
                        <span class="bottom"></span>
                    </a>
                </div>
            </div>
            <a href="javascript:closeNavigation()" class="button navigation menu">
                <span class="top"></span>
                <span class="middle"></span>
                <span class="bottom"></span>
            </a>
        </div>
    </header>
    <div id="container">
