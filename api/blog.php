<?php
// بسم الله الرحمن الرحيم

namespace PixelShadow\Blogify;

/**
 * Fetches a list of blogs from the provided base URL using the access token.
 *
 * This function utilizes the `wp_remote_get` function to retrieve a list of blogs
 * from the specified base URL with the provided access token. It expects the base
 * URL to include the "/blogs" endpoint.
 *
 * @param string $baseUrl The base URL of the API endpoint (including "/blogs").
 * @param string $access_token The user's access token for authentication.
 *
 * @throws WP_Error If an error occurs during the API call.
 *
 * @return array An associative array containing the list of blogs retrieved from the API.
 *        
 */
function get_blogs(string $baseUrl, string $access_token): array
{
    $response = wp_remote_get(
        "$baseUrl/blogs",
        [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $access_token)
            ]
        ]
    );

    if (is_wp_error($response)) {
        throw new WP_Error($response->get_error_message());
    }

    // Handle successful response
    $body = wp_remote_retrieve_body($response);
    $json = json_decode($body, true); // Assuming JSON response

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new WP_Error('Failed to decode response data');
    }

    return $json['data'];
}

