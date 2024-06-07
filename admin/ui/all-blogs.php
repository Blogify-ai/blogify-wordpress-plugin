<?php

namespace PixelShadow\Blogify;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once BLOGIFY_PLUGIN_DIR . 'admin/api/authentication.php';
require_once BLOGIFY_PLUGIN_DIR . 'admin/api/blog.php';

$page_number = wp_verify_nonce($_GET['blogify-pagination-nonce'], 'blogify-pagination') ? $_GET['page-number'] : 1;
$blogs = get_blogs($page_number, 20);

?>

<div class="wrap">
    <div class="blogify">
    <?php require_once 'components/header.php';?>
        <main>
        <article class="blogify-blog-list">
                <section class="blogify-header">
                    <span class ="blogify-left">
                        <span class="blogify-title">My Blogs</span>
                    </span>
                    <span class="blogify-right">
                        <a href= 'https://blogify.ai/dashboard/blogs' target="_blank">
                            <button type="button" class="blogify-primary">View All</button>
                        </a>
                        <a href= 'https://blogify.ai/dashboard/blogs/select-source' target="_blank">
                            <button type="button" class="blogify-primary">Create</button>
                        </a>
                    </span>
                </section>
                <section class="blogify-items">
                <?php require_once 'components/blog-item.php';
                    if ($blogs['data']) {
                        echo wp_kses(
                            implode("\n", array_map(
                                fn($blog) => blogify_blog_item($blog['_id'], $blog['title'], $blog['image'], $blog['publishStatus'], $blog['wordCount']),
                                $blogs['data'])),
                            'post'
                        );
                    } else {
                        echo '<p style="text-align: center; width: 100%;">No Blogs Found</p>';
                    }
                ?>
                </section>
            </article>
        </main>
        <footer>
                <?php require_once 'components/pagination.php';
                    $pagination_data = $blogs['pagination'];
                    $nonce = wp_create_nonce('blogify-pagination');
                    echo wp_kses(
                       construct_pagination(
                            $pagination_data['page'],
                            $pagination_data['totalResults'],
                            $pagination_data['limit'],
                            $pagination_data['totalPages'],
                            BLOGIFY_IMAGES_URL,
                            $nonce,
                       ),
                       array_merge(
                        wp_kses_allowed_html('post'),
                        [
                            'form' => [
                                'method' => 'GET',
                                'action' => admin_url( "admin.php" ),
                            ],
                            'input' => [
                                'type' => array('hidden'),
                                'name' => array('page', 'blogify-pagination-nonce'),
                                'value' => array('blogify-all-blogs', $nonce ),
                                'id' => array('blogify-pagination-nonce'),
                            ],
                        ]
                    ));
                ?>
        </footer>
    </div>
</div>