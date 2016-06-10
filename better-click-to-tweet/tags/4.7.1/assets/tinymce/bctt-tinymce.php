<?php
/**
 * This file for use with the Better Click To Tweet Plugin. http://wordpress.org/plugins/better-click-to-tweet
 * Huge thanks to Andrew Norcross @norcross for pretty much every bit of code you see here:
 *
 * @since 4.0
 * @author Andrew Norcross
 */

defined( 'ABSPATH' ) or die( "No soup for you. You leave now." );

if ( ! class_exists( 'BCTT_TinyMCE' ) ) {

// Start up the engine
	class BCTT_TinyMCE {

		/**
		 * This is our constructor
		 *
		 * @return BCTT_TinyMCE
		 */
		public function __construct() {
			add_action( 'admin_init', array( $this, 'tinymce_loader' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'tinymce_css' ), 10 );
		}

		/**
		 * load our CSS file
		 * @return [type]       [description]
		 */
		public function tinymce_css() {

			wp_enqueue_style( 'bctt-admin', plugins_url( '/css/bctt-admin.css', __FILE__ ), array(), null, 'all' );
		}

		/**
		 * load the TinyMCE button
		 *
		 * @return [type] [description]
		 */
		public function tinymce_loader() {
			add_filter( 'mce_external_languages', array( __class__, 'bctt_tinymce_languages' ) );
			add_filter( 'mce_external_plugins', array( __class__, 'bctt_tinymce_core' ) );
			add_filter( 'mce_buttons', array( __class__, 'bctt_tinymce_buttons' ) );
		}

		/**
		 * loader for the language strings
		 *
		 */
		public static function bctt_tinymce_languages( $bctt_locales ) {
			$bctt_locales['bctt'] = plugin_dir_path( __FILE__ ) . '/languages/bctt-mce-locale.php';

			return $bctt_locales;
		}

		/**
		 * loader for the required JS
		 *
		 * @param  [type] $plugin_array [description]
		 *
		 * @return [type]               [description]
		 */
		public static function bctt_tinymce_core( $plugin_array ) {

			// add our JS file
			$plugin_array['bctt'] = plugins_url( '/js/tinymce-bctt.js', __FILE__ );

			// return the array
			return $plugin_array;
		}

		/**
		 * Add the button key for event link via JS
		 *
		 * @param  [type] $buttons [description]
		 *
		 * @return [type]          [description]
		 */
		public static function bctt_tinymce_buttons( $buttons ) {

			// set the 'kitchen sink' button as a variable for later
			$sink = array_search( 'wp_adv', $buttons );

			// remove the sink
			if ( ! empty( $sink ) ) {
				unset( $buttons[ $sink ] );
			}

			// push our buttons to the end
			array_push( $buttons, 'bctt' );

			// now add back the sink
			if ( ! empty( $sink ) ) {
				$buttons[] = 'wp_adv';
			}

			// send them back
			return $buttons;
		}

// end class
	}

// end exists check
}

// Instantiate our class
new BCTT_TinyMCE();