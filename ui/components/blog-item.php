<?php

namespace PixelShadow\Blogify\UI;

require_once 'dialog.php';

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function blogify_blog_item(int $index, string $id, string $title, ?string $cover_image, string $nonce, string $page, int $page_number): void
{ ?>
    <section class="blogify-item">
        <span class="blogify-left">
            <img class="blogify-blog-cover" alt="Blog Cover"
                src="<?php echo esc_url($cover_image ?: BLOGIFY_IMAGES_URL . 'logos/blogify-logo-black.png') ?>" />
            <span class="blogify-blog-info">
                <span class="blogify-blog-title">
                    <?php echo esc_html($title ?: 'Failed Blog') ?>
                </span>
                <span class="blogify-blog-stats">
                </span>
            </span>
        </span>
        <span class="blogify-right">
            <a href="<?php echo esc_url(BLOGIFY_CLIENT_BASEURL . "dashboard/blogs/$id") ?>" target="_blank">
                <button type="button" class="blogify-secondary">View</button>
            </a>
            <a href="<?php echo esc_url(BLOGIFY_CLIENT_BASEURL . "dashboard/blogs/$id/edit") ?>" target="_blank">
                <button hidden type="button" class="blogify-secondary">Edit</button>
            </a>
            <button type="button" class="blogify-secondary"
                onclick="<?php echo esc_js('publish' . $id . '.showModal()'); ?>"
                style="background-color: var(--blogify-primary-color); color: white">Publish</button>
        </span>
        <?php blogify_publish_dialog('publish' . $id, $id, $nonce, $page, $page_number); ?>
    </section>
<?php }