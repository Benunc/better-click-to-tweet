<?php
/**
 * Better Click to Tweet Shortcode
 *
 * @package     Better_Click_To_Tweet
 * @since       5.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Better_Click_To_Tweet_Shortcode Class.
 *
 * @since 5.1
 */
class Better_Click_To_Tweet_Shortcodes {


	/**
	 * Better_Click_To_Tweet_Shortcode constructor.
	 */
	public function __construct() {

		add_shortcode( 'bctt', array( $this, 'bctt_shortcode' ) );

	}


	/**
	 * Creates the bctt shortcode.
	 *
	 * @param array $atts an array of shortcode attributes
	 *
	 * @return string
	 */
	public function bctt_shortcode( $atts ) {

		$atts = shortcode_atts( array(
			'tweet'    => '',
			'via'      => 'yes',
			'username' => 'not-a-real-user',
			'url'      => 'yes',
			'nofollow' => 'no',
			'prompt'   => sprintf( _x( 'Click To Tweet', 'Text for the box on the reader-facing box', 'better-click-to-tweet' ) )
		), $atts, 'bctt' );

		//since 4.7: adds option to add in a per-box username to the tweet
		if ( $atts['username'] != 'not-a-real-user' ) {

			$handle = $atts['username'];

		} else {

			$handle = get_option( 'bctt-twitter-handle' );

		}

		if ( function_exists( 'mb_internal_encoding' ) ) {

			$handle_length = ( 6 + mb_strlen( $handle ) );

		} else {

			$handle_length = ( 6 + strlen( $handle ) );

		}

		if ( ! empty( $handle ) && $atts['via'] != 'no' ) {

			$handle_code = "&amp;via=" . $handle . "&amp;related=" . $handle;

		} else {

			$handle_code = '';

		}

		if ( $atts['via'] != 'yes' ) {

			$handle_code   = '';
			$handle_length = 0;

		}

		$text = $atts['tweet'];

		if ( filter_var( $atts['url'], FILTER_VALIDATE_URL ) ) {

			$bcttURL = '&amp;url=' . $atts['url'];

		} elseif ( $atts['url'] != 'no' ) {

			if ( get_option( 'bctt-short-url' ) != false ) {

				$bcttURL = '&amp;url=' . wp_get_shortlink();

			} else {

				$bcttURL = '&amp;url=' . get_permalink();

			}

		} else {

			$bcttURL = '';

		}

		if ( $atts['url'] != 'no' ) {

			$short = $this->shorten_tweet_length( $text, ( 117 - ( $handle_length ) ) );

		} else {

			$short = $this->shorten_tweet_length( $text, ( 140 - ( $handle_length ) ) );

		}

		if ( $atts['nofollow'] != 'no' ) {

			$rel = "rel='nofollow'";

		} else {

			$rel = '';

		}

		$bctt_span_class        = apply_filters( 'bctt_span_class', 'bctt-click-to-tweet' );
		$bctt_text_span_class   = apply_filters( 'bctt_text_span_class', 'bctt-ctt-text' );
		$bctt_button_span_class = apply_filters( 'bctt_button_span_class', 'bctt-ctt-btn' );

		if ( ! is_feed() ) {

			return "<span class='" . $bctt_span_class . "'><span class='" . $bctt_text_span_class . "'><a href='https://twitter.com/intent/tweet?text=" . rawurlencode( html_entity_decode( $short ) ) . $handle_code . $bcttURL . "' target='_blank'" . $rel . ">" . $short . " </a></span><a href='https://twitter.com/intent/tweet?text=" . rawurlencode( html_entity_decode( $short ) ) . $handle_code . $bcttURL . "' target='_blank' class='" . $bctt_button_span_class . "'" . $rel . ">" . $atts['prompt'] . "</a></span>";
		} else {

			return "<hr /><p><em>" . $short . "</em><br /><a href='https://twitter.com/intent/tweet?text=" . rawurlencode( html_entity_decode( $short ) ) . $handle_code . $bcttURL . "' target='_blank' class='bctt-ctt-btn'" . $rel . ">" . $atts['prompt'] . "</a><br /><hr />";

		}

	}


	/**
	 * Shorten Tween Length
	 *
	 * Strips the html, shortens the text (after checking for mb_internal_encoding compatibility)
	 * and adds an ellipsis if the text has been shortened.
	 *
	 * @param string $input      raw text string from the shortcode
	 * @param int    $length     length for truncation
	 * @param bool   $ellipsis   boolean for whether the text has been truncated
	 * @param bool   $strip_html ensures that html is stripped from text string
	 *
	 * @return string
	 */
	function shorten_tweet_length( $input, $length, $ellipsis = true, $strip_html = true ) {

		if ( $strip_html ) {
			$input = strip_tags( $input );
		}

		/**
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


}

