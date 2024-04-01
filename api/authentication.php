<?php
// بسم الله الرحمن الرحيم

namespace PixelShadow\Blogify;

defined( 'ABSPATH' ) or die( 'Hey, you can\'t access this file, you silly human!' );



/**
 * Retrieves the access token by making a POST request to the specified endpoint with the given email and password.
 *
 * @param string $baseUrl The base URL of the API.
 * @param string $email The user's email address.
 * @param string $password The user's password.
 * @return string The access_token after successful login
 * @throws WP_Error The WP_Error object on failure. 
 */
function login(string $baseUrl, string $email, string $password): string { 
 $response = wp_remote_post(
        $baseUrl . '/auth/login',
        array(
            'body' => json_encode(array('email' => $email, 'password' => $password)),
            'headers' => array('Content-Type' => 'application/json'),
        )
    );

    if (!is_wp_error($response)) {
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (isset($data['access_token'])) {
            return $data['access_token'];
        } elseif (isset($data['message'])) {
            throw new WP_Error('login_failed', $data['message']);
        } 
        else {
            throw new WP_Error('missing_access_token', 'Access token not found in response body');
        }
    } else {
        throw $response;
    }
};

/**
 * Get the Access Token for the current user
 * @param string $baseUrl The base URL of the API.
 * @return string The access_token for the current user
 * @throws WP_Error The WP_Error object on failure. 
 * @see login() for more information.
 */
function get_auth_token(string $baseUrl): string {
    return login($baseUrl, wp_get_current_user()->user_email, get_option( "blogify_client_secret"));    
}


/**
 * Get the Blogify Dashboard URL for the current user
 * @param string $baseUrl The base URL of the API.
 * @param string $access_token The access_token for the current user
 * @return string The dashboard URL for the current user
 * @see get_auth_token() for more information.
 */
function get_dashboard_url(string $baseUrl, string $access_token): string {
    return "${baseUrl}/dashboard?token=${access_token}";
}

