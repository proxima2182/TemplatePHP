/**
 * @file 캘린더 모듈 자동생성 스크립트
 * @todo setCalendar 파악, 스타일 설정 option 추가
 */

let today = new Date();

function getOptionStyle(className, cellSize) {
    let innerCellSize = cellSize - 20
    let innerCellSizeHalf = innerCellSize / 2
    return `
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
        width: ${cellSize}px;
        height: ${cellSize}px;
        font-weight: 400;
        position: relative;
    }

    .calendar.${className} .date li .calendar-number-wrap {
        width: ${innerCellSize}px;
        height: ${innerCellSize}px;
        line-height: ${innerCellSize}px;
        margin: -${innerCellSizeHalf}px -${innerCellSizeHalf}px 0 0;
        position: absolute;
        top: 50%;
        right: 50%;
        z-index: 2;
    }

    .calendar.${className} .date li .calendar-number {
        font-size: 14px;
        line-height: normal;
    }

    .calendar.${className}.square .date li .calendar-number {
        position: absolute;
        top: 5px;
        left: 5px
    }

    .calendar.${className}.circle .date li.today .calendar-number-wrap {
        border: 1px solid #222;
        font-weight: bold;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        border-radius: 50%;
    }

    .calendar.${className}.square .date li.today {
        background: #efefef;
    }

    .calendar.${className}.circle .date li.selected .calendar-number-wrap {
        background: #222;
        color: #fff;
        font-weight: bold;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        border-radius: 50%;
    }

    .calendar.${className}.square .date li.selected {
        background: #666;
        color: #fff;
        font-weight: bold;
    }

    .calendar.${className} .date li.red .calendar-number {
        color: #a40000;
    }

    .calendar.${className} .date li.blue .calendar-number {
        color: #100964;
    }

    .calendar.${className} .date li.selected.blue span {
        color: #6799FF;
    }

    .calendar.${className} .calendar-week-title-row {
        font-size: 0;
    }

    .calendar.${className} .calendar-week-title-row li {
        width: ${cellSize}px;
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

    .calendar.${className} .nav .calendar-button {
        width: 30px;
        height: 30px;
        line-height: 30px;
        cursor: pointer;
        position: absolute;
        margin-top: -12px;
        top: 50%;
        text-align: center;
    }

    .calendar.${className} .nav .calendar-button * {
        vertical - align: middle;
    }

    .calendar.${className} .nav .calendar-button.prev {
        left: 15px;
    }

    .calendar.${className} .nav .calendar-button.next {
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

    .calendar.${className} .calendar-disabled {
        position: absolute;
        top: 100px;
        width: 420px;
        text-align: left;
    }

    .calendar.${className}.circle .calendar-disabled li {
        width: 100%;
        height: ${innerCellSize}px;
        margin-top: 20px;
        border-radius: ${innerCellSizeHalf}px;
        background: #eee;
    }
    .calendar.${className}.square .calendar-disabled li {
        width: 100%;
        height: ${innerCellSize}px;
        background: #ddd;
    }
    </style>`
}

/**
 * jquery object 에서 해당 함수 호출 시 내부에 calendar 를 추가해주는 기능
 * @param option
 * @todo refactoring & release as an open source
 */
jQuery.prototype.initCalendar = function (option) {
    let $parent = this;
    if ($parent.length == 0) return;
    let cellSize = 60;
    let timeStandard = new Date();
    let styleType = 'circle';
    let language = 'en';
    let limit = 'none';

    styleType = option.style ?? styleType;
    timeStandard = option.timeStandard ?? timeStandard;
    cellSize = option.cellSize ?? cellSize;
    language = option.lang ?? language;
    limit = option.limit ?? limit;
    // rebinding
    option.style = styleType;
    option.timeStandard = timeStandard;
    option.cellSize = cellSize;
    option.lang = language;
    option.limit = limit;
    $parent.addClass(styleType);
    try {
        $parent.attr({
            option: JSON.stringify(option),
        })
    } catch (e) {
        // do nothing
    }
    let dayOfWeek = {};
    switch (language) {
        case 'ko' :
            dayOfWeek = {
                'mon': '월',
                'tue': '화',
                'wed': '수',
                'thu': '목',
                'fri': '금',
                'sat': '토',
                'sun': '일',
            };
    }
    const rand = Math.random().toString(36).substr(2, 11);
    let className = `calendar-view-${rand}`;
    $parent.addClass(className);
    $parent.css({
        'width': `${cellSize * 7}px`,
        'position': 'relative',
        'display': 'inline-block',
        'vertical-align': 'middle',
    })

    let html = `
    ${getOptionStyle(className, cellSize)}
    <div class="nav">
        <span class="calendar-button prev">
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
        <span class="calendar-button next">
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
    </div>`;

    html += `<ul class="calendar-week-title-row">`;

    let weeks = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
    for (let i in weeks) {
        let weekKey = weeks[i];
        let weekOptionString = dayOfWeek[weekKey];
        if (weekOptionString) {
            html += `<li>${weekOptionString}</li>`;
        } else {
            html += `<li>${weekKey.toUpperCase()}</li>`;
        }
    }

    html += `</ul>`;
    html += `
    <ul class="date">
    </ul>
    <ul class="calendar-disabled">
    </ul>`
    $parent.append(html);

    $parent.attr({
        now: timeStandard.getTime(),
    })

    let $prev = $parent.find('.calendar-button.prev');
    let $next = $parent.find('.calendar-button.next');
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
    setCalendar($parent, timeStandard);
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
    let $liSelected = $parent.find('.selected');
    if ($liSelected != undefined) {
        $liSelected.removeClass('selected')
    }
    $cell.addClass('selected');

    let $input = $parent.find('input[type=hidden]');
    if ($input != undefined) {
        $input.remove();
    }
    let day = $cell.find('span.calendar-number')[0].innerHTML;
    let value = year.padStart(4, '0') + '-' + month.padStart(2, '0') + '-' + day.padStart(2, '0');
    $parent.append('<input type="hidden" name="date" value="' + value + '">')
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
    let months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    switch (option.lang) {
        case 'ko' :
            months = [];
            for (let i = 1; i <= 12; ++i) {
                months.push(`${i.toString().padStart(2, '0')} 월`)
            }
    }

    let $year = $parent.find('.nav .year');
    let $month = $parent.find('.nav .month');
    let $weeks = $parent.find('.week li');
    setStyleUnselectable($weeks);
    setStyleUnselectable($year);
    setStyleUnselectable($month);
    let $cells = $parent.find('ul.date');
    let $ulDisabled = $parent.find('ul.calendar-disabled');
    $cells.empty();
    $ulDisabled.empty();

    let year = date.getFullYear();
    let month = date.getMonth();
    let hasToday = (year == today.getFullYear() && month == today.getMonth());
    $year[0].innerHTML = year;
    $month[0].innerHTML = months[month];

    let $prev = $parent.find('.calendar-button.prev');
    let $next = $parent.find('.calendar-button.next');
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

    let disabledRowCount = rows;
    if (hasToday && yesterday.getMonth() == today.getMonth()) {
        let yesterday_row = getRow(firstDay, yesterday);
        disabledRowCount = yesterday_row
    } else if (yesterday.getFullYear() == year && yesterday.getMonth() < month ||
        yesterday.getFullYear() < year) {
        disabledRowCount = 0;
    }

    let days = 0;
    for (let i = 0; i < firstDay.getDay(); i++, days++) {
        let $cell = $('<li></li>');
        setStyleUnselectable($cell)
        $cells.append($cell);
    }
    for (let i = 0; i < numberOfDays; i++, days++) {
        let $cell = $(`<li id="day-${(i + 1)}"><span class="calendar-number-wrap"></span></li>`);
        $cell.find(`.calendar-number-wrap`).append(`<span class="calendar-number">${(i + 1)}</span>`)
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

    let cellHeight = option.style == 'circle' ? (cell_width - 20) : cell_width;
    let cellStandardY = option.style == 'circle' ? 20 : 10;

    $ulDisabled.css({
        paddingTop: `${cellStandardY}px`
    })

    for (let i = 0; i < disabledRowCount; ++i) {
        let $row = $('<li></li>');

        let count = 7;
        let left = 0;

        if (i == 0) {
            count -= firstDay.getDay();
            left += firstDay.getDay();
            $row.css({
                marginTop: 0
            })
        }
        if (hasToday && i == disabledRowCount - 1) {
            count -= (7 - yesterday.getDay() - 1);
        }

        if (!hasToday && rows == disabledRowCount && i == disabledRowCount - 1) {
            count -= (7 - lastDay.getDay() - 1)
        }

        let width = cell_width * count;
        let margin_left = cell_width * left;
        $row.css({
            'width': width + 'px',
            'height': cellHeight + 'px',
            'margin-left': margin_left + 'px',
        })
        $ulDisabled.append($row);
    }
    if (disabledRowCount == 0 && hasLimitedDay) {
        let limited_row = getRow(firstDay, limitedDay);
        let last_row = getRow(firstDay, lastDay);
        let loop_count = last_row - limited_row + 1;
        for (let i = 0; i < loop_count; ++i) {
            let $row = $('<li></li>');
            let count = 7;
            let left = 0;
            if (i == 0) {
                count -= limitedDay.getDay();
                left += limitedDay.getDay()
                $row.css({
                    'margin-top': 0
                })
            }
            if (i == loop_count - 1) {
                count -= (7 - lastDay.getDay() - 1);
            }
            let width = cell_width * count;
            let margin_left = cell_width * left;
            $row.css({
                'width': width + 'px',
                'height': cellHeight + 'px',
                'margin-left': margin_left + 'px',
            })
            $ulDisabled.append($row);
        }
    }
}
