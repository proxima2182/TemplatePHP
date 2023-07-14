let popupTimeoutId;

/**
 * 파일로 css를 따로 둘 시 파일을 읽는 과정에서 지연이 생겨 내부에 포함시킴
 * (큰 차이는 없으나 slick에 영향줌)
 * @param id
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

.${className} .popup-inner {
    overflow-y: scroll;
    max-height: 400px;
    overflow-x: hidden;
    padding: 40px 20px;
}

.${className} .popup .interface {
    height: 40px;
    background: #222;
    position: relative;
}

.${className} .popup .button.close {
    width: 30px;
    height: 30px;
    line-height: 30px;
    text-align: center;
    position: absolute;
    right: 5px;
    top: 50%;
    margin-top: -15px;
}

.${className} .popup .button.close img {
    display: inline-block;
    vertical-align: middle;
}

.${className} .popup .slider-wrap {
    width: 600px;
    display: inline-block;
    text-align: center;
    position: relative;
}
</style>
${style ?? ''}
    <div class="popup">
        <div class="interface">
            <a href="javascript:closePopup('${className}')" class="button close">
                <img src="/asset/images/icon/button_close_white.png"/>
            </a>
        </div>
        <div class="popup-inner">
        ${html ?? ''}
        </div>
    </div>
</div>`)
    resizeWindow();
    if (callback && typeof callback == 'function') callback();
    let element = $(`.${className}`).get(0);
    if (element) {
        element.addEventListener('click', function (event) {
            if (event.target.className && event.target.className.includes("popup-wrap")) {
                console.log(className)
                closePopup(className)
            }
        })
    }
    addEventListener("resize", resizeWindow);
}

function closePopup(className) {
    let popupWrap = $(`.${className}`)
    popupWrap.find('.popup').css({
        'animation-duration': '0.2s',
        'animation-name': 'popupFadeOut',
    })
    removeEventListener('resize', resizeWindow);
    popupTimeoutId = setTimeout(function () {
        let element = popupWrap.get(0);
        if (element) {
            element.removeEventListener('click', closePopup);
        }
        popupWrap.remove()
        clearTimeout(popupTimeoutId)
    }, 150)
}

/**
 * window resize
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

