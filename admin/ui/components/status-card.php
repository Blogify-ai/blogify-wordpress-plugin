<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function blogify_status_card($title, $value, $info) {
return <<<END
    <article class="blogify-status-card">
        <span class="blogify-title">$title</span>
        <span class="blogify-value">$value</span>
        <span class="blogify-info">$info</span>
    </article>
    END;
}