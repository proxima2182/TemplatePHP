</div>

<footer id="footer">
    <div class="footer-inner">
        <a href="/" class="logo"><img src="/asset/images/include/logo_footer.png" alt="footer logo"></a>
        <div class="text-wrap">
            <ul class="cf">
                <?php if(isset($settings['footer-text'])) {
                    $texts = preg_split("/\r\n|\n|\r/", $settings['footer-text']);
                    foreach ($texts as $text) {?>
                        <li><p><?=$text?></p></li>
                    <?php }
                }?>
            </ul>
        </div>
        <div class="terms">
            copyright 2023. <a href="https://github.com/proxima2182" target="_blank">proxima2182</a> all rights reserved.
        </div>
    </div>
</footer>
</div>

</body>
</html>
