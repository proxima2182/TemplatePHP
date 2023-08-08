<?php

use Crisu83\ShortId\ShortId;

if ($type == 'create') {
    $title = '';
    $content = '';
}
$shortid = ShortId::create();
$identifier = $shortid->generate();
?>

<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            <?= $alias ?>
        </h3>
        <div class="topic-wrap">
            <div class="form-box">
                <div class="row row-title line-after black">
                    <input type="text" placeholder="Title" name="title" class="column title editable"
                           value="<?= $title ?>"/>
                </div>
                <div class="text-wrap line-after">
                    <textarea placeholder="Content" name="content" class="content editable"><?= $content ?></textarea>
                </div>
                <input hidden type="text" name="board_id" class="editable" value="<?= $board_id ?>"/>
                <input hidden type="text" name="identifier" class="editable" value="<?= $identifier ?>"/>
            </div>
            <div class="slider-wrap">
                <div class="slider-box">
                    <div class="slick">
                        <div class="slick-element add"
                             style="background: url('/asset/images/icon/plus_circle_big.png') no-repeat center; font-size: 0;">
                            <label for="file" class="button"></label>
                            <input type="file" name="file" multiple id="file"
                                   onchange="onFileUpload(this,'<?= $identifier ?>');"
                                   accept="image/*"/>
                            <!--                            <a href="#" class="button"></a>-->
                        </div>
                        <?php if (isset($images)) {
                            foreach ($images as $index => $image) { ?>
                                <script>
                                    image_file_ids.push('<?=$image['id']?>');
                                </script>
                                <div class="slick-element"
                                     style="background: url('<?= $image ?>') no-repeat center; background-size: cover; font-size: 0;">
                                    Slider #<?= $index ?>
                                    <div class="slick-element-hover">
                                        <a href="javascript:deleteImage(<?= $index + 1 ?>)"
                                           class="button delete-image black">
                                            <img src="/asset/images/icon/cancel_white.png"/>
                                        </a>
                                    </div>
                                </div>
                            <?php }
                        } ?>
                    </div>
                </div>
            </div>
            <div class="button-wrap">
                <a href="<?= $type == 'create' ? 'javascript:confirmCreateTopic()' : 'javascript:confirmEditTopic(' . $id . ')' ?>"
                   class="button confirm black">Confirm</a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    let image_file_ids = [];

    $('.slider-wrap .slick').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: false,
        infinite: false,
    })

    function confirmEditTopic(id) {
        let data = parseInputToData($(`.form-box .editable`))

        $.ajax({
            type: 'POST',
            data: data,
            dataType: 'json',
            url: `/api/topic/update/${id}`,
            success: function (response, status, request) {
                if (!response.success) {
                    openPopupErrors('popup-error', response, status, request);
                    return;
                }
            },
            error: function (response, status, error) {
                console.log(error)
            },
        });
    }

    function confirmCreateTopic() {
        let data = parseInputToData($(`.form-box .editable`))

        data['images'] = image_file_ids;

        $.ajax({
            type: 'POST',
            url: `/api/topic/create`,
            data: data,
            dataType: 'json',
            success: function (response, status, request) {
                if (!response.success) {
                    openPopupErrors('popup-error', response, status, request);
                    return;
                }
                History.back();
            },
            error: function (response, status, error) {
                openPopupErrors('popup-error', response, status, error);
            },
        });
    }

    function deleteImage(id) {
        let index = image_file_ids.indexOf(id);
        if (index >= 0) {
            $('.slider-wrap .slick').slick('slickRemove', index + 1);
            image_file_ids.splice(index, 1);
            $.ajax({
                type: 'DELETE',
                url: `/api/image-file/delete/${id}`,
                dataType: 'json',
                success: function (response, status, request) {
                    openPopupErrors('popup-error', response, status, request);
                },
                error: function (response, status, error) {
                    openPopupErrors('popup-error', response, status, error);
                },
            });

        }
    }

    function onFileUpload(input, identifier) {
        if (input.files.length == 0) return;
        let form = new FormData();
        for (let i in input.files) {
            let file = input.files[i];
            form.append("file", file);
        }

        $.ajax({
            type: 'POST',
            url: `/api/image-file/upload/${identifier}`,
            data: form,
            processData: false,
            contentType: false,
            cache: false,
            dataType: "json",
            success: function (response, status, request) {
                if (!response.success) {
                    openPopupErrors('popup-error', response, status, request);
                    return;
                }
                let data = response.data;
                image_file_ids.push(data.id);

                $('.slider-wrap .slick').slick('slickAdd', `
                <div class="slick-element"
                     style="background: url('/image-file/${data.id}') no-repeat center; background-size: cover; font-size: 0;">
                    Slider #${data.id}
                    <div class="slick-element-hover">
                        <a href="javascript:deleteImage(${data.id})"
                           class="button delete-image black">
                            <img src="/asset/images/icon/cancel_white.png"/>
                        </a>
                    </div>
                </div>`);
                // reset input file
                input.type = ''
                input.type = 'file'
            },
            error: function (response, status, error) {
                openPopupErrors('popup-error', response, status, error);
                // reset input file
                input.type = ''
                input.type = 'file'
            },
        });
    }

    window.onbeforeunload = function () {
        $.ajax({
            type: 'POST',
            url: `/api/image-file/refresh/<?= $identifier ?>`,
            dataType: 'json',
        });
    };
</script>
