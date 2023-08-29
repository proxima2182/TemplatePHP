<div class="container-inner">
    <div class="inner-box">
        <h3 class="page-title">
            <?= $board_alias ?>
        </h3>
        <div class="table-box">
            <div class="table-wrap">
                <?php if ($is_login) { ?>
                    <div class="control-button-wrap">
                        <a href="<?= $is_admin_page ? '/admin/board/' . $board_code . '/topic/create' : '/board/' . $board_code . '/topic/create' ?>"
                           class="button under-line create">
                            <img src="/asset/images/icon/plus.png"/>
                            <span>Create</span>
                        </a>
                    </div>
                <?php }
                if (!isset($array) || sizeof($array) == 0) { ?>
                    <div class="no-data-box">
                        <div class="no-data-wrap">
                            <img src="/asset/images/icon/empty_folder.png">
                            <span>No data available.</span>
                        </div>
                    </div>
                <?php } else { ?>
                <div class="row-title">
                    <div class="row">
                        <span class="column title">Title</span>
                        <span class="column  created-at">Created At</span>
                    </div>
                </div>
                <ul>
                    <?php foreach ($array as $index => $item) { ?>
                        <li class=" row">
                            <a href="<?= $is_admin_page ? '/admin/topic/' . $item['id'] : '/topic/' . $item['id'] ?>"
                               class="button row-button">
                                <span class="column title"><?= $item['title'] ?></span>
                                <span class="column created-at"><?= $item['created_at'] ?></span>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <?php } ?>
        </div>

        <?= \App\Helpers\HtmlHelper::getPagination($pagination, $pagination_link); ?>
    </div>
</div>

<?php if (!$is_admin_page) { ?>
    <style>
        .list-box {
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);
        }
    </style>
<?php } ?>
