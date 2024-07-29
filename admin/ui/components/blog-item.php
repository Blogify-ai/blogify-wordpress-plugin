<?php

namespace PixelShadow\Blogify;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function blogify_blog_item(string $id, string $title, ?string $cover_image, string $publish_status, ?int $word_count): void { ?>
        <section class="blogify-item">
            <span class="blogify-left">
                <img class="blogify-blog-cover" alt="Blog Cover"
                    src="<?= esc_url($cover_image?: BLOGIFY_IMAGES_URL . 'logos/blogify-logo-black.png') ?>"
                />
                <span class="blogify-blog-info">
                    <span class="blogify-blog-title">
                        <?= esc_html($title ?: 'Failed Blog') ?>
                    </span>
                    <span class="blogify-blog-stats">
                        <?= ucfirst($publish_status)?>
                        <?= $word_count === null ? "" : " ·  $word_count words" ?>
                    </span>
                </span>
            </span>
            <span class="blogify-right">
                <a href="<?= esc_url(BLOGIFY_CLIENT_BASEURL . "dashboard/blogs/$id") ?>" target="_blank" >
                    <button type="button" class="blogify-secondary">View</button>
                </a>
                <a href="<?= esc_url(BLOGIFY_CLIENT_BASEURL . "dashboard/blogs/$id/edit") ?>" target="_blank" >
                    <button type="button" class="blogify-secondary">Edit</button>
                </a>
            </span>
        </section>
<?php }