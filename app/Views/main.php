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

    <link rel="stylesheet" type="text/css" href="/asset/css/default.css"/>
    <link rel="stylesheet" type="text/css" href="/asset/css/include.css"/>
    <link rel="stylesheet" type="text/css" href="/asset/font/fonts.css"/>
    <link rel="stylesheet" type="text/css" href="/asset/css/slick/slick.css"/>

    <script type="text/javascript" src="/asset/js/fullpage/jquery.min.js"></script>
    <script type="text/javascript" src="/asset/js/fullpage/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/asset/js/fullpage/jquery.fullPage.js"></script>
    <script type="text/javascript" src="/asset/js/slick/slick.min.js"></script>

    <script type="text/javascript" src="/asset/js/page/main.js"></script>

    <link rel="stylesheet" type="text/css" href="/asset/css/page/main.css"/>
</head>

<body>
<div id="fullpage">
    <div class="section " id="section_start">
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
                    foreach ($links as $name => $link) {
                        ?>
                        <li><a href="<?= $link ?>"><?= $name ?></a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </header>
        <div class="main-slider-wrap">
            <div class="content-wrap">
                <div class="slick">
                    <div class="slick-element"
                         style="background: url('/asset/images/slider/drilling-rig-4581167_1280.jpeg') no-repeat; background-size: cover; font-size: 0;">
                        Slider #01
                    </div>
                    <div class="slick-element"
                         style="background: url('/asset/images/slider/film-4613426_1280.jpeg') no-repeat; background-size: cover; font-size: 0;">
                        Slider #02
                    </div>
                    <div class="slick-element"
                         style="background: url('/asset/images/slider/geometry-7209216_1280.jpeg') no-repeat; background-size: cover; font-size: 0;">
                        Slider #03
                    </div>
                    <div class="slick-element"
                         style="background: url('/asset/images/slider/record-player-1851576_1280.jpeg') no-repeat; background-size: cover; font-size: 0;">
                        Slider #04
                    </div>
                    <div class="slick-element"
                         style="background: url('/asset/images/slider/vacation-2302013_1280.jpeg') no-repeat; background-size: cover; font-size: 0;">
                        Slider #05
                    </div>
                </div>
                <div class="slider-text-wrap">
                    <div class="text-box">
                        <h3 class="title">Lorem ipsum</h3>
                        <ul class="content cf">
                            <li><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></li>
                            <li><p>Proin semper dolor in purus iaculis ullamcorper.</p></li>
                            <li><p>In eu posuere sapien, id finibus libero.</p></li>
                            <li><p>Fusce fringilla enim dolor.</p></li>
                            <li><p>Vivamus et arcu sit amet ante gravida malesuada.</p></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section" id="section_01">
        <video id="video" allow="autoplay;" muted autoplay loop>
            <source src="/asset/video/pexels-kelly-2491284-4096x2160-24fps.mp4" type="video/mp4">
        </video>

        <div class="inner">
            <div class="wrap">
                <div class="text-box">
                    <h3 class="title">Lorem ipsum</h3>
                    <ul class="content cf">
                        <li><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></li>
                        <li><p>Proin semper dolor in purus iaculis ullamcorper.</p></li>
                        <li><p>In eu posuere sapien, id finibus libero.</p></li>
                        <li><p>Fusce fringilla enim dolor.</p></li>
                        <li><p>Vivamus et arcu sit amet ante gravida malesuada.</p></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="section" id="section_last">
        <footer id="footer">
            <div class="inner">
                <a href="/" class="logo"><img src="/asset/images/include/logo_footer.png" alt="footer logo"></a>
                <div class="text-box">
                    <ul class="cf">
                        <li><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p></li>
                        <li><p>Mauris laoreet lacinia neque, quis maximus felis finibus id.</p></li>
                    </ul>
                </div>
                <div class="terms">
                    copyright 2023. Lorem Ipsum all rights reserved.
                </div>
            </div>
        </footer>
    </div>
</div>

</body>
</html>
