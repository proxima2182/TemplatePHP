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
    '10',
    '20',
    '30',
    '40',
    '50',
]

jQuery.prototype.initTimeSelector = function (option) {
    const $time_selector = this;

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
            html += `<li ${(i == 0) ? 'class = "selected"' : ''}>${values[i]}</li>`
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
    $time_selector.addClass(className);

    let html = `
    <style>
    .${className} {
        font-size: 0;
    }
    
    .${className} .time {
        margin: 10px 0;
    }
    .${className} .time .box {
        width: 100px;
        text-align: center;
        display: inline-block;
    }
    
    .${className} .time .box p {
        font-size: 18px;
        color: #333;
        margin-bottom: 10px;
        font-weight: 400;
    }
    
    .${className} .time ul {
        width: 30px;
        height: 30px;
        position: relative;
        text-align: center;
        display: inline-block;
    }
    
    .${className} .time ul li {
        width: 30px;
        height: 30px;
        line-height: 30px;
        font-size: 18px;
        color: #222;
        display: none;
        font-weight: normal;
        position: absolute;
    }
    
    .${className} .time ul li.selected {
        display: block;
    }
    
    .${className} .time .button {
        width: 30px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        cursor: pointer;
        display: inline-block;
    }
    
    .${className} .time .button * {
        vertical-align: middle;
    }
    </style>
    <div class="time">
        <span class="box hours">
            <p>시간</p>
            ${getSlotHtml(hours)}
        </span>
        <span class="box minutes">
            <p>분</p>
            ${getSlotHtml(minutes)}
    </div>`
    $time_selector.append(html)
    setUnselectable($(`.${className} .time *`));
    let $hours = $(`.${className} .time .hours`);
    let $minutes = $(`.${className} .time .minutes`);
    setActions($hours);
    setActions($minutes);

    let $time = $('.time');
    $time.append('<input type="hidden" name="time" value="09:00"/>')
}

function setUnselectable(view) {
    view.css({
        '-webkit-touch-callout': 'none',
        '-webkit-user-select': 'none',
        '-khtml-user-select': 'none',
        '-moz-user-select': 'none',
        '-ms-user-select': 'none',
        'user-select': 'none',
    })
    view.attr('unselectable', 'on')
    view.attr('onselectstart', 'return false;')
    view.attr('onmousedown', 'return false;')
}

function setActions(wrap) {
    let $button_top = wrap.find('.button.top');
    let $button_bottom = wrap.find('.button.bottom');

    let $list = wrap.find('ul li');
    setUnselectable($list)

    $button_top.click(function () {
        $list = wrap.find('ul li');
        let $selected = wrap.find('.selected');
        $selected.removeClass('selected');
        let index = $list.index($selected);

        if (index > 0) {
            index -= 1;
        } else {
            index = $list.length - 1;
        }
        $list.eq(index).addClass('selected');
        selectTime($(this).parents('span.box'));
    })
    $button_bottom.click(function () {
        $list = wrap.find('ul li');
        let $selected = wrap.find('.selected');
        $selected.removeClass('selected');
        let index = $list.index($selected);

        if (index < $list.length - 1) {
            index += 1;
        } else {
            index = 0;
        }
        $list.eq(index).addClass('selected');
        selectTime($(this).parents('span.box'));
    })
}

$hours = undefined;
$minutes = undefined;

function selectTime(changed) {
    let $time = $('.time');
    let $hidden = $time.find('input[type=hidden]');
    if ($hidden != undefined) {
        $hidden.remove();
    }
    let $minutes, $hours
    if (changed.hasClass('minutes')) {
        $minutes = changed;
    } else if (changed.hasClass('hours')) {
        $hours = changed;
    } else {
        return;
    }
    var hour = $hours.find('.selected')[0].innerHTML;
    var minute = $minutes.find('.selected')[0].innerHTML;
    console.log(hour + ", " + minute)
    var value = pad(hour, 2) + ':' + pad(minute, 2);
    $time.append('<input type="hidden" name="time" value="' + value + '"/>')
}
