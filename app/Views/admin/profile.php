<?php
if (!isset($username) || !isset($name) || !isset($email)) return;
if (!isset($sub)) $sub = 'view'
?>
<div class="container-inner profile-container">
    <div class="container-wrap">
        <?php if ($sub == 'view' || $sub == 'edit') { ?>
            <h3 class="page-title">
                <?= $sub == 'edit' ? 'Edit Profile' : 'Profile' ?>
            </h3>
            <div class="form-box">
                <div class="form-wrap profile">
                    <div class="input-wrap">
                        <p class="input-title">Username</p>
                        <input type="text" name="username" class="under-line" readonly value="<?= $username ?>"/>
                    </div>
                    <div class="input-wrap">
                        <p class="input-title">Email</p>
                        <input type="text" name="email"
                               class="under-line<?= $user_type == 'admin' ? ' editable' : '' ?>" readonly
                               value="<?= $email ?>"/>
                    </div>
                    <div class="input-wrap">
                        <p class="input-title">Name</p>
                        <input type="text" name="name" class="under-line editable" readonly value="<?= $name ?>"/>
                    </div>
                    <div class="input-wrap">
                        <p class="input-title">Notification</p>
                        <input type="checkbox" name="is_notification" class="editable"
                               disabled <?= $is_notification == 1 ? 'checked' : '' ?>/>
                    </div>
                    <div class="error-message-wrap">
                    </div>
                    <div class="button-wrap" style="margin-top: 40px">
                        <a href="<?= $is_admin_page ? '/admin/profile?sub=password' : '/profile?sub=password' ?>"
                           class="button change-password white">Change Password</a>
                    </div>
                    <div class="button-wrap">
                        <a href="<?= $is_admin_page ? '/admin/profile?sub=edit' : '/profile?sub=edit' ?>"
                           class="button edit-profile black">Edit Profile</a>
                    </div>
                </div>
            </div>
        <?php if ($sub == 'edit') { ?>
            <script type="text/javascript">
                editProfile();
            </script>
        <?php }
        } else { ?>
            <h3 class="page-title">
                Change Password
            </h3>
            <div class="form-box">
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
                    <a href="javascript:cancel()" class="button cancel white">Cancel</a>
                    <a href="javascript:confirmChangePassword()" class="button confirm black">Confirm</a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
