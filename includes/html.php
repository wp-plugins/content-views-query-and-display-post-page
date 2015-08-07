<?php
/**
 * HTML output, class, id generating
 *
 * @package   PT_Content_Views
 * @author    PT Guy <palaceofthemes@gmail.com>
 * @license   GPL-2.0+
 * @link      http://www.contentviewspro.com/
 * @copyright 2014 PT Guy
 */
if ( !class_exists( 'PT_CV_Html' ) ) {

	/**
	 * @name PT_CV_Html
	 * @todo related HTML functions: Define HTML layout, Set class name...
	 */
	class PT_CV_Html {

		// Store directory of selected view_types
		static $view_type_dir	 = array();
		// Store all selected styles
		static $style			 = array();

		/**
		 * return class for Panel (Group of) group of params
		 *
		 * @return string
		 */
		static function html_panel_group_class() {
			return 'panel-group';
		}

		/**
		 * Return ID for Panel (Group of) group of params
		 *
		 * @param string $id Unique id of element
		 *
		 * @return string
		 */
		static function html_panel_group_id( $id ) {
			return 'panel-group-' . $id;
		}

		/**
		 * Return class for no animation elements
		 *
		 * @return string
		 */
		static function html_no_animation_class() {
			return PT_CV_PREFIX . 'no-animation';
		}

		/**
		 * Return class for group of params
		 *
		 * @return string
		 */
		static function html_group_class() {
			return PT_CV_PREFIX . 'group';
		}

		/**
		 * Return ID for group of params
		 *
		 * @param string $id Unique id of element
		 *
		 * @return string
		 */
		static function html_group_id( $id ) {
			return self::html_group_class() . '-' . $id;
		}

		/**
		 * Collapse HTML
		 *
		 * @param string $parent_id Id of parent element
		 * @param string $id        Unique id of element
		 * @param string $heading   Heading text
		 * @param string $content   Content
		 * @param bool   $show      Show/hide the content
		 */
		static function html_collapse_one( $parent_id, $id, $heading, $content = '',
									 $show = true ) {
			$class = $show ? 'in' : '';
			ob_start();
			?>
			<div class="panel panel-primary pt-accordion">
				<div class="panel-heading">
					<h4 class="panel-title" title="<?php _e( 'Click to toggle', PT_CV_DOMAIN ); ?>">
						<a class="pt-accordion-a" data-parent="#<?php echo esc_attr( $parent_id ); ?>" href="#<?php echo esc_attr( $id ); ?>">
							<?php echo balanceTags( $heading ); ?>
						</a>
					</h4>
					<span class="pull-right clickable"><i class="glyphicon glyphicon-minus"></i></span>
				</div>
				<div id="<?php echo esc_attr( $id ); ?>" class="panel-body <?php echo esc_attr( $class ); ?>">
					<div class="panel-body">
						<?php echo balanceTags( $content ); ?>
					</div>
				</div>
			</div>
			<?php
			return ob_get_clean();
		}

		/**
		 * HTML content of Preview box
		 */
		static function html_preview_box() {
			ob_start();
			?>
			<div class="panel panel-default collapse" id="<?php echo esc_attr( PT_CV_PREFIX ); ?>preview-box"></div>
			<div class="text-center hidden" style="position: absolute; left: 50%; top: 160px;">
				<?php echo balanceTags( self::html_loading_img() ); ?>
			</div>
			<?php
			return ob_get_clean();
		}

		/**
		 * Show loading image
		 *
		 * @param type $dimension
		 *
		 * @return type
		 */
		static function html_loading_img( $dimension = 16, $class = '' ) {
			$img = sprintf( '<img width="%1$s" height="%1$s" class="%2$s" alt="%3$s" src="%4$s" /><div class="clear %5$s"></div>', esc_attr( $dimension ), esc_attr( $class ), __( 'loading', PT_CV_DOMAIN ), 'data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==', PT_CV_PREFIX . 'clear-pagination' );

			return apply_filters( PT_CV_PREFIX_ . 'loading_image', $img );
		}

		/**
		 * Html output for button
		 *
		 * @param string $style Bootstrap type of button
		 * @param string $text  Text of button
		 * @param string $class Class of button
		 * @param string $size  Size of button
		 *
		 * @return string
		 */
		static function html_button( $style, $text = 'Button', $class = '', $size = '' ) {
			return sprintf( '<button type="button" class="btn btn-%s %s %s">%s</button>', $style, $class, $size, $text );
		}

		/**
		 * Html output for a link, but style as button
		 *
		 * @param string $link  Value for href attribute of link
		 * @param string $style Bootstrap type of button
		 * @param string $text  Text of button
		 * @param string $class Class of button
		 * @param string $size  Size of button
		 *
		 * @return string
		 */
		static function link_button( $link, $style, $text = 'Button', $class = '',
							   $size = '' ) {
			return sprintf( '<a href="%s" class="btn btn-%s %s %s">%s</a>', $link, $style, $class, $size, $text );
		}

		/**
		 * Get Output HTML of a View type
		 *
		 * @param string $view_type The view type (grid, collapse...)
		 * @param object $post      The post object
		 * @param string $style     The style of view type (main, style2...)
		 */
		static function view_type_output( $view_type, $post, $style = 'main' ) {

			$dargs	 = PT_CV_Functions::get_global_variable( 'dargs' );
			$content = NULL;

			if ( empty( $view_type ) ) {
				return $content;
			}

			// Get view type directory
			$view_type_dir = apply_filters( PT_CV_PREFIX_ . 'view_type_dir', PT_CV_VIEW_TYPE_OUTPUT, $view_type ) . $view_type;

			// Get asset directory
			$view_type_assets_dir = apply_filters( PT_CV_PREFIX_ . 'view_type_asset', $view_type_dir, $view_type );

			if ( is_dir( $view_type_dir ) ) {
				// Store view type & asset information
				self::$view_type_dir[]	 = $view_type_assets_dir;
				self::$style[]			 = $style;

				// Generate HTML output of all content fields
				$fields_html = array();
				foreach ( $dargs[ 'fields' ] as $field_name ) {
					// Get settings of fields
					$fargs = isset( $dargs[ 'field-settings' ] ) ? $dargs[ 'field-settings' ] : array();

					$fargs[ 'layout-format' ] = $dargs[ 'layout-format' ];

					// Get HTML output of field
					$item_html = self::field_item_html( $field_name, $post, $fargs );
					if ( $item_html ) {
						$fields_html[ $field_name ] = $item_html;
					}
				}

				$fields_html = apply_filters( PT_CV_PREFIX_ . 'fields_html', $fields_html, $post );

				// Get HTML content of view type, with specific style
				$file_path = apply_filters( PT_CV_PREFIX_ . 'view_type_file', $view_type_dir . '/' . 'html' . '/' . $style . '.' . 'php' );

				if ( file_exists( $file_path ) ) {
					ob_start();
					// Include, not include_once
					include $file_path;
					$content = ob_get_clean();
				}
			}

			return $content;
		}

		/**
		 * Wrap content of a item
		 *
		 * @param array  $html_item The HTML output of a item
		 * @param string $class     The extra wrapper class of a item, such as col span
		 * @param array  $post_id   The post ID
		 *
		 * @return string Full HTML output of a item
		 */
		static function content_item_wrap( $html_item, $class = '', $post_id = 0 ) {

			$dargs = PT_CV_Functions::get_global_variable( 'dargs' );

			if ( empty( $html_item ) ) {
				return '';
			}

			if ( is_array( $dargs ) ) {
				// If only show Title
				if ( isset( $dargs[ 'fields' ] ) && count( (array) $dargs[ 'fields' ] ) == 1 && $dargs[ 'fields' ][ 0 ] === 'title' ) {
					$class .= ' ' . PT_CV_PREFIX . 'only-title';
				}
			}

			// Get wrapper class of a item
			$layout		 = $dargs[ 'layout-format' ];
			$item_class	 = apply_filters( PT_CV_PREFIX_ . 'content_item_class', array( $class, PT_CV_PREFIX . 'content-item', PT_CV_PREFIX . $layout ) );

			$item_filter = apply_filters( PT_CV_PREFIX_ . 'content_item_filter_value', '', $post_id );

			// Add custom HTML for each item
			ob_start();
			do_action( PT_CV_PREFIX_ . 'item_extra_html', $post_id );
			$html_item .= ob_get_clean();

			$result = sprintf( '<div class="%s" %s>%s</div>', esc_attr( implode( ' ', $item_class ) ), $item_filter, balanceTags( $html_item ) );

			return apply_filters( PT_CV_PREFIX_ . 'before_item', '', $post_id ) . $result . apply_filters( PT_CV_PREFIX_ . 'after_item', '', $post_id );
		}

		/**
		 * Wrap content of all items
		 *
		 * @param array $content_items The array of Raw HTML output (is not wrapped) of each item
		 * @param int   $current_page  The current page
		 * @param int   $post_per_page The number of posts per page
		 * @param int   $id            ID of View
		 *
		 * @return string Full HTML output for Content View
		 */
		static function content_items_wrap( $content_items, $current_page,
									  $post_per_page, $id ) {
			global $pt_cv_glb, $pt_cv_id;
			$dargs = PT_CV_Functions::get_global_variable( 'dargs' );

			if ( empty( $content_items ) ) {
				return 'empty content_items';
			}

			// Assign as global variable
			$pt_cv_glb[ $pt_cv_id ][ 'content_items' ] = $content_items;

			$display = PT_CV_Functions::is_pagination( $dargs, $current_page );

			// 1. Before output
			$before_output = $display ? apply_filters( PT_CV_PREFIX_ . 'before_output_html', '' ) : '';

			// 2. Output content
			$content	 = array();
			$view_type	 = $dargs[ 'view-type' ];

			// Separate items by row, column
			switch ( $view_type ) {

				// Grid
				case 'grid':

					PT_CV_Html_ViewType::grid_wrapper( $content_items, $content );

					break;

				// Collapsible List
				case 'collapsible':

					PT_CV_Html_ViewType::collapsible_wrapper( $content_items, $content );

					break;

				// Scrollable List
				case 'scrollable':

					PT_CV_Html_ViewType::scrollable_wrapper( $content_items, $content );

					break;

				default :
					foreach ( $content_items as $post_id => $content_item ) {
						// Wrap content of item
						$content[] = PT_CV_Html::content_item_wrap( $content_item, '', $post_id );
					}

					$content = apply_filters( PT_CV_PREFIX_ . 'content_items_wrap', $content, $content_items, $current_page, $post_per_page );

					break;
			}

			// Join content
			$content_list = balanceTags( implode( "\n", $content ) );

			// Custom attribute of a page
			$page_attr_	 = apply_filters( PT_CV_PREFIX_ . 'page_attr', '', $view_type, $content_items );
			$page_attr	 = strip_tags( $page_attr_ );

			// Wrap items in 'page' wrapper
			$wrap_in_page = apply_filters( PT_CV_PREFIX_ . 'wrap_in_page', true );
			if ( $wrap_in_page ) {
				// Wrap in page wrapper
				$html		 = sprintf( '<div id="%s" class="%s" %s>%s</div>', esc_attr( PT_CV_PREFIX . 'page' . '-' . $current_page ), esc_attr( PT_CV_PREFIX . 'page' ), $page_attr, $content_list );
				// Remove page attribute value
				$page_attr	 = '';
			} else {
				$html = $content_list;
			}

			if ( $display ) {
				// Get wrapper class of a view
				$view_class = apply_filters( PT_CV_PREFIX_ . 'view_class', array( PT_CV_PREFIX . 'view', PT_CV_PREFIX . $view_type ) );

				// ID for the wrapper
				$view_id = PT_CV_PREFIX . 'view-' . $id;

				$output = sprintf( '<div class="%s" id="%s" %s>%s</div>', esc_attr( implode( ' ', array_filter( $view_class ) ) ), esc_attr( $view_id ), $page_attr, $html );

				do_action( PT_CV_PREFIX_ . 'store_view_data', $view_id );
			} else {
				$output = $html;
			}

			return balanceTags( $before_output ) . balanceTags( $output );
		}

		/**
		 * HTML output of a field (thumbnail, title, content, meta fields...)
		 *
		 * @param string $field_name The name of field
		 * @param object $post       The post object
		 * @param array  $fargs      The array of Field settings
		 *
		 * @return string
		 */
		static function field_item_html( $field_name, $post, $fargs ) {

			$dargs = PT_CV_Functions::get_global_variable( 'dargs' );

			if ( empty( $field_name ) ) {
				return '';
			}

			$html = '';

			// Get other settings
			$oargs = isset( $dargs[ 'other-settings' ] ) ? $dargs[ 'other-settings' ] : array();

			switch ( $field_name ) {

				// Thumbnail
				case 'thumbnail':

					if ( empty( $fargs[ 'thumbnail' ] ) ) {
						break;
					}

					$html = self::_field_thumbnail( $post, $fargs );

					break;

				// Title
				case 'title':

					$html = self::_field_title( $post, $oargs, $fargs );

					break;

				// Content
				case 'content':

					if ( empty( $fargs[ 'content' ] ) ) {
						break;
					}

					$html = self::_field_content( $post, $fargs );

					break;

				// Meta fields
				case 'meta-fields':

					if ( empty( $fargs[ 'meta-fields' ] ) ) {
						break;
					}

					$html = self::_field_meta( $post, $fargs[ 'meta-fields' ] );

					break;

				default :
					$html = apply_filters( PT_CV_PREFIX_ . 'field_item_html', $html, $field_name, $post );
					break;
			}

			return $html;
		}

		/**
		 * Get Title
		 *
		 * @param object $post
		 * @param array  $oargs
		 * @return string
		 */
		static function _field_title( $post, $oargs, $fargs ) {
			// Get title class
			$title_class = apply_filters( PT_CV_PREFIX_ . 'field_title_class', PT_CV_PREFIX . 'title' );

			// Get title tag
			$tag = apply_filters( PT_CV_PREFIX_ . 'field_title_tag', 'h4' );

			// Get post title
			$title = get_the_title( $post );
			if ( empty( $title ) ) {
				$title = __( '(no title)', PT_CV_DOMAIN );
			}

			$title = apply_filters( PT_CV_PREFIX_ . 'field_title_result', $title, $fargs, $post->ID );

			$html = sprintf(
			'<%1$s class="%2$s">%3$s</%1$s>', $tag, esc_attr( $title_class ), self::_field_href( $oargs, $post, $title )
			);

			return apply_filters( PT_CV_PREFIX_ . 'field_title_extra', $html, $post );
		}

		/**
		 * Get content
		 *
		 * @param object $post
		 * @param array  $fargs
		 *
		 * @return string
		 */
		static function _field_content( $post, $fargs ) {
			$dargs = PT_CV_Functions::get_global_variable( 'dargs' );

			// Get other settings
			$oargs = isset( $dargs[ 'other-settings' ] ) ? $dargs[ 'other-settings' ] : array();

			// Sets up global post data
			setup_postdata( $post );

			// Handle the more tag inside content
			do_action( PT_CV_PREFIX_ . 'handle_teaser' );

			// Get content class
			$content_class = apply_filters( PT_CV_PREFIX_ . 'field_content_class', PT_CV_PREFIX . 'content' );

			// Get content tag (div/p/span...)
			$tag = apply_filters( PT_CV_PREFIX_ . 'field_content_tag', 'div' );

			// Get full content/exceprt
			$content = '';
			switch ( $fargs[ 'content' ][ 'show' ] ) {
				case 'excerpt':
					$length			 = (int) $fargs[ 'content' ][ 'length' ];
					$readmore_btn	 = '';
					$dots			 = ' ...';
					$readmore_html	 = apply_filters( PT_CV_PREFIX_ . 'field_excerpt_dots', 1, $fargs ) ? $dots : '';

					// Read more button
					if ( apply_filters( PT_CV_PREFIX_ . 'field_content_readmore_enable', 1, $fargs[ 'content' ] ) ) {
						$text		 = apply_filters( PT_CV_PREFIX_ . 'field_content_readmore_text', __( 'Read More', PT_CV_DOMAIN ), $fargs[ 'content' ] );
						$btn_class	 = apply_filters( PT_CV_PREFIX_ . 'field_content_readmore_class', 'btn btn-success btn-sm', $fargs );
						$readmore_btn .= self::_field_href( $oargs, $post, $text, PT_CV_PREFIX . 'readmore ' . $btn_class );
						$readmore_html .= apply_filters( PT_CV_PREFIX_ . 'field_content_readmore_seperated', '<br/>', $fargs ) . $readmore_btn;
					}

					// Get excerpt
					if ( $length > 0 ) {
						$content_to_extract	 = apply_filters( PT_CV_PREFIX_ . 'field_content_to_extract', get_the_content(), $post );
						// Extract excerpt from content
						$excerpt			 = PT_CV_Functions::wp_trim_words( $content_to_extract, $length );
						// Get manual excerpt
						$excerpt			 = apply_filters( PT_CV_PREFIX_ . 'field_content_excerpt', $excerpt, $fargs, $post );
						// Append readmore button
						$content			 = $excerpt . $readmore_html;
					} else {
						// Display only readmore button if length <= 0
						$content = $readmore_btn;
					}

					// Trim period which precedes dots
					$content = str_replace( '.' . $dots, $dots, $content );

					break;

				case 'full':
					ob_start();
					the_content();
					$content = ob_get_clean();

					break;
			}

			$content = apply_filters( PT_CV_PREFIX_ . 'field_content_final', $content, $post );

			$html = rtrim( $content, '.' ) ? sprintf(
			'<%1$s class="%2$s">%3$s</%1$s>', $tag, esc_attr( $content_class ), force_balance_tags( $content )
			) : '';

			return $html;
		}

		/**
		 * Output link to item
		 *
		 * @param array  $oargs   The other settings
		 * @param object $post    The post object
		 * @param string $content The HTML of <a> tag
		 */
		static function _field_href( $oargs, $post, $content, $defined_class = '' ) {

			// Open in
			$open_in = isset( $oargs[ 'open-in' ] ) ? $oargs[ 'open-in' ] : '_blank';

			// Class of href
			$href_class = apply_filters( PT_CV_PREFIX_ . 'field_href_class', array( $open_in, $defined_class ), $oargs );

			// Custom data
			$custom_attr = apply_filters( PT_CV_PREFIX_ . 'field_href_attrs', array(), $open_in, $oargs );

			// Don't wrap link
			$no_link = apply_filters( PT_CV_PREFIX_ . 'field_href_no_link', 0, $open_in );

			$href = apply_filters( PT_CV_PREFIX_ . 'field_href', get_permalink( $post->ID ), $post );

			// Change href
			if ( $no_link && strpos( $defined_class, 'readmore' ) === false ) {
				$href = 'javascript:void(0)';
			}

			// Generate a tag
			$html = sprintf(
			'<a href="%s" class="%s" target="%s" %s>%s</a>', $href, implode( ' ', array_filter( $href_class ) ), $open_in, implode( ' ', array_filter( $custom_attr ) ), balanceTags( $content )
			);

			return $html;
		}

		/**
		 * HTML output of thumbnail field
		 *
		 * @param object $post  The post object
		 * @param array  $fargs The settings of this field
		 *
		 * @return string
		 */
		static function _field_thumbnail( $post, $fargs ) {

			$dargs = PT_CV_Functions::get_global_variable( 'dargs' );

			// Get layout format
			$layout_format = $fargs[ 'layout-format' ];

			// Get thumbnail settings
			$fargs = $fargs[ 'thumbnail' ];

			$html = '';

			// Get post ID
			$post_id = $post->ID;

			// Custom args for get_the_post_thumbnail function
			$thumbnail_class	 = array();
			$thumbnail_class[]	 = PT_CV_PREFIX . 'thumbnail';
			$thumbnail_class[]	 = isset( $fargs[ 'style' ] ) ? $fargs[ 'style' ] : '';
			if ( $layout_format === '2-col' ) {
				$thumbnail_class[] = isset( $fargs[ 'position' ] ) ? 'pull-' . $fargs[ 'position' ] : 'pull-left';
			}
			$gargs = array(
				'class' => apply_filters( PT_CV_PREFIX_ . 'field_thumbnail_class', implode( ' ', array_filter( $thumbnail_class ) ) ),
			);

			// Get thumbnail dimensions
			$dimensions	 = PT_CV_Functions::field_thumbnail_dimensions( $fargs );
			$dimensions	 = (array) apply_filters( PT_CV_PREFIX_ . 'field_thumbnail_dimension_output', $dimensions, $fargs );

			// Check if has thumbnail ( has_post_thumbnail doesn't works )
			$thumbnail_id	 = get_post_thumbnail_id( $post_id );
			// Check if user doesn't want to load thumbnail: field_thumbnail_load = 0
			$load_thumbnail	 = !empty( $thumbnail_id ) && apply_filters( PT_CV_PREFIX_ . 'field_thumbnail_load', 1 );
			if ( $load_thumbnail ) {
				$thumbnail_size	 = count( $dimensions ) > 1 ? $dimensions : $dimensions[ 0 ];
				$html			 = wp_get_attachment_image( (int) $thumbnail_id, $thumbnail_size, false, $gargs );
				$html			 = apply_filters( PT_CV_PREFIX_ . 'field_thumbnail_image', $html, $post, $dimensions, $fargs );
			} else {
				$html = apply_filters( PT_CV_PREFIX_ . 'field_thumbnail_not_found', $html, $post, $dimensions, $gargs );
			}

			// Add link to thumbnail
			$oargs	 = isset( $dargs[ 'other-settings' ] ) ? $dargs[ 'other-settings' ] : array();
			$html	 = self::_field_href( $oargs, $post, $html );

			return $html;
		}

		/**
		 * HTML output of meta fields group
		 *
		 * @param object $post  The post object
		 * @param array  $fargs The settings of this field
		 *
		 * @return string
		 */
		static function _field_meta( $post, $fargs ) {

			$html = array();

			// Sets up global post data
			setup_postdata( $post );

			foreach ( $fargs as $meta => $val ) {
				if ( !$val ) {
					continue;
				}

				switch ( $meta ) {
					case 'date':
						// Get date wrapper class
						$date_class	 = apply_filters( PT_CV_PREFIX_ . 'field_meta_class', 'entry-date', 'date' );
						$prefix_text = apply_filters( PT_CV_PREFIX_ . 'field_meta_prefix_text', '', 'date' );
						$date		 = apply_filters( PT_CV_PREFIX_ . 'field_meta_date_final', get_the_date( '', $post ), get_the_time( 'U' ) );

						$html[ 'date' ] = sprintf( '<span class="%s">%s <time datetime="%s">%s</time></span>', esc_html( $date_class ), balanceTags( $prefix_text ), esc_attr( get_the_date( 'c' ) ), esc_html( $date ) );
						break;

					case 'taxonomy':

						// Get terms wrapper class
						$term_class	 = apply_filters( PT_CV_PREFIX_ . 'field_meta_class', 'terms', 'terms' );
						$prefix_text = apply_filters( PT_CV_PREFIX_ . 'field_meta_prefix_text', __( 'in', PT_CV_DOMAIN ), 'terms' );

						$terms = PT_CV_Functions::post_terms( $post );
						if ( !empty( $terms ) ) {
							$term_html			 = sprintf( '<span class="%s">%s %s</span>', esc_attr( $term_class ), balanceTags( $prefix_text ), balanceTags( $terms ) );
							$html[ 'taxonomy' ]	 = apply_filters( PT_CV_PREFIX_ . 'field_term_html', $term_html, $terms );
						}
						break;

					case 'comment':
						if ( !post_password_required() && ( comments_open() || get_comments_number() ) ) :
							// Get comment wrapper class
							$comment_class	 = apply_filters( PT_CV_PREFIX_ . 'field_meta_class', 'comments-link', 'comment' );
							$prefix_text	 = apply_filters( PT_CV_PREFIX_ . 'field_meta_prefix_text', '', 'comment' );

							ob_start();
							comments_popup_link( __( 'Leave a comment', PT_CV_DOMAIN ), __( '1 Comment', PT_CV_DOMAIN ), __( '% Comments', PT_CV_DOMAIN ) );
							$comment_content	 = ob_get_clean();
							$html[ 'comment' ]	 = sprintf( '<span class="%s">%s %s</span>', esc_attr( $comment_class ), balanceTags( $prefix_text ), $comment_content );
						endif;
						break;

					case 'author':

						// Get author wrapper class
						$author_class	 = apply_filters( PT_CV_PREFIX_ . 'field_meta_class', 'author', 'author' );
						$prefix_text	 = apply_filters( PT_CV_PREFIX_ . 'field_meta_prefix_text', __( 'by', PT_CV_DOMAIN ), 'author' );

						$author_html		 = sprintf( '<span class="%s">%s <a href="%s" rel="author">%s</a></span>', esc_attr( $author_class ), balanceTags( $prefix_text ), esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), get_the_author() );
						$html[ 'author' ]	 = apply_filters( PT_CV_PREFIX_ . 'field_meta_author_html', $author_html, $post );
						break;

					default:
						break;
				}
			}

			// Merge fields, or let them as seperate items in array
			$merge_fields = apply_filters( PT_CV_PREFIX_ . 'field_meta_merge_fields', true );

			if ( $merge_fields ) {
				$result = PT_CV_Html::_field_meta_wrap( $html );
			} else {
				$result = $html;
			}

			return $result;
		}

		/**
		 * Wrap meta fields in a wrapper
		 *
		 * @param array  $meta_html Array of meta fields to wrapping
		 * @param string $seperator Seperator string when join meta fields
		 *
		 * @return string
		 */
		static function _field_meta_wrap( $meta_html, $seperator = NULL ) {

			if ( !$meta_html ) {
				return '';
			}

			$seperator = isset( $seperator ) ? $seperator : apply_filters( PT_CV_PREFIX_ . 'field_meta_seperator', ' / ' );

			// Get meta fields class
			$meta_fields_class = apply_filters( PT_CV_PREFIX_ . 'field_meta_fields_class', PT_CV_PREFIX . 'meta-fields' );

			// Get meta fields tag
			$tag = apply_filters( PT_CV_PREFIX_ . 'field_meta_fields_tag', 'div' );

			// Define wrapper
			$wrapper = sprintf(
			'<%1$s class="%2$s">%3$s</%1$s>', $tag, esc_attr( $meta_fields_class ), '%s'
			);

			// Join fields
			$meta_html = implode( $seperator, (array) $meta_html );

			// Wrap
			$html = sprintf( $wrapper, balanceTags( $meta_html ) );

			return $html;
		}

		/**
		 * Output pagination
		 *
		 * @param type   $max_num_pages The total of pages
		 * @param type   $current_page  The current pages
		 * @param string $session_id    The session ID of current view
		 *
		 * @return type
		 */
		static function pagination_output( $max_num_pages, $current_page, $session_id ) {

			$dargs = PT_CV_Functions::get_global_variable( 'dargs' );

			if ( !$max_num_pages || (int) $max_num_pages === 1 ) {
				return '';
			}

			$pagination_btn = '';

			$type	 = isset( $dargs[ 'pagination-settings' ][ 'type' ] ) ? $dargs[ 'pagination-settings' ][ 'type' ] : 'ajax';
			$style	 = isset( $dargs[ 'pagination-settings' ][ 'style' ] ) ? $dargs[ 'pagination-settings' ][ 'style' ] : 'regular';

			if ( $type == 'normal' || $style == 'regular' ) {
				$pagination_btn = sprintf( '<ul class="%s" data-totalpages="%s" data-sid="%s">%s</ul>', PT_CV_PREFIX . 'pagination' . ' ' . PT_CV_PREFIX . $type . ' pagination', esc_attr( $max_num_pages ), esc_attr( $session_id ), PT_CV_Functions::pagination( $max_num_pages, $current_page ) );
			} else {
				$pagination_btn = apply_filters( PT_CV_PREFIX_ . 'btn_more_html', $pagination_btn, $max_num_pages, $session_id );
			}
			// Add loading icon
			$pagination_btn .= self::html_loading_img( 12, PT_CV_PREFIX . 'spinner' );

			$wrapper_class	 = apply_filters( PT_CV_PREFIX_ . 'pagination_class', '' );
			$output			 = apply_filters( PT_CV_PREFIX_ . 'pagination_output', sprintf( '<div class="%s">%s</div>', $wrapper_class . ' ' . PT_CV_PREFIX . 'pagination-wrapper', $pagination_btn ) );

			return $output;
		}

		/**
		 * Get assets content of all selected view types in a page
		 * by merging css files to public/assets/css/public.css, js files to public/assets/js/public.js
		 */
		static function assets_of_view_types() {
			global $pt_cv_glb, $pt_cv_id;

			// If already processed | have no View on this page -> return
			if ( !empty( $pt_cv_glb[ $pt_cv_id ][ 'applied_assets' ] ) || !$pt_cv_id ) {
				return;
			}
			// Mark as processed
			$pt_cv_glb[ $pt_cv_id ][ 'applied_assets' ] = 1;

			// Print inline view styles & scripts
			if ( apply_filters( PT_CV_PREFIX_ . 'assets_verbose_loading', 1 ) ) {
				$assets			 = array( 'css', 'js' );
				$assets_output	 = $assets_files	 = array();

				// Get content of asset files in directory of view type
				foreach ( self::$view_type_dir as $idx => $view_type_dir ) {
					// Get selected style of current view type
					$style = self::$style[ $idx ];

					// With each type of asset (css, js), looking for suit file of selected style
					foreach ( $assets as $type ) {
						$file_path	 = $view_type_dir . '/' . $type . '/' . $style . '.' . $type;
						$content	 = PT_CV_Functions::file_include_content( $file_path );
						if ( $content ) {
							$assets_output[ $type ][] = $content;
						}
					}
				}

				// Echo script, style inline
				if ( $assets_output ) {
					foreach ( $assets_output as $type => $contents ) {
						$content = implode( "\n", $contents );

						if ( $type == 'js' ) {
							echo '' . self::inline_script( $content, false );
						} else {
							echo '' . self::inline_style( $content );
						}
					}
				}
			}

			// Link to external files
			$assets_files = apply_filters( PT_CV_PREFIX_ . 'assets_files', array() );

			if ( is_admin() ) {
				// Include assets file in Preview
				foreach ( $assets_files as $type => $srcs ) {
					foreach ( $srcs as $src ) {
						PT_CV_Asset::include_inline( 'preview', $src, $type );
					}
				}
			} else {
				// Enqueue merged asset contents
				foreach ( $assets_files as $type => $srcs ) {
					foreach ( $srcs as $src ) {
						$type		 = ( $type == 'js' ) ? 'script' : 'style';
						$function	 = "wp_enqueue_{$type}";

						if ( function_exists( $function ) ) {
							$function( PT_CV_PREFIX . $type, $src );
						}
					}
				}
			}

			// Output custom inline style for Views
			if ( apply_filters( PT_CV_PREFIX_ . 'output_view_style', 1 ) ) {
				do_action( PT_CV_PREFIX_ . 'print_view_style' );
			}
		}

		/**
		 * Scripts for Preview & WP frontend
		 *
		 * @param bool $is_admin Whether or not in WP Admin
		 */
		static function frontend_scripts( $is_admin = false ) {
			$unload_bootstrap = PT_CV_Functions::get_option_value( 'unload_bootstrap' );
			if ( $is_admin || empty( $unload_bootstrap ) ) {
				// Load bootstrap js
				PT_CV_Asset::enqueue( 'bootstrap' );
			}

			// Load bootstrap paginator
			PT_CV_Asset::enqueue( 'bootstrap-paginator' );

			// Public script
			PT_CV_Asset::enqueue(
			'public', 'script', array(
				'src'	 => plugins_url( 'public/assets/js/public.js', PT_CV_FILE ),
				'deps'	 => array( 'jquery' ),
			)
			);

			// Localize for Public script
			PT_CV_Asset::localize_script(
			'public', PT_CV_PREFIX_UPPER . 'PUBLIC', array(
				'_prefix'		 => PT_CV_PREFIX,
				'page_to_show'	 => apply_filters( PT_CV_PREFIX_ . 'pages_to_show', 5 ),
				'_nonce'		 => wp_create_nonce( PT_CV_PREFIX_ . 'ajax_nonce' ),
				'is_admin'		 => is_admin(),
				'is_mobile'		 => wp_is_mobile(),
				'ajaxurl'		 => admin_url( 'admin-ajax.php' ),
				'lang'			 => PT_CV_Functions::get_language(), #Get current language of site
				'move_bootstrap' => apply_filters( PT_CV_PREFIX_ . 'move_bootstrap', 1 ), #Should I move Bootstrap to top of all styles
			)
			);

			// Localize for Pagination script
			PT_CV_Asset::localize_script(
			'bootstrap-paginator', PT_CV_PREFIX_UPPER . 'PAGINATION', array(
				'first'			 => apply_filters( PT_CV_PREFIX_ . 'pagination_first', '&laquo;' ),
				'prev'			 => apply_filters( PT_CV_PREFIX_ . 'pagination_prev', '&lsaquo;' ),
				'next'			 => apply_filters( PT_CV_PREFIX_ . 'pagination_next', '&rsaquo;' ),
				'last'			 => apply_filters( PT_CV_PREFIX_ . 'pagination_last', '&raquo;' ),
				'goto_first'	 => apply_filters( PT_CV_PREFIX_ . 'goto_first', __( 'Go to first page', PT_CV_DOMAIN ) ),
				'goto_prev'		 => apply_filters( PT_CV_PREFIX_ . 'goto_prev', __( 'Go to previous page', PT_CV_DOMAIN ) ),
				'goto_next'		 => apply_filters( PT_CV_PREFIX_ . 'goto_next', __( 'Go to next page', PT_CV_DOMAIN ) ),
				'goto_last'		 => apply_filters( PT_CV_PREFIX_ . 'goto_last', __( 'Go to last page', PT_CV_DOMAIN ) ),
				'current_page'	 => apply_filters( PT_CV_PREFIX_ . 'current_page', __( 'Current page is', PT_CV_DOMAIN ) ),
				'goto_page'		 => apply_filters( PT_CV_PREFIX_ . 'goto_page', __( 'Go to page', PT_CV_DOMAIN ) ),
			)
			);
		}

		/**
		 * Styles for Preview & WP frontend
		 *
		 * @global bool $is_IE
		 */
		static function frontend_styles() {
			$unload_bootstrap = PT_CV_Functions::get_option_value( 'unload_bootstrap' );
			if ( !is_admin() && empty( $unload_bootstrap ) ) {
				PT_CV_Asset::enqueue( 'bootstrap', 'style' );
			}

			PT_CV_Asset::enqueue(
			'public', 'style', array(
				'src' => plugins_url( 'public/assets/css/public.css', PT_CV_FILE ),
			)
			);

			// Fix bootstrap error in IE
			global $is_IE;
			if ( $is_IE ) {
				PT_CV_Asset::enqueue(
				'html5shiv', 'script', array(
					'src'	 => plugins_url( 'assets/ie-fix/html5shiv.min.js', PT_CV_FILE ),
					'ver'	 => '3.7.0',
				)
				);
				PT_CV_Asset::enqueue(
				'respond', 'script', array(
					'src'	 => plugins_url( 'assets/ie-fix/respond.js', PT_CV_FILE ),
					'ver'	 => '1.4.2',
				)
				);
			}
		}

		/**
		 * Print inline js code
		 *
		 * @param string $js The js code
		 *
		 * @return string
		 */
		static function inline_script( $js, $wrap = true, $prefix = 'inline' ) {
			// Generate random id for script tag
			$random_id = PT_CV_Functions::string_random();

			ob_start();
			?>
			<script type="text/javascript" id="<?php echo esc_attr( PT_CV_PREFIX . $prefix . '-script-' . $random_id ); ?>">
			<?php
			$newline = "\n";
			$format	 = $wrap ? "(function($){\$(function(){ {$newline}%s{$newline} });}(jQuery));" : '%s';
			printf( $format, $js );
			?>
			</script>
			<?php
			return ob_get_clean();
		}

		/**
		 * Print inline css code
		 *
		 * @param string $css The css code
		 *
		 * @return string
		 */
		static function inline_style( $css, $prefix = 'inline' ) {
			// Generate random id for style tag
			$random_id = PT_CV_Functions::string_random();

			ob_start();
			?>
			<style type="text/css" id="<?php echo esc_attr( PT_CV_PREFIX . $prefix . '-style-' . $random_id ); ?>"><?php echo '' . $css; ?></style>
			<?php
			return ob_get_clean();
		}

	}

}