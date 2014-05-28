<?php
/**
 * HTML output, class, id generating
 *
 * @package   PT_Content_Views
 * @author    Palace Of Themes <palaceofthemes@gmail.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Palace Of Themes
 */

if ( ! class_exists( 'PT_CV_Html' ) ) {

	/**
	 * @name PT_CV_Html
	 * @todo related HTML functions: Define HTML layout, Set class name...
	 */
	class PT_CV_Html {

		// Store directory of selected view_types
		static $view_type_dir = array();
		// Store all selected styles
		static $style = array();

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
		static function html_collapse_one( $parent_id, $id, $heading, $content = '', $show = true ) {
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
			$img = sprintf( '<img width="%1$s" height="%1$s" class="%2$s" alt="%3$s" src="%4$s" />', esc_attr( $dimension ), esc_attr( $class ), __( 'loading', PT_CV_DOMAIN ), 'data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==' );

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
		static function link_button( $link, $style, $text = 'Button', $class = '', $size = '' ) {
			return sprintf( '<a href="%s" class="btn btn-%s %s %s">%s</a>', $link, $style, $class, $size, $text );
		}

		/**
		 * Get Output HTML of a View type
		 *
		 * @param string $view_type The view type (grid, collapse...)
		 * @param array  $dargs     The array of Display settings
		 * @param object $post      The post object
		 * @param string $style     The style of view type (main, style2...)
		 */
		static function view_type_output( $view_type, $dargs, $post, $style = 'main' ) {
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
				self::$view_type_dir[] = $view_type_assets_dir;
				self::$style[]         = $style;

				// Generate HTML output of all content fields
				$fields_html = array();
				foreach ( $dargs['fields'] as $field_name ) {
					// Get settings of fields
					$fargs = isset( $dargs['field-settings'] ) ? $dargs['field-settings'] : array();

					$fargs['layout-format'] = $dargs['layout-format'];

					// Get HTML output of field
					$fields_html[$field_name] = self::field_item_html( $field_name, $post, $fargs, $dargs );
				}

				// Get HTML content of view type, with specific style
				$file_path = $view_type_dir . '/' . 'html' . '/' . $style . '.' . 'php';

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
		 * @param array  $dargs     The array of Display settings
		 *
		 * @return string Full HTML output of a item
		 */
		static function content_item_wrap( $html_item, $class = '', $dargs = array() ) {
			if ( empty( $html_item ) ) {
				return '';
			}
			if ( $dargs ) {
				// If only show Title
				if ( isset( $dargs['fields'] ) && count( (array) $dargs['fields'] ) == 1 && $dargs['fields'][0] === 'title' ) {
					$class .= ' ' . PT_CV_PREFIX . 'only-title';
				}
			}
			// Get wrapper class of a item
			$item_class = apply_filters( PT_CV_PREFIX_ . 'content_item_class', array( $class, PT_CV_PREFIX . 'content-item' ) );

			$result = sprintf( '<div class="%1$s">%2$s</div>', esc_attr( implode( ' ', $item_class ) ), balanceTags( $html_item ) );

			return $result;
		}

		/**
		 * Wrap content of all items
		 *
		 * @param array $content_items The array of Raw HTML output (is not wrapped) of each item
		 * @param array $dargs         The array of Display settings
		 * @param array $current_page  The current page
		 *
		 * @return string Full HTML output for Content View
		 */
		static function content_items_wrap( $content_items, $dargs, $current_page ) {
			if ( empty( $content_items ) ) {
				return '';
			}

			$content = array();

			$view_type = $dargs['view-type'];

			// Separate items by row, column
			switch ( $view_type ) {

				// Grid
				case 'grid':

					PT_CV_Html_ViewType::grid_wrapper( $content_items, $dargs, $content );

					break;

				// Collapsible List
				case 'collapsible':

					PT_CV_Html_ViewType::collapsible_wrapper( $content_items, $dargs, $content );

					break;

				// Scrollable List
				case 'scrollable':

					PT_CV_Html_ViewType::scrollable_wrapper( $content_items, $dargs, $content );

					break;

				default :
					foreach ( $content_items as $content_item ) {
						// Wrap content of item
						$content[] = PT_CV_Html::content_item_wrap( $content_item );
					}

					$content = apply_filters( PT_CV_PREFIX_ . 'content_items_wrap', $content, $content_items, $dargs, $current_page );

					break;
			}

			// Join content
			$content_list = balanceTags( implode( "\n", $content ) );

			// Custom attribute of a page
			$page_attr_ = apply_filters( PT_CV_PREFIX_ . 'page_attr', '', $view_type, $dargs, $content_items );
			$page_attr  = strip_tags( $page_attr_ );

			// Wrap items in 'page' wrapper
			$wrap_in_page = apply_filters( PT_CV_PREFIX_ . 'wrap_in_page', true, $dargs );
			if ( $wrap_in_page ) {
				// Wrap in page wrapper
				$html = sprintf( '<div id="%s" class="%s" %s>%s</div>', esc_attr( PT_CV_PREFIX . 'page' . '-' . $current_page ), esc_attr( PT_CV_PREFIX . 'page' ), $page_attr, $content_list );
				// Remove page attribute value
				$page_attr = '';
			} else {
				$html = $content_list;
			}

			// If is first page, wrap content in 'view' wrapper
			if ( $current_page === 1 ) {
				// Get wrapper class of a view
				$view_class = apply_filters( PT_CV_PREFIX_ . 'view_class', array( PT_CV_PREFIX . 'view', PT_CV_PREFIX . $view_type ) );

				// ID for the wrapper
				$view_id = PT_CV_PREFIX . 'view-' . PT_CV_Functions::string_random();

				$output = sprintf( '<div class="%s" id="%s" %s>%s</div>', esc_attr( implode( ' ', array_filter( $view_class ) ) ), esc_attr( $view_id ), $page_attr, $html );

				do_action( PT_CV_PREFIX_ . 'store_view_data', $view_id, $dargs );
			} else {
				$output = $html;
			}

			return $output;
		}

		/**
		 * HTML output of a field (thumbnail, title, content, meta fields...)
		 *
		 * @param string $field_name The name of field
		 * @param object $post       The post object
		 * @param array  $fargs      The array of Field settings
		 * @param array  $dargs      The settings array of Fields
		 *
		 * @return string
		 */
		static function field_item_html( $field_name, $post, $fargs, $dargs ) {
			if ( empty( $field_name ) ) {
				return '';
			}

			$html = '';

			// Get other settings
			$oargs = isset( $dargs['other-settings'] ) ? $dargs['other-settings'] : array();

			switch ( $field_name ) {

				// Thumbnail
				case 'thumbnail':

					if ( empty( $fargs['thumbnail'] ) ) {
						break;
					}

					$html = self::_field_thumbnail( $post, $fargs, $dargs );

					break;

				// Title
				case 'title':

					// Get title class
					$title_class = apply_filters( PT_CV_PREFIX_ . 'field_title_class', PT_CV_PREFIX . 'title' );

					// Get title tag
					$tag = apply_filters( PT_CV_PREFIX_ . 'field_title_tag', 'h4' );

					// Get post title
					$title = get_the_title( $post );
					if ( empty( $title ) ) {
						$title = __( '(no title)', PT_CV_DOMAIN );
					}

					$html = sprintf(
						'<%1$s class="%2$s">%3$s</%1$s>',
						$tag, esc_attr( $title_class ), self::_field_href( $oargs, $post, $title )
					);

					break;

				// Content
				case 'content':

					if ( empty( $fargs['content'] ) ) {
						break;
					}

					// Sets up global post data
					setup_postdata( $post );

					// Get content class
					$content_class = apply_filters( PT_CV_PREFIX_ . 'field_content_class', PT_CV_PREFIX . 'content' );

					// Get content tag (div/p/span...)
					$tag = apply_filters( PT_CV_PREFIX_ . 'field_content_tag', 'div' );

					// Get full content/exceprt
					$content = '';
					switch ( $fargs['content']['show'] ) {
						case 'excerpt':
							$length   = (int) $fargs['content']['length'];
							$readmore = '<br />' . PT_CV_Html::link_button( get_permalink(), 'success', __( 'Read more...' ), PT_CV_PREFIX . 'readmore', 'btn-sm' );
							$content  = wp_trim_words( get_the_content(), $length, apply_filters( PT_CV_PREFIX_ . 'field_content_readmore', $readmore, $fargs['content'], get_permalink() ) );
							break;

						case 'full':
							ob_start();
							the_content();
							$content = ob_get_clean();

							break;
					}

					$html = sprintf(
						'<%1$s class="%2$s">%3$s</%1$s>',
						$tag, esc_attr( $content_class ), balanceTags( $content )
					);

					break;

				// Meta fields
				case 'meta-fields':

					if ( empty( $fargs['meta-fields'] ) ) {
						break;
					}

					$html = self::_field_meta( $post, $fargs['meta-fields'], $dargs );

					break;
			}

			return $html;
		}

		/**
		 * Output link to item
		 *
		 * @param array  $oargs   The other settings
		 * @param object $post    The post object
		 * @param string $content The HTML of <a> tag
		 */
		static function _field_href( $oargs, $post, $content ) {

			// Open in
			$open_in = isset( $oargs['open-in'] ) ? $oargs['open-in'] : '_blank';

			// Class of href
			$href_class = apply_filters( PT_CV_PREFIX_ . 'field_href_class', array( $open_in ), $oargs );

			// Custom data
			$custom_attr = apply_filters( PT_CV_PREFIX_ . 'field_href_attrs', array(), $open_in, $oargs );

			$html = sprintf(
				'<a href="%s" class="%s" target="%s" %s>%s</a>',
				get_permalink( $post->ID ), implode( ' ', array_filter( $href_class ) ), $open_in, implode( ' ', array_filter( $custom_attr ) ), balanceTags( $content )
			);

			return $html;
		}

		/**
		 * HTML output of thumbnail field
		 *
		 * @param object $post  The post object
		 * @param array  $fargs The settings of this field
		 * @param array  $dargs The settings array
		 *
		 * @return string
		 */
		static function _field_thumbnail( $post, $fargs, $dargs ) {

			// Get layout format
			$layout_format = $fargs['layout-format'];

			// Get thumbnail settings
			$fargs = $fargs['thumbnail'];

			$html = '';

			// Get post ID
			$post_id = $post->ID;

			// Custom args for get_the_post_thumbnail function
			$thumbnail_class   = array();
			$thumbnail_class[] = PT_CV_PREFIX . 'thumbnail';
			$thumbnail_class[] = isset( $fargs['style'] ) ? $fargs['style'] : '';
			if ( $layout_format === '2-col' ) {
				$thumbnail_class[] = isset( $fargs['position'] ) ? 'pull-' . $fargs['position'] : 'pull-left';
			}
			$gargs = array(
				'class' => apply_filters( PT_CV_PREFIX_ . 'field_thumbnail_class', implode( ' ', array_filter( $thumbnail_class ) ) ),
			);

			// Get thumbnail dimensions
			$dimensions = PT_CV_Functions::field_thumbnail_dimensions( $fargs );

			// Check if has thumbnail ( has_post_thumbnail doesn't works )
			$has_thumbnail = get_the_post_thumbnail( $post_id );
			if ( ! empty( $has_thumbnail ) ) {
				$thumbnail_size = (array) apply_filters( PT_CV_PREFIX_ . 'field_thumbnail_dimension_output', $dimensions, $fargs );
				$thumbnail_size = count( $thumbnail_size ) > 1 ? $thumbnail_size : $thumbnail_size[0];
				$html           = get_the_post_thumbnail( $post_id, $thumbnail_size, $gargs );
			} else {
				$html = apply_filters( PT_CV_PREFIX_ . 'field_thumbnail_not_found', $html, $post, $dimensions, $gargs );
			}

			// If title is not shown, add link to thumbnail
			if ( ! in_array( 'title', $dargs['fields'] ) ) {
				$oargs = isset( $dargs['other-settings'] ) ? $dargs['other-settings'] : array();
				$html  = self::_field_href( $oargs, $post, $html );
			}

			return $html;
		}

		/**
		 * HTML output of meta fields group
		 *
		 * @param object $post  The post object
		 * @param array  $fargs The settings of this field
		 * @param array  $dargs The settings array of Fields
		 *
		 * @return string
		 */
		static function _field_meta( $post, $fargs, $dargs ) {

			$html = array();

			// Sets up global post data
			setup_postdata( $post );

			foreach ( $fargs as $meta => $val ) {
				if ( ! $val ) {
					continue;
				}

				switch ( $meta ) {
					case 'date':
						// Get date wrapper class
						$date_class = apply_filters( PT_CV_PREFIX_ . 'field_meta_date_class', 'entry-date' );

						$html['date'] = sprintf( '<span class="%1$s"><time datetime="%2$s">%3$s</time></span>', esc_html( $date_class ), esc_attr( get_the_date( 'c' ) ), esc_html( get_the_date() ) );
						break;

					case 'taxonomy':

						// Get terms wrapper class
						$term_class = apply_filters( PT_CV_PREFIX_ . 'field_meta_terms_class', 'terms' );

						$terms = PT_CV_Functions::post_terms( $post );
						if ( ! empty( $terms ) ) {
							$html['taxonomy'] = sprintf( '<span class="%s">%s %s</span>', esc_attr( $term_class ), __( 'in', PT_CV_DOMAIN ), balanceTags( $terms ) );
						}
						break;

					case 'comment':
						if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
							// Get comment wrapper class
							$comment_class = apply_filters( PT_CV_PREFIX_ . 'field_meta_comment_class', 'comments-link' );

							ob_start();
							?>
							<span class="<?php echo esc_attr( $comment_class ); ?>"><?php comments_popup_link( __( 'Leave a comment', PT_CV_DOMAIN ), __( '1 Comment', PT_CV_DOMAIN ), __( '% Comments', PT_CV_DOMAIN ) ); ?></span>
							<?php
							$html['comment'] = ob_get_clean();
						endif;
						break;

					case 'author':

						// Get author wrapper class
						$author_class = apply_filters( PT_CV_PREFIX_ . 'field_meta_author_class', 'author' );

						$author_html    = sprintf( '<span class="%s">%s <a href="%s" rel="author">%s</a></span>', esc_attr( $author_class ), __( 'by', PT_CV_DOMAIN ), esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ), get_the_author() );
						$html['author'] = apply_filters( PT_CV_PREFIX_ . 'field_meta_author_html', $author_html, $post, $dargs );
						break;

					default:
						break;
				}
			}

			// Merge fields, or let them as seperate items in array
			$merge_fields = apply_filters( PT_CV_PREFIX_ . 'field_meta_merge_fields', true, $dargs );

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

			$seperator = isset( $seperator ) ? $seperator : apply_filters( PT_CV_PREFIX_ . 'field_meta_seperator', ' / ' );

			// Get meta fields class
			$meta_fields_class = apply_filters( PT_CV_PREFIX_ . 'field_meta_fields_class', PT_CV_PREFIX . 'meta-fields' );

			// Get meta fields tag
			$tag = apply_filters( PT_CV_PREFIX_ . 'field_meta_fields_tag', 'div' );

			// Define wrapper
			$wrapper = sprintf(
				'<%1$s class="%2$s">%3$s</%1$s>',
				$tag, esc_attr( $meta_fields_class ), '%s'
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
		 * @param array  $dargs         The settings array of Fields
		 * @param string $view_id       The current view id
		 *
		 * @return type
		 */
		static function pagination_output( $max_num_pages, $dargs, $view_id ) {

			if ( ! $max_num_pages || (int) $max_num_pages === 1 ) {
				return '';
			}

			$output = '';

			$style = isset( $dargs['pagination-settings']['style'] ) ? $dargs['pagination-settings']['style'] : 'regular';
			if ( $style == 'regular' ) {
				$output = sprintf( '<ul class="%s" data-totalpages="%s" data-id="%s"></ul>', PT_CV_PREFIX . 'pagination', esc_attr( $max_num_pages ), esc_attr( $view_id ) );
			} else {
				$output = apply_filters( PT_CV_PREFIX_ . 'btn_more_html', $output, $max_num_pages, $view_id );
			}

			// Add loading icon
			$output .= self::html_loading_img( 12, PT_CV_PREFIX . 'spinner' );

			return $output;
		}

		/**
		 * Get assets content of all selected view types in a page
		 * by merging css files to public/assets/css/public.css, js files to public/assets/js/public.js
		 */
		static function assets_of_view_types() {
			$assets        = array( 'css', 'js' );
			$assets_output = $assets_files = array();

			// Get content of asset files in directory of view type
			foreach ( self::$view_type_dir as $idx => $view_type_dir ) {
				// Get selected style of current view type
				$style = self::$style[$idx];

				// With each type of asset (css, js), looking for suit file of selected style
				foreach ( $assets as $type ) {
					$file_path              = $view_type_dir . '/' . $type . '/' . $style . '.' . $type;
					$assets_output[$type][] = PT_CV_Functions::file_include_content( $file_path );
				}
			}

			// Merge content of asset files
			if ( $assets_output ) {
				foreach ( $assets as $type ) {

					$asset_file = $type . '/' . 'assets' . '.' . $type;

					// Write to file
					$file_path = PT_CV_PUBLIC_ASSETS . $asset_file;

					if ( file_exists( $file_path ) ) {
						$fp = @fopen( $file_path, 'w' );
						fwrite( $fp, implode( "\n", $assets_output[$type] ) );
						fclose( $fp );
					}

					$assets_files[$type] = PT_CV_PUBLIC_ASSETS_URI . $asset_file;
				}
			}

			if ( is_admin() ) {
				// Include assets file in Preview
				foreach ( $assets_files as $type => $src ) {
					PT_CV_Asset::include_inline( 'preview', $src, $type );
				}
			} else {
				// Enqueue merged asset contents
				foreach ( $assets_files as $type => $src ) {

					$type     = ( $type == 'js' ) ? 'script' : 'style';
					$function = "wp_enqueue_{$type}";

					if ( function_exists( $function ) ) {
						$function( PT_CV_PREFIX . $type, $src );
					}
				}
			}

			// Output font style for views
			do_action( PT_CV_PREFIX_ . 'print_view_style' );
		}

		/**
		 * Scripts for Preview & WP frontend
		 */
		static function frontend_scripts() {
			// Load bootstrap js
			PT_CV_Asset::enqueue( 'bootstrap' );

			// Load bootstrap paginator
			PT_CV_Asset::enqueue( 'bootstrap-paginator' );

			// Public script
			PT_CV_Asset::enqueue(
				'public', 'script', array(
					'src'  => plugins_url( 'public/assets/js/public.js', PT_CV_FILE ),
					'deps' => array( 'jquery' ),
				)
			);

			// Localize for Public script
			PT_CV_Asset::localize_script(
				'public', PT_CV_PREFIX_UPPER . 'PUBLIC', array(
					'is_admin' => is_admin() ? 1 : 0,
					'_prefix'  => PT_CV_PREFIX,
					'ajaxurl'  => admin_url( 'admin-ajax.php' ),
					'_nonce'   => wp_create_nonce( PT_CV_PREFIX_ . 'ajax_nonce' ),
				)
			);
		}

		/**
		 * Styles for Preview & WP frontend
		 */
		static function frontend_styles() {
			PT_CV_Asset::enqueue( 'bootstrap', 'style' );

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
						'src' => plugins_url( 'assets/ie-fix/html5shiv.min.js', PT_CV_FILE ),
						'ver' => '3.7.0',
					)
				);
				PT_CV_Asset::enqueue(
					'respond', 'script', array(
						'src' => plugins_url( 'assets/ie-fix/respond.js', PT_CV_FILE ),
						'ver' => '1.4.2',
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
		static function inline_script( $js ) {
			// Generate random id for script tag
			$random_id = PT_CV_Functions::string_random();

			ob_start();
			?>
			<script type="text/javascript" id="<?php echo esc_attr( PT_CV_PREFIX . 'inline-script-' . $random_id ); ?>">
				(function ($) {
					$(function () { <?php echo balanceTags( $js ); ?>
					});
				}(jQuery));
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
		static function inline_style( $css ) {
			// Generate random id for style tag
			$random_id = PT_CV_Functions::string_random();

			ob_start();
			?>
			<style type="text/css" id="<?php echo esc_attr( PT_CV_PREFIX . 'inline-style-' . $random_id ); ?>">
				<?php echo balanceTags( $css ); ?>
			</style>
			<?php
			return ob_get_clean();
		}
	}

}