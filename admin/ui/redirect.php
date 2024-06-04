<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
require_once BLOGIFY_PLUGIN_DIR . 'admin/api/authentication.php';

$auth_code = $_GET['code'];
$state = $_GET['state'];

if ($auth_code && $state) {
    $tokens = get_oauth2_tokens_from_auth_code($auth_code);
    save_oauth2_tokens($tokens['access_token'], $tokens['refresh_token'], $tokens['expires_in']);
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
                <img id="blogify-banner" src="<?php echo BLOGIFY_IMAGES_URL; ?>logos/blogify-banner.png" alt="Blogify Logo">
            </a>
            <img id="blogify-two-way-arrow" src="<?php echo BLOGIFY_IMAGES_URL; ?>icons/arrow-goes-left-right.svg" alt="Arrow Goes Left Right Icon">
            <a href="https://wordpress.org" target="_blank">
                <img id="blogify-wordpress-banner" src="<?php echo BLOGIFY_IMAGES_URL; ?>logos/wordpress-black-banner.png" alt="WordPress Logo">
            </a>
        </section>
        <article class="blogify-redirect">
            <span class="blogify-instructions">
                <h1><?php echo $heading; ?></h1>
                <span class="blogify-description">
                    <h3><?php echo $instruction; ?></h3>
                    <h3><br>
                        Should the automatic process not initiate, kindly click the <a
                            href="<?php echo $redirectURL; ?>">link</a> here to proceed.
                    </h3>
                </span>
            </span>
        </article>
    </main>
</div>
<script>
    setTimeout(
        () => window.location.assign("<?php echo $redirectURL; ?>"),
        5000
    );
</script>
