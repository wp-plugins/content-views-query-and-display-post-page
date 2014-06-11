<?php
/**
 * Uninstall the plugin
 *
 * @package   PT_Content_Views
 * @author    Palace Of Themes <palaceofthemes@gmail.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Palace Of Themes
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Include library files
include_once( plugin_dir_path( __FILE__ ) . 'includes/defines.php' );

// Delete all posts of PT View post type ?

