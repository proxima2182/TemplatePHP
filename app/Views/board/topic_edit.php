<?php
?>

<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            Notice
        </h3>
        <div class="topic-wrap">
            <div class="column-wrap line-after title">
                <input type="text" placeholder="Title" name="title" class="column title" value="<?= $title ?>"/>
            </div>
            <div class="text-wrap line-after">
                <textarea placeholder="Content" name="content" class="content"><?= $content ?></textarea>
            </div>
            <div class="slider-wrap">
                <div class="slider-box">
                    <div class="slick">
                        <?php foreach ($images as $index => $image) { ?>
                            <div class="slick-element"
                                 style="background: url('<?= $image ?>') no-repeat center; background-size: cover; font-size: 0;">
                                Slider #<?= $index ?>
                                <div class="slick-element-hover"></div>
                            </div>
                        <?php } ?>
                        <div class="slick-element"
                             style="background: url('/asset/images/icon/plus_circle.png') no-repeat center; font-size: 0;">
                            <a href="#" class="button"></a>
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
            success: function (data, textStatus, request) {
                history.back();
            },
            error: function (request, textStatus, error) {
                console.log(error)
            },
            dataType: 'json'
        });
    }
</script>
