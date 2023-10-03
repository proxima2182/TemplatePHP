<?php

$is_admin_page = isset($is_admin) && $is_admin;
?>
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
<?= \App\Helpers\HtmlHelper::setTranslations(['questioner', 'status', 'time', 'request', 'request_comment', 'select_date', 'select_time']); ?>
<script type="text/javascript">
    $(document).ready(function () {
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
                const is_time_select = <?=$board['id']?>;
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
                                            <span>${item['status']}</span>
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
                        for (let day in array) {
                            let items = array[day];
                            let contents = $('<div style="line-height: normal; font-weight: 200; padding: 5px 0 10px 20px; overflow: hidden; text-align: left"></div>');
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
                                // contents.append(`<p style="margin-top: 5px; padding: 3px; font-size : 14px; color: #eee; background: ${color}; border-radius: 15px;
                                //     text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">${item['question_comment']}</p>`);
                                contents.append(`<span style="width: 10px; height: 10px; margin: 5px 2px; display: inline-block; background: ${color}; border-radius: 50%;"></span>`);
                            }
                            $parent.getCell(day).find('.calendar-number-wrap').append(contents);
                        }
                    },
                    error: function (response, status, error) {
                        openPopupErrors('popup-error', response, status, error);
                    },
                });
            },
        })
    })
</script>
