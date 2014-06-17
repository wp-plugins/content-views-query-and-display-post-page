<?php
/**
 * HTML output for specific View types
 *
 * @package   PT_Content_Views
 * @author    Palace Of Themes <palaceofthemes@gmail.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Palace Of Themes
 */

if ( ! class_exists( 'PT_CV_Html_ViewType' ) ) {

	/**
	 * @name PT_CV_Html_ViewType
	 * @todo List of functions relates to View type output
	 */
	class PT_CV_Html_ViewType {

		/**
		 * Wrap content of Grid type
		 *
		 * @param array $content_items The array of Raw HTML output (is not wrapped) of each item
		 * @param array $dargs         The array of Display settings
		 * @param array $content       The output array
		 *
		 * @return array Array of rows, each row contains columns
		 */
		static function grid_wrapper( $content_items, $dargs, &$content ) {

			// -- Get column span
			$columns = ( (int) $dargs['number-columns'] < count( $content_items ) ) ? (int) $dargs['number-columns'] : count( $content_items );

			if ( ! $columns ) {
				$columns = 1;
			}

			$span_width_last = $span_width = (int) ( 12 / $columns );

			// Get span for the last column on row
			if ( 12 % $columns ) {
				$span_width_last = $span_width + ( 12 % $columns );
			}

			// Get span class
			$span_class = apply_filters( PT_CV_PREFIX_ . 'span_class', 'col-md-' );

			// -- Row output

			// Get wrapper class of a row
			$row_classes = apply_filters( PT_CV_PREFIX_ . 'row_class', array( 'row', PT_CV_PREFIX . 'row' ) );
			$row_class   = implode( ' ', $row_classes );

			// Split items to rows
			$columns_item = array_chunk( $content_items, $columns );

			// Get HTML of each row
			foreach ( $columns_item as $items_per_row ) {
				$row_html = array();

				foreach ( $items_per_row as $idx => $content_item ) {
					$_span_width = ( $idx == count( $items_per_row ) - 1 ) ? $span_width_last : $span_width;

					// Wrap content of item
					$row_html[] = PT_CV_Html::content_item_wrap( $content_item, $span_class . $_span_width, $dargs );
				}

				$content[] = sprintf( '<div class="%1$s">%2$s</div>', esc_attr( $row_class ), implode( "\n", $row_html ) );
			}
		}

		/**
		 * Wrap content of Collapsible List type
		 *
		 * @param array $content_items The array of Raw HTML output (is not wrapped) of each item
		 * @param array $dargs         The array of Display settings
		 * @param array $content       The output array
		 *
		 * @return string Collapsible list, wrapped in a "panel-group" div
		 */
		static function collapsible_wrapper( $content_items, $dargs, &$content ) {

			// Generate random id for the wrapper of Collapsible list
			$random_id = PT_CV_Functions::string_random();

			$collapsible_list = array();
			foreach ( $content_items as $idx => $content_item ) {
				// Replace class in body of collapsible item, to show one (now is the first item)
				$class        = ( $idx == 0 ) ? 'in' : '';
				$content_item = str_replace( PT_CV_PREFIX_UPPER . 'CLASS', $class, $content_item );

				// Replace id in {data-parent="#ID"} of each item by generated id
				$collapsible_list[] = str_replace( PT_CV_PREFIX_UPPER . 'ID', $random_id, $content_item );
			}

			// Data attribute
			$open_multiple = ( isset( $dargs['view-type-settings']['open-multiple'] ) && $dargs['view-type-settings']['open-multiple'] == 'yes' ) ? 'data-multiple-open="yes"' : '';

			// Collapsible wrapper class
			$wrapper_class = apply_filters( PT_CV_PREFIX_ . 'wrapper_collapsible_class', 'panel-group' );

			$output = sprintf( '<div class="%s" id="%s" %s>%s</div>', esc_attr( $wrapper_class ), esc_attr( $random_id ), $open_multiple, balanceTags( implode( "\n", $collapsible_list ) ) );

			$content[] = $output;
		}

		/**
		 * Wrap content of Scrollable list
		 *
		 * @param array $content_items The array of Raw HTML output (is not wrapped) of each item
		 * @param array $dargs         The array of Display settings
		 * @param array $content       The output array
		 *
		 * @return array Array of rows, each row contains columns
		 */
		static function scrollable_wrapper( $content_items, $dargs, &$content ) {

			// ID for the wrapper of scrollable list
			$wrapper_id = PT_CV_Functions::string_random();

			// Store all output of Scrollale list (indicators, content, controls)
			$scrollable_html = array();

			$scrollable_content_data = self::scrollable_content( $content_items, $dargs );
			$count_slides            = $scrollable_content_data['count_slides'];
			$scrollable_content      = $scrollable_content_data['scrollable_content'];

			// Js code
			$interval = apply_filters( PT_CV_PREFIX_ . 'scrollable_interval', 'false', $dargs );
			$js       = "$('#$wrapper_id').carousel({ interval : $interval })";

			$scrollable_html[] = PT_CV_Html::inline_script( $js );

			// Indicator html
			$show_indicator    = isset( $dargs['view-type-settings']['indicator'] ) ? $dargs['view-type-settings']['indicator'] : 'yes';
			$scrollable_html[] = self::scrollable_indicator( ( $show_indicator == 'yes' ) ? 1 : 0, $wrapper_id, $count_slides );

			// Content html
			$scrollable_html[] = $scrollable_content;

			// Control html
			$show_navigation   = isset( $dargs['view-type-settings']['navigation'] ) ? $dargs['view-type-settings']['navigation'] : 'yes';
			$scrollable_html[] = self::scrollable_control( ( $show_navigation == 'yes' ) ? 1 : 0, $wrapper_id, $count_slides );

			// Get wrapper class scrollable
			$scrollable_class = apply_filters( PT_CV_PREFIX_ . 'scrollable_class', 'carousel slide' );
			$content[]        = sprintf( '<div id="%s" class="%s" data-ride="carousel">%s</div>', esc_attr( $wrapper_id ), esc_attr( $scrollable_class ), implode( "\n", $scrollable_html ) );
		}

		/**
		 * HTML output of item in Scrollable List
		 *
		 * @param array $content_items The array of Raw HTML output (is not wrapped) of each item
		 * @param array $dargs         The array of Display settings
		 *
		 * @return array
		 */
		static function scrollable_content( $content_items, $dargs ) {
			// Store content of a Scrollable list
			$scrollable_content = array();

			// -- Get column span
			$columns = ( (int) $dargs['number-columns'] < count( $content_items ) ) ? (int) $dargs['number-columns'] : count( $content_items );
			$rows    = ( $dargs['number-rows'] ) ? (int) $dargs['number-rows'] : 1;

			$span_width_last = $span_width = (int) ( 12 / $columns );

			// Get span for the last column on row
			if ( 12 % $columns ) {
				$span_width_last = $span_width + ( 12 % $columns );
			}

			// Get span class
			$span_class = apply_filters( PT_CV_PREFIX_ . 'span_class', 'col-md-' );

			// -- Row output

			// Get wrapper class of a scrollable slide
			$slide_class = apply_filters( PT_CV_PREFIX_ . 'scrollable_slide_class', 'item' );

			// Get wrapper class of a row
			$row_classes = apply_filters( PT_CV_PREFIX_ . 'row_class', array( 'row', PT_CV_PREFIX . 'row' ) );
			$row_class   = implode( ' ', array_filter( $row_classes ) );

			// Split items to slide
			$slides_item = array_chunk( $content_items, $columns * $rows );

			foreach ( $slides_item as $s_idx => $slide ) {
				// Store content of a slide
				$slide_html = array();

				// Split items to rows
				$columns_item = array_chunk( $slide, $columns );

				// Get HTML of each row
				foreach ( $columns_item as $items_per_row ) {
					$row_html = array();

					foreach ( $items_per_row as $idx => $content_item ) {
						$_span_width = ( $idx == count( $items_per_row ) - 1 ) ? $span_width_last : $span_width;

						// Wrap content of item
						$row_html[] = PT_CV_Html::content_item_wrap( $content_item, $span_class . $_span_width );
					}

					$slide_html[] = sprintf( '<div class="%1$s">%2$s</div>', esc_attr( $row_class ), implode( "\n", $row_html ) );
				}

				// Show first slide
				$this_class           = $slide_class . ( ( $s_idx == 0 ) ? ' active' : '' );
				$scrollable_content[] = sprintf( '<div class="%1$s">%2$s</div>', esc_attr( $this_class ), implode( "\n", $slide_html ) );
			}

			// Get class of wrapper of content of scrollable list
			$content_class = apply_filters( PT_CV_PREFIX_ . 'scrollable_content_class', 'carousel-inner' );
			$content       = sprintf( '<div class="%s">%s</div>', esc_attr( $content_class ), implode( "\n", $scrollable_content ) );

			return array(
				'scrollable_content' => $content,
				'count_slides'       => count( $slides_item ),
			);
		}

		/**
		 * HTML output of Indicators in Scrollable
		 *
		 * @param bool   $show         Whether or not to show this element
		 * @param string $wrapper_id   The ID of wrapper of scrollable list
		 * @param int    $count_slides The amount of items
		 */
		static function scrollable_indicator( $show, $wrapper_id, $count_slides ) {
			if ( ! $show ) {
				return '';
			}

			$output = '';
			if ( $count_slides > 1 ) {
				$li = array();
				for ( $index = 0; $index < $count_slides; $index ++ ) {
					$class = ( $index == 0 ) ? 'active' : '';
					$li[]  = sprintf( '<li data-target="#%s" data-slide-to="%s" class="%s"></li>', esc_attr( $wrapper_id ), esc_attr( $index ), $class );
				}

				$output = '<ol class="carousel-indicators">' . implode( "\n", $li ) . '</ol>';
			}

			return $output;
		}

		/**
		 * HTML output of Controls in Scrollable
		 *
		 * @param bool   $show       Whether or not to show this element
		 * @param string $wrapper_id The ID of wrapper of scrollable list
		 * @param int    $count_slides The amount of items
		 */
		static function scrollable_control( $show, $wrapper_id, $count_slides ) {
			if ( ! $show ) {
				return '';
			}
			$output = '';
			if ( $count_slides > 1 ) {
				$output = sprintf(
					'<a class="left carousel-control" href="#%1$s" data-slide="prev">
						<span class="glyphicon glyphicon-chevron-left"></span>
					</a>
					<a class="right carousel-control" href="#%1$s" data-slide="next">
						<span class="glyphicon glyphicon-chevron-right"></span>
					</a>',
					esc_attr( $wrapper_id )
				);
			}

			return $output;
		}
	}

}