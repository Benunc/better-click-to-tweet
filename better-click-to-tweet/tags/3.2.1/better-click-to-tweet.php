<?php
/*
Plugin Name: Better Click To Tweet
Description: The only Click To Tweet plugin to add translation support. The only Click To Tweet plugin to take into account your Twitter username's length in truncating long tweets, or to correctly take into account non-Roman characters. Simply put, as Click To Tweet plugins go, this one is, well, BETTER. 
Version: 3.2.1
Author: Ben Meredith
Author URI: http://benandjacq.com
Plugin URI: https://wordpress.org/plugins/better-click-to-tweet/
License: GPL2
Text Domain: better-click-to-tweet 
*/
include 'bctt_options.php';
include 'bctt-i18n.php';

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
		
function bctt_shortcode( $atts ) {
 			extract( shortcode_atts( array(
					'tweet' 	=> '',
					'via'		=> 'yes',
					'url'		=> 'yes',
   				 ), $atts ) );
		    	$handle = get_option( 'bctt-twitter-handle' );
		    	$handle_length = ( 6 + mb_strlen($handle));
		    
		    if ( !empty( $handle ) && $via != 'no' ) {
		        $handle_code = "&via=" . $handle . "&related=" . $handle;
		    } else {
		    	$handle_code = '';
		    }
		    
		    if( $via != 'yes') { 
		    		$handle = '';
		    		$handle_code = '';
		    		$handle_length = 0;
		    		}
		    	
		    $text = $tweet;
                    if( $url != 'no' ){
                    	if ( get_option('bctt-short-url') != false ) { 
                        	$bcttURL = '&url=' . wp_get_shortlink();
                        	}
                    	else { 
                        	$bcttURL = '&url=' . get_permalink(); 
                        	}
                    } else {
                    	$bcttURL = '';
                    }
                    $bcttBttn = sprintf( __( 'Click To Tweet', 'better-click-to-tweet' ) );
		    if( $url != 'no'){
		    	$short = bctt_shorten( $text, ( 117 - ( $handle_length ) ) );
		    	}
		    	else {
		    	$short = bctt_shorten( $text, ( 140 - ( $handle_length ) ) );
		    	}
                    if ( !is_feed() ) {
                        return "<div class='bctt-click-to-tweet'><span class='bctt-ctt-text'><a href='https://twitter.com/intent/tweet?text=" . urlencode($short) . $handle_code . $bcttURL."' target='_blank'>".$short."</a></span><a href='https://twitter.com/intent/tweet?text=" . urlencode($short) . $handle_code . "&url=" . $bcttURL . "' target='_blank' class='bctt-ctt-btn'>" . $bcttBttn . "</a></div>";} else {
                        return "<hr /><p><em>" . $short . "</em><br /><a href='https://twitter.com/intent/tweet?text=" . urlencode($short) . $handle_code . $bcttURL . "' target='_blank' class='bctt-ctt-btn'>" . $bcttBttn . "</a><br /><hr />";
	        	};
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
	 * Delete options and shortcode on uninstall
	*/
	
function bctt_on_uninstall(){

	delete_option( 'bctt-twitter-handle' );
        delete_option( 'bctt-short-url');
        remove_shortcode( 'bctt' );

};

register_uninstall_hook(    __FILE__, 'bctt_on_uninstall' );

function bctt_options_link($links) {
  $settingsText = sprintf( __( 'Settings', 'better-click-to-tweet')); 
  $settings_link = '<a href="options-general.php?page=better-click-to-tweet">'.$settingsText.'</a>'; 
  array_unshift( $links, $settings_link ); 
  return $links; 
}
$bcttlink = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$bcttlink", 'bctt_options_link' );

