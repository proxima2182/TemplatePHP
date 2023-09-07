<?php

$is_admin_page = isset($is_admin) && $is_admin;
?>
<div class="container-inner">
    <div class="inner-box">
        <h3 class="page-title">
            <?= $board['alias'] ?>
        </h3>
        <div class="table-box">
            <div class="table-wrap">
                <?php if ($is_login) { ?>
                    <div class="control-button-wrap">
                        <a href="javascript:openReservationPopupRequest(<?= $board['id'] ?>, <?= $board['is_time_select'] ?>);"
                           class="button under-line create">
                            <img src="/asset/images/icon/plus.png"/>
                            <span>Reserve</span>
                        </a>
                    </div>
                <?php }
                if (\App\Helpers\HtmlHelper::checkArray($array)) { ?>
                <div class="row-title">
                    <div class="row">
                        <span class="column questioner">Questioner</span>
                        <span class="column status">Status</span>
                        <span class="column expect-date">Expect Date</span>
                        <span class="column expect-time">Expect Time</span>
                        <span class="column confirm-date">Confirm Date</span>
                        <span class="column confirm-time">Confirm Time</span>
                    </div>
                </div>
                <ul>
                    <?php foreach ($array as $index => $item) { ?>
                        <li class="row">
                            <a href="javascript:openReservationBoardPopup(<?= $item['id'] ?>, <?= $board['is_time_select'] ?>);"
                               class="button row-button">
                                <span class="column questioner"><?= $item['questioner_name'] ?></span>
                                <span class="column status"><?= $item['status'] ?></span>
                                <span class="column expect-date">
                                <?= $item['expect_date'] ??
                                    '<img src="/asset/images/icon/none.png"/>' ?>
                                </span>
                                <span class="column expect-time">
                                <?= $item['expect_time'] ??
                                    '<img src="/asset/images/icon/none.png"/>' ?>
                                </span>
                                <span class="column confirm-date">
                                <?= $item['confirm_date'] ??
                                    '<img src="/asset/images/icon/none.png"/>' ?>
                                </span>
                                <span class="column confirm-time">
                                <?= $item['confirm_time'] ??
                                    '<img src="/asset/images/icon/none.png"/>' ?>
                                </span>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
                <?php } ?>
            </div>
        </div>

        <?= \App\Helpers\HtmlHelper::getPagination($pagination, $pagination_link); ?>
    </div>
</div>

