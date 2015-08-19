<?php

/**
 * Defines common constant
 *
 * @package   PT_Content_Views
 * @author    PT Guy <palaceofthemes@gmail.com>
 * @license   GPL-2.0+
 * @link      http://www.contentviewspro.com/
 * @copyright 2014 PT Guy
 */
define( 'PT_CV_DOMAIN', 'content-views' );
define( 'PT_CV_PREFIX', 'pt-cv-' );
define( 'PT_CV_PREFIX_', 'pt_cv_' );
define( 'PT_CV_PREFIX_UPPER', 'PT_CV_' );

// Custom post type
define( 'PT_CV_POST_TYPE', 'pt_view' );

// Options
define( 'PT_CV_OPTION_VERSION', PT_CV_PREFIX_ . 'version' );
define( 'PT_CV_OPTION_NAME', PT_CV_PREFIX_ . 'options' );

// Custom fields
define( 'PT_CV_META_ID', '_' . PT_CV_PREFIX_ . 'id' );
define( 'PT_CV_META_SETTINGS', '_' . PT_CV_PREFIX_ . 'settings' );
define( 'PT_CV_META_VIEW_COUNT', '_' . PT_CV_PREFIX_ . 'view_count' );

// Public assets directory
define( 'PT_CV_PUBLIC_ASSETS', plugin_dir_path( PT_CV_FILE ) . 'public/assets/' );

// Public assets uri
define( 'PT_CV_PUBLIC_ASSETS_URI', plugins_url( 'public/assets/', PT_CV_FILE ) );

// View type directory (HTML + CSS + JS)
define( 'PT_CV_VIEW_TYPE_OUTPUT', plugin_dir_path( PT_CV_FILE ) . 'public/templates/' );

// Enable/Disable debug mode
define( 'PT_CV_DEBUG', false );
