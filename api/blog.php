<?php
// بسم الله الرحمن الرحيم

namespace PixelShadow\Blogify\API;

require_once __DIR__ . '/authentication.php';


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function blogify_fetch_blog(string $blog_id)
{
    $response = wp_remote_get(
        BLOGIFY_SERVER_BASEURL . "public-api/v1/blogs/" . $blog_id,
        [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', blogify_get_access_token()),
            ],
            'timeout' => 10,
        ]
    );
    if (is_wp_error($response)) {
        throw new \Exception(esc_textarea($response->get_error_message()));
    }
    return json_decode(wp_remote_retrieve_body($response), true, 512, JSON_THROW_ON_ERROR);
}

function blogify_get_blogs(int $page_number, int $page_size): array
{
    $response = wp_remote_get(
        BLOGIFY_SERVER_BASEURL . "public-api/v1/blogs?" . http_build_query([
            'offset' => ($page_number - 1) * $page_size,
            'limit' => $page_size,
        ]),
        [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', blogify_get_access_token()),
            ],
            'timeout' => 10,
        ]
    );

    if (is_wp_error($response)) {
        throw new \Exception(esc_textarea($response->get_error_message()));
    }

    $body = wp_remote_retrieve_body($response);
    $blogs = json_decode($body, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new \Exception('Failed to decode response data' . __FUNCTION__);
    }

    return $blogs;
}
