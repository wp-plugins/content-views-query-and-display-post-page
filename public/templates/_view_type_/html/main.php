<?php
/**
 * Layout Name: View type
 *
 * @package   PT_Content_Views_Admin
 * @author    Palace Of Themes <palaceofthemes@gmail.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Palace Of Themes
 */

$html = array();

$layout = $dargs['layout-format'];

// Prevent the case: there are 2 columns but have not setting for thumbnail position
if ( $layout == '2-col' && ! isset( $dargs['field-settings']['thumbnail'] ) ) {
	$layout = '1-col';
}

switch ( $layout ) {
	case '1-col':
		foreach ( $fields_html as $field_html ) {
			$html[] = $field_html;
		}
		break;
	case '2-col':

		// Thumbnail html
		$thumbnail_html = $fields_html['thumbnail'];

		// Other fields html
		unset( $fields_html['thumbnail'] );
		$others_html = implode( "\n", $fields_html );

		$html[] = $thumbnail_html;
		$html[] = $others_html;

		break;
}

echo balanceTags( implode( "\n", $html ) );