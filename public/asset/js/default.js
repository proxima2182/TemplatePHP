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

/**
 * 시간 format에 맞도록 width에 맞게 0을 채우는 기능
 * @param n
 * @param width
 * @returns {string}
 */
function pad(n, width) {
    n = n + '';
    return n.length >= width ? n : new Array(width - n.length + 1).join('0') + n;
}

function hash() {
    return Math.random().toString(36).substr(2, 11)
}


function parseInputToData($inputs) {
    let data = {};
    for (let i = 0; i < $inputs.length; ++i) {
        let $input = $inputs.eq(i);
        if ($input.length > 0) {
            let domElement = $input[0]
            if(domElement.tagName == 'textarea') {
                data[domElement.name] = domElement.value.toRawString();
            }if(domElement.type == 'checkbox') {
                data[domElement.name] = domElement.checked ? 1 : 0;
            } else {
                data[domElement.name] = $input.val();
            }
        }
    }
    return data;
}

function openWindow(url) {
    window.open(url)
}
