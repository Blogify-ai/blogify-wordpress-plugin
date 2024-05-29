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

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
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

add_action(
    'admin_menu', fn() => add_menu_page(
        'Blogify-AI Turn Anything into A Blog!',
        'Blogify-AI 📝',
        'manage_options',
        'blogify-ai',
        fn() => require_once plugin_dir_path(__FILE__) . 'admin/ui/dashboard.php',
        plugins_url('/admin/assets/images/icons/blogify-icon.svg', __FILE__), 
    )
);


add_action(
    'admin_menu',
    fn() => add_submenu_page(
        'blogify-ai',
        'All Blogs on Blogify',
        'All Blogs',
        'manage_options',
        'blogify-all-blogs',
        fn() => require_once plugin_dir_path(__FILE__). 'admin/ui/all-blogs.php',
    )
);


add_action(
    'admin_menu',
    fn() => add_submenu_page(
        'blogify-ai',
        'Blogify Subscription',
        'Subscription',
        'manage_options',
        'blogify-subscription',
        fn() => require_once plugin_dir_path(__FILE__). 'admin/ui/subscription.php',
    )
);

add_action(
    'admin_enqueue_scripts', function() { 
        wp_enqueue_style(
            'blogify-theme',
            plugins_url('/admin/assets/css/theme.css', __FILE__)
        );
        wp_enqueue_style(
            'blogify-header',
            plugins_url('/admin/assets/css/header.css', __FILE__),
            ['blogify-theme']
        );
        wp_enqueue_style(
            'blogify-buttons',
            plugins_url('/admin/assets/css/button.css', __FILE__),
            ['blogify-theme']
        );
        wp_enqueue_style(
            'blogify-status-card',
            plugins_url('/admin/assets/css/status-card.css', __FILE__),
            ['blogify-theme']
        );
        wp_enqueue_style(
            'blogify-blog-list',
            plugins_url('/admin/assets/css/blog-list.css', __FILE__),
            ['blogify-theme', 'blogify-buttons']
        );
        wp_enqueue_style(
            'blogify-category-list',
            plugins_url('/admin/assets/css/category-list.css', __FILE__),
            ['blogify-theme', 'blogify-buttons']
        );
        wp_enqueue_style(
            'blogify-pagination',
            plugins_url('/admin/assets/css/pagination.css', __FILE__),
            ['blogify-theme', 'blogify-buttons']
        );
        wp_enqueue_style(
            'blogify-redirect',
            plugins_url('/admin/assets/css/redirect.css', __FILE__),
            ['blogify-theme', 'blogify-buttons']
        );        
        }
);