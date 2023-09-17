/**
 * @file 공통 javascript 함수 스크립트
 */

/**
 * textarea 에서 newline 표현을 위해 string 을 변환하는 기능
 * @returns {string}
 */
String.prototype.toTextareaString = function () {
    return this.replace(/(\\n|\n)/g, '&#10;')
}

/**
 * input 에서 값을 가져올 시 데이터베이스에 저장을 위해 string 을 변환하는 기능
 * @returns {string}
 */
String.prototype.toRawString = function () {
    return this.replace(/(\&\#10\;)/g, '\n')
}

function hash() {
    return Math.random().toString(36).substr(2, 11)
}

function isEmpty(value) {
    return !value || value.length == 0
}

function parseInputToData($inputs) {
    let data = {};
    for (let i = 0; i < $inputs.length; ++i) {
        let $input = $inputs.eq(i);
        if ($input.length > 0) {
            let domElement = $input[0]
            if (domElement.name) {
                if (domElement.tagName == 'textarea') {
                    data[domElement.name] = domElement.value.toRawString();
                }
                if (domElement.type == 'checkbox') {
                    data[domElement.name] = domElement.checked ? 1 : 0;
                } else {
                    data[domElement.name] = $input.val();
                }
            }
        }
    }
    return data;
}

function openWindow(url) {
    window.open(url)
}

function getCookie(cookie_name) {
    let x, y;
    let val = document.cookie.split(';');
    for (let i = 0; i < val.length; i++) {
        x = val[i].substring(0, val[i].indexOf('='));
        y = val[i].substring(val[i].indexOf('=') + 1);
        x = x.replace(/^\s+|\s+$/g, '');
        // 앞과 뒤의 공백 제거하기
        if (x == cookie_name) {
            return y;
        }
    }
}


function clearErrorsByClassName(className) {
    let $wrapErrorMessage = $(`.${className} .error-message-wrap`);
    $wrapErrorMessage.empty();
}

function showErrorsByClassName(className, response, status, requestOrError) {
    let $wrapErrorMessage = $(`.${className} .error-message-wrap`);
    $wrapErrorMessage.empty();
    if (status == 'success' || status >= 200 && status < 300) {
        if (response.messages) {
            for (let key in response.messages) {
                let message = response.messages[key];
                $wrapErrorMessage.append(`<div>${message}</div>`);
            }
        }
        if (response.message) {
            $wrapErrorMessage.append(`<div>${response.message}</div>`);
        }
    } else {
        let message = requestOrError;
        try {
            let errorObject = JSON.parse(response.responseText);
            if (errorObject.message) {
                message = errorObject.message
            }
        } catch (e) {
            // do nothing
        }
        if (message) {
            $wrapErrorMessage.append(`<div>${message}</div>`);
        }
    }
}

let isRequestRunning = false;

async function apiRequest(input) {
    if (isRequestRunning) return;
    isRequestRunning = true;
    let timeoutId = setTimeout(function () {
        $(`.loading-wrap`).css({
            display: 'block'
        })
        clearTimeout(timeoutId);
    }, 200);
    await $.ajax({
        type: input.type,
        data: input.data,
        dataType: input.dataType,
        url: input.url,
        processData: input.processData,
        contentType: input.contentType,
        cache: input.cache,
        success: function (response, status, request) {
            isRequestRunning = false;
            clearTimeout(timeoutId);
            if (input.success && typeof input.success == 'function') {
                input.success(response, status, request);
            }
            $(`.loading-wrap`).css({
                display: 'none'
            })
        },
        error: function (response, status, error) {
            isRequestRunning = false;
            clearTimeout(timeoutId);
            if (input.error && typeof input.error == 'function') {
                input.error(response, status, error);
            }
            $(`.loading-wrap`).css({
                display: 'none'
            })
        },
    });
}

jQuery.prototype.setVideoCoverStyle = function () {
    let $parent = this;
    let $video = $parent.find(`video`)
    if ($video.length == 0) return;
    this.css({
        'position': 'relative',
    })
    $video.css({
        'position': 'absolute',
    });
    let ratioParent = $parent.width() / $parent.height();
    let ratioVideo = $video.width() / $video.height();
    console.log("parent", $parent.width(), $parent.height())
    console.log("video", $video.width(), $video.height())
    if (ratioParent > ratioVideo) {
        let standard = $parent.width() / ratioVideo;
        $video.css({
            'width': '100%',
            'height': `${standard}px`,
            'top': '50%',
            'left': 0,
            'margin-top': `-${standard / 2}px`,
            'margin-left' : 0,
        });
    } else {
        let standard = $parent.height() * ratioVideo;
        $video.css({
            'width': `${standard}px`,
            'height': '100%',
            'top': 0,
            'left': '50%',
            'margin-top' : 0,
            'margin-left': `-${standard / 2}px`
        });
    }
}
