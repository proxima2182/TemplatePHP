/**
 * @file login popup 스크립트
 */

function login(className) {
    clearErrorsByClassName(className);

    let data = parseInputToData($(`.${className} .form-wrap input`))

    apiRequest({
        type: 'POST',
        url: `/api/user/login`,
        data: data,
        dataType: 'json',
        success: function (response, status, request) {
            if (!response.success) {
                showErrorsByClassName(className, response, status, request);
                return;
            }
            location.reload();
        },
        error: function (response, status, error) {
            showErrorsByClassName(className, response, status, error);
        },
    });
}


function logout() {
    apiRequest({
        type: 'POST',
        url: `/api/user/logout`,
        dataType: 'json',
        success: function (response, status, request) {
            if (!response.success) return;
            location.reload();
        },
        error: function (response, status, error) {
        },
    });
}

$(document).ready(function() {
    let element = $(`.login-container`).get(0);
    if (element) {
        element.addEventListener('keydown', function (event) {
            switch (event.key) {
                case 'Escape':
                    closePopup(className);
                    break;
                case 'Enter':
                    let button = $(`.login-container .button.confirm`).get(0);
                    if (button) {
                        button.click();
                    }
                    break;
            }
        })
    }
})
