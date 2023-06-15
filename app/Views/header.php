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

    <link rel="stylesheet" type="text/css" href="/asset/css/default.css">
    <link rel="stylesheet" type="text/css" href="/asset/css/include.css">
    <link rel="stylesheet" type="text/css" href="/asset/font/fonts.css">
</head>

<body>
<div id="wrap">
<header id="header">
    <div class="inner">
        <div class="utill">
            <ul class="cf">
                <?php
                if ($is_login) {
                    ?>
                    <li><a href="/profile">Profile</a></li>
                    <li class="last"><a href="/logout">Logout</a></li>
                    <?php
                } else {
                    ?>
                    <li><a href="/register">Register</a></li>
                    <li class="last"><a href="/login">Login</a></li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <h1 class="logo"><a href="/"><img src="/asset/images/include/logo.png" alt="header logo"></a></h1>
        <ul class="gnb cf">
            <?php
                foreach($links as $name => $link) {
            ?>
            <li><a href="<?= $link ?>"><?= $name ?></a></li>
            <?php
                }
            ?>
        </ul>
    </div>
</header>
<div id="container">
