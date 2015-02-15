<?php
/**
 * Contain main functions to work with plugin, post, custom fields...
 *
 * @package   PT_Content_Views
 * @author    PT Guy <palaceofthemes@gmail.com>
 * @license   GPL-2.0+
 * @link      http://www.contentviewspro.com/
 * @copyright 2014 PT Guy
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
		 *
		 * @param string  $version_to_compare
		 * @param string  $operator
		 *
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
		 * Get current language of site
		 */
		static function get_language() {
			$language = '';

			// WPML
			global $sitepress;
			if ( $sitepress && method_exists( $sitepress, 'get_current_language' ) ) {
				$language = $sitepress->get_current_language();
			}

			return $language;
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
		 * @param string $user_role   Required role to see this menu
		 * @param string $sub_page    Slug of sub menu
		 * @param string $class       Class name which contains function to output content of page created by this menu
		 */
		static function menu_add_sub( $parent_slug, $page_title, $menu_title, $user_role, $sub_page, $class ) {
			return add_submenu_page(
				$parent_slug, $page_title, $menu_title, $user_role, $parent_slug . '-' . $sub_page, array( $class, 'display_sub_page_' . $sub_page )
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
			$slug = preg_replace( '/[^a-zA-Z]+/', ' ', $slug );

			return ucwords( $slug );
		}

		/**
		 * Trims text to a certain number of words.
		 * @since 1.4.3
		 * @param string $text
		 * @param int $num_words
		 * @return string
		 */
		static function wp_trim_words( $text, $num_words = 500 ) {
			// Strip all shortcodes
			$text   = strip_shortcodes( $text );
			// Strip HTML tags
			$result = self::pt_strip_tags( $text );
			// Split words
			$array  = preg_split( "/[\n\r\t ]+/", $result, $num_words + 1, PREG_SPLIT_NO_EMPTY );
			//  Already short enough, return the whole thing
			if ( count( $array ) > $num_words )
			{
				array_splice( $array, $num_words );
				$result = implode( ' ', $array );
			}

			return $result;
		}

		/**
		 * Custom strip tags, allow some tags
		 *
		 * @since 1.4.6
		 * @param string $string
		 * @return string
		 */
		static function pt_strip_tags( $string ) {
			$string = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $string );

			# allow some tags
			global $dargs;
			# predefined allowable HTML tags
			$allowable_tags = (array) apply_filters( PT_CV_PREFIX_ . 'allowable_tags', array( '<a>', '<br>', '<strong>', '<em>', '<strike>', '<i>', '<ul>', '<ol>', '<li>' ) );
			$allowed_tags = ! empty( $dargs['field-settings']['content']['allow_html'] ) ? implode( '', $allowable_tags ) : '';
			$string       = strip_tags( $string, $allowed_tags );

			return trim( $string );
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
		 * @param string     $field        The full name of setting to get value
		 * @param array      $array_to_get Array to get values of wanted setting
		 * @param mixed|null $assign       The value to assign if setting is not found
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
				if ( isset( $option['params'] ) ) {
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
		 * @param string $prefix  The prefix in name of setting options
		 * @param bool   $backend Get settings from Backend form
		 */
		static function settings_values_by_prefix( $prefix, $backend = FALSE ) {
			global $pt_view_settings;

			if ( ! $pt_view_settings && $backend ) {
				global $pt_cv_admin_settings;
				$pt_view_settings = $pt_cv_admin_settings;
			}

			$result = array();

			foreach ( (array) $pt_view_settings as $name => $value ) {
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
			global $pt_post_terms;


			// List of HTML link to terms
			$links = array();

			// Get list of taxonomies
			$taxonomies = get_taxonomies( '', 'names' );

			$taxonomies = apply_filters( PT_CV_PREFIX_ . 'taxonomies_list', $taxonomies );

			// Get post ID
			$post_id = is_object( $post ) ? $post->ID : $post;

			// Get lists of terms of this post
			$terms = wp_get_object_terms( $post_id, $taxonomies );

			foreach ( $terms as $term ) {
				$links[] = sprintf(
					'<a href="%1$s" title="%2$s %3$s">%3$s</a>',
					esc_url( get_term_link( $term, $term->taxonomy ) ),
					__( 'View all posts in', PT_CV_DOMAIN ),
					$term->name
				);

				if ( ! isset( $pt_post_terms[$post_id] ) ) {
					$pt_post_terms[$post_id] = array();
				}
				$pt_post_terms[$post_id][$term->slug] = $term->name;
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
		 * Insert/Update post
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
		 *
		 * @return int Return Post ID of this view
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
					'meta_value'  => esc_sql( $meta_id ),
				)
			);
			if ( $pt_query->have_posts() ) :
				while ( $pt_query->have_posts() ):
					$pt_query->the_post();
					$post_id = get_the_ID();
				endwhile;
			endif;

			// Restore $wp_query and original Post Data
			wp_reset_query();

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
			return current( array_keys( (array) $args ) );
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

			$post_id = apply_filters( PT_CV_PREFIX_ . 'view_get_post_id', PT_CV_Functions::post_id_from_meta_id( $meta_id ), $meta_id );

			// Get view settings
			if ( $post_id ) {
				$view_settings = get_post_meta( $post_id, PT_CV_META_SETTINGS, true );

				/* Backward compatibility
				 * since 1.3.2
				 */
				self::view_backward_comp( $view_settings );

				return is_array( $view_settings ) ? $view_settings : array();
			}

			return array();
		}

		/**
		 * Update values for some new options in new version (from options in old version)
		 *
		 * @param type $view_settings
		 */
		static function view_backward_comp( &$view_settings ) {
			if ( ! $view_settings ) {
				return $view_settings;
			}

			// Taxonomy In, Not in
			$taxonomies = isset( $view_settings[PT_CV_PREFIX . 'taxonomy'] ) ? $view_settings[PT_CV_PREFIX . 'taxonomy'] : array();
			if ( is_array( $taxonomies ) ) {
				$list = array( '__in', '__not_in' );
				foreach ( $taxonomies as $taxonomy ) {
					// Check if IN/NOT IN list has values. NOT IN list will overwite IN list
					foreach ( $list as $ltype ) {
						if ( isset( $view_settings[PT_CV_PREFIX . $taxonomy . $ltype] ) ) {
							$view_settings[PT_CV_PREFIX . $taxonomy . '-terms']    = $view_settings[PT_CV_PREFIX . $taxonomy . $ltype];
							$view_settings[PT_CV_PREFIX . $taxonomy . '-operator'] = ( $ltype == '__in' ) ? 'IN' : 'NOT IN';
						}
					}
				}
			}

			$view_settings = apply_filters( PT_CV_PREFIX_ . 'backward_comp', $view_settings );
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
			if ( empty( $settings ) ) {
				return __( 'Empty settings', PT_CV_DOMAIN );
			}

			/**
			 * Check if this view is processed in this page
			 * @since 1.5.2
			 */
			global $processed_views;
			if ( ! empty( $processed_views[$id] ) ) {
				return '';
			}
			$processed_views[$id] = 1;

			// Escaped value appropriate for use in a SQL query
			global $pt_view_settings;

			$pt_view_settings = array();
			foreach ( $settings as $key => $value ) {
				$pt_view_settings[$key] = esc_sql( $value );
			}

			// Get content type
			global $pt_content_type;
			$pt_content_type = $content_type = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'content-type', $pt_view_settings );

			// Get view type
			global $pt_view_type;
			$pt_view_type = $view_type = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'view-type', $pt_view_settings );

			// Store display settings
			global $dargs, $pt_query_args;

			$session_id = ( $pargs && isset( $pargs['session_id'] ) ) ? $pargs['session_id'] : 0;

			// If is pagination request
			if ( $session_id ) {
				$session_data = array_merge(
					array( '$args' => '', '$dargs' => '' ),
					( false === ( $saved_settings = get_transient( PT_CV_PREFIX . 'view-data-' . $session_id ) ) ) ? array() : $saved_settings
				);

				$args  = $session_data['$args'];
				$dargs = $session_data['$dargs'];
			} else {
				global $pt_view_sid;
				// If id is passed, assign it to session id, otherwise, generate random number
				$pt_view_sid = $session_id = $id ? $id : PT_CV_Functions::string_random();

				// Store settings
				set_transient( PT_CV_PREFIX . 'view-settings-' . $session_id, $settings, 7 * DAY_IN_SECONDS );
			}

			if ( empty( $args ) || empty( $dargs ) ) {
				$args  = PT_CV_Functions::view_filter_settings( $content_type, $pt_view_settings );
				$dargs = PT_CV_Functions::view_display_settings( $view_type );

				// Filter
				$dargs = apply_filters( PT_CV_PREFIX_ . 'all_display_settings', $dargs );
				$args  = apply_filters( PT_CV_PREFIX_ . 'query_parameters', $args );

				// Store view data
				set_transient(
					PT_CV_PREFIX . 'view-data-' . $session_id,
					array(
						'$args'  => $args,
						'$dargs' => $dargs,
					), 7 * DAY_IN_SECONDS
				);
			}

			// Pagination settings
			PT_CV_Functions::view_get_pagination_settings( $dargs, $args, $pargs );

			// Validate settings, if some required parameters are missing, show error and exit
			$error = apply_filters( PT_CV_PREFIX_ . 'validate_settings', array(), $args );

			// Return error message
			if ( $error ) {
				return ( implode( '</p><p>', $error ) );
			}

			// Update global query parameters variable
			$pt_query_args = $args;

			do_action( PT_CV_PREFIX_ . 'add_global_variables' );

			/**
			 * Output Items
			 */

			// Store HTML output of each item
			$content_items = array();

			// The Query
			do_action( PT_CV_PREFIX_ . 'before_query' );

			$pt_query = new WP_Query( $args );

			do_action( PT_CV_PREFIX_ . 'after_query' );

			// The Loop
			if ( $pt_query->have_posts() ) {
				while ( $pt_query->have_posts() ) {
					$pt_query->the_post();
					global $post;

					// Output HTML for this item
					$post_id = apply_filters( PT_CV_PREFIX_ . 'show_this_post', $post->ID );
					if ( $post_id ) {
						$content_items[$post->ID] = PT_CV_Html::view_type_output( $view_type, $post );
					}
				}
			} else {
				// Get no post found class
				$_class = apply_filters( PT_CV_PREFIX_ . 'content_no_post_found_class', 'alert alert-warning' );

				// Get no post found text
				$_text = apply_filters( PT_CV_PREFIX_ . 'content_no_post_found_text', __( 'No post found', PT_CV_DOMAIN ) );

				// Output HTML
				$content_items[] = sprintf( '<div class="%1$s">%2$s</div>', esc_attr( $_class ), balanceTags( $_text ) );
			}

			// Restore $wp_query and original Post Data
			wp_reset_query();

			// Filter array of items
			$content_items = apply_filters( PT_CV_PREFIX_ . 'content_items', $content_items );

			/**
			 * Output Pagination
			 */
			$current_page = self::get_current_page( $pargs );
			$html         = PT_CV_Html::content_items_wrap( $content_items, $current_page, $args['posts_per_page'], $id );

			// Append Pagination HTML if this is first page, or not Ajax calling
			$type  = isset( $dargs['pagination-settings']['type'] ) ? $dargs['pagination-settings']['type'] : 'ajax';
			if ( $args['posts_per_page'] > 0 && ( ( $type == 'ajax' && $current_page === 1 ) || $type == 'normal' ) ) {
				// Total post founds
				$found_posts = apply_filters( PT_CV_PREFIX_ . 'found_posts', $pt_query->found_posts );

				// Total number of items
				$total_items = ( $args['limit'] > 0 && $found_posts > $args['limit'] ) ? $args['limit'] : $found_posts;

				// Total number of pages
				$max_num_pages = ceil( $total_items / $args['posts_per_page'] );

				// Output pagination
				$html .= "\n" . PT_CV_Html::pagination_output( $max_num_pages, $current_page, $session_id );
			}

			return $html;
		}

		/**
		 * Get query parameters of View
		 *
		 * @param string $content_type     The current content type
		 * @param array  $pt_view_settings The settings of View
		 *
		 * @return array
		 */
		static function view_filter_settings( $content_type, $pt_view_settings ) {
			/**
			 * Get Query parameters
			 * Set default values
			 */
			$args = array(
				'post_type'           => $content_type,
				'post_status'         => 'publish',
				'ignore_sticky_posts' => apply_filters( PT_CV_PREFIX_ . 'ignore_sticky_posts', 1 ),
			);

			// Post in
			if ( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'post__in', $pt_view_settings ) ) {
				$post_in          = PT_CV_Functions::string_to_array( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'post__in', $pt_view_settings ) );
				$args['post__in'] = array_map( 'intval', array_filter( $post_in ) );
			}

			// Post not in
			if ( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'post__not_in', $pt_view_settings ) ) {
				$post_not_in          = PT_CV_Functions::string_to_array( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'post__not_in', $pt_view_settings ) );
				$args['post__not_in'] = array_map( 'intval', array_filter( $post_not_in ) );
			}

			$args['post__not_in'] = apply_filters( PT_CV_PREFIX_ . 'post__not_in', isset( $args['post__not_in'] ) ? $args['post__not_in'] : array(), $pt_view_settings );

			// Parent page
			if ( $content_type == 'page' ) {
				$post_parent = apply_filters( PT_CV_PREFIX_ . 'post_parent_id', PT_CV_Functions::setting_value( PT_CV_PREFIX . 'post_parent', $pt_view_settings ) );
				if ( ! empty( $post_parent ) ) {
					$args['post_parent'] = (int) $post_parent;
				}
			}

			// Advance settings
			PT_CV_Functions::view_get_advanced_settings( $args, $content_type );

			return $args;
		}

		/**
		 * Get display parameters of View
		 *
		 * @param string $view_type The view type of View
		 *
		 * @return array
		 */
		static function view_display_settings( $view_type ) {
			/**
			 * Get Display parameters
			 */
			$dargs = array();

			$dargs['view-type'] = $view_type;

			// Field settings of a item
			PT_CV_Functions::view_get_display_settings( $dargs );

			// Other settings
			PT_CV_Functions::view_get_other_settings( $dargs );

			// View type settings
			$dargs['view-type-settings'] = PT_CV_Functions::settings_values_by_prefix( PT_CV_PREFIX . $view_type . '-' );

			return $dargs;
		}

		/**
		 * Get Advance settings
		 *
		 * @param array  $args         The parameters array
		 * @param string $content_type The content type
		 */
		static function view_get_advanced_settings( &$args, $content_type ) {
			global $pt_view_settings;

			$advanced_settings = (array) PT_CV_Functions::setting_value( PT_CV_PREFIX . 'advanced-settings', $pt_view_settings );
			if ( $advanced_settings ) {
				foreach ( $advanced_settings as $setting ) {
					switch ( $setting ) {

						// Author
						case 'author':
							$author_in = PT_CV_Functions::string_to_array( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'author__in', $pt_view_settings ) );

							// Check if using Wordpress version 3.7 or higher
							$version_gt_37 = PT_CV_Functions::wp_version_compare( '3.7' );

							if ( $version_gt_37 ) {
								$author_not_in = PT_CV_Functions::string_to_array( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'author__not_in', $pt_view_settings ) );

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
									'post_status' => PT_CV_Functions::string_to_array( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'post_status', $pt_view_settings, 'publish' ) ),
								)
							);
							break;

						// Search
						case 'search':
							if ( PT_CV_Functions::setting_value( PT_CV_PREFIX . 's', $pt_view_settings ) ) {
								$args = array_merge(
									$args, array(
										's' => PT_CV_Functions::setting_value( PT_CV_PREFIX . 's', $pt_view_settings ),
									)
								);
							}
							break;

						// Taxonomy
						case 'taxonomy':
							// No taxonomy found
							if ( ! PT_CV_Functions::setting_value( PT_CV_PREFIX . 'taxonomy', $pt_view_settings ) ) {
								break;
							}

							// All settings of taxonomies
							$taxonomy_setting = array();

							// Selected taxonomies
							$taxonomies = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'taxonomy', $pt_view_settings );

							// Get Terms & criterias (In, Not in)
							foreach ( $taxonomies as $taxonomy ) {
								if ( PT_CV_Functions::setting_value( PT_CV_PREFIX . $taxonomy . '-terms', $pt_view_settings ) ) {
									// Get operator
									$operator = PT_CV_Functions::setting_value( PT_CV_PREFIX . $taxonomy . '-operator', $pt_view_settings, 'IN' );

									$taxonomy_setting[] = array(
										'taxonomy' => $taxonomy,
										'field'    => 'slug',
										'terms'    => (array) PT_CV_Functions::setting_value( PT_CV_PREFIX . $taxonomy . '-terms', $pt_view_settings ),
										'operator' => $operator,
									);
								}
							}

							// Get Taxonomy relation if there are more than 1 selected taxonomies | set In & Not in of a taxonomy
							if ( count( $taxonomies ) > 1 || count( $taxonomy_setting ) > 1 ) {
								$taxonomy_setting['relation'] = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'taxonomy-relation', $pt_view_settings, 'AND' );
							}

							// Filter taxonomy with Custom post types
							$taxonomy_setting_ = apply_filters( PT_CV_PREFIX_ . 'taxonomy_setting', $taxonomy_setting );

							$args = array_merge( $args, array( 'tax_query' => $taxonomy_setting_ ) );
							break;

						// Order
						case 'order':
							$order_settings = apply_filters(
								PT_CV_PREFIX_ . 'order_setting',
								array(
									'orderby' => PT_CV_Functions::setting_value( PT_CV_PREFIX . 'orderby', $pt_view_settings ),
									'order'   => PT_CV_Functions::setting_value( PT_CV_PREFIX . 'order', $pt_view_settings ),
								)
							);

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
		 * @param array $dargs The settings array of Fields
		 */
		static function view_get_display_settings( &$dargs ) {
			global $pt_view_settings;

			$view_type = $dargs['view-type'];

			/**
			 * Layout format
			 */
			$dargs['layout-format'] = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'layout-format', $pt_view_settings );

			/**
			 * Columns count & Rows count
			 */
			$dargs['number-columns'] = PT_CV_Functions::setting_value( PT_CV_PREFIX . $view_type . '-' . 'number-columns', $pt_view_settings, 1 );
			$dargs['number-rows']    = PT_CV_Functions::setting_value( PT_CV_PREFIX . $view_type . '-' . 'number-rows', $pt_view_settings, 1 );

			/**
			 * Fields settings
			 */
			$cfields_settings = PT_CV_Functions::settings_values_by_prefix( PT_CV_PREFIX . 'show-field-' );
			$cfields          = (array) array_keys( (array) $cfields_settings );
			foreach ( $cfields as $field ) {
				// If show this field
				if ( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'show-field-' . $field, $pt_view_settings ) ) {
					// Add this field to display fields list
					$dargs['fields'][] = $field;

					// Get field settings
					switch ( $field ) {

						// Get title settings
						case 'title':
							$prefix        = PT_CV_PREFIX . 'field-title-';
							$field_setting = PT_CV_Functions::settings_values_by_prefix( $prefix );

							$dargs['field-settings'][$field] = apply_filters( PT_CV_PREFIX_ . 'field_title_setting_values', $field_setting, $prefix );

							break;

						// Get thumbnail settings
						case 'thumbnail':
							$prefix        = PT_CV_PREFIX . 'field-thumbnail-';
							$field_setting = PT_CV_Functions::settings_values_by_prefix( $prefix );

							$dargs['field-settings'][$field] = apply_filters( PT_CV_PREFIX_ . 'field_thumbnail_setting_values', $field_setting, $prefix );

							break;

						// Get meta fields settings
						case 'meta-fields':
							$prefix        = PT_CV_PREFIX . 'meta-fields-';
							$field_setting = PT_CV_Functions::settings_values_by_prefix( $prefix );

							$dargs['field-settings'][$field] = apply_filters( PT_CV_PREFIX_ . 'field_meta_fields_setting_values', $field_setting, $prefix );

							break;

						// Get content settings
						case 'content':
							$prefix        = PT_CV_PREFIX . 'field-content-';
							$field_setting = PT_CV_Functions::settings_values_by_prefix( $prefix );

							if ( $field_setting['show'] == 'excerpt' ) {
								$field_setting = array_merge( $field_setting, PT_CV_Functions::settings_values_by_prefix( PT_CV_PREFIX . 'field-excerpt-' ) );
							}

							$dargs['field-settings'][$field] = apply_filters( PT_CV_PREFIX_ . 'field_content_setting_values', $field_setting, $prefix );

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
		 * @param array $dargs The settings array of Fields
		 * @param array $args  The parameters array
		 * @param array $pargs The pagination settings array
		 */
		static function view_get_pagination_settings( &$dargs, &$args, $pargs ) {
			global $pt_view_settings;

			// Get Limit value
			$limit         = trim( PT_CV_Functions::setting_value( PT_CV_PREFIX . 'limit', $pt_view_settings ) );
			$limit         = ( empty( $limit ) || $limit === '-1' ) ? 10000000 : (int) $limit;
			$args['limit'] = $args['posts_per_page'] = $limit;
			$offset        = 0;

			// Get pagination enable/disable
			$pagination = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'enable-pagination', $pt_view_settings );
			if ( $pagination ) {
				$prefix        = PT_CV_PREFIX . 'pagination-';
				$field_setting = PT_CV_Functions::settings_values_by_prefix( $prefix );

				$dargs['pagination-settings'] = apply_filters( PT_CV_PREFIX_ . 'pagination_settings', $field_setting, $prefix );

				// If Items per page is set, get its value
				$posts_per_page = isset( $dargs['pagination-settings']['items-per-page'] ) ? (int) $dargs['pagination-settings']['items-per-page'] : $limit;

				if ( $posts_per_page > $limit ) {
					$posts_per_page = $limit;
				}

				// Set 'posts_per_page' parameter
				$args['posts_per_page'] = $posts_per_page;

				// Get offset
				$paged = self::get_current_page( $pargs );

				$offset = $posts_per_page * ( (int) $paged - 1 );

				// Update posts_per_page
				if ( intval( $args['posts_per_page'] ) > $limit - $offset ) {
					$args['posts_per_page'] = $limit - $offset;
				}
			}

			$offset = apply_filters( PT_CV_PREFIX_ . 'settings_args_offset', $offset );

			// Set 'offset' parameter
			$args['offset'] = $offset;
		}

		/**
		 * Get Other settings
		 *
		 * @param array $dargs The settings array of Fields
		 */
		static function view_get_other_settings( &$dargs ) {
			$prefix        = PT_CV_PREFIX . 'other-';
			$field_setting = PT_CV_Functions::settings_values_by_prefix( $prefix );

			$dargs['other-settings'] = apply_filters( PT_CV_PREFIX_ . 'other_settings', $field_setting );
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

			// Insert post
			if ( ! $cur_post_id ) {
				$post_id = PT_CV_Functions::post_insert( array( 'ID' => $cur_post_id, 'title' => $title ) );
			} else {
				$post_id = $cur_post_id;
			}

			/**
			 * ADD/UPDATE CUSTOM FIELDS
			 */
			// Get current view id, = 0 if it is new view
			$cur_view_id = esc_sql( $_POST[PT_CV_PREFIX . 'view-id'] );
			$view_id     = empty( $cur_view_id ) ? PT_CV_Functions::string_random() : $cur_view_id;
			update_post_meta( $post_id, PT_CV_META_ID, $view_id );
			update_post_meta( $post_id, PT_CV_META_SETTINGS, (array) $_POST );

			// Update post title
			if ( strpos( $title, '[ID:' ) === false ) {
				PT_CV_Functions::post_insert( array( 'ID' => $post_id, 'title' => sprintf( '%s [ID: %s]', $title, $view_id ) ) );
			}

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
				apply_filters(
					PT_CV_PREFIX_ . 'shortcode_params',
					array(
						'id' => 0,
					)
				),
				$atts
			);

			global $pt_cv_shortcode_params;
			$pt_cv_shortcode_params = $atts;

			// View meta id
			$id = esc_sql( $atts['id'] );

			// Get View settings
			$settings = PT_CV_Functions::view_get_settings( $id );

			// Show View output
			$view_html = balanceTags( PT_CV_Functions::view_process_settings( $id, $settings ) );

			return PT_CV_Functions::view_final_output( $view_html );
		}

		/**
		 * Final output of View: HTML & Assets
		 *
		 * @param string $html
		 */
		static function view_final_output( $html ) {
			// Print View assets
			ob_start();
			PT_CV_Html::assets_of_view_types();
			$view_assets = ob_get_clean();

			return $html . $view_assets;
		}

		/**
		 * Generate link to View page: Add view/ Edit view
		 *
		 * @param string $view_id The view id
		 * @param array  $action  Custom parameters
		 *
		 * @return string
		 */
		public static function view_link( $view_id, $action = array() ) {

			$edit_link = admin_url( 'admin.php?page=' . PT_CV_DOMAIN . '-add' );
			if ( ! empty( $view_id ) ) {
				$query_args = array( 'id' => $view_id ) + $action;
				$edit_link  = add_query_arg( $query_args, $edit_link );
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

			// Session id
			$session_id = empty( $_POST['sid'] ) ? '' : esc_sql( $_POST['sid'] );

			// Get saved $settings
			$settings = get_transient( PT_CV_PREFIX . 'view-settings-' . $session_id );

			// If empty, get settings by ID
			if ( ! $settings ) {
				$settings = PT_CV_Functions::view_get_settings( $session_id );
			}

			// Pagination settings
			$pargs = array( 'session_id' => $session_id, 'page' => (int) esc_sql( $_POST['page'] ) );

			// Language
			$language = empty( $_POST['lang'] ) ? '' : esc_sql( $_POST['lang'] );
			// WPML
			global $sitepress;
			if ( $sitepress && $language ) {
				$sitepress->switch_lang( $language, true );
			}

			// Show View output
			echo balanceTags( PT_CV_Functions::view_process_settings( null, $settings, $pargs ) );

			// Must exit
			die;
		}

		/**
		 * Show promotion text in View page
		 */
		static function util_show_promo_view() {
			$pro_installed = get_option( 'pt_cv_version_pro' );
			if ( ! $pro_installed ) {
				?>
				<div class="pull-right" style="margin-top: -54px;">
					<a class="btn btn-success" target="_blank" href="http://www.contentviewspro.com/pricing/?utm_source=client&utm_medium=view">&#187; Get Pro version</a>
					or
					<a class="btn btn-info" target="_blank" href="http://sample.contentviewspro.com/?utm_source=client&utm_medium=view">Check demo site</a>
				</div>
			<?php
			}
		}

		/**
		 * Generate pagination button for each page
		 * @param string $class     Class name
		 * @param string $this_page Page number
		 * @param string $label     Page label
		 */
		static function pagination_generate_link( $class, $this_page, $label = '' ) {
			$data_page = '';

			if ( ! $label ) {
				$label = $this_page;
				$data_page = sprintf( ' data-page="%s"', $this_page );
			}

			$html  = sprintf( '<a%s href="%s">%s</a>', $data_page, add_query_arg( 'vpage', $this_page ), $label );
			$class = $class ? sprintf( ' class="%s"', esc_attr( $class ) ) : '';

			return sprintf( '<li%s>%s</li>', $class, $html );
		}

		/**
		 * Pagination output
		 *
		 * @param int $total_pages   Total pages
		 * @param int $current_page  Current page number
		 * @param int $pages_to_show Number of page to show
		 */
		static function pagination( $total_pages, $current_page = 1, $pages_to_show = 4 ) {
			if ( $total_pages == 1 )
				return '';

			$pages_to_show = apply_filters( PT_CV_PREFIX_ . 'pages_to_show', $pages_to_show );

			// Define labels
			$labels = apply_filters( PT_CV_PREFIX_ . 'pagination_label', array(
				'prev'  => '&lsaquo;',
				'next'  => '&rsaquo;',
				'first' => '&laquo;',
				'last'  => '&raquo;',
			) );

			$start = ( ( $current_page - $pages_to_show ) > 0 ) ? $current_page - $pages_to_show : 1;
			$end   = ( ( $current_page + $pages_to_show ) < $total_pages ) ? $current_page + $pages_to_show : $total_pages;

			$html  = '';

			$compared_page = 1;
			// First
			if ( $start > $compared_page ) {
				$html .= self::pagination_generate_link( '', $compared_page, $labels['first'] );
			}
			// Prev
			if ( $current_page > $compared_page ) {
				$html  .= self::pagination_generate_link( '', $current_page - 1, $labels['prev'] );
			}

			for ( $i = $start ; $i <= $end; $i++ ) {
				$html .= self::pagination_generate_link( ( $current_page == $i ) ? 'active' : '', $i );
			}

			$compared_page = $total_pages;
			// Next
			if ( $current_page < $total_pages ) {
				$html  .= self::pagination_generate_link( '', $current_page + 1, $labels['next'] );
			}
			// Last
			if ( $end < $compared_page ) {
				$html .= self::pagination_generate_link( '', $compared_page, $labels['last'] );
			}

			return $html;
		}

		/**
		 * Get current page number
		 */
		static function get_current_page( $pargs ) {
			$paged = 1;

			if ( ! empty( $pargs['page'] ) ) {
				$paged = intval( $pargs['page'] );
			}

			if ( ! empty( $_GET['vpage'] ) ) {
				$paged = intval( $_GET['vpage'] );
			}

			return $paged;
		}

	}

}