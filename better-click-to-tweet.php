<?php
/**
 * Plugin Name: Better Click To Tweet
 * Description: Add Click to Tweet boxes simply and elegantly to your posts or pages. All the features of a premium plugin, for FREE!
 * Version: 5.0
 * Author: Ben Meredith
 * Author URI: https://www.wpsteward.com
 * Plugin URI: https://wordpress.org/plugins/better-click-to-tweet/
 * License: GPL2
 * Text Domain: better-click-to-tweet
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Better_Click_To_Tweet' ) ) :

	/**
	 * Class Better_Click_To_Tweet
	 */
	final class Better_Click_To_Tweet {

		/**
		 * Instance
		 *
		 * @since  6.0
		 * @access private
		 *
		 * @var    Better_Click_To_Tweet
		 */
		private static $instance;

		/**
		 * BCTT Shortcodes Object
		 *
		 * @since  6.0
		 * @access public
		 *
		 * @var    Better_Click_To_Tweet_Shortcodes object
		 */
		public $shortcodes;

		/**
		 * Settings Object
		 *
		 * @since  6.0
		 * @access public
		 *
		 * @var    Better_Click_To_Tweet_Settings object
		 */
		public $settings;

		/**
		 * BCTT i18n Notice Object
		 *
		 * @since  6.0
		 * @access public
		 *
		 * @var    BCTT_i18n_Notice object
		 */
		public $i18n_notice;

		/**
		 * Main Better_Click_To_Tweet Instance
		 *
		 * Insures that only one instance of Better_Click_To_Tweet exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since     6.0
		 * @access    public
		 *
		 * @staticvar array $instance
		 *
		 * @return    Better_Click_To_Tweet
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) ) {

				self::$instance = new Better_Click_To_Tweet();
				self::$instance->setup_constants();

				add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );

				self::$instance->includes();

				self::$instance->settings   = new Better_Click_To_Tweet_Settings();
				self::$instance->licenses   = new Better_Click_To_Tweet_License();
				self::$instance->shortcodes = new Better_Click_To_Tweet_Shortcodes();

				// instantiate i18n encouragement module
				self::$instance->i18n_notice = new BCTT_i18n_Notice(
					array(
						'textdomain'     => 'better-click-to-tweet',
						'project_slug'   => '/wp-plugins/better-click-to-tweet/stable',
						'plugin_name'    => 'Better Click To Tweet',
						'hook'           => 'bctt_settings_top',
						'glotpress_url'  => 'https://translate.wordpress.org/',
						'glotpress_name' => 'Translating WordPress',
						'glotpress_logo' => 'https://plugins.svn.wordpress.org/better-click-to-tweet/assets/icon-256x256.png',
						'register_url '  => 'https://translate.wordpress.org/projects/wp-plugins/better-click-to-tweet/',
					)
				);

				self::$instance->actions();
			}

			return self::$instance;
		}

		/**
		 * Setup plugin constants
		 *
		 * @since  6.0
		 * @access private
		 *
		 * @return void
		 */
		private function setup_constants() {

			// Plugin Folder Path.
			if ( ! defined( 'BCTT_PLUGIN_DIR' ) ) {
				define( 'BCTT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL.
			if ( ! defined( 'BCTT_PLUGIN_URL' ) ) {
				define( 'BCTT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Basename.
			if ( ! defined( 'BCTT_PLUGIN_BASENAME' ) ) {
				define( 'BCTT_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
			}

			// Plugin Root File.
			if ( ! defined( 'BCTT_PLUGIN_FILE' ) ) {
				define( 'BCTT_PLUGIN_FILE', __FILE__ );
			}

		}

		/**
		 * Include required files
		 *
		 * @since  6.0
		 * @access private
		 *
		 * @return void
		 */
		private function includes() {
			require_once BCTT_PLUGIN_DIR . 'includes/class-bctt-i18n-notice.php';
			require_once BCTT_PLUGIN_DIR . 'includes/class-bctt-shortcodes.php';
			require_once BCTT_PLUGIN_DIR . 'includes/class-bctt-license-handler.php';
			require_once BCTT_PLUGIN_DIR . 'includes/class-bctt-settings.php';
			require_once BCTT_PLUGIN_DIR . 'assets/tinymce/bctt-tinymce.php';
		}

		/**
		 * Add our actions.
		 *
		 * @since  1.0
		 * @access private
		 *
		 * @return void
		 */
		private function actions() {

			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ), 10 );

		}

		/**
		 * Frontend Script Loading
		 *
		 * @param $hook
		 */
		public function frontend_scripts( $hook ) {

			if ( get_option( 'bctt_disable_css' ) ) {
				add_option( 'bctt_style_dequeued', true );
				foreach ( wp_load_alloptions() as $option => $value ) {
					if ( strpos( $option, 'bcct_' ) === 0 ) {
						delete_option( $option );
					}
				}

				return;
			}

			$dir = wp_upload_dir();

			$custom = file_exists( $dir['basedir'] . '/bcttstyle.css' );

			$tag      = $custom ? 'bcct_custom_style' : 'bcct_style';
			$antitag  = $custom ? 'bcct_style' : 'bcct_custom_style';
			$location = $custom ?    : plugins_url( 'assets/css/styles.css', __FILE__ );

			$version = $custom ? '1.0' : '3.0';

			wp_register_style( $tag, $location, false, $version, 'all' );

			wp_enqueue_style( $tag );

			delete_option( 'bctt_style_dequeued' );
			add_option( $tag . '_enqueued', true );
			delete_option( $antitag . '_enqueued' );


		}

		/**
		 * Loads the plugin language files
		 *
		 * @since  6.0
		 * @access public
		 *
		 * @return void
		 */
		public function load_textdomain() {

			// Set filter for BCTT's languages directory
			$bctt_lang_dir = dirname( plugin_basename( BCTT_PLUGIN_FILE ) ) . '/languages/';
			$bctt_lang_dir = apply_filters( 'bctt_languages_directory', $bctt_lang_dir );

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale', get_locale(), 'better-click-to-tweet' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'better-click-to-tweet', $locale );

			// Setup paths to current locale file
			$mofile_local  = $bctt_lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/better-click-to-tweet/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/better-click-to-tweet directory.
				load_textdomain( 'better-click-to-tweet', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local location from filter `bctt_languages_directory`.
				load_textdomain( 'better-click-to-tweet', $mofile_local );
			} else {
				// Load the default language files packaged up w/ BCTT
				load_plugin_textdomain( 'better-click-to-tweet', false, $bctt_lang_dir );
			}
		}

	}

endif; // End if class_exists check.

/**
 * The main function responsible for returning the one true Better_Click_To_Tweet instance
 * to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $recurring = Better_Click_To_Tweet(); ?>
 *
 * @since 1.0
 *
 * @return Better_Click_To_Tweet one true Better_Click_To_Tweet instance.
 */

function Better_Click_To_Tweet() {
	return Better_Click_To_Tweet::instance();
}

add_action( 'init', 'Better_Click_To_Tweet', 1 );