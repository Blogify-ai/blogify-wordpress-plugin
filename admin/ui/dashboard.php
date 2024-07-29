<?php

namespace PixelShadow\Blogify;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once BLOGIFY_PLUGIN_DIR . 'admin/api/blog.php';
require_once BLOGIFY_PLUGIN_DIR . 'admin/api/authentication.php';

$counts = get_publish_status_count();
$blogs = get_blogs(1, 5);
$totalBlogCount = $blogs['pagination']['totalResults'];


?>

<div class="wrap">
<div class="blogify">
    <?php require_once 'components/header.php';?>
    <main>
        <article class="blogify-status-bar">
            <?php foreach($counts as $publish_status => $count): ?>
                <article class='blogify-status-card'>
                    <span class='blogify-title'> <?= esc_html(ucfirst($publish_status)) ?> </span>
                    <span class='blogify-value'> <?= esc_html($count) ?> </span>
                    <span class='blogify-info'> <span>
                </article>
            <?php endforeach; ?>
        </article>
            <article class="blogify-blog-list">
                <section class="blogify-header">
                    <span class ="blogify-left">
                        <span class="blogify-title">My Blogs</span>
                    </span>
                    <span class="blogify-right">
                        <a href="<?= esc_url(get_admin_url() . 'admin.php?page=blogify-all-blogs') ?>">
                            <button type="button" class="blogify-primary">View All</button>
                        </a>
                        <a href= "<?= esc_url(BLOGIFY_CLIENT_BASEURL . 'dashboard/blogs/select-source') ?>" target="_blank">
                            <button type="button" class="blogify-primary">Create</button>
                        </a>
                    </span>
                </section>
                <section class="blogify-items">
                    <?php require_once 'components/blog-item.php';
                        if ($blogs['pagination']['totalResults']):
                            foreach ($blogs['data'] as $blog):
                                blogify_blog_item($blog['_id'], $blog['title'], $blog['image'], $blog['publishStatus'], $blog['wordCount'] ?? null);
                            endforeach;
                        else: ?>
                        <p style="text-align: center; width: 100%;">
                            No Blogs Found
                        </p>
                    <?php endif;?>
                </section>
            </article>
        </main>
    </div>
</div>