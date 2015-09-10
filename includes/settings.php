<?php

/**
 * Define settings for options
 *
 * @package   PT_Content_Views
 * @author    PT Guy <palaceofthemes@gmail.com>
 * @license   GPL-2.0+
 * @link      http://www.contentviewspro.com/
 * @copyright 2014 PT Guy
 */
if ( !class_exists( 'PT_CV_Settings' ) ) {

	/**
	 * @name PT_CV_Settings
	 * @todo Define settings for options
	 */
	class PT_CV_Settings {

		/**
		 * Get collection : Taxonomies => Terms
		 *
		 * @param string $taxonomies Array of taxonomies
		 * @param array  $args       Array of query parameters
		 */
		static function terms_of_taxonomies( $taxonomies = array(), $args = array() ) {
			$terms_of_taxonomies = $result				 = array();
			// Get taxonomies
			$taxonomies			 = PT_CV_Values::taxonomy_list();
			// Get slug list of taxonomies
			$taxonomies_slug	 = array_keys( $taxonomies );

			foreach ( $taxonomies_slug as $taxonomy_slug ) {
				PT_CV_Values::term_of_taxonomy( $taxonomy_slug, $terms_of_taxonomies, $args );
			}

			foreach ( $terms_of_taxonomies as $taxonomy_slug => $terms ) {

				$result[ $taxonomy_slug ] = array(
					// Select term to filter
					array(
						'label'	 => array(
							'text' => __( 'Select terms', PT_CV_DOMAIN ),
						),
						'params' => array(
							array(
								'type'		 => 'select',
								'name'		 => $taxonomy_slug . '-terms[]',
								'options'	 => $terms,
								'std'		 => '',
								'class'		 => apply_filters( PT_CV_PREFIX_ . 'select_term_class', 'select2' ),
								'multiple'	 => '1',
							),
						),
					),
					// Quick filter
					apply_filters( PT_CV_PREFIX_ . 'term_quick_filter', array() ),
					//Operator
					array(
						'label'	 => array(
							'text' => __( 'Operator', PT_CV_DOMAIN ),
						),
						'params' => array(
							array(
								'type'		 => 'select',
								'name'		 => $taxonomy_slug . '-operator',
								'options'	 => PT_CV_Values::taxonomy_operators(),
								'std'		 => 'IN',
							),
						),
					),
				);
			}

			return $result;
		}

		/**
		 * Order by options
		 *
		 * @return array
		 */
		static function orderby() {
			$result = array();

			$result[ 'common' ] = array(
				// Order By
				array(
					'label'	 => array(
						'text' => __( 'Order by', PT_CV_DOMAIN ),
					),
					'params' => array(
						array(
							'type'		 => 'select',
							'name'		 => 'orderby',
							'options'	 => PT_CV_Values::post_regular_orderby(),
							'std'		 => '',
							'desc'		 => __( 'Select a criteria to sort by', PT_CV_DOMAIN ),
						),
					),
				),
				// Order
				array(
					'label'	 => array(
						'text' => __( 'Order', PT_CV_DOMAIN ),
					),
					'params' => array(
						array(
							'type'		 => 'radio',
							'name'		 => 'order',
							'options'	 => PT_CV_Values::orders(),
							'std'		 => 'asc',
						),
					),
				),
			);

			$result = apply_filters( PT_CV_PREFIX_ . 'orderby', $result );

			return $result;
		}

		/**
		 * Pagination settings
		 *
		 * @return array
		 */
		static function settings_pagination() {

			$prefix = 'pagination-';

			$result = array(
				// Pagination
				array(
					'label'	 => array(
						'text' => __( 'Pagination', PT_CV_DOMAIN ),
					),
					'params' => array(
						array(
							'type'		 => 'checkbox',
							'name'		 => 'enable-pagination',
							'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Enable', PT_CV_DOMAIN ) ),
							'std'		 => '',
						),
					),
				),
				// Items per page
				array(
					'label'		 => array(
						'text' => __( 'Items per page', PT_CV_DOMAIN ),
					),
					'params'	 => array(
						array(
							'type'			 => 'number',
							'name'			 => $prefix . 'items-per-page',
							'std'			 => '5',
							'placeholder'	 => 'e.g. 5',
							'append_text'	 => '1 &rarr; 100',
							'desc'			 => __( 'The number of items per page<br>If value of "Limit" option is not blank (empty), this value should be smaller than "Limit" value', PT_CV_DOMAIN ),
						),
					),
					'dependence' => array( 'enable-pagination', 'yes' ),
				),
				// Pagination Type
				array(
					'label'		 => array(
						'text' => __( 'Pagination type', PT_CV_DOMAIN ),
					),
					'params'	 => array(
						array(
							'type'		 => 'radio',
							'name'		 => $prefix . 'type',
							'options'	 => PT_CV_Values::pagination_types(),
							'std'		 => 'ajax',
						),
					),
					'dependence' => array( 'enable-pagination', 'yes' ),
				),
				// Pagination Style
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width' => 12,
						),
					),
					'params'		 => array(
						array(
							'type'	 => 'group',
							'params' => array(
								array(
									'label'		 => array(
										'text' => __( 'Pagination style', PT_CV_DOMAIN ),
									),
									'params'	 => array(
										array(
											'type'		 => 'radio',
											'name'		 => $prefix . 'style',
											'options'	 => PT_CV_Values::pagination_styles(),
											'std'		 => PT_CV_Functions::array_get_first_key( PT_CV_Values::pagination_styles() ),
										),
									),
									'dependence' => array( $prefix . 'type', 'normal', '!=' ),
								),
							),
						),
					),
					'dependence'	 => array( 'enable-pagination', 'yes' ),
				),
			);

			$result = apply_filters( PT_CV_PREFIX_ . 'settings_pagination', $result, $prefix );

			return $result;
		}

		/**
		 * Other settings for All View Type
		 */
		static function settings_other() {

			$prefix = 'other-';

			$result = array(
				// Open an item in
				array(
					'label'	 => array(
						'text' => __( 'Open item in', PT_CV_DOMAIN ),
					),
					'params' => array(
						array(
							'type'		 => 'radio',
							'name'		 => $prefix . 'open-in',
							'options'	 => PT_CV_Values::open_in(),
							'std'		 => PT_CV_Functions::array_get_first_key( PT_CV_Values::open_in() ),
							'desc'		 => __( 'How to open an item when click on Title, Thumbnail, Read more button', PT_CV_DOMAIN ),
						),
					),
				),
			);

			$result = apply_filters( PT_CV_PREFIX_ . 'settings_other', $result, $prefix );

			return $result;
		}

		/**
		 * Fields settings
		 */
		static function field_settings() {

			$prefix	 = 'field-';
			$prefix2 = 'show-' . $prefix;

			$result = array(
				// Fields display
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'wrap-class' => PT_CV_Html::html_group_class(),
							'width'		 => 12,
						),
					),
					'params'		 => array(
						array(
							'type'	 => 'group',
							'params' => PT_CV_Settings::field_display_settings(),
						),
					),
				),
				// Upgrade to Pro
				!get_option( 'pt_cv_version_pro' ) ? array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width' => 12,
						),
					),
					'params'		 => array(
						array(
							'type'		 => 'html',
							'content'	 => sprintf( '<p class="text-muted" style="margin-top: -10px; margin-bottom: 15px;">&rarr; %s</p>', __( 'Customize display order of above fields by a simple drag-and-drop ?', PT_CV_DOMAIN ) . sprintf( ' <a href="%s" target="_blank">%s</a>', esc_url( 'http://www.contentviewspro.com/pricing/?utm_source=client&utm_medium=view' ), __( 'Please upgrade to Pro', PT_CV_DOMAIN ) ) ),
						),
					),
				) : '',
				// Title settings
				apply_filters( PT_CV_PREFIX_ . 'settings_title_display', array(), $prefix, $prefix2 ),
				// Thumbnail settings
				array(
					'label'			 => array(
						'text' => __( 'Thumbnail settings', PT_CV_DOMAIN ),
					),
					'extra_setting'	 => array(
						'params' => array(
							'wrap-class' => PT_CV_Html::html_group_class() . ' ' . PT_CV_PREFIX . 'thumbnail-setting' . ' ' . PT_CV_PREFIX . 'w50',
						),
					),
					'params'		 => array(
						array(
							'type'	 => 'group',
							'params' => PT_CV_Settings::field_thumbnail_settings( $prefix ),
						),
					),
					'dependence'	 => array( $prefix2 . 'thumbnail', 'yes' ),
				),
				// Content settings
				array(
					'label'		 => array(
						'text' => __( 'Content settings', PT_CV_DOMAIN ),
					),
					'params'	 => array(
						array(
							'type'		 => 'radio',
							'name'		 => $prefix . 'content-show',
							'options'	 => array(
								'full'		 => __( 'Show Full Content', PT_CV_DOMAIN ),
								'excerpt'	 => __( 'Show Excerpt', PT_CV_DOMAIN ),
							),
							'std'		 => 'excerpt',
						),
					),
					'dependence' => array( $prefix2 . 'content', 'yes' ),
				),
				// Excerpt settings
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'wrap-id' => PT_CV_Html::html_group_id( 'excerpt-settings' ),
						),
					),
					'params'		 => array(
						array(
							'type'	 => 'group',
							'params' => apply_filters(
							PT_CV_PREFIX_ . 'excerpt_settings', array(
								// Excerpt length
								array(
									'label'			 => array(
										'text' => __( 'Excerpt settings', PT_CV_DOMAIN ),
									),
									'extra_setting'	 => array(
										'params' => array(
											'width' => 9,
										),
									),
									'params'		 => array(
										array(
											'type'			 => 'number',
											'name'			 => $prefix . 'excerpt-length',
											'std'			 => '20',
											'placeholder'	 => 'e.g. 20',
											'append_text'	 => 'words',
											'desc'			 => __( 'Generating excerpt by selecting the first X words of the content', PT_CV_DOMAIN ),
										),
									),
								),
								// Allow HTML tags
								array(
									'label'			 => array(
										'text' => '',
									),
									'extra_setting'	 => array(
										'params' => array(
											'wrap-class' => PT_CV_PREFIX . 'full-fields',
											'width'		 => 9,
										),
									),
									'params'		 => array(
										array(
											'type'		 => 'checkbox',
											'name'		 => $prefix . 'excerpt-allow_html',
											'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Allow HTML tags (a, br, strong, em, strike, i, ul, ol, li) in excerpt', PT_CV_DOMAIN ) ),
											'std'		 => '',
											'desc'		 => __( 'This option can cause broken HTML output. Please be careful when tick it', PT_CV_DOMAIN ),
										),
									),
								),
							), $prefix . 'excerpt-'
							),
						),
					),
					'dependence'	 => array( array( $prefix . 'content-show', 'excerpt' ) ),
				),
				// Meta fields settings
				array(
					'label'			 => array(
						'text' => __( 'Meta fields settings', PT_CV_DOMAIN ),
					),
					'extra_setting'	 => array(
						'params' => array(
							'wrap-class' => PT_CV_Html::html_group_class() . ' ' . PT_CV_PREFIX . 'meta-fields-settings',
						),
					),
					'params'		 => array(
						array(
							'type'	 => 'group',
							'params' => PT_CV_Settings::field_meta_fields( 'meta-fields-' ),
							'desc'	 => apply_filters( PT_CV_PREFIX_ . 'settings_sort_text', '' ),
						),
					),
					'dependence'	 => array( $prefix2 . 'meta-fields', 'yes' ),
				),
				// Taxonomies settings
				apply_filters( PT_CV_PREFIX_ . 'settings_taxonomies_display', array(), 'meta-fields-' ),
			);

			$result = apply_filters( PT_CV_PREFIX_ . 'field_settings', $result, $prefix2 );

			return $result;
		}

		/**
		 * Fields display
		 *
		 * @return array
		 */
		static function field_display_settings() {

			$field_display_settings = array(
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width'		 => 12,
							'wrap-class' => PT_CV_PREFIX . 'field-display',
						),
					),
					'params'		 => array(
						array(
							'type'	 => 'group',
							'params' => PT_CV_Settings::field_display(),
							'desc'	 => apply_filters( PT_CV_PREFIX_ . 'settings_sort_text', '' ),
						),
					),
				),
			);

			$result = apply_filters( PT_CV_PREFIX_ . 'field_display_settings', $field_display_settings );

			return $result;
		}

		/**
		 * Options to check/uncheck to display fields
		 *
		 * @return array
		 */
		static function field_display() {

			$prefix = 'show-field-';

			$result = array(
				// Thumbnail position
				array(
					'label'			 => array(
						'text' => __( 'Thumbnail position', PT_CV_DOMAIN ),
					),
					'extra_setting'	 => array(
						'params' => array(
							'wrap-class' => PT_CV_PREFIX . 'bg-none' . ' ' . PT_CV_PREFIX . 'w200',
						),
					),
					'params'		 => array(
						array(
							'type'		 => 'select',
							'name'		 => 'field-' . 'thumbnail-position',
							'options'	 => PT_CV_Values::thumbnail_position(),
							'std'		 => PT_CV_Functions::array_get_first_key( PT_CV_Values::thumbnail_position() ),
						),
					),
					'dependence'	 => array( 'layout-format', '2-col' ),
				),
				// Show Thumbnail
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width' => 12,
						),
					),
					'params'		 => array(
						array(
							'type'		 => 'checkbox',
							'name'		 => $prefix . 'thumbnail',
							'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Show Thumbnail', PT_CV_DOMAIN ) ),
							'std'		 => 'yes',
						),
					),
					'dependence'	 => array( 'layout-format', '1-col' ),
				),
				// Show Title
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width' => 12,
						),
					),
					'params'		 => array(
						array(
							'type'		 => 'checkbox',
							'name'		 => $prefix . 'title',
							'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Show Title', PT_CV_DOMAIN ) ),
							'std'		 => 'yes',
						),
					),
				),
				// Show Content
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width' => 12,
						),
					),
					'params'		 => array(
						array(
							'type'		 => 'checkbox',
							'name'		 => $prefix . 'content',
							'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Show Content', PT_CV_DOMAIN ) ),
							'std'		 => 'yes',
						),
					),
				),
				// Show Meta fields
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width' => 12,
						),
					),
					'params'		 => array(
						array(
							'type'		 => 'checkbox',
							'name'		 => $prefix . 'meta-fields',
							'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Show Meta Fields (Author, Date, Comment...)', PT_CV_DOMAIN ) ),
							'std'		 => '',
						),
					),
				),
			);

			// Add/remove params
			$result = apply_filters( PT_CV_PREFIX_ . 'field_display', $result, $prefix );

			// Sort array of params by saved order
			$result = apply_filters( PT_CV_PREFIX_ . 'settings_sort', $result, PT_CV_PREFIX . $prefix );

			return $result;
		}

		/**
		 * Setting options for Field = Thumbnail
		 */
		static function field_thumbnail_settings( $prefix ) {

			$result = array(
				// Size
				array(
					'label'			 => array(
						'text' => __( 'Thumbnail size', PT_CV_DOMAIN ),
					),
					'extra_setting'	 => array(
						'params' => array(
							'width' => 9,
						),
					),
					'params'		 => array(
						array(
							'type'		 => 'select',
							'name'		 => $prefix . 'thumbnail-size',
							'options'	 => PT_CV_Values::field_thumbnail_sizes(),
							'std'		 => 'medium',
						),
					),
				),
			);

			$result = apply_filters( PT_CV_PREFIX_ . 'field_thumbnail_settings', $result, $prefix );

			return $result;
		}

		/**
		 * Show settings of other fields
		 */
		static function field_meta_fields( $prefix ) {

			$result = array(
				// Date
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width' => 12,
						),
					),
					'params'		 => array(
						array(
							'type'		 => 'checkbox',
							'name'		 => $prefix . 'date',
							'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Show Date', PT_CV_DOMAIN ) ),
							'std'		 => 'yes',
						),
					),
				),
				// Author
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width' => 12,
						),
					),
					'params'		 => array(
						array(
							'type'		 => 'checkbox',
							'name'		 => $prefix . 'author',
							'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Show Author', PT_CV_DOMAIN ) ),
							'std'		 => 'yes',
						),
					),
				),
				// Taxonomy
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width' => 12,
						),
					),
					'params'		 => array(
						array(
							'type'		 => 'checkbox',
							'name'		 => $prefix . 'taxonomy',
							'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Show Taxonomies (categories, tags...)', PT_CV_DOMAIN ) ),
							'std'		 => 'yes',
						),
					),
					'dependence'	 => array( 'content-type', 'page', '!=' ),
				),
				// Comment
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width' => 12,
						),
					),
					'params'		 => array(
						array(
							'type'		 => 'checkbox',
							'name'		 => $prefix . 'comment',
							'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Show Comment Count', PT_CV_DOMAIN ) ),
							'std'		 => 'yes',
						),
					),
				),
			);

			// Sort array of params by saved order
			$result = apply_filters( PT_CV_PREFIX_ . 'settings_sort', $result, PT_CV_PREFIX . $prefix );

			return $result;
		}

		/**
		 * Settings of View Type = Grid
		 *
		 * @return array
		 */
		static function view_type_settings_grid() {

			$prefix = 'grid-';

			$result = array(
				// Number of columns
				array(
					'label'		 => array(
						'text' => __( 'Items per row', PT_CV_DOMAIN ),
					),
					'params'	 => array(
						array(
							'type'			 => 'number',
							'name'			 => $prefix . 'number-columns',
							'std'			 => '2',
							'append_text'	 => '1 &rarr; 4',
							'desc'			 => __( 'The number of items per row of grid', PT_CV_DOMAIN ),
						),
					),
					'dependence' => array( 'view-type', 'grid' ),
				),
			);

			$result = apply_filters( PT_CV_PREFIX_ . 'view_type_settings_grid', $result );

			return $result;
		}

		/**
		 * Settings of View Type = Collapsible
		 *
		 * @return array
		 */
		static function view_type_settings_collapsible() {

			$prefix = 'collapsible-';

			$result = array(
				PT_CV_Settings::setting_no_option(),
			);

			$result = apply_filters( PT_CV_PREFIX_ . 'view_type_settings_collapsible', $result );

			return $result;
		}

		/**
		 * Settings of View Type = Scrollable
		 *
		 * @return array
		 */
		static function view_type_settings_scrollable() {

			$prefix = 'scrollable-';

			$result = array(
				PT_CV_Settings::setting_no_option(),
			);

			$result = apply_filters( PT_CV_PREFIX_ . 'view_type_settings_scrollable', $result );

			return $result;
		}

		/**
		 * Setting with no option
		 *
		 * @return array
		 */
		static function setting_no_option() {

			return array(
				'label'			 => array(
					'text' => '',
				),
				'extra_setting'	 => array(
					'params' => array(
						'width' => 12,
					),
				),
				'params'		 => array(
					array(
						'type'		 => 'html',
						'content'	 => "<div class='" . PT_CV_PREFIX . "text'>" . __( 'There is no option', PT_CV_DOMAIN ) . '</div>',
					),
				),
			);
		}

	}

}