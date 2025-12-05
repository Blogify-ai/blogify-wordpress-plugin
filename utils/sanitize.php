<?php

namespace PixelShadow\Blogify\Utils;

/**
 * Sanitizes an array of text values
 * 
 * @param array|null $array Array to sanitize
 * @return array Sanitized array
 */
function sanitize_text_array(?array $array): array {
    return is_array($array) ? array_map('sanitize_text_field', $array) : [];
}

/**
 * Sanitize post data for creating/updating WordPress posts
 * 
 * @param array $data Raw post data
 * @return array Sanitized post data
 */
function sanitize_post_data(array $data): array {
    return [
        'post_title' => sanitize_text_field($data['title'] ?? ''),
        'post_content' => wp_kses_post($data['content'] ?? ''),
        'tags_input' => sanitize_text_array($data['keywords'] ?? []),
        'post_excerpt' => sanitize_text_field($data['summary'] ?? ''),
        'post_status' => sanitize_text_field($data['status'] ?? 'draft'),
        'post_author' => absint($data['author'] ?? get_current_user_id()),
        'post_category' => array_map('absint', $data['categories'] ?? []),
        'meta_input' => [
            'blogify_blog_id' => sanitize_text_field($data['blog_id'] ?? ''),
            'blogify_meta_tags' => array_map('sanitize_text_field', $data['meta_tags'] ?? []),
            'blogify_meta_description' => sanitize_text_field($data['meta_description'] ?? ''),
        ],
    ];
}