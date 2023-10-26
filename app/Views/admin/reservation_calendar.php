<?php

$is_admin_page = isset($is_admin) && $is_admin;
?>
<style>
    .date > li:not(.disabled):not(.standard):after {
        content: "";
        height: 30%;
        display: block;
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(rgba(255, 255, 255, 0), #fff 80%);
        z-index: 2;
    }

    @media (max-width: 840px) {
        .calendar-wrap {
            width: 100%;
            max-width: 800px;
        }
    }
</style>
<div class="container-inner">
    <div class="container-wrap">
        <h3 class="page-title">
            <?= $board['alias'] ?>
        </h3>
        <?php if ($is_login) { ?>
            <div class="control-button-wrap">
                <a href="javascript:openReservationPopupRequest(<?= $board['id'] ?>);"
                   class="button under-line create">
                    <img src="/asset/images/icon/plus.png"/>
                    <span><?= lang('Service.reserve') ?></span>
                </a>
            </div>
        <?php } ?>

        <div class="calendar-wrap">
            <div class="calendar"></div>
        </div>
    </div>
</div>
<?= \App\Helpers\HtmlHelper::setTranslations([
    'questioner', 'status', 'time',
    'request', 'request_comment', 'select_date', 'select_time',
    'requested', 'accepted', 'refused', 'canceled', 'accept', 'refuse',
    'request_accept', 'request_refuse',
    'reservation_refuse_reason', 'message_popup_refuse',
    'reservation_use_default', 'reservation_response']); ?>
<script type="text/javascript">
    function initCalendar() {
        $(`.container-inner .calendar`).initCalendar({
            cellSize: 140,
            style: 'square',
            // standardDate : '2023-10-31',
            // endDate: '2023-10-31',
            limitPrevious: false,
            limitStandard: false,
            limitedDayOfWeek: ['mon'],
            textSize: 14,
            selectedStyle: false,
            colors: {
                today: '#',
                selected: '#',
                text_sunday: '#',
                text_saturday: '#',
            },
            onDateSelected: async function ($parent, date, year, month, day) {
                const is_time_select = <?=$board['is_time_select']?>;
                apiRequest({
                    type: 'POST',
                    url: `/api/reservation-board/reservation/get/<?=$board['code']?>`,
                    data: {
                        year: year,
                        month: month,
                        day: day,
                    },
                    dataType: 'json',
                    success: async function (response, status, request) {
                        if (!response.success) {
                            openPopupErrors('popup-error', response, status, request);
                            return;
                        }
                        let className = 'popup-reservation-table'
                        let css = await loadStyleFile('/asset/css/common/table.css', "." + className);
                        css += await loadStyleFile('/asset/css/common/popup/reservation_table.css', "." + className);
                        let data = response.data
                        let array = data.array;
                        if (!array || array.length == 0) {
                            return;
                        }
                        let html = `
                        <div class="table-wrap">
                        <div class="row-title">
                            <div class="row">
                                <span class="column questioner">${lang('questioner')}</span>
                                <span class="column status">${lang('status')}</span>
                                <span class="column time">${lang('time')}</span>
                            </div>
                        </div>
                        <ul>`
                        for (let day in array) {
                            let items = array[day];
                            for (let i in items) {
                                let item = items[i];
                                let time_field_name = 'expect_time';
                                if (item['status'] == 'accepted') {
                                    time_field_name = 'confirm_time';
                                }
                                html += `
                                <li class="row">
                                    <a href="javascript:openReservationBoardPopup(${item['id']}, ${is_time_select});"
                                       class="button row-button">
                                        <span class="column questioner">
                                            ${item['questioner_name'] ??
                                item['temp_name'] ??
                                '<img src="/asset/images/icon/none.png"/>'}
                                        </span>
                                        <span class="column status">
                                            <span class="badge ${item['status']}"></span>
                                            <span>${lang(item['status'])}</span>
                                        </span>
                                        <span class="column time">
                                            ${item[time_field_name] ??
                                '<img src="/asset/images/icon/none.png"/>'}
                                        </span>
                                    </a>
                                </li>`
                            }
                        }
                        html += `</ul></div>`
                        openPopup({
                            className: className,
                            style: `<style>${css}</style>`,
                            html: html
                        })
                    },
                    error: function (response, status, error) {
                        openPopupErrors('popup-error', response, status, error);
                    },
                });
            },
            onRefreshed: function ($parent, year, month) {
                apiRequest({
                    type: 'POST',
                    url: `/api/reservation-board/reservation/get/<?=$board['code']?>`,
                    data: {
                        year: year,
                        month: month
                    },
                    dataType: 'json',
                    success: function (response, status, request) {
                        if (!response.success) {
                            openPopupErrors('popup-error', response, status, request);
                            return;
                        }
                        let data = response.data
                        let array = data.array;
                        let $cellFirstDay = $parent.getCell(1);
                        let $spanNumber = $cellFirstDay.find('.calendar-number');
                        for (let day in array) {
                            let items = array[day];
                            let $cellDay = $parent.getCell(day);
                            let $spanNumber = $cellDay.find('.calendar-number');
                            let top = 5;
                            let left = 20;
                            let badgeMargin = 5;
                            if ($spanNumber) {
                                badgeMargin = ($spanNumber.height() - 10) / 2;
                                top = $spanNumber.position().top;
                                left = $spanNumber.width() + $spanNumber.position().left * 2;
                            }
                            let contents = $(`<div style="line-height: normal; font-weight: 200; padding: ${top}px 0 0 ${left}px; overflow: hidden; text-align: left"></div>`);
                            for (let i in items) {
                                let item = items[i];
                                let color = "#000";
                                switch (item['status']) {
                                    case 'requested' :
                                        color = '#ddbd2b';
                                        break;
                                    case 'accepted' :
                                        color = '#2ea400';
                                        break;
                                    case 'refused' :
                                        color = '#ba2b14';
                                        break;
                                }
                                contents.append(`<span style="width: 10px; height: 10px; margin: ${badgeMargin}px 2px; display: inline-block; background: ${color}; border-radius: 50%;"></span>`);
                            }
                            $cellDay.find('.calendar-inner-cell').append(contents);
                        }
                    },
                    error: function (response, status, error) {
                        openPopupErrors('popup-error', response, status, error);
                    },
                });
            },
        })
    }

    $(document).ready(function () {
        if (isMobile()) {
            resizeWindow();
        } else {
            initCalendar();
        }
    })

    let reservationCalendarTimeoutId = -1;
    addEventListener("resize", (event) => {
        if (reservationCalendarTimeoutId != -1)
            clearTimeout(reservationCalendarTimeoutId);
        if(isMobile()) {
            reservationCalendarTimeoutId = setTimeout(function () {
                resizeWindow();
                clearTimeout(reservationCalendarTimeoutId);
            }, 500);
        } else {
            resizeWindow();
        }
    });

    /**
     * window resize
     */
    function resizeWindow() {
        if (isMobile() && !$('body').hasClass('mobile')) {
            // mobile 로 전환
            // 첫 load 때 모바일인 경우 호출됨
            $('body').addClass('mobile');
            initCalendar();
        }
        if (!isMobile() && $('body').hasClass('mobile')) {
            // pc 로 전환
            $('body').removeClass('mobile');
            initCalendar();
        }
    }

</script>
