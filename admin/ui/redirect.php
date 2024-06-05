<?php

namespace PixelShadow\Blogify;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
require_once BLOGIFY_PLUGIN_DIR . 'admin/api/authentication.php';

if (wp_verify_nonce($_GET['state'], 'blogify-oauth2-nonce') && $_GET['code']) {
    $auth_code = $_GET['code'];
    $tokens = get_oauth2_tokens_from_auth_code($auth_code);
    save_oauth2_tokens($tokens['access_token'], $tokens['refresh_token'], $tokens['expires_in']);
    register_publish_route_with_blogify();
}
if (get_option('blogify_oauth2_tokens', null)) {
    $redirectURL = get_admin_url() . 'admin.php?page=blogify-ai';
    $heading = 'Connection to your Blogify account was successful!';
    $instruction = 'You will be redirected to the plugin\'s dashboard page shortly.';
} else {
    $redirectURL = get_oauth2_consent_url();
    $heading = "Seamless Integration with " . ucfirst(wp_get_current_user()->display_name) . "'s WordPress Site";
    $instruction = 'To establish a connection, a prompt may appear requesting authorization.';
}

?>
<div class="wrap">
    <main>
        <section class="blogify-logos">
            <a href="https://blogify.ai" target="_blank">
                <img id="blogify-banner" src="<?php echo esc_url(BLOGIFY_IMAGES_URL); ?>logos/blogify-banner.png" alt="Blogify Logo">
            </a>
            <img id="blogify-two-way-arrow" src="<?php echo esc_url(BLOGIFY_IMAGES_URL); ?>icons/arrow-goes-left-right.svg" alt="Arrow Goes Left Right Icon">
            <a href="https://wordpress.org" target="_blank">
                <img id="blogify-wordpress-banner" src="<?php echo esc_url(BLOGIFY_IMAGES_URL); ?>logos/wordpress-black-banner.png" alt="WordPress Logo">
            </a>
        </section>
        <article class="blogify-redirect">
            <span class="blogify-instructions">
                <h1><?php echo esc_html($heading); ?></h1>
                <span class="blogify-description">
                    <h3><?php echo esc_html($instruction); ?></h3>
                    <h3><br>
                        Should the automatic process not initiate, kindly click the <a
                            href="<?php echo esc_html($redirectURL); ?>">link</a> here to proceed.
                    </h3>
                </span>
            </span>
        </article>
    </main>
</div>
<script>
    setTimeout(
        () => window.location.assign("<?php echo esc_url_raw($redirectURL); ?>"),
        5000
    );
</script>
