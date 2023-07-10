function changePassword() {
    $('h3.title').html('Change Password')
    $('.info-wrap').empty();
    $('.info-wrap').prepend(`
    <div class="form-box password">
        <div class="input-wrap">
            <p class="input-title">Current Password</p>
            <input type="password" name="current_password" class="under-line"/>
        </div>
        <div class="input-wrap">
            <p class="input-title">New Password</p>
            <input type="password" name="new_password" class="under-line"/>
        </div>
        <div class="input-wrap">
            <p class="input-title">Confirm New Password</p>
            <input type="password" name="confirm_password" class="under-line"/>
        </div>
    </div>
    <div class="error-message-wrap">
        <div class="error-message-box">
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
        url: `/api/profile`,
        success: function (data, textStatus, request) {
            $('h3.title').html('Profile')
            const wrap = $('.info-wrap');
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
            </div>
            <div class="button-wrap">
                <a href="javascript:changePassword()" class="button change-password white">Change Password</a>
            </div>
            <div class="button-wrap">
                <a href="javascript:editProfile()" class="button edit-profile black">Edit Profile</a>
            </div>`)
        },
        error: function (request, textStatus, error) {
        },
        dataType: 'json'
    })
}

function editProfile() {
    $('h3.title').html('Edit Profile')
    $('.form-box input.editable').removeAttr('readonly')
    $('.button-wrap').remove();
    $('.info-wrap').append(`
    <div class="error-message-wrap">
        <div class="error-message-box">
        </div>
    </div>
    <div class="button-wrap controls">
        <a href="javascript:refreshProfile()" class="button cancel white">Cancel</a>
        <a href="javascript:confirmEditProfile()" class="button confirm black">Confirm</a>
    </div>`);
}

function confirmChangePassword() {
    $('.error-message-box').empty();
    let data = {};
    let inputs = $('.form-box input');
    for (let i = 0; i < inputs.length; ++i) {
        let input = inputs.eq(i);
        if (input.length > 0) {
            data[input[0].name] = input.val();
        }
    }

    if(data['current_password'].length == 0 || data['new_password'].length == 0 || data['confirm_password'].length == 0) {
        $('.error-message-box').append(`<p>fields are empty.</p>`)
    }
    if(data['current_password'] == data['new_password']) {
        $('.error-message-box').append(`<p>please enter different password with original.</p>`)
    }
    if(data['new_password'] != data['confirm_password']) {
        $('.error-message-box').append(`<p>please confirm new password is same.</p>`)
    }
}

function confirmEditProfile() {

}
