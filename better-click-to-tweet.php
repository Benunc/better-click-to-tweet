<?php
/**
 * Plugin Name: Better Click To Share (Formerly Better Click To Tweet)
 * Description: Add styled boxes to posts and pages so that readers can share your content on X. Increase engagement by asking for it. All the features of a premium plugin, for FREE!
 * Version: 6.0.0
 * Author: Ben Meredith
 * Author URI: https://www.betterclicktoshare.com
 * Plugin URI: https://wordpress.org/plugins/better-click-to-tweet/
 * License: GPL2
 * Text Domain: better-click-to-tweet
**/

defined( 'ABSPATH' ) or die( "No soup for you. You leave now." );

define( 'BCTT_VERSION', '6.0.0' );
define( 'BCTT_PLUGIN_FILE', __FILE__ );

// Load updater at plugin load so BCTT_License exists before add-ons' plugins_loaded callbacks.
// Add-ons (e.g. Premium Styles, UTM Tags) instantiate BCTT_License on plugins_loaded, which
// runs before init, so the class must be defined when this file is loaded.
require_once __DIR__ . '/includes/updater/bctt-updater.php';

// Include files that don't use translation functions early
include 'bctt-i18n.php';

// Initialize the plugin after translations are loaded
add_action( 'init', 'bctt_init', 0 );

function bctt_init() {
    // Include all plugin files after translations are loaded
    include 'i18n-module.php';
    include 'bctt-admin.php';
    include 'bctt_options.php';
    include 'admin-nags.php';

    // @since 5.7.0 (bctt-updater.php loaded in main file so add-ons can use BCTT_License on plugins_loaded)
    include 'includes/updater/license-page.php';
    include 'includes/misc-functions.php';
    include 'bctt-welcome-functions.php';

    // Register shortcode after translations are loaded
    add_shortcode( 'bctt', 'bctt_shortcode' );

    // Register block after translations are loaded
    if ( function_exists( 'register_block_type' ) ) {
        require_once( plugin_dir_path( __FILE__ ) . 'assets/block/init.php' );
    }

    // WordPress 6.9+ Abilities API: register bctt/insert-click-to-tweet and bctt/suggest-tweetables.
    // Only load when the API exists so we do not break sites on older WordPress versions.
    if ( function_exists( 'wp_register_ability' ) ) {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-bctt-abilities.php';
    }
}

/**
 * Checks whether any WordPress Connectors LLM (e.g. Gemini, OpenAI, Claude) is connected.
 *
 * Used to decide whether to use AI for suggest-tweetables and to show the "Connect a model" prompt.
 *
 * @since 6.0.0
 * @return bool True if at least one connector provider is configured.
 */
function bctt_has_llm_connector() {
	if ( ! class_exists( '\WordPress\AiClient\AiClient' ) ) {
		return false;
	}
	try {
		$registry = \WordPress\AiClient\AiClient::defaultRegistry();
		$provider_ids = array( 'google', 'openai', 'anthropic' );
		foreach ( $provider_ids as $id ) {
			if ( $registry->hasProvider( $id ) && $registry->isProviderConfigured( $id ) ) {
				return true;
			}
		}
	} catch ( Exception $e ) {
		return false;
	}
	return false;
}

/**
 * REST API: Connector usage agreement (for "Connect to AI" CTA when no connector is connected).
 *
 * @since 6.0.0
 */
function bctt_rest_register_connector_agreement() {
	register_rest_route(
		'bctt/v1',
		'/connector-agreement',
		array(
			array(
				'methods'             => 'GET',
				'callback'            => function () {
					return array(
						'agreed' => (bool) get_option( 'bctt_connector_usage_agreed', false ),
					);
				},
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			),
			array(
				'methods'             => 'POST',
				'callback'            => function ( $request ) {
					$agreed = (bool) $request->get_param( 'agreed' );
					update_option( 'bctt_connector_usage_agreed', $agreed ? '1' : '0' );
					return array(
						'agreed' => (bool) get_option( 'bctt_connector_usage_agreed', false ),
					);
				},
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
				'args'                => array(
					'agreed' => array(
						'type'    => 'boolean',
						'default' => false,
					),
				),
			),
		)
	);
}
add_action( 'rest_api_init', 'bctt_rest_register_connector_agreement' );

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
	$twitter_handle = get_option( 'bctt-twitter-handle' );

	$atts = shortcode_atts( apply_filters( 'bctt_atts', array(
		'tweet'    => !empty( get_the_ID() ) ? get_the_title( get_the_ID() ) : '',
		'via'      => 'yes',
		'username' => $twitter_handle ? $twitter_handle : 'not-a-real-user',
		'url'      => 'yes',
		'nofollow' => 'no',
		'prompt'   => sprintf( _x( 'Share on X', 'Text for the box on the reader-facing box', 'better-click-to-tweet' ) )
	) ), $atts, 'bctt' );

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

		$via = $handle;
		$related = $handle;
	} else {

		$via = null;
		$related = '';

	}

	if ( $atts['via'] != 'yes' ) {
		$via = null;
		$handle_length = 0;

	}

	$text = $atts['tweet'];

	if ( filter_var( $atts['url'], FILTER_VALIDATE_URL ) ) {

		$bcttURL = apply_filters( 'bctturl', $atts['url'], $atts );

	} elseif ( $atts['url'] != 'no' ) {

		if ( get_option( 'bctt-short-url' ) != false ) {

			$bcttURL  = apply_filters( 'bctturl', wp_get_shortlink(), $atts );

		} else {

			$bcttURL = apply_filters( 'bctturl', get_permalink(), $atts);

		}

	} else {

		$bcttURL = null;

	}

	if ( $atts['url'] != 'no' ) {

		$short = bctt_shorten( $text, ( 253 - ( $handle_length ) ) );

	} else {

		$short = bctt_shorten( $text, ( 280 - ( $handle_length ) ) );

	}

	if ( $atts['nofollow'] != 'no' ) {

		$rel = 'rel="noopener noreferrer nofollow"';

	} else {

		$rel = 'rel="noopener noreferrer"';

	}

	$bctt_span_class        = apply_filters( 'bctt_span_class', 'bctt-click-to-tweet' );
	$bctt_text_span_class   = apply_filters( 'bctt_text_span_class', 'bctt-ctt-text' );
	$bctt_button_span_class = apply_filters( 'bctt_button_span_class', 'bctt-ctt-btn' );


	$href  = add_query_arg(  array(
		'url'     => rawurlencode( $bcttURL ),
		'text'    => rawurlencode( html_entity_decode( $short ) ),
		'via'     => $via,
		'related' => $related,
	), 'https://x.com/intent/tweet' );

	if ( ! is_feed() ) {

		$output = "<span class='" . esc_attr( $bctt_span_class ) . "'><span class='" . esc_attr( $bctt_text_span_class ) . "'><a href='" . esc_url( $href ) . "' target='_blank'" . $rel . ">" . esc_html( $short ) . " </a></span><a href='" . esc_url( $href ) . "' target='_blank' class='" . esc_attr( $bctt_button_span_class ) . "'" . $rel . ">" . esc_html( $atts['prompt'] ) . "</a></span>";
	} else {

		$output = "<hr /><p><em>" . esc_html( $short ) . "</em><br /><a href='" . esc_url( $href ) . "' target='_blank' " . $rel . " >" . esc_html( $atts['prompt'] ) . "</a><br /><hr />";

	}
	return apply_filters( 'bctt_output', $output, $short, $bctt_button_span_class, $bctt_span_class, $bctt_text_span_class, $href, $rel, $atts );
}

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

	if ( bctt_is_default_styles_dequeued() ) {
		foreach ( wp_load_alloptions() as $option => $value ) {
			if ( strpos( $option, 'bcct_' ) === 0 ) {
				delete_option( $option );
			}
		}

		return;
	}

	$custom = bctt_is_custom_stylesheet();

	$tag      = $custom ? 'bcct_custom_style' : 'bcct_style';
	$location = bctt_get_stylesheet_url();

	$version = $custom ? '1.0' : '3.0';

	wp_register_style( $tag, $location, false, $version, 'all' );

	wp_enqueue_style( $tag );
}


//add_action( 'wp_enqueue_scripts', 'bctt_scripts', 10 );

/**
 * Check if default stylesheet must not be enqueued
 *
 * @return bool
 */
function bctt_is_default_styles_dequeued() {
	return (bool) get_option( 'bctt_disable_css' );
}


/**
 * Check if there's a custom stylesheet that will be enqueued
 */
function bctt_is_custom_stylesheet() {
	return file_exists( bctt_get_custom_styles_path() );
}

/**
 * Return the BCTT stylesheet URL
 *
 * Return custom styles URL if the file exists or the default one otherwise
 *
 * @return string
 */
function bctt_get_stylesheet_url() {
	return bctt_is_custom_stylesheet() ? bctt_get_custom_styles_url() : bctt_get_styles_url();
}


/**
 * Return the custom stylesheet path
 *
 * @return string
 */
function bctt_get_custom_styles_path() {
	$dir = wp_upload_dir();
	return $dir['basedir'] . '/bcttstyle.css';
}

/**
 * Return the custom stylesheet URL
 *
 * @return string
 */
function bctt_get_custom_styles_url() {
	$dir = wp_upload_dir();
	return $dir['baseurl'] . '/bcttstyle.css';
}

/**
 * Return the default stylesheet path
 *
 * @return string
 */
function bctt_get_styles_url() {
	return plugins_url( 'assets/css/styles.css', __FILE__ );
}

/**
 * Plugin Activation
 *
 * @return void
 */
function bctt_on_activation() {
	set_transient( '_bctt_activation_redirect', 1, 30 );
}
register_activation_hook( __FILE__, 'bctt_on_activation' );

/*
 * Delete options and shortcode on uninstall
 *
 * @since 0.1
*/

function bctt_on_uninstall() {

	delete_option( 'bctt-twitter-handle' );

	delete_option( 'bctt-short-url' );

	delete_option( 'bctt_disable_css' );

	delete_option( 'bctt_style_enqueued' );

	remove_shortcode( 'bctt' );

	delete_metadata( 'user', 0, 'bctt_has_dismissed_nag', '', true );


}

register_uninstall_hook( __FILE__, 'bctt_on_uninstall' );

function bctt_options_link( $links ) {

	$settingsText = sprintf( _x( 'Settings', 'text for the link on the plugins page', 'better-click-to-tweet' ) );

	$settings_link = '<a href="' . esc_url( admin_url( 'options-general.php?page=better-click-to-tweet' ) ) . '">' . esc_html( $settingsText ) . '</a>';

	array_unshift( $links, $settings_link );

	return $links;

}

$bcttlink = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$bcttlink", 'bctt_options_link' );


