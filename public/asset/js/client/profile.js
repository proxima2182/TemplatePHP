
function editProfile() {
    $('.profile-container .page-title').html('Edit Profile')
    $('.form-wrap .editable').removeAttr('readonly')
    $('.form-wrap .button-wrap').remove();
    $('.form-box').append(`
    <div class="button-wrap controls">
        <a href="javascript:cancel()" class="button cancel white">Cancel</a>
        <a href="javascript:confirmEditProfile()" class="button confirm black">Confirm</a>
    </div>`);
}

function cancel() {
    history.back()
}

function confirmChangePassword() {
    clearErrors();

    let $wrapErrorMessage = $('#container .form-box .error-message-wrap');
    let data = parseInputToData($(`#container .form-wrap .editable`))

    let validations = [
        {
            key: 'current_password',
            name: 'Current Password'
        },
        {
            key: 'new_password',
            name: 'New Password'
        },
        {
            key: 'confirm_new_password',
            name: 'Confirm New Password'
        },
    ];

    for (let i in validations) {
        let validation = validations[i];
        if (isEmpty(data[validation.key])) {
            $wrapErrorMessage.append(`<p>${validation.name} field is empty.</p>`)
        }
    }

    if (data['current_password'] == data['new_password']) {
        $wrapErrorMessage.append(`<p>please enter different password with original.</p>`)
    }
    if (data['new_password'] != data['confirm_new_password']) {
        $wrapErrorMessage.append(`<p>please check two fields for password is same.</p>`)
    }

    apiRequest({
        type: 'POST',
        url: `/api/user/password-change`,
        dataType: 'json',
        data: data,
        success: function (response, textStatus, request) {
            if (!response.success) {
                showErrors(response, textStatus, request);
                return;
            }
            history.back();
        },
        error: function (response, textStatus, error) {
            showErrors(response, textStatus, error);
        },
    })
}

function confirmEditProfile() {
    clearErrors();
    let data = parseInputToData($(`#container .form-wrap .editable`))

    apiRequest({
        type: 'POST',
        url: `/api/user/update/profile`,
        data: data,
        dataType: 'json',
        success: function (response, status, request) {
            if (!response.success) {
                showErrors(response, status, request);
                return;
            }
            history.back();
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
