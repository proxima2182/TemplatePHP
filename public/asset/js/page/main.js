let timeoutId;
let map;
$(document).ready(function () {
    /**
     * activate full page
     */
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
                            'animation-name': 'slideOut',
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
                        'animation-name': 'slideIn',
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

    windowResize();

    /**
     * activate slick
     * @type {number}
     */
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

    map = new kakao.maps.Map(container, options); //지도 생성 및 객체 리턴
    let event = new Event("customMapLoad");
    dispatchEvent(event);
});

/**
 * window resize
 */
function windowResize() {
    let header_height = $('#header').height();
    let slick_height = window.innerHeight - header_height
    $('#page-start .main-slider-wrap').css('padding-top', header_height);
    $('#page-start .main-slider-wrap .slick').css('height', slick_height);
    $('#page-start .main-slider-wrap .slick .slick-element').css('height', slick_height);

    $('#page-start .slider-text-wrap').css({
        'line-height': `${slick_height}px`,
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
}

addEventListener("resize", (event) => {
    windowResize();
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
