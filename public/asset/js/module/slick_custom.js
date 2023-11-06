const mobileSlickPageSize = 2;
const pcSlickPageSize = 4;

function getCustomSlickChildren($slick) {
    let $children;
    let isMobile = $slick.hasClass('mobile');
    if (isMobile) {
        $children = [];
        let $wraps = $slick.find('.slick-item-wrap');
        let firstArray = [];
        let secondArray = [];
        for (let i = 0; i < $wraps.length; ++i) {
            let $chs = $($wraps.get(i)).children();
            if (i % mobileSlickPageSize == 0 && i != 0) {
                $children = $children.concat(firstArray);
                $children = $children.concat(secondArray);
                firstArray = [];
                secondArray = [];
            }
            for (let j = 0; j < $chs.length; ++j) {
                if (j % 2 == 0) {
                    firstArray.push($chs[j])
                } else {
                    secondArray.push($chs[j])
                }
            }
        }
        $children = $children.concat(firstArray);
        $children = $children.concat(secondArray);
    } else {
        $children = $slick.find('.slick-item');
    }
    return $children;
}

jQuery.prototype.moveCustomSlickOffset = function (index) {
    let $slick = this;

    if (index < 0) index = 0;

    let isMobile = $slick.hasClass('mobile');
    if (isMobile) {
        $slick.slick('slickSetOption', 'speed', 0);
        $slick.slick('slickGoTo', index % mobileSlickPageSize + Math.floor(index / mobileSlickPageSize / 2) * mobileSlickPageSize + 1, false);
        $slick.slick('slickSetOption', 'speed', 300);
    } else {
        $slick.slick('slickSetOption', 'speed', 0);
        $slick.slick('slickGoTo', index + 1, false);
        $slick.slick('slickSetOption', 'speed', 300);
    }
}

jQuery.prototype.addCustomSlickItem = function (index, html) {
    let $slick = this;

    let $children = getCustomSlickChildren($slick);
    let $result = [];
    $result = $result.concat($children.slice(0, index));
    $result = $result.concat([html]);
    $result = $result.concat($children.slice(index));
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
    if ($slick.hasClass('slick-initialized')) {
        $slick.slick("unslick");
    }
    $slick.setCustomSlick(isMobile(), option)
    $slick.moveCustomSlickOffset(index + 1);
}

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
    $slick.setCustomSlick(isMobile(), option)
    $slick.moveCustomSlickOffset(index - 1);
}

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

        console.log($children)

        let $resultArray = [];
        let height = (window.innerHeight - 300) / 2;

        function getChild(i) {
            let ch = $children.get(i);
            ch.style.height = `${height}px`;
            ch.style.width = ``;
            return ch.cloneNode(true);
        }

        let slotCount = mobileSlickPageSize * 2;
        let slotPage = Math.floor($children.length / slotCount);
        let slotIndex = $children.length % slotCount;
        let totalIndex = slotPage * mobileSlickPageSize + (slotIndex > 0 ? 1 : 0) * mobileSlickPageSize;
        for (let i = 0; i < totalIndex; ++i) {
            let page = Math.floor(i / mobileSlickPageSize);
            let itemArray = [];
            if (i + mobileSlickPageSize * page < $children.length) {
                let itemIndex = i + mobileSlickPageSize * page;
                itemArray.push(getChild(itemIndex));
            }
            if (i + mobileSlickPageSize * (page + 1) < $children.length) {
                let itemIndex = i + mobileSlickPageSize * (page + 1);
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
            'total': $children.length
        })
        $slick.slick({
            ...option,
            slidesToShow: mobileSlickPageSize,
            slidesToScroll: 2,
        });
    } else {
        // PC 뷰
        // 한 행에 두개의 열을 가지도록 children 이 조정된 경우 원복
        // 아닌 경우 그대로 slick 실행
        $slick.removeClass('mobile');

        let $children = $slick.find('.slick-item-wrap');
        if ($children.length > 0) {
            let $resultArray = [];
            let firstArray = [];
            let secondArray = [];
            for (let i = 0; i < $children.length; ++i) {
                let $chs = $($children.get(i)).children();
                $chs.css({
                    'height': ''
                })
                if (i % mobileSlickPageSize == 0 && i != 0) {
                    $resultArray = $resultArray.concat(firstArray);
                    $resultArray = $resultArray.concat(secondArray);
                    firstArray = [];
                    secondArray = [];
                }
                for (let j = 0; j < $chs.length; ++j) {
                    if (j % 2 == 0) {
                        firstArray.push($chs[j])
                    } else {
                        secondArray.push($chs[j])
                    }
                }
            }

            $resultArray = $resultArray.concat(firstArray);
            $resultArray = $resultArray.concat(secondArray);

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
            slidesToShow: pcSlickPageSize,
            slidesToScroll: 1,
        });
    }
}
