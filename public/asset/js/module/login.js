/**
 * @file login popup 스크립트
 */

/**
 * login 가능한 popup 을 여는 기능
 * 모든 페이지에서 login 버튼을 이용해 호출할 수 있도록 함
 * @returns {Promise<void>}
 * @throws {Response}           fetch 로 파일읽기에 실패했을 경우 결과를 throw
 * @todo(log) throw 에 잡힌 경우 log 로 남길 필요 있음
 */
async function openPopupLogin() {
    try {
        let request = await fetch('/asset/css/common/input.css')
        if (!request.ok) throw request;
        let css = await request.text()
        let className = 'popup-login';
        let style = `
        <style>
        ${css}
        body .${className} .popup {
            width: 600px;
        }

        .${className} h3.title {
            margin: 0 0 20px 0;
            font-size: 20px;
            font-weight: 600;
        }

        .${className} .popup-inner {
            overflow: hidden;
        }

        .${className} .form-wrap {
            margin: 10px 50px;
        }

        .${className} .form-wrap .input-wrap * {
            line-height: 40px;
        }

        .${className} .input-wrap.inline .input-title {
            width: calc(30% - 15px);
        }

        .${className} .control-button-wrap {
            line-height: 20px;
            font-weight: 600;
            margin: 0 50px;
        }

        .${className} .control-button-wrap a {
            margin: 3px;
        }

        .${className} .control-button-wrap span {
            font-size: 16px;
        }

        .${className} .popup-inner .button-wrap {
            margin-top: 40px;
        }

        .${className} .popup-inner .button-wrap .button {
            min-width: 100px;
            padding: 10px 20px;
            margin: 0 10px;
        }
        </style>`
        let html = `
        <h3 class="title">
            Login
        </h3>
        <div class="form-wrap">
            <div class="input-wrap">
                <p class="input-title">Username</p>
                <input type="text" name="username" class="under-line"/>
            </div>
            <div class="input-wrap">
                <p class="input-title">Password</p>
                <input type="password" name="password" class="under-line"/>
            </div>
        </div>
        <div class="control-button-wrap">
            <a href="/registration" class="button register">
                <img src="/asset/images/icon/plus_circle.png"/>
                <span>Register</span>
            </a>
        </div>
        <div class="control-button-wrap">
            <a href="/reset-password" class="button forgot-password">
                <img src="/asset/images/icon/password.png"/>
                <span>Forgot Password</span>
            </a>
        </div>
        <div class="error-message-wrap">
        </div>
        <div class="button-wrap controls">
            <a href="javascript:closePopup('${className}')" class="button cancel white">Cancel</a>
            <a href="javascript:login('${className}')" class="button confirm black">Login</a>
        </div>`;
        openPopup({
            className: className,
            style: style,
            html: html,
            onEnterKeydown: function() {
                let button = $(`.${className} .button.confirm`).get(0);
                if(button) {
                    button.click();
                }
            },
        })
    } catch (e) {
        // do nothing
    }
}

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
