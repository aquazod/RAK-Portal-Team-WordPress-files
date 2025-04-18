<?php
/**
 * Theme functions and definitions.
 *
 * Sets up the theme and provides some helper functions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 *
 * For more information on hooks, actions, and filters,
 * see http://codex.wordpress.org/Plugin_API
 *
 * @package OceanWP WordPress theme
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Core Constants.
define( 'OCEANWP_THEME_DIR', get_template_directory() );
define( 'OCEANWP_THEME_URI', get_template_directory_uri() );

/**
 * OceanWP theme class
 */

final class OCEANWP_Theme_Class {

	/**
	 * Main Theme Class Constructor
	 *
	 * @since   1.0.0
	 */
	public function __construct() {
		// Migrate
		$this->migration();

		// Define theme constants.
		$this->oceanwp_constants();

		// Load required files.
		$this->oceanwp_has_setup();

		// Load framework classes.
		add_action( 'after_setup_theme', array( 'OCEANWP_Theme_Class', 'classes' ), 4 );

		// Setup theme => add_theme_support, register_nav_menus, load_theme_textdomain, etc.
		add_action( 'after_setup_theme', array( 'OCEANWP_Theme_Class', 'theme_setup' ), 10 );

		// register sidebar widget areas.
		add_action( 'widgets_init', array( 'OCEANWP_Theme_Class', 'register_sidebars' ) );

		// Registers theme_mod strings into Polylang.
		if ( class_exists( 'Polylang' ) ) {
			add_action( 'after_setup_theme', array( 'OCEANWP_Theme_Class', 'polylang_register_string' ) );
		}

		/** Admin only actions */
		if ( is_admin() ) {

			// Load scripts in the WP admin.
			add_action( 'admin_enqueue_scripts', array( 'OCEANWP_Theme_Class', 'admin_scripts' ) );

			// Outputs custom CSS for the admin.
			add_action( 'admin_head', array( 'OCEANWP_Theme_Class', 'admin_inline_css' ) );

			/** Non Admin actions */
		} else {
			// Load theme js.
			add_action( 'wp_enqueue_scripts', array( 'OCEANWP_Theme_Class', 'theme_js' ) );

			// Load theme CSS.
			add_action( 'wp_enqueue_scripts', array( 'OCEANWP_Theme_Class', 'theme_css' ) );

			// Load his file in last.
			add_action( 'wp_enqueue_scripts', array( 'OCEANWP_Theme_Class', 'custom_style_css' ), 9999 );

			// Remove Customizer CSS script from Front-end.
			add_action( 'init', array( 'OCEANWP_Theme_Class', 'remove_customizer_custom_css' ) );

			// Add a pingback url auto-discovery header for singularly identifiable articles.
			add_action( 'wp_head', array( 'OCEANWP_Theme_Class', 'pingback_header' ), 1 );

			// Add meta viewport tag to header.
			add_action( 'wp_head', array( 'OCEANWP_Theme_Class', 'meta_viewport' ), 1 );

			// Add an X-UA-Compatible header.
			add_filter( 'wp_headers', array( 'OCEANWP_Theme_Class', 'x_ua_compatible_headers' ) );

			// Outputs custom CSS to the head.
			add_action( 'wp_head', array( 'OCEANWP_Theme_Class', 'custom_css' ), 9999 );

			// Minify the WP custom CSS because WordPress doesn't do it by default.
			add_filter( 'wp_get_custom_css', array( 'OCEANWP_Theme_Class', 'minify_custom_css' ) );

			// Alter the search posts per page.
			add_action( 'pre_get_posts', array( 'OCEANWP_Theme_Class', 'search_posts_per_page' ) );

			// Alter WP categories widget to display count inside a span.
			add_filter( 'wp_list_categories', array( 'OCEANWP_Theme_Class', 'wp_list_categories_args' ) );

			// Add a responsive wrapper to the WordPress oembed output.
			add_filter( 'embed_oembed_html', array( 'OCEANWP_Theme_Class', 'add_responsive_wrap_to_oembeds' ), 99, 4 );

			// Adds classes the post class.
			add_filter( 'post_class', array( 'OCEANWP_Theme_Class', 'post_class' ) );

			// Add schema markup to the authors post link.
			add_filter( 'the_author_posts_link', array( 'OCEANWP_Theme_Class', 'the_author_posts_link' ) );

			// Add support for Elementor Pro locations.
			add_action( 'elementor/theme/register_locations', array( 'OCEANWP_Theme_Class', 'register_elementor_locations' ) );

			// Remove the default lightbox script for the beaver builder plugin.
			add_filter( 'fl_builder_override_lightbox', array( 'OCEANWP_Theme_Class', 'remove_bb_lightbox' ) );

			add_filter( 'ocean_enqueue_generated_files', '__return_false' );
		}
	}

	/**
	 * Migration Functinality
	 *
	 * @since   1.0.0
	 */
	public static function migration() {
		if ( get_theme_mod( 'ocean_disable_emoji', false ) ) {
			set_theme_mod( 'ocean_performance_emoji', 'disabled' );
		}

		if ( get_theme_mod( 'ocean_disable_lightbox', false ) ) {
			set_theme_mod( 'ocean_performance_lightbox', 'disabled' );
		}
	}

	/**
	 * Define Constants
	 *
	 * @since   1.0.0
	 */
	public static function oceanwp_constants() {

		$version = self::theme_version();

		// Theme version.
		define( 'OCEANWP_THEME_VERSION', $version );

		// Javascript and CSS Paths.
		define( 'OCEANWP_JS_DIR_URI', OCEANWP_THEME_URI . '/assets/js/' );
		define( 'OCEANWP_CSS_DIR_URI', OCEANWP_THEME_URI . '/assets/css/' );

		// Include Paths.
		define( 'OCEANWP_INC_DIR', OCEANWP_THEME_DIR . '/inc/' );
		define( 'OCEANWP_INC_DIR_URI', OCEANWP_THEME_URI . '/inc/' );

		// Check if plugins are active.
		define( 'OCEAN_EXTRA_ACTIVE', class_exists( 'Ocean_Extra' ) );
		define( 'OCEANWP_ELEMENTOR_ACTIVE', class_exists( 'Elementor\Plugin' ) );
		define( 'OCEANWP_BEAVER_BUILDER_ACTIVE', class_exists( 'FLBuilder' ) );
		define( 'OCEANWP_WOOCOMMERCE_ACTIVE', class_exists( 'WooCommerce' ) );
		define( 'OCEANWP_EDD_ACTIVE', class_exists( 'Easy_Digital_Downloads' ) );
		define( 'OCEANWP_LIFTERLMS_ACTIVE', class_exists( 'LifterLMS' ) );
		define( 'OCEANWP_ALNP_ACTIVE', class_exists( 'Auto_Load_Next_Post' ) );
		define( 'OCEANWP_LEARNDASH_ACTIVE', class_exists( 'SFWD_LMS' ) );
	}

	/**
	 * Load all core theme function files
	 *
	 * @since 1.0.0oceanwp_has_setup
	 */
	public static function oceanwp_has_setup() {

		$dir = OCEANWP_INC_DIR;

		require_once $dir . 'helpers.php';
		require_once $dir . 'header-content.php';
		require_once $dir . 'oceanwp-strings.php';
		require_once $dir . 'oceanwp-svg.php';
		require_once $dir . 'oceanwp-theme-icons.php';
		require_once $dir . 'template-helpers.php';
		require_once $dir . 'customizer/controls/typography/webfonts.php';
		require_once $dir . 'walker/init.php';
		require_once $dir . 'walker/menu-walker.php';
		require_once $dir . 'third/class-gutenberg.php';
		require_once $dir . 'third/class-elementor.php';
		require_once $dir . 'third/class-beaver-themer.php';
		require_once $dir . 'third/class-bbpress.php';
		require_once $dir . 'third/class-buddypress.php';
		require_once $dir . 'third/class-lifterlms.php';
		require_once $dir . 'third/class-learndash.php';
		require_once $dir . 'third/class-sensei.php';
		require_once $dir . 'third/class-social-login.php';
		require_once $dir . 'third/class-amp.php';
		require_once $dir . 'third/class-pwa.php';

		// WooCommerce.
		if ( OCEANWP_WOOCOMMERCE_ACTIVE ) {
			require_once $dir . 'woocommerce/woocommerce-config.php';
		}

		// Easy Digital Downloads.
		if ( OCEANWP_EDD_ACTIVE ) {
			require_once $dir . 'edd/edd-config.php';
		}

	}

	/**
	 * Returns current theme version
	 *
	 * @since   1.0.0
	 */
	public static function theme_version() {

		// Get theme data.
		$theme = wp_get_theme();

		// Return theme version.
		return $theme->get( 'Version' );

	}

	/**
	 * Compare WordPress version
	 *
	 * @access public
	 * @since 1.8.3
	 * @param  string $version - A WordPress version to compare against current version.
	 * @return boolean
	 */
	public static function is_wp_version( $version = '5.4' ) {

		global $wp_version;

		// WordPress version.
		return version_compare( strtolower( $wp_version ), strtolower( $version ), '>=' );

	}


	/**
	 * Check for AMP endpoint
	 *
	 * @return bool
	 * @since 1.8.7
	 */
	public static function oceanwp_is_amp() {
		return function_exists( 'is_amp_endpoint' ) && is_amp_endpoint();
	}

	/**
	 * Load theme classes
	 *
	 * @since   1.0.0
	 */
	public static function classes() {

		// Admin only classes.
		if ( is_admin() ) {

			// Recommend plugins.
			require_once OCEANWP_INC_DIR . 'activation-notice/class-oceanwp-plugin-manager.php';
			require_once OCEANWP_INC_DIR . 'activation-notice/template.php';

			// Ajax Actions
			if (defined('DOING_AJAX') && DOING_AJAX) {
				require OCEANWP_INC_DIR . 'activation-notice/api.php';
			}

			// Front-end classes.
		}

		// Breadcrumbs class.
		require_once OCEANWP_INC_DIR . 'breadcrumbs.php';

		// Customizer class.
		require_once OCEANWP_INC_DIR . 'customizer/library/customizer-custom-controls/functions.php';
		require_once OCEANWP_INC_DIR . 'customizer/customizer.php';

	}

	/**
	 * Theme Setup
	 *
	 * @since   1.0.0
	 */
	public static function theme_setup() {

		// Load text domain.
		load_theme_textdomain( 'oceanwp', OCEANWP_THEME_DIR . '/languages' );

		// Get globals.
		global $content_width;

		// Set content width based on theme's default design.
		if ( ! isset( $content_width ) ) {
			$content_width = 1200;
		}

		// Register navigation menus.
		register_nav_menus(
			array(
				'topbar_menu' => esc_html__( 'Top Bar', 'oceanwp' ),
				'main_menu'   => esc_html__( 'Main', 'oceanwp' ),
				'footer_menu' => esc_html__( 'Footer', 'oceanwp' ),
				'mobile_menu' => esc_html__( 'Mobile (optional)', 'oceanwp' ),
			)
		);

		// Enable support for Post Formats.
		add_theme_support( 'post-formats', array( 'video', 'gallery', 'audio', 'quote', 'link' ) );

		// Enable support for <title> tag.
		add_theme_support( 'title-tag' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );

		/**
		 * Enable support for header image
		 */
		add_theme_support(
			'custom-header',
			apply_filters(
				'ocean_custom_header_args',
				array(
					'width'       => 2000,
					'height'      => 1200,
					'flex-height' => true,
					'video'       => true,
					'video-active-callback' => '__return_true'
				)
			)
		);

		/**
		 * Enable support for site logo
		 */
		add_theme_support(
			'custom-logo',
			apply_filters(
				'ocean_custom_logo_args',
				array(
					'height'      => 45,
					'width'       => 164,
					'flex-height' => true,
					'flex-width'  => true,
				)
			)
		);

		/*
		 * Switch default core markup for search form, comment form, comments, galleries, captions and widgets
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
				'widgets',
			)
		);

		// Declare WooCommerce support.
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		// Add editor style.
		add_editor_style( 'assets/css/editor-style.min.css' );

		// Declare support for selective refreshing of widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

	}

	/**
	 * Adds the meta tag to the site header
	 *
	 * @since 1.1.0
	 */
	public static function pingback_header() {

		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
		}

	}

	/**
	 * Adds the meta tag to the site header
	 *
	 * @since 1.0.0
	 */
	public static function meta_viewport() {

		// Meta viewport.
		$viewport = '<meta name="viewport" content="width=device-width, initial-scale=1">';

		// Apply filters for child theme tweaking.
		echo apply_filters( 'ocean_meta_viewport', $viewport ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}

	/**
	 * Load scripts in the WP admin
	 *
	 * @since 1.0.0
	 */
	public static function admin_scripts() {
		global $pagenow;
		if ( 'nav-menus.php' === $pagenow ) {
			wp_enqueue_style( 'oceanwp-menus', OCEANWP_INC_DIR_URI . 'walker/assets/menus.css', false, OCEANWP_THEME_VERSION );
		}
	}

	/**
	 * Load front-end scripts
	 *
	 * @since   1.0.0
	 */
	public static function theme_css() {

		// Define dir.
		$dir           = OCEANWP_CSS_DIR_URI;
		$theme_version = OCEANWP_THEME_VERSION;

		// Remove font awesome style from plugins.
		wp_deregister_style( 'font-awesome' );
		wp_deregister_style( 'fontawesome' );

		// Enqueue font awesome style.
		if ( get_theme_mod( 'ocean_performance_fontawesome', 'enabled' ) === 'enabled' ) {
			wp_enqueue_style( 'font-awesome', OCEANWP_THEME_URI . '/assets/fonts/fontawesome/css/all.min.css', false, '6.4.2' );
		}

		// Enqueue simple line icons style.
		if ( get_theme_mod( 'ocean_performance_simple_line_icons', 'enabled' ) === 'enabled' ) {
			wp_enqueue_style( 'simple-line-icons', $dir . 'third/simple-line-icons.min.css', false, '2.4.0' );
		}

		// Enqueue Main style.
		wp_enqueue_style( 'oceanwp-style', $dir . 'style.min.css', false, $theme_version );

		// Blog Header styles.
		if ( 'default' !== get_theme_mod( 'oceanwp_single_post_header_style', 'default' )
			&& is_single() && 'post' === get_post_type() ) {
			wp_enqueue_style( 'oceanwp-blog-headers', $dir . 'blog/blog-post-headers.css', false, $theme_version );
		}

		// Register perfect-scrollbar plugin style.
		wp_register_style( 'ow-perfect-scrollbar', $dir . 'third/perfect-scrollbar.css', false, '1.5.0' );

		// Register hamburgers buttons to easily use them.
		wp_register_style( 'oceanwp-hamburgers', $dir . 'third/hamburgers/hamburgers.min.css', false, $theme_version );
		// Register hamburgers buttons styles.
		$hamburgers = oceanwp_hamburgers_styles();
		foreach ( $hamburgers as $class => $name ) {
			wp_register_style( 'oceanwp-' . $class . '', $dir . 'third/hamburgers/types/' . $class . '.css', false, $theme_version );
		}

		// Get mobile menu icon style.
		$mobile_menu = get_theme_mod( 'ocean_mobile_menu_open_hamburger', 'default' );
		// Enqueue mobile menu icon style.
		if ( ! empty( $mobile_menu ) && 'default' !== $mobile_menu ) {
			wp_enqueue_style( 'oceanwp-hamburgers' );
			wp_enqueue_style( 'oceanwp-' . $mobile_menu . '' );
		}

		// If Vertical header style.
		if ( 'vertical' === oceanwp_header_style() ) {
			wp_enqueue_style( 'oceanwp-hamburgers' );
			wp_enqueue_style( 'oceanwp-spin' );
			wp_enqueue_style( 'ow-perfect-scrollbar' );
		}
	}

	/**
	 * Returns all js needed for the front-end
	 *
	 * @since 1.0.0
	 */
	public static function theme_js() {

		if ( self::oceanwp_is_amp() ) {
			return;
		}

		// Get js directory uri.
		$dir = OCEANWP_JS_DIR_URI;

		// Get current theme version.
		$theme_version = OCEANWP_THEME_VERSION;

		// Get localized array.
		$localize_array = self::localize_array();

		// Main script dependencies.
		$main_script_dependencies = array( 'jquery' );

		// Comment reply.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Add images loaded.
		wp_enqueue_script( 'imagesloaded' );

		/**
		 * Load Venors Scripts.
		 */

		// Isotop.
		wp_register_script( 'ow-isotop', $dir . 'vendors/isotope.pkgd.min.js', array(), '3.0.6', true );

		// Flickity.
		wp_register_script( 'ow-flickity', $dir . 'vendors/flickity.pkgd.min.js', array(), $theme_version, true );

		// Magnific Popup.
		wp_register_script( 'ow-magnific-popup', $dir . 'vendors/magnific-popup.min.js', array( 'jquery' ), $theme_version, true );

		// Sidr Mobile Menu.
		wp_register_script( 'ow-sidr', $dir . 'vendors/sidr.js', array(), $theme_version, true );

		// Perfect Scrollbar.
		wp_register_script( 'ow-perfect-scrollbar', $dir . 'vendors/perfect-scrollbar.min.js', array(), $theme_version, true );

		// Smooth Scroll.
		wp_register_script( 'ow-smoothscroll', $dir . 'vendors/smoothscroll.min.js', array(), $theme_version, false );

		/**
		 * Load Theme Scripts.
		 */

		// Theme script.
		wp_enqueue_script( 'oceanwp-main', $dir . 'theme.min.js', $main_script_dependencies, $theme_version, true );
		wp_localize_script( 'oceanwp-main', 'oceanwpLocalize', $localize_array );
		array_push( $main_script_dependencies, 'oceanwp-main' );

		// Blog Masonry script.
		if ( 'masonry' === oceanwp_blog_grid_style() ) {
			array_push( $main_script_dependencies, 'ow-isotop' );
			wp_enqueue_script( 'ow-isotop' );
			wp_enqueue_script( 'oceanwp-blog-masonry', $dir . 'blog-masonry.min.js', $main_script_dependencies, $theme_version, true );
		}

		// Menu script.
		switch ( oceanwp_header_style() ) {
			case 'full_screen':
				wp_enqueue_script( 'oceanwp-full-screen-menu', $dir . 'full-screen-menu.min.js', $main_script_dependencies, $theme_version, true );
				break;
			case 'vertical':
				array_push( $main_script_dependencies, 'ow-perfect-scrollbar' );
				wp_enqueue_script( 'ow-perfect-scrollbar' );
				wp_enqueue_script( 'oceanwp-vertical-header', $dir . 'vertical-header.min.js', $main_script_dependencies, $theme_version, true );
				break;
		}

		// Mobile Menu script.
		switch ( oceanwp_mobile_menu_style() ) {
			case 'dropdown':
				wp_enqueue_script( 'oceanwp-drop-down-mobile-menu', $dir . 'drop-down-mobile-menu.min.js', $main_script_dependencies, $theme_version, true );
				break;
			case 'fullscreen':
				wp_enqueue_script( 'oceanwp-full-screen-mobile-menu', $dir . 'full-screen-mobile-menu.min.js', $main_script_dependencies, $theme_version, true );
				break;
			case 'sidebar':
				array_push( $main_script_dependencies, 'ow-sidr' );
				wp_enqueue_script( 'ow-sidr' );
				wp_enqueue_script( 'oceanwp-sidebar-mobile-menu', $dir . 'sidebar-mobile-menu.min.js', $main_script_dependencies, $theme_version, true );
				break;
		}

		// Search script.
		switch ( oceanwp_menu_search_style() ) {
			case 'drop_down':
				wp_enqueue_script( 'oceanwp-drop-down-search', $dir . 'drop-down-search.min.js', $main_script_dependencies, $theme_version, true );
				break;
			case 'header_replace':
				wp_enqueue_script( 'oceanwp-header-replace-search', $dir . 'header-replace-search.min.js', $main_script_dependencies, $theme_version, true );
				break;
			case 'overlay':
				wp_enqueue_script( 'oceanwp-overlay-search', $dir . 'overlay-search.min.js', $main_script_dependencies, $theme_version, true );
				break;
		}

		// Mobile Search Icon Style.
		if ( oceanwp_mobile_menu_search_style() !== 'disabled' ) {
			wp_enqueue_script( 'oceanwp-mobile-search-icon', $dir . 'mobile-search-icon.min.js', $main_script_dependencies, $theme_version, true );
		}

		// Equal Height Elements script.
		if ( oceanwp_blog_entry_equal_heights() ) {
			wp_enqueue_script( 'oceanwp-equal-height-elements', $dir . 'equal-height-elements.min.js', $main_script_dependencies, $theme_version, true );
		}

		$perf_lightbox = get_theme_mod( 'ocean_performance_lightbox', 'enabled' );

		// Lightbox script.
		if ( oceanwp_gallery_is_lightbox_enabled() || $perf_lightbox === 'enabled' ) {
			array_push( $main_script_dependencies, 'ow-magnific-popup' );
			wp_enqueue_script( 'ow-magnific-popup' );
			wp_enqueue_script( 'oceanwp-lightbox', $dir . 'ow-lightbox.min.js', $main_script_dependencies, $theme_version, true );
		}

		// Slider script.
		array_push( $main_script_dependencies, 'ow-flickity' );
		wp_enqueue_script( 'ow-flickity' );
		wp_enqueue_script( 'oceanwp-slider', $dir . 'ow-slider.min.js', $main_script_dependencies, $theme_version, true );

		// Scroll Effect script.
		if ( get_theme_mod( 'ocean_performance_scroll_effect', 'enabled' ) === 'enabled' ) {
			wp_enqueue_script( 'oceanwp-scroll-effect', $dir . 'scroll-effect.min.js', $main_script_dependencies, $theme_version, true );
		}

		// Scroll to Top script.
		if ( oceanwp_display_scroll_up_button() ) {
			wp_enqueue_script( 'oceanwp-scroll-top', $dir . 'scroll-top.min.js', $main_script_dependencies, $theme_version, true );
		}

		// Custom Select script.
		if ( get_theme_mod( 'ocean_performance_custom_select', 'enabled' ) === 'enabled' ) {
			wp_enqueue_script( 'oceanwp-select', $dir . 'select.min.js', $main_script_dependencies, $theme_version, true );
		}

		// Infinite Scroll script.
		if ( 'infinite_scroll' === get_theme_mod( 'ocean_blog_pagination_style', 'standard' ) || 'infinite_scroll' === get_theme_mod( 'ocean_woo_pagination_style', 'standard' ) ) {
			wp_enqueue_script( 'oceanwp-infinite-scroll', $dir . 'ow-infinite-scroll.min.js', $main_script_dependencies, $theme_version, true );
		}

		// WooCommerce scripts.
		if ( OCEANWP_WOOCOMMERCE_ACTIVE
		&& 'yes' !== get_theme_mod( 'ocean_woo_remove_custom_features', 'no' ) ) {
			wp_enqueue_script( 'oceanwp-woocommerce-custom-features', $dir . 'wp-plugins/woocommerce/woo-custom-features.min.js', array( 'jquery' ), $theme_version, true );
			wp_localize_script( 'oceanwp-woocommerce-custom-features', 'oceanwpLocalize', $localize_array );
		}

		// Register scripts for old addons.
		wp_register_script( 'nicescroll', $dir . 'vendors/support-old-oceanwp-addons/jquery.nicescroll.min.js', array( 'jquery' ), $theme_version, true );
	}

	/**
	 * Functions.js localize array
	 *
	 * @since 1.0.0
	 */
	public static function localize_array() {

		// Create array.
		$sidr_side     = get_theme_mod( 'ocean_mobile_menu_sidr_direction', 'left' );
		$sidr_side     = $sidr_side ? $sidr_side : 'left';
		$sidr_target   = get_theme_mod( 'ocean_mobile_menu_sidr_dropdown_target', 'link' );
		$sidr_target   = $sidr_target ? $sidr_target : 'link';
		$vh_target     = get_theme_mod( 'ocean_vertical_header_dropdown_target', 'link' );
		$vh_target     = $vh_target ? $vh_target : 'link';
		$scroll_offset = get_theme_mod( 'ocean_scroll_effect_offset_value' );
		$scroll_offset = $scroll_offset ? $scroll_offset : 0;
		$array       = array(
			'nonce'                 => wp_create_nonce( 'oceanwp' ),
			'isRTL'                 => is_rtl(),
			'menuSearchStyle'       => oceanwp_menu_search_style(),
			'mobileMenuSearchStyle' => oceanwp_mobile_menu_search_style(),
			'sidrSource'            => oceanwp_sidr_menu_source(),
			'sidrDisplace'          => get_theme_mod( 'ocean_mobile_menu_sidr_displace', true ) ? true : false,
			'sidrSide'              => $sidr_side,
			'sidrDropdownTarget'    => $sidr_target,
			'verticalHeaderTarget'  => $vh_target,
			'customScrollOffset'    => $scroll_offset,
			'customSelects'         => '.woocommerce-ordering .orderby, #dropdown_product_cat, .widget_categories select, .widget_archive select, .single-product .variations_form .variations select',
		);

		// WooCart.
		if ( OCEANWP_WOOCOMMERCE_ACTIVE ) {
			$array['wooCartStyle'] = oceanwp_menu_cart_style();
		}

		// Apply filters and return array.
		return apply_filters( 'ocean_localize_array', $array );
	}

	/**
	 * Add headers for IE to override IE's Compatibility View Settings
	 *
	 * @param obj $headers   header settings.
	 * @since 1.0.0
	 */
	public static function x_ua_compatible_headers( $headers ) {
		$headers['X-UA-Compatible'] = 'IE=edge';
		return $headers;
	}

	/**
	 * Registers sidebars
	 *
	 * @since   1.0.0
	 */
	public static function register_sidebars() {

		$heading = get_theme_mod( 'ocean_sidebar_widget_heading_tag', 'h4' );
		$heading = apply_filters( 'ocean_sidebar_widget_heading_tag', $heading );

		$foo_heading = get_theme_mod( 'ocean_footer_widget_heading_tag', 'h4' );
		$foo_heading = apply_filters( 'ocean_footer_widget_heading_tag', $foo_heading );

		// Default Sidebar.
		register_sidebar(
			array(
				'name'          => esc_html__( 'Default Sidebar', 'oceanwp' ),
				'id'            => 'sidebar',
				'description'   => esc_html__( 'Widgets in this area will be displayed in the left or right sidebar area if you choose the Left or Right Sidebar layout.', 'oceanwp' ),
				'before_widget' => '<div id="%1$s" class="sidebar-box %2$s clr">',
				'after_widget'  => '</div>',
				'before_title'  => '<' . $heading . ' class="widget-title">',
				'after_title'   => '</' . $heading . '>',
			)
		);

		// Left Sidebar.
		register_sidebar(
			array(
				'name'          => esc_html__( 'Left Sidebar', 'oceanwp' ),
				'id'            => 'sidebar-2',
				'description'   => esc_html__( 'Widgets in this area are used in the left sidebar region if you use the Both Sidebars layout.', 'oceanwp' ),
				'before_widget' => '<div id="%1$s" class="sidebar-box %2$s clr">',
				'after_widget'  => '</div>',
				'before_title'  => '<' . $heading . ' class="widget-title">',
				'after_title'   => '</' . $heading . '>',
			)
		);

		// Search Results Sidebar.
		if ( get_theme_mod( 'ocean_search_custom_sidebar', true ) ) {
			register_sidebar(
				array(
					'name'          => esc_html__( 'Search Results Sidebar', 'oceanwp' ),
					'id'            => 'search_sidebar',
					'description'   => esc_html__( 'Widgets in this area are used in the search result page.', 'oceanwp' ),
					'before_widget' => '<div id="%1$s" class="sidebar-box %2$s clr">',
					'after_widget'  => '</div>',
					'before_title'  => '<' . $heading . ' class="widget-title">',
					'after_title'   => '</' . $heading . '>',
				)
			);
		}

		// Footer 1.
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer 1', 'oceanwp' ),
				'id'            => 'footer-one',
				'description'   => esc_html__( 'Widgets in this area are used in the first footer region.', 'oceanwp' ),
				'before_widget' => '<div id="%1$s" class="footer-widget %2$s clr">',
				'after_widget'  => '</div>',
				'before_title'  => '<' . $foo_heading . ' class="widget-title">',
				'after_title'   => '</' . $foo_heading . '>',
			)
		);

		// Footer 2.
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer 2', 'oceanwp' ),
				'id'            => 'footer-two',
				'description'   => esc_html__( 'Widgets in this area are used in the second footer region.', 'oceanwp' ),
				'before_widget' => '<div id="%1$s" class="footer-widget %2$s clr">',
				'after_widget'  => '</div>',
				'before_title'  => '<' . $foo_heading . ' class="widget-title">',
				'after_title'   => '</' . $foo_heading . '>',
			)
		);

		// Footer 3.
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer 3', 'oceanwp' ),
				'id'            => 'footer-three',
				'description'   => esc_html__( 'Widgets in this area are used in the third footer region.', 'oceanwp' ),
				'before_widget' => '<div id="%1$s" class="footer-widget %2$s clr">',
				'after_widget'  => '</div>',
				'before_title'  => '<' . $foo_heading . ' class="widget-title">',
				'after_title'   => '</' . $foo_heading . '>',
			)
		);

		// Footer 4.
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer 4', 'oceanwp' ),
				'id'            => 'footer-four',
				'description'   => esc_html__( 'Widgets in this area are used in the fourth footer region.', 'oceanwp' ),
				'before_widget' => '<div id="%1$s" class="footer-widget %2$s clr">',
				'after_widget'  => '</div>',
				'before_title'  => '<' . $foo_heading . ' class="widget-title">',
				'after_title'   => '</' . $foo_heading . '>',
			)
		);

	}

	/**
	 * Registers theme_mod strings into Polylang.
	 *
	 * @since 1.1.4
	 */
	public static function polylang_register_string() {

		if ( function_exists( 'pll_register_string' ) && $strings = oceanwp_register_tm_strings() ) {
			foreach ( $strings as $string => $default ) {
				pll_register_string( $string, get_theme_mod( $string, $default ), 'Theme Mod', true );
			}
		}

	}

	/**
	 * All theme functions hook into the oceanwp_head_css filter for this function.
	 *
	 * @param obj $output output value.
	 * @since 1.0.0
	 */
	public static function custom_css( $output = null ) {

		// Add filter for adding custom css via other functions.
		$output = apply_filters( 'ocean_head_css', $output );

		// If Custom File is selected.
		if ( 'file' === get_theme_mod( 'ocean_customzer_styling', 'head' ) ) {

			global $wp_customize;
			$upload_dir = wp_upload_dir();

			// Render CSS in the head.
			if ( isset( $wp_customize ) || ! file_exists( $upload_dir['basedir'] . '/oceanwp/custom-style.css' ) ) {

				// Minify and output CSS in the wp_head.
				if ( ! empty( $output ) ) {
					echo "<!-- OceanWP CSS -->\n<style type=\"text/css\">\n" . wp_strip_all_tags( oceanwp_minify_css( $output ) ) . "\n</style>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			}
		} else {

			// Minify and output CSS in the wp_head.
			if ( ! empty( $output ) ) {
				echo "<!-- OceanWP CSS -->\n<style type=\"text/css\">\n" . wp_strip_all_tags( oceanwp_minify_css( $output ) ) . "\n</style>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

	}

	/**
	 * Minify the WP custom CSS because WordPress doesn't do it by default.
	 *
	 * @param obj $css minify css.
	 * @since 1.1.9
	 */
	public static function minify_custom_css( $css ) {

		return oceanwp_minify_css( $css );

	}

	/**
	 * Include Custom CSS file if present.
	 *
	 * @param obj $output output value.
	 * @since 1.4.12
	 */
	public static function custom_style_css( $output = null ) {

		// If Custom File is not selected.
		if ( 'file' !== get_theme_mod( 'ocean_customzer_styling', 'head' ) ) {
			return;
		}

		global $wp_customize;
		$upload_dir = wp_upload_dir();

		// Get all the customier css.
		$output = apply_filters( 'ocean_head_css', $output );

		// Get Custom Panel CSS.
		$output_custom_css = wp_get_custom_css();

		// Minified the Custom CSS.
		$output .= oceanwp_minify_css( $output_custom_css );

		// Render CSS from the custom file.
		if ( ! isset( $wp_customize ) && file_exists( $upload_dir['basedir'] . '/oceanwp/custom-style.css' ) && ! empty( $output ) ) {
			wp_enqueue_style( 'oceanwp-custom', trailingslashit( $upload_dir['baseurl'] ) . 'oceanwp/custom-style.css', false, false );
		}
	}

	/**
	 * Remove Customizer style script from front-end
	 *
	 * @since 1.4.12
	 */
	public static function remove_customizer_custom_css() {

		// If Custom File is not selected.
		if ( 'file' !== get_theme_mod( 'ocean_customzer_styling', 'head' ) ) {
			return;
		}

		global $wp_customize;

		// Disable Custom CSS in the frontend head.
		remove_action( 'wp_head', 'wp_custom_css_cb', 11 );
		remove_action( 'wp_head', 'wp_custom_css_cb', 101 );

		// If custom CSS file exists and NOT in customizer screen.
		if ( isset( $wp_customize ) ) {
			add_action( 'wp_footer', 'wp_custom_css_cb', 9999 );
		}
	}

	/**
	 * Adds inline CSS for the admin
	 *
	 * @since 1.0.0
	 */
	public static function admin_inline_css() {
		echo '<style>div#setting-error-tgmpa{display:block;}</style>';
	}

	/**
	 * Alter the search posts per page
	 *
	 * @param obj $query query.
	 * @since 1.3.7
	 */
	public static function search_posts_per_page( $query ) {
		$posts_per_page = get_theme_mod( 'ocean_search_post_per_page', '8' );
		$posts_per_page = $posts_per_page ? $posts_per_page : '8';

		if ( $query->is_main_query() && is_search() ) {
			$query->set( 'posts_per_page', $posts_per_page );
		}
	}

	/**
	 * Alter wp list categories arguments.
	 * Adds a span around the counter for easier styling.
	 *
	 * @param obj $links link.
	 * @since 1.0.0
	 */
	public static function wp_list_categories_args( $links ) {
		$links = str_replace( '</a> (', '</a> <span class="cat-count-span">(', $links );
		$links = str_replace( ')', ')</span>', $links );
		return $links;
	}

	/**
	 * Alters the default oembed output.
	 * Adds special classes for responsive oembeds via CSS.
	 *
	 * @param obj $cache     cache.
	 * @param url $url       url.
	 * @param obj $attr      attributes.
	 * @param obj $post_ID   post id.
	 * @since 1.0.0
	 */
	public static function add_responsive_wrap_to_oembeds( $cache, $url, $attr, $post_ID ) {

		// Supported video embeds.
		$hosts = apply_filters(
			'ocean_oembed_responsive_hosts',
			array(
				'vimeo.com',
				'youtube.com',
				'youtu.be',
				'blip.tv',
				'money.cnn.com',
				'dailymotion.com',
				'flickr.com',
				'hulu.com',
				'kickstarter.com',
				'vine.co',
				'soundcloud.com',
				'#http://((m|www)\.)?youtube\.com/watch.*#i',
				'#https://((m|www)\.)?youtube\.com/watch.*#i',
				'#http://((m|www)\.)?youtube\.com/playlist.*#i',
				'#https://((m|www)\.)?youtube\.com/playlist.*#i',
				'#http://youtu\.be/.*#i',
				'#https://youtu\.be/.*#i',
				'#https?://(.+\.)?vimeo\.com/.*#i',
				'#https?://(www\.)?dailymotion\.com/.*#i',
				'#https?://dai\.ly/*#i',
				'#https?://(www\.)?hulu\.com/watch/.*#i',
				'#https?://wordpress\.tv/.*#i',
				'#https?://(www\.)?funnyordie\.com/videos/.*#i',
				'#https?://vine\.co/v/.*#i',
				'#https?://(www\.)?collegehumor\.com/video/.*#i',
				'#https?://(www\.|embed\.)?ted\.com/talks/.*#i',
			)
		);

		// Supports responsive.
		$supports_responsive = false;

		// Check if responsive wrap should be added.
		foreach ( $hosts as $host ) {
			if ( strpos( $url, $host ) !== false ) {
				$supports_responsive = true;
				break; // no need to loop further.
			}
		}

		// Output code.
		if ( $supports_responsive ) {
			return '<p class="responsive-video-wrap clr">' . $cache . '</p>';
		} else {
			return '<div class="oceanwp-oembed-wrap clr">' . $cache . '</div>';
		}

	}

	/**
	 * Adds extra classes to the post_class() output
	 *
	 * @param obj $classes   Return classes.
	 * @since 1.0.0
	 */
	public static function post_class( $classes ) {

		// Get post.
		global $post;

		// Add entry class.
		$classes[] = 'entry';

		// Add has media class.
		if ( has_post_thumbnail()
			|| get_post_meta( $post->ID, 'ocean_post_self_hosted_media', true )
			|| get_post_meta( $post->ID, 'ocean_post_oembed', true )
			|| get_post_meta( $post->ID, 'ocean_post_video_embed', true ) ) {
			$classes[] = 'has-media';
		}

		// Return classes.
		return $classes;

	}

	/**
	 * Add schema markup to the authors post link
	 *
	 * @param obj $link   Author link.
	 * @since 1.0.0
	 */
	public static function the_author_posts_link( $link ) {

		// Add schema markup.
		$schema = oceanwp_get_schema_markup( 'author_link' );
		if ( $schema ) {
			$link = str_replace( 'rel="author"', 'rel="author" ' . $schema, $link );
		}

		// Return link.
		return $link;

	}

	/**
	 * Add support for Elementor Pro locations
	 *
	 * @param obj $elementor_theme_manager    Elementor Instance.
	 * @since 1.5.6
	 */
	public static function register_elementor_locations( $elementor_theme_manager ) {
		$elementor_theme_manager->register_all_core_location();
	}

	/**
	 * Add schema markup to the authors post link
	 *
	 * @since 1.1.5
	 */
	public static function remove_bb_lightbox() {
		return true;
	}

}

/**--------------------------------------------------------------------------------
#region Freemius - This logic will only be executed when Ocean Extra is active and has the Freemius SDK
---------------------------------------------------------------------------------*/

if ( ! function_exists( 'owp_fs' ) ) {
	if ( class_exists( 'Ocean_Extra' ) &&
			defined( 'OE_FILE_PATH' ) &&
			file_exists( dirname( OE_FILE_PATH ) . '/includes/freemius/start.php' )
	) {
		/**
		 * Create a helper function for easy SDK access.
		 */
		function owp_fs() {
			global $owp_fs;

			if ( ! isset( $owp_fs ) ) {
				// Include Freemius SDK.
				require_once dirname( OE_FILE_PATH ) . '/includes/freemius/start.php';

				$owp_fs = fs_dynamic_init(
					array(
						'id'                             => '3752',
						'bundle_id'                      => '3767',
						'slug'                           => 'oceanwp',
						'type'                           => 'theme',
						'public_key'                     => 'pk_043077b34f20f5e11334af3c12493',
						'bundle_public_key'              => 'pk_c334eb1ae413deac41e30bf00b9dc',
						'is_premium'                     => false,
						'has_addons'                     => true,
						'has_paid_plans'                 => true,
						'menu'                           => array(
							'slug'    => 'oceanwp',
							'account' => true,
							'contact' => false,
							'support' => false,
						),
						'bundle_license_auto_activation' => true,
						'navigation'                     => 'menu',
						'is_org_compliant'               => true,
					)
				);
			}

			return $owp_fs;
		}

		// Init Freemius.
		owp_fs();
		// Signal that SDK was initiated.
		do_action( 'owp_fs_loaded' );
	}
}

function enqueue_font_awesome() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
}

add_action('wp_enqueue_scripts', 'enqueue_font_awesome');

function keepAccordionsInitiallyClosed() { // Yomna Yousry 23rd August 2024, Script to Keep the Elementor Accodions Initially Closed
    ?>
    <script>
    jQuery(document).ready(function($) {
        var delay = 100;
        setTimeout(function() {
            $('.elementor-tab-title').removeClass('elementor-active');
            $('.elementor-tab-content').css('display', 'none');
        }, delay);
    });
    </script>
    <?php
}
add_action('wp_footer', 'keepAccordionsInitiallyClosed');

// sticky header scripts
function sticky_header_script() {
    // Enqueue jQuery if not already loaded
    wp_enqueue_script('jquery');

    // Updated jQuery scroll script (for .clearHeader and .stickyHeader2)
    $custom_js_1 = '
    jQuery(document).ready(function($) {
        var header = $(".clearHeader");
        var footer = $("footer"); // assuming your footer has a <footer> tag
        var windowHeight = $(window).height();
        var pageHeight = $(document).height();
        var footerHeight = footer.length ? footer.outerHeight() : 0; // get footer height

        // Check if the page height including the footer is greater than the window height
        if ((pageHeight + footerHeight) > windowHeight + 1000) {  // You can adjust the "+100" threshold as needed
            $(window).scroll(function() {
                var scroll = $(window).scrollTop();
                if (scroll >= 200) {
                    header.addClass("stickyHeader2");
                } else {
                    header.removeClass("stickyHeader2");
                }
            });
        }
    });
    ';
    wp_add_inline_script('jquery', $custom_js_1);

}
// Hook the function into wp_enqueue_scripts action
add_action('wp_enqueue_scripts', 'sticky_header_script');

function enqueueTooltip() {
    wp_enqueue_script('tooltip-js', get_template_directory_uri() . '/tooltip.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'enqueueTooltip');

function register_dyn_tooltip_post_type() {
    register_post_type('dynTooltip', [
        'labels' => [
            'name' => 'Dynamic Tooltips',
            'singular_name' => 'Dynamic Tooltip',
        ],
        'public' => true,
        'show_in_rest' => true,
        'supports' => ['title'],
    ]);
}
add_action('init', 'register_dyn_tooltip_post_type');

// Assem Ali, 17th Oct 2024, Enqueuing favicon script to show favicon
function favicon_script() {
    wp_enqueue_script('favicon-js', get_template_directory_uri() . '/assets/js/favicon.js', array(), filemtime(get_template_directory() . '/assets/js/favicon.js'), true);
}
add_action('wp_enqueue_scripts', 'favicon_script');

// Handling empty submission, sanitization of search input, and stricting the search within certain categories
function elementor_search_form() {
	?>
	<script>
	jQuery(document).ready(function($) {
		var pageLang = jQuery('html').attr('lang');
		var excludedEnglishCategoryIDs = '1, 1021, 1028'; // Uncategorized, User Manual-EN, Marriage Contracts-EN
		var excludedArabicCategoryIDs = '7, 1030'; // Uncategorized-AR, Marriage Contracts-AR
		
		jQuery('form.hfe-search-button-wrapper').each(function() {
			jQuery(this).append('<input type="hidden" name="exclude_cat" value="' + ((pageLang === 'ar')? excludedArabicCategoryIDs : excludedEnglishCategoryIDs) + '">');
			if (pageLang === 'ar') {
				jQuery(this).attr('action', '/ar/');
			}
		});
		
		jQuery('form.hfe-search-button-wrapper').on('submit', function(e) {
			var searchInput = jQuery(this).find('input[type="search"]').val().trim();
			searchInput = searchInput.replace(/['"<>;\\]/g, '');
			if (searchInput.length === 0) {
				e.preventDefault();
			}
		});
	});	
	</script>
	<?php
}
add_action('wp_footer', 'elementor_search_form');

add_action('pre_get_posts', function($query) {
    if ($query->is_main_query() && $query->is_search() && !is_admin()) {
        if (isset($_GET['exclude_cat'])) {
            $exclude_cat_ids = explode(',', sanitize_text_field($_GET['exclude_cat']));
            $query->set('category__not_in', $exclude_cat_ids);
        }
    }
});


// Assem Ali, making the search result contain items with search terms in title only
function wpse_search_by_title( $search, $wp_query ) {
    if ( ! empty( $search ) && ! empty( $wp_query->query_vars['search_terms'] ) ) {
        global $wpdb;

        $q = $wp_query->query_vars;
        $n = ! empty( $q['exact'] ) ? '' : '%';

        $search = array();

        foreach ( ( array ) $q['search_terms'] as $term )
            $search[] = $wpdb->prepare( "$wpdb->posts.post_title LIKE %s", $n . $wpdb->esc_like( $term ) . $n );

        if ( ! is_user_logged_in() )
            $search[] = "$wpdb->posts.post_password = ''";

        $search = ' AND ' . implode( ' AND ', $search );
    }

    return $search;
}
add_filter( 'posts_search', 'wpse_search_by_title', 10, 2 );

function localize_rest_settings() {
    wp_localize_script('Ajax-Search-Script1', 'wpApiSettings', array(
        'root' => esc_url_raw(rest_url()),
        'nonce' => wp_create_nonce('wp_rest')
    ));
}
add_action('wp_footer', 'localize_rest_settings');


add_filter( 'rest_url_prefix', 'rest_api_url_prefix' );
function rest_api_url_prefix() {
    return 'itsmethemario';
}

add_filter('rest_authentication_errors', function ($result) {
    if (!is_user_logged_in()) {
        $allowed_route = '/wp/v2/posts';
        $request_uri = $_SERVER['REQUEST_URI'];
		$request_method = $_SERVER['REQUEST_METHOD'];

        if (strpos($request_uri, $allowed_route) !== false && $request_method === 'GET') {
                return $result;
        }

        return new WP_Error('rest_forbidden', 'Access to the REST API is restricted.', array('status' => 403));
    }
    return $result;
});

function handling_total_number_of_visistors() {
    $total_visitors = ((int) do_shortcode('[wpstatistics stat="visits" time="total"]') + 2602018);
    return $total_visitors;
}
add_shortcode('new_total_visitors', 'handling_total_number_of_visistors');

// adding shortcode for templates
function render_elementor_template($atts) {
    $atts = shortcode_atts(array('id' => ''), $atts, 'elementor_template');
    $template_id = $atts['id'];

    if (empty($template_id)) {
        return 'Template ID is missing.';
    }

    // Check if Elementor is active
    if (did_action('elementor/loaded')) {
        return Elementor\Plugin::instance()->frontend->get_builder_content_for_display($template_id);
    }

    return 'Elementor is not active.';
}
add_shortcode('elementor_template', 'render_elementor_template');

// // Add a "Who Can Edit?" setting to each ACF field
// function add_who_can_edit_setting($field) {
//     acf_render_field_setting($field, array(
//         'label'         => __('Who Can Edit?'),
//         'instructions'  => __('Select which user roles can edit this field. Leave blank for everyone.'),
//         'type'          => 'select',
//         'name'          => 'who_can_edit',
//         'multiple'      => 1, // Allow multiple roles
//         'choices'       => wp_list_pluck(get_editable_roles(), 'name'), // Fetch all roles
//         'ui'            => 1, // Enable the ACF UI selector
//         'allow_null'    => 1
//     ));
// }
// add_action('acf/render_field_settings', 'add_who_can_edit_setting');

// // Disable fields for users who don’t have permission
// function restrict_field_based_on_role($field) {
//     if (!isset($field['who_can_edit'])) {
//         return $field; // Skip if no restriction is set
//     }

//     $current_user = wp_get_current_user();
//     $allowed_roles = (array) $field['who_can_edit']; // Get allowed roles

//     // If roles are set and the user is not in them, disable the field
//     if (!empty($allowed_roles) && !array_intersect($allowed_roles, $current_user->roles)) {
//         $field['disabled'] = 1;
//     }

//     return $field;
// }
// add_filter('acf/prepare_field', 'restrict_field_based_on_role');

// function enqueue_hero_styles_global() {
//     wp_enqueue_style('hero-css', get_template_directory_uri() . '/css/hero-style.css', [], '1.0', 'all');
// }
// add_action('wp_enqueue_scripts', 'enqueue_hero_styles_global');


function restrict_acf_field_update($value, $post_id, $field) {
	$user_roles = wp_get_current_user()->roles;
	$allowed_roles = array('administrator');
	if (!array_intersect($user_roles, $allowed_roles)) {
		$field_key = 'testona';
		$original_value = get_field($field_key, $post_id);
		wp_die("You are not allowed to change the field '{$field_key}'."); 
		return $original_value;
	}

    return $value;
}
add_filter('wp_insert_post_data', 'restrict_acf_field_update', 10, 3);


// expired date stuff
function add_expiry_date_meta_box() {
    add_meta_box('post_expiry_date', 'Post Expiry Date', 'post_expiry_date_callback', get_all_custom_post_types(), 'side', 'high');
}
add_action('add_meta_boxes', 'add_expiry_date_meta_box');

function post_expiry_date_callback($post) {
    $expiry_date = get_post_meta($post->ID, '_post_expiry_date', true);
    ?>
    <label for="post_expiry_date">Expiry Date:</label>
    <input type="date" id="post_expiry_date" name="post_expiry_date" value="<?php echo esc_attr($expiry_date); ?>" />
    <?php
}

function save_expiry_date_meta($post_id) {
    if (isset($_POST['post_expiry_date'])) {
        update_post_meta($post_id, '_post_expiry_date', sanitize_text_field($_POST['post_expiry_date']));
    }
}
add_action('save_post', 'save_expiry_date_meta');

function custom_cron_schedules($schedules) {
    $schedules['every_five_minutes'] = array(
        'interval' => 5 * 60,
        'display'  => __('Every 5 Minutes'),
    );

	$schedules['every_ten_minutes'] = array(
        'interval' => 10 * 60,
        'display'  => __('Every 10 Minutes'),
    );

    return $schedules;
}
add_filter('cron_schedules', 'custom_cron_schedules');

function schedule_expiry_checker() {
	// wp_clear_scheduled_hook('thanos_event');
    if (!wp_next_scheduled('thanos_event')) {
        wp_schedule_event(time(), 'every_five_minutes', 'thanos_event');
    }
}
add_action('init', 'schedule_expiry_checker');
add_action('thanos_event', 'check_and_expire_posts');
// do_action('thanos_event');


function check_and_expire_posts() {
    $today_date = strtotime(date('Y-m-d'));
    
    $args = array(
		'post_type'      => get_all_custom_post_types(),
		'post_status'    => 'publish', 
		'posts_per_page' => -1, // gets all the posts
	);

    $query = new WP_Query($args);

    if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			$expiry_date = get_post_meta(get_the_ID(), '_post_expiry_date', true);
			if ($expiry_date) {
				$expiry_date = strtotime($expiry_date);
				if ($expiry_date <= $today_date) {
					wp_update_post(array(
						'ID'          => get_the_ID(),
						'post_status' => 'expired',
					));
				}
		    }
	    }
		wp_reset_postdata();
    }
}
check_and_expire_posts();

function get_all_custom_post_types() {
	$custom_post_types = get_post_types(
		array(
			'public'   => true,
			'_builtin' => false, // if "true", it will return default post types only
		),
		'names'
	);

	array_push($custom_post_types, 'post', 'page');

	// foreach ($custom_post_types as $post_type) {
	// 	echo $post_type . ' ';
	// }

	return $custom_post_types;
}
// add_shortcode('post_types', 'get_all_custom_post_types');

function get_post_statuses_function() {
	$statuses = get_post_statuses();
	foreach ($statuses as $status => $label) {
		echo $status . ': ' . $label . '<br>';
	}
}
add_shortcode('post_statuses', 'get_post_statuses_function');

function bomba_expiry() {
	$today_date = date('Y-m-d');
	$args = array(
		'post_type'      => get_all_custom_post_types(),
		'post_status'    => 'publish',
		'posts_per_page' => -1,
	);
	
	$query = new WP_Query($args);
	
	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			$expiry_date = get_post_meta(get_the_ID(), '_post_expiry_date', true);
			if ($expiry_date) {
				echo 'Post ID: ' . get_the_ID() . ' - Expiry Date: ' . $expiry_date . '<br>';
				if ($expiry_date <= $today_date) {
					echo 'this post is expired' . '<br>';
					wp_update_post(array(
						'ID'          => get_the_ID(),
						'post_status' => 'expired',
					));
				} else {
					echo 'this post is not expired' . '<br>';
				}
			} else {
				echo 'Post ID: ' . get_the_ID() . ' - No Expiry Date<br>';
			}
		}
		wp_reset_postdata();
	}
}
// add_shortcode('posts_expiry_dates', 'bomba_expiry');


function current_eventss() {
	wp_clear_scheduled_hook('check_expired_posts_event');
    $current_events = _get_cron_array(); // Get all scheduled events

    if (!empty($current_events)) {
        foreach ($current_events as $timestamp => $hooks) {
            foreach ($hooks as $hook => $details) {
                echo 'Event Hook: <strong>' . esc_html($hook) . '</strong> at ' . date('Y-m-d H:i:s', $timestamp) . '<br>';
            }
        }
    } else {
        echo 'No scheduled events found.';
    }
}
add_shortcode('current_events_shortcode', 'current_eventss');


// end of expired date stuff

function disable_new_posts_for_acf_cpt($args, $post_type) {
    if ($post_type === 'custom-widgets') { // Replace with your actual ACF CPT slug
        $args['capabilities']['create_posts'] = 'do_not_allow'; 
    }
    return $args;
}
add_filter('register_post_type_args', 'disable_new_posts_for_acf_cpt', 10, 2);

// Add the custom setting for ACF fields
function add_who_can_edit_setting($field) {
    acf_render_field_setting($field, array(
        'label'         => __('Who Can Edit?'),
        'instructions'  => __('Select which user roles can edit this field. Leave blank for everyone.'),
        'type'          => 'radio',
        'name'          => 'who_can_edit',
        'choices'       => array(
            'administrator'   => __('Admins'),
            'editor'  => __('Editors'),
            'everyone' => __('Everyone'),
        ),
        'ui'            => 1, // Enable ACF UI
        'allow_null'    => 0,
        'layout'        => 'vertical', // Display radio buttons vertically
    ));
}
add_action('acf/render_field_settings', 'add_who_can_edit_setting');

// Convert the setting to a string if needed (in case it's an array)
function convert_who_can_edit_to_string( $field ) {
    if ( isset($field['who_can_edit']) && is_array($field['who_can_edit']) ) {
        $field['who_can_edit'] = reset($field['who_can_edit']);
    }
    return $field;
}
add_filter('acf/load_field', 'convert_who_can_edit_to_string');

// Restrict fields based on user roles
function restrict_field_based_on_role($field) {
    $current_user = wp_get_current_user();
    $user_roles = (array) $current_user->roles;
    $allowed_roles = [];
	if(isset($field['who_can_edit'])){
		switch ($field['who_can_edit']) {
			case 'administrator':
				$allowed_roles = ['administrator'];
				break;
			case 'editor':
				$allowed_roles = ['editor', 'administrator'];
				break;
			default:
				return $field; // Return the field without modifying it
		}
	}

    // Only restrict the field if the user doesn't have the allowed role
    if (!empty($allowed_roles) && !array_intersect($allowed_roles, $user_roles)) {
        if (!isset($field['wrapper']['class'])) {
            $field['wrapper']['class'] = '';
        }
        $field['wrapper']['class'] .= ' disabled-acf-field'; // Visually disable the field
    }

    return $field;
}
add_filter('acf/prepare_field', 'restrict_field_based_on_role');

function disable_acf_fields_script() {
    wp_enqueue_script('disable-acf-fields', get_template_directory_uri() . '/js/disable-acf-fields.js', array('jquery'), filemtime(get_template_directory() . '/js/disable-acf-fields.js'), true);
}
add_action('acf/input/admin_enqueue_scripts', 'disable_acf_fields_script');

// Stuff related to new custom widgets testing

function my_custom_widget_shortcode($atts = []) {
    $atts = shortcode_atts([
        'title' => 'Hello from shortcode!',
    ], $atts);

    $elementor = \Elementor\Plugin::instance();

    $widget_data = [
        'id' => uniqid('my_widget_'),
        'elType' => 'widget',
        'widgetType' => 'my_dynamic_image_toggle', // Must match get_name()
        'settings' => [
            'title' => $atts['title'],
        ],
    ];

    ob_start();
    echo $elementor->frontend->get_builder_content_for_display([
        'elements' => [ $widget_data ]
    ]);
    return ob_get_clean();
}
add_shortcode('my_dynamic_image_toggle_shortcode', 'my_custom_widget_shortcode');

// Hook to add admin menu
add_action('admin_menu', 'register_custom_widget_tracker_menu');

function register_custom_widget_tracker_menu() {
    add_menu_page(
        'Custom Widget Tracker',            // Page title
        'Widget Tracker',                   // Menu title
        'manage_options',                   // Capability
        'custom-widget-tracker',            // Menu slug
        'render_custom_widget_tracker_page',// Callback function
        'dashicons-search',                 // Icon
        90                                  // Position
    );
}

function render_custom_widget_tracker_page() {
    echo '<div class="wrap"><h1>Custom Banner Slider Widget Tracker</h1>';

    $widgets = get_all_custom_banner_slider_widgets();

    if (empty($widgets)) {
        echo '<p>No instances of <code>custom_banner_slider</code> found on the site.</p>';
    } else {
        echo '<table class="widefat fixed striped">';
        echo '<thead><tr><th>Post Title</th><th>Post ID</th><th>Gallery Count</th><th>Edit Link</th></tr></thead>';
        echo '<tbody>';
        foreach ($widgets as $widget) {
            $post_title = get_the_title($widget['post_id']);
            $edit_link = get_edit_post_link($widget['post_id']);
            // $slide_speed = esc_html($widget['settings']['slide_speed']);
            $gallery_count = isset($widget['settings']['gallery']) ? count($widget['settings']['gallery']) : 0;
			// <th>Slide Speed</th> <td>{$slide_speed} ms</td> 

            echo "<tr>
                <td>{$post_title}</td>
                <td>{$widget['post_id']}</td>
                
                <td>{$gallery_count}</td>
                <td><a href='{$edit_link}' class='button'>Edit Page</a></td>
            </tr>";
        }
        echo '</tbody></table>';
    }

    echo '</div>';
}

function get_all_custom_banner_slider_widgets() {
    global $wpdb;
    $widgets = [];

    $results = $wpdb->get_results("
        SELECT post_id, meta_value 
        FROM {$wpdb->postmeta}
        WHERE meta_key = '_elementor_data'
          AND meta_value LIKE '%\"widgetType\":\"banner_gallery_slider\"%'
    ");

    foreach ($results as $row) {
        $data = json_decode($row->meta_value, true);
        if (!$data) continue;

        // Recursively look for the widget
        $queue = $data;
        while (!empty($queue)) {
            $node = array_shift($queue);

            if (isset($node['widgetType']) && $node['widgetType'] === 'banner_gallery_slider') {
                $widgets[] = [
                    'post_id' => $row->post_id,
                    'settings' => $node['settings']
                ];
            }

            if (!empty($node['elements'])) {
                $queue = array_merge($queue, $node['elements']);
            }
        }
    }

    return $widgets;
}

// end of new custom widgets testing

function ega_enqueue_custom_fonts() {
	wp_enqueue_style('ega-fonts', get_template_directory_uri() . '/css/font-file.css');
}
add_action('wp_enqueue_scripts', 'ega_enqueue_custom_fonts');


// endregion

new OCEANWP_Theme_Class();