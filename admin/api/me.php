<?php
// بسم الله الرحمن الرحيم

namespace PixelShadow\Blogify;


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

require_once BLOGIFY_PLUGIN_DIR . 'admin/api/authentication.php';

function blogify_get_user_details(): array
{
    $data = get_transient('blogify_user_details');

    if ($data) {
        return $data;
    }

    
    $baseUrl = parse_ini_file(BLOGIFY_INI_PATH, true, INI_SCANNER_TYPED)['BLOGIFY']['SERVER_BASEURL'];
    $response = wp_remote_get(
        "{$baseUrl}public-api/v1/me",
        [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', get_access_token())
            ]
        ]
    );

    if (is_wp_error($response)) {
        throw new \Exception(esc_textarea($response->get_error_message()),  esc_textarea($response->get_error_code));
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new \Exception('Failed to decode response data');
    }

    set_transient('blogify_user_details', $data, 60);
    return $data;
}