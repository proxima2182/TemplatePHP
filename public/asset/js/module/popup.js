let popupTimeoutId;

/**
 * 파일로 css를 따로 둘 시 파일을 읽는 과정에서 지연이 생겨 내부에 포함시킴
 * (큰 차이는 없으나 slick에 영향줌)
 * @param id
 */
function openPopup(style, html, callback) {
    $('#container').append(`
<div class="popup-wrap">
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

.popup-wrap {
    text-align: center;
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.3);
    z-index: 15;
}

.popup-wrap .popup {
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

.popup-wrap .popup-inner {
    overflow-y: scroll;
    max-height: 400px;
    overflow-x: hidden;
    padding: 40px 20px;
}

.popup-wrap .popup .interface {
    height: 40px;
    background: #222;
    position: relative;
}

.popup-wrap .popup .button.close {
    width: 30px;
    height: 30px;
    line-height: 30px;
    text-align: center;
    position: absolute;
    right: 5px;
    top: 50%;
    margin-top: -15px;
}

.popup-wrap .popup .button.close img {
    display: inline-block;
    vertical-align: middle;
}

.popup-wrap .popup .slider-wrap {
    width: 600px;
    display: inline-block;
    text-align: center;
    position: relative;
}
</style>
${style ?? ''}
    <div class="popup">
        <div class="interface">
            <a href="javascript:closePopup()" class="button close">
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
    let popup_wrap = $('.popup-wrap').get(0);
    if (popup_wrap) {
        popup_wrap.addEventListener('click', function (event) {
            if (event.target.className && event.target.className.includes("popup-wrap")) {
                closePopup()
            }
        })
    }
    addEventListener("resize", resizeWindow);
}

function closePopup() {
    $('.popup-wrap .popup').css({
        'animation-duration': '0.2s',
        'animation-name': 'popupFadeOut',
    })
    removeEventListener('resize', resizeWindow);
    popupTimeoutId = setTimeout(function () {
        let popup_wrap = $('.popup-wrap').get(0);
        if (popup_wrap) {
            removeEventListener('click', closePopup);
        }
        $('.popup-wrap').remove()
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

