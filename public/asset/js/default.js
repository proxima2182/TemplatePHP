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
