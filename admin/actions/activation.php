// Add extra links on plugin page
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'add_blogify_dashboard_and_settings_links');

function add_blogify_dashboard_and_settings_links($actions)
{
    $actions[] = '<a href="' . esc_url(get_admin_url(null, 'options-general.php?page=blogify-settings')) . '">Settings</a>';
    $actions[] = '<a href="' . esc_url(get_admin_url(null, 'admin.php?page=blogify-dashboard')) . '">Dashboard</a>';
    $actions[] = '<a href="https://blogify.ai">Website</a>';

    return $actions;
}