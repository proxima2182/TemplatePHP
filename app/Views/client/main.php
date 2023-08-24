<?php

use App\Helpers\Utils;

if (!isset($links) && !isset($is_login)) return;

$sliderImages = [
    '/asset/images/slider/drilling-rig-4581167_1280.jpeg',
    '/asset/images/slider/film-4613426_1280.jpeg',
    '/asset/images/slider/geometry-7209216_1280.jpeg',
    '/asset/images/slider/record-player-1851576_1280.jpeg',
    '/asset/images/slider/vacation-2302013_1280.jpeg',
]
?>
<!doctype html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width"/>
    <title>Template</title>

    <link rel="stylesheet" type="text/css" href="/asset/font/fonts.css"/>
    <link rel="stylesheet" type="text/css" href="/asset/css/default.css"/>
    <link rel="stylesheet" type="text/css" href="/asset/css/style.css"/>
    <link rel="stylesheet" type="text/css" href="/asset/css/client/include.css"/>
    <link rel="stylesheet" type="text/css" href="/asset/css/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="/asset/css/client/main.css"/>
    <link rel="stylesheet" type="text/css" href="/asset/css/common/table.css"/>

    <script type="text/javascript" src="/asset/js/default.js"></script>
    <script type="text/javascript" src="/asset/js/fullpage/jquery.min.js"></script>
    <script type="text/javascript" src="/asset/js/fullpage/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/asset/js/fullpage/jquery.fullPage.js"></script>
    <script type="text/javascript" src="/asset/js/slick/slick.min.js"></script>
    <script type="text/javascript"
            src="//dapi.kakao.com/v2/maps/sdk.js?appkey=221aa6cfc43d262a0a90ca26facc9708"></script>

    <script type="text/javascript" src="/asset/js/module/popup.js"></script>
    <script type="text/javascript" src="/asset/js/module/login.js"></script>
    <script type="text/javascript" src="/asset/js/client/main.js"></script>
    <script type="text/javascript">
        addEventListener('customMapLoad', function () {
            let points = [];
            <?php foreach ($locations['array'] as $index => $point) { ?>
            points.push({
                name: '<?=$point['name']?>',
                latitude: '<?=$point['latitude']?>',
                longitude: '<?=$point['longitude']?>',
            })
            <?php
            }
            ?>
            setMapPoints(points)
        })
    </script>

</head>

<body>
<div id="container">
    <div class="section " id="page-start">
        <header id="header">
            <div class="header-inner">
                <div class="utill">
                    <ul class="cf">
                        <?php if ($is_login) { ?>
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
                    <?php foreach ($links as $i => $link) { ?>
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
                    <?php } ?>
                </ul>
            </div>
        </header>
        <div class="main-slider-wrap">
            <div class="slider-box">
                <div class="slick">
                    <?php foreach ($sliderImages as $index => $image) { ?>
                        <div class="slick-element"
                             style="background: url('<?= $image ?>') no-repeat center; background-size: cover; font-size: 0;">
                            Slider #<?= $index ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="slider-text-wrap">
                    <div class="text-wrap">
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

    <div class="section" id="page-intro">
        <video id="video" allow="autoplay;" muted autoplay loop>
            <source src="/asset/video/pexels-kelly-2491284-4096x2160-24fps.mp4" type="video/mp4">
        </video>

        <div class="page-inner whole-page">
            <div class="text-box">
                <div class="text-wrap">
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

    <div class="section" id="page-video">
        <div class="page-inner">
            <div class="inner-box">
                <iframe class="main-video"
                        width="680" height="400"
                        src="https://www.youtube.com/embed/isTo5kISXMg?rel=0&controls=0&playsinline=1"
                        title="YouTube video player"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen>
                </iframe>
                <hr>
                <div class="text-wrap">
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

    <?php if (isset($topics_grid)) { ?>
        <div class="section" id="page-notice">
            <div class="page-inner">
                <div class="inner-box">
                    <h3 class="title">
                        <?= $topics_grid['board_alias'] ?>
                    </h3>
                    <div class="table-box">
                        <?php if (!isset($topics_grid['array']) || sizeof($topics_grid['array']) == 0) { ?>
                            <div class="no-data-box">
                                <div class="no-data-wrap">
                                    <img src="/asset/images/icon/empty_folder.png">
                                    <span>No data available.</span>
                                </div>
                            </div>
                        <?php } else {
                            $array = $topics_grid['array']; ?>
                            <div class="table-wrap">
                                <div class="row-title">
                                    <div class="row">
                                        <span class="column title">Title</span>
                                        <span class="column  created-at">Created At</span>
                                    </div>
                                </div>
                                <ul>
                                    <?php foreach ($array as $index => $item) { ?>
                                        <li class=" row">
                                            <a href="#"
                                               class="button row-button">
                                                <span class="column title"><?= $item['title'] ?></span>
                                                <span class="column created-at"><?= $item['created_at'] ?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div class="button-wrap">
                                <a href="<?= $topics_grid['link'] ?>" class="button more">
                                    <span>See More</span>
                                    <img src="/asset/images/icon/arrow_right.png"/>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if (isset($topics_grid)) { ?>
        <div class="section" id="page-preview">
            <div class="page-inner">
                <div class="inner-box">
                    <h3 class="title">
                        <?= $topics_grid['board_alias'] ?>
                    </h3>
                    <div class="slider-box">
                        <?php if (!isset($topics_grid['array']) || sizeof($topics_grid['array']) == 0) { ?>
                            <div class="no-data-box">
                                <div class="no-data-wrap">
                                    <img src="/asset/images/icon/empty_folder.png">
                                    <span>No data available.</span>
                                </div>
                            </div>
                        <?php } else {
                            $array = $topics_grid['array']; ?>
                            <div class="slick slider-wrap">
                                <?php foreach ($array as $index => $item) { ?>
                                    <div class="slick-element">
                                        <div class="element-box">
                                            <?php if (isset($item['image_id'])) { ?>
                                                <div class="image-wrap"
                                                     style="background: url('/image-file/<?= $item['image_id'] ?>') no-repeat center; background-size: cover; font-size: 0;">
                                                </div>
                                            <?php } ?>
                                            <div class="text-wrap">
                                                <h4 class="title"><?= $item['title'] ?></h4>
                                                <p class="content"><?= $item['content'] ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="button-wrap">
                                <a href="<?= $topics_grid['link'] ?>" class="button more">
                                    <span>See More</span>
                                    <img src="/asset/images/icon/arrow_right.png"/>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if (isset($locations)) {
        $locationPagination = $locations['pagination']; ?>
        <div class="section" id="page-map">
            <div class="page-inner whole-page">
                <div class="location-list-box">
                    <h3 class="title">Map Locations</h3>
                    <div class="location-list-wrap" page="<?= $locationPagination['page'] ?>"
                         per-page="<?= $locationPagination['per-page'] ?>"
                         total="<?= $locationPagination['total'] ?>"
                         total-page="<?= $locationPagination['total-page'] ?>">
                        <ul>
                            <?php foreach ($locations['array'] as $index => $point) { ?>
                                <li class="button">
                                    <div class="text-wrap">
                                        <div class="title"><?= $point['name'] ?></div>
                                        <div class="content"><?= $point['address'] ?></div>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="map-box">
                    <div id="map" style="width:100%;height:100%;"></div>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="section" id="page-last">
        <div class="page-inner">
            <div class="form-box">
                <div class="form-wrap">
                    <h3 class="title">Enquiries about membership</h3>
                    <div class="input-wrap inline">
                        <p class="input-title">Name</p>
                        <input type="text" name="name"/>
                    </div>
                    <div class="input-wrap inline">
                        <p class="input-title">Phone Number</p>
                        <input type="phone" name="phone"/>
                    </div>
                    <div class="input-wrap inline">
                        <p class="input-title">Region</p>
                        <input type="text" name="region"/>
                    </div>
                    <div class="input-wrap inline">
                        <p class="input-title">Inquiry</p>
                        <textarea class="content" rows="4" name="content"></textarea>
                    </div>
                    <div class="text-wrap">
                        <div class="title"></div>
                        <div class="content">
                            작성하신 이름과 전화번호는 상담접수 용도로만 사용됩니다.
                            <br/>
                            - 개인정보 수집, 이용 목적 : 상담접수
                            <br/>
                            - 수집하려는 개인정보 항목 : 이름, 전화번호
                            <br/>
                            - 개인정보의 보유 및 이용기간 : 상담 완료 후 파기
                        </div>
                    </div>
                    <div class="input-wrap inline agreement">
                        <span class="input-title">개인 정보 취급 방침에 동의</span>
                        <input type="checkbox" name="agreement"/>
                    </div>
                    <div class="submit-box">
                        <a href="javascript:requestRegister();" class="button submit black">제출</a>
                    </div>
                </div>
            </div>
        </div>
        <footer id="footer">
            <div class="footer-inner">
                <a href="/" class="logo"><img src="/asset/images/include/logo_footer.png" alt="footer logo"></a>
                <div class="text-wrap">
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
