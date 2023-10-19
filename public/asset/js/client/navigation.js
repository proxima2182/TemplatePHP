function closeNavigation(isAnimation = true) {
    $('#header').addClass('closed');
    $('#header').css({
        'bottom': 'auto',
        'height': '60px',
    })
    if (isAnimation) {
        $('#header').css({
            'animation-duration': '0.2s',
            'animation-name': 'headerSlideUp',
        })
    }

    // menu button animation
    $('.button.menu span.middle').css({
        opacity: 1
    })
    $('.button.menu span.top').css({
        transform: 'rotate(0deg)',
        top: '15px',
    })
    $('.button.menu span.bottom').css({
        transform: 'rotate(0deg)',
        top: '25px',
    })
    $('.button.menu').attr({
        href: 'javascript:openNavigation()'
    })
}

function openNavigation() {
    $('#header').removeClass('closed');
    $('#header').css({
        'bottom': '0',
        'height': 'auto',
    })
    $('#header').css({
        'animation-duration': '0.2s',
        'animation-name': 'headerSlideDown',
    })

    // menu button animation
    $('.button.menu span.middle').css({
        opacity: '',
    })
    $('.button.menu span.top').css({
        transform: '',
        top: '',
    })
    $('.button.menu span.bottom').css({
        transform: '',
        top: '',
    })
    $('.button.menu').attr({
        href: 'javascript:closeNavigation()'
    })
}

addEventListener("resize", (event) => {
    if (window.innerWidth > 1070) {
        $('#header').css({
            'bottom': '',
            'height': '',
        })
        $('#header .header-inner').css({
            'display': ''
        })
    } else {
        if (!$('#header').hasClass('closed')) {
            closeNavigation(false);
        }
    }
});
