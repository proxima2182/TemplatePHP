// override onResolutionChanged from default.js
onResolutionChanged = (isMobile = false) => {
    closePopup();
    let $slick = $('.slider-wrap .slick');

    $slick.setCustomSlick(isMobile, {
        infinite: false,
        autoplay: false,
        draggable: false,
        swipe: false,
    });
}
