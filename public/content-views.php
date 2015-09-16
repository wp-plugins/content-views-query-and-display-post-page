<?php

/**
 * Content Views for Public
 *
 * @package   PT_Content_Views
 * @author    PT Guy <palaceofthemes@gmail.com>
 * @license   GPL-2.0+
 * @link      http://www.contentviewspro.com/
 * @copyright 2014 PT Guy
 */
class PT_Content_Views {

	/**
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = PT_CV_DOMAIN;

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		// Init session
		add_action( 'init', array( $this, 'register_session' ), 1 );

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ), 11 );

		// Register content
		add_action( 'init', array( $this, 'content_register' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 0 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Update view count of post
		add_action( 'wp_head', array( &$this, 'head_actions' ) );

		// Output assets content at footer of page
		add_action( 'wp_footer', array( 'PT_CV_Html', 'assets_of_view_types' ), 100 );

		// Ajax action
		$action = 'pagination_request';
		add_action( 'wp_ajax_' . $action, array( 'PT_CV_Functions', 'ajax_callback_' . $action ) );
		add_action( 'wp_ajax_nopriv_' . $action, array( 'PT_CV_Functions', 'ajax_callback_' . $action ) );

		// Custom hooks for both preview & frontend
		PT_CV_Hooks::init();
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean $network_wide       True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();
			} else {
				self::single_activate();
			}
		} else {
			self::single_activate();
		}
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean $network_wide       True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();
				}

				restore_current_blog();
			} else {
				self::single_deactivate();
			}
		} else {
			self::single_deactivate();
		}
	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int $blog_id ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();
	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	public static function get_blog_ids() {

		global $wpdb;

		// Get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );
	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	public static function single_activate() {
		update_option( PT_CV_OPTION_VERSION, PT_CV_VERSION );
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	public static function single_deactivate() {
		delete_option( PT_CV_OPTION_VERSION );
	}

	/**
	 * Start SESSION
	 */
	public function register_session() {
		if ( !session_id() )
			session_start();
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		$domain	 = PT_CV_DOMAIN;
		// WPLANG is no longer needed since 4.0
		$locale	 = get_locale();

		// Load mo file from wp-content/languages/content-views/
		load_textdomain( $domain, WP_LANG_DIR . "/{$domain}/{$domain}-{$locale}.mo" );
		// Load mo file from sub-folder /languages of this plugin
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( PT_CV_FILE ) ) . '/languages/' );
	}

	/**
	 * Content register: Create custom post type
	 */
	public function content_register() {

		/**
		 * Register custom post type : View
		 */
		$labels = array(
			'name'				 => _x( 'Views', 'post type general name', PT_CV_DOMAIN ),
			'singular_name'		 => _x( 'View', 'post type singular name', PT_CV_DOMAIN ),
			'menu_name'			 => _x( 'Views', 'admin menu', PT_CV_DOMAIN ),
			'name_admin_bar'	 => _x( 'View', 'add new on admin bar', PT_CV_DOMAIN ),
			'add_new'			 => _x( 'Add New', PT_CV_POST_TYPE, PT_CV_DOMAIN ),
			'add_new_item'		 => __( 'Add New View', PT_CV_DOMAIN ),
			'new_item'			 => __( 'New View', PT_CV_DOMAIN ),
			'edit_item'			 => __( 'Edit View', PT_CV_DOMAIN ),
			'view_item'			 => __( 'View View', PT_CV_DOMAIN ),
			'all_items'			 => __( 'All Views', PT_CV_DOMAIN ),
			'search_items'		 => __( 'Search Views', PT_CV_DOMAIN ),
			'parent_item_colon'	 => __( 'Parent Views:', PT_CV_DOMAIN ),
			'not_found'			 => __( 'No views found.', PT_CV_DOMAIN ),
			'not_found_in_trash' => __( 'No views found in Trash.', PT_CV_DOMAIN ),
		);

		$args = array(
			'labels'			 => $labels,
			'public'			 => false,
			// Hide in menu, but can see All Views page
			'show_ui'			 => true, // set "true" to fix "Invalid post type" error
			'show_in_menu'		 => false,
			'query_var'			 => true,
			'rewrite'			 => array( 'slug' => PT_CV_POST_TYPE ),
			'capability_type'	 => 'post',
			'has_archive'		 => true,
			'hierarchical'		 => false,
			'menu_position'		 => null,
			'supports'			 => array( 'title', 'author', 'custom-fields' ),
		);

		register_post_type( PT_CV_POST_TYPE, $args );

		/**
		 * Add shortcode
		 */
		add_shortcode( PT_CV_POST_TYPE, array( 'PT_CV_Functions', 'view_output' ) );
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		PT_CV_Html::frontend_styles();
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		PT_CV_Html::frontend_scripts();
	}

	/**
	 * Update view count
	 *
	 * @global type $post
	 * @return void
	 */
	public static function _update_view_count() {
		global $post;
		if ( !isset( $post ) || !is_object( $post ) ) {
			return;
		}
		if ( is_single( $post->ID ) ) {
			PT_CV_Functions::post_update_view_count( $post->ID );
		}
	}

	/**
	 * Custom actions at head
	 */
	public function head_actions() {
		// Update View count
		self::_update_view_count();

		// Initialize global variables
		global $pt_cv_glb, $pt_cv_id;
		$pt_cv_glb	 = array();
		$pt_cv_id	 = 0;
	}

}
