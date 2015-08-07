<?php

/**
 * Content Views Admin
 *
 * @package   PT_Content_Views_Admin
 * @author    PT Guy <palaceofthemes@gmail.com>
 * @license   GPL-2.0+
 * @link      http://www.contentviewspro.com/
 * @copyright 2014 PT Guy
 */
class PT_Content_Views_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix	 = null;
	// Slugs for sub menu pages
	protected $plugin_sub_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
		  return;
		  } */

		/*
		 * Call $plugin_slug from public plugin class.
		 */
		$plugin				 = PT_Content_Views::get_instance();
		$this->plugin_slug	 = $plugin->get_plugin_slug();

		// Fix redirect error
		add_action( 'init', array( $this, 'do_output_buffer' ) );

		// Redirect to "Add View" page when click "Add new" link in "All Views" page
		add_action( 'admin_init', array( $this, 'redirect_add_new' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'admin_print_footer_scripts', array( $this, 'print_footer_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Ajax action
		$action = 'preview_request';
		add_action( 'wp_ajax_' . $action, array( 'PT_CV_Functions', 'ajax_callback_' . $action ) );

		// Output assets content at footer of page
		add_action( PT_CV_PREFIX_ . 'preview_footer', array( 'PT_CV_Html', 'assets_of_view_types' ) );


		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( dirname( __FILE__ ) ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'filter_add_action_links' ) );

		// Filter link of actions in All Views page
		add_filter( 'post_row_actions', array( $this, 'filter_view_row_actions' ), 10, 2 );

		// Add Shortcode column
		add_filter( 'manage_pt_view_posts_columns', array( $this, 'filter_view_custom_column_header' ) );
		add_action( 'manage_pt_view_posts_custom_column', array( $this, 'action_view_custom_column_content' ), 10, 2 );

		// Filter link of Title in All Views page
		add_filter( 'get_edit_post_link', array( $this, 'filter_get_edit_post_link' ), 10, 3 );

		// Filter Title of Edit View page
		add_filter( 'admin_title', array( $this, 'filter_admin_title' ), 10, 2 );

		// Custom hooks for both preview & frontend
		PT_CV_Hooks::init();

		// Custom settings page
		PT_CV_Plugin::init();
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * @TODO :
		 *
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
		  return;
		  } */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Output buffering
	 */
	public function do_output_buffer() {
		ob_start();
	}

	/**
	 * Redirect to "Add View" page when click "Add new" link in "All Views" page
	 */
	public function redirect_add_new() {
		global $pagenow;
		if ( $pagenow === 'post-new.php' ) {
			$post_type = isset( $_GET[ 'post_type' ] ) ? $_GET[ 'post_type' ] : '';
			if ( $post_type === PT_CV_POST_TYPE ) {
				wp_redirect( admin_url( 'admin.php?page=' . $this->plugin_slug . '-add' ), 301 );
				exit;
			}
		}
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( !isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		// Load every Admin pages
		PT_CV_Asset::enqueue(
		'admin-menu', 'style', array(
			'src' => plugins_url( 'assets/css/menu.css', __FILE__ ),
		)
		);

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id || in_array( $screen->id, $this->plugin_sub_screen_hook_suffix ) ) {

			// WP assets
			wp_enqueue_style( 'thickbox' );
			wp_enqueue_style( 'media-upload' );
			wp_enqueue_style( 'wp-color-picker' );

			// Main admin style
			PT_CV_Asset::enqueue(
			'admin', 'style', array(
				'src' => plugins_url( 'assets/css/admin.css', __FILE__ ),
			)
			);

			// Fix style of WP
			global $wp_version;
			if ( version_compare( $wp_version, '3.8.0' ) >= 0 ) {
				PT_CV_Asset::enqueue(
				'admin-fix', 'style', array(
					'src'	 => plugins_url( 'assets/css/wp38.css', __FILE__ ),
					'ver'	 => $wp_version,
				)
				);
			} else {
				PT_CV_Asset::enqueue(
				'admin-fix', 'style', array(
					'src'	 => plugins_url( 'assets/css/wp.css', __FILE__ ),
					'ver'	 => $wp_version,
				)
				);
			}

			// Bootstrap for Admin
			PT_CV_Asset::enqueue(
			'bootstrap-admin', 'style', array(
				'src' => plugins_url( 'assets/bootstrap/css/bootstrap.admin.css', PT_CV_FILE ),
			)
			);

			// For Preview
			PT_CV_Html::frontend_styles();

			// Main scripts
			PT_CV_Asset::enqueue( 'select2', 'style' );
			PT_CV_Asset::enqueue( 'select2-bootstrap', 'style' );
		}
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( !isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id || in_array( $screen->id, $this->plugin_sub_screen_hook_suffix ) ) {

			// WP assets
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'jquery-ui-sortable' );

			// Main admin script
			PT_CV_Asset::enqueue(
			'admin', 'script', array(
				'src'	 => plugins_url( 'assets/js/admin.js', __FILE__ ),
				'deps'	 => array( 'jquery' ),
			)
			);

			// Localize strings
			PT_CV_Asset::localize_script(
			'admin', PT_CV_PREFIX_UPPER . 'ADMIN', array(
				'supported_version'	 => PT_CV_Functions::wp_version_compare( '3.5' ),
				'text'				 => array(
					'no_taxonomy'		 => __( 'There is no taxonomy for selected content type', PT_CV_DOMAIN ),
					'pagination_disable' => __( 'Pagination is disabled when Limit = -1', PT_CV_DOMAIN ),
					'prevent_click'		 => __( 'Opening a link is prevented in preview box', PT_CV_DOMAIN ),
				),
				'btn'				 => array(
					'preview' => array(
						'show'	 => __( 'Show Preview', PT_CV_DOMAIN ),
						'hide'	 => __( 'Hide Preview', PT_CV_DOMAIN ),
						'update' => __( 'Update Preview', PT_CV_DOMAIN ),
					),
				),
				'data'				 => array(
					'post_types_vs_taxonomies' => PT_CV_Values::post_types_vs_taxonomies(),
				),
			)
			);

			// For Preview
			PT_CV_Html::frontend_scripts( true );

			PT_CV_Asset::enqueue( 'select2' );
		}
	}

	/**
	 * Print script at footer of WP admin
	 */
	public function print_footer_scripts() {
		if ( !isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id || in_array( $screen->id, $this->plugin_sub_screen_hook_suffix ) ) {
			PT_Options_Framework::print_js();
		}
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		/*
		 * Add a settings page for this plugin to the Settings menu.
		 */
		// Get user role settings option
		$user_role = current_user_can( 'administrator' ) ? 'administrator' : PT_CV_Functions::get_option_value( 'access_role', 'edit_posts' );

		$this->plugin_screen_hook_suffix = add_menu_page(
		__( 'Content View Settings', $this->plugin_slug ), __( 'Content View Settings', $this->plugin_slug ), $user_role, $this->plugin_slug, array( $this, 'display_plugin_admin_page' ), '', '45.6'
		);

		$this->plugin_sub_screen_hook_suffix[] = PT_CV_Functions::menu_add_sub(
		$this->plugin_slug, __( 'All Content Views', $this->plugin_slug ), __( 'All Views', $this->plugin_slug ), $user_role, 'list', __CLASS__
		);

		$this->plugin_sub_screen_hook_suffix[] = PT_CV_Functions::menu_add_sub(
		$this->plugin_slug, __( 'Add New View', $this->plugin_slug ), __( 'Add New', $this->plugin_slug ), $user_role, 'add', __CLASS__
		);
	}

	/**
	 * Admin custom column content
	 *
	 * @param type $column_name
	 * @param type $post_id
	 */
	public function action_view_custom_column_content( $column_name, $post_id ) {
		if ( $column_name == 'shortcode' ) {
			// Get View id
			$view_id = get_post_meta( $post_id, PT_CV_META_ID, true );

			printf( '<input style="width: 250px; background: #ADFFAD;" type="text" value="[pt_view id=&quot;%s&quot;]" onclick="this.select()" readonly="">', $view_id );
		}
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public static function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * List all Views page
	 */
	public static function display_sub_page_list() {
		include_once( 'views/list.php' );
	}

	/**
	 * Add/Edit View page
	 */
	public static function display_sub_page_add() {
		include_once( 'views/view.php' );
	}

	/**
	 * Add settings action link to the plugins page, doesn't work in Multiple site
	 *
	 * @since    1.0.0
	 */
	public function filter_add_action_links( $links ) {

		return array_merge(
		array(
			'settings'	 => '<a href="' . admin_url( 'admin.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>',
			'add'		 => '<a href="' . admin_url( 'admin.php?page=' . $this->plugin_slug . '-add' ) . '">' . __( 'Add View', $this->plugin_slug ) . '</a>',
		), $links
		);
	}

	/**
	 * Filter link of actions in All Views page
	 *
	 * @param array  $actions Array of actions link
	 * @param object $post    The post
	 *
	 * @return array
	 */
	public function filter_view_row_actions( $actions, $post ) {

		// Get current post type
		$post_type = PT_CV_Functions::admin_current_post_type();

		if ( $post_type != PT_CV_POST_TYPE ) {
			return $actions;
		}

		// Remove Quick edit link
		unset( $actions[ 'inline hide-if-no-js' ] );

		// Remove View link
		unset( $actions[ 'view' ] );

		// Update Edit link
		// Get View id
		$view_id = get_post_meta( $post->ID, PT_CV_META_ID, true );

		if ( !empty( $view_id ) ) {
			$edit_link			 = PT_CV_Functions::view_link( $view_id );
			$actions[ 'edit' ]	 = '<a href="' . esc_url( $edit_link ) . '" title="' . esc_attr( __( 'Edit this item' ) ) . '">' . __( 'Edit' ) . '</a>';
		}

		// Filter actions
		$actions = apply_filters( PT_CV_PREFIX_ . 'view_row_actions', $actions, $view_id );

		return $actions;
	}

	/**
	 * Modify column in View list page (Admin)
	 *
	 * @param type $defaults
	 */
	public function filter_view_custom_column_header( $defaults ) {
		unset( $defaults[ 'author' ] );
		unset( $defaults[ 'date' ] );

		$defaults[ 'shortcode' ] = __( 'Shortcode' );
		$defaults[ 'author' ]	 = __( 'Author' );
		$defaults[ 'date' ]		 = __( 'Date' );

		return $defaults;
	}

	/**
	 * Filter link of Title in All Views page
	 */
	public function filter_get_edit_post_link( $edit_link, $post_id, $context ) {

		// Get current post type
		$post_type = PT_CV_Functions::admin_current_post_type();

		if ( $post_type != PT_CV_POST_TYPE ) {
			return $edit_link;
		}

		// Get View id
		$view_id = get_post_meta( $post_id, PT_CV_META_ID, true );

		$edit_link = PT_CV_Functions::view_link( $view_id );

		return $edit_link;
	}

	/**
	 * Filter Title for View page
	 *
	 * @param string $admin_title
	 * @param string $title
	 */
	public function filter_admin_title( $admin_title, $title ) {
		$screen = get_current_screen();

		if ( !$this || !isset( $this->plugin_sub_screen_hook_suffix ) ) {
			return $admin_title;
		}

		// If is View page
		if ( $this->plugin_screen_hook_suffix == $screen->id || in_array( $screen->id, $this->plugin_sub_screen_hook_suffix ) ) {
			// If View id is passed in url
			if ( !empty( $_GET[ 'id' ] ) ) {
				$admin_title = str_replace( 'Add New', 'Edit', $admin_title );
			}
		}

		return $admin_title;
	}

}
