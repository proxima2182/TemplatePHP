<?php

use Crisu83\ShortId\ShortId;

$shortid = ShortId::create();
$identifier = $shortid->generate();
?>
<script type="text/javascript">
    identifier = '<?=$identifier?>';
    <?php if (isset($images)) {
    foreach ($images as $index => $image) { ?>
    image_file_ids.push('<?=$image['id']?>')
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
        <?php if (\App\Helpers\HtmlHelper::checkArray([])) { ?>
            <div class="video-wrap">
            </div>
        <?php } ?>
        <div class="control-button-wrap video">
            <a href="#"
               class="button under-line edit">
                <img src="/asset/images/icon/edit.png"/>
                <span>Edit</span>
            </a>
        </div>
        <h4 class="page-sub-title">
            Slider Images
        </h4>
        <div class="slider-wrap">
            <?php if (\App\Helpers\HtmlHelper::checkArray($images)) { ?>
                <div class="slider-box">
                    <div class="slick">
                        <?php foreach ($images as $index => $image) { ?>
                            <div class="slick-item button"
                                 style="background: url('/image-file/<?= $image['id'] ?>') no-repeat center; background-size: cover; font-size: 0;"
                                 onclick="openImagePopup(<?= $image['id'] ?>)">
                                Slider #<?= $image['id'] ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="control-button-wrap slider">
            <a href="javascript:editTest();"
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

    function editTest() {
        let $parent = $(`.container-inner .slider-wrap`);
        $parent.empty();
        $parent.addClass('lines-horizontal')

        let html = ``;
        //todo change identifier
        html += `
        <div class="slider-box">
            <div class="slick uploader">
                <div class="slick-item add"
                     style="background: url('/asset/images/icon/plus_circle_big.png') no-repeat center; font-size: 0;">
                    <label for="file" class="button"></label>
                    <input type="file" name="file" multiple id="file"
                           onchange="onFileUpload(this, 'main');"
                           accept="image/*"/>
                </div>`;

        for (let i in image_file_ids) {
            let image_id = image_file_ids[i];
            html += `
            <div class="slick-item draggable-item" draggable="true"
                 style="background: url('/image-file/${image_id}') no-repeat center; background-size: cover; font-size: 0;">
                Slider #${image_id}
                <input hidden type="text" name="id" value="${image_id}">
                <div class="slick-item-hover">
                    <a href="javascript:deleteImage('${image_id}')"
                       class="button delete-image black">
                        <img src="/asset/images/icon/cancel_white.png"/>
                    </a>
                </div>
            </div>`;
        }
        html += `
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
            <a href="javascript:dropEditingImagesWithRefresh();"
               class="button under-line cancel">
                <img src="/asset/images/icon/cancel.png"/>
                <span>Cancel</span>
            </a>
            <a href="javascript:confirmEditImagesWithRefresh();"
               class="button under-line confirm">
                <img src="/asset/images/icon/check.png"/>
                <span>Confirm</span>
            </a>`)
    }

    function dropEditingImagesWithRefresh() {
        dropEditingImages(function () {
            location.reload();
        });
    }

    function confirmEditImagesWithRefresh() {
        confirmEditImages(function () {
            location.reload();
        });
    }
</script>
