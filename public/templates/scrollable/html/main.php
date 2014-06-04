<?php
/**
 * Layout Name: Scrollable List
 *
 * @package   PT_Content_Views_Admin
 * @author    Palace Of Themes <palaceofthemes@gmail.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Palace Of Themes
 */

$html = array();

$ex_cap_cls = PT_CV_PREFIX . 'cap-w-img';;

if ( ! empty( $fields_html['thumbnail'] ) ) {
	// Thumbnail html
	$html[] = $fields_html['thumbnail'];
	unset( $fields_html['thumbnail'] );
} else {
	$ex_cap_cls = PT_CV_PREFIX . 'cap-wo-img';
}

// Other fields html
$others_html = implode( "\n", $fields_html );

// Get wrapper class of caption
$caption_class = apply_filters( PT_CV_PREFIX_ . 'scrollable_caption_class', array( 'carousel-caption', $ex_cap_cls ) );
$html[]        = sprintf( '<div class="%s">%s</div>', esc_attr( implode( ' ', array_filter( $caption_class ) ) ), balanceTags( $others_html ) );

echo balanceTags( implode( "\n", $html ) );