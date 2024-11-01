<?php
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<div class="wrap maileroo">

    <h1>
        <b>
            Simple SMTP by Maileroo
        </b>
    </h1>

    <form method="post" action="options.php">

        <?php
        settings_fields('ssbm_settings');
        do_settings_sections('ssbm-settings');
        ?>

        <table class="form-table">

            <?php
            wp_nonce_field('ssbm_settings', 'ssbm_nonce');
            ?>

            <tr>
                <th scope="row"><label for="ssbm_smtp_host">SMTP Host</label></th>
                <td><input type="text" name="ssbm_smtp_host" id="ssbm_smtp_host" value="<?php echo esc_attr(get_option('ssbm_smtp_host', 'smtp.maileroo.com')); ?>" class="regular-text" required/></td>
            </tr>

            <tr>
                <th scope="row"><label for="ssbm_smtp_port">SMTP Port</label></th>
                <td><input type="text" name="ssbm_smtp_port" id="ssbm_smtp_port" value="<?php echo esc_attr(get_option('ssbm_smtp_port', '587')); ?>" class="small-text" required/></td>
            </tr>

            <tr>
                <td colspan="3">
                    <hr>
                </td>
            </tr>

            <tr>
                <th scope="row">Authentication</th>
                <td>
                    <div class="custom-switch" id="ssbm_authentication-switch">
                        <input type="checkbox" name="ssbm_authentication" id="ssbm_authentication_checkbox" <?php checked('on', get_option('ssbm_authentication')); ?> />
                        <label for="ssbm_authentication_checkbox"></label>
                    </div>
                </td>
            </tr>

            <tr class="auth-input">
                <th scope="row"><label for="ssbm_smtp_username">Username</label></th>
                <td><input type="text" name="ssbm_smtp_username" id="ssbm_smtp_username" value="<?php echo esc_attr(get_option('ssbm_smtp_username')); ?>" class="regular-text" <?php echo (get_option('ssbm_authentication') == 'on') ? '' : 'disabled'; ?> required/></td>
            </tr>

            <tr class="auth-input">
                <th scope="row"><label for="ssbm_smtp_password">Password</label></th>
                <td><input type="password" name="ssbm_smtp_password" id="ssbm_smtp_password" value="<?php echo esc_attr(get_option('ssbm_smtp_password')); ?>" class="regular-text" <?php echo (get_option('ssbm_authentication') == 'on') ? '' : 'disabled'; ?> required/></td>
            </tr>

            <tr>
                <td colspan="3">
                    <hr>
                </td>
            </tr>

            <tr>
                <th scope="row">Encryption</th>
                <td>
                    <fieldset required>
                        <legend class="screen-reader-text"><span>ssbm_encryption</span></legend>
                        <label for="ssbm_encryption_plaintext"><input type="radio" name="ssbm_encryption" id="ssbm_encryption_plaintext" value="plaintext" <?php checked('plaintext', get_option('ssbm_encryption')); ?> /> Plaintext</label><br>
                        <label for="ssbm_encryption_starttls"><input type="radio" name="ssbm_encryption" id="ssbm_encryption_starttls" value="starttls" <?php checked('starttls', get_option('ssbm_encryption')); ?> /> STARTTLS</label><br>
                        <label for="ssbm_encryption_ssl_tls"><input type="radio" name="ssbm_encryption" id="ssbm_encryption_ssl_tls" value="ssl_tls" <?php checked('ssl_tls', get_option('ssbm_encryption')); ?> /> SSL/TLS</label><br>
                    </fieldset>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <hr>
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="ssbm_from_email">From Email</label></th>
                <td><input type="text" name="ssbm_from_email" id="ssbm_from_email" value="<?php echo esc_attr(get_option('ssbm_from_email')); ?>" class="regular-text" required/></td>
            </tr>

            <tr>
                <th scope="row"><label for="ssbm_from_name">From Name</label></th>
                <td><input type="text" name="ssbm_from_name" id="ssbm_from_name" value="<?php echo esc_attr(get_option('ssbm_from_name')); ?>" class="regular-text" required/></td>
            </tr>

        </table>

        <p class="submit">

            <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">

            <?php if (get_option('ssbm_smtp_host') && get_option('ssbm_smtp_port') && get_option('ssbm_from_email')) : ?>
                <button type="button" id="test-button" class="button button-outline">Test Connection</button>
            <?php endif; ?>

        </p>

    </form>


    <div class="maileroo-modal">
        <div id="test-modal" class="modal">
            <div class="modal-content">
                <div>
                    <span class="close">&times;</span>
                    <h2>Test Email Connection</h2>
                </div>
                <div>
                    <div class="modal-body">
                        <p>Please enter the recipient email address:</p>
                        <input type="hidden" id="ssbm_nonce_test" value="<?php echo esc_html(wp_create_nonce('ssbm_nonce_test')); ?>">
                        <input type="email" id="recipient_email" name="recipient_email" class="regular-text" required/>
                        <button id="send-test-email" class="button">Send Email</button>
                    </div>
                    <div class="loading-container display-none">
                        <div class="loading"></div>
                    </div>
                    <div id="test-result"></div>
                </div>
            </div>
        </div>

    </div>

    <h4>
        Experience the pinnacle of email delivery with Maileroo! Our platform offers blazing-fast transactional email delivery, advanced tracking of delivery rates, opens, clicks, and bounces, and a straightforward setup process. Whether you prefer traditional SMTP or our efficient HTTP Email API, Maileroo is designed to ensure your emails always reach the inbox. Trusted by thousands worldwide for its scalability and reliability, Maileroo is your go-to solution for transactional email needs.
        <br/>
        <br/>
        Don't wait - Click <a href="https://maileroo.com/" target="_blank">here</a> to sign up at Maileroo and transform your email delivery today.
    </h4>

    <div class="promo-message">
        <p class="promo-p"><span>Made with ❤️ by </span><a href="https://maileroo.com" target="_blank"><img class="maileroo-logo" src="<?php echo esc_html(plugin_dir_url(__FILE__)) . '../assets/logo.svg'; ?>" alt="Maileroo"></a></p>
    </div>

    <div id="toast-container">
        <div id="toast" class="success">
        </div>
    </div>

</div>