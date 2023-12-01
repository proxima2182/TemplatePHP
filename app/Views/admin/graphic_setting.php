<?php

use Crisu83\ShortId\ShortId;

$shortid = ShortId::create();
$identifier = $shortid->generate();
?>
<script type="text/javascript">
    identifier = '<?=$identifier?>';
    <?php
    if (isset($graphic_settings)) {
    foreach ($graphic_settings as $key => $graphic_setting) {
    foreach ($graphic_setting as $index => $item) { ?>
    files.push('<?=$key?>', '<?=$item['id']?>');
    <?php }
    }
    }?>
</script>
<div class="container-inner">
    <div class="container-wrap">
        <h3 class="page-title">
            <?= lang('Service.graphic_setting') ?>
        </h3>
        <div class="content-box favicon">
            <h4 class="page-sub-title">
                <?= lang('Service.favicon') ?>
            </h4>
            <div class="content-wrap">
                <?php if (\App\Helpers\HtmlHelper::showDataEmpty($graphic_settings['favicon'] ?? null)) { ?>
                    <div class="content-wrap-inner lines-horizontal">
                        <?php foreach ($graphic_settings['favicon'] as $index => $item) { ?>
                            <div class="upload-item"
                                 style="background: url('/file/<?= $item['id'] ?>') no-repeat center; background-size: contain; font-size: 0;">
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <div class="control-button-wrap">
                <a href="javascript:editSettingFile('favicon');"
                   class="button under-line edit">
                    <img src="/asset/images/icon/edit.png"/>
                    <span><?= lang('Service.edit') ?></span>
                </a>
            </div>
        </div>
        <div class="content-box open_graph">
            <h4 class="page-sub-title">
                <?= lang('Service.open_graph') ?>
            </h4>
            <div class="content-wrap">
                <?php if (\App\Helpers\HtmlHelper::showDataEmpty($graphic_settings['open_graph'] ?? null)) { ?>
                    <div class="content-wrap-inner lines-horizontal">
                        <?php foreach ($graphic_settings['open_graph'] as $index => $item) { ?>
                            <div class="upload-item"
                                 style="background: url('/file/<?= $item['id'] ?>') no-repeat center; background-size: cover; font-size: 0;">
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <div class="control-button-wrap">
                <a href="javascript:editSettingFile('open_graph');"
                   class="button under-line edit">
                    <img src="/asset/images/icon/edit.png"/>
                    <span><?= lang('Service.edit') ?></span>
                </a>
            </div>
        </div>
        <div class="content-box logo">
            <h4 class="page-sub-title">
                <?= lang('Service.logo') ?>
            </h4>
            <div class="content-wrap">
                <?php if (\App\Helpers\HtmlHelper::showDataEmpty($graphic_settings['logo'] ?? null)) { ?>
                    <div class="content-wrap-inner lines-horizontal">
                        <?php foreach ($graphic_settings['logo'] as $index => $item) { ?>
                            <div class="upload-item"
                                 style="background: url('/file/<?= $item['id'] ?>') no-repeat center; background-size: contain; font-size: 0;">
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <div class="control-button-wrap">
                <a href="javascript:editSettingFile('logo');"
                   class="button under-line edit">
                    <img src="/asset/images/icon/edit.png"/>
                    <span><?= lang('Service.edit') ?></span>
                </a>
            </div>
        </div>
        <div class="content-box footer_logo">
            <h4 class="page-sub-title">
                <?= lang('Service.footer_logo') ?>
            </h4>
            <div class="content-wrap">
                <?php if (\App\Helpers\HtmlHelper::showDataEmpty($graphic_settings['footer_logo'] ?? null)) { ?>
                    <div class="content-wrap-inner lines-horizontal">
                        <?php foreach ($graphic_settings['footer_logo'] as $index => $item) { ?>
                            <div class="upload-item"
                                 style="background: url('/file/<?= $item['id'] ?>') no-repeat center; background-size: contain; font-size: 0;">
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <!-- class에 type 명 입력 필요-->
            <div class="control-button-wrap">
                <a href="javascript:editSettingFile('footer_logo');"
                   class="button under-line edit">
                    <img src="/asset/images/icon/edit.png"/>
                    <span><?= lang('Service.edit') ?></span>
                </a>
            </div>
        </div>
        <div class="content-box main_video">
            <h4 class="page-sub-title">
                <?= lang('Service.main_video') ?>
            </h4>
            <div class="content-wrap">
                <?php if (\App\Helpers\HtmlHelper::showDataEmpty($graphic_settings['main_video'] ?? null)) { ?>
                    <div class="content-wrap-inner lines-horizontal">
                        <?php foreach ($graphic_settings['main_video'] as $index => $item) { ?>
                            <div class="upload-item"
                                 style="background: url('/file/<?= $item['id'] ?>/thumbnail') no-repeat center; background-size: cover; font-size: 0;">
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <div class="control-button-wrap">
                <a href="javascript:editSettingFile('main_video');"
                   class="button under-line edit">
                    <img src="/asset/images/icon/edit.png"/>
                    <span><?= lang('Service.edit') ?></span>
                </a>
            </div>
        </div>
        <div class="content-box main_image">
            <h4 class="page-sub-title">
                <?= lang('Service.main_image') ?>
            </h4>
            <div class="content-wrap">
                <?php if (\App\Helpers\HtmlHelper::showDataEmpty($graphic_settings['main_image'] ?? null)) { ?>
                    <div class="content-wrap-inner slider-wrap lines-horizontal">
                        <div class="slick-wrap">
                            <div class="slick">
                                <?php foreach ($graphic_settings['main_image'] as $index => $item) { ?>
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
            <div class="control-button-wrap">
                <a href="javascript:editSettingFile('main_image');"
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
