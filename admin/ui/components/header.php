<?php

namespace PixelShadow\Blogify;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

require_once(BLOGIFY_PLUGIN_DIR . 'admin/api/me.php');
$user_details = blogify_get_user_details();
?>

<header>
        <div class="blogify-left">
            <a href="<?= esc_url(BLOGIFY_CLIENT_BASEURL) ?>" target="_blank">
                <img id="blogify-banner" alt="Blogify" src="<?= esc_url(BLOGIFY_IMAGES_URL . 'logos/blogify-banner.png') ?>" />
            </a>   
        </div>
        <div class="blogify-right">
            <span id="blogify-credits">
                <img id="blogify-credits-logo" alt="Credits" src="<?= esc_url(BLOGIFY_IMAGES_URL . 'icons/blogify-credit-coin.png') ?>" />
                <a style="color:white;" href="<?= esc_url(get_admin_url() . 'admin.php?page=blogify-subscription') ?>" target="_blank">
                    <?= esc_html( 'Credits: ' . $user_details['currentCredits'] ) ?>
                </a>
            </span>
            <a href="<?= esc_url(get_admin_url() . 'options-general.php?page=blogify') ?>" target="_blank">
                <img class="blogify-header-icon" alt="Settings" src="<?= esc_url(BLOGIFY_IMAGES_URL) . 'icons/settings-gear.svg' ?>" />
            </a>
            <img id="blogify-user-avatar" alt="User Avatar" src="<?= esc_url($user_details['profilePicture'] ?? BLOGIFY_IMAGES_URL . 'logos/blogify-logo-black.png') ?>" />
        </div>
</header>