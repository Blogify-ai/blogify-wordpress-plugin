<?php

namespace PixelShadow\Blogify;


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function blogify_blog_item(string $id, string $title, ?string $cover_image, string $publish_status, ?int $word_count, string $category = null) { 
    $blog_image = $cover_image?: BLOGIFY_IMAGES_URL . "logos/blogify-logo-black.png";
    $blog_stats = implode( " · ", array_filter(
                                                [
                                                    ucfirst(strtolower($publish_status)),
                                                    $word_count ? "$word_count words": null,
                                                    '0 links',
                                                    $category,
                                                ],
                                                fn($item) => $item !== null,
                                 )
                    );
    
    return <<<END
        <section class="blogify-item">
            <span class="blogify-left">
                <img class="blogify-blog-cover" alt="Blog Cover"
                    src=$blog_image />
                <span class="blogify-blog-info">
                    <span class="blogify-blog-title">$title</span>
                    <span class="blogify-blog-stats">$blog_stats</span>
                </span>
            </span>
            <span class="blogify-right">
                <a href="https://blogify.ai/dashboard/blogs/$id" target="_blank" >
                    <button type="button" class="blogify-secondary">View</button>
                </a>
                <a href="https://blogify.ai/dashboard/blogs/$id/edit" target="_blank" >
                    <button type="button" class="blogify-secondary">Edit</button>
                </a>
            </span>
        </section>
        END;
}