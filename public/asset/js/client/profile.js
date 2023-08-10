function changePassword() {
    $('h3.title').html('Change Password')
    $('.form-wrap').empty();
    $('.form-wrap').prepend(`
    <div class="form-box password">
        <div class="input-wrap">
            <p class="input-title">Current Password</p>
            <input type="password" name="current_password" class="under-line"/>
        </div>
        <div class="input-wrap" style="margin-top: 40px">
            <p class="input-title">New Password</p>
            <input type="password" name="new_password" class="under-line"/>
        </div>
        <div class="input-wrap">
            <p class="input-title">Confirm New Password</p>
            <input type="password" name="confirm_new_password" class="under-line"/>
        </div>
        <div class="error-message-wrap">
        </div>
    </div>
    <div class="button-wrap controls">
        <a href="javascript:refreshProfile()" class="button cancel white">Cancel</a>
        <a href="javascript:confirmChangePassword()" class="button confirm black">Confirm</a>
    </div>`);
    $('.inner-box').append(``)
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
            const wrap = $('.form-wrap');
            wrap.empty();

            wrap.prepend(`
            <div class="form-box profile">
                <div class="input-wrap">
                    <p class="input-title">Username</p>
                    <input type="text" name="name" class="under-line" readonly value="${data.username}"/>
                </div>
                <div class="input-wrap">
                    <p class="input-title">Name</p>
                    <input type="text" name="name" class="under-line editable" readonly value="${data.name}"/>
                </div>
                <div class="input-wrap">
                    <p class="input-title">Email</p>
                    <input type="text" name="name" class="under-line editable" readonly value="${data.email}"/>
                </div>
                <div class="input-wrap">
                    <p class="input-title">Notification</p>
                    <input type="checkbox" name="notification" class="editable" readonly ${data.notification == 1 ? 'checked' : ''}/>
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
    $('.form-wrap .form-box').append(`
    <div class="error-message-wrap">
    </div>`);
    $('.form-wrap').append(`
    <div class="button-wrap controls">
        <a href="javascript:refreshProfile()" class="button cancel white">Cancel</a>
        <a href="javascript:confirmEditProfile()" class="button confirm black">Confirm</a>
    </div>`);
}

function confirmChangePassword() {
    let data = parseInputToData($(`.form-wrap .editable`))
    let $wrapErrorMessage = $('.form-wrap .error-message-wrap');
    $wrapErrorMessage.empty();

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
}

function confirmEditProfile() {

}
