<?php
// بسم الله الرحمن الرحيم

namespace PixelShadow\Blogify;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


require_once BLOGIFY_PLUGIN_DIR . 'admin/api/authentication.php';

add_action(
    'admin_menu', fn() => add_options_page(
        'Blogify Settings',
        'Blogify-AI 📝',
        'manage_options',
        'blogify',
        function () {
            ?>
                    <div class="wrap">
                        <form action='options.php' method='post'>
                <?php
            settings_fields('blogify');
            do_settings_sections('blogify');
            submit_button();
            ?>
                        </form>
                    </div>

                <?php
},
    )
);

add_action('admin_init', function () {
    register_setting('blogify', 'blogify_access_token', [
        'type' => 'string',
        'sanitize_callback' => function ($value) {
            $message = "Connected to Blogify.ai successfully ✅" . "<br />". "<a href='" 
            . esc_url(get_admin_url(null, 'admin.php?page=blogify-ai'))
            ."'>Head over to Blogify-AI Dashboard</a>";

            if(validate_token($value)) {
                 register_publish_route_with_blogify($value);
                
                add_settings_error(
                    'blogify_access_token',
                    'token-success',
                    $message,
                    'success'
                );
            } else {
                add_settings_error(
                    'blogify_access_token',
                    'token-failure',
                    "Invalid Token ❌, Please try again.",
                    'error'
                );
            }
            return sanitize_text_field($value);
        },
        'show_in_rest' => false,
    ]);

    // Add settings section
    add_settings_section(
        'blogify_section',
        'Credentials',
        function () {
            $tutorial_link = BLOGIFY_CLIENT_BASEURL . 'dashboard/settings/wordpressorg-connect';
            ?>

                <p>Configure your Blogify.ai credentials.<p>
                <a target="_blank" href="<?php echo esc_url($tutorial_link); ?>">How to obtain an Access Token</a>
            <?php

            },
        'blogify'
    );

    // Add settings fields
    add_settings_field(
        'blogify_access_token',
        'Blogify Access Token',
        function () {
            ?>
                <input style='width: 80%;' type='password' id='blogify_access_token' name='blogify_access_token' value='<?php echo esc_attr(get_option('blogify_access_token')); ?>'>
            <?php
        },
        'blogify',
        'blogify_section',
        [
            'label_for' => 'blogify_access_token',
        ]
    );

});