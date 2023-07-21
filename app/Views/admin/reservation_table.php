<?php
$is_admin_page = isset($is_admin) && $is_admin;
?>
<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            Reservation
        </h3>
        <div class="list-wrap">
            <div class="list-box">
                <div class="row-title">
                    <div class="row">
                        <span class="column user">User</span>
                        <span class="column status">Status</span>
                        <span class="column expect-date">Expect Date</span>
                        <span class="column expect-time">Expect Time</span>
                    </div>
                </div>
                <ul>
                    <?php foreach ($array as $index => $item) { ?>
                        <li class="row">
                            <a href="<?= $is_admin_page ? '/admin/reservation/' . $index : '/reservation/' . $index ?>" class="button row-button">
                                <span class="column user"><?= $item['user_name'] ?></span>
                                <span class="column status"><?= $item['status'] ?></span>
                                <span class="column expect-date"><?= $item['expect_date'] ?></span>
                                <span class="column expect-time"><?= $item['expect_time'] ?></span>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <?= \App\Helpers\HtmlHelper::getPagination($pagination, $pagination_link); ?>
    </div>
</div>
