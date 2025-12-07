<?php
// بسم الله الرحمن الرحيم
/**
 * Blogify-AI
 *
 * A functional WordPress plugin for integrating with Blogify.ai
 * This plugin follows functional programming principles and avoids global state.
 *
 * @package           Blogify-AI
 * @author            Fida Waseque Choudhury
 * @copyright         PixelShadow
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Blogify-AI
 * Plugin URI:        https://blogify.ai/
 * Description:       Seamlessly publish AI-generated blog posts from Blogify.ai to your WordPress site with ease, enhancing content management and SEO optimization in a few clicks.
 * Version:           1.3.2
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            PixelShadow
 * Developer:         Fida Waseque Choudhury
 * Developer URI:     https://www.linkedin.com/in/u3kkasha/
 * Text Domain:       blogify-ai
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace PixelShadow\Blogify;

// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}

// Constants
DEFINE('BLOGIFY_SERVER_BASEURL',  "https://api.blogify.ai/");
DEFINE('BLOGIFY_CLIENT_BASEURL',  "https://blogify.ai/");	

DEFINE('BLOGIFY_VERSION', '1.3.2');
DEFINE('BLOGIFY_PLUGIN_BASENAME', plugin_basename(__FILE__));

DEFINE('BLOGIFY_PLUGIN_DIR', plugin_dir_path(__FILE__));
DEFINE('BLOGIFY_UI_PAGES_DIR', BLOGIFY_PLUGIN_DIR . 'ui/');
DEFINE('BLOGIFY_UI_COMPONENTS_DIR', BLOGIFY_PLUGIN_DIR . 'ui/components');

DEFINE('BLOGIFY_ASSETS_URL', plugins_url('/assets/', __FILE__));
DEFINE('BLOGIFY_IMAGES_URL', BLOGIFY_ASSETS_URL . 'images/');
DEFINE('BLOGIFY_CSS_URL', BLOGIFY_ASSETS_URL . 'css/');

DEFINE('BLOGIFY_ACCESS_TOKEN_OPTION_HANDLE','blogify_access_token');
DEFINE('BLOGIFY_CLIENT_SECRET_OPTION_HANDLE', 'blogify_client_secret');
DEFINE('BLOGIFY_DISPLAY_ADMIN_MENU_PAGES_HANDLE', 'blogify_display_admin_menu_pages');

// Load core functionality
require_once __DIR__ . '/core/hooks.php';

use function PixelShadow\Blogify\Core\side_effects;

// Register hooks and create options
side_effects();
