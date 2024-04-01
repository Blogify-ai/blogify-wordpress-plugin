<?php
// بسم الله الرحمن الرحيم

namespace PixelShadow\Blogify;


defined( 'ABSPATH' ) or die( 'Hey, you can\'t access this file, you silly human!' );

/**
 * Fetches user details from the provided base URL using the access token.
 *
 * This function utilizes the `wp_remote_get` function to retrieve user details
 * from the specified base URL with the provided access token. It expects the base
 * URL to include the "/users/me" endpoint.
 *
 * @param string $baseUrl The base URL of the API endpoint (including "/users/me").
 * @param string $access_token The user's access token for authentication.
 *
 * @throws WP_Error If an error occurs during the API call.
 *
 * @return array The user details retrieved from the API in an associative array format.
 *        
 */
function get_user_details(string $baseUrl, string $access_token): array
{
    $response = wp_remote_get(
        "$baseUrl/users/me",
        [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $access_token)
            ]
        ]
    );

    if (is_wp_error($response)) {
        throw $response;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new WP_Error('Failed to decode response data');
    }

    return $data;
}