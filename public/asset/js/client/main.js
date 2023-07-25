/**
 * @file 메인 페이지 동작 스크립트
 */
let timeoutId;
let map;
$(document).ready(function () {
    // activate full page
    $('#fullpage').fullpage({
        anchors: ['0', '1', '2', '3', '4', '5'],
        menu: '#menu',
        onLeave: function (index, nextIndex, direction) {
            if (index === 1) {
                // 이전 다른 스타일 변경이 예약되어있다면 취소
                if (timeoutId) {
                    clearTimeout(timeoutId);
                    timeoutId = undefined;
                }

                // 간소화 스타일 적용
                timeoutId = setTimeout(function () {
                    let body = $('body')
                    let header = $('#header');
                    if (body && header) {
                        header.remove()
                        body.append(header)

                        header.css({
                            'position': 'absolute',
                            'top': '0',
                            'animation-duration': '0.2s',
                            'animation-name': 'headerSlideOut',
                        })
                    }

                    let utill = header.find('.utill');
                    if (utill) {
                        utill.css({
                            'height': '60px',
                        })
                    }
                    let logo = header.find('.logo');
                    if (logo) {
                        logo.css({
                            'position': 'absolute',
                            'width': '400px',
                            'height': '60px',
                            'line-height': '60px',
                            'top': '0',
                            'text-align': 'left',
                        })
                    }
                    clearTimeout(timeoutId);
                    timeoutId = undefined;
                }, 200);
            }
            if (nextIndex == 1) {
                // 이전 다른 스타일 변경이 예약되어있다면 취소
                if (timeoutId) {
                    clearTimeout(timeoutId);
                    timeoutId = undefined;
                }
                let header = $('#header');
                if (header) {
                    header.css({
                        'animation-duration': '0.2s',
                        'animation-name': 'headerSlideIn',
                    })
                }

                // 간소화 스타일 원복
                timeoutId = setTimeout(function () {
                    let section_start = $('#page-start');
                    let header = $('#header');
                    if (section_start && header) {
                        header.remove();
                        section_start.append(header)

                        header.css({
                            'position': '',
                            'top': '',
                            'animation-duration': '',
                            'animation-name': '',
                        })
                    }
                    let utill = header.find('.utill');
                    if (utill) {
                        utill.css({
                            'height': '',
                        })
                    }
                    let logo = header.find('.logo');
                    if (logo) {
                        logo.css({
                            'position': '',
                            'width': '',
                            'height': '',
                            'line-height': '',
                            'top': '',
                            'text-align': '',
                        })
                    }
                    clearTimeout(timeoutId);
                    timeoutId = undefined;
                }, 200);
            }
        }
    });

    resizeWindow();

    // activate slick
    $('#page-start .main-slider-wrap .slick').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
    });
    $('#page-preview .slick').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: false,
    });

    let container = document.getElementById('map'); //지도를 담을 영역의 DOM 레퍼런스
    let options = { //지도를 생성할 때 필요한 기본 옵션
        center: new kakao.maps.LatLng(36.119312, 127.838161), //지도의 중심좌표.
        level: 12, //지도의 레벨(확대, 축소 정도)
        // draggable: false,
        scrollwheel: false,
        disableDoubleClick: true,
        disableDoubleClickZoom: true,
    };


    checkPagePopup();


    map = new kakao.maps.Map(container, options); //지도 생성 및 객체 리턴
    let event = new Event("customMapLoad");
    dispatchEvent(event);
});

function openPagePopup($parent, className, style, html, callback) {
    if (!$parent) return;
    $parent.append(`
    <div class="page-popup ${className}">
    ${style ?? ''}
        <div class="interface">
            <a href="javascript:closePagePopup('${className}')" class="button close">
                <img src="/asset/images/icon/cancel_white.png"/>
            </a>
        </div>
        <div class="popup-box">
            <div class="popup-inner">
            ${html ?? ''}
            </div>
        </div>
    </div>`)
    if (callback && typeof callback == 'function') callback();
}

/**
 * popup 끄기 기능
 * @param className
 */
function closePagePopup(className) {
    let $popupWrap = $(`.${className}`)
    $popupWrap.remove()
    resizePagePopupWindow();
}

function closePagePopupTodayDisabled(className) {
    let $popupWrap = $(`.${className}`)
    $popupWrap.remove()
    resizePagePopupWindow();
}

function resizePagePopupWindow() {
    let $popups = $('.page-popup')
    let left = 20;
    for (let i = 0; i < $popups.length; i++) {
        let popup = $popups.get(i);
        popup.style = `top: 20px; left: ${left}px`;
        left += 470;
    }
}

function checkPagePopup() {
    $.ajax({
        type: 'GET',
        url: `/api/board/topic/get/popup`,
        success: function (response, status, request) {
            if (!response.success) return;
            let array = response.array;
            let style = `
                <style>
                </style>`;
            for (let i in array) {
                let data = array[i]
                let className = `page-popup-${hash()}`;
                let html = `
                <div class="slider-wrap">
                    <div class="slick">`;
                //TODO add loop
                for (let index in data.images) {
                    let image = data.images[index];
                    html +=
                        `
                        <div class="slick-element"
                             style="background: url('${image}') no-repeat center; background-size: cover; font-size: 0;">
                            Slider #${index}
                        </div>`
                }
                html += `
                    </div>
                </div>
                <div class="button-wrap">
                    <a href="javascript:closePagePopupTodayDisabled(${data['id']})" class="button black">
                        <span>Don't show this popup today</span>
                    </a>
                </div>`
                openPagePopup($('body'), className, style, html, function () {
                    $(`.page-popup.${className} .slick`).slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        autoplay: true,
                        arrows: false,
                        autoplaySpeed: 5000,
                    });
                })
            }
            resizePagePopupWindow();
        },
        error: function (request, status, error) {
        },
        dataType: 'json'
    });
}

/**
 * window resize
 */
function resizeWindow() {
    let header_height = $('#header').height();
    let content_height = window.innerHeight - header_height
    $('#page-start .main-slider-wrap').css('padding-top', header_height);
    $('#page-start .main-slider-wrap .slick').css('height', content_height);
    $('#page-start .main-slider-wrap .slick .slick-element').css('height', content_height);

    $('#page-start .slider-text-wrap').css({
        'line-height': `${content_height}px`,
    })
    $('#page-video .page-inner').css({
        'line-height': `${window.innerHeight - 100}px`,
    })
    $('#page-preview .page-inner').css({
        'line-height': `${window.innerHeight - 100}px`,
    })
    $('#page-last .page-inner').css({
        'line-height': `${window.innerHeight - 265}px`,
    })
    resizePagePopupWindow();
}

addEventListener("resize", (event) => {
    resizeWindow();
});

function setMapPoints(points) {
    let bounds = new kakao.maps.LatLngBounds();

    let i, marker;


    var imageSize = new kakao.maps.Size(20, 40),
        imageOptions = {
            spriteOrigin: new kakao.maps.Point(0, 0),
            spriteSize: new kakao.maps.Size(20, 40)
        };
    // 마커이미지와 마커를 생성합니다
    var markerImage = new kakao.maps.MarkerImage("/asset/images/icon/marker.png", imageSize, imageOptions);

    for (i = 0; i < points.length; i++) {
        // 배열의 좌표들이 잘 보이게 마커를 지도에 추가합니다
        let point = points[i];
        let mapPoint = new kakao.maps.LatLng(point.latitude, point.longitude);
        marker = new kakao.maps.Marker({
            position: mapPoint,
            image: markerImage,
        });
        marker.setMap(map);

        // LatLngBounds 객체에 좌표를 추가합니다
        bounds.extend(mapPoint);
    }

    map.setBounds(bounds);
    map.setLevel(map.getLevel() + 1);
    // map.setMinLevel(12)
    // map.setMaxLevel(13)
}
