<?php
/*
 * License Handler Class
 *
 * This is the base class for adding licenses for Premium add-ons. Each add-on extends this class
 *
 * @since 5.7.0
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'BCTT_License' ) ):

	class BCTT_License {
		/**
		 * File
		 *
		 * @access private
		 * @since  1.0
		 *
		 * @var    string
		 */
		private $file;

		/**
		 * License
		 *
		 * @access private
		 * @since  1.0
		 *
		 * @var    string
		 */
		private $license;

		/**
		 * Item name
		 *
		 * @access private
		 * @since  1.0
		 *
		 * @var    string
		 */
		private $item_name;

		/**
		 * License Information object.
		 *
		 * @access private
		 * @since  1.7
		 *
		 * @var    object
		 */
		private $license_data;

		/**
		 * Item shortname
		 *
		 * @access private
		 * @since  1.0
		 *
		 * @var    string
		 */
		private $item_shortname;

		/**
		 * Version
		 *
		 * @access private
		 * @since  1.0
		 *
		 * @var    string
		 */
		private $version;

		/**
		 * Author
		 *
		 * @access private
		 * @since  1.0
		 *
		 * @var    string
		 */
		private $author;

		/**
		 * array of licensed addons
		 *
		 * @since  2.1.4
		 * @access private
		 *
		 * @var    array
		 */
		private static $licensed_addons = array();

		/**
		 * API URL
		 *
		 * @access private
		 * @since  1.0
		 *
		 * @var    string
		 */
		private $api_url = 'https://www.betterclicktotweet.com/';


		public function __construct( $_file, $_item_name, $_version, $_author, $_optname = null, $_api_url = null ) {


			$this->file           = $_file;
			$this->item_name      = $_item_name;
			$this->item_shortname = 'bctt_' . bctt_addon_slug( bctt_addon_shortname( $this->item_name ) );
			$this->item_slug      = preg_replace( '/[^a-zA-Z0-9_\s]/', '', str_replace( ' ', '_', strtolower( $this->item_shortname ) ) );
			$this->version        = $_version;
			$bctt_license         = get_option( $this->item_shortname . '_license' );
			$this->license        = isset( $bctt_license ) ? trim( $bctt_license ) : '';
			$this->author         = $_author;
			$this->api_url        = is_null( $_api_url ) ? $this->api_url : $_api_url;


			$this->includes();
			$this->hooks();
			$this->bctt_updater();
		}

		private function hooks() {
			add_action( 'admin_init', array( $this, 'bctt_updater' ), 0 );
			add_action( 'admin_init', array( $this, 'activate_license' ), 10 );
			add_action( 'admin_init', array( $this, 'deactivate_license' ), 11 );

		}


		public function bctt_updater() {

			// retrieve our license key from the DB
			$license_key = trim( get_option( $this->license ) );

			// setup the updater
			$edd_updater = new BCTT_SL_Plugin_Updater(
				$this->api_url,
				$this->file,
				array(
					'version'   => $this->version,   // current version number
					'license'   => $this->license,   // license key (used get_option above to retrieve from DB)
					'item_name' => $this->item_name, // name of this plugin
					'author'    => $this->author,    // author of this plugin
				)
			);

		}

		public static function get_short_name( $plugin_name ) {
			$plugin_name = trim( str_replace( 'Better Click To Tweet ', '', $plugin_name ) );
			$plugin_name = 'bctt_' . preg_replace( '/[^a-zA-Z0-9_\s]/', '', str_replace( ' ', '_', strtolower( $plugin_name ) ) );

			return $plugin_name;
		}

		private function includes() {

			if ( ! class_exists( 'BCTT_SL_Plugin_Updater' ) ) {
				require_once 'BCTT_SL_Plugin_Updater.php';
			}
		}

		public function activate_license() {

			// listen for our activate button to be clicked
			if ( isset( $_POST[ $this->item_shortname . '_license_activate'] ) ) {

				// run a quick security check
			if ( ! isset( $_REQUEST[ $this->item_shortname . '_license_nonce'] ) || ! wp_verify_nonce( $_REQUEST[ $this->item_shortname . '_license_nonce'], $this->item_shortname . '_license_nonce' ) ) {

			return;

			}
				if ( empty( $_POST["{$this->item_shortname}_license"] ) ) {
					$this->unset_license();

					return;
				}

				// Do not simultaneously activate add-ons if the user want to deactivate a specific add-on.
				if ( $this->is_deactivating_license() ) {
					return;
				}

				// Get license key.
				$this->license = sanitize_text_field( $_POST[ $this->item_shortname . '_license' ] );

				// Make the call to the API and make sure there are no api errors.
				$license_data = $this->get_license_info( 'activate_license' );
				// var_dump($license_data );
				if (  $license_data->success !== true ) {
					add_settings_error('bctt-license', esc_attr( 'settings_updated' ), __( 'License failed to activate. Please double-check it and enter again.', 'better-click-to-tweet' ) );
					return;
				}


				// Tell WordPress to check for updates
				set_site_transient( 'update_plugins', null );

				//update the license key option.
				update_option( "{$this->item_shortname}_license", $this->license );
				update_option( "{$this->item_shortname}_license_active", 'valid' );


			}
		}

		public function get_license_info( $edd_action = '', $response_in_array = false ) {

			if ( empty( $edd_action ) ) {
				return false;
			}

			// Data to send to the API.
			$api_params = array(
				'edd_action' => $edd_action, // never change from "edd_" to "bctt_"!
				'license'    => $this->license,
				'item_name'  => urlencode( $this->item_name ),
				'url'        => home_url(),
			);

			// Call the API.
			$response = wp_remote_post(
				$this->api_url,
				array(
					'timeout'   => 15,
					'sslverify' => false,
					'body'      => $api_params,
				)
			);

			// Make sure there are no errors.
			if ( is_wp_error( $response ) ) {
				return false;
			}

			return json_decode( wp_remote_retrieve_body( $response ), $response_in_array );
		}

		private function unset_license() {

			if ( ! ( $license_data = $this->get_license_info( 'deactivate_license' ) ) ) {
				return;
			}
			// Remove license from database.
			delete_option( "{$this->item_shortname}_license_active" );
			delete_option( "{$this->item_shortname}_license" );
			unset( $_POST["{$this->item_shortname}_license"] );

			// Unset license param.
			$this->license = '';
		}

		private function is_deactivating_license() {
			$status = false;

			foreach ( $_POST as $key => $value ) {
				if ( false !== strpos( $key, 'license_deactivate' ) ) {
					$status = true;
					break;
				}
			}

			return $status;
		}

		public function deactivate_license() {

			// Run on deactivate button press.
			if ( isset( $_POST[ $this->item_shortname . '_license_deactivate'] ) ) {
				$this->unset_license();
			}
		}

	}
endif;