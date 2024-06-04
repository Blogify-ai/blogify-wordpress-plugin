<?php
// بسم الله الرحمن الرحيم

//namespace PixelShadow\Blogify\API;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function get_oauth2_consent_url(): string
{
    $config = parse_ini_file(BLOGIFY_INI_PATH, true, INI_SCANNER_TYPED);
    $redirect_uri = get_admin_url() . "admin.php?page=oauth2-connect";

    return $config['BLOGIFY']['CLIENT_BASEURL'] . 'dashboard/settings/oauth2-consent?'
    . http_build_query(
        [
            'client_id' => $config['OAUTH2']['CLIENT_ID'],
            'redirect_uri' => $redirect_uri,
            'response_type' => 'code',
            'scope' => $config['OAUTH2']['SCOPE'],
            'state' => get_option('blogify_client_secret'),
        ],
        '',
        '&',
        PHP_QUERY_RFC3986
    );
}

function get_oauth2_tokens_from_auth_code(string $auth_code): array
{
    $config = parse_ini_file(BLOGIFY_INI_PATH, true, INI_SCANNER_TYPED);
    $redirect_uri = get_admin_url() . "admin.php?page=oauth2-connect";

    $response = wp_remote_post($config['BLOGIFY']['SERVER_BASEURL'] . 'oauth2/v1/token',
        [
            'body' => [
                "grant_type" => "authorization_code",
                "client_id" => $config['OAUTH2']['CLIENT_ID'],
                "client_secret" => $config['OAUTH2']['CLIENT_SECRET'],
                "redirect_uri" => $redirect_uri,
                "code" => $auth_code,
            ],
        ]
    );

    if (is_wp_error($response)) 
        throw new \Exception($response->get_error_message());

    if (2 !== intdiv(wp_remote_retrieve_response_code($response), 100))
        throw new \Exception(implode(' ',
            [
                'Failed to get access token:',
                'request failed with code ' . wp_remote_retrieve_response_code($response),
                '=>',
                wp_remote_retrieve_body($response),
            ]
        ));
    

    return json_decode(wp_remote_retrieve_body($response), true, 512, JSON_THROW_ON_ERROR);

}

function get_oauth2_tokens_from_refresh_token(string $refresh_token): array
{
    $config = parse_ini_file(BLOGIFY_INI_PATH, true, INI_SCANNER_TYPED);
    
    $response = wp_remote_post($config['BLOGIFY']['SERVER_BASEURL'] . 'oauth2/v1/refresh',
        [
            'body' => [
                "grant_type" => "refresh_token",
                "client_id" => $config['OAUTH2']['CLIENT_ID'],
                "client_secret" => $config['OAUTH2']['CLIENT_SECRET'],
                "refresh_token" => $refresh_token,
            ],
        ]
    );

    if (is_wp_error($response)) 
        throw new \Exception($response->get_error_message());

    if (2 !== intdiv(wp_remote_retrieve_response_code($response), 100))
        throw new \Exception(implode(' ',
        [
            'Failed to get access token:',
            'request failed with code ' . wp_remote_retrieve_response_code($response),
            '=>',
            wp_remote_retrieve_body($response),
        ]
    ));

    return json_decode(wp_remote_retrieve_body($response), true, 512, JSON_THROW_ON_ERROR);
}

function save_oauth2_tokens(string $access_token, string $refresh_token, int $expires_in): void
{
    update_option('blogify_oauth2_tokens', [
        'access' => $access_token,
        'refresh' => $refresh_token,
        'expires_at' => time() + $expires_in,
    ], false);
}


function get_access_token(): string
{
    $tokens = get_option('blogify_oauth2_tokens', null);

    if( !$tokens) 
        throw new \Exception('No blogify oauth2 tokens found: option is does not exist');
    
    if( time() >= $tokens['expires_at']) {
        $new_tokens = get_oauth2_tokens_from_refresh_token($tokens['refresh']);
        save_oauth2_tokens($new_tokens['access_token'], $new_tokens['refresh_token'], $new_tokens['expires_in']);
        return $new_tokens['access_token'];
    }
    
    return $tokens['access'];

}