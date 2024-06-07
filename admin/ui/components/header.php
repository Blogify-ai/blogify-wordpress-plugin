<?php

namespace PixelShadow\Blogify;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
# Displays the header bar bar used on every page of the plugin except redirect page

require_once(BLOGIFY_PLUGIN_DIR . 'admin/api/me.php');
$user_details = blogify_get_user_details();
?>

<header>
        <div class="blogify-left">
            <a href='https://blogify.ai' target="_blank">
                <img id="blogify-banner" alt="Blogify" src="<?php echo esc_url(BLOGIFY_IMAGES_URL);?>logos/blogify-banner.png" />
            </a>   
        </div>
        <div class="blogify-right">
            <span id="blogify-credits">
                <img id="blogify-credits-logo" alt="Credits" src="<?php echo esc_url(BLOGIFY_IMAGES_URL);?>icons/blogify-credit-coin.png" />
                <a style="color:white;" href=<?php echo esc_url(get_admin_url() . 'admin.php?page=blogify-subscription'); ?> target="_blank">
                    Credits: <?php echo esc_html( $user_details['currentCredits'] ); ?>
                </a>
            </span>
            <a href='https://blogify.ai/dashboard/settings' target="_blank">
                <img class="blogify-header-icon" alt="Settings" src="<?php echo esc_url(BLOGIFY_IMAGES_URL);?>icons/settings-gear.svg" />
            </a>
            <img class="blogify-header-icon" hidden alt="Notification" src="<?php echo esc_url(BLOGIFY_IMAGES_URL);?>icons/notification-bell.svg" />
            <img id="blogify-user-avatar" alt="User Avatar" src="<?php echo esc_url($user_details['profilePicture'] ?? BLOGIFY_IMAGES_URL . "logos/blogify-logo-black.png") ?>" />
        </div>
</header>