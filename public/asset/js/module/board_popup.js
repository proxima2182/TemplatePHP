let timeoutId;


/**
 * 파일로 css를 따로 둘 시 파일을 읽는 과정에서 지연이 생겨 내부에 포함시킴
 * (큰 차이는 없으나 slick에 영향줌)
 * @param id
 */
function openPopup(id) {
    $.ajax({
        type: 'GET',
        url: `/api/board/get/${id}`,
        success: function (data, textStatus, request) {

            var html = `
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

.popup-wrap .popup .slider-wrap {
    width: 600px;
    display: inline-block;
    text-align: center;
    position: relative;
}

.popup-wrap .popup .slider-wrap .slick-element {
    height: 200px;
    display: inline-block;
}

.popup-wrap .popup .slick button {
    width: 30px;
    height: 30px;
}

.popup-wrap .popup .slick button.slick-prev {
    left: -30px;
}

.popup-wrap .popup .slick button.slick-next {
    right: -30px;
}

.popup-wrap .popup .text-wrap {
    margin: 20px 50px 0 50px;
    text-align: left;
}

.popup-wrap .popup .text-wrap .title {
    font-size: 18px;
    line-height: 30px;
}

.popup-wrap .popup .content {
    font-size: 16px;
    text-overflow: ellipsis;
    white-space: normal;
    overflow: hidden;
    display: inline-block;
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
</style>
    <div class="popup">
        <div class="interface">
            <a href="javascript:closePopup()" class="button close"><img
                    src="/asset/images/icon/button_close_white.png"/></a>
        </div>
        <div class="popup-inner">
            <div class="slider-wrap">
                <div class="slick">`

            // TODO add loop
            html +=
                `
                    <div class="slick-element"
                         style="background: url('${data.image_url}') no-repeat center; background-size: cover; font-size: 0;">
                        Slider #0
                    </div>`
            html +=
                `
                </div>
            </div>
            <div class="text-wrap">
                <h4 class="title">${data.title}</h4>
                <p class="content">${data.content}</p>
            </div>
        </div>
    </div>
</div>`
            $('#container').append(html)
            windowResize();
            $('.popup-wrap .popup .slider-wrap .slick').slick({
                slidesToShow: 3,
                slidesToScroll: 1,
                autoplay: false,
                infinite: false,
            });
            let popup_wrap = $('.popup-wrap').get(0);
            if (popup_wrap) {
                popup_wrap.addEventListener('click', function (event) {
                    if (event.target.className && event.target.className.includes("popup-wrap")) {
                        closePopup()
                    }
                })
            }
            addEventListener("resize", windowResize);
        },
        error: function (request, textStatus, error) {
        },
        dataType: 'json'
    });
}

function closePopup() {
    $('.popup-wrap .popup').css({
        'animation-duration': '0.2s',
        'animation-name': 'popupFadeOut',
    })
    removeEventListener('resize', windowResize);
    timeoutId = setTimeout(function () {
        let popup_wrap = $('.popup-wrap').get(0);
        if (popup_wrap) {
            removeEventListener('click', closePopup);
        }
        $('.popup-wrap').remove()
        clearTimeout(timeoutId)
    }, 150)
}

/**
 * window resize
 */
function windowResize(event) {
    $('.popup-wrap').css({
        'line-height': `${window.innerHeight}px`,
    })
    let popup_height = window.innerHeight - 400 < 200 ? 200 : window.innerHeight - 400
    $('.popup-wrap .popup-inner').css({
        'max-height': `${popup_height}px`,
    })
}

