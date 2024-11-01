<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Plugin Name: Simple SMTP by Maileroo
 * Plugin URI: https://maileroo.com/
 * Description: Simplify WordPress email delivery with universal SMTP compatibility, including Maileroo, SendGrid, Mailgun, and more - all-in-one SMTP plugin for reliable, user-friendly email integration.
 * Version: 1.0.0
 * Author: Areeb
 * Author URI: https://areeb.com/
 * License: GPL v3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Requires PHP: 7.4
 */

define("SSBM_VERSION", "1.0.0");

function ssbm_menu() {

    add_menu_page(
        'Simple SMTP by Maileroo',
        'Simple SMTP',
        'manage_options',
        'simple-smtp-by-maileroo',
        'ssbm_page',
        plugins_url('/assets/icon.svg', __FILE__)
    );

}

add_action('admin_menu', 'ssbm_menu');

function ssbm_page() {
    include_once(plugin_dir_path(__FILE__) . 'components/home_page.php');
}

function ssbm_settings_init() {

    register_setting('ssbm_settings', 'ssbm_smtp_host', [
        'sanitize_callback' => 'sanitize_text_field',
        'default' => 'smtp.maileroo.com',
    ]);

    register_setting('ssbm_settings', 'ssbm_smtp_port', [
        'sanitize_callback' => 'sanitize_text_field',
        'default' => '587',
    ]);

    register_setting('ssbm_settings', 'ssbm_authentication', [
        'sanitize_callback' => 'sanitize_text_field',
        'default' => 'on',
    ]);

    register_setting('ssbm_settings', 'ssbm_smtp_username', [
        'sanitize_callback' => 'sanitize_text_field',
        'default' => '',
    ]);

    register_setting('ssbm_settings', 'ssbm_smtp_password', [
        'sanitize_callback' => 'sanitize_text_field',
        'default' => '',
    ]);

    register_setting('ssbm_settings', 'ssbm_encryption', [
        'sanitize_callback' => 'sanitize_text_field',
        'default' => 'starttls',
    ]);

    register_setting('ssbm_settings', 'ssbm_from_email', [
        'sanitize_callback' => 'sanitize_email',
        'default' => '',
    ]);

    register_setting('ssbm_settings', 'ssbm_from_name', [
        'sanitize_callback' => 'sanitize_text_field',
        'default' => '',
    ]);

}

add_action('admin_init', 'ssbm_settings_init');
add_action('wp_ajax_send_test_email', 'ssbm_send_test_email');

function ssbm_send_test_email() {

    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ssbm_nonce_test'])), 'ssbm_nonce_test')) {
        echo '<div class="error">Please reload the page and try again.</div>';
        die();
    }

    $recipient_email = sanitize_email($_POST['recipient_email']);
    $subject = 'Test Email from Maileroo Plugin';
    $message = 'This is a test email sent from the Maileroo Plugin.';
    $headers = 'From: ' . get_option('ssbm_from_email') . "\r\n";

    add_action('wp_mail_failed', 'ssbm_capture_wp_mail_errors');

    $success = wp_mail($recipient_email, $subject, $message, $headers);

    remove_action('wp_mail_failed', 'ssbm_capture_wp_mail_errors');

    if ($success) {

        echo '<div class="success">The test email has been sent successfully.</div>';

    } else {

        $error_message = get_transient('ssbm_error_message');

        if (!empty($error_message)) {
            echo '<div class="error">Failed to send test email. Check your server settings and try again. Error: ' . esc_html($error_message) . '</div>';
        } else {
            echo '<div class="error">Failed to send test email. Check your server settings and try again.</div>';
        }

    }

    die();

}

function ssbm_capture_wp_mail_errors($wp_error) {

    $error_message = $wp_error->get_error_message();

    set_transient('ssbm_error_message', $error_message, 60);

}

function ssbm_enqueue_admin_styles() {

    wp_enqueue_style('ssbm_global_styles', plugins_url('/assets/global_styles.css', __FILE__), array(), SSBM_VERSION);
    wp_enqueue_style('ssbm_app_styles', plugin_dir_url(__FILE__) . 'assets/styles.css', array(), SSBM_VERSION);
    wp_enqueue_script('ssbm_scripts', plugin_dir_url(__FILE__) . 'assets/scripts.js', array(), SSBM_VERSION, true);

}

add_action('admin_enqueue_scripts', 'ssbm_enqueue_admin_styles');
add_action('phpmailer_init', 'ssbm_custom_smtp_override');

function ssbm_custom_smtp_override($phpmailer) {

    $ssbm_smtp_host = get_option('ssbm_smtp_host', 'smtp.maileroo.com');
    $ssbm_smtp_port = get_option('ssbm_smtp_port', '587');
    $ssbm_authentication = get_option('ssbm_authentication', 'on');
    $ssbm_smtp_username = get_option('ssbm_smtp_username', '');
    $ssbm_smtp_password = get_option('ssbm_smtp_password', '');
    $ssbm_encryption = get_option('ssbm_encryption', 'starttls');
    $ssbm_from_email = get_option('ssbm_from_email', '');
    $ssbm_from_name = get_option('ssbm_from_name', '');

    $phpmailer->isSMTP();
    $phpmailer->Host = $ssbm_smtp_host;
    $phpmailer->Port = $ssbm_smtp_port;
    $phpmailer->SMTPAuth = $ssbm_authentication === 'on';
    $phpmailer->Username = $ssbm_smtp_username;
    $phpmailer->Password = $ssbm_smtp_password;
    $phpmailer->SMTPSecure = $ssbm_encryption;
    $phpmailer->From = $ssbm_from_email;
    $phpmailer->FromName = $ssbm_from_name;

}

function ssbm_save_form() {
    do_action('ssbm_action_form');
}

add_action('admin_init', 'ssbm_save_form');

function ssbm_form_function_submit() {

    if (isset($_POST['submit'])) {

        if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['ssbm_nonce'])), 'ssbm_nonce')) {
            return;
        }

        setcookie('submit-result', 'success', time() + 1, '/');

    }

}

add_action('ssbm_action_form', 'ssbm_form_function_submit');
