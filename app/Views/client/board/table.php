<?php
$is_admin_page = isset($is_admin) && $is_admin;
?>
<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            Notice
        </h3>
        <div class="list-wrap">
            <div class="list-box">
                <div class="row-title">
                    <div class="row">
                        <span class="column title">Title</span>
                        <span class="column  created-at">Created At</span>
                    </div>
                </div>
                <ul>
                    <?php for ($i = 0; $i < 6; $i++) { ?>
                        <li class=" row">
                            <a href="<?= $is_admin_page ? '/admin/topic/' . $i : '/topic/' . $i ?>"
                               class="button row-button">
                                <span class="column title">Lorem ipsum</span>
                                <span class="column  created-at">2023-06-29 00:00:00</span>
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
