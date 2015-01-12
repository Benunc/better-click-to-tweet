<?php
/*
Plugin Name: Better Click To Tweet
Description: Add click to tweet boxes to your WordPress posts, easily. This is a new, fully renovated version of the late "Click to Tweet" plugin by Todaymade. I overhauled the plugin using the shortcode API, and (perhaps most importantly) removed the "powered by" link.
Version: 1.0
Author: Ben Meredith
Author URI: http://benandjacq.com
Plugin URI: https://wordpress.org/plugins/better-click-to-tweet/
License: GPL2
*/
include 'bctt_options.php';

defined( 'ABSPATH' ) or die( "No script kiddies please!" );

function bctt_shorten( $input, $length, $ellipsis = true, $strip_html = true ) {
		    if ( $strip_html ) {
		        $input = strip_tags( $input );
		    }
		    if ( mb_strlen( $input ) <= $length ) {
		        return $input;
		    }
		    $last_space = mb_strrpos( mb_substr( $input, 0, $length) , ' ');
		    $trimmed_text = mb_substr( $input, 0, $last_space );
		    if ( $ellipsis ) {
		        $trimmed_text .= '…';
		    }
		    return $trimmed_text;
		};
		
function bctt_shortcode( $atts, $content ) {
 			$handle = get_option( 'bctt-twitter-handle' );
		    if ( !empty( $handle ) ) {
		        $handle_code = "&via=".$handle."&related=".$handle;
		    } else {
		    	$handle_code = $handle;
		    }
 			extract( shortcode_atts( array(
					'tweet' 	=> '$content',
					'handle'	=> '$handle_code'	
   				 ), $atts ) );
		    $text = $tweet;
		    $short = bctt_shorten( $text, ( 117 - strlen( $handle ) ) );
                    return "<div class='bctt-click-to-tweet'><span class='bctt-ctt-text'><a href='https://twitter.com/intent/tweet?text=".urlencode($short).$handle_code."&url=".get_permalink()."' target='_blank'>".$short."</a></span><a href='https://twitter.com/intent/tweet?text=".urlencode($short).$handle_code."&url=".get_permalink()."' target='_blank' class='bctt-ctt-btn'>Click To Tweet</a></div>";
		}

add_shortcode('bctt', 'bctt_shortcode');
	
	/* 
	 * Load the stylesheet to style the output
	*/
	
function bctt_scripts () {
	wp_register_style( 'bcct_style', plugins_url( 'assets/css/styles.css', __FILE__ ), false, '1.0', 'all' );
	wp_enqueue_style('bcct_style');
	};
	
add_action('wp_enqueue_scripts', 'bctt_scripts');	
	
	/*
	 * Delete options on uninstall
	*/
	
function bctt_on_uninstall(){

	delete_option( 'bctt-twitter-handle' );

};

register_uninstall_hook(    __FILE__, 'bctt_on_uninstall' );

function bctt_options_link($links) { 
  $settings_link = '<a href="options-general.php?page=better-click-to-tweet">Settings</a>'; 
  array_unshift( $links, $settings_link ); 
  return $links; 
}
$bcttlink = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$bcttlink", 'bctt_options_link' );
