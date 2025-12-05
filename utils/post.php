<?php

namespace PixelShadow\Blogify\Utils;

require_once 'sanitize.php';

use WP_Error;

use function PixelShadow\Blogify\Utils\sanitize_post_data;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


/**
 * Validates that required fields are present
 * 
 * @param string $value The value to check
 * @param string $field The field name
 * @return string|WP_Error The value or WP_Error if invalid
 */
function validate_required(string $value, string $field) {
    if (empty($value)) {
        return new WP_Error(
            'missing_field',
            sprintf('The %s field is required', $field)
        );
    }
    return $value;
}

/**
 * Validates post status is valid
 * 
 * @param string|null $status The post status
 * @return string|WP_Error The status or WP_Error if invalid
 */
function validate_post_status(?string $status) {
    if ($status && !in_array($status, array_keys(get_post_statuses()), true)) {
        return new WP_Error(
            'invalid_status',
            'Invalid post status'
        );
    }
    return $status;
}

/**
 * Create a new WordPress post with sanitized data
 * 
 * @param array $data Post data
 * @return int|WP_Error Post ID on success, WP_Error on failure
 */
function create_post(array $data) {
    $sanitized_data = sanitize_post_data($data);
    
    $post_id = wp_insert_post($sanitized_data, true);
    
    if (is_wp_error($post_id)) {
        return $post_id;
    }
    
    if (!empty($data['image_url'])) {
        $image_id = media_sideload_image(
            sanitize_url($data['image_url']), 
            $post_id, 
            null, 
            'id'
        );
        
        if (!is_wp_error($image_id)) {
            set_post_thumbnail($post_id, $image_id);
        }
    }
    
    return $post_id;
}