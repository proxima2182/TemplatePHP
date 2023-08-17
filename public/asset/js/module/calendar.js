/**
 * @file 캘린더 모듈 자동생성 스크립트
 * @todo setCalendar 파악, 스타일 설정 option 추가
 */

let today = new Date();

/**
 * jquery object 에서 해당 함수 호출 시 내부에 calendar 를 추가해주는 기능
 * @param option
 * @todo refactoring & release as an open source
 */
jQuery.prototype.initCalendar = function (option) {
    let $parent = this;
    if ($parent.length == 0) return;
    let cell_size = option && option.cell_size ? option.cell_size : 60;
    let time = option && option.time ? option.time : new Date();
    $parent.css({
        'width': `${cell_size * 7}px`,
        'position': 'relative',
        'display': 'inline-block',
        'vertical-align': 'middle',
    })
    const rand = Math.random().toString(36).substr(2, 11);
    let className = `calendar-${rand}`;
    $parent.addClass(className);
    if (option) {
        try {
            $parent.attr({
                option: JSON.stringify(option),
            })
        } catch (e) {
            // do nothing
        }
    }
    $parent.append(`
    <style>
    .calendar.${className} {
        width: 420px;
        display: inline-block;
        vertical-align: middle;
        position: relative;
    }
    
    .calendar.${className} li {
        display: inline-block;
        vertical-align: middle;
    }
    
    .calendar.${className} .date li {
        width: 60px;
        height: 60px;
        font-weight: 400;
        position: relative;
    }
    
    .calendar.${className} .date li span {
        width: 40px;
        line-height: 40px;
        font-size: 14px;
        margin: -20px -20px 0 0;
        position: absolute;
        top: 50%;
        right: 50%;
        z-index: 2;
    }
    
    .calendar.${className} .date li.today span {
        border: 1px solid #222;
        font-weight: bold;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        border-radius: 50%;
    }
    
    .calendar.${className} .date li.selected span {
        background: #222;
        color: #fff;
        font-weight: bold;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        border-radius: 50%;
    }
    
    .calendar.${className} .date li.red span {
        color: #a40000;
    }
    
    .calendar.${className} .date li.blue span {
        color: #100964;
    }
    
    .calendar.${className} .date li.selected.blue span {
        color: #6799FF;
    }
    
    .calendar.${className} .week {
        font-size: 0;
    }
    
    .calendar.${className} .week li {
        width: 60px;
        height: 30px;
        line-height: 30px;
        font-size: 16px;
        font-weight: 600;
    }
    
    .calendar.${className} .nav {
        height: 80px;
        line-height: 80px;
        position: relative;
        font-size: 0;
    }
    
    .calendar.${className} .nav .button {
        width: 30px;
        height: 30px;
        line-height: 30px;
        cursor: pointer;
        position: absolute;
        margin-top: -12px;
        top: 50%;
        text-align: center;
    }
    
    .calendar.${className} .nav .button * {
        vertical-align: middle;
    }

    .calendar.${className} .nav .button.prev {
        left: 15px;
    }
    
    .calendar.${className} .nav .button.next {
        right: 15px;
    }
    
    .calendar.${className} .nav .text {
        display: inline-block;
        line-height: normal;
        vertical-align: middle;
        font-size: 20px;
    }
    
    .calendar.${className} .nav .year {
        font-size: 14px;
    }
    
    .calendar.${className} .nav .month {
        font-size: 25px;
    }
    
    .calendar.${className} .week-disabled {
        position: absolute;
        top: 100px;
        width: 420px;
        text-align: left;
    }
    
    .calendar.${className} .week-disabled li {
        width: 100%;
        height: 40px;
        margin-top: 20px;
        border-radius: 20px;
        background: #eee;
    }
    </style>
    <div class="nav">
        <span class="button prev">
            <svg width="10px" height="18px"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 8.74 16.06">
                <defs>
                    <style>.cls-1 {
                            fill: none;
                            stroke: #000;
                            stroke-miterlimit: 10;
                        }</style>
                </defs>
                <polyline class="cls-1" points="8.38 0.35 0.71 8.03 8.38 15.71"/>
            </svg>
        </span>
        <span class="text">
                    <p class="year"></p>
                    <p class="month"></p>
                </span>
        <span class="button next">
            <svg width="10px" height="18px"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 8.74 16.06">
                <defs>
                    <style>.cls-1 {
                            fill: none;
                            stroke: #000;
                            stroke-miterlimit: 10;
                        }</style>
                </defs>
                <polyline class="cls-1" points="0.35 15.71 8.03 8.03 0.35 0.35"/>
            </svg>
        </span>
    </div>

    <ul class="week" style="font-size: 0;">
        <li>일</li>
        <li>월</li>
        <li>화</li>
        <li>수</li>
        <li>목</li>
        <li>금</li>
        <li>토</li>
    </ul>

    <ul class="date">
    </ul>
    <ul class="week-disabled">
    </ul>
    <input type="hidden" name="date"/>`)
    $parent.attr({
        now: time.getTime(),
    })

    let $prev = $parent.find('.button.prev');
    let $next = $parent.find('.button.next');
    $prev.click(function () {
        let rawTime = $parent.attr('now') && $parent.attr('now').length > 0 ? Number($parent.attr('now')) : undefined;
        let date = new Date(rawTime);
        date.setMonth(date.getMonth() - 1);
        setCalendar($parent, date);
        $parent.attr({
            now: date.getTime(),
        })
    })
    $next.click(function () {
        let rawTime = $parent.attr('now') && $parent.attr('now').length > 0 ? Number($parent.attr('now')) : undefined;
        let date = new Date(rawTime);
        date.setMonth(date.getMonth() + 1);
        setCalendar($parent, date);
        $parent.attr({
            now: date.getTime(),
        })
    })
    setCalendar($parent, time);
}

/**
 * setCalendar 를 다시 호출해 주는 기능
 * (setCalendar 에서 조건에 맞게 새로 그려준다)
 */
jQuery.prototype.refreshCalendar = function () {
    const $parent = this;
    let rawTime = $parent.attr('now') && $parent.attr('now').length > 0 ? Number($parent.attr('now')) : undefined;
    let date = new Date(rawTime);
    setCalendar($parent, date);
}


jQuery.prototype.getSelectedDate = function () {
    const $parent = this;
    let $input = $parent.find('input[type=hidden]');
    return $input.val();
}

/**
 * 전체 기본 스타일을 없애는 기능
 * @param $view
 */
function setStyleUnselectable($view) {
    $view.css({
        '-webkit-touch-callout': 'none',
        '-webkit-user-select': 'none',
        '-khtml-user-select': 'none',
        '-moz-user-select': 'none',
        '-ms-user-select': 'none',
        'user-select': 'none',
    })
    $view.attr('unselectable', 'on')
    $view.attr('onselectstart', 'return false;')
    $view.attr('onmousedown', 'return false;')
}

/**
 * 클릭 가능하도록 하는 스타일 기능
 * @param $view
 */
function setStyleSelectable($view) {
    setStyleUnselectable($view);
    $view.css({
        'cursor': 'pointer',
    })
}

/**
 * cell 선택 시 호출되는 공통 기능
 * @param year
 * @param month
 * @param $parent
 * @param $cell
 */
function selectCalendar(year, month, $parent, $cell) {
    let previously_selected = $parent.find('.selected');
    if (previously_selected != undefined) {
        previously_selected.removeClass('selected')
    }
    $cell.addClass('selected');

    let $input = $parent.find('input[type=hidden]');
    if ($input != undefined) {
        $input.remove();
    }
    let day = $cell.find('span')[0].innerHTML;
    let value = pad(year, 4) + '-' + pad(month, 2) + '-' + pad(day, 2);
    $parent.append('<input type="hidden" name="date" value="' + value + '"/>')
}

function getRow(first, now) {
    let rows = parseInt((first.getDay() + now.getDate()) / 7);
    if ((first.getDay() + now.getDate()) % 7 != 0) rows += 1
    return rows;
}

/**
 * 주어진 조건에 맞게 캘린더 생성해주는 기능
 * @param $parent
 * @param date
 */
function setCalendar($parent, date) {
    let cell_width = parseInt($parent.innerWidth() / 7 * 100) / 100;

    let optionString = $parent.attr('option');
    let option;
    if (optionString) {
        try {
            option = JSON.parse(optionString);
        } catch (e) {
            // do nothing
        }
    }

    let $year = $parent.find('.nav .year');
    let $month = $parent.find('.nav .month');
    let $weeks = $parent.find('.week li');
    setStyleUnselectable($weeks);
    setStyleUnselectable($year);
    setStyleUnselectable($month);
    let $cells = $parent.find('ul.date');
    let $week_disabled = $parent.find('ul.week-disabled');
    $cells.empty();
    $week_disabled.empty();

    let year = date.getFullYear();
    let month = date.getMonth();
    let hasToday = (year == today.getFullYear() && month == today.getMonth());
    $year[0].innerHTML = year;
    $month[0].innerHTML = (month + 1) + '월';

    let $prev = $parent.find('.button.prev');
    let $next = $parent.find('.button.next');
    if (hasToday) {
        $prev.css({
            'display': 'none',
        })
    } else {
        $prev.css({
            'display': 'inline-block',
        })
    }

    let firstDay = (new Date(year, month));
    let lastDay = (new Date(year, month + 1));
    lastDay.setDate(lastDay.getDate() - 1);
    let numberOfDays = 32 - new Date(year, month, 32).getDate();

    let rows = parseInt((firstDay.getDay() + numberOfDays) / 7);
    if ((firstDay.getDay() + numberOfDays) % 7 != 0) rows += 1;
    let yesterday = new Date(today);
    yesterday.setDate(today.getDate() - 1);

    let limitedDay = new Date(today);
    limitedDay.setMonth(today.getMonth() + 1);
    let hasLimitedDay = (year == limitedDay.getFullYear() && month == limitedDay.getMonth());
    let buffer = new Date(date);
    buffer.setMonth(buffer.getMonth() + 1);
    if (hasLimitedDay || limitedDay.getDate() == 1 && limitedDay.getMonth() == buffer.getMonth() && limitedDay.getFullYear() == buffer.getFullYear()) {
        $next.css({
            'display': 'none',
        })
    } else {
        $next.css({
            'display': 'inline-block',
        })
    }

    let disabled_row = rows;
    if (hasToday && yesterday.getMonth() == today.getMonth()) {
        let yesterday_row = getRow(firstDay, yesterday);
        disabled_row = yesterday_row
    } else if (yesterday.getFullYear() == year && yesterday.getMonth() < month ||
        yesterday.getFullYear() < year) {
        disabled_row = 0;
    }

    let days = 0;
    for (let i = 0; i < firstDay.getDay(); i++, days++) {
        let $cell = $('<li></li>');
        setStyleUnselectable($cell)
        $cells.append($cell);
    }
    for (let i = 0; i < numberOfDays; i++, days++) {
        let $cell = $('<li><span>' + (i + 1) + '</span></li>');
        if (hasToday && i == today.getDate() - 1) {
            $cell.addClass('today');
        }

        function setCell() {
            if (hasToday && i <= today.getDate() - 1
                || hasLimitedDay && i >= limitedDay.getDate() - 1) {
                setStyleUnselectable($cell);
            } else {
                setStyleSelectable($cell);
                $cell.click(function () {
                    selectCalendar(year, month, $parent, $(this));
                })
            }
        }

        if (days % 7 == 0) {
            // 일요일
            setStyleUnselectable($cell);
            $cell.addClass('red');
        } else if (days % 7 == 6) {
            // 토요일
            $cell.addClass('blue');
            setCell();
        } else {
            setCell();
        }
        $cells.append($cell);
    }
    for (; days < 6 * 7; days++) {
        let $cell = $('<li></li>');
        setStyleUnselectable($cell)
        $cells.append($cell);
    }
    for (let i = 0; i < disabled_row; ++i) {
        let $cell_row = $('<li></li>');

        let count = 7;
        let left = 0;

        if (i == 0) {
            count -= firstDay.getDay();
            left += firstDay.getDay();
        }
        if (hasToday && i == disabled_row - 1) {
            count -= (7 - yesterday.getDay() - 1);
        }

        if (!hasToday && rows == disabled_row && i == disabled_row - 1) {
            count -= (7 - lastDay.getDay() - 1)
        }

        let width = cell_width * count;
        let margin_left = cell_width * left;
        $cell_row.css({
            'width': width + 'px',
            'margin-left': margin_left + 'px',
        })
        $week_disabled.append($cell_row);
    }
    if (disabled_row == 0 && hasLimitedDay) {
        let limited_row = getRow(firstDay, limitedDay);
        let last_row = getRow(firstDay, lastDay);
        let loop_count = last_row - limited_row + 1;
        for (let i = 0; i < loop_count; ++i) {
            let $cell_row = $('<li></li>');

            let count = 7;
            let left = 0;
            if (i == 0) {
                count -= limitedDay.getDay();
                left += limitedDay.getDay()
                let margin_top = cell_width / 3 + cell_width * (limited_row - 1);
                $cell_row.css({
                    'margin-top': margin_top + 'px',
                })
            }

            if (i == loop_count - 1) {
                count -= (7 - lastDay.getDay() - 1);
            }
            let width = cell_width * count;
            let margin_left = cell_width * left;
            $cell_row.css({
                'width': width + 'px',
                'margin-left': margin_left + 'px',
            })
            $week_disabled.append($cell_row);
        }
    }
}
