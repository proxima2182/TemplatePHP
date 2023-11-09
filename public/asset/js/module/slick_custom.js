/**
 * @file slick 용 mobile/PC 레이아웃 전환 스크립트
 */
const mobileSlickPageRowSize = 2;
const mobileSlickSlotSize = 2;
const pcSlickSlotSize = 4;

/**
 * slick item 배열을 얻는 기능
 * @param $slick
 * @returns []
 */
function getCustomSlickChildren($slick) {
    let $resultArray = [];
    let isMobile = $slick.hasClass('mobile');
    if (isMobile) {
        let $wraps = $slick.find('.slick-item-wrap');
        for (let i = 0; i < $wraps.length; ++i) {
            let $childrenArray = $wraps.eq(i).children();
            for (let j = 0; j < $childrenArray.length; ++j) {
                $resultArray.push($childrenArray[j])
            }
        }
    } else {
        $resultArray = $slick.find('.slick-item');
    }
    return $resultArray;
}

/**
 * item index를 입력받으면 해당 item이 보이도록 slickGoTo 호출해 주는 기능
 * @param offset
 */
jQuery.prototype.moveCustomSlickOffset = function (offset) {
    // 몇 번째 item 인지 offset 으로 입력받음
    let $slick = this;

    let isMobile = $slick.hasClass('mobile');
    let slotSize;
    if (isMobile) {
        offset = Math.floor(offset / mobileSlickPageRowSize)
        slotSize = mobileSlickSlotSize;
    } else {
        offset = offset
        slotSize = pcSlickSlotSize;
    }
    // slickGoTo는 보이는 구간의 첫 offset 을 전달해 줘야 하므로 slotSize를 빼줌
    offset = offset - slotSize + 1;
    if (offset < 0) offset = 0;
    $slick.slick('slickSetOption', 'speed', 0);
    // 0부터 시작 하지 않고 1부터 시작하기 때문에 +1 해줌
    $slick.slick('slickGoTo', offset + 1, false);
    $slick.slick('slickSetOption', 'speed', 300);
}

/**
 * slick 에 item 추가
 * @param index
 * @param html
 */
jQuery.prototype.addCustomSlickItem = function (index, html) {
    let $slick = this;

    let $children = getCustomSlickChildren($slick);
    let $result = [];
    $result = $result.concat($children.slice(0, index));
    $result = $result.concat([html]);
    $result = $result.concat($children.slice(index));
    if ($slick.hasClass('slick-initialized')) {
        $slick.slick("unslick");
    }
    $slick.empty();
    $slick.append($result);

    let optionString = $slick.attr('option');
    let option;
    if (optionString) {
        try {
            option = JSON.parse(optionString);
        } catch (e) {
            // do nothing
        }
    }
    let isMobile = $slick.hasClass('mobile');
    $slick.setCustomSlick(isMobile, option)
    $slick.moveCustomSlickOffset(index + 1);
}

/**
 * slick 에 아이템 제거
 * @param index
 */
jQuery.prototype.removeCustomSlickItem = function (index) {
    let $slick = this;

    let $children = getCustomSlickChildren($slick);
    let $result = [];
    $result = $result.concat($children.slice(0, index));
    if (index + 1 < $children.length) {
        $result = $result.concat($children.slice(index + 1));
    }
    if ($slick.hasClass('slick-initialized')) {
        $slick.slick("unslick");
    }
    $slick.empty();
    $slick.append($result);

    let optionString = $slick.attr('option');
    let option;
    if (optionString) {
        try {
            option = JSON.parse(optionString);
        } catch (e) {
            // do nothing
        }
    }
    let isMobile = $slick.hasClass('mobile');
    $slick.setCustomSlick(isMobile, option)
    $slick.moveCustomSlickOffset(index - 1);
}

/**
 * slick 설정
 * mobile/PC 환경에 따라 레이아웃 설정
 * @param isMobile
 * @param option
 */
jQuery.prototype.setCustomSlick = function (isMobile = false, option = {}) {
    let $slick = this;
    if (!option) option = {};

    try {
        $slick.attr({
            'option': JSON.stringify(option)
        })
    } catch (e) {
        // do nothing
    }

    if (isMobile) {
        // 모바일 뷰
        // 한 행에 두개의 열을 가지도록 children 조정
        $slick.addClass('mobile');
        let $children = $slick.find('.slick-item');
        let total = $children.length;
        let $resultArray = [];
        let height = (window.innerHeight - 300) / 2;

        function getChild(i) {
            let ch = $children.get(i);
            ch.style.height = `${height}px`;
            ch.style.width = ``;
            return ch.cloneNode(true);
        }

        let totalSlotIndex = Math.ceil($children.length / mobileSlickPageRowSize);
        for (let i = 0; i < totalSlotIndex; ++i) {
            let itemArray = [];
            let itemIndex = i * mobileSlickPageRowSize;
            if (itemIndex < $children.length) {
                itemArray.push(getChild(itemIndex));
            }
            itemIndex = i * 2 + 1;
            if (itemIndex < $children.length) {
                itemArray.push(getChild(itemIndex));
            }
            $resultArray.push(itemArray);
        }
        if ($slick.hasClass('slick-initialized')) {
            $slick.slick("unslick");
        }
        $slick.empty();
        for (let i in $resultArray) {
            let $div = $('<div class="slick-item-wrap" draggable="true"></div>');
            for (let j in $resultArray[i]) {
                $div.append($resultArray[i][j])
            }
            $slick.append($div);
        }
        $slick.attr({
            'total': total
        })
        $slick.slick({
            ...option,
            slidesToShow: mobileSlickSlotSize,
            slidesToScroll: 1,
        });
    } else {
        // PC 뷰
        // 한 행에 두개의 열을 가지도록 children 이 조정된 경우 원복
        // 아닌 경우 그대로 slick 실행
        $slick.removeClass('mobile');
        let $children = $slick.find('.slick-item-wrap');
        if ($children.length > 0) {
            let $resultArray = [];
            for (let i = 0; i < $children.length; ++i) {
                let $childrenArray = $($children.get(i)).children();
                $childrenArray.css({
                    'height': ''
                })
                $resultArray = $resultArray.concat($childrenArray);
            }

            if ($slick.hasClass('slick-initialized')) {
                $slick.slick("unslick");
            }
            $slick.empty();
            $slick.append($resultArray);
        }
        $slick.attr({
            'total': $slick.children().length
        })
        $slick.slick({
            ...option,
            slidesToShow: pcSlickSlotSize,
            slidesToScroll: 1,
        });
    }
}
