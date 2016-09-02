<?php
/*
Plugin Name: Better Click To Tweet
Description: Add Click to Tweet boxes simply and elegantly to your posts or pages. All the features of a premium plugin, for FREE!
Version: 4.8
Author: Ben Meredith
Author URI: https://www.wpsteward.com
Plugin URI: https://wordpress.org/plugins/better-click-to-tweet/
License: GPL2
Text Domain: better-click-to-tweet 
*/
include 'i18n-module.php';
include 'bctt_options.php';
include 'bctt-i18n.php';


defined( 'ABSPATH' ) or die( "No soup for you. You leave now." );

/*
*  	Strips the html, shortens the text (after checking for mb_internal_encoding compatibility) 
*	and adds an ellipsis if the text has been shortened
* 
* 	@param string $input raw text string from the shortcode
* 	@param int $length length for truncation
* 	@param bool $ellipsis boolean for whether the text has been truncated
* 	@param bool $strip_html ensures that html is stripped from text string
*/

function bctt_shorten( $input, $length, $ellipsis = true, $strip_html = true ) {

	if ( $strip_html ) {
		$input = strip_tags( $input );
	}

	/*
	* 	Checks to see if the mbstring php extension is loaded, for optimal truncation.
	*	If it's not, it bails and counts the characters based on utf-8.
	*	What this means for users is that non-Roman characters will only be counted
	*	correctly if that extension is loaded. Contact your server admin to enable the extension.
	*/

	if ( function_exists( 'mb_internal_encoding' ) ) {
		if ( mb_strlen( $input ) <= $length ) {
			return $input;
		}

		$last_space   = mb_strrpos( mb_substr( $input, 0, $length ), ' ' );
		$trimmed_text = mb_substr( $input, 0, $last_space );

		if ( $ellipsis ) {
			$trimmed_text .= "…";
		}

		return $trimmed_text;

	} else {

		if ( strlen( $input ) <= $length ) {
			return $input;
		}

		$last_space   = strrpos( substr( $input, 0, $length ), ' ' );
		$trimmed_text = substr( $input, 0, $last_space );

		if ( $ellipsis ) {
			$trimmed_text .= "…";
		}

		return $trimmed_text;
	}
}

;

/*
* 	Creates the bctt shortcode
*
* 	@since 0.1
* 	@param array $atts an array of shortcode attributes
*	
*/

function bctt_shortcode( $atts ) {

	extract( shortcode_atts( array(
		'tweet'    => '',
		'via'      => 'yes',
		'username' => 'not-a-real-user',
		'url'      => 'yes',
		'nofollow' => 'no',
	), $atts ) );
	
	//since 4.7: adds option to add in a per-box username to the tweet
	if ( $username != 'not-a-real-user' ) {
	
		$handle = $username;
	
	} else {
		
		$handle = get_option( 'bctt-twitter-handle' );
	
	}

	if ( function_exists( 'mb_internal_encoding' ) ) {

		$handle_length = ( 6 + mb_strlen( $handle ) );

	} else {

		$handle_length = ( 6 + strlen( $handle ) );

	}

	if ( ! empty( $handle ) && $via != 'no' ) {

		$handle_code = "&amp;via=" . $handle . "&amp;related=" . $handle;

	} else {

		$handle_code = '';

	}
	
	if ( $via != 'yes' ) {

		$handle        = '';
		$handle_code   = '';
		$handle_length = 0;

	}

	$text = $tweet;

	if ( filter_var( $url, FILTER_VALIDATE_URL ) ) {

		$bcttURL = '&amp;url=' . $url;

	} elseif ( $url != 'no' ) {

		if ( get_option( 'bctt-short-url' ) != false ) {

			$bcttURL = '&amp;url=' . wp_get_shortlink();

		} else {

			$bcttURL = '&amp;url=' . get_permalink();

		}

	} else {

		$bcttURL = '';

	}

	$bcttBttn = sprintf( _x( 'Click To Tweet', 'Text for the box on the reader-facing box', 'better-click-to-tweet' ) );

	if ( $url != 'no' ) {

		$short = bctt_shorten( $text, ( 117 - ( $handle_length ) ) );

	} else {

		$short = bctt_shorten( $text, ( 140 - ( $handle_length ) ) );

	}

	if ( $nofollow != 'no' ) {

		$rel = "rel='nofollow'";

	} else {

		$rel = '';

	}

	$bctt_span_class =  apply_filters( 'bctt_span_class', 'bctt-click-to-tweet' );
	$bctt_text_span_class = apply_filters( 'bctt_text_span_class', 'bctt-ctt-text');
	$bctt_button_span_class = apply_filters('bctt_button_span_class', 'bctt-ctt-btn');

	if ( ! is_feed() ) {

		return "<span class='" . $bctt_span_class . "'><span class='" . $bctt_text_span_class . "'><a href='https://twitter.com/intent/tweet?text=" . rawurlencode( html_entity_decode( $short ) ) . $handle_code . $bcttURL . "' target='_blank'" . $rel . ">" . $short . " </a></span><a href='https://twitter.com/intent/tweet?text=" . rawurlencode( html_entity_decode( $short ) ) . $handle_code . $bcttURL . "' target='_blank' class='" . $bctt_button_span_class ."'" . $rel . ">" . $bcttBttn . "</a></span>";
	} else {

		return "<hr /><p><em>" . $short . "</em><br /><a href='https://twitter.com/intent/tweet?text=" . rawurlencode( html_entity_decode( $short ) ) . $handle_code . $bcttURL . "' target='_blank' class='bctt-ctt-btn'" . $rel . ">" . $bcttBttn . "</a><br /><hr />";

	};
}

add_shortcode( 'bctt', 'bctt_shortcode' );

/*
 * Load the stylesheet to style the output.
 *
 * As of v4.1, defaults to a custom stylesheet
 * located in the root of the uploads folder at wp-content/uploads/bcttstyle.css and falls
 * back to the stylesheet bundled with the plugin if the custom sheet is not present.
 *
 * @since 0.1
 *
*/

function bctt_scripts() {

	$dir = wp_upload_dir();

	$custom = file_exists( $dir['basedir'] . '/bcttstyle.css' );

	if ( $custom != 'true' ) {

		wp_register_style( 'bcct_style', plugins_url( 'assets/css/styles.css', __FILE__ ), false, '3.0', 'all' );

		wp_enqueue_style( 'bcct_style' );

	} else {

		wp_register_style( 'bcct_custom_style', $dir['baseurl'] . '/bcttstyle.css', false, '1.0', 'all' );

		wp_enqueue_style( 'bcct_custom_style' );
	}


}

;

add_action( 'wp_enqueue_scripts', 'bctt_scripts' );

/*
 * Delete options and shortcode on uninstall
 *
 * @since 0.1
*/

function bctt_on_uninstall() {

	delete_option( 'bctt-twitter-handle' );

	delete_option( 'bctt-short-url' );

	remove_shortcode( 'bctt' );

}

;

register_uninstall_hook( __FILE__, 'bctt_on_uninstall' );

function bctt_options_link( $links ) {

	$settingsText = sprintf( _x( 'Settings', 'text for the link on the plugins page', 'better-click-to-tweet' ) );

	$settings_link = '<a href="options-general.php?page=better-click-to-tweet">' . $settingsText . '</a>';

	array_unshift( $links, $settings_link );

	return $links;

}

$bcttlink = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$bcttlink", 'bctt_options_link' );
