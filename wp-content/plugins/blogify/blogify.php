<?php
/*
Plugin Name: Blogify.ai
Plugin URI:   http://blogify.ai
Description:  This plugin adds posts in your wordpress site from Blogify.ai
Version:      1.0
Author:       Abu Haider Siddiq
Author URI:   http://bilassiddiq.com
*/

/* Create Custom Endpoint */

function create_blogify_api_endpoint() {
    register_rest_route('blogify/v1', '/create-post', array(
        'methods' => 'POST',  // Allow POST requests
        'callback' => 'create_post_callback', // Callback function to handle the request
    ));
}

// Register the custom endpoint
add_action('rest_api_init', 'create_blogify_api_endpoint');

// Callback function to handle the POST request
function create_post_callback($request) {
    // Get data from the request
    $title = $request->get_param('title');
    $content = $request->get_param('content');
    $client_secret_at_blogify = $request->get_param('client_secret');
    $client_secret_in_users_wp_site = get_option('blogify_client_secret');

    if($client_secret_at_blogify !== $client_secret_in_users_wp_site){
        // return array('message' => 'Client Secret Error');
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
 * Blogify settings page
 */

 function custom_settings_page() {
    $dir_path = plugins_url( "blogify/favicon.ico", "" );
    add_menu_page(
        'Blogify Settings', // Page Title
        'Blogify Settings', // Menu Title
        'manage_options', // Capability (who can access)
        'custom-settings', // Menu Slug
        'custom_settings_callback', // Callback function to display settings
        $dir_path
    );
}
add_action('admin_menu', 'custom_settings_page');

function custom_settings_callback() {
    ?>
    <div class="wrap">
        <h2>Custom Settings</h2>
        <div>
            <?php
            // settings_fields('custom_settings_group'); // Use the settings group name
            do_settings_sections('custom-settings'); // Use the menu slug
            // submit_button();
            ?>
        </div>
    </div>
    <?php
}

function custom_settings_register() {
    register_setting('custom_settings_group', 'blogify_client_secret');
}
add_action('admin_init', 'custom_settings_register');

function custom_settings_fields() {
    add_settings_section('custom_section_id', 'Blogify Section', 'custom_section_callback', 'custom-settings');
    add_settings_field('blogify_client_secret', 'Blogify Client Secret', 'custom_option_callback', 'custom-settings', 'custom_section_id');
}

function custom_section_callback() {
    echo 'This is the description of the custom section.';
}

function custom_option_callback() {
    $random = generateRandomString(10);
    if( get_option( "blogify_client_secret") == ""){
        update_option('blogify_client_secret', $random);
    }
    $option = get_option('blogify_client_secret');
    // $url = site_url();
    $client_copy = site_url() . "?secret=" .$option ;
    echo "<span>".$client_copy."</span>";
    // echo '<input type="text" name="blogify_client_secret" value="' . esc_attr($option) . '" />';
}
add_action('admin_init', 'custom_settings_fields');

/**
 * Generate Random string
 */
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>