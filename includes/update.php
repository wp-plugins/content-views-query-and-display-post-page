<?php
/**
 * Check update, do update
 *
 * @package   PT_Content_Views
 * @author    Palace Of Themes <palaceofthemes@gmail.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Palace Of Themes
 */

// Compare stored version and current version
$stored_version = get_option( PT_CV_OPTION_VERSION );
if ( $stored_version && version_compare( $stored_version, PT_CV_VERSION, '<' ) ) {
	// Do update

	// Update version
	update_option( PT_CV_OPTION_VERSION, PT_CV_VERSION );
}