<?php
/**
 * Plugin Name
 *
 * @package           BlogifyAI
 * @author            Fida Waseque Choudhury
 * @copyright         PixelShadow
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       BlogifyAI
 * Plugin URI:        https://blogify.ai/
 * Description:       Create AI-generated blogs via Blogify.ai.
 * Version:           3.0.0
 * Requires at least: 6.4
 * Requires PHP:      8.0
 * Author:            Fida Waseque Choudhury
 * Author URI:        https://www.linkedin.com/in/u3kkasha/
 * Text Domain:       blogifyai
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        blogify-ai
 */

/**
 * Generates a Version 4 (random) UUID.
 *
 * @return string Returns a Version 4 UUID.
 */
function v4uuid(): string {
    $a = str_pad(dechex(random_int(0x0000_0000, 0xffff_ffff)), '0', STR_PAD_LEFT);
    $b = str_pad(dechex(random_int(0x0000, 0xffff)), '0', STR_PAD_LEFT);
    $c = dechex(random_int(0x4000, 0x4fff));
    $d = dechex(random_int(0x8000, 0xbfff));
    $e = str_pad(dechex(random_int(0x0000_0000_0000, 0xffff_ffff_ffff)), '0', STR_PAD_LEFT);
    return "$a$b$c$d$e";
}
if (is_multisite()) {

    /**
     * Registers custom REST API endpoints.
     */
    function create_blogify_api_endpoints(): void {
        register_rest_route('blogify/v1', '/create-post', array(
            'methods' => 'POST',
            'callback' => 'create_post_callback',
        ));

        register_rest_route('blogify/v1', '/sites', array(
            'methods' => 'GET',
            'callback' => 'get_sites_callback',
        ));
    }
    add_action('rest_api_init', 'create_blogify_api_endpoints');

    /**
     * Callback function to handle the creation of a post via REST API.
     *
     * @param WP_REST_Request $request The REST request object.
     * @return WP_REST_Response|WP_Error Returns a response object or WP_Error object.
     */
    function create_post_callback(WP_REST_Request $request): WP_REST_Response|WP_Error {
        $title = $request->get_param('title');
        $content = $request->get_param('content');
        $client_secret_at_blogify = $request->get_param('client_secret');
        $blog_id = $request->get_param('blog_id');

        // Check if blog_id is provided
        if (!$blog_id) {
            return new WP_Error('missing_blog_id', 'Missing blog_id parameter', array('status' => 400));
        }

        // Switch to the specified blog
        switch_to_blog($blog_id);
        $client_secret_in_users_wp_site = get_site_option('blogify_client_secret');

        // Check if client secret matches
        if ($client_secret_at_blogify !== $client_secret_in_users_wp_site) {
            restore_current_blog();
            return new WP_Error('unauthorized', 'Unauthorized access to the specified blog', array('status' => 401));
        }

        // Create a new post
        $post_data = array(
            'post_title' => $title,
            'post_content' => $content,
            'post_status' => 'publish',
            'post_type' => 'post',
        );
        $post_id = wp_insert_post($post_data);

        if ($post_id) {
            $blog_link = get_permalink($post_id);
            restore_current_blog();
            return new WP_REST_Response(array('message' => 'Post created successfully', 'blog_link' => $blog_link), 200);
        } else {
            restore_current_blog();
            return new WP_Error('failed_create_post', 'Failed to create post', array('status' => 500));
        }
    }


/**
 * Callback function to retrieve all sites in the multisite network.
 *
 * @param WP_REST_Request $request The REST request object.
 * @return WP_REST_Response Returns a response object.
 */
function get_sites_callback(WP_REST_Request $request): WP_REST_Response {
    $client_secret = $request->get_param('client_secret');

    // Retrieve stored client secret
    $stored_secret = get_site_option('blogify_client_secret');

    // Check if client secret matches
    if ($client_secret !== $stored_secret) {
        return new WP_REST_Response(array('error' => 'Unauthorized'), 401);
    }

    $sites = get_sites();
    $formatted_sites = array_map(function($site) {
        $blog_details = get_blog_details($site->blog_id); 
        return array(
            'id' => $site->blog_id,
            'name' => $blog_details->blogname,
            'url' => $blog_details->siteurl,
        );
    }, $sites);
    
    return new WP_REST_Response($formatted_sites, 200);
}


    /**
     * Adds a menu page for Blogify settings in the WordPress Network admin panel.
     */
    function custom_settings_page(): void {
        add_menu_page(
            'Blogify Settings',
            'Blogify Settings',
            'manage_options',
            'custom-settings',
            'custom_settings_callback'
        );
    }

    is_network_admin() and add_action('network_admin_menu', 'custom_settings_page');


    /**
     * Callback function to display settings on the Blogify Settings page.
     */
    function custom_settings_callback(): void {
        ?>
        <div class="wrap">
            <h2>Blogify Settings</h2>
            <form method="post" action="options.php">
                <?php
                settings_fields('custom_settings_group');
                do_settings_sections('custom-settings');
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Registers the Blogify client secret setting.
     */
    function custom_settings_register(): void {
        register_setting('custom_settings_group', 'blogify_client_secret');
    }
    is_network_admin() and add_action('admin_init', 'custom_settings_register');

    /**
     * Adds settings fields and sections for Blogify settings.
     */
    function custom_settings_fields(): void {
        add_settings_section('custom_section_id', 'Blogify Section', 'custom_section_callback', 'custom-settings');
        add_settings_field('blogify_client_secret', 'Blogify Wordpress Plugin', 'custom_option_callback', 'custom-settings', 'custom_section_id');
    }

    /**
     * Callback function to display information in the Blogify Settings section.
     */
    function custom_section_callback(): void {
        echo 'You are most likely here to connect your Wordpress site to Blogify.ai.<br/> If so please click on the Connect Button below (if you haven\'t already done so).<br/> Clicking the button will allow you to post to any and all sites in your Network from Blogify.ai';
    }

    /**
     * Callback function to display the connect button and generate a secret for Blogify integration.
     */
    function custom_option_callback(): void {
        if (get_site_option("blogify_client_secret") == "") {
            update_site_option('blogify_client_secret', v4uuid() . 'multisite');
        }
        $env = parse_ini_file('.env');
        $blogify_client_baseurl = $env['BLOGIFY_CLIENT_BASEURL'];

        $option = get_site_option('blogify_client_secret');
        $url_with_secret = site_url() . "?secret=" . $option;
        $url_to_open = $blogify_client_baseurl . "/dashboard/settings/wordpressorg-connect?wordpressorg=" . $url_with_secret;
        echo '<input type="button" class="button button-primary" value="Connect" onclick="window.open( \'' . $url_to_open . '\', \'_blank\');" />';
    }

    is_network_admin() and add_action('admin_init', 'custom_settings_fields');

} else {

    /**
     * Registers a custom REST API endpoint for creating a post.
     *
     * This function registers an endpoint to accept POST requests for creating posts.
     *
     * @since 1.0
     *
     * @see register_rest_route()
     *
     * @return void
     */


    function create_blogify_api_endpoint() {
        register_rest_route('blogify/v1', '/create-post', array(
            'methods' => 'POST',  // Allow POST requests
            'callback' => 'create_post_callback', // Callback function to handle the request
        ));
    }

    // Register the custom endpoint
    add_action('rest_api_init', 'create_blogify_api_endpoint');


    /**
     * Callback function to handle the creation of a post via REST API.
     *
     * Handles the POST request to create a new post using received parameters.
     *
     * @since 1.0
     *
     * @see wp_insert_post()
     *
     * @param WP_REST_Request $request The REST request object.
     * @return WP_Error|array Returns a success message and post link on success, or a WP_Error object on failure.
     */

    function create_post_callback($request) {
        // Get data from the request
        $title = $request->get_param('title');
        $content = $request->get_param('content');
        $client_secret_at_blogify = $request->get_param('client_secret');
        $client_secret_in_users_wp_site = get_option('blogify_client_secret');

        if($client_secret_at_blogify !== $client_secret_in_users_wp_site){
            return new WP_Error('error', 'Client secret mismatch', array('status' => 403));
        }

        // Create a new post
        $post_data = array(
            'post_title' => $title,
            'post_content' => $content,
            'post_status' => 'publish', // You can change the post status as needed
            'post_type' => 'post', // You can use other post types as well
        );

        $post_id = wp_insert_post($post_data);

        if ($post_id) {
            return array('message' => 'Post created successfully', 'blog_link' => get_permalink($post_id));
        } else {
            return new WP_Error('error', 'Failed to create post', array('status' => 500));
        }
    }


    /**
     * Adds a menu page for Blogify settings in the WordPress admin panel.
     *
     * Creates a menu page in the admin panel to access Blogify settings.
     *
     * @since 1.0
     *
     * @see add_menu_page()
     *
     * @return void
     */

    function custom_settings_page() {
        add_menu_page(
            'Blogify Settings', // Page Title
            'Blogify Settings', // Menu Title
            'manage_options', // Capability (who can access)
            'custom-settings', // Menu Slug
            'custom_settings_callback' // Callback function to display settings
        );
    }
    add_action('admin_menu', 'custom_settings_page');


    /**
     * Callback function to display settings on the Blogify Settings page.
     *
     * Displays content on the Blogify Settings page in the admin panel.
     *
     * @since 1.0
     *
     * @return void
     */

    function custom_settings_callback() {
        ?>
        <div class="wrap">
            <h2>Blogify Settings</h2>
            <form method="post" action="options.php">
                <?php
                settings_fields('custom_settings_group'); // Use the settings group name
                do_settings_sections('custom-settings'); // Use the menu slug
                ?>
            </form>
        </div>
        <?php
    }


    /**
     * Registers the Blogify client secret setting.
     *
     * Registers the setting for the Blogify client secret in WordPress options.
     *
     * @since 1.0
     *
     * @see register_setting()
     *
     * @return void
     */

    function custom_settings_register() {
        register_setting('custom_settings_group', 'blogify_client_secret');
    }
    add_action('admin_init', 'custom_settings_register');


    /**
     * Adds settings fields and sections for Blogify settings.
     *
     * Adds sections and fields to the Blogify Settings page in the admin panel.
     *
     * @since 1.0
     *
     * @return void
     */

    function custom_settings_fields() {
        add_settings_section('custom_section_id', 'Blogify Section', 'custom_section_callback', 'custom-settings');
        add_settings_field('blogify_client_secret', 'Blogify Wordpress Plugin', 'custom_option_callback', 'custom-settings', 'custom_section_id');
    }


    /**
     * Callback function to display information in the Blogify Settings section.
     *
     * Displays contextual information within the Blogify Settings section.
     *
     * @since 1.0
     *
     * @return void
     */

    function custom_section_callback() {
        echo 'You are most likely here to connect your Wordpress site to Blogify.ai.<br/> If so please click on the Connect Button below (if you haven\'t already done so).';
    }


    /**
     * Callback function to display the connect button and generate a secret for Blogify integration.
     *
     * Generates a secret and displays the connect button for Blogify integration.
     *
     * @since 1.0
     *
     * @return void
     */

    function custom_option_callback() {
        if( get_option( "blogify_client_secret") == ""){
            update_option('blogify_client_secret', v4uuid());
        }
        $env = parse_ini_file('.env');
        $blogify_client_baseurl= $env['BLOGIFY_CLIENT_BASEURL'];
        
        $option = get_option('blogify_client_secret');
        $url_with_secret = site_url() . "?secret=" .$option;
        $url_to_open= $blogify_client_baseurl . "/dashboard/settings/wordpressorg-connect?wordpressorg=" .$url_with_secret;
        echo '<input type="button" class="button button-primary" value="Connect" onclick="window.open( \''.$url_to_open.'\', \'_blank\');" />';

    }

    add_action('admin_init', 'custom_settings_fields');

}
?>
