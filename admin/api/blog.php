<?php
// بسم الله الرحمن الرحيم

namespace PixelShadow\Blogify;

require_once BLOGIFY_PLUGIN_DIR . 'admin/api/authentication.php';


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function get_blogs(int $page_number, int $page_size, ?string $publish_status = null): array
{
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

function get_publish_status_count(): array
{
    
    $baseUrl = parse_ini_file(BLOGIFY_INI_PATH, true, INI_SCANNER_TYPED)['BLOGIFY']['SERVER_BASEURL'];
    
    $statuses= ['draft', 'scheduled', 'published'];

    $results = \Requests::request_multiple(array_map(fn (string $status): array => [
        'type' => \Requests::GET,
        'url' => "{$baseUrl}public-api/v1/blogs?publish-status=$status",
        'headers' => ['Authorization' => sprintf('Bearer %s', get_access_token())],
        'timeout' => 10,
    ],
    array_combine($statuses, $statuses)));

    return array_map(fn($response) => json_decode($response->body, true, 512, JSON_THROW_ON_ERROR)['pagination']['totalResults'], $results);
}