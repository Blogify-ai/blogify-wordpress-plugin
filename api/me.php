<?php
// بسم الله الرحمن الرحيم

namespace PixelShadow\Blogify\API;


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


// class UserDetails
// {
// 	public string $status;
// 	public bool $verified;
// 	public string $id;
// 	public string $name;
// 	public string $email;
// 	public string $role;
// 	public string $googleId;
// 	public bool $googleAuthEnabled;
// 	public string $createdAt;
// 	public string $business;
// 	public array $interests;
// 	public string $stripeId;
// 	public string $subscriptionPlan;
// 	public string $subscriptionStatus;
// 	public int $credits;
// 	public bool $youtubeConnect;
// 	public bool $isShopifyUser;
// }



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
 * @throws Exception If an error occurs during the API call.
 *
 * @return array The user details retrieved from the API in an associative array format.
 *        
 */
function get_user_details(string $baseUrl, string $access_token)
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
        throw new \Exception($response->get_error_message(),  $response->get_error_code);
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new \Exception('Failed to decode response data');
    }

    return $data;
}