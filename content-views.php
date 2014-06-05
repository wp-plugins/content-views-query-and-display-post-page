<?php
/**
 * @package   PT_Content_Views
 * @author    Palace Of Themes <palaceofthemes@gmail.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Palace Of Themes
 *
 * @wordpress-plugin
 * Plugin Name:       Content Views
 * Plugin URI:        http://wordpress.org/plugins/content-views-query-and-display-post-page/
 * Description:       Query and display <strong>posts, pages</strong> in awesome layouts (<strong>grid, scrollable list, collapsible list</strong>) easier than ever, without coding!
 * Version:           1.1.1
 * Author:            Palace Of Themes
 * Author URI:        http://profiles.wordpress.org/pt-guy
 * Text Domain:       content-views
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/<owner>/<repo>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/*
 * Define Constant
 */
define( 'PT_CV_VERSION', '1.1.1' );
define( 'PT_CV_FILE', __FILE__ );
include_once( plugin_dir_path( __FILE__ ) . 'includes/defines.php' );

/*
 * Include other library files (name ASC)
 */
include_once( plugin_dir_path( __FILE__ ) . 'includes/assets.php' );
include_once( plugin_dir_path( __FILE__ ) . 'includes/functions.php' );
include_once( plugin_dir_path( __FILE__ ) . 'includes/hooks.php' );
include_once( plugin_dir_path( __FILE__ ) . 'includes/html-viewtype.php' );
include_once( plugin_dir_path( __FILE__ ) . 'includes/html.php' );
include_once( plugin_dir_path( __FILE__ ) . 'includes/settings.php' );
include_once( plugin_dir_path( __FILE__ ) . 'includes/update.php' );
include_once( plugin_dir_path( __FILE__ ) . 'includes/values.php' );

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

/*
 * the plugin's class file
 */
include_once( plugin_dir_path( __FILE__ ) . 'public/content-views.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'PT_Content_Views', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'PT_Content_Views', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'PT_Content_Views', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * the plugin's admin file
 */
if ( is_admin() ) {

	// Require Admin side functions
	include_once( plugin_dir_path( __FILE__ ) . 'admin/content-views-admin.php' );
	add_action( 'plugins_loaded', array( 'PT_Content_Views_Admin', 'get_instance' ) );

	// Require PT Options framework
	include_once( plugin_dir_path( __FILE__ ) . 'admin/includes/options.php' );
}

/**
 * Common settings
 */
// Support for post thumbnails
add_theme_support( 'post-thumbnails' );

// Enable shortcode in content
add_filter( 'the_content', 'do_shortcode', 11 );

// Enable shortcodes in text widgets.
add_filter( 'widget_text', 'do_shortcode' );