<div class="container-inner">
    <div class="inner-box">
        <h3 class="page-title">
            Reset Password
        </h3>
        <?php if (isset($username) && isset($code)) { ?>
            <div class="form-box">
                <div class="form-wrap">
                    <div class="input-wrap">
                        <p class="input-title">Username</p>
                        <input type="email" name="username" class="under-line" readonly value="<?= $username ?>"/>
                    </div>
                    <div class="input-wrap disappear-at-next-step">
                        <p class="input-title">Verification Code</p>
                        <input type="text" name="code" class="under-line" readonly value="<?= $code ?>"/>
                    </div>
                    <div class="error-message-wrap disappear-at-next-step">
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                confirmVerificationCode();
            </script>
        <?php } else { ?>
            <div class="form-box">
                <div class="form-wrap">
                    <div class="input-wrap">
                        <p class="input-title">Username</p>
                        <input type="email" name="username" class="under-line"/>
                    </div>
                    <div class="error-message-wrap disappear-at-next-step">
                    </div>
                    <div class="button-wrap controls disappear-at-next-step" style="margin-top: 40px">
                        <a href="javascript:sendVerificationCode()" class="button confirm black">Verify Email</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
