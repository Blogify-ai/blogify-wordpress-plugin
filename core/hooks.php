<?php
// بسم الله الرحمن الرحيم

namespace PixelShadow\Blogify\Core;
// Needed for image sideloading
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

require_once BLOGIFY_PLUGIN_DIR . 'api/index.php';
require_once BLOGIFY_PLUGIN_DIR . 'utils/uuid.php';
require_once BLOGIFY_PLUGIN_DIR . 'utils/post.php';

use function PixelShadow\Blogify\Utils\generate_uuid;
use function PixelShadow\Blogify\Utils\create_post;
use function PixelShadow\Blogify\API\blogify_validate_token;
use function PixelShadow\Blogify\API\blogify_get_access_token;
use function PixelShadow\Blogify\API\blogify_register_publish_route;
use function PixelShadow\Blogify\API\blogify_unregister_publish_route;

// WordPress classes
use WP_Error;
use WP_REST_Request;

/**
 * Register all WordPress hooks for the plugin
 * 
 * @return void
 */
function side_effects(): void {
    // Initialize client secret if not exists
    add_option(BLOGIFY_CLIENT_SECRET_OPTION_HANDLE, generate_uuid());
    register_actions_filter();
    register_settings_hooks();

    if (get_option(BLOGIFY_ACCESS_TOKEN_OPTION_HANDLE)) {
        register_rest_hooks();
        register_meta_tags_hook();
        register_style_hooks();
        if(get_option(BLOGIFY_DISPLAY_ADMIN_MENU_PAGES_HANDLE, true)) {
            register_admin_menu_hooks();
        }
    }

    register_deactivation_hooks();
}

/**
 * Register admin menu hooks for connected state
 * 
 * @return void
 */
function register_admin_menu_hooks(): void {
    add_action(
        'admin_menu',
        function () {
            add_menu_page(
                'Blogify-AI Turn Anything into A Blog!',
                'Blogify-AI 📝',
                'manage_options',
                'blogify-ai',
                fn() => require BLOGIFY_UI_PAGES_DIR . 'all-blogs.php',
                BLOGIFY_IMAGES_URL . 'icons/blogify-navigation.svg'
            );

            add_submenu_page(
                'blogify-ai',
                'Blogify Subscription',
                'Subscription',
                'manage_options',
                'blogify-subscription',
                fn() => require BLOGIFY_UI_PAGES_DIR . 'subscription.php'
            );
        }
    );
}

/**
 * Register plugin action links
 * 
 * @return void
 */
function register_actions_filter(): void
{
    add_filter(
        'plugin_action_links_' . BLOGIFY_PLUGIN_BASENAME,
        function ($actions) {
            [$format, $value] = get_option(BLOGIFY_ACCESS_TOKEN_OPTION_HANDLE) ? ['<a href="%s">Dashboard</a>', 'admin.php?page=blogify-ai'] : ['<a href="%s">Connect this site to Blogify.ai</a>', 'options-general.php?page=blogify'];
            $actions[] = sprintf(
                $format ,
                esc_url(get_admin_url(null, $value)),
            );
            return $actions;
        }
    );
}

/**
 * Register style hooks
 * 
 * @return void
 */
function register_style_hooks(): void {
    add_action(
        'admin_enqueue_scripts',
        function () {
            $styles = [
                'blogify-theme' => [ 'theme.css', [] ],
                'blogify-header' => ['header.css', ['blogify-theme']],
                'blogify-buttons' => ['button.css', ['blogify-theme']],
                'blogify-status-card' => ['status-card.css', ['blogify-theme']],
                'blogify-publish-dialog' => ['dialog.css', ['blogify-theme']],
                'blogify-blog-list' => ['blog-list.css', ['blogify-theme', 'blogify-buttons', 'blogify-publish-dialog']],
                'blogify-pagination' => ['pagination.css', ['blogify-theme', 'blogify-buttons']],
            ];

            array_walk(
                $styles,
                function ($style, $handle) {
                    [$file, $deps] = $style;       
                    wp_enqueue_style(
                        $handle,
                        BLOGIFY_CSS_URL . $file,
                        $deps,
                        BLOGIFY_VERSION
                    );
                }
            );
        }
    );
}

/**
 * Register deactivation hooks
 * 
 * @return void
 */
function register_deactivation_hooks(): void {
    add_action(
        'deactivate_' . BLOGIFY_PLUGIN_BASENAME,
        function () {
            if(get_option(BLOGIFY_ACCESS_TOKEN_OPTION_HANDLE, null)) {
            blogify_unregister_publish_route(blogify_get_access_token());
            delete_option(BLOGIFY_ACCESS_TOKEN_OPTION_HANDLE);
            }
        }
    );
}

/**
 * Register settings page hooks
 * 
 * @return void
 */
function register_settings_hooks(): void
{
    add_action(
        'admin_menu',
        fn() => add_options_page(
            'Blogify Settings',
            'Blogify-AI 📝',
            'manage_options',
            'blogify',
            function () {
                ?>
                <div class="wrap">
                    <form action='options.php' method='post'>
                        <?php
                                settings_fields('blogify');
                                do_settings_sections('blogify');
                                submit_button();
                                ?>
                    </form>
                </div>
            <?php
            },
        )
    );

    add_action('admin_init', function () {
        register_setting('blogify', BLOGIFY_ACCESS_TOKEN_OPTION_HANDLE, [
            'type' => 'string',
            'sanitize_callback' => function ($value) {
                return sanitize_text_field($value);
            },
            'show_in_rest' => false,
        ]);

        add_settings_section(
            'blogify_section',
            'Credentials',
            function () {
                $tutorial_link = BLOGIFY_CLIENT_BASEURL . 'dashboard/integrations/wordpressorg';
                ?>

            <p>Configure your Blogify.ai credentials.
            <p>
                <a target="_blank" href="<?php echo esc_url($tutorial_link); ?>">How to obtain an Access Token</a>
                <?php

            },
            'blogify'
        );

        add_settings_field(
            BLOGIFY_ACCESS_TOKEN_OPTION_HANDLE,
            'Blogify Access Token',
            function () {
                ?>
                <input style='width: 80%;' type='password' id='<?php echo esc_attr(BLOGIFY_ACCESS_TOKEN_OPTION_HANDLE); ?>' name='<?php echo esc_attr(BLOGIFY_ACCESS_TOKEN_OPTION_HANDLE); ?>' name='<?php echo esc_attr(BLOGIFY_ACCESS_TOKEN_OPTION_HANDLE); ?>'
                    value='<?php echo esc_attr(get_option(BLOGIFY_ACCESS_TOKEN_OPTION_HANDLE)); ?>'>
                <p class="description">Access Token from your Blogify account.</p>
                <?php
            },
            'blogify',
            'blogify_section',
            [
                'label_for' => BLOGIFY_ACCESS_TOKEN_OPTION_HANDLE,
            ]
        );

        add_settings_field(
            'blogify_client_secret',
            'Blogify Client Secret',
            function () {
                $client_secret = get_option(BLOGIFY_CLIENT_SECRET_OPTION_HANDLE);
                ?>
                <input style='width: 80%;' type='text' id='blogify_client_secret' value='<?php echo esc_attr($client_secret); ?>' readonly />
                <p class="description">This is your Blogify Client Secret. Keep it safe and do not share it publicly.</p>
                <?php
            },
            'blogify',
            'blogify_section',
            [
                'label_for' => 'blogify_client_secret',
            ]
        );
        
        // Register the display option and create its own settings section
        register_setting('blogify', BLOGIFY_DISPLAY_ADMIN_MENU_PAGES_HANDLE, [
            'type' => 'boolean',
            'sanitize_callback' => function ($value) {
                return (bool) $value;
            },
            'default' => true,
            'show_in_rest' => false,
        ]);

        add_settings_section(
            'blogify_display_section',
            'Display Settings',
            function () {
                echo '<p>Configure if Blogify admin pages are displayed in the WordPress admin menu.</p>';
            },
            'blogify'
        );

        add_settings_field(
            BLOGIFY_DISPLAY_ADMIN_MENU_PAGES_HANDLE,
            'Display Admin Menu Pages',
            function () {
                $option = get_option(BLOGIFY_DISPLAY_ADMIN_MENU_PAGES_HANDLE, true);
                ?>
                <input type='checkbox' id='<?php echo esc_attr(BLOGIFY_DISPLAY_ADMIN_MENU_PAGES_HANDLE); ?>' name='<?php echo esc_attr(BLOGIFY_DISPLAY_ADMIN_MENU_PAGES_HANDLE); ?>' <?php checked($option, true); ?> />
                <p class="description">Uncheck to hide Blogify-AI admin menu pages.</p>
                <?php
            },
            'blogify',
            'blogify_display_section',
            [
                'label_for' => BLOGIFY_DISPLAY_ADMIN_MENU_PAGES_HANDLE,
            ]
        );

    });

    add_filter(
        'pre_update_option_' . BLOGIFY_ACCESS_TOKEN_OPTION_HANDLE,
        function ($value, $old_value) {
            if ( $old_value !== $value) {
                blogify_register_publish_route($value);
            }
            return $value;
        },
        10,
        3
    );
}

/**
 * Register REST API endpoints
 * 
 * @return void
 */
function register_rest_hooks(): void {
    
    // Register create post endpoint
    add_action(
        'rest_api_init',
        fn() =>
        register_rest_route('blogify/v1', '/create-post', [
            'methods' => ['POST'],
            'permission_callback' => fn(WP_REST_Request $request) => $request->get_param('client_secret') === get_option(BLOGIFY_CLIENT_SECRET_OPTION_HANDLE),
            'callback' => function (WP_REST_Request $request) {
                $post_id = create_post([
                    'title' => $request->get_param('title'),
                    'content' => $request->get_param('content'),
                    'blog_id' => $request->get_param('blog_id'),
                    'status' => $request->get_param('status'),
                    'author' => $request->get_param('author'),
                    'categories' => $request->get_param('categories'), 
                    'meta_tags' => $request->get_param('meta_tags'),
                    'meta_description' => $request->get_param('meta_description'),
                    'image_url' => $request->get_param('image_url'),
                    'keywords' => $request->get_param('keywords'),
                    'summary' => $request->get_param('summary')
                ]);
                return ['message' => 'Post created successfully', 'blog_link' => get_permalink($post_id)];
            }
        ])
    );

    // Register upload image endpoint
    add_action('rest_api_init',
        fn() => register_rest_route('blogify/v1', '/upload-image', [
            'methods' => ['POST'],
            'permission_callback' => fn(WP_REST_Request $request) => $request->get_param('client_secret') === get_option(BLOGIFY_CLIENT_SECRET_OPTION_HANDLE),
            'callback' => function (WP_REST_Request $request) {
                $image_url = $request->get_param('image');

                if (empty($image_url)) {
                    return new WP_Error('error', 'image URL is required', ['status' => 400]);
                }

                $image_src = media_sideload_image(sanitize_url($image_url), 0, null, 'src');

                if (is_wp_error($image_src)) {
                    return new WP_Error('error', 'Failed to upload image: ' . $image_src->get_error_message(), ['status' => 500]);
                }

                return ['src' => $image_src];
            },
        ])
    );

    // Register users and categories endpoint
    add_action('rest_api_init',
        fn() => register_rest_route('blogify/v1', '/site-info', [
            'methods' => ['GET'],
            'permission_callback' => fn(WP_REST_Request $request) => $request->get_param('client_secret') === get_option(BLOGIFY_CLIENT_SECRET_OPTION_HANDLE),
            'callback' => function () {
                $users = get_users(['fields' => ['ID', 'display_name', 'user_email']]);
                $categories = get_categories(['hide_empty' => false]);
                
                return [
                    'users' => array_map(fn($user) => [
                        'id' => $user->ID,
                        'name' => $user->display_name,
                        'email' => $user->user_email
                    ], $users),
                    'categories' => array_map(fn($cat) => [
                        'id' => $cat->term_id,
                        'name' => $cat->name,
                        'slug' => $cat->slug
                    ], $categories)
                ];
            }
        ])
    );
}

function register_meta_tags_hook(): void {
    add_action(
        'wp_head',
        function () {
            global $post;
            if (is_page() || is_single()) {
                $meta_description = get_post_meta(get_queried_object_id(), 'blogify_meta_description', true);
                $meta_tags = get_post_meta(get_queried_object_id(), 'blogify_meta_tags', true);

                if (!empty($meta_description)) {
                    printf(
                        '<meta name="description" content="%s" />' . "\n",
                        esc_attr(trim($meta_description)),
                    );
                }

                if (!empty($meta_tags)) {
                    printf(
                        '<meta name="keywords" content="%s" />' . "\n",
                        esc_attr(trim(implode(',', $meta_tags))),
                    );
                }
            }
        }
    );
}