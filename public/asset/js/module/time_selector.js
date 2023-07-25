/**
 * @file 시간 선택 모듈 자동생성 스크립트
 * @todo 시간제약, 스타일 설정 option 추가
 */

const hours = [
    '09',
    '10',
    '11',
    '12',
    '13',
    '14',
    '15',
    '16',
    '17',
    '18',
    '19',
]

const minutes = [
    '00',
    '30',
]

function setTimeSelectorDropdown($parent) {
    function getSlotHtml(values) {
        let html = `
        <div>
            <span class="button top">
                <svg width="18px" height="10px"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16.06 8.74">
                    <defs>
                        <style>.cls-1 {
                            fill: none;
                            stroke: #000;
                            stroke-miterlimit: 10;
                        }</style>
                    </defs>
                    <polyline class="cls-1" points="15.71 8.38 8.03 0.71 0.35 8.38"/>
                </svg>
            </span>
        </div>
        <ul>`;

        for (let i in values) {
            html += `<li ${(i == 0) ? 'class = "selected"' : ''}>${pad(values[i], 2)}</li>`
        }
        html += `
        </ul>
        <div>
            <span class="button bottom">
                <svg width="18px" height="10px"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16.06 8.74">
                    <defs>
                        <style>.cls-1 {
                            fill: none;
                            stroke: #000;
                            stroke-miterlimit: 10;
                        }</style>
                    </defs>
                    <polyline class="cls-1" points="0.35 0.35 8.03 8.03 15.71 0.35"/>
                </svg>
            </span>
        </div>`
        return html
    }

    const rand = Math.random().toString(36).substr(2, 11);
    let className = `time-selector-${rand}`;
    $parent.addClass(className);

    let html = `
    <style>
    .${className} {
        font-size: 0;
        margin: 10px 0;
    }
    
    .${className} .selector-box {
        width: 80px;
        text-align: center;
        display: inline-block;
    }
    
    .${className} .selector-box p {
        font-size: 16px;
        color: #222;
        font-weight: 200;
    }
    
    .${className} ul {
        width: 30px;
        height: 30px;
        position: relative;
        text-align: center;
        display: inline-block;
    }
    
    .${className} ul li {
        width: 30px;
        height: 30px;
        line-height: 30px;
        font-size: 18px;
        color: #222;
        display: none;
        font-weight: normal;
        position: absolute;
    }
    
    .${className} ul li.selected {
        display: block;
    }
    
    .${className} .button {
        width: 30px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        cursor: pointer;
        display: inline-block;
    }
    
    .${className} .button * {
        vertical-align: middle;
    }
    </style>
    <span class="selector-box hours">
        <p>시간</p>
        ${getSlotHtml(hours)}
    </span>
    <span class="selector-box minutes">
        <p>분</p>
        ${getSlotHtml(minutes)}
    </span>
    <input type="hidden" name="time" value="09:00"/>`
    $parent.append(html)
    setUnselectable($parent.find(`span`));
    let $hours = $parent.find(`.hours`);
    let $minutes = $parent.find(`.minutes`);
    setDropdownAction($parent, $hours);
    setDropdownAction($parent, $minutes);
}

function setTimeSelectorButtons($parent) {
    const rand = Math.random().toString(36).substr(2, 11);
    let className = `time-selector-${rand}`;
    $parent.addClass(className);

    let html = `
    <style>
    .${className} {
        font-size: 0;
        margin: 10px 0;
    }
    .${className} .time-selector-list{
        overflow-x: scroll;
    }
    .${className} .time-selector-list ul{
        font-size: 0;
        margin: 10px 0;
        text-align: left;
    }
    .${className} .time-selector-list ul li{
        width: 80px;
        margin: 5px;
        display: inline-block;
        font-size: 18px;
        text-align: center;
        border-radius: 20px;
        line-height: 40px;
        background: #eee;
    }
    .${className} .time-selector-list ul li.selected{
        color: #fff;
        background: #000;
    }
    </style>
    <div class="time-selector-list">
        <ul>`
    for (let i in hours) {
        for (let j in minutes) {
            html += `<li>${pad(hours[i], 2)}:${pad(minutes[j], 2)}</li>`
        }
    }
    `</ul>
    </div>
    <input type="hidden" name="time" value=""/>`
    $parent.append(html)
    let listWidth = hours.length * minutes.length * 90
    $parent.find(`.time-selector-list ul`).css({
        width: `${listWidth}px`
    })
    setUnselectable($parent.find(`li`));
    $parent.find(`li`).click(function () {
        $parent.find('.selected').removeClass('selected');
        $(this).addClass('selected');

        let $input = $parent.find('input[type=hidden]');
        if ($input != undefined) {
            $input.remove();
        }
        $parent.append('<input type="hidden" name="time" value="' + this.innerHTML + '"/>')
    })
}

jQuery.prototype.initTimeSelector = function (option) {
    const $parent = this;
    setTimeSelectorButtons($parent)
}

jQuery.prototype.getSelectedTime = function () {
    const $parent = this;
    let $input = $parent.find('input[type=hidden]');
    return $input.val();
}

function setUnselectable($view) {
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

function setDropdownAction($parent, $wrap) {
    let $button_top = $wrap.find('.button.top');
    let $button_bottom = $wrap.find('.button.bottom');

    let $list = $wrap.find('ul li');
    setUnselectable($list)

    $button_top.click(function () {
        $list = $wrap.find('ul li');
        let $selected = $wrap.find('.selected');
        $selected.removeClass('selected');
        let index = $list.index($selected);

        if (index > 0) {
            index -= 1;
        } else {
            index = $list.length - 1;
        }
        $list.eq(index).addClass('selected');
        selectDropdownTime($parent);
    })
    $button_bottom.click(function () {
        $list = $wrap.find('ul li');
        let $selected = $wrap.find('.selected');
        $selected.removeClass('selected');
        let index = $list.index($selected);

        if (index < $list.length - 1) {
            index += 1;
        } else {
            index = 0;
        }
        $list.eq(index).addClass('selected');
        selectDropdownTime($parent);
    })
}

function selectDropdownTime($parent) {
    let hour = $parent.find('.hours .selected').html();
    let minute = $parent.find('.minutes .selected').html();
    let value = pad(hour, 2) + ':' + pad(minute, 2);

    let $input = $parent.find('input[type=hidden]');
    if ($input != undefined) {
        $input.remove();
    }
    $parent.append('<input type="hidden" name="time" value="' + value + '"/>')
}
