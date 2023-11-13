$(document).ready(function () {
    let $slick = $('.slider-wrap .slick');
    $slick.setOnResolutionChanged((event) => {
        let isSwipe = $slick.hasClass('uploader') ? false : true;
        $slick.setCustomSlick(event.detail.isMobile, {
            infinite: false,
            autoplay: false,
            draggable: false,
            swipe: isSwipe,
        });
        $slick.initDraggable({
            onDragFinished: onDragFinished,
        });
    })
});
