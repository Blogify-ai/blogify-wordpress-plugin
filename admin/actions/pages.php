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
        'Blogify Dashboard ', // Page Title
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
    // $baseUrl = 'https://test.blogify.ai';
    // $token = PixelShadow\Blogify\API\login('https://testapi.blogify.ai', get_option('blogify_email'), get_option('blogify_password'));
    // $user = PixelShadow\Blogify\API\get_user_details('https://testapi.blogify.ai', $token);
    // $dashboard_url = $baseUrl . "/dashboard?token=" . $token;
    // $name = $user['name'];
    // $subscriptionStatus = ucfirst($user['subscriptionStatus']);
    // $subscriptionPlan = implode('-', array_map(fn($word) => ucfirst(strtolower($word)), explode('_', $user['subscriptionPlan'])));
    // $credits = $user['credits'];

    // $style = <<<EOD
    //     <style>
    //     .card {
    //         background-color: #f5f5f5;
    //         padding: 20px;
    //         border-radius: 5px;
    //         box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    //         width: 100%;
    //     }

    //     .profile {
    //         display: flex;
    //         justify-content: space-between; /* Align items on the main axis */
    //         align-items: center; /* Align items on the cross axis */
    //       }

    //     .profile h1,
    //     .profile button {
    //     display: inline-block;
    //     }

    //     .profile button {
    //     margin-left: auto; /* Push the button to the right */
    //     }

    //     .profile h1 {
    //     flex: 1;
    //     }

    //     .info,
    //     .posts {
    //     display: flex;
    //     flex-wrap: wrap;
    //     margin-bottom: 15px;
    //     }

    //     .info > div,
    //     .posts > li {
    //     margin-right: 15px;
    //     flex: 1;
    //     }

    //     .info span {
    //     font-weight: bold;
    //     }

    //     .posts {
    //     border-top: 1px solid #ddd;
    //     padding-top: 10px;
    //     }

    //     .posts li {
    //     list-style: none;
    //     }
    //     </style>
    //     EOD;

    // $html = <<<EOD
    //     <div class="wrap">
    //     <div class="card profile">
    //       <h1>Blogify Dashboard</h1>
    //       <a href="$dashboard_url" target="_blank" style="text-decoration: none;"> <button class="button button-primary">Open Dashboard</button> </a>
    //     </div>
    //       <div class="card">
    //         <h2>User Profile</h2>
    //         <div class="info">
    //           <div><span>Name:</span> $name</div>
    //           <div><span>Subscription:</span> $subscriptionStatus ($subscriptionPlan)</div>
    //           <div><span>Remaining Balance:</span> $credits</div>
    //         </div>
    //       </div>
    //       <div class="card posts">
    //         <h3>Your Posts</h3>
    //       </div>
    //       <div class="posts">
    //         <ul>
    //           <li>This is the first post title</li>
    //           <li>This is the second post title, even longer</li>
    //           <li>A shorter post title here</li>
    //         </ul>
    //       </div>
    //       </div>
    //   EOD;

    // echo $style . $html;

    if (get_option("blogify_client_secret") == "") {
        update_option('blogify_client_secret', v4uuid());
    }
    $env = parse_ini_file('.env');
    $blogify_client_baseurl = $env['BLOGIFY_CLIENT_BASEURL'];

    $option = get_option('blogify_client_secret');
    $url_with_secret = site_url() . "?secret=" . $option;
    $url_to_open = $blogify_client_baseurl . "/login?redirectTo=/dashboard/settings/wordpressorg-connect?wordpressorg=" . $url_with_secret;
    $button = '<input type="button" class="button button-primary" value="Connect" onclick="window.open( \'' . $url_to_open . '\', \'_blank\');" />';
    echo '<div class="wrap">' . $button . '</div>';

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
