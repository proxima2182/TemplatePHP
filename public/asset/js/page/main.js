
let timeoutId;
$(document).ready(function () {
    /**
     * activate full page
     */
    $('#fullpage').fullpage({
        anchors: ['0', '1', '2', '3', '4'],
        menu: '#menu',
        onLeave: function (index, nextIndex, direction) {
            if (index === 1) {
                // 이전 다른 스타일 변경이 예약되어있다면 취소
                if(timeoutId) {
                    clearTimeout(timeoutId);
                    timeoutId = undefined;
                }

                // 간소화 스타일 적용
                timeoutId = setTimeout(function(){
                    let body = $('body')
                    let header = $('#header');
                    if (body && header) {
                        header.remove()
                        body.append(header)

                        header.css ({
                            'position' : 'absolute',
                            'top' : '0',
                            'animation-duration' : '0.2s',
                            'animation-name' : 'slideOut',
                        })
                    }

                    let utill = header.find('.utill');
                    if (utill) {
                        utill.css ({
                            'height' : '60px',
                        })
                    }
                    let logo = header.find('.logo');
                    if(logo) {
                        logo.css ({
                            'position' : 'absolute',
                            'width' : '400px',
                            'height' : '60px',
                            'line-height' : '60px',
                            'top' : '0',
                            'text-align' : 'left',
                        })
                    }
                    clearTimeout(timeoutId);
                    timeoutId = undefined;
                }, 200);
            }
            if (nextIndex == 1) {
                // 이전 다른 스타일 변경이 예약되어있다면 취소
                if(timeoutId) {
                    clearTimeout(timeoutId);
                    timeoutId = undefined;
                }
                let header = $('#header');
                if (header) {
                    header.css ({
                        'animation-duration' : '0.2s',
                        'animation-name' : 'slideIn',
                    })
                }

                // 간소화 스타일 원복
                timeoutId = setTimeout(function(){
                    let section_start = $('#section_start');
                    let header = $('#header');
                    if (section_start && header) {
                        header.remove();
                        section_start.append(header)

                        header.css ({
                            'position' : '',
                            'top' : '',
                            'animation-duration' : '',
                            'animation-name' : '',
                        })
                    }
                    let utill = header.find('.utill');
                    if (utill) {
                        utill.css ({
                            'height' : '',
                        })
                    }
                    let logo = header.find('.logo');
                    if(logo) {
                        logo.css ({
                            'position' : '',
                            'width' : '',
                            'height' : '',
                            'line-height' : '',
                            'top' : '',
                            'text-align' : '',
                        })
                    }
                    clearTimeout(timeoutId);
                    timeoutId = undefined;
                }, 200);
            }
        }
    });

    /**
     * activate slick
     * @type {number}
     */
    let header_height = $('#header').height();
    let slick_height = window.innerHeight - header_height
    $('#section_start .main-slider-wrap').css('padding-top', header_height);
    $('#section_start .main-slider-wrap .slick').css('height', slick_height);
    $('#section_start .main-slider-wrap .slick .slick-element').css('height', slick_height);
    $('#section_start .main-slider-wrap .slick').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
    });

    const slider_text_box = $('#section_start .slider-text-wrap .text-box');

        slider_text_box.css ({
            'margin-top' : `${-1 * slider_text_box.height() / 2}px`,
            'margin-left' : `${-1 * slider_text_box.width() / 2}px`,
        })
});
