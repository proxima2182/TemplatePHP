<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            Preview
        </h3>
        <div class="list-wrap">
            <ul>
                <?php for ($i = 0; $i < 8; $i++) {
                    if ($i % 4 == 0) { ?>
                        <li class="first">
                    <?php } else { ?>
                        <li>
                    <?php } ?>
                    <a href="javascript:openPopup(1);" class="button">
                        <div class="element-box">
                            <div class="image-wrap">
                                <img src="/asset/images/object.png">
                            </div>
                            <div class="text-wrap">
                                <h4 class="title">Lorem ipsum</h4>
                                <p class="content">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                    Proin semper dolor in purus iaculis ullamcorper. In eu posuere sapien, id
                                    finibus libero.</p>
                            </div>
                        </div>
                    </a>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <?= \App\Helpers\HtmlHelper::getPagination($pagination, $pagination_link); ?>
    </div>
</div>
<!--<div class="popup-wrap">-->
<!--    <div class="popup">-->
<!--        <div class="interface">-->
<!--            <a href="javascript:closePopup()" class="button close"><img-->
<!--                    src="/asset/images/icon/button_close_white.png"/></a>-->
<!--        </div>-->
<!--        <div class="popup-inner">-->
<!--            <div class="slider-wrap">-->
<!--                <div class="slick">-->
<!--                    <div class="slick-element"-->
<!--                         style="background: url('/asset/images/object.png') no-repeat; background-size: cover; font-size: 0;">-->
<!--                        Slider #0-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="text-wrap">-->
<!--                <h4 class="title">Lorem ipsum</h4>-->
<!--                <p class="content">Lorem ipsum dolor sit amet, consectetur adipiscing elit.-->
<!--                    Proin semper dolor in purus iaculis ullamcorper. In eu posuere sapien, id-->
<!--                    finibus libero.</p>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->
