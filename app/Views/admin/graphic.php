<?php

use Crisu83\ShortId\ShortId;

$shortid = ShortId::create();
$identifier = $shortid->generate();
?>
<script type="text/javascript">
    identifier = '<?=$identifier?>';
    <?php if (isset($slider_images)) {
    foreach ($slider_images as $index => $item) { ?>
    image_file_ids.push('<?=$item['id']?>')
    <?php }
    }?>
    let video_id = null;
    let video_mime_type = null;
    <?php if (isset($videos)) {
    foreach ($videos as $index => $item) { ?>
    video_id = '<?=$item['id']?>';
    video_mime_type = '<?=$item['mime_type']?>';
    <?php }
    }?>
</script>
<div class="container-inner">
    <div class="inner-box">
        <h3 class="page-title">
            Graphic
        </h3>
        <h4 class="page-sub-title">
            Video
        </h4>
        <div class="video-box">
            <?php if (\App\Helpers\HtmlHelper::checkArray($videos)) { ?>
                <div class="video-wrap lines-horizontal">
                    <div class="upload-item">
                        <?php foreach ($videos as $index => $item) { ?>
                            <video muted loop>
                                <source src="/file/<?= $item['id'] ?>" type="<?= $item['mime_type'] ?>">
                            </video>
                        <?php } ?>
                    </div>
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
            <?php if (\App\Helpers\HtmlHelper::checkArray($slider_images)) { ?>
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
            <div class="slick uploader">
                <div class="slick-item upload-item-add"
                     style="background: url('/asset/images/icon/plus_circle_big.png') no-repeat center; font-size: 0;">
                    <label for="file" class="button"></label>
                    <input type="file" name="file" multiple id="file"
                           onchange="onFileUpload(this, 'image', 'main');"
                           accept="image/*"/>
                </div>`;

        for (let i in image_file_ids) {
            let image_id = image_file_ids[i];
            html += `
            <div class="slick-item draggable-item upload-item" draggable="true"
                 style="background: url('/file/${image_id}') no-repeat center; background-size: cover; font-size: 0;">
                Slider #${image_id}
                <input hidden type="text" name="id" value="${image_id}">
                <div class="upload-item-hover">
                    <a href="javascript:deleteImage('${image_id}')"
                       class="button delete-image black">
                        <img src="/asset/images/icon/cancel_white.png"/>
                    </a>
                </div>
            </div>`;
        }
        html += `
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
        dropEditingImages('image', function () {
            location.reload();
        });
    }

    function confirmSliderEdit() {
        confirmEditImages('image', {
            files: image_file_ids,
        }, function () {
            location.reload();
        });
    }

    $(document).ready(function () {
        $(`.container-inner .video-wrap .upload-item`).setVideoCoverStyle();
    })

    function editVideo() {
        let $parent = $(`.container-inner .video-box`);
        $parent.empty();

        let html = `<div class="video-wrap lines-horizontal">`;
        //todo change identifier
        if (!video_id || !video_mime_type) {
            html += `
            <div class="upload-item-add"
                 style="background: url('/asset/images/icon/plus_circle_big.png') no-repeat center; font-size: 0;">
                <label for="file" class="button"></label>
                <input type="file" name="file" multiple id="file"
                       onchange="onFileUpload(this, 'video', 'main');"
                       accept="video/*"/>
            </div>`;
        } else {
            html += `
            <div class="upload-item">
                <video muted loop>
                    <source src="/file/${video_id}" type="${video_mime_type}">
                </video>
                <div class="upload-item-hover">
                    <a href="javascript:deleteVideo('${video_id}')"
                       class="button delete-image black">
                        <img src="/asset/images/icon/cancel_white.png"/>
                    </a>
                </div>
            </div>`
        }
        html += `
        </div>`;
        $parent.append(html);
        // $parent.find('.slick').slick({
        //     slidesToShow: 4,
        //     slidesToScroll: 4,
        //     autoplay: false,
        //     infinite: false,
        //     draggable: false,
        // });
        // $parent.find('.slick').initDraggable({
        //     onDragFinished: onDragFinished
        // });
        $parent.find('.upload-item').setVideoCoverStyle();

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
        dropEditingImages('video', function () {
            location.reload();
        });
    }

    function confirmVideoEdit() {
        let files = [];
        if (video_id) {
            files.add(video_id);
        }
        confirmEditImages('video', {
            files: files,
        }, function () {
            location.reload();
        });
    }

    function deleteVideo() {
        video_mime_type = null;
        video_id = null;

        let $parent = $(`.container-inner .video-box .video-wrap`);
        $parent.empty();

        $parent.append(`
            <div class="upload-item-add"
                 style="background: url('/asset/images/icon/plus_circle_big.png') no-repeat center; font-size: 0;">
                <label for="file" class="button"></label>
                <input type="file" name="file" multiple id="file"
                       onchange="onFileUpload(this, 'video', 'main');"
                       accept="video/*"/>
            </div>`);

    }
</script>
