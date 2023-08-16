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
            <?= $data['board_alias'] ?>
        </h3>
        <div class="topic-wrap">
            <div class="row row-title line-after black">
                <span class="column title"><?= $data['title'] ?></span>
                <span class="column  created-at"><?= $data['created_at'] ?></span>
            </div>
            <div class="text-wrap line-after">
                <div class="content"><?= $data['content'] ?></div>
            </div>
            <div class="slider-wrap">
                <div class="slider-box">
                    <div class="slick">
                        <?php foreach ($data['images'] as $index => $image) { ?>
                            <div class="slick-element"
                                 style="background: url('/image-file/<?= $image['id'] ?>') no-repeat center; background-size: cover; font-size: 0;">
                                Slider #<?= $image['id'] ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php if ($is_login && ($is_admin || $user_id == $data['user_id'])) { ?>
                <div class="control-wrap">
                    <?php if ($user_id == $data['user_id']) { ?>
                        <a href="<?= $is_admin_page ? '/admin/topic/' . $data['id'] . '/edit' : '/topic/' . $data['id'] . '/edit' ?>"
                           class="button edit">
                            <img src="/asset/images/icon/edit.png"/>
                            <span>Edit</span>
                        </a>
                    <?php } ?>
                    <?php if ($is_admin) { ?>
                        <a href="javascript:openTopicPopupDelete(<?= $data['id'] ?>)" class="button delete">
                            <img src="/asset/images/icon/delete.png"/>
                            <span>Delete</span>
                        </a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?= \App\Helpers\HtmlHelper::getReply($data['id'], $reply); ?>
<script type="text/javascript">
    $('.slider-wrap .slick').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: false,
        infinite: false,
    })

    function openTopicPopupDelete(id) {
        let className = 'popup-delete';
        let style = `
                <style>
                body .${className} .popup {
                    width: 500px;
                }

                .${className} .popup-inner .text-wrap {
                    padding: 20px 0;
                }

                .${className} .popup-inner .button-wrap {
                    padding-top: 20px;
                }

                .${className} .popup-inner .button-wrap .button {
                    min-width: 100px;
                    padding: 10px 20px;
                    margin: 0 10px;
                }
                </style>`
        let html = `
        <div class="text-wrap">
            Are you sure to delete?
        </div>`;
        html += `
            <div class="button-wrap controls">
                <a href="javascript:closePopup('${className}')" class="button cancel white">Cancel</a>
                <a href="javascript:confirmDeleteTopic(${id})" class="button confirm black">Delete</a>
            </div>`;
        openPopup({
            className: className,
            style: style,
            html: html,
        })
    }

    function confirmDeleteTopic(id) {
        $.ajax({
            type: 'DELETE',
            dataType: 'json',
            url: `/api/topic/delete/${id}`,
            success: function (response, status, request) {
                //TODO refresh
            },
            error: function (response, status, error) {
                console.log(error)
            },
        });
    }
</script>
