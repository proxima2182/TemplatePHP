/**
 * @file reset_password.php
 */

/**
 * [change password] step#01
 * verification code 보냄과 동시에 압력 대기 페이지로 전환하는 기능
 * @param isAdmin
 */
function sendVerificationCode(isAdmin = 0) {
    let $inputUsername = $('#container .form-wrap input[name=username]');
    let $wrapErrorMessage = $('#container .form-box .error-message-wrap');
    $wrapErrorMessage.empty();
    if (isEmpty($inputUsername.val())) {
        $wrapErrorMessage.append(`<p>username field is empty.</p>`)
        return;
    }

    let data = {
        'username': $inputUsername.val(),
        'is_admin': isAdmin,
    }

    apiRequest({
        type: 'POST',
        url: `/api/email/send/verification-code/reset-password`,
        data: data,
        dataType: 'json',
        success: function (response, status, request) {
            if (!response.success) {
                showErrors(response, status, request);
                return;
            }

            $inputUsername.attr({
                'readonly': true,
            })
            $(`#container .form-wrap .disappear-at-next-step`).remove();
            $('#container .form-wrap').prepend(`
                <div class="info-text-wrap disappear-at-next-step">
                   ${lang('message_info_mail')}
                </div>
                `)
            $('#container .form-wrap').append(`
                    <div class="input-wrap disappear-at-next-step">
                        <p class="input-title">${lang('verification_code')}</p>
                        <input type="text" name="code" class="under-line"/>
                    </div>
                    <div class="timer disappear-at-next-step">
                        03:00
                    </div>
                    <div class="error-message-wrap disappear-at-next-step">
                    </div>
                    <div class="button-wrap controls disappear-at-next-step">
                        <a href="javascript:resendVerificationCode(${isAdmin})" class="button resend white">${lang('resend')}</a>
                        <a href="javascript:confirmVerificationCode(${isAdmin})" class="button confirm black">${lang('confirm')}</a>
                    </div>
                `)
            startTimer();
        },
        error: function (response, status, error) {
            showErrors(response, status, error);
        },
    });
}

/**
 * [change password] step#01'
 * verification code 다시 보내는 기능
 * @param isAdmin
 */
function resendVerificationCode(isAdmin = 0) {
    stopTimer();
    clearErrors();

    let $inputUsername = $('#container .form-wrap input[name=username]');
    let data = {
        'username': $inputUsername.val(),
        'is_admin': isAdmin,
    }
    let $inputCode = $('#container .form-wrap input[name=code]');
    $inputCode.val('');

    apiRequest({
        type: 'POST',
        url: `/api/email/send/verification-code/reset-password`,
        data: data,
        dataType: 'json',
        success: function (response, status, request) {
            if (!response.success) {
                showErrors(response, status, request);
                return;
            }

            startTimer();
        },
        error: function (response, status, error) {
            showErrors(response, status, error);
        },
    });
}

/**
 * [change password] step#02
 * verification 완료하고 성공 시 비밀번호 변경 하는 페이지 출력 하는 기능
 * @param isAdmin
 */
function confirmVerificationCode(isAdmin = 0) {
    clearErrors();

    let $inputUsername = $('#container .form-wrap input[name=username]');
    let $inputCode = $('#container .form-wrap input[name=code]');
    let data = {
        'username': $inputUsername.val(),
        'code': $inputCode.val(),
    }

    apiRequest({
        type: 'POST',
        url: `/api/user/reset-password/verify`,
        data: data,
        dataType: 'json',
        success: function (response, status, request) {
            if (!response.success) {
                showErrors(response, status, request);
                return;
            }
            stopTimer();

            $(`#container .form-wrap .disappear-at-next-step`).remove();
            $('#container .form-wrap').append(`
            <div class="input-wrap" style="margin-top: 40px">
                <p class="input-title">${lang('password_new')}</p>
                <input type="password" name="password" class="under-line editable"/>
            </div>
            <div class="input-wrap">
                <p class="input-title">${lang('password_confirm_new')}</p>
                <input type="password" name="confirm_password" class="under-line editable"/>
            </div>
            <div class="error-message-wrap">
            </div>`)
            $('#container .form-box').append(`
            <div class="button-wrap controls">
                <a href="javascript:confirmChangePassword(${isAdmin})" class="button confirm black">${lang('confirm')}</a>
            </div>`)

        },
        error: function (response, status, error) {
            showErrors(response, status, error);
        },
    });
}

/**
 * [change password] step#03
 * 비밀번호 변경 완료 기능
 * @param isAdmin
 */
function confirmChangePassword(isAdmin = 0) {
    clearErrors();

    let $wrapErrorMessage = $('#container .form-box .error-message-wrap');
    let data = parseInputToData($(`#container .form-wrap input`))

    let validations = [
        {
            key: 'username',
            name: 'Username'
        },
        {
            key: 'password',
            name: 'Password'
        },
        {
            key: 'confirm_password',
            name: 'Confirm Password'
        },
    ];

    for (let i in validations) {
        let validation = validations[i];
        if (isEmpty(data[validation.key])) {
            $wrapErrorMessage.append(`<p>${validation.name} field is empty.</p>`)
        }
    }
    if (data['password'] != data['confirm_password']) {
        $wrapErrorMessage.append(`<p>please check two fields for password is same.</p>`)
    }

    apiRequest({
        type: 'POST',
        url: `/api/user/reset-password/confirm`,
        data: data,
        dataType: 'json',
        success: function (response, status, request) {
            if (!response.success) {
                showErrors(response, status, request);
                return;
            }
            if (!isAdmin) {
                window.location.assign('/');
            } else {
                window.location.assign('/admin');
            }
        },
        error: function (response, status, error) {
            showErrors(response, status, error);
        },
    });
}

/**
 * 에러 출력 기능
 */
function showErrors(response, status, requestOrError) {
    let $wrapErrorMessage = $('#container .form-box .error-message-wrap');
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

/**
 * 출력한 에러 지우는 기능
 */
function clearErrors() {
    let $wrapErrorMessage = $('#container .form-box .error-message-wrap');
    $wrapErrorMessage.empty();
}

/**
 * 타이머 시작 기능
 */
function startTimer() {
    //시간을 format string 으로 변환하는 기능
    function getTimeToString(time) {
        /* to show seconds to exact format */
        let time_string = "";
        let m = parseInt(time / 60);
        let s = time % 60;
        if (m < 10) {
            time_string += "0";
        }
        time_string += (m + ":");
        if (s < 10) {
            time_string += "0";
        }
        time_string += s;
        return time_string;
    }

    let $timer = $('.form-box .timer')
    let time = 600;
    $timer.html(getTimeToString(time));
    let timer_interval_id = setInterval(function () {
        if (time >= 0) {
            $timer.html(getTimeToString(time));
            time--;
        } else {
            stopTimer();
        }
    }, 1000);
    $timer.attr({
        'timer_interval_id': timer_interval_id,
    })
    let $buttonConfirm = $('#container .form-box .button-wrap .button.confirm');
    $buttonConfirm.removeClass('disabled');
}

/**
 * 타이머 종료 기능
 */
function stopTimer() {
    let $timer = $('.form-box .timer')
    let timer_interval_id = $timer.attr('timer_interval_id')
    $timer.html('00:00')
    // $timer.css({
    //     'display': 'none',
    // });
    clearInterval(timer_interval_id);
    $timer.attr({
        'timer_interval_id': -1,
    })

    let $buttonConfirm = $('#container .form-box .button-wrap .button.confirm');
    $buttonConfirm.addClass('disabled');
}
