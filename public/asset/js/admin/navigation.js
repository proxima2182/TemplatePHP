function closeNavigation() {
    let gnbWrap = $('.gnb-wrap');
    let gnb = $('.gnb');
    if (gnbWrap.hasClass('fixed')) {
        gnb.css({
            'left': '-150px',
            'animation-duration': '0.2s',
            'animation-name': 'gnbFixedSlideLeft',
        })
    } else {
        gnb.css({
            'left': '150px',
            'z-index': '-5',
            'animation-duration': '0.2s',
            'animation-name': 'gnbAbsoluteSlideRight',
        })
    }

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
    let gnbWrap = $('.gnb-wrap');
    let gnb = $('.gnb');
    if (gnbWrap.hasClass('fixed')) {
        gnb.css({
            'left': '',
            'animation-duration': '0.2s',
            'animation-name': 'gnbFixedSlideRight',
        })
    } else {
        gnb.css({
            'left': '',
            'z-index': '-5',
            'animation-duration': '0.2s',
            'animation-name': 'gnbAbsoluteSlideLeft',
        })
    }

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

function setNavigationAbsolute() {
    if ($('.gnb-wrap.fixed').length == 0) {
        return;
    }
    let gnbWrap = $('.gnb-wrap.fixed');
    gnbWrap.css({
        'position': 'absolute',
        'left': '-150px',
        'top': '0',
    })
    gnbWrap.removeClass('fixed')
    gnbWrap.addClass('absolute')

    $('.gnb .button.navigation').remove();
    // let buttonClose = $('.button.menu')
    // buttonClose.remove();
    // $('.header-inner').append(buttonClose)
}

function returnNavigationAbsolute() {
    if ($('.gnb-wrap.absolute').length == 0) {
        return;
    }
    let gnbWrap = $('.gnb-wrap.absolute');
    gnbWrap.css({
        'position': '',
        'left': '',
        'top': '',
    })
    gnbWrap.removeClass('absolute')
    gnbWrap.addClass('fixed')

    $('.gnb').append(`
    <a href="javascript:closeNavigation()" class="button navigation close">
        <span class="top"></span>
        <span class="bottom"></span>
    </a>`)
}

addEventListener("resize", (event) => {
    if (window.innerWidth > 1500) {
        setNavigationAbsolute();
    } else {
        returnNavigationAbsolute();
    }
});

$(document).ready(function () {
    if (window.innerWidth > 1500) {
        setNavigationAbsolute();
    } else {
        $('.gnb').append(`
        <a href="javascript:closeNavigation()" class="button navigation close">
            <span class="top"></span>
            <span class="bottom"></span>
        </a>`)
    }
})
