<?php
/**
 *    This file dynamically creates MCE locales, based on .po/.mo files loaded in the plugin's translation folder.
 *    It interfaces with the TinyMCE API using the tinyMCE.addI18n() function,
 *    which adds a language pack to TinyMCE
 *
 * @var string $strings a JavaScript snippet to add another language pack to TinyMCE
 * @var string $mce_locale an ISO 639-1 formated string of the current language e.g. en, de...
 * @deprecated wp_tiny_mce() at wp-admin/includes/post.php (for versions prior WP 3.3)
 * @see _WP_Editors::editor_settings in wp-includes/class-wp-editor.php
 */
$strings =
	'tinyMCE.addI18n( 
		"' . $mce_locale . '.bctt",
			{
			toolTip : "' . esc_js( _x( 'Better Click To Tweet Shortcode Generator', 'Text that shows on mouseover for visual editor button', 'better-click-to-tweet' ) ) . '",
			windowTitle : "' . esc_js( _x( 'Better Click To Tweet Shortcode Generator', 'Text for title of the popup box when creating tweetable quote in the visual editor', 'better-click-to-tweet' ) ) . '",
			tweetableQuote : "' . esc_js( _x( 'Tweetable Quote', 'Text for label on input box on popup box in visual editor', 'better-click-to-tweet' ) ) . '",
			viaExplainer : "' . esc_js( _x( 'Add the username below to this tweet', 'Text explaining the checkbox on the visual editor', 'better-click-to-tweet' ) ) . '",
			viaPrompt : "' . esc_js( _x( 'Include via?', 'Checkbox label in visual editor', 'better-click-to-tweet' ) ) . '",
			usernameExplainer : "' . esc_js( _x( 'Which Twitter username?', 'Help text for label in visual editor', 'better-click-to-tweet' ) ) . '",
			userPrePopulated : "' . esc_js( get_option( 'bctt-twitter-handle' ) ) . '",
			} 
  		);
  	';