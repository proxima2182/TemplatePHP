<?php
if (!isset($username) || !isset($name) || !isset($email)) return;
?>
<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            Profile
        </h3>
        <div class="form-wrap">
            <div class="form-box profile">
                <div class="input-wrap lines">
                    <p class="input-title">Username</p>
                    <input type="text" name="username" class="under-line" readonly value="<?= $username ?>"/>
                </div>
                <div class="input-wrap lines">
                    <p class="input-title">Email</p>
                    <input type="text" name="email" class="under-line" readonly value="<?= $email ?>"/>
                </div>
                <div class="input-wrap lines">
                    <p class="input-title">Name</p>
                    <input type="text" name="name" class="under-line editable" readonly value="<?= $name ?>"/>
                </div>
<!--                //todo notification setting, sync with profile.js-->
<!--                <div class="input-wrap lines">-->
<!--                    <p class="input-title">Notification</p>-->
<!--                    <input type="checkbox" name="is_notification" class="editable" readonly --><?php //= $is_notification == 1 ? 'checked' : '' ?><!--/>-->
<!--                </div>-->
                <div class="error-message-wrap">
                </div>
                <div class="button-wrap">
                    <a href="javascript:changePassword()" class="button change-password white">Change Password</a>
                </div>
                <div class="button-wrap">
                    <a href="javascript:editProfile()" class="button edit-profile black">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>
