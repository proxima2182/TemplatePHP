<?php
if (!isset($username) || !isset($name) || !isset($email)) return;
?>
<div class="container-inner">
    <div class="inner-box">
        <h3 class="title">
            Profile
        </h3>
        <div class="info-wrap">
            <div class="form-box">
                <div class="input-wrap">
                    <p class="input-title">Username</p>
                    <input type="text" name="name" value="<?= $username ?>" readonly/>
                </div>
                <div class="input-wrap">
                    <p class="input-title">Name</p>
                    <input type="text" name="name" value="<?= $name ?>" readonly/>
                </div>
                <div class="input-wrap">
                    <p class="input-title">Email</p>
                    <input type="text" name="name" value="<?= $email ?>" readonly/>
                </div>
            </div>
            <div class="button-wrap">
                <a href="javascript:editProfile()" class="button edit-profile">Edit Profile</a>
            </div>
            <div class="button-wrap">
                <a href="javascript:changePassword()" class="button change-password">Change Password</a>
            </div>
        </div>
    </div>
</div>
