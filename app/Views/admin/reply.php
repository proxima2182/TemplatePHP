<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            Reply
        </h3>
        <div class="list-wrap">
            <div class="list-box">
                <div class="row-title">
                    <div class="row">
                        <span class="column user">User</span>
                        <span class="column content">Content</span>
                        <span class="column board">Board</span>
                        <span class="column created-at">Created At</span>
                    </div>
                </div>
                <ul>
                    <?php foreach ($array as $index => $item) { ?>
                        <li class="row">
                            <a href="javascript:openPopupDetail('<?= $item['id'] ?>')" class="button row-button">
                                <span class="column user"><?= $item['user_name'] ?></span>
                                <span class="column content"><?= $item['content'] ?></span>
                                <span class="column board"><?= $item['board_code'] ?></span>
                                <span class="column created-at"><?= $item['created_at'] ?></span>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <?= \App\Helpers\HtmlHelper::getPagination($pagination, $pagination_link); ?>
    </div>
</div>
