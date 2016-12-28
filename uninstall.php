<?php
/**
 * Uninstall Better Click to Tweet :(
 *
 * Deletes options when the user deletes the plugin.
 *
 * @package     Better_Click_To_Tweet
 * @subpackage  Uninstall
 * @copyright   Copyright (c) 2016, WordImpress
 * @license     https://opensource.org/licenses/gpl-license GNU Public License
 * @since       5.x
 */

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'bctt-twitter-handle' );

delete_option( 'bctt-short-url' );

delete_option( 'bctt_disable_css' );

delete_option( 'bctt_style_enqueued' );

remove_shortcode( 'bctt' );