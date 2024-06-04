<?php

namespace PixelShadow\Blogify;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once BLOGIFY_PLUGIN_DIR . 'admin/api/authentication.php';
require_once BLOGIFY_PLUGIN_DIR . 'admin/api/me.php';

$user_details = get_user_details();

?>

<div class="wrap">
    <div class="blogify">
    <?php require_once 'components/header.php';?>
        <main>
            <article class="blogify-status-card">
                <span class="blogify-title" style="color: var(--blogify-primary-color);"><?php echo ucfirst($user_details['subscriptionStatus']) ?> Subscription</span>
                <span class="blogify-value"><?php echo implode(' ', array_map(fn($text) => ucfirst(strtolower($text)), explode('_', $user_details['subscriptionPlan']))) ?></span>
                <span class="blogify-bottom-line">
                    <span class="blogify-left">
                        <span class="blogify-info"></span>
                    </span>
                    <span class="blogify-right">
                        <a href='https://blogify.ai/dashboard/transaction-history' target="_blank">
                            <button class="blogify-primary">Transaction History</button>
                        </a>
                        <a href='https://blogify.ai/dashboard/subscription' target="_blank">
                            <button class="blogify-primary">Upgrade Subscription</button>
                        </a>
                    </span>
                </span>
            </article>
        </main>
    </div>
</div>
