<?php
// بسم الله الرحمن الرحيم

namespace PixelShadow\Blogify;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function blogify_validate_token(string $token): bool
{
     $response = wp_remote_get(BLOGIFY_SERVER_BASEURL . 'wordpressorg/token/validate', [
        'headers' => [
            'Authorization' => 'Bearer ' . $token,
        ],
        'timeout' => 10,
    ]);

    if (is_wp_error($response)) {
        throw new \Exception(esc_textarea($response->get_error_message()));
    }

    if (2 === intdiv(wp_remote_retrieve_response_code($response), 100)) {
        return true;
    } else {
        return false;
    }
}

function blogify_get_access_token(): string
{
    $access_token = get_option('blogify_access_token', null);

    if (!$access_token) {
        throw new \Exception('No blogify access token found: option is does not exist');
    }

    return $access_token;

}

function blogify_register_publish_route_with_blogify(string $access_token): void
{
    $response = wp_remote_post(
        BLOGIFY_SERVER_BASEURL . 'wordpressorg/subscribe',
        [
            'body' => [
                'webhook' => site_url() . "?secret=" . get_option('blogify_client_secret'),
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $access_token,
            ],
            'timeout' => 10,
        ]
    );

    if (is_wp_error($response)) {
        throw new \Exception(esc_textarea($response->get_error_message()));
    }

}
