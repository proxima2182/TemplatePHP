<?php
$is_admin_page = isset($is_admin) && $is_admin;
?>
<?php
$reply = [
    'page' => 1,
    'per-page' => 5,
    'total' => 13,
    'total-page' => 3,
    'array' => [
        [
            'id' => 0,
            'user_name' => 'Lorem Ipsum',
            'depth' => 0,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'created_at' => '2023-07-03 22:35:00',
            'nested_reply' => [
                'page' => 1,
                'per-page' => 10,
                'total' => 2,
                'total-page' => 3,
                'array' => [
                    [
                        'user_name' => 'Lorem Ipsum',
                        'depth' => 1,
                        'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                        'created_at' => '2023-07-03 22:35:00',
                    ],
                    [
                        'user_name' => 'Lorem Ipsum',
                        'depth' => 1,
                        'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                        'created_at' => '2023-07-03 22:35:00',
                    ],
                ],
            ],
        ],
        [
            'id' => 1,
            'user_name' => 'Lorem Ipsum',
            'depth' => 0,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'created_at' => '2023-07-03 22:35:00',
            'nested_reply' => [
                'page' => 1,
                'per-page' => 10,
                'total' => 0,
                'total-page' => 0,
                'array' => [],
            ],
        ],
        [
            'id' => 3,
            'user_name' => 'Lorem Ipsum',
            'depth' => 0,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'created_at' => '2023-07-03 22:35:00',
            'nested_reply' => [
                'page' => 1,
                'per-page' => 10,
                'total' => 0,
                'total-page' => 0,
                'array' => [],
            ],
        ],
        [
            'id' => 4,
            'user_name' => 'Lorem Ipsum',
            'depth' => 0,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'created_at' => '2023-07-03 22:35:00',
            'nested_reply' => [
                'page' => 1,
                'per-page' => 10,
                'total' => 0,
                'total-page' => 0,
                'array' => [],
            ],
        ],
        [
            'id' => 5,
            'user_name' => 'Lorem Ipsum',
            'depth' => 0,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'created_at' => '2023-07-03 22:35:00',
            'nested_reply' => [
                'page' => 1,
                'per-page' => 10,
                'total' => 0,
                'total-page' => 0,
                'array' => [],
            ],
        ],
    ],
];
?>

<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            Notice
        </h3>
        <div class="topic-wrap">
            <div class="column-wrap line-after title">
                <span class="column title"><?= $title ?></span>
                <span class="column created_at"><?= $created_at ?></span>
            </div>
            <div class="text-wrap line-after">
                <div class="content"><?= $content ?></div>
            </div>
            <div class="slider-wrap">
                <div class="slider-box">
                    <div class="slick">
                        <?php foreach ($images as $index => $image) { ?>
                            <div class="slick-element"
                                 style="background: url('<?= $image ?>') no-repeat center; background-size: cover; font-size: 0;">
                                Slider #<?= $index ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="control-wrap">
                <a href="<?= $is_admin_page ? '/admin/board/topic/' . $id . '/edit' : '/board/topic/' . $id . '/edit' ?>"
                   class="button edit">
                    <img src="/asset/images/icon/edit.png"/>
                    <span>Edit</span>
                </a>
                <?php if ($is_admin_page) { ?>
                    <a href="javascript:openPopupDeleteTopic(<?= $id ?>)" class="button delete">
                        <img src="/asset/images/icon/delete.png"/>
                        <span>Delete</span>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?= \App\Helpers\HtmlHelper::getReply($reply); ?>
<script type="text/javascript">
    $('.slider-wrap .slick').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: false,
        infinite: false,
    })

    function openPopupDeleteTopic(id) {
        let style = `
                <style>
                .popup-inner .text-wrap {
                    padding: 20px 0;
                }

                .popup-inner .button-wrap {
                    padding-top: 20px;
                }

                .popup-inner .button {
                    min-width: 100px;
                    padding: 10px 20px;
                    margin: 0 10px;
                }
                </style>`
        let html = `
        <div class="text-wrap">
            Are you sure to delete this topic?
        </div>`;
        html += `
            <div class="button-wrap controls">
                <a href="javascript:closePopup()" class="button cancel white">Cancel</a>
                <a href="javascript:deleteTopic(${id})" class="button confirm black">Delete</a>
            </div>`;
        openPopup(style, html)
    }

    function deleteTopic(id) {
        $.ajax({
            type: 'DELETE',
            url: `/api/topic/delete/${id}`,
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
