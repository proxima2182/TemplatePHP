/**
 * @file admin 용 네비게이션 버튼 제어 스크립트
 */
/**
 * navigation 관련 위치를 refresh 시키는 기능
 * 전체 경우에 대한 position 값을 적어두고 위치 변화 필요할 때마다
 * 해당 함수 호출
 */
function refreshNavigationPosition() {
    let gnbWrap = $('.gnb-wrap');
    let gnb = $('.gnb');

    if (gnbWrap.hasClass('fixed')) {
        // fixed
        gnbWrap.css({
            'position': '',
            'top': '',
        })
        if (gnbWrap.hasClass('closed')) {
            gnbWrap.css({
                'left': '-150px',
            })
        } else {
            gnbWrap.css({
                'left': '',
            })
        }
        gnb.css({
            'left': '',
            'z-index': '',
        })
    } else {
        // absolute
        gnbWrap.css({
            'position': 'absolute',
            'left': '-150px',
            'top': '0',
        })
        if (gnbWrap.hasClass('closed')) {
            gnb.css({
                'left': '150px',
                'z-index': '-5',
            })
        } else {
            gnb.css({
                'left': '',
                'z-index': '',
            })
        }
    }
}

/**
 * window 사이즈에 따라 navigation position 변경
 * absolute - #wrap에 붙임
 */
function setNavigationAbsolute() {
    if ($('.gnb-wrap.fixed').length == 0) {
        return;
    }
    let gnbWrap = $('.gnb-wrap.fixed');
    gnbWrap.removeClass('fixed')
    gnbWrap.addClass('absolute')
    refreshNavigationPosition();

    // remove close button
    $('.gnb .button.navigation').remove();
}

/**
 * window 사이즈에 따라 navigation position 변경
 * static - 화면에 붙임
 * TODO 메뉴 세로 중간 정렬 작업 필요
 */
function returnNavigationAbsolute() {
    if ($('.gnb-wrap.absolute').length == 0) {
        return;
    }
    let gnbWrap = $('.gnb-wrap.absolute');
    gnbWrap.removeClass('absolute')
    gnbWrap.addClass('fixed')
    gnbWrap.css({
        'position': '',
        'top': '',
    })
    refreshNavigationPosition();

    // add close button
    $('.gnb').append(`
    <a href="javascript:closeNavigation()" class="button navigation close">
        <span class="top"></span>
        <span class="bottom"></span>
    </a>`)
}

/**
 * html 쪽에서 호출하는 navigation 제어 기능 - 닫기
 */
function closeNavigation() {
    let gnbWrap = $('.gnb-wrap');
    let gnb = $('.gnb');
    gnbWrap.addClass('closed')
    refreshNavigationPosition();

    // navigation slide animation
    if (gnbWrap.hasClass('fixed')) {
        gnbWrap.css({
            'animation-duration': '0.2s',
            'animation-name': 'gnbFixedSlideLeft',
        })
    } else {
        gnb.css({
            'animation-duration': '0.2s',
            'animation-name': 'gnbAbsoluteSlideRight',
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


/**
 * html 쪽에서 호출하는 navigation 제어 기능 - 열기
 */
function openNavigation() {
    let gnbWrap = $('.gnb-wrap');
    let gnb = $('.gnb');
    gnbWrap.removeClass('closed')
    refreshNavigationPosition();

    // navigation slide animation
    if (gnbWrap.hasClass('fixed')) {
        gnbWrap.css({
            'animation-duration': '0.2s',
            'animation-name': 'gnbFixedSlideRight',
        })
    } else {
        gnb.css({
            'animation-duration': '0.2s',
            'animation-name': 'gnbAbsoluteSlideLeft',
        })
    }

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
    if (window.innerWidth > 1500) {
        setNavigationAbsolute();
    } else {
        returnNavigationAbsolute();
    }
});

$(document).ready(function () {
    if (window.innerWidth > 1500) {
        setNavigationAbsolute();
    }
})
