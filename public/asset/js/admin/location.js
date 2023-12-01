/**
 * @file location.php
 */

/**
 * 공통 동작 함수 생성
 * confirm 버튼 금지 기능
 * @param className
 * @returns {(function(): void)|*}
 */
function generateBlockConfirm(className) {
    return function () {
        $(`.${className} .control-button-wrap .button.search`).css({
            'display': 'inline-block',
        })
        $(`.${className} .control-button-wrap .button.confirm`).css({
            'display': 'none',
        })
    }
}

/**
 * 공통 동작 함수 생성
 * confirm 버튼 금지 해제 기능
 * @param className
 * @returns {(function(): void)|*}
 */
function generateUnblockConfirm(className) {
    return function () {
        $(`.${className} .control-button-wrap .button.search`).css({
            'display': 'none',
        })
        $(`.${className} .control-button-wrap .button.confirm`).css({
            'display': 'inline-block',
        })
    }
}

/**
 * override
 * @returns {Promise<void>}
 */
async function openInputPopupCreate() {
    let className = 'popup-create';
    if (!getCreateUrl || !getHtml) return;
    try {
        let css = await loadStyleFile('/asset/css/common/input.css', "." + className);
        css += await loadStyleFile('/asset/css/common/popup/input.css', "." + className);
        let style = `
        <style>
        ${css}
        </style>`
        let html = `
        <div class="form-wrap">
            ${getHtml()}
            <div class="error-message-wrap"></div>
        </div>`
        openPopup({
            className: className,
            style: style,
            html: html,
        }, ($parent) => {
            $parent.find(`.form-wrap .info-text-wrap`).css({
                'display' : 'inline-block'
            })
            $parent.find(`.popup-box`).addClass('has-control-button');
            $parent.find(`.popup-inner`).append(`
            <div class="control-button-wrap absolute line-before">
                <div class="control-button-box">
                    <a href="javascript:searchAddress('${className}');"
                        class="button under-line search">
                        <img src="/asset/images/icon/search.png"/>
                        <span>${lang('search')}</span>
                    </a>
                    <a href="javascript:closePopup('${className}');"
                        class="button under-line cancel">
                        <img src="/asset/images/icon/cancel.png"/>
                        <span>${lang('cancel')}</span>
                    </a>
                    <a href="javascript:confirmInputPopupCreate('${className}');"
                        class="button under-line confirm" style="display: none;">
                        <img src="/asset/images/icon/check.png"/>
                        <span>${lang('confirm')}</span>
                    </a>
                </div>
            </div>`);

            $parent.find(`input[name=address]`).on("input", generateBlockConfirm(className));
        })
    } catch (e) {
        // do nothing
    }
}

/**
 * override
 * @param className
 * @param id
 */
function editInputPopup(className, id) {
    let $parent = $(`.${className}`);
    $parent.find(`.form-wrap .editable`).not(`.readonly`).removeAttr('readonly')
    $parent.find(`.form-wrap .editable`).not(`.readonly`).removeAttr('disabled')
    $parent.find(`.form-wrap .button-wrap`).remove();
    $parent.find(`.popup-inner .control-button-wrap`).remove();

    $parent.find(`.form-wrap .info-text-wrap`).css({
        'display' : 'inline-block'
    })

    $parent.find(`.popup-inner`).append(`
    <div class="control-button-wrap absolute line-before">
        <div class="control-button-box">
            <a href="javascript:searchAddress('${className}');"
                class="button under-line search" style="display: none;">
                <img src="/asset/images/icon/search.png"/>
                <span>${lang('search')}</span>
            </a>
            <a href="javascript:refreshInputPopup(${id});"
                class="button under-line cancel">
                <img src="/asset/images/icon/cancel.png"/>
                <span>${lang('cancel')}</span>
            </a>
            <a href="javascript:confirmInputPopupEdit('${className}', ${id});"
                class="button under-line confirm">
                <img src="/asset/images/icon/check.png"/>
                <span>${lang('confirm')}</span>
            </a>
        </div>
    </div>`);

    let $inputAddress = $(`.${className} input[name=address]`);
    $inputAddress.attr({
        'original': $inputAddress.val()
    })
    $inputAddress.on("input", function () {
        if (this.value == this.getAttribute('original')) {
            generateUnblockConfirm(className)();
        } else {
            generateBlockConfirm(className)();
        }
    });
}

/**
 * 주소검색 기능
 * @param className
 */
function searchAddress(className) {
    let $wrapErrorMessage = $(`.${className} .error-message-wrap`);
    $wrapErrorMessage.empty();
    try {
        let data = parseInputToData($(`.${className} .editable`))

        var geocoder = new kakao.maps.services.Geocoder();

        // 주소로 좌표를 검색합니다
        geocoder.addressSearch(data['address'], function (result, status) {

            // 정상적으로 검색이 완료됐으면
            if (status === kakao.maps.services.Status.OK) {
                $(`.${className} input[name=latitude]`).val(result[0].y);
                $(`.${className} input[name=longitude]`).val(result[0].x);
                generateUnblockConfirm(className)();
            } else {
                $wrapErrorMessage.append(`<div>잘못된 주소 입니다.</div>`)
            }
        });
    } catch (e) {
        $wrapErrorMessage.append(`<div>지도 APPKEY 가 올바른 값인지 확인해주세요.</div>`)
    }
}
