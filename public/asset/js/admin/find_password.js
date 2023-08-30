
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

    $.ajax({
        type: 'POST',
        data: data,
        dataType: 'json',
        url: `/api/email/send/verification-code/reset-password`,
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
                <div class="info-wrap disappear-at-next-step">
                    * please check your spam mailbox if you don't receive the mail.
                </div>
                `)
            $('#container .form-wrap').append(`
                    <div class="input-wrap disappear-at-next-step">
                        <p class="input-title">Verification Code</p>
                        <input type="text" name="code" class="under-line"/>
                    </div>
                    <div class="timer disappear-at-next-step">
                        03:00
                    </div>
                    <div class="error-message-wrap disappear-at-next-step">
                    </div>
                    <div class="button-wrap controls disappear-at-next-step">
                        <a href="javascript:resendVerificationCode(${isAdmin})" class="button resend white">Resend</a>
                        <a href="javascript:confirmVerificationCode(${isAdmin})" class="button confirm black">Confirm</a>
                    </div>
                `)
            startTimer();
        },
        error: function (response, status, error) {
            showErrors(response, status, error);
        },
    });
}

function clearErrors() {
    let $wrapErrorMessage = $('#container .form-box .error-message-wrap');
    $wrapErrorMessage.empty();
}

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

let timer_interval_id = -1;

function clock(time) {
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

function stopTimer() {
    let $timer = $('.form-box .timer')
    $timer.html('00:00')
    // $timer.css({
    //     'display': 'none',
    // });
    clearInterval(timer_interval_id);
    timer_interval_id = -1;

    let $buttonConfirm = $('#container .form-box .button-wrap .button.confirm');
    $buttonConfirm.addClass('disabled');
}

function startTimer() {
    let $timer = $('.form-box .timer')
    let time = 600;
    $timer.html(clock(time));
    timer_interval_id = setInterval(function () {
        if (time >= 0) {
            $timer.html(clock(time));
            time--;
        } else {
            stopTimer();
        }
    }, 1000);
    let $buttonConfirm = $('#container .form-box .button-wrap .button.confirm');
    $buttonConfirm.removeClass('disabled');
}

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

    $.ajax({
        type: 'POST',
        data: data,
        dataType: 'json',
        url: `/api/email/send/verification-code/reset-password`,
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

function confirmVerificationCode(isAdmin = 0) {
    clearErrors();

    let $inputUsername = $('#container .form-wrap input[name=username]');
    let $inputCode = $('#container .form-wrap input[name=code]');
    let data = {
        'username': $inputUsername.val(),
        'code': $inputCode.val(),
    }

    $.ajax({
        type: 'POST',
        data: data,
        dataType: 'json',
        url: `/api/user/reset-password/verify`,
        success: function (response, status, request) {
            if (!response.success) {
                showErrors(response, status, request);
                return;
            }
            stopTimer();

            $(`#container .form-wrap .disappear-at-next-step`).remove();
            $('#container .form-wrap').append(`
            <div class="input-wrap" style="margin-top: 40px">
                <p class="input-title">New Password</p>
                <input type="password" name="password" class="under-line editable"/>
            </div>
            <div class="input-wrap">
                <p class="input-title">Confirm New Password</p>
                <input type="password" name="confirm_password" class="under-line editable"/>
            </div>
            <div class="error-message-wrap">
            </div>`)
            $('#container .form-box').append(`
            <div class="button-wrap controls">
                <a href="javascript:confirmChangePassword(${isAdmin})" class="button confirm black">Confirm</a>
            </div>`)

        },
        error: function (response, status, error) {
            showErrors(response, status, error);
        },
    });
}

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

    for(let i in validations) {
        let validation = validations[i];
        if(isEmpty(data[validation.key])) {
            $wrapErrorMessage.append(`<p>${validation.name} field is empty.</p>`)
        }
    }
    if (data['password'] != data['confirm_password']) {
        $wrapErrorMessage.append(`<p>please check two fields for password is same.</p>`)
    }

    $.ajax({
        type: 'POST',
        data: data,
        dataType: 'json',
        url: `/api/user/reset-password/confirm`,
        success: function (response, status, request) {
            if (!response.success) {
                showErrors(response, status, request);
                return;
            }
            if(!isAdmin) {
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
