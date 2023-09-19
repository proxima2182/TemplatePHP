<div class="container-inner">
    <div class="container-wrap">
        <h3 class="page-title">
            Registration
        </h3>
        <?php if (isset($email) && isset($code)) { ?>
            <div class="form-box">
                <div class="form-wrap">
                    <div class="input-wrap">
                        <p class="input-title">Email</p>
                        <input type="email" name="email" class="under-line" readonly value="<?= $email ?>"/>
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
                        <p class="input-title">Email</p>
                        <input type="email" name="email" class="under-line"/>
                    </div>
                    <div class="error-message-wrap disappear-at-next-step">
                    </div>
                    <div class="button-wrap controls disappear-at-next-step" style="margin-top: 40px">
                        <a href="javascript:sendVerificationCode(1)" class="button confirm black">Verify Email</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
