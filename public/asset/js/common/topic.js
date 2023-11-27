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
        try {
            $slick.initDraggable({
                onDragFinished: onDragFinished,
            });
        } catch (e) {
            // do nothing
            // topic view page doesn't need initDraggable
        }
    })
});
