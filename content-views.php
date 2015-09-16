<?php

/**
 * @package   PT_Content_Views
 * @author    PT Guy <palaceofthemes@gmail.com>
 * @license   GPL-2.0+
 * @link      http://www.contentviewspro.com/
 * @copyright 2014 PT Guy
 *
 * @wordpress-plugin
 * Plugin Name:       Content Views
 * Plugin URI:        http://wordpress.org/plugins/content-views-query-and-display-post-page/
 * Description:       Query and display <strong>posts, pages</strong> in awesome layouts (<strong>grid, scrollable list, collapsible list</strong>) easier than ever, without coding!
 * Version:           1.6.7
 * Author:            PT Guy
 * Author URI:        http://profiles.wordpress.org/pt-guy
 * Text Domain:       content-views
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/<owner>/<repo>
 */
// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
	die;
}
/*
 * Define Constant
 */
define( 'PT_CV_VERSION', '1.6.7' );
define( 'PT_CV_FILE', __FILE__ );
$pt_cv_path = plugin_dir_path( __FILE__ );
include_once( $pt_cv_path . 'includes/defines.php' );

/*
 * Include other library files (name ASC)
 */
include_once( $pt_cv_path . 'includes/assets.php' );
include_once( $pt_cv_path . 'includes/functions.php' );
include_once( $pt_cv_path . 'includes/hooks.php' );
include_once( $pt_cv_path . 'includes/html-viewtype.php' );
include_once( $pt_cv_path . 'includes/html.php' );
include_once( $pt_cv_path . 'includes/settings.php' );
include_once( $pt_cv_path . 'includes/update.php' );
include_once( $pt_cv_path . 'includes/values.php' );

/* ----------------------------------------------------------------------------*
 * Public-Facing Functionality
 * ---------------------------------------------------------------------------- */

/*
 * the plugin's class file
 */
include_once( $pt_cv_path . 'public/content-views.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'PT_Content_Views', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'PT_Content_Views', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'PT_Content_Views', 'get_instance' ) );

/* ----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 * ---------------------------------------------------------------------------- */

/*
 * the plugin's admin file
 */
if ( is_admin() ) {

	// Require Admin side functions
	include_once( $pt_cv_path . 'admin/content-views-admin.php' );
	add_action( 'plugins_loaded', array( 'PT_Content_Views_Admin', 'get_instance' ) );

	// Require PT Options framework
	include_once( $pt_cv_path . 'admin/includes/options.php' );

	// Settings page for the plugin
	include_once( $pt_cv_path . 'admin/includes/plugin.php' );
}

/**
 * Common settings
 */
// Support for post thumbnails
add_theme_support( 'post-thumbnails' );

// Enable shortcode in content
add_filter( 'the_content', 'do_shortcode', 15 );

// Enable shortcodes in text widgets.
add_filter( 'widget_text', 'do_shortcode', 15 );
