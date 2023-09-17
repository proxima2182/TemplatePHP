<?php

use App\Helpers\Utils;

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
    <link rel="stylesheet" type="text/css" href="/asset/css/client/style.css"/>
    <link rel="stylesheet" type="text/css" href="/asset/css/client/include.css">
    <?php
    if (isset($css)) echo $css;
    ?>

    <script type="text/javascript" src="/asset/js/library/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="/asset/js/default.js"></script>
    <script type="text/javascript" src="/asset/js/module/popup.js"></script>
    <script type="text/javascript" src="/asset/js/client/login.js"></script>
    <?php
    if (isset($js)) echo $js;
    if (isset($javascript)) echo $javascript;
    ?>
</head>

<body>
<div class="loading-wrap">
    <span class="gadget"></span>
</div>
<div id="wrap">
    <header id="header">
        <div class="header-inner">
            <div class="utill">
                <ul class="cf">
                    <?php if ($is_login) {
                        if ($is_admin) { ?>
                            <li><a href="/admin">Admin Page</a></li>
                        <?php } ?>
                        <li><a href="/profile">Profile</a></li>
                        <li class="last"><a href="javascript:logout();">Logout</a></li>
                    <?php } else { ?>
                        <li><a href="/registration">Register</a></li>
                        <li class="last"><a href="javascript:openPopupLogin();">Login</a></li>
                    <?php } ?>
                </ul>
            </div>
            <h1 class="logo"><a href="/"><img src="/asset/images/include/logo.png" alt="header logo"></a></h1>
            <ul class="gnb cf">
                <?php
                foreach ($links as $i => $link) {
                    if ($link['is_main_only'] == 0) { ?>
                        <li>
                            <a href="<?= Utils::parseUrl($link['path']) ?>"><?= $link['name'] ?></a>
                            <ul class="lnb">
                                <?php if ($link['has_local'] == 1) {
                                    foreach ($link['locals'] as $j => $localLink) { ?>
                                        <li>
                                            <a href="<?= Utils::parseUrl($localLink['path']) ?>"><?= $localLink['name'] ?></a>
                                        </li>
                                    <?php }
                                } ?>
                            </ul>
                        </li>
                    <?php }
                } ?>
            </ul>
        </div>
    </header>
    <div id="container">
