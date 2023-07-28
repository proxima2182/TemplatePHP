/**
 * @file form 관련 공통 기능 스크립트
 */
let getConfirmUrl, validate;

function initializeForm(input) {
    getConfirmUrl = input.getConfirmUrl;
    validate = input.validate;
}

function confirm() {
    if (!getConfirmUrl) return;
    $('.error-message-box').empty();
    let data = {};
    let inputs = $('.form-box input');
    for (let i = 0; i < inputs.length; ++i) {
        let input = inputs.eq(i);
        if (input.length > 0) {
            data[input[0].name] = input.val();
        }
    }

    //TODO validation
    if (validate && typeof validate == 'function') {
        if (!validate()) return;
    }

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: getConfirmUrl(),
        success: function (response, status, request) {
            if (!response.success) return;
            let data = response.data;
        },
        error: function (response, status, error) {
        },
    });

}

function confirmEditProfile() {

}
