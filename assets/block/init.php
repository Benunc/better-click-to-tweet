<?php
/**
 * Blocks Registration
 *
 * Enqueue CSS/JS for the blocks & register callback to render shortcode on frontend
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Register block
add_action('init', 'register_block_click_to_tweet');

function register_block_click_to_tweet(){
    $block_assets = [];
    $block_js   = 'block/build/script.js';
 
    // Script
    wp_register_script(
        'bctt-block-js',
        plugins_url($block_js, dirname(__FILE__)),
        array(
            'wp-blocks',
            'wp-i18n',
        ),
        filemtime(plugin_dir_path(__FILE__))
    );
    // Editor styles
    if ( ! bctt_is_default_styles_dequeued() ) {
	    $stylesheet_url = bctt_get_stylesheet_url();

	    wp_register_style( 
            'bctt-block-editor-css', 
            $stylesheet_url, 
            array(), 
            'all' 
        );

        $block_assets['editor_style'] = 'bctt-block-editor-css';
    }

     // Add plugin options for block
     $bctt_data = array(
        'username' => get_option( 'bctt-twitter-handle' ),
    );
    wp_localize_script( 'bctt-block-js', 'bctt_options_js', $bctt_data );

    // Register block type
    $block_assets['script'] = 'bctt-block-js';
    register_block_type('bctt/clicktotweet', $block_assets);
}
