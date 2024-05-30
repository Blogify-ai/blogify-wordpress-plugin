<?php
    $image_base = plugin_dir_url(__FILE__) . '../../assets';
?>
<header>
        <div class="left">
            <img id="blogify-banner" alt="Blogify" src="<?php echo $image_base; ?>/images/logos/blogify-banner.png" />
        </div>
        <div class="right">
            <span id="credits">
                <img id="credits-logo" alt="Credits" src="<?php echo $image_base; ?>/images/logos/blogify-logo-white.png" />
                <span>
                    Credits: 800
                </span>
            </span>
            <img class="icon" alt="Settings" src="<?php echo $image_base; ?>/images/icons/settings-gear-icon.svg" />
            <img class="icon" hidden alt="Notification" src="<?php echo $image_base; ?>/images/icons/notification-icon.svg" />
            <img id="user-avatar" alt="User Avatar" src="<?php echo $image_base; ?>/images/logos/blogify-banner-light.svg" />
        </div>
</header>