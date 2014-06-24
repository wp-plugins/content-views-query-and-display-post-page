<?php
/**
 * List all Content Views
 *
 * @package   PT_Content_Views
 * @author    Palace Of Themes <palaceofthemes@gmail.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Palace Of Themes
 */

// Redirect to edit.php page of Content Views post type
wp_redirect( admin_url( 'edit.php?post_type=' . PT_CV_POST_TYPE ) );
exit;