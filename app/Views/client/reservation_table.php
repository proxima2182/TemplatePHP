<?php

$is_admin_page = isset($is_admin) && $is_admin;
?>
<div class="container-inner">
    <div class="container-wrap">
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
                            <span><?= lang('Service.reserve') ?></span>
                        </a>
                    </div>
                <?php }
                if (\App\Helpers\HtmlHelper::showDataEmpty($array)) { ?>
                    <div class="row-title">
                        <div class="row">
                            <span class="column questioner"><?= lang('Service.questioner') ?></span>
                            <span class="column status"><?= lang('Service.status') ?></span>
                            <span class="column expect-date"><?= lang('Service.expect_date') ?></span>
                            <span class="column expect-time"><?= lang('Service.expect_time') ?></span>
                            <span class="column confirm-date"><?= lang('Service.confirm_date') ?></span>
                            <span class="column confirm-time"><?= lang('Service.confirm_time') ?></span>
                        </div>
                    </div>
                    <ul>
                        <?php foreach ($array as $index => $item) { ?>
                            <li class="row">
                                <a href="javascript:openReservationBoardPopup(<?= $item['id'] ?>, <?= $board['is_time_select'] ?>);"
                                   class="button row-button">
                                    <span class="column questioner">
                                        <?= $item['questioner_name'] ??
                                            $item['temp_name'] ??
                                            '<img src="/asset/images/icon/none.png"/>' ?>
                                    </span>
                                    <span class="column status">
                                        <span class="badge <?= $item['status'] ?>"></span>
                                        <span>
                                            <?= lang('Service.' . $item['status']) ?>
                                        </span>
                                    </span>
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
<?= \App\Helpers\HtmlHelper::setTranslations([
    'request', 'request_comment', 'select_date', 'select_time',
    'requested', 'accepted', 'refused', 'canceled', 'accept', 'refuse',
    'request_accept', 'request_refuse',
    'reservation_refuse_reason', 'message_popup_refuse',
    'reservation_use_default', 'reservation_response']); ?>

