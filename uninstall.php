<?php
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}
delete_option('blogify_client_secret');
delete_option('blogify_display_admin_menu_pages');