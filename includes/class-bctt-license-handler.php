<?php
/**
 * Better Click To Tweet License handler
 *
 * @package     Better_Click_To_Tweet
 * @subpackage  Admin/License
 * @copyright   Copyright (c) 2016, Ben Meredith
 * @license     https://opensource.org/licenses/gpl-license GNU Public License
 * @since       6.0
 */

// Exit if accessed directly.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Better_Click_To_Tweet_License' ) ) :

	/**
	 * Better_Click_To_Tweet_License Class
	 *
	 * This class simplifies the process of adding license information to new Better Click To Tweet add-ons.
	 *
	 * @since 6.0
	 */
	class Better_Click_To_Tweet_License {

		/**
		 * File
		 *
		 * @access private
		 * @since  6.0
		 *
		 * @var    string
		 */
		private $file;

		/**
		 * License
		 *
		 * @access private
		 * @since  6.0
		 *
		 * @var    string
		 */
		private $license;

		/**
		 * Item name
		 *
		 * @access private
		 * @since  6.0
		 *
		 * @var    string
		 */
		private $item_name;

		/**
		 * Item shortname
		 *
		 * @access private
		 * @since  6.0
		 *
		 * @var    string
		 */
		private $item_shortname;

		/**
		 * Version
		 *
		 * @access private
		 * @since  6.0
		 *
		 * @var    string
		 */
		private $version;

		/**
		 * Author
		 *
		 * @access private
		 * @since  6.0
		 *
		 * @var    string
		 */
		private $author;

		/**
		 * API URL
		 *
		 * @access private
		 * @since  6.0
		 *
		 * @var    string
		 */
		private $api_url = 'https://www.wpsteward.com/';

		/**
		 * Class Constructor
		 *
		 * Set up the Better Click To Tweet License Class.
		 *
		 * @access public
		 * @since  6.0
		 *
		 * @param string $_file
		 * @param string $_item_name
		 * @param string $_version
		 * @param string $_author
		 * @param string $_optname
		 * @param string $_api_url
		 * @param string $_checkout_url
		 * @param string $_account_url
		 */
		public function __construct( $_file, $_item_name, $_version, $_author, $_optname = null, $_api_url = null, $_checkout_url = null, $_account_url = null ) {


			$this->file           = $_file;
			$this->item_name      = $_item_name;
			$this->item_shortname = preg_replace( '/[^a-zA-Z0-9_\s]/', '', str_replace( ' ', '_', strtolower( $this->item_name ) ) );
			$bctt_options         = get_option( $this->item_shortname . '_license_key' );
			$this->version        = $_version;
			$this->license        = isset( $bctt_options ) ? trim( $bctt_options ) : '';
			$this->license_data   = get_option( $this->item_shortname . '_license_active' );
			$this->author         = $_author;
			$this->api_url        = is_null( $_api_url ) ? $this->api_url : $_api_url;
			$this->checkout_url   = is_null( $_checkout_url ) ? $this->checkout_url : $_checkout_url;
			$this->account_url    = is_null( $_account_url ) ? $this->account_url : $_account_url;

			// Setup hooks
			$this->includes();
			$this->hooks();
			//$this->auto_updater();
		}

		/**
		 * Includes
		 *
		 * Include the updater class.
		 *
		 * @access private
		 * @since  6.0
		 *
		 * @return void
		 */
		private function includes() {
			if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
				require_once 'EDD_SL_Plugin_Updater.php';
			}
		}

		/**
		 * Hooks
		 *
		 * Setup license hooks.
		 *
		 * @access private
		 * @since  6.0
		 *
		 * @return void
		 */
		private function hooks() {

			// Register settings
			add_filter( 'bctt_settings_licenses', array( $this, 'settings' ), 20 );

			// Activate license key on settings save
			add_action( 'admin_init', array( $this, 'activate_license' ) );

			// Deactivate license key
			add_action( 'admin_init', array( $this, 'deactivate_license' ) );

			// Updater
			//add_action( 'admin_init', array( $this, 'auto_updater' ), 0 );

			add_action( 'admin_notices', array( $this, 'notices' ) );

			// Check license weekly.
			add_action( 'bctt_weekly_scheduled_events', array( $this, 'weekly_license_check' ) );
			add_action( 'bctt_validate_license_when_site_migrated', array( $this, 'weekly_license_check' ) );

			// Check subscription weekly.
			add_action( 'bctt_weekly_scheduled_events', array( $this, 'weekly_subscription_check' ) );
			add_action( 'bctt_validate_license_when_site_migrated', array( $this, 'weekly_subscription_check' ) );
		}

		/**
		 * Auto Updater
		 *
		 * @access private
		 * @since  6.0
		 *
		 * @return bool
		 */
		public function auto_updater() {

			if ( ! $this->is_valid_license() ) {
				return false;
			}

			// Setup the updater
			$bctt_updater = new EDD_SL_Plugin_Updater(
				$this->api_url,
				$this->file,
				array(
					'version'   => $this->version,
					'license'   => $this->license,
					'item_name' => $this->item_name,
					'author'    => $this->author
				)
			);
		}

		/**
		 * License Settings
		 *
		 * Add license field to settings.
		 *
		 * @access public
		 * @since  6.0
		 *
		 * @param  array $settings License settings.
		 *
		 * @return array           License settings.
		 */
		public function settings( $settings ) {

			$bctt_license_settings = array(
				array(
					'name'    => $this->item_name,
					'id'      => $this->item_shortname . '_license_key',
					'desc'    => '',
					'type'    => 'license_key',
					'options' => array(
						'license'      => get_option( $this->item_shortname . '_license_active' ),
						'shortname'    => $this->item_shortname,
						'item_name'    => $this->item_name,
						'api_url'      => $this->api_url,
						'checkout_url' => $this->checkout_url,
						'account_url'  => $this->account_url,
					),
					'size'    => 'regular',
				),
			);

			return array_merge( $settings, $bctt_license_settings );
		}


		/**
		 * License Settings Content
		 *
		 * Add Some Content to the Licensing Settings.
		 *
		 * @access public
		 * @since  6.0
		 *
		 * @param  array $settings License settings content.
		 *
		 * @return array           License settings content.
		 */
		public function license_settings_content( $settings ) {

			$bctt_license_settings = array(
				array(
					'name' => esc_html__( 'Add-on Licenses', 'better-click-to-tweet' ),
					'desc' => '<hr>',
					'type' => 'bctt_title',
					'id'   => 'bctt_title'
				),
			);

			return array_merge( $settings, $bctt_license_settings );
		}

		/**
		 * Activate License
		 *
		 * Activate the license key.
		 *
		 * @access public
		 * @since  6.0
		 *
		 * @return void
		 */
		public function activate_license() {
			// Bailout: Check if license key set of not.
			if ( ! isset( $_POST[ $this->item_shortname . '_license_key' ] ) ) {
				return;
			}

			// Security check.
			if ( ! wp_verify_nonce( $_REQUEST[ $this->item_shortname . '_license_key-nonce' ], $this->item_shortname . '_license_key-nonce' ) ) {

				wp_die( esc_html__( 'Nonce verification failed.', 'better-click-to-tweet' ), esc_html__( 'Error', 'better-click-to-tweet' ), array( 'response' => 403 ) );

			}

			// Check if user have correct permissions.
			if ( ! current_user_can( 'manage_bctt_settings' ) ) {
				return;
			}

			// Allow third party addon developers to handle license activation.
			if ( $this->__is_third_party_addon() ) {
				do_action( 'bctt_activate_license', $this );

				return;
			}

			// Delete previous license setting if a empty license key submitted.
			if ( empty( $_POST[ $this->item_shortname . '_license_key' ] ) ) {
				delete_option( $this->item_shortname . '_license_active' );

				return;
			}

			// Do not simultaneously activate any addon if user want to deactivate any addon.
			foreach ( $_POST as $key => $value ) {
				if ( false !== strpos( $key, 'license_key_deactivate' ) ) {
					// Don't activate a key when deactivating a different key
					return;
				}
			}


			// Check if plugin previously installed.
			if ( $this->is_valid_license() ) {
				return;
			}

			// Get license key.
			$license = sanitize_text_field( $_POST[ $this->item_shortname . '_license_key' ] );

			// Bailout.
			if ( empty( $license ) ) {
				return;
			}

			// Delete previous license key from subscription if previously added.
			$this->__remove_license_key_from_subscriptions();

			// Data to send to the API
			$api_params = array(
				'edd_action' => 'activate_license', //never change from "edd_" to "bctt_"!
				'license'    => $license,
				'item_name'  => urlencode( $this->item_name ),
				'url'        => home_url()
			);

			// Call the API
			$response = wp_remote_post(
				$this->api_url,
				array(
					'timeout'   => 15,
					'sslverify' => false,
					'body'      => $api_params
				)
			);

			// Make sure there are no errors
			if ( is_wp_error( $response ) ) {
				return;
			}

			// Tell WordPress to look for updates
			set_site_transient( 'update_plugins', null );

			// Decode license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			update_option( $this->item_shortname . '_license_active', $license_data );

			// Check subscription for license key and store this to db (if any).
			$this->__single_subscription_check();
		}

		/**
		 * Deactivate License
		 *
		 * Deactivate the license key.
		 *
		 * @access public
		 * @since  6.0
		 *
		 * @return void
		 */
		public function deactivate_license() {

			if ( ! isset( $_POST[ $this->item_shortname . '_license_key' ] ) ) {
				return;
			}

			if ( ! wp_verify_nonce( $_REQUEST[ $this->item_shortname . '_license_key-nonce' ], $this->item_shortname . '_license_key-nonce' ) ) {

				wp_die( esc_html__( 'Nonce verification failed.', 'better-click-to-tweet' ), esc_html__( 'Error', 'better-click-to-tweet' ), array( 'response' => 403 ) );

			}

			if ( ! current_user_can( 'manage_bctt_settings' ) ) {
				return;
			}

			// Allow third party addon developers to handle license deactivation.
			if ( $this->__is_third_party_addon() ) {
				do_action( 'bctt_deactivate_license', $this );

				return;
			}

			// Run on deactivate button press
			if ( isset( $_POST[ $this->item_shortname . '_license_key_deactivate' ] ) ) {

				// Data to send to the API
				$api_params = array(
					'edd_action' => 'deactivate_license', //never change from "edd_" to "bctt_"!
					'license'    => $this->license,
					'item_name'  => urlencode( $this->item_name ),
					'url'        => home_url()
				);

				// Call the API
				$response = wp_remote_post(
					$this->api_url,
					array(
						'timeout'   => 15,
						'sslverify' => false,
						'body'      => $api_params
					)
				);

				// Make sure there are no errors
				if ( is_wp_error( $response ) ) {
					return;
				}

				// Decode the license data
				$license_data = json_decode( wp_remote_retrieve_body( $response ) );

				// Remove license data.
				delete_option( $this->item_shortname . '_license_active' );

				// Remove license key from subscriptions if exist.
				$this->__remove_license_key_from_subscriptions();
			}
		}


		/**
		 * Admin notices for errors
		 *
		 * @access public
		 * @since  6.0
		 *
		 * @return void
		 */
		public function notices() {
			static $showed_invalid_message;
			static $showed_subscriptions_message;
			static $addon_license_key_in_subscriptions;

			// Set default value.
			$addon_license_key_in_subscriptions = ! empty( $addon_license_key_in_subscriptions ) ? $addon_license_key_in_subscriptions : array();

			if ( empty( $this->license ) ) {
				return;
			}

			if ( ! current_user_can( 'manage_shop_settings' ) ) {
				return;
			}

			// Do not show licenses notices on license tab.
			if ( ! empty( $_GET['tab'] ) && 'licenses' === $_GET['tab'] ) {
				return;
			}

			$messages = array();

			// Get subscriptions.
			$subscriptions = get_option( 'bctt_subscriptions' );

			// Show subscription messages.
			if ( ! empty( $subscriptions ) && ! $showed_subscriptions_message ) {

				foreach ( $subscriptions as $subscription ) {
					// Subscription expires timestamp.
					$subscription_expires = strtotime( $subscription['expires'] );

					// Start showing subscriptions message before one week of renewal date.
					if ( strtotime( '- 7 days', $subscription_expires ) > current_time( 'timestamp', 1 ) ) {
						continue;
					}

					// Check if subscription message already exist in messages.
					if ( array_key_exists( $subscription['id'], $messages ) ) {
						continue;
					}

					if ( ( ! $this->__is_notice_dismissed( $subscription['id'] ) && 'active' !== $subscription['status'] ) ) {

						if ( strtotime( $subscription['expires'] ) < current_time( 'timestamp', 1 ) ) {// Check if license already expired.
							$messages[ $subscription['id'] ] = sprintf(
								__( 'Your Better Click To Tweet addon license expired for payment <a href="%s" target="_blank">#%d</a>. <a href="%s" target="_blank">Click to renew an existing license</a> or <a href="%s">Click here if already renewed</a>.', 'better-click-to-tweet' ),
								urldecode( $subscription['invoice_url'] ),
								$subscription['payment_id'],
								"{$this->checkout_url}?edd_license_key={$subscription['license_key']}&utm_campaign=admin&utm_source=licenses&utm_medium=expired",
								esc_url( add_query_arg( '_bctt_hide_license_notices_permanently', $subscription['id'], $_SERVER['REQUEST_URI'] ) )
							);
						} else {
							$messages[ $subscription['id'] ] = sprintf(
								__( 'Your Better Click To Tweet addon license will expire in %s for payment <a href="%s" target="_blank">#%d</a>. <a href="%s" target="_blank">Click to renew an existing license</a> or <a href="%s">Click here if already renewed</a>.', 'better-click-to-tweet' ),
								human_time_diff( current_time( 'timestamp', 1 ), strtotime( $subscription['expires'] ) ),
								urldecode( $subscription['invoice_url'] ),
								$subscription['payment_id'],
								"{$this->checkout_url}?edd_license_key={$subscription['license_key']}&utm_campaign=admin&utm_source=licenses&utm_medium=expired",
								esc_url( add_query_arg( '_bctt_hide_license_notices_permanently', $subscription['id'], $_SERVER['REQUEST_URI'] ) )
							);
						}
					}

					// Stop validation for these licencse keys.
					$addon_license_key_in_subscriptions = array_merge( $addon_license_key_in_subscriptions, $subscription['licenses'] );
				}
				$showed_subscriptions_message = true;
			}

			// Show non subscription addon messages.
			if ( ! in_array( $this->license, $addon_license_key_in_subscriptions ) && ! $this->__is_notice_dismissed( 'general' ) && ! $this->is_valid_license() && empty( $showed_invalid_message ) ) {

				$messages['general']    = sprintf(
					__( 'You have invalid or expired license keys for Better Click To Tweet Addon. Please go to the <a href="%s">licenses page</a> to correct this issue.', 'better-click-to-tweet' ),
					admin_url( '' )
				);
				$showed_invalid_message = true;

			}

			// Print messages.
			if ( ! empty( $messages ) ) {
				foreach ( $messages as $notice_id => $message ) {
					echo '<div class="notice notice-error is-dismissible" data-dismiss-notice-shortly="' . esc_url( add_query_arg( '_bctt_hide_license_notices_shortly', $notice_id, $_SERVER['REQUEST_URI'] ) ) . '">';
					echo '<p>' . $message . '</p>';
					echo '</div>';
				}
			}
		}

	}

endif; // end class_exists check
