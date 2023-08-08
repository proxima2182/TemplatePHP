<?php
$is_admin_page = isset($is_admin) && $is_admin;
?>
<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            <?=$title?>
        </h3>
        <div class="control-wrap">
            <a href="<?= $is_admin_page ? '/admin/board/'.$code.'/topic/create' : '/board/'.$code.'/topic/create' ?>" class="button create">
                <img src="/asset/images/icon/plus.png"/>
                <span>Create</span>
            </a>
        </div>
        <div class="list-wrap">
            <div class="list-box">
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
