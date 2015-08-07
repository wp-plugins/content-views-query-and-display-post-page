<?php

/**
 * List all Content Views
 *
 * @package   PT_Content_Views_Admin
 * @author    PT Guy <palaceofthemes@gmail.com>
 * @license   GPL-2.0+
 * @link      http://www.contentviewspro.com/
 * @copyright 2014 PT Guy
 */
// Redirect to edit.php page of Content Views post type
wp_redirect( admin_url( 'edit.php?post_type=' . PT_CV_POST_TYPE ) );
exit;
