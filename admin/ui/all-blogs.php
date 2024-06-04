<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

require_once BLOGIFY_PLUGIN_DIR . 'admin/api/authentication.php';
require_once BLOGIFY_PLUGIN_DIR . 'admin/api/blog.php';


$blogs = get_blogs($_GET['page-number'] ?? 1, 20);

?>

<div class="wrap">
    <div class="blogify">
    <?php require_once 'components/header.php'; ?>   
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
                        echo implode('', 
                            array_map(
                                fn ($blog) => blogify_blog_item($blog['_id'], $blog['title'], $blog['image'], $blog['publishStatus'], $blog['wordCount'] ),
                                $blogs['data'])
                            );
                    ?>
                </section>
            </article>
        </main>
        <footer>
                <?php require_once 'components/pagination.php'; 
                    $pagination_data = $blogs['pagination'];
                    echo construct_pagination(
                        $pagination_data['page'],
                        $pagination_data['totalResults'],
                        $pagination_data['limit'],
                        $pagination_data['totalPages'],
                        BLOGIFY_IMAGES_URL
                    )
                ?>
                
        </footer>
    </div>
</div>