<?php
/*
Plugin Name: STFQ Login Logo
Description: Replaces the WordPress logo on the login page with an image from the media library and allows changing the URL the logo links to.
Plugin URI: https://strangefrequency.com/wp-plugins/stfq-login-logo/
Version: 1.2
Author: Strangefrequency LLC
Author URI: https://strangefrequency.com/
License: GPL-2.0
License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

// Function to replace the WordPress login logo
function stfq_custom_login_logo() {
    $login_logo_id = get_option('stfq_login_logo_id');
    $login_logo_url = wp_get_attachment_url($login_logo_id);
    if ($login_logo_url) {
        echo '<style type="text/css">
            #login h1 a, .login h1 a {
                background-image: url(' . esc_url($login_logo_url) . ');
                padding-bottom: 30px;
                background-size: contain;
                width: auto;
                height: 100px;
            }
        </style>';
    }
}

add_action('login_enqueue_scripts', 'stfq_custom_login_logo');

// Function to replace default WordPress.org link with custom URL
function stfq_custom_logo_url($url) {
    $custom_logo_url = get_option('stfq_custom_logo_url');
    if ($custom_logo_url) {
        return esc_url($custom_logo_url);
    }
    return $url;
}

add_filter('login_headerurl', 'stfq_custom_logo_url');

// Add settings to customize login logo and logo URL
function stfq_login_logo_settings_init() {
    register_setting('general', 'stfq_login_logo_id', 'absint');
    register_setting('general', 'stfq_custom_logo_url', 'esc_url');
    add_settings_field(
        'stfq_login_logo_field',
        'Custom Login Logo',
        'stfq_login_logo_field_callback',
        'general'
    );
    add_settings_field(
        'stfq_custom_logo_url_field',
        'Custom Logo URL',
        'stfq_custom_logo_url_field_callback',
        'general'
    );
}

add_action('admin_init', 'stfq_login_logo_settings_init');

// Callback function for login logo settings field
function stfq_login_logo_field_callback() {
    $login_logo_id = get_option('stfq_login_logo_id');
    ?>
    <input type="hidden" name="stfq_login_logo_id" id="stfq_login_logo_id" value="<?php echo esc_attr($login_logo_id); ?>" />
    <input type="button" id="upload_stfq_login_logo_button" class="button" value="Upload/Login Logo" />
    <div id="stfq_login_logo_preview">
        <?php if ($login_logo_id) : ?>
            <?php echo wp_get_attachment_image($login_logo_id, 'medium'); ?>
        <?php endif; ?>
    </div>
    <?php
}

// Callback function for custom logo URL settings field
function stfq_custom_logo_url_field_callback() {
    $custom_logo_url = get_option('stfq_custom_logo_url');
    $default_custom_logo_url = get_option('default_stfq_custom_logo_url');
    ?>
    <input type="url" name="stfq_custom_logo_url" id="stfq_custom_logo_url" value="<?php echo esc_url($custom_logo_url); ?>" class="regular-text" />
    <input type="button" class="button stfq-custom-logo-url-clear-button" value="Clear" data-default="<?php echo esc_attr($default_custom_logo_url); ?>" />
    <p class="description">Enter the URL to which the login logo should link.</p>
    <?php
}

// Enqueue media uploader scripts
function stfq_enqueue_media_uploader() {
    wp_enqueue_media();
}

add_action('admin_enqueue_scripts', 'stfq_enqueue_media_uploader');

// Enqueue script for handling logo upload
function stfq_enqueue_logo_upload_script() {
    wp_enqueue_script('stfq-logo-upload', plugin_dir_url(__FILE__) . 'js/logo-upload.js', array('jquery'), null, true);
}

add_action('admin_enqueue_scripts', 'stfq_enqueue_logo_upload_script');

// Enqueue script for handling logo URL clear button
function stfq_enqueue_custom_logo_url_clear_script() {
    wp_enqueue_script('stfq-custom-logo-url-clear', plugin_dir_url(__FILE__) . 'js/custom-logo-url-clear.js', array('jquery'), null, true);
}

add_action('admin_enqueue_scripts', 'stfq_enqueue_custom_logo_url_clear_script');
