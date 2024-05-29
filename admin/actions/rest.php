// Needed for image sideloading
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';



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
        'meta_input' => [
            'blogify_blog_id' => $request->get_param('blog_id'),
            'blogify_meta_tags' => $request->get_param('meta_tags'),
            'blogify_meta_description' => $request->get_param('meta_description'),
        ],
    );

    $post_id = wp_insert_post($post_data);

    if (is_wp_error($post_id)) {
        return new WP_Error('error', 'Failed to create post' . $post_id->get_error_message(), array('status' => 500));
    }

    if ($request->get_param('image_url')) {
        $image = media_sideload_image($request->get_param('image_url'), $post_id, null, 'id');
        set_post_thumbnail($post_id, $image);

    }
    return array('message' => 'Post created successfully', 'blog_link' => get_permalink($post_id));

}

function wp_add_meta_html()
{
    global $post;
    if (is_page() || is_single()) {
        $meta_description = get_post_meta(get_queried_object_id(), 'blogify_meta_description', true);
        $meta_tags = get_post_meta(get_queried_object_id(), 'blogify_meta_tags', true);

        if (!empty($meta_description)) {
            printf(
                '<meta name="description" content="%s" />',
                esc_attr(trim($meta_description))
            );
        }

        if (!empty($meta_tags)) {
            printf(
                '<meta name="keywords" content="%s" />',
                esc_attr(trim(implode(',', $meta_tags)))
            );
        }
    }
}

add_action('wp_head', 'wp_add_meta_html');