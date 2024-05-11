<?php
// بسم الله الرحمن الرحيم
/**
 * Blogify AI
 *
 * @package           Blogify-AI
 * @author            Fida Waseque Choudhury
 * @copyright         PixelShadow
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Blogify-AI
 * Plugin URI:        https://blogify.ai/
 * Description:       Create AI-generated blogs via Blogify.ai.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            PixelShadow
 * Author URI:        https://blogify.ai/
 * Developer:         Fida Waseque Choudhury
 * Developer URI:     https://www.linkedin.com/in/u3kkasha/
 * Text Domain:       blogify-ai
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        blogify-ai
 */

// Needed for image sideloading
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

require_once plugin_dir_path(__FILE__) . '/api/me.php';
require_once plugin_dir_path(__FILE__) . '/api/authentication.php';

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Add extra links on plugin page
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'add_blogify_dashboard_and_settings_links');

function add_blogify_dashboard_and_settings_links($actions)
{
    $actions[] = '<a href="' . esc_url(get_admin_url(null, 'options-general.php?page=blogify-settings')) . '">Settings</a>';
    $actions[] = '<a href="' . esc_url(get_admin_url(null, 'admin.php?page=blogify-dashboard')) . '">Dashboard</a>';
    $actions[] = '<a href="https://blogify.ai">Website</a>';

    return $actions;
}

/**
 * Generates a Version 4 (random) UUID.
 *
 * @return string Returns a Version 4 UUID.
 */
function v4uuid(): string
{
    $a = str_pad(dechex(random_int(0x0000_0000, 0xffff_ffff)), '0', STR_PAD_LEFT);
    $b = str_pad(dechex(random_int(0x0000, 0xffff)), '0', STR_PAD_LEFT);
    $c = dechex(random_int(0x4000, 0x4fff));
    $d = dechex(random_int(0x8000, 0xbfff));
    $e = str_pad(dechex(random_int(0x0000_0000_0000, 0xffff_ffff_ffff)), '0', STR_PAD_LEFT);
    return "$a$b$c$d$e";
}

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

function create_blogify_api_endpoint()
{
    register_rest_route('blogify/v1', '/create-post', array(
        'methods' => 'POST', // Allow POST requests
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

 function create_post_callback($request)
 {
 
     if ($request->get_param('client_secret') !== get_option('blogify_client_secret')) {
         return new WP_Error('error', 'Client secret mismatch', array('status' => 403));
     }
 
     // Create a new post
     $post_data = array(
         'post_title' => $request->get_param('title'),
         'post_content' => $request->get_param('content'),
         'post_status' => $request->get_param('status'),
         'tags_input' => $request->get_param('keywords'),
         'post_type' => 'post', // You can use other post types as well
         'post_excerpt' => $request->get_param('summary'),
     );
 
     $post_id = wp_insert_post($post_data);
 
     if (is_wp_error($post_id)) {
         return new WP_Error('error', 'Failed to create post' . $post_id->get_error_message(), array('status' => 500));
     }
 
     if ($request->get_param('image_url')) {
         $image = media_sideload_image($request->get_param('image_url'), $post_id, null, 'id');
         set_post_thumbnail($post_id, $image);

    if ($request->get_param( 'blog_id' )) {
        add_post_meta( $post_id, 'blog_id', $request->get_param( 'blog_id' ), true);
    }
    
     }
     return array('message' => 'Post created successfully', 'blog_link' => get_permalink($post_id));
 
 }

const blogify_settings_slug = 'blogify-settings';
const blogify_dashboard_slug = 'blogify-dashboard';
const blogify_settings_group = 'blogify_settings_group';

/**
 * Adds  submenu pages for Blogify in the WordPress admin panel.
 *
 * Creates a submenu page under Dashboard to access Blogify Dashboard.
 * Creates a submenu page under Settings to access Blogify Settings.
 *
 * @since 1.0
 * *
 * @return void
 */

function add_blogify_menu_pages()
{
    add_dashboard_page(
        'Blogify Dashboard 📝', // Page Title
        'Blogify 📝', // Menu Title
        'manage_options', // Capability (who can access)
        blogify_dashboard_slug, // Menu Slug
        'blogify_dashboard_callback', // Callback function to display settings
    );
    add_options_page(
        'Blogify Settings 📝', // Page Title
        'Blogify 📝', // Menu Title
        'manage_options', // Capability (who can access)
        blogify_settings_slug, // Menu Slug
        'blogify_settings_callback', // Callback function to display settings
    );

}
add_action('admin_menu', 'add_blogify_menu_pages');

function blogify_dashboard_callback()
{
    $baseUrl = 'https://test.blogify.ai';
    $token = PixelShadow\Blogify\API\login('https://testapi.blogify.ai', get_option('blogify_email'), get_option('blogify_password'));
    $user = PixelShadow\Blogify\API\get_user_details('https://testapi.blogify.ai', $token);
    $dashboard_url = $baseUrl . "/dashboard?token=" . $token;
    $name = $user['name'];
    $subscriptionStatus = ucfirst($user['subscriptionStatus']);
    $subscriptionPlan = implode('-', array_map(fn($word) => ucfirst(strtolower($word)), explode('_', $user['subscriptionPlan'])));
    $credits = $user['credits'];

    $style = <<<EOD
        <style>
        .card {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .profile {
            display: flex;
            justify-content: space-between; /* Align items on the main axis */
            align-items: center; /* Align items on the cross axis */
          }

        .profile h1,
        .profile button {
        display: inline-block;
        }

        .profile button {
        margin-left: auto; /* Push the button to the right */
        }

        .profile h1 {
        flex: 1;
        }

        .info,
        .posts {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 15px;
        }

        .info > div,
        .posts > li {
        margin-right: 15px;
        flex: 1;
        }

        .info span {
        font-weight: bold;
        }

        .posts {
        border-top: 1px solid #ddd;
        padding-top: 10px;
        }

        .posts li {
        list-style: none;
        }
        </style>
        EOD;

    $html = <<<EOD
        <div class="wrap">
        <div class="card profile">
          <h1>Blogify Dashboard</h1>
          <a href="$dashboard_url" target="_blank" style="text-decoration: none;"> <button class="button button-primary">Open Dashboard</button> </a>
        </div>
          <div class="card">
            <h2>User Profile</h2>
            <div class="info">
              <div><span>Name:</span> $name</div>
              <div><span>Subscription:</span> $subscriptionStatus ($subscriptionPlan)</div>
              <div><span>Remaining Balance:</span> $credits</div>
            </div>
          </div>
          <div class="card posts">
            <h3>Your Posts</h3>
          </div>
          <div class="posts">
            <ul>
              <li>This is the first post title</li>
              <li>This is the second post title, even longer</li>
              <li>A shorter post title here</li>
            </ul>
          </div>
          </div>
      EOD;

    echo $style . $html;

}

/**
 * Callback function to display Blogify dashboard.
 *
 * Displays content on the Blogify Dashboard page in the admin panel.
 *
 * @since 1.0
 *
 * @return void
 */

function blogify_settings_callback()
{
    ?>
        <div class="wrap">
            <h2>Blogify Settings</h2>
            <form method="post" action="options.php">
                <?php
settings_fields(blogify_settings_group); // Use the settings group name
    do_settings_sections(blogify_settings_slug); // Use the menu slug
    submit_button(); // Display the submit button
    ?>
            </form>
        </div>
        <?php
}

/**
 * Registers the Blogify Options
 *
 * @since 1.0
 *
 * @see register_setting()
 *
 * @return void
 */

function blogify_settings_register()
{
    register_setting(blogify_settings_group, 'blogify_password');
    register_setting(blogify_settings_group, 'blogify_email');
    add_settings_section('blogify_login_section', 'Blogify Login Credentials', 'blogify_login_section_html', blogify_settings_slug);
    add_settings_field('blogify_password', 'Blogify Password', 'blogify_password_field_callback', blogify_settings_slug, 'blogify_login_section');
    add_settings_field('blogify_email', 'Blogify Email', 'blogify_email_field_callback', blogify_settings_slug, 'blogify_login_section');
}
add_action('admin_init', 'blogify_settings_register');

/**
 * Callback function to display information in the Blogify Settings section.
 *
 * Displays contextual information within the Blogify Settings section.
 *
 * @since 1.0
 *
 * @return void
 */

function blogify_login_section_html()
{
    echo 'These the login credentials used by the plugin to connect to Blogify.';
}

function blogify_email_field_callback()
{
    echo '<input class="regular-text" type="text" name="blogify_email" value="' . esc_attr(get_option('blogify_email')) . '" />';
}

function blogify_password_field_callback()
{
    $input = '<input type="password" value="' . esc_attr(get_option('blogify_password')) . '" name="blogify_password" id="blogify_password" class="regular-text">';
    $visibility_button = <<<EOD
    <span class="wp-pwd">
    <button type="button" class="button wp-hide-pw hide-if-no-js" id="blogify_password-toggle"
        aria-label="Toggle password visibility"
        aria-pressed="false"
    >
        <span class="dashicons dashicons-visibility" aria-hidden="false"></span>
    </button>
    </span>
    <script>
    document.getElementById("blogify_password-toggle").addEventListener("click", function() {
    const passwordInput = document.getElementById("blogify_password");
    const visibilityIcon = this.querySelector('.dashicons');

    if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    visibilityIcon.classList.replace('dashicons-visibility', 'dashicons-hidden');
    } else {
    passwordInput.type = 'password';
    visibilityIcon.classList.replace('dashicons-hidden', 'dashicons-visibility');
    }
    });
    </script>
    EOD;
    echo $input . $visibility_button;
}
