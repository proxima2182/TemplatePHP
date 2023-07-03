<?php
$reply = [
    'page' => 1,
    'per-page' => 5,
    'total' => 13,
    'total-page' => 3,
    'array' => [
        [
            'user_name' => 'Lorem Ipsum',
            'depth' => 0,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'created_at' => '2023-07-03 22:35:00',
            'nested_reply' => [
                'page' => 1,
                'per_page' => 10,
                'total' => 2,
                'total_page' => 3,
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
            'user_name' => 'Lorem Ipsum',
            'depth' => 0,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'created_at' => '2023-07-03 22:35:00',
            'nested_reply' => [
                'page' => 1,
                'per_page' => 10,
                'total' => 0,
                'total_page' => 0,
                'array' => [],
            ],
        ],
        [
            'user_name' => 'Lorem Ipsum',
            'depth' => 0,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'created_at' => '2023-07-03 22:35:00',
            'nested_reply' => [
                'page' => 1,
                'per_page' => 10,
                'total' => 0,
                'total_page' => 0,
                'array' => [],
            ],
        ],
        [
            'user_name' => 'Lorem Ipsum',
            'depth' => 0,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'created_at' => '2023-07-03 22:35:00',
            'nested_reply' => [
                'page' => 1,
                'per_page' => 10,
                'total' => 0,
                'total_page' => 0,
                'array' => [],
            ],
        ],
        [
            'user_name' => 'Lorem Ipsum',
            'depth' => 0,
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'created_at' => '2023-07-03 22:35:00',
            'nested_reply' => [
                'page' => 1,
                'per_page' => 10,
                'total' => 0,
                'total_page' => 0,
                'array' => [],
            ],
        ],
    ],
];

$images = [
    '/asset/images/object.png',
]
?>

<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            Notice
        </h3>
        <div class="detail-wrap">
            <div class="column-wrap line-after">
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
</script>
