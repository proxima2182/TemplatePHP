function changePassword() {
    $('h3.title').html('Change Password')
    const box = $('.form-box');
    box.empty();
    box.append(`
    <div class="form-wrap password">
        <div class="input-wrap">
            <p class="input-title">Current Password</p>
            <input type="password" name="current_password" class="under-line editable"/>
        </div>
        <div class="input-wrap" style="margin-top: 40px">
            <p class="input-title">New Password</p>
            <input type="password" name="new_password" class="under-line editable"/>
        </div>
        <div class="input-wrap">
            <p class="input-title">Confirm New Password</p>
            <input type="password" name="confirm_new_password" class="under-line editable"/>
        </div>
        <div class="error-message-wrap">
        </div>
    </div>
    <div class="button-wrap controls">
        <a href="javascript:refreshProfile()" class="button cancel white">Cancel</a>
        <a href="javascript:confirmChangePassword()" class="button confirm black">Confirm</a>
    </div>`);
}

function refreshProfile() {
    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: `/api/user/get/profile`,
        success: function (response, textStatus, request) {
            if (!response.success) return;
            let data = response.data;
            $('h3.title').html('Profile')
            const box = $('.form-box');
            box.empty();
            box.prepend(`
            <div class="form-wrap profile">
                <div class="input-wrap">
                    <p class="input-title">Username</p>
                    <input type="text" name="name" class="under-line" readonly value="${data.username}"/>
                </div>
                <div class="input-wrap">
                    <p class="input-title">Email</p>
                    <input type="text" name="name" class="under-line" readonly value="${data.email}"/>
                </div>
                <div class="input-wrap">
                    <p class="input-title">Name</p>
                    <input type="text" name="name" class="under-line editable" readonly value="${data.name}"/>
                </div>
                <div class="error-message-wrap">
                </div>
                <div class="button-wrap">
                    <a href="javascript:changePassword()" class="button change-password white">Change Password</a>
                </div>
                <div class="button-wrap">
                    <a href="javascript:editProfile()" class="button edit-profile black">Edit Profile</a>
                </div>
            </div>`)
        },
        error: function (response, textStatus, error) {
        },
    })
}

function editProfile() {
    $('h3.title').html('Edit Profile')
    $('.form-wrap .editable').removeAttr('readonly')
    $('.form-wrap .button-wrap').remove();
    $('.form-box').append(`
    <div class="button-wrap controls">
        <a href="javascript:refreshProfile()" class="button cancel white">Cancel</a>
        <a href="javascript:confirmEditProfile()" class="button confirm black">Confirm</a>
    </div>`);
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

    $.ajax({
        type: 'POST',
        dataType: 'json',
        data: data,
        url: `/api/user/password-change`,
        success: function (response, textStatus, request) {
            if (!response.success) {
                showErrors(response, textStatus, request);
                return;
            }
            refreshProfile();
        },
        error: function (response, textStatus, error) {
            showErrors(response, textStatus, error);
        },
    })
}

function confirmEditProfile() {
    clearErrors();
    let data = parseInputToData($(`#container .form-wrap .editable`))

    $.ajax({
        type: 'POST',
        data: data,
        dataType: 'json',
        url: `/api/user/update/profile`,
        success: function (response, status, request) {
            if (!response.success) {
                showErrors(response, status, request);
                return;
            }
            refreshProfile();
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
        }
        if (message) {
            $wrapErrorMessage.append(`<div>${message}</div>`);
        }
    }
}
