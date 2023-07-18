<?php
?>

<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            Notice
        </h3>
        <div class="topic-wrap">
            <div class="row row-title line-after">
                <input type="text" placeholder="Title" name="title" class="column title" value="<?= $title ?>"/>
            </div>
            <div class="text-wrap line-after">
                <textarea placeholder="Content" name="content" class="content"><?= $content ?></textarea>
            </div>
            <div class="slider-wrap">
                <div class="slider-box">
                    <div class="slick">
                        <div class="slick-element add"
                             style="background: url('/asset/images/icon/plus_circle.png') no-repeat center; font-size: 0;">
                            <label for="file" class="button"></label>
                            <input type="file" name="file" multiple id="file" onchange="onFileUpload(this);"
                                    accept="image/*"/>
                            <!--                            <a href="#" class="button"></a>-->
                        </div>
                        <?php foreach ($images as $index => $image) { ?>
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
                        <?php } ?>

                        <div class="slick-element"
                             style="background: url('./writable/uploads/object.png') no-repeat center; background-size: cover; font-size: 0;">
                            Slider #1
                        </div>
                    </div>
                </div>
            </div>
            <div class="button-wrap">
                <a href="javascript:confirmEditTopic(<?= $id ?>)" class="button confirm black">Confirm</a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.slider-wrap .slick').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: false,
        infinite: false,
    })

    function confirmEditTopic(id) {
        let data = {};

        function appendData(element) {
            if (element.length == 0) return;
            let domElement = element.get(0)
            data[domElement.name] = domElement.value.toRawString();
        }

        appendData($('input[name=title]'))
        appendData($('textarea[name=content]'))

        $.ajax({
            type: 'POST',
            data: data,
            url: `/api/topic/update/${id}`,
            success: function (response, status, request) {
                //TODO refresh
            },
            error: function (request, status, error) {
                console.log(error)
            },
            dataType: 'json'
        });
    }

    function deleteImage(index) {
        $('.slider-wrap .slick').slick('slickRemove', index);
    }

    function onFileUpload(input, id) {
        if(input.files.length == 0) return;
        let form = new FormData();
        for(let i in input.files) {
            let file = input.files[i];
            form.append("file", file);
        }

        $.ajax({
            type: 'POST',
            url: `/api/image-file/upload`,
            data: form,
            processData: false,
            contentType: false,
            cache: false,
            dataType: "multipart/form-data",
            success: function (response, status, request) {
                console.log(response)
                // console.log(res.success);
                // if (res.success == true) {
                //     $('#ajaxImgUpload').attr('src', 'https://via.placeholder.com/300');
                //     $('#alertMsg').html(res.msg);
                //     $('#alertMessage').show();
                // } else if (res.success == false) {
                //     $('#alertMsg').html(res.msg);
                //     $('#alertMessage').show();
                // }
                // setTimeout(function () {
                //     $('#alertMsg').html('');
                //     $('#alertMessage').hide();
                // }, 4000);
                // $('.uploadBtn').html('Upload');
                // $('.uploadBtn').prop('Enabled');
                // document.getElementById("upload_image_form").reset();
            },
            error: function (request, status, error) {
            },
        });
    }
</script>
