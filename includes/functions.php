<?php
/**
 * Contain main functions to work with plugin, post, custom fields...
 *
 * @package   PT_Content_Views
 * @author    Palace Of Themes <palaceofthemes@gmail.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Palace Of Themes
 */

if ( ! function_exists( 'get_plugin_data' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
if ( ! class_exists( 'PT_CV_Functions' ) ) {

	/**
	 * @name PT_CV_Functions
	 * @todo Utility functions
	 */
	class PT_CV_Functions {

		static $prev_random_string = '';

		/**
		 * Compare current Wordpress version with a version
		 *
		 * @global string $wp_version
		 * @param string $version_to_compare
		 * @param string $operator
		 * @return boolean
		 */
		static function wp_version_compare( $version_to_compare, $operator = '>=' ) {
			if ( empty( $version_to_compare ) ) {
				return true;
			}

			global $wp_version;
			// Check if using Wordpress version 3.7 or higher
			return version_compare( $wp_version, $version_to_compare, $operator );
		}

		/**
		 * Get plugin info
		 *
		 * @param string $file Absolute path to the plugin file
		 * @param string $data Field of plugin data want to get
		 *
		 * @return array | null
		 */
		static function plugin_info( $file, $data = '' ) {
			$plugin_data = get_plugin_data( $file );

			return isset( $plugin_data[$data] ) ? $plugin_data[$data] : NULL;
		}

		/**
		 * Add sub menu page
		 *
		 * @param string $parent_slug Slug of parent menu
		 * @param string $page_title  Title of page
		 * @param string $menu_title  Title of menu
		 * @param string $sub_page    Slug of sub menu
		 * @param string $class       Class name which contains function to output content of page created by this menu
		 */
		static function menu_add_sub( $parent_slug, $page_title, $menu_title, $sub_page, $class ) {
			return add_submenu_page(
				$parent_slug, $page_title, $menu_title, 'manage_options', $parent_slug . '-' . $sub_page, array( $class, 'display_sub_page_' . $sub_page )
			);
		}

		/**
		 * Get current post type in Admin
		 *
		 * @global type $post
		 * @global type $typenow
		 * @global type $current_screen
		 * @return type
		 */
		static function admin_current_post_type() {
			global $post, $typenow, $current_screen;

			//we have a post so we can just get the post type from that
			if ( $post && $post->post_type ) {
				return $post->post_type;
			} //check the global $typenow - set in admin.php
			elseif ( $typenow ) {
				return $typenow;
			} //check the global $current_screen object - set in sceen.php
			elseif ( $current_screen && isset( $current_screen->post_type ) ) {
				return $current_screen->post_type;
			}
		}

		/**
		 * Include content of file
		 *
		 * @param string $file_path Absolute path of file
		 *
		 * @return NULL | string Content of file
		 */
		static function file_include_content( $file_path ) {
			$content = NULL;

			if ( file_exists( $file_path ) ) {
				ob_start();
				include_once $file_path;
				$content = ob_get_clean();
			}

			return $content;
		}

		/**
		 * Generate random string
		 *
		 * @param bool $prev_return Return previous generated string
		 *
		 * @return string
		 */
		static function string_random( $prev_return = false ) {
			if ( $prev_return ) {
				return PT_CV_Functions::$prev_random_string;
			}
			PT_CV_Functions::$prev_random_string = substr( md5( rand() ), 0, 10 );

			return PT_CV_Functions::$prev_random_string;
		}

		/**
		 * Create array from string, use explode function
		 *
		 * @param string $string    String to explode
		 * @param string $delimiter Delimiter to explode string
		 *
		 * @return array
		 */
		static function string_to_array( $string, $delimiter = ',' ) {
			return is_array( $string ) ? $string : (array) explode( $delimiter, (string) str_replace( ' ', '', $string ) );
		}

		/**
		 * Slug to nice String
		 *
		 * @param string $slug Slug string
		 *
		 * @return string
		 */
		static function string_slug_to_text( $slug ) {
			$slug = preg_replace( '/[^a-z]+/', ' ', $slug );

			return ucwords( $slug );
		}

		/**
		 * Get thumbnail dimensions
		 *
		 * @param array $fargs The settings of thumbnail
		 *
		 * @return array
		 */
		static function field_thumbnail_dimensions( $fargs ) {
			$size = $fargs['size'];

			return (array) explode( '&times;', str_replace( ' ', '', $size ) );
		}

		/**
		 * Get value of a setting from global settings array
		 *
		 * @param string      $field        The full name of setting to get value
		 * @param array       $array_to_get Array to get values of wanted setting
		 * @param mixed|null  $assign       The value to assign if setting is not found
		 */
		static function setting_value( $field, $array_to_get, $assign = NULL ) {
			return isset( $array_to_get[$field] ) ? $array_to_get[$field] : $assign;
		}

		/**
		 * Get values of settings from global settings array
		 *
		 * @param array  $fields        Array of setting fields to get value
		 * @param array  $array_to_save Array to save values of wanted setting fields
		 * @param array  $array_to_get  Array to get values of wanted setting fields
		 * @param string $prefix        Prefix string to looking for fields in $array_to_get
		 */
		static function settings_values( $fields, &$array_to_save, $array_to_get, $prefix ) {
			foreach ( $fields as $tsetting ) {
				$array_to_save[$tsetting] = PT_CV_Functions::setting_value( $prefix . $tsetting, $array_to_get );
			}
		}

		/**
		 * Get names of options for a setting group (setting name started by a prefix)
		 *
		 * @param string $prefix  The prefix in name of settings
		 * @param array  $options The options array (contain full paramaters of settings)
		 */
		static function settings_keys( $prefix, $options ) {
			$result = array();
			foreach ( $options as $option ) {
				if ( $option['params'] ) {
					foreach ( $option['params'] as $params ) {
						// If name of setting match with prefix string, got it name
						if ( isset( $params['name'] ) && substr( $params['name'], 0, strlen( $prefix ) ) === $prefix ) {
							$result[] = substr( $params['name'], strlen( $prefix ) );
						}
					}
				}
			}

			return $result;
		}

		/**
		 * Get value of some setting options by prefix
		 *
		 * @param string $prefix    The prefix in name of setting options
		 * @param type   $settings_ The settings array
		 */
		static function settings_values_by_prefix( $prefix, $settings_ ) {
			$result = array();

			foreach ( $settings_ as $name => $value ) {
				// If name of setting match with prefix string, got it name
				if ( substr( $name, 0, strlen( $prefix ) ) === $prefix ) {
					$result[substr( $name, strlen( $prefix ) )] = $value;
				}
			}

			return $result;
		}

		/**
		 * Get terms list of a post
		 *
		 * @param object $post The post object
		 *
		 * @return string
		 */
		static function post_terms( $post ) {
			// List of HTML link to terms
			$links = array();

			// Get list of taxonomies
			$taxonomies = get_taxonomies( '', 'names' );

			// Get lists of terms of this post
			$terms = wp_get_object_terms( $post->ID, $taxonomies );

			foreach ( $terms as $term ) {
				$links[] = sprintf(
					'<a href="%1$s" title="%2$s %3$s">%3$s</a>',
					esc_url( get_term_link( $term, $term->taxonomy ) ),
					__( 'View all posts in', PT_CV_DOMAIN ),
					$term->name
				);
			}

			return implode( ', ', $links );
		}

		/**
		 * Update post view count
		 *
		 * @param string $post_id ID of post
		 */
		static function post_update_view_count( $post_id ) {
			$meta_key = PT_CV_META_VIEW_COUNT;
			$count    = get_post_meta( $post_id, $meta_key, true );
			if ( ! $count ) {
				$count = 0;
			}
			$count ++;
			update_post_meta( $post_id, $meta_key, $count );
		}

		/**
		 * Get post view count
		 *
		 * @param string $post_id ID of post
		 */
		static function post_get_view_count( $post_id ) {
			$meta_key = PT_CV_META_VIEW_COUNT;
			$count    = get_post_meta( $post_id, $meta_key, true );
			if ( ! $count ) {
				$count = 1;
			}

			return _n( 'view', 'views', $count, PT_CV_DOMAIN );
		}

		/**
		 * Insert new post
		 *
		 * @param string $arr Array of post data
		 */
		static function post_insert( $arr ) {
			if ( ! $arr ) {
				return;
			}
			// Create post object
			$my_post = array(
				'ID'           => (int) $arr['ID'],
				'post_type'    => PT_CV_POST_TYPE,
				'post_content' => '',
				'post_title'   => ! empty( $arr['title'] ) ? $arr['title'] : __( '(no title)', PT_CV_DOMAIN ),
				'post_status'  => 'publish',
			);

			// Insert the post into the database
			return wp_insert_post( $my_post );
		}

		/**
		 * Get View id in post table, from "id" meta key value
		 *
		 * @param string $meta_id ID of custom field
		 */
		static function post_id_from_meta_id( $meta_id ) {

			$post_id = 0;
			if ( ! $meta_id ) {
				return $post_id;
			}

			// Query view which has view id = $meta_id
			$pt_query = new WP_Query(
				array(
					'post_type'   => PT_CV_POST_TYPE,
					'post_status' => 'publish',
					'meta_key'    => PT_CV_META_ID,
					'meta_value'  => $meta_id,
				)
			);
			if ( $pt_query->have_posts() ) :
				while ( $pt_query->have_posts() ):
					$pt_query->the_post();
					$post_id = get_the_ID();
				endwhile;
			endif;

			return $post_id;
		}

		/**
		 * Get first key of array
		 *
		 * @param array $args Array data
		 *
		 * @return string
		 */
		static function array_get_first_key( $args ) {
			return current( array_keys( $args ) );
		}

		/**
		 * Check valid request
		 *
		 * @param string $nonce_name  Name of nonce field
		 * @param string $action_name name of action relates to nonce field
		 */
		static function _nonce_check( $nonce_name, $action_name ) {
			$nonce_name = PT_CV_PREFIX_ . $nonce_name;
			if ( ! isset( $_POST[$nonce_name] ) || ! wp_verify_nonce( $_POST[$nonce_name], PT_CV_PREFIX_ . $action_name ) ) {
				print esc_html( __( 'Sorry, your nonce did not verify.', PT_CV_DOMAIN ) );
				exit;
			}
		}

		/**
		 * Get view data
		 *
		 * @param string $meta_id ID of custom field
		 *
		 * @return array
		 */
		static function view_get_settings( $meta_id ) {
			if ( ! $meta_id ) {
				return;
			}

			$post_id = PT_CV_Functions::post_id_from_meta_id( $meta_id );

			// Get view settings
			if ( $post_id ) {
				$view_settings = get_post_meta( $post_id, PT_CV_META_SETTINGS, true );
				return is_array( $view_settings ) ? $view_settings : array();
			}

			return array();
		}

		/**
		 * Process view $settings array, return HTML output
		 *
		 * @param string $id       The current view id
		 * @param array  $settings The settings array
		 * @param array  $pargs    The pagination settings array
		 *
		 * @return string HTML output of Content View
		 */
		static function view_process_settings( $id, $settings, $pargs = array() ) {
			if ( ! $settings ) {
				return '';
			}

			// Escaped value appropriate for use in a SQL query
			$settings_ = array();
			foreach ( $settings as $key => $value ) {
				$settings_[$key] = esc_sql( $value );
			}

			// Get content type
			$content_type = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'content-type', $settings_ );

			/**
			 * Get Query parameters
			 * Set default values
			 */
			$args = $default_args = array(
				'post_type'           => $content_type,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => 1,
			);

			// Post in
			if ( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'post__in', $settings_ ) ) {
				$post_in              = PT_CV_Functions::string_to_array( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'post__in', $settings_ ) );
				$args['post__in'] = array_map( 'intval', array_filter( $post_in ) );
			}

			// Post not in
			if ( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'post__not_in', $settings_ ) ) {
				$post_not_in          = PT_CV_Functions::string_to_array( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'post__not_in', $settings_ ) );
				$args['post__not_in'] = array_map( 'intval', array_filter( $post_not_in ) );
			}

			// Parent page
			if ( $content_type == 'page' ) {
				$post_parent = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'post_parent', $settings_ );
				if ( ! empty( $post_parent ) ) {
					$args['post_parent'] = (int) $post_parent;
				}
			}

			// Advance settings
			PT_CV_Functions::view_get_advanced_settings( $settings_, $args, $content_type );

			/**
			 * Get Display parameters
			 */
			$dargs = array();

			// Get view type
			$view_type = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'view-type', $settings_ );

			$dargs['view-type'] = $view_type;

			// Field settings of a item
			PT_CV_Functions::view_get_display_settings( $settings_, $dargs );

			// Pagination settings
			PT_CV_Functions::view_get_pagination_settings( $settings_, $dargs, $args, $pargs );

			// Other settings
			PT_CV_Functions::view_get_other_settings( $settings_, $dargs );

			// View type settings
			$dargs['view-type-settings'] = PT_CV_Functions::settings_values_by_prefix( PT_CV_PREFIX . $view_type . '-', $settings_ );

			$dargs = apply_filters( PT_CV_PREFIX_ . 'all_display_settings', $dargs, $settings_ );

			// Validate settings before processing, if some required parameters are missing, show error and exit
			$error = apply_filters( PT_CV_PREFIX_ . 'validate_settings', array(), $dargs, $args );

			// Return error message
			if ( $error ) {
				return ( implode( '</p><p>', $error ) );
			}

			/**
			 * Output Items
			 */

			// Store HTML output of each item
			$content_items = array();

			// The Query
			$pt_query = new WP_Query( $args );

			// The Loop
			if ( $pt_query->have_posts() ) {
				while ( $pt_query->have_posts() ) {
					$pt_query->the_post();
					global $post;

					// Output HTML for this item
					$content_items[] = PT_CV_Html::view_type_output( $view_type, $dargs, $post );
				}
			} else {
				// Get no post found class
				$_class = apply_filters( PT_CV_PREFIX_ . 'content_no_post_found_class', 'alert alert-warning' );

				// Get no post found text
				$_text = apply_filters( PT_CV_PREFIX_ . 'content_no_post_found_text', __( 'No post found', PT_CV_DOMAIN ) );

				// Output HTML
				$content_items[] = sprintf( '<div class="%1$s">%2$s</div>', esc_attr( $_class ), balanceTags( $_text ) );
			}

			// Restore original Post Data
			wp_reset_postdata();

			/**
			 * Output Pagination
			 */
			$current_page = ( isset( $pargs['page'] ) && $pargs['page'] > 1 ) ? $pargs['page'] : 1;
			$html         = PT_CV_Html::content_items_wrap( $content_items, $dargs, $current_page, $args['posts_per_page'] );

			// Append Pagination HTML if this is first page, or not Ajax calling
			if ( $args['posts_per_page'] > 0 && $current_page === 1 ) {
				// Total number of items
				$total_items = ( $args['limit'] > 0 && $pt_query->found_posts > $args['limit'] ) ? $args['limit'] : $pt_query->found_posts;

				// Total number of pages
				$max_num_pages = ceil( $total_items / $args['posts_per_page'] );
				$html .= "\n" . PT_CV_Html::pagination_output( $max_num_pages, $dargs, $id );
			}

			return $html;
		}

		/**
		 * Get Advance settings
		 *
		 * @param array  $settings_    The settings array
		 * @param array  $args         The parameters array
		 * @param string $content_type The content type
		 */
		static function view_get_advanced_settings( $settings_, &$args, $content_type ) {

			$advanced_settings = (array) PT_CV_Functions::setting_value( PT_CV_PREFIX . 'advanced-settings', $settings_ );
			if ( $advanced_settings ) {
				foreach ( $advanced_settings as $setting ) {
					switch ( $setting ) {

						// Author
						case 'author':
							$author_in = PT_CV_Functions::string_to_array( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'author__in', $settings_ ) );

							// Check if using Wordpress version 3.7 or higher
							$version_gt_37 = PT_CV_Functions::wp_version_compare( '3.7' );

							if ( $version_gt_37 ) {
								$author_not_in = PT_CV_Functions::string_to_array( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'author__not_in', $settings_ ) );

								// Author in
								if ( ! empty( $author_in[0] ) ) {
									$args = array_merge(
										$args, array(
											'author__in' => array_map( 'intval', $author_in ),
										)
									);
								}

								// Author not in
								if ( ! empty( $author_not_in[0] ) ) {
									$args = array_merge(
										$args, array(
											'author__not_in' => array_map( 'intval', $author_not_in ),
										)
									);
								}
							} else {
								// Author = ID
								if ( ! empty( $author_in[0] ) ) {
									$args = array_merge(
										$args, array(
											'author' => intval( $author_in[0] ),
										)
									);
								}
							}

							break;

						// Status
						case 'status':
							$args = array_merge(
								$args, array(
									'post_status' => PT_CV_Functions::string_to_array( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'post_status', $settings_, 'publish' ) ),
								)
							);
							break;

						// Search
						case 'search':
							if ( PT_CV_Functions::setting_value( PT_CV_PREFIX . 's', $settings_ ) ) {
								$args = array_merge(
									$args, array(
										's' => PT_CV_Functions::setting_value( PT_CV_PREFIX . 's', $settings_ ),
									)
								);
							}
							break;

						// Taxonomy
						case 'taxonomy':
							// No taxonomy found
							if ( ! PT_CV_Functions::setting_value( PT_CV_PREFIX . 'taxonomy', $settings_ ) ) {
								break;
							}

							// All settings of taxonomies
							$taxonomy_setting = array();

							// Selected taxonomies
							$taxonomies = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'taxonomy', $settings_ );

							// Get Terms & criterias (In, Not in)
							foreach ( $taxonomies as $taxonomy ) {

								// If found setting for taxonomy
								if ( PT_CV_Functions::setting_value( PT_CV_PREFIX . $taxonomy . '__in', $settings_ ) ) {
									$taxonomy_setting[] = array(
										'taxonomy' => $taxonomy,
										'field'    => 'slug',
										'terms'    => (array) PT_CV_Functions::setting_value( PT_CV_PREFIX . $taxonomy . '__in', $settings_ ),
									);
								}
								if ( PT_CV_Functions::setting_value( PT_CV_PREFIX . $taxonomy . '__not_in', $settings_ ) ) {
									$taxonomy_setting[] = array(
										'taxonomy' => $taxonomy,
										'field'    => 'slug',
										'terms'    => (array) PT_CV_Functions::setting_value( PT_CV_PREFIX . $taxonomy . '__not_in', $settings_ ),
										'operator' => 'NOT IN',
									);
								}
							}

							// Get Taxonomy relation if there are more than 1 selected taxonomies | set In & Not in of a taxonomy
							if ( count( $taxonomies ) > 1 || count( $taxonomy_setting ) > 1 ) {
								$taxonomy_setting['relation'] = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'taxonomy-relation', $settings_, 'AND' );
							}

							// Filter taxonomy with Custom post types
							$taxonomy_setting_ = apply_filters( PT_CV_PREFIX_ . 'taxonomy_setting', $taxonomy_setting );

							$args = array_merge( $args, array( 'tax_query' => $taxonomy_setting_ ) );
							break;

						// Order
						case 'order':

							$order_settings = array();

							// Advanced order by
							if ( PT_CV_Functions::setting_value( PT_CV_PREFIX . $content_type . '-orderby', $settings_ ) ) {

								// Get meta key to order by
								$meta_key = PT_CV_Functions::setting_value( PT_CV_PREFIX . $content_type . '-orderby', $settings_ );

								// Use 'meta_value_num' for numeric values
								$meta_numeric_values = apply_filters( PT_CV_PREFIX_ . 'meta_numeric_values', array() );
								$meta_orderby        = in_array( $meta_key, $meta_numeric_values ) ? 'meta_value_num' : 'meta_value';
								$order_settings      = array(
									'meta_key' => $meta_key,
									'orderby'  => $meta_orderby,
									'order'    => PT_CV_Functions::setting_value( PT_CV_PREFIX . 'advanced-order', $settings_ ),
								);

							} else {
								// Common order by

								$orderby          = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'orderby', $settings_ );
								$order_by_options = array_keys( PT_CV_Values::post_regular_orderby() );
								if ( in_array( $orderby, $order_by_options ) ) {
									$order_settings = array(
										'orderby' => $orderby,
										'order'   => PT_CV_Functions::setting_value( PT_CV_PREFIX . 'order', $settings_ )
									);
								}
							}

							$args = array_merge( $args, $order_settings );
							break;

						default:
							break;
					}
				}
			}
		}

		/**
		 * Get Fields settings
		 *
		 * @param array $settings_ The settings array
		 * @param array $dargs     The settings array of Fields
		 */
		static function view_get_display_settings( $settings_, &$dargs ) {

			$view_type = $dargs['view-type'];

			/**
			 * Layout format
			 */
			$dargs['layout-format'] = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'layout-format', $settings_ );

			/**
			 * Columns count & Rows count
			 */
			$dargs['number-columns'] = PT_CV_Functions::setting_value( PT_CV_PREFIX . $view_type . '-' . 'number-columns', $settings_, 1 );
			$dargs['number-rows']    = PT_CV_Functions::setting_value( PT_CV_PREFIX . $view_type . '-' . 'number-rows', $settings_, 1 );

			/**
			 * Fields settings
			 */
			$cfields_settings = PT_CV_Functions::settings_values_by_prefix( PT_CV_PREFIX . 'show-field-', $settings_ );
			$cfields = (array) array_keys( (array) $cfields_settings );
			foreach ( $cfields as $field ) {
				// If show this field
				if ( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'show-field-' . $field, $settings_ ) ) {
					// Add this field to display fields list
					$dargs['fields'][] = $field;

					// Get field settings
					switch ( $field ) {

						// Get thumbnail settings
						case 'thumbnail':

							$field_setting = array();

							$fields = array( 'position', 'size', 'style' );
							$prefix = PT_CV_PREFIX . 'field-thumbnail-';
							PT_CV_Functions::settings_values( $fields, $field_setting, $settings_, $prefix );

							$dargs['field-settings'][$field] = apply_filters( PT_CV_PREFIX_ . 'field_thumbnail_setting_values', $field_setting, $settings_, $prefix );

							break;

						// Get meta fields settings
						case 'meta-fields':

							$field_setting = array();

							$prefix = PT_CV_PREFIX . 'meta-fields-';
							$meta_fields_settings = PT_CV_Functions::settings_values_by_prefix( PT_CV_PREFIX . 'meta-fields-', $settings_ );
							$fields = (array) array_keys( (array) $meta_fields_settings );

							PT_CV_Functions::settings_values( $fields, $field_setting, $settings_, $prefix );

							$dargs['field-settings'][$field] = apply_filters( PT_CV_PREFIX_ . 'field_meta_fields_setting_values', $field_setting, $settings_, $prefix );

							break;

						// Get content settings
						case 'content':

							$field_setting = array();

							$prefix = PT_CV_PREFIX . 'field-content-';
							$fields = PT_CV_Functions::settings_keys( 'field-content-', PT_CV_Settings::field_settings() );
							PT_CV_Functions::settings_values( $fields, $field_setting, $settings_, $prefix );

							if ( $field_setting['show'] == 'excerpt' ) {
								$field_setting = array_merge( $field_setting, PT_CV_Functions::settings_values_by_prefix( PT_CV_PREFIX . 'field-excerpt-', $settings_ ) );
							}

							$dargs['field-settings'][$field] = apply_filters( PT_CV_PREFIX_ . 'field_content_setting_values', $field_setting, $settings_, $prefix );

							break;

						default:
							break;
					}
				}
			}
		}

		/**
		 * Get Pagination settings
		 *
		 * @param array $settings_ The settings array
		 * @param array $dargs     The settings array of Fields
		 * @param array $args      The parameters array
		 * @param array $pargs     The pagination settings array
		 */
		static function view_get_pagination_settings( $settings_, &$dargs, &$args, $pargs ) {

			// Get Limit value
			$limit = trim( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'limit', $settings_ ) );
			$limit = ( empty( $limit ) || $limit === '-1' ) ? 10000000 : (int) $limit;
			$args['limit'] = $args['posts_per_page'] = $limit;

			// Get pagination enable/disable
			$pagination = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'enable-pagination', $settings_ );
			if ( $pagination ) {
				$field_setting = array();

				$prefix = PT_CV_PREFIX . 'pagination-';
				$fields = PT_CV_Functions::settings_keys( 'pagination-', PT_CV_Settings::settings_pagination() );
				PT_CV_Functions::settings_values( $fields, $field_setting, $settings_, $prefix );

				$dargs['pagination-settings'] = apply_filters( PT_CV_PREFIX_ . 'pagination_settings', $field_setting );

				// If Items per page is set, get its value
				$posts_per_page = isset( $dargs['pagination-settings']['items-per-page'] ) ? (int) $dargs['pagination-settings']['items-per-page'] : $limit;

				if ( $posts_per_page > $limit ) {
					$posts_per_page = $limit;
				}

				// Set 'posts_per_page' parameter
				$args['posts_per_page'] = $posts_per_page;

				// Get offset
				if ( isset( $pargs['page'] ) ) {
					$offset = $posts_per_page * ( (int) $pargs['page'] - 1 );

					// Reaching out of limit
					if ( $offset > $limit ) {
						return '';
					}

					// Set 'offset' parameter
					$args['offset'] = $offset;
				}
			}

			$args = apply_filters( PT_CV_PREFIX_ . 'settings_args_offset', $args, $pagination, $pargs, $settings_, $limit );
		}

		/**
		 * Get Other settings
		 *
		 * @param array $settings_ The settings array
		 * @param array $dargs     The settings array of Fields
		 */
		static function view_get_other_settings( $settings_, &$dargs ) {
			$field_setting = array();

			$prefix = PT_CV_PREFIX . 'other-';
			$fields = PT_CV_Functions::settings_keys( 'other-', PT_CV_Settings::settings_other() );
			PT_CV_Functions::settings_values( $fields, $field_setting, $settings_, $prefix );

			$dargs['other-settings'] = apply_filters( PT_CV_PREFIX_ . 'other_settings', $field_setting, $settings_ );
		}

		/**
		 * Process data when submit form add/edit view
		 *
		 * @return void
		 */
		static function view_submit() {
			if ( empty( $_POST ) ) {
				return;
			}

			PT_CV_Functions::_nonce_check( 'form_nonce', 'view_submit' );

			/**
			 * INSERT VIEW
			 */
			// View title
			$title = esc_sql( $_POST[PT_CV_PREFIX . 'view-title'] );

			// Current post id ( 0 if new view )
			$cur_post_id = esc_sql( $_POST[PT_CV_PREFIX . 'post-id'] );

			// Insert/update post
			$post_id = PT_CV_Functions::post_insert( array( 'ID' => $cur_post_id, 'title' => $title ) );

			/**
			 * ADD/UPDATE CUSTOM FIELDS
			 */
			// Get current view id, = 0 if it is new view
			$cur_view_id = esc_sql( $_POST[PT_CV_PREFIX . 'view-id'] );
			$view_id     = empty( $cur_view_id ) ? PT_CV_Functions::string_random() : $cur_view_id;
			update_post_meta( $post_id, PT_CV_META_ID, $view_id );
			update_post_meta( $post_id, PT_CV_META_SETTINGS, (array) $_POST );

			/**
			 * redirect to edit page
			 */
			$edit_link = PT_CV_Functions::view_link( $view_id );
			wp_redirect( $edit_link );
			exit;
		}

		/**
		 * Add shortcode
		 *
		 * @param array  $atts    Array of setting parameters for shortcode
		 * @param string $content Content of shortcode
		 */
		static function view_output( $atts, $content = '' ) {
			$atts = shortcode_atts(
				array(
					'id' => 0,
				),
				$atts
			);

			// View meta id
			$id = esc_sql( $atts['id'] );

			// Get View settings
			$settings = PT_CV_Functions::view_get_settings( $id );

			// Show View output
			return balanceTags( PT_CV_Functions::view_process_settings( $id, $settings ) );
		}

		/**
		 * Generate link to View page: Add view/ Edit view
		 *
		 * @param string $view_id The view id
		 *
		 * @return string
		 */
		public static function view_link( $view_id ) {

			$edit_link = admin_url( 'admin.php?page=' . PT_CV_DOMAIN . '-add' );
			if ( ! empty( $view_id ) ) {
				$query_args = array( 'id' => $view_id );
				$edit_link = add_query_arg( $query_args, $edit_link );
			}

			return $edit_link;
		}

		/**
		 * Callback function for ajax Preview action 'preview_request'
		 */
		static function ajax_callback_preview_request() {

			// Validate request
			check_ajax_referer( PT_CV_PREFIX_ . 'ajax_nonce', 'ajax_nonce' );

			do_action( PT_CV_PREFIX_ . 'preview_header' );

			// Request handle
			$settings = array();
			parse_str( $_POST['data'], $settings );

			// Store settings
			session_start();
			$_SESSION[PT_CV_PREFIX . 'settings'] = $settings;

			// Show View output
			echo balanceTags( PT_CV_Functions::view_process_settings( null, $settings ) );

			do_action( PT_CV_PREFIX_ . 'preview_footer' );
			// Must exit
			die;
		}

		/**
		 * Callback function for ajax Pagination action 'pagination_request'
		 */
		static function ajax_callback_pagination_request() {

			// Validate request
			check_ajax_referer( PT_CV_PREFIX_ . 'ajax_nonce', 'ajax_nonce' );

			// View meta id
			$id = esc_sql( $_POST['id'] );

			if ( empty( $id ) ) {
				// Get saved $settings
				session_start();
				if ( isset( $_SESSION[PT_CV_PREFIX . 'settings'] ) ) {
					$settings = $_SESSION[PT_CV_PREFIX . 'settings'];
				} else {
					$settings = '';
				}
			} else {
				// Get View settings
				$settings = PT_CV_Functions::view_get_settings( $id );
			}
			// Pagination settings
			$pargs = array( 'page' => (int) esc_sql( $_POST['page'] ) );

			// Show View output
			echo balanceTags( PT_CV_Functions::view_process_settings( $id, $settings, $pargs ) );

			// Must exit
			die;
		}
	}

}