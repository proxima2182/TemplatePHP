<?php

use Crisu83\ShortId\ShortId;

$shortid = ShortId::create();
$identifier = $shortid->generate();
?>
<script type="text/javascript">
    identifier = '<?=$identifier?>';
    <?php if (isset($logos)) {
    foreach ($logos as $index => $item) { ?>
    files.push('logo', '<?=$item['id']?>');
    <?php }
    }
    if (isset($footer_logos)) {
    foreach ($footer_logos as $index => $item) { ?>
    files.push('footer_logo', '<?=$item['id']?>');
    <?php }
    }
    if (isset($slider_images)) {
    foreach ($slider_images as $index => $item) { ?>
    files.push('image', '<?=$item['id']?>');
    <?php }
    }?>
    let video_mime_types = {};
    <?php if (isset($videos)) {
    foreach ($videos as $index => $item) { ?>
    files.push('video', '<?=$item['id']?>');
    video_mime_types['<?=$item['id']?>'] = '<?=$item['mime_type']?>';
    <?php }
    }?>
    console.log(files)
</script>
<div class="container-inner">
    <div class="container-wrap">
        <h3 class="page-title">
            <?= lang('Service.graphic_setting') ?>
        </h3>
        <div class="content-wrap logo">
            <h4 class="page-sub-title">
                <?= lang('Service.logo') ?>
            </h4>
            <div class="logo-box">
                <?php if (\App\Helpers\HtmlHelper::showDataEmpty($logos ?? null, 164)) { ?>
                    <div class="logo-wrap lines-horizontal">
                        <?php foreach ($logos as $index => $item) { ?>
                            <div class="upload-item"
                                 style="background: url('/file/<?= $item['id'] ?>') no-repeat center; background-size: cover; font-size: 0;">
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <div class="control-button-wrap logo">
                <a href="javascript:editLogo('logo');"
                   class="button under-line edit">
                    <img src="/asset/images/icon/edit.png"/>
                    <span><?= lang('Service.edit') ?></span>
                </a>
            </div>
        </div>
        <div class="content-wrap footer_logo">
            <h4 class="page-sub-title">
                <?= lang('Service.footer_logo') ?>
            </h4>
            <div class="footer-logo-box">
                <?php if (\App\Helpers\HtmlHelper::showDataEmpty($footer_logos ?? null, 164)) { ?>
                    <div class="footer-logo-wrap lines-horizontal">
                        <?php foreach ($footer_logos as $index => $item) { ?>
                            <div class="upload-item"
                                 style="background: url('/file/<?= $item['id'] ?>') no-repeat center; background-size: cover; font-size: 0;">
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <!-- class에 type 명 입력 필요-->
            <div class="control-button-wrap footer_logo">
                <a href="javascript:editLogo('footer_logo');"
                   class="button under-line edit">
                    <img src="/asset/images/icon/edit.png"/>
                    <span><?= lang('Service.edit') ?></span>
                </a>
            </div>
        </div>
        <div class="content-wrap video">
            <h4 class="page-sub-title">
                <?= lang('Service.video') ?>
            </h4>
            <div class="video-box">
                <?php if (\App\Helpers\HtmlHelper::showDataEmpty($videos ?? null, 314)) { ?>
                    <div class="video-wrap lines-horizontal">
                        <?php foreach ($videos as $index => $item) { ?>
                            <div class="upload-item"
                                 style="background: url('/file/<?= $item['id'] ?>/thumbnail') no-repeat center; background-size: cover; font-size: 0;">
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <div class="control-button-wrap video">
                <a href="javascript:editVideo();"
                   class="button under-line edit">
                    <img src="/asset/images/icon/edit.png"/>
                    <span><?= lang('Service.edit') ?></span>
                </a>
            </div>
        </div>
        <div class="content-wrap slider">
            <h4 class="page-sub-title">
                <?= lang('Service.slider_images') ?>
            </h4>
            <div class="slider-box">
                <?php if (\App\Helpers\HtmlHelper::showDataEmpty($slider_images ?? null, 312)) { ?>
                    <div class="slider-wrap lines-horizontal">
                        <div class="slick-wrap">
                            <div class="slick">
                                <?php foreach ($slider_images as $index => $item) { ?>
                                    <div class="slick-item button"
                                         style="background: url('/file/<?= $item['id'] ?>') no-repeat center; background-size: cover; font-size: 0;"
                                         onclick="openImagePopup(<?= $item['id'] ?>)">
                                        Slider #<?= $item['id'] ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="control-button-wrap slider">
                <a href="javascript:editSlider();"
                   class="button under-line edit">
                    <img src="/asset/images/icon/edit.png"/>
                    <span><?= lang('Service.edit') ?></span>
                </a>
            </div>
        </div>
        <!--        <div class="button-wrap">-->
        <!--            <a href="#"-->
        <!--               class="button confirm black">Confirm</a>-->
        <!--        </div>-->
    </div>
</div>
<?= \App\Helpers\HtmlHelper::setTranslations(['message_info_drag']) ?>
