<?php if ($recaptcha_enabled): ?>
    <?php if (!$recaptcha_ajax): ?>
        <?php if (isset($attr['options'])): ?>
            <script type="text/javascript">
            var RecaptchaOptions = <?php echo json_encode($attr['options']) ?>;
            </script>
        <?php endif ?>
        <script type="text/javascript" src="<?php echo $url_challenge ?>"></script>
        <noscript>
            <iframe src="<?php echo $url_noscript ?>" height="300" width="500" frameborder="0"></iframe><br/>
            <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
            <input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
        </noscript>
    <?php else: ?>
        <div id="recaptcha_div"></div>

        <script type="text/javascript" src="<?php echo $url_api ?>"></script>
        <script type="text/javascript">
            $(function () {
                Recaptcha.create('<?php echo $public_key ?>', 'recaptcha_div', <?php echo json_encode($attr['options']) ?>);
            });
        </script>
    <?php endif ?>
<?php endif ?>
