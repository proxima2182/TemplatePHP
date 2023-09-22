<?php

use Crisu83\ShortId\ShortId;

$shortid = ShortId::create();
$identifier = $shortid->generate();
?>
<script type="text/javascript">
    identifier = '<?=$identifier?>';
    <?php if (isset($slider_images)) {
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
</script>
<div class="container-inner">
    <div class="container-wrap">
        <h3 class="page-title">
            Graphic Setting
        </h3>
        <h4 class="page-sub-title">
            Video
        </h4>
        <div class="video-box">
            <?php if (\App\Helpers\HtmlHelper::showDataEmpty($videos ?? null)) { ?>
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
                <span>Edit</span>
            </a>
        </div>
        <h4 class="page-sub-title">
            Slider Images
        </h4>
        <div class="slider-box">
            <?php if (\App\Helpers\HtmlHelper::showDataEmpty($slider_images ?? null)) { ?>
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
                <span>Edit</span>
            </a>
        </div>
        <!--        <div class="button-wrap">-->
        <!--            <a href="#"-->
        <!--               class="button confirm black">Confirm</a>-->
        <!--        </div>-->
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.slider-wrap .slick').slick({
            slidesToShow: 4,
            slidesToScroll: 4,
            autoplay: false,
            infinite: false,
            draggable: false,
        });
    })

    function editSlider() {
        let $parent = $(`.container-inner .slider-box`);
        $parent.empty();

        let html = `
        <div class="slider-wrap lines-horizontal">
            <div class="slick-wrap">`;
        //todo change identifier
        html += `
            <div class="slick uploader">`;

        for (let i in files.get('image')) {
            let file_id = files.get('image')[i];
            html += `
            <div class="slick-item draggable-item upload-item" draggable="true"
                 style="background: url('/file/${file_id}') no-repeat center; background-size: cover; font-size: 0;">
                Slider #${file_id}
                <input hidden type="text" name="id" value="${file_id}">
                <div class="upload-item-hover">
                    <a href="javascript:deleteImageFile('${file_id}')"
                       class="button delete-image black">
                        <img src="/asset/images/icon/cancel_white.png"/>
                    </a>
                </div>
            </div>`;
        }
        html += `
                <div class="slick-item upload-item-add"
                     style="background: url('/asset/images/icon/plus_circle_big.png') no-repeat center; font-size: 0;">
                    <label for="file" class="button"></label>
                    <input type="file" name="file" multiple id="file"
                           onchange="onFileUpload(this, 'image', 'main');"
                           accept="image/*"/>
                </div>
            </div>
            </div>
        </div>`;
        $parent.append(html);
        $parent.find('.slick').slick({
            slidesToShow: 4,
            slidesToScroll: 4,
            autoplay: false,
            infinite: false,
            draggable: false,
        });
        $parent.find('.slick').initDraggable({
            onDragFinished: onDragFinished
        });

        let $wrapButtonControls = $(`.control-button-wrap.slider`);
        $wrapButtonControls.empty();
        $wrapButtonControls.append(`
            <a href="javascript:cancelSliderEdit();"
               class="button under-line cancel">
                <img src="/asset/images/icon/cancel.png"/>
                <span>Cancel</span>
            </a>
            <a href="javascript:confirmSliderEdit();"
               class="button under-line confirm">
                <img src="/asset/images/icon/check.png"/>
                <span>Confirm</span>
            </a>`)
    }

    function cancelSliderEdit() {
        dropEditingFiles('image', function () {
            location.reload();
        });
    }

    function confirmSliderEdit() {
        confirmEditFiles('image', function () {
            location.reload();
        });
    }

    function editVideo() {
        let $parent = $(`.container-inner .video-box`);
        $parent.empty();

        let html = `<div class="video-wrap lines-horizontal">`;
        //todo change identifier
        if (files.get('video').length == 0) {
            html += `
            <div class="upload-item-add"
                 style="background: url('/asset/images/icon/plus_circle_big.png') no-repeat center; font-size: 0;">
                <label for="file" class="button"></label>
                <input type="file" name="file" multiple id="file"
                       onchange="onFileUpload(this, 'video', 'main', onVideoFileUploaded);"
                       accept="video/*"/>
            </div>`;
        } else {
            for (let i in files.get('video')) {
                let file_id = files.get('video')[i];
                html += `
                <div class="upload-item" style="background: url('/file/${file_id}/thumbnail') no-repeat center; background-size: cover; font-size: 0;">
                    <div class="upload-item-hover">
                        <a href="javascript:deleteVideoFile('${file_id}')"
                           class="button delete-image black">
                            <img src="/asset/images/icon/cancel_white.png"/>
                        </a>
                    </div>
                </div>`
            }
        }
        html += `
        </div>`;
        $parent.append(html);
        let $wrapButtonControls = $(`.control-button-wrap.video`);
        $wrapButtonControls.empty();
        $wrapButtonControls.append(`
            <a href="javascript:cancelVideoEdit();"
               class="button under-line cancel">
                <img src="/asset/images/icon/cancel.png"/>
                <span>Cancel</span>
            </a>
            <a href="javascript:confirmVideoEdit();"
               class="button under-line confirm">
                <img src="/asset/images/icon/check.png"/>
                <span>Confirm</span>
            </a>`)
    }

    function cancelVideoEdit() {
        dropEditingFiles('video', function () {
            location.reload();
        });
    }

    function confirmVideoEdit() {
        confirmEditFiles('video', function () {
            location.reload();
        });
    }

    function onVideoFileUploaded(file_id, mime_type) {
        let $parent = $(`.container-inner .video-box`);
        $parent.empty();
        $parent.append(`
        <div class="video-wrap lines-horizontal">
            <div class="upload-item" style="background: url('/file/${file_id}/thumbnail') no-repeat center; background-size: cover; font-size: 0;">
                <div class="upload-item-hover">
                    <a href="javascript:deleteVideoFile('${file_id}')"
                       class="button delete-image black">
                        <img src="/asset/images/icon/cancel_white.png"/>
                    </a>
                </div>
            </div>
        </div>`);
    }

    function deleteVideoFile(id) {
        let type = 'video';
        let index = files.get(type).indexOf(id);
        if (index < 0) return;
        files.splice(type, index);

        let $parent = $(`.container-inner .video-box .video-wrap`);
        $parent.empty();

        $parent.append(`
            <div class="upload-item-add"
                 style="background: url('/asset/images/icon/plus_circle_big.png') no-repeat center; font-size: 0;">
                <label for="file" class="button"></label>
                <input type="file" name="file" multiple id="file"
                       onchange="onFileUpload(this, 'video', 'main', onVideoFileUploaded);"
                       accept="video/*"/>
            </div>`);

    }
</script>
