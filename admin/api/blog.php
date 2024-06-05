<?php
// بسم الله الرحمن الرحيم

namespace PixelShadow\Blogify;

require_once BLOGIFY_PLUGIN_DIR . 'admin/api/authentication.php';


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function get_blogs(int $page_number, int $page_size, ?string $publish_status = null): array
{
    $blogs = get_transient('blogify_blogs' . $page_number . $page_size . $publish_status);
    
    if ($blogs) {
        return $blogs;
    }
    
    $baseUrl = parse_ini_file(BLOGIFY_INI_PATH, true, INI_SCANNER_TYPED)['BLOGIFY']['SERVER_BASEURL'];
    $response = wp_remote_get(
        "{$baseUrl}public-api/v1/blogs?" . http_build_query([
            'page-number' => $page_number,
            'page-size' => $page_size,
            'publish-status' => $publish_status,
        ]),
        [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', get_access_token()),
            ],
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

    set_transient('blogify_blogs', $blogs, 60);

    return $blogs;
}