/**
 * @file popup 용 공통 기능 스크립트
 */
let popupTimeoutId;

/**
 * popup 열기 기능
 * selector 를 공통으로 주면 함수가 전체 같은 동작이 적용되므로
 * 1. event 가 들어온 element 의 제시된 조건에 해당하는 모든 parent 를 검사해서 해당 element 제어
 *   (ex. close 시 해당 root element 제거)
 * 2. 생성될 때 추가 selector 를 설정
 * 2안에서 스타일 독립성도 유지할 수 있고 recursive 를 사용하지 않아도 때문에 2안으로 결정
 * 현재는 popup 종류가 동일한게 동시에 뜰 필요가 없지만, 필요한 경우 selector 를 class 가 아닌 id 로 바꾸어주어야한다
 * @param className     각 팝업을 각각 제어하기 위해 주는 selector name
 * @param style
 * @param html
 * @param callback      팝업 세팅 완료 후 수행되어져야 할 기능 callback
 */
function openPopup(className, style, html, callback) {
    $('#container').append(`
<div class="popup-wrap ${className}">
<style>
@keyframes popupFadeIn {
    from {
        margin-top: 100px;
        opacity: 0.5;
    }

    to {
        margin-top: 0px;
        opacity: 1;
    }
}

@keyframes popupFadeOut {
    from {
        margin-top: 0px;
        opacity: 1;
    }

    to {
        margin-top: 200px;
        opacity: 0;
    }
}

.${className} {
    text-align: center;
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.3);
    z-index: 15;
}

.${className} .popup {
    width: 700px;
    line-height: normal;
    display: inline-block;
    vertical-align: middle;
    background: #fff;
    box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3);
    position: relative;
    animation-duration: 0.2s;
    animation-name: popupFadeIn;
}

.${className} .popup-box {
    position: relative;
}

.${className} .popup-inner {
    overflow-y: scroll;
    max-height: 400px;
    overflow-x: hidden;
    padding: 20px 20px 40px 20px;
}

.${className} .popup .interface {
    height: 40px;
    line-height: 40px;
    text-align: right;
    background: #222;
    position: relative;
    font-size: 0;
}

.${className} .popup .interface .button.close {
    width: 30px;
    height: 30px;
    line-height: 30px;
    margin-right: 5px;
    display: inline-block;
    vertical-align: middle;
    text-align: center;
}

.${className} .popup .interface .button.close *{
    display: inline-block;
    vertical-align: middle;
}
</style>
${style ?? ''}
    <div class="popup">
        <div class="interface">
            <a href="javascript:closePopup('${className}')" class="button close">
                <img src="/asset/images/icon/cancel_white.png"/>
            </a>
        </div>
        <div class="popup-box">
            <div class="popup-inner">
            ${html ?? ''}
            </div>
        </div>
    </div>
</div>`)
    resizeWindow();
    if (callback && typeof callback == 'function') callback();
    let element = $(`.${className}`).get(0);
    if (element) {
        element.addEventListener('click', function (event) {
            if (event.target.className && event.target.className.includes && event.target.className.includes("popup-wrap")) {
                closePopup(className)
            }
        })
    }
    addEventListener("resize", resizeWindow);
}

/**
 * popup 끄기 기능
 * @param className
 */
function closePopup(className) {
    let $popupWrap = $(`.${className}`)
    $popupWrap.find('.popup').css({
        'animation-duration': '0.2s',
        'animation-name': 'popupFadeOut',
    })
    removeEventListener('resize', resizeWindow);
    popupTimeoutId = setTimeout(function () {
        let element = $popupWrap.get(0);
        if (element) {
            element.removeEventListener('click', closePopup);
        }
        $popupWrap.remove()
        clearTimeout(popupTimeoutId)
    }, 150)
}

/**
 * resize 시 호출 되는 기능
 * popup 의 세로 가운데 정렬을 위해 popup 의 root 영역에
 * line-height 를 화면에 비례해 조정해 준다
 * @param event
 */
function resizeWindow(event) {
    $('.popup-wrap').css({
        'line-height': `${window.innerHeight}px`,
    })
    let popup_height = window.innerHeight - 400 < 200 ? 200 : window.innerHeight - 400
    $('.popup-wrap .popup-inner').css({
        'max-height': `${popup_height}px`,
    })
}

function openPopupErrors(className, response, status, requestOrError) {
    let style = `
        <style>
        body .${className} .popup {
            width: 500px;
        }

        .${className} .popup-inner .error-message-wrap {
            padding: 20px 0;
        }

        .${className} .popup-inner .button-wrap {
            margin-top: 20px;
        }

        .${className} .popup-inner .button-wrap .button {
            min-width: 100px;
            padding: 10px 20px;
            margin: 0 10px;
        }
        </style>`
    let hasMessage = false;
    let html = `
        <div class="error-message-wrap">`
    if (status == 'success' || status >= 200 && status < 300) {
        if (response.messages) {
            for (let key in response.messages) {
                let message = response.messages[key];
                html += `<div>${message}</div>`
                hasMessage = true;
            }
        }
        if (response.message) {
            html += `<div>${response.message}</div>`
            hasMessage = true;
        }
    } else {
        let message = requestOrError;
        try {
            let errorObject = JSON.parse(response.responseText);
            if (errorObject.message) {
                message = errorObject.message
            }
        } catch (e) {
        }
        if (message) {
            html += `<div>${message}</div>`
            hasMessage = true;
        }
    }
    html += `
        </div>
        <div class="button-wrap controls">
            <a href="javascript:closePopup('${className}')" class="button ok white">OK</a>
        </div>`;
    if (hasMessage) {
        openPopup(className, style, html);
    }
}

/**
 * 공통 style string 생성 함수
 * - popup-input 을 사용하지 않는 보기 전용인 경우 호출
 * @param className
 * @returns {string}
 */
function getPopupViewStyle(className) {
    return `
    <style>
    .${className} .popup-inner {
        padding: 20px 20px 80px 20px;
    }
    
    .${className} .row {
        text-align: left;
        font-size: 0;
    }
    
    .${className} .row .column {
        padding: 10px;
        line-height: 35px;
        text-align: left;
        box-sizing: border-box;
        font-size: 18px;
        font-weight: 400;
        display: inline-block;
        vertical-align: bottom;
    }
    
    .${className} .popup-inner .control-wrap.absolute {
        line-height: 20px;
        text-align: right;
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        background: #fff;
    }
    .${className} .popup-inner .control-box {
        padding: 5px 20px 15px 20px;
    }
    </style>`
}
