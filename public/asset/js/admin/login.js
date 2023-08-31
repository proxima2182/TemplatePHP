/**
 * @file login popup 스크립트
 */

function clearErrors(className) {
    let $wrapErrorMessage = $(`.${className} .error-message-wrap`);
    $wrapErrorMessage.empty();
}

function showErrors(className, response, status, requestOrError) {
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

function login(className) {
    clearErrors(className);

    let data = parseInputToData($(`.${className} .form-wrap input`))

    $.ajax({
        type: 'POST',
        data: data,
        dataType: 'json',
        url: `/api/user/login`,
        success: function (response, status, request) {
            if (!response.success) {
                showErrors(className, response, status, request);
                return;
            }
            location.reload();
        },
        error: function (response, status, error) {
            showErrors(className, response, status, error);
        },
    });
}


function logout() {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: `/api/user/logout`,
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
