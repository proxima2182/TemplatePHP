$(document).ready(function () {
    setTopicSlick(isMobile());
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
        setTopicSlick(true);
    }
    if (!isMobile() && $('body').hasClass('mobile')) {
        // pc 로 전환
        $('body').removeClass('mobile');
        setTopicSlick(false);
    }
}

function setTopicSlick(isMobile = false) {
    let $slick = $('.slider-wrap .slick');
    if ($slick.hasClass('slick-initialized')) {
        $slick.slick("unslick");
    }

    if (isMobile) {
        // 모바일 뷰
        $slick.slick({
            slidesToShow: 2,
            slidesToScroll: 2,
            infinite: false,
            autoplay: false,
            draggable: false,
        });
    } else {
        // PC 뷰
        $slick.slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: false,
            infinite: false,
            draggable: false,
        });
    }
}
