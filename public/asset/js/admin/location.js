function generateBlockConfirm(className) {
    return function () {
        $(`.${className} .control-wrap .button.search`).css({
            'display': 'inline-block',
        })
        $(`.${className} .control-wrap .button.confirm`).css({
            'display': 'none',
        })
    }
}

function generateUnblockConfirm(className) {
    return function () {
        $(`.${className} .control-wrap .button.search`).css({
            'display': 'none',
        })
        $(`.${className} .control-wrap .button.confirm`).css({
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
        let request = await fetch('/asset/css/common/input.css')
        if (!request.ok) throw request;
        let css = await request.text()
        let style = `
        <style>
        ${css}
        ${getPopupStyle(className)}
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
        })
        $(`.${className} .popup-box`).css({
            "padding-bottom": "61px",
        })
        $(`.${className} .popup-inner`).append(`
        <div class="control-wrap absolute line-before">
            <div class="control-box">
                <a href="javascript:search('${className}');" class="button search">
                    <img src="/asset/images/icon/search.png"/>
                    <span>Search</span>
                </a>
                <a href="javascript:closePopup('${className}');" class="button cancel">
                    <img src="/asset/images/icon/cancel.png"/>
                    <span>Cancel</span>
                </a>
                <a href="javascript:confirmInputPopupCreate('${className}');" class="button confirm" style="display: none;">
                    <img src="/asset/images/icon/check.png"/>
                    <span>Confirm</span>
                </a>
            </div>
        </div>`);

        $(`.${className} input[name=address]`).on("input", generateBlockConfirm(className));
    } catch (e) {
        console.log(e)
    }
}

/**
 * override
 * @param className
 * @param id
 */
function editInputPopup(className, id) {
    $(`.${className} .form-wrap .editable`).not(`.readonly`).removeAttr('readonly')
    $(`.${className} .form-wrap .editable`).not(`.readonly`).removeAttr('disabled')
    $(`.${className} .form-wrap .button-wrap`).remove();
    $(`.${className} .popup-inner .control-wrap`).remove();

    $(`.${className} .popup-inner`).append(`
    <div class="control-wrap absolute line-before">
        <div class="control-box">
            <a href="javascript:search('${className}');" class="button search" style="display: none;">
                <img src="/asset/images/icon/search.png"/>
                <span>Search</span>
            </a>
            <a href="javascript:refreshInputPopup(${id});" class="button cancel">
                <img src="/asset/images/icon/cancel.png"/>
                <span>Cancel</span>
            </a>
            <a href="javascript:confirmInputPopupEdit('${className}', ${id});" class="button confirm">
                <img src="/asset/images/icon/check.png"/>
                <span>Confirm</span>
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

function search(className) {
    let $wrapErrorMessage = $(`.${className} .error-message-wrap`);
    $wrapErrorMessage.empty();
    let data = parseInputToData($(`.${className} .editable`))
    console.log(data)

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
}
