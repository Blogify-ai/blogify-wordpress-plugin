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
$draftBlogCount = $counts['draft'];
$publishedBlogCount = $counts['published'];
$scheduledBlogCount = $counts['scheduled'];

?>

<div class="wrap">
<div class="blogify">
    <?php require_once 'components/header.php';?>
    <main>
        <article class="blogify-status-bar">
        <?php
            require_once 'components/status-card.php';
            echo wp_kses(implode("\n",
                [
                    blogify_status_card('Draft', $draftBlogCount, "") ,
                    blogify_status_card('Scheduled', $scheduledBlogCount, ""),
                    blogify_status_card('Published', $publishedBlogCount, ""),
                    blogify_status_card('All', $totalBlogCount, ""),
                    blogify_status_card('Earnings', "$0.00", ""),
                ]
            ), 'post');
        ?>
        </article>
            <article class="blogify-blog-list">
                <section class="blogify-header">
                    <span class ="blogify-left">
                        <span class="blogify-title">My Blogs</span>
                    </span>
                    <span class="blogify-right">
                        <a href='<?php echo esc_url(get_admin_url() . 'admin.php?page=blogify-all-blogs'); ?>'>
                            <button type="button" class="blogify-primary">View All</button>
                        </a>
                        <a href= 'https://blogify.ai/dashboard/blogs/select-source' target="_blank">
                            <button type="button" class="blogify-primary">Create</button>
                        </a>
                    </span>
                </section>
                <section class="blogify-items">
                    <?php require_once 'components/blog-item.php';
                        if ($blogs['pagination']['totalResults']) {
                            echo wp_kses(
                                implode("\n",
                                array_map(
                                    fn($blog) => blogify_blog_item($blog['_id'], $blog['title'], $blog['image'], $blog['publishStatus'], $blog['wordCount'] ?? null),
                                    $blogs['data'])
                            ),
                            'post');
                        } else {
                        echo '<p style="text-align: center; width: 100%;">No Blogs Found</p>';
                    }
                    ?>
                </section>
            </article>
            <?php require_once 'components/category-list.php';?>
        </main>
    </div>
</div>