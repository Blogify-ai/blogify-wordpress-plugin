<?php

namespace PixelShadow\Blogify;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generates a Version 4 (random) UUID.
 *
 * @return string Returns a Version 4 UUID.
 */
function blogify_v4uuid(): string
{
    $a = str_pad(dechex(random_int(0x0000_0000, 0xffff_ffff)), '0', STR_PAD_LEFT);
    $b = str_pad(dechex(random_int(0x0000, 0xffff)), '0', STR_PAD_LEFT);
    $c = dechex(random_int(0x4000, 0x4fff));
    $d = dechex(random_int(0x8000, 0xbfff));
    $e = str_pad(dechex(random_int(0x0000_0000_0000, 0xffff_ffff_ffff)), '0', STR_PAD_LEFT);
    return "$a-$b-$c-$d-$e";
}

add_option('blogify_client_secret', blogify_v4uuid());

// Needed for image sideloading
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

add_action('rest_api_init', fn () => 
    register_rest_route('blogify/v1', '/create-post', [
        'methods' => ['POST'],
        'permission_callback' => fn(\WP_REST_Request $request) => $request->get_param('client_secret') === get_option('blogify_client_secret'),
        'callback' => function (\WP_REST_Request $request) {

            $required = function($field) use ($request) {
                $value = $request->get_param($field);
                if (empty($value)) {
                    return new \WP_Error('error', "$field is required", ['status' => 400]);
                }
                return $value;
            };

            $status_validation = function() use ($request) {
                $status = $request->get_param('status');
                if ($status && !in_array($status, array_keys(get_post_statuses()), true)) {
                    return new \WP_Error('error', 'status must be one of ' . implode(', ', array_keys(get_post_statuses())), ['status' => 400]);
                }
                return $status;
            };
            
            $sanitize_text_array = fn(?array $array) => is_array($array) ? array_map('sanitize_text_field', $array) : [];


            $title = $required('title');
            if (is_wp_error($title)) {
                return $title;
            }

            $content = $required('content');
            if (is_wp_error($content)) {
                return $content;
            }

            $blog_id = $required('blog_id');
            if (is_wp_error($blog_id)) {
                return $blog_id;
            }

            $status = $status_validation();
            if (is_wp_error($status)) {
                return $status;
            }

            $post_id = wp_insert_post([
                'post_title' => sanitize_text_field($title),
                'post_content' => wp_kses_post($content),
                'post_status' => $status,
                'tags_input' => $sanitize_text_array($request->get_param('keywords')),
                'post_type' => 'post',
                'post_excerpt' => sanitize_text_field($request->get_param('summary')),
                'meta_input' => [
                    'blogify_blog_id' => sanitize_text_field($blog_id),
                    'blogify_meta_tags' => $sanitize_text_array($request->get_param('meta_tags')),
                    'blogify_meta_description' => sanitize_text_field($request->get_param('meta_description')),
                ],
            ]);

            if (is_wp_error($post_id)) {
                return new WP_Error('error', 'Failed to create post: ' . $post_id->get_error_message(), ['status' => 500]);
            }

            if ($request->get_param('image_url')) {
                $image = media_sideload_image(sanitize_url($request->get_param('image_url')), $post_id, null, 'id');
                set_post_thumbnail($post_id, $image);
            }

            return ['message' => 'Post created successfully', 'blog_link' => get_permalink($post_id)];
        },
    ])
);



add_action('wp_head', function () {
    global $post;
    if (is_page() || is_single()) {
        $meta_description = get_post_meta(get_queried_object_id(), 'blogify_meta_description', true);
        $meta_tags = get_post_meta(get_queried_object_id(), 'blogify_meta_tags', true);

        if (!empty($meta_description)) {
            printf(
                '<meta name="description" content="%s" />\\n',
                esc_attr(trim($meta_description))
            );
        }

        if (!empty($meta_tags)) {
            printf(
                '<meta name="keywords" content="%s" />\\n',
                esc_attr(trim(implode(',', $meta_tags)))
            );
        }
    }
}
);
