<?php

$is_admin_page = isset($is_admin) && $is_admin;
?>
<div class="container-inner">
    <div class="container-wrap">
        <h3 class="page-title">
            <?= $board['alias'] ?>
        </h3>
        <div class="reservation-wrap">
            <?php if (strlen($data['questioner_name'] ?? '') > 0 && strlen($data['questioner_id'] ?? '') > 0) { ?>
                <div class="row-box line-after black">
                    <div class="row bold user link">
                        <a href="javascript:openUserPopup(<?= $data['questioner_id'] ?>);" class="button out-line">
                            <img src="/asset/images/icon/user.png"/>
                            <span><?= $data['questioner_name'] ?></span>
                        </a>
                    </div>
                </div>
            <?php } else if (strlen($data['temp_name'] ?? '') > 0) { ?>
                <div class="row-box line-after black">
                    <div class="row bold user link">
                        <img src="/asset/images/icon/user.png"/>
                        <span><?= $data['temp_name'] ?></span>
                    </div>
                </div>
            <?php }
            if (strlen($data['temp_phone_number'] ?? '') > 0) { ?>
                <div class="line-after">
                    <div class="row-text-wrap">
                        <img src="/asset/images/icon/message.png"/>
                        <span><?= $data['temp_phone_number'] ?></span>
                    </div>
                </div>
            <?php }

            $hasDate = strlen($data['expect_date'] ?? '') > 0;
            $hasTime = strlen($data['expect_time'] ?? '') > 0;

            if ($hasDate || $hasTime) { ?>

                <div class="line-after">
                    <div class="row-text-wrap">
                        <img src="/asset/images/icon/time.png"/>
                        <span><?= $hasDate ? $data['expect_date'] : '' ?><?= $hasDate && $hasTime ? ' ' : '' ?><?= $hasDate ? $data['expect_time'] : '' ?>
                    </div>
                </div>
            <?php }
            if (strlen($data['question_comment'] ?? '') > 0) { ?>
                <div class="text-wrap">
                    <div class="comment"><?= $data['question_comment'] ?></div>
                </div>
            <?php }
            if (strlen($data['status'] ?? '') > 0 && $data['status'] != 'requested') {
                if (strlen($data['respondent_id'] ?? '') > 0 && strlen($data['respondent_name'] ?? '') > 0) { ?>

                    <div class="row-box line-after black" style="margin-top: 20px;">
                        <div class="row bold user link">
                            <a href="javascript:openUserPopup(<?= $data['respondent_id'] ?>);" class="button out-line">
                                <img src="/asset/images/icon/user.png"/>
                                <span><?= $data['respondent_name'] ?></span>
                            </a>
                        </div>
                    </div>
                <?php }

                $hasDate = strlen($data['confirm_date'] ?? '') > 0;
                $hasTime = strlen($data['confirm_time'] ?? '') > 0;

                if($data['status'] == 'accepted') {
                if ($hasDate || $hasTime) { ?>

                    <div class="line-after">
                        <div class="row-text-wrap">
                            <img src="/asset/images/icon/time.png"/>
                            <span><?= $hasDate ? $data['confirm_date'] : '' ?><?= $hasDate && $hasTime ? ' ' : '' ?><?= $hasDate ? $data['confirm_time'] : '' ?>
                        </div>
                    </div>
                <?php }
                }?>
                <?php if (strlen($data['respond_comment'] ?? '') > 0) { ?>
                    <div class="text-wrap">
                        <div class="comment"><?= $data['respond_comment'] ?></div>
                    </div>
                <?php } ?>
            <?php } else {
                $parameterString = '';
                if ($hasDate) {
                    $parameterString .= ", '".$data['expect_date']."'";
                } else {
                    $parameterString .= ", null";
                }
                if ($hasTime) {
                    $parameterString .= ", '".$data['expect_time']."'";
                } else {
                    $parameterString .= ", null";
                }
                if($is_admin) { ?>
                    <div class="control-button-wrap absolute line-before">
                        <div class="control-button-box">
                            <a href="javascript:openReservationPopupRefuse(<?=$data['id']?>);"
                               class="button under-line refuse">
                                <img src="/asset/images/icon/cancel.png"/>
                                <span>Refuse</span>
                            </a>
                            <a href="javascript:openReservationPopupAccept(<?=$data['id']?>, <?= $board['is_time_select'] ?><?=$parameterString?>)"
                               class="button under-line accept">
                                <img src="/asset/images/icon/check.png"/>
                                <span>Accept</span>
                            </a>
                        </div>
                    </div>
                <?php }
            } ?>
        </div>
    </div>
</div>

