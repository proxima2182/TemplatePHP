<?php
$is_admin_page = isset($is_admin) && $is_admin;
?>
<div class="container-inner">
    <div class="inner-box">
        <h3 class="page-title">
            Reservation
        </h3>
        <div class="table-box">
            <div class="table-wrap">
                <?php if ($is_login) { ?>
                    <div class="control-button-wrap">
                        <a href="javascript:openReservationPopupRequest(this);"
                            class="button under-line create">
                            <img src="/asset/images/icon/plus.png"/>
                            <span>Reserve</span>
                        </a>
                    </div>
                <?php } ?>
                <div class="row-title">
                    <div class="row">
                        <span class="column questioner">Questioner</span>
                        <span class="column status">Status</span>
                        <span class="column expect-date">Expect Date</span>
                        <span class="column expect-time">Expect Time</span>
                    </div>
                </div>
                <ul>
                    <?php foreach ($array as $index => $item) { ?>
                        <li class="row">
                            <a href="javascript:openReservationPopup(<?= $item['id'] ?>);"
                               class="button row-button">
                                <span class="column questioner"><?= $item['questioner_name'] ?></span>
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

<script type="text/javascript">
    /**
     * 본인의 작성물에 대한 수정은 본인만 가능하여야 하므로
     * admin 이 일방적으로 수정은 할 수 없도록 하기 때문에 삭제 기능만 가능
     * @param id
     */
    function openReservationPopup(id) {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: `/api/reservation/get/${id}`,
            success: function (response, status, request) {
                if (!response.success)
                    return;
                let data = response.data;
                let className = 'popup-reservation';
                let style = `
                <style>
                .${className} .rows-box {
                    margin: 10px;
                }

                .${className} .row {
                    line-height: 35px;
                    font-size: 18px;
                }

                .${className} .row.title {
                    font-weight: 400;
                }

                .${className} .time-wrap {
                    margin: 10px;
                    text-align: left;
                    line-height: 20px;
                }

                .${className} .time-wrap *{
                    vertical-align: middle;
                }

                .${className} .text-wrap .comment {
                    margin: 10px 20px;
                    min-height: 100px;
                    font-size: 18px;
                    text-align: left;
                }
                </style>`
                let html = `
                <div class="rows line-after black">
                    <div class= "rows-box">
                        <div class="row title user">${data['questioner_name']}</div>
                        <div class="row phone_number">${data['questioner_phone_number']}</div>
                    </div>
                </div>`
                let hasDate = data['expect_date'] && data['expect_date'].length > 0;
                let hasTime = data['expect_time'] && data['expect_time'].length > 0;
                if (hasDate || hasTime) {
                    html += `
                    <div class="time-wrap">
                        <img src="/asset/images/icon/time.png"/>
                        <span>${hasDate ? data['expect_date'] : ''}${hasDate && hasTime ? ' ' : ''}${hasTime ? data['expect_time'] : ''}</span>
                    </div>`
                }
                if (data['question_comment'] && data['question_comment'].length > 0) {
                    html += `
                    <div class="text-wrap line-before">
                        <div class="comment">${data['question_comment']}</div>
                    </div>`
                }

                if (data['status'] && data['status'] != 'requested') {
                    html += `
                    <div class="row row-title line-after black">
                        <span class="column user">${data['respondent_name']}</span>
                    </div>`
                    if (data['status'] == 'confirmed') {
                        let hasDate = data['confirm_date'] && data['confirm_date'].length > 0;
                        let hasTime = data['confirm_time'] && data['confirm_time'].length > 0;
                        if (hasDate || hasTime) {
                            html += `
                            <div class="time-wrap">
                                <img src="/asset/images/icon/time.png"/>
                                <span>${hasDate ? data['confirm_date'] : ''}${hasDate && hasTime ? ' ' : ''}${hasTime ? data['confirm_time'] : ''}</span>
                            </div>`
                        }
                    }
                    if (data['respond_comment'] && data['respond_comment'].length > 0) {
                        html += `
                    <div class="text-wrap line-before">
                        <div class="comment">${data['respond_comment']}</div>
                    </div>`
                    }
                }

                html += `
                <div class="control-button-wrap absolute line-before">
                    <div class="control-box">
                        <a href="javascript:openReservationPopupReject(this, ${data['id']});"
                            class="button under-line reject">
                            <img src="/asset/images/icon/cancel.png"/>
                            <span>Reject</span>
                        </a>
                        <a href="javascript:openReservationPopupRequest(this, ${data['id']});"
                            class="button under-line accept">
                            <img src="/asset/images/icon/check.png"/>
                            <span>Accept</span>
                        </a>
                    </div>
                </div>`;
                openPopup({
                    className: className,
                    style: style,
                    html: html,
                })
            },
            error: function (response, status, error) {
            },
        });
    }

    /**
     * 반려 시 다시한번 묻는 popup 을 여는 기능
     * @requires openPopup
     * @requires closePopup
     * @param id
     */
    function openReservationPopupReject($parent, id) {
        let className = 'popup-reservation-reject';
        let style = `
        <style>
        body .${className} .popup {
            width: 500px;
        }

        .${className} .popup-inner .text-wrap {
            padding: 20px 0;
        }

        .${className} .popup-inner .button-wrap {
            margin-top: 20px;
        }

        .${className} .popup-inner .button-wrap .button {
            min-width: 100px;
            padding: 10px 20px;
            margin: 0 10px;
        }
        </style>`
        let html = `
        <div class="text-wrap">
            Are you sure to reject reservation?
        </div>
        <div class="button-wrap controls">
            <a href="javascript:closePopup('${className}')" class="button cancel white">Cancel</a>
            <a href="javascript:confirmReservationPopupReject(${id})" class="button confirm black">Reject</a>
        </div>`;
        openPopup({
            className: className,
            style: style,
            html: html,
        })
    }

    /**
     * 예약 반려 기능
     * @param {string}id
     */
    function confirmReservationPopupReject(id) {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: `/api/reservation/reject/${id}`,
            success: function (response, status, request) {
                location.reload();
            },
            error: function (response, status, error) {
            },
        });
    }

    function openReservationPopupRequest() {
        let className = 'popup-reservation-request';
        let style = `
        <style>
        .${className} input, .${className} textarea {
            border: none;
            margin: 0;
            font-weight: 200;
        }

        .${className} .comment {
            width: 100%;
            min-height: 100px;
            padding: 10px 20px;
            font-size: 18px;
            font-weight: 200;
        }

        .${className} .title {
            line-height: 30px;
            font-size: 18px;
            font-weight: 400;
        }

        .${className} .time-selector-wrap {
            display: inline-block;
            width: 450px;
            margin-bottom: 40px;
        }

        </style>`
        let html = `
        <div class= "calendar-wrap">
            <p class="title">Expect Date</p>
            <div class= "calendar"></div>
        </div>
        <div class= "time-selector-wrap">
            <p class="title">Expect Time</p>
            <div class= "time-selector"></div>
        </div>
        <div class="input-wrap inline line-before">
            <textarea placeholder="Comment" name="comment" class="comment"></textarea>
        </div>
        <div class="control-button-wrap absolute line-before">
            <div class="control-box">
                <a href="javascript:closePopup('${className}');"
                    class="button under-line cancel">
                    <img src="/asset/images/icon/cancel.png"/>
                    <span>Cancel</span>
                </a>
                <a href="#" class="button confirm">
                    <img src="/asset/images/icon/check.png"/>
                    <span>Confirm</span>
                </a>
            </div>
        </div>`;
        openPopup({
            className: className,
            style: style,
            html: html,
            callback: function () {
                $(`.${className} .calendar`).initCalendar({
                    cell_size: 60,
                })
                $(`.${className} .time-selector`).initTimeSelector()
            },
        })
    }
</script>
