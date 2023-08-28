<div class="container-inner">
    <div class="inner-box">
        <h3 class="page-title">
            Preview
        </h3>
        <div class="grid-wrap">
            <?php if ($is_login) { ?>
                <div class="control-button-wrap">
                    <a href="<?= $is_admin_page ? '/admin/board/' . $board_code . '/topic/create' : '/board/' . $board_code . '/topic/create' ?>"
                       class="button create">
                        <img src="/asset/images/icon/plus.png"/>
                        <span>Create</span>
                    </a>
                </div>
            <?php }
            $index = 0;
            foreach ($array as $index => $item) { ?>
                <div class="grid-item button" onclick="openTopicPopup(<?= $item['id'] ?>)">
                    <?php if (isset($item['image_id'])) { ?>
                        <div class="image-wrap"
                             style="background: url('/image-file/<?= $item['image_id'] ?>') no-repeat center; background-size: cover; font-size: 0;">
                        </div>
                    <?php } ?>
                    <div class="text-wrap">
                        <span class="title"><?= $item['title'] ?></span>
                        <span class="content"><?= $item['content'] ?></span>
                    </div>
                </div>
            <?php } ?>
        </div>

        <?= \App\Helpers\HtmlHelper::getPagination($pagination, $pagination_link); ?>
    </div>
</div>
