<?php
// بسم الله الرحمن الرحيم

namespace PixelShadow\Blogify\API;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Registers a new user on the provided base URL.
 *
 * The function throws an exception on various error conditions, including:
 *  - API call failure.
 *  - Missing access token in the response.
 *  - Signup failure with a specific message from the response.
 *
 * On successful signup, the function returns the access token retrieved from the
 * response.
 *
 * @param string $baseUrl The base URL of the API endpoint.
 * @param string $email The user's email address.
 * @param string $password The user's password.
 * @param string $name The user's name.
 * @param string $business_name The user's business name.
 * @param string $found_us_from How the user found the platform.
 *
 * @throws Exception If an error occurs during signup.
 *
 * @return string The access token on successful signup. Empty string on failure.
 */
function signup(string $baseUrl, string $email, string $password, string $name, string $business_name, string $found_us_from): string
{
    $response = wp_remote_post(
        $baseUrl . '/auth/signup',
        array(
            'body' => json_encode(array('email' => $email, 'password' => $password,
                'name' => $name, 'businessName' => $business_name, 'foundUsFrom' => $found_us_from)),
            'headers' => array('Content-Type' => 'application/json'),
        )
    );
    if (!is_wp_error($response)) {
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (isset($data['access_token'])) {
            return $data['access_token'];
        } elseif (isset($data['message'])) {
            throw new \Exception('Signup failed with message: ' . $data['message']);
        } else {
            throw new \Exception('Signup failed: Access token not found in response body');
        }
    } else {
        throw new \Exception($response->get_error_message(), $response->get_error_code());
    }
}

/**
 * Retrieves the access token by making a POST request to the specified endpoint with the given email and password.
 *
 * @param string $baseUrl The base URL of the API.
 * @param string $email The user's email address.
 * @param string $password The user's password.
 * @return string The access_token after successful login
 * @throws Exception If an error occurs during login.
 * @see signup() for more information.
 * @see get_auth_token() for more information.
 */
function login(string $baseUrl, string $email, string $password): string
{
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
            throw new \Exception('Login failed with message: ' . $data['message']);
        } else {
            throw new \Exception('Login failed : Access token not found in response body');
        }
    } else {
        throw new \Exception($response->get_error_message(), $response->get_error_code());
    }
};

/**
 * Get the Access Token for the current user
 * @param string $baseUrl The base URL of the API.
 * @return string The access_token for the current user
 * @throws Exception If there is any failure in getting the access token.
 * @see login() for more information.
 */
function get_auth_token(string $baseUrl): string
{
    return login($baseUrl, wp_get_current_user()->user_email, get_option("blogify_client_secret"));
}

/**
 * Registers a new user on the provided base URL.
 *
 * The function throws a `WP_Error` exception on various error conditions, including:
 *  - API call failure.
 *  - Missing access token in the response.
 *  - Signup failure with a specific message from the response.
 *
 * On successful signup, the function returns the access token retrieved from the
 * response.
 *
 * @param string $baseUrl The base URL of the API endpoint.
 *
 * @throws Exception If an error occurs during signup.
 *
 * @return string The access token on successful signup.
 */
function signup_wordpress_user(string $baseUrl): string
{
    return signup($baseUrl,
        wp_get_current_user()->user_email,
        get_option("blogify_client_secret"),
        wp_get_current_user()->display_name,
        site_url, "blogify-wordpress-plugin");
}

/**
 * Get the Blogify Dashboard URL for the current user
 * @param string $baseUrl The base URL of the API.
 * @param string $access_token The access_token for the current user
 * @return string The dashboard URL for the current user
 * @see get_auth_token() for more information.
 */
function get_dashboard_url(string $baseUrl, string $access_token): string
{
    return "${baseUrl}/dashboard?token=${access_token}";
}
