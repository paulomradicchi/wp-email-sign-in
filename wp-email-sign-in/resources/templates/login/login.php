<p class="forgetmenot">
    <label for="wm_email_sign_in">
        <input name="wm_email_sign_in" type="checkbox" id="wm_email_sign_in" value="emailme" /> <?php esc_attr_e('Sign-in using email account', 'wp-email-sign-in'); ?>
    </label>
    <input type="hidden" name="action" value="emailsignin">
</p>
<?php wp_nonce_field('ajax-emailsignin-nonce', 'security'); ?>