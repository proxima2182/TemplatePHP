$(document).ready(function () {
    resizeWindow();
    if(!isMobile()) {
        setSlick(false);
    }
});

addEventListener("resize", (event) => {
    resizeWindow();
});

/**
 * window resize
 */
function resizeWindow() {
    if (isMobile() && !$('body').hasClass('mobile')) {
        // mobile 로 전환
        // 첫 load 때 모바일인 경우 호출됨
        $('body').addClass('mobile');
        setSlick(true);
    }
    if (!isMobile() && $('body').hasClass('mobile')) {
        // pc 로 전환
        $('body').removeClass('mobile');
        setSlick(false);
    }
}

function setSlick(isMobile = false) {
    let $slick = $('.slider-wrap .slick');

    $slick.setCustomSlick(isMobile, {
        infinite: false,
        autoplay: false,
        draggable: false,
        swipe: false,
    });
}
