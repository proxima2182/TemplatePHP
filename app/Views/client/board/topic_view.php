<div class="container-inner">
    <div class="container-wrap">
        <h3 class="page-title">
            <?= $board['alias'] ?>
        </h3>
        <div class="topic-wrap">
            <div class="row user link">
                <a href="javascript:openUserPopup(<?= $data['user_id'] ?>);" class="button out-line">
                    <img src="/asset/images/icon/user.png"/>
                    <span><?= $data['user_name'] ?></span>
                </a>
            </div>
            <div class="row row-title line-after black">
                <span class="column title"><?= $data['title'] ?></span>
                <span class="column created-at"><?= $data['created_at'] ?></span>
            </div>
            <div class="text-wrap line-after">
                <div class="content"><?= $data['content'] ?></div>
            </div>
            <div class="slider-box">
                <div class="slider-wrap">
                    <div class="slick">
                        <?php foreach ($data['files'] as $index => $file) { ?>
                            <div class="slick-item button"
                                 style="background: url('/file/<?= $file['id'] ?>') no-repeat center; background-size: cover; font-size: 0;"
                                 onclick="openImagePopup(<?= $file['id'] ?>)">
                                Slider #<?= $file['id'] ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php if ($is_login && ($is_admin || $user_id == $data['user_id'])) { ?>
                <div class="control-button-wrap">
                    <?php if ($user_id == $data['user_id']) { ?>
                        <a href="<?= $is_admin_page ? '/admin/topic/' . $data['id'] . '/edit' : '/topic/' . $data['id'] . '/edit' ?>"
                           class="button under-line edit">
                            <img src="/asset/images/icon/edit.png"/>
                            <span><?= lang('Service.edit') ?></span>
                        </a>
                    <?php } ?>
                    <?php if ($is_admin) { ?>
                        <a href="javascript:openTopicPopupDelete(<?= $data['id'] ?>)"
                           class="button under-line delete">
                            <img src="/asset/images/icon/delete.png"/>
                            <span><?= lang('Service.delete') ?></span>
                        </a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <div class="line black"></div>
    </div>
    <?php
    if ($board['is_reply'] == 1) {
        \App\Helpers\HtmlHelper::setTranslations(['send']);
        echo \App\Helpers\HtmlHelper::getReply($data['id'], $reply);
    }
    ?>
</div>
