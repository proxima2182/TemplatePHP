<?php

$is_admin_page = isset($is_admin) && $is_admin;
?>
<div class="container-inner">
    <div class="inner-box">
        <h3 class="page-title">
            <?= $board['alias'] ?>
        </h3>
        <?php if ($is_login) { ?>
            <div class="control-button-wrap">
                <a href="javascript:openReservationPopupRequest(<?= $board['id'] ?>);"
                   class="button under-line create">
                    <img src="/asset/images/icon/plus.png"/>
                    <span>Reserve</span>
                </a>
            </div>
        <?php } ?>

        <div class="calendar-wrap">
            <div class="calendar"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $(`.container-inner .calendar`).initCalendar({
            cellSize: 140,
            style: 'circle',
            standardDate : '2023-10-10',
            endDate: '2023-10-09',
            limitPrevious: false,
            limitStandard: true,
            limitedDayOfWeek : ['mon'],
            textSize: 14,
            selectedStyle : true,
            colors : {
                today : '#',
                selected : '#',
                text_sunday : '#',
                text_saturday : '#',
            },
            onDataSelected: function($parent, date) {
            },
            onRefreshed : function($parent, year, month) {
            },
        })
    })
</script>
