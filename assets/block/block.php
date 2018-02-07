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

// Enque Block assets
function bctt_block_editor_assets() {
    wp_enqueue_script('bctt-block-js', plugins_url('block/block.build.js', dirname(__FILE__)), array( 'wp-i18n', 'wp-blocks', 'wp-components' ));
    wp_enqueue_style('bctt-block-editor-css', plugins_url('css/styles.css', dirname(__FILE__)), array( 'wp-edit-blocks' ));

    // Add plugin options for block
    $bctt_data = array(
        'username'            => get_option( 'bctt-twitter-handle' ),
    );
    wp_localize_script( 'bctt-block-js', 'bctt_options_js', $bctt_data );
}

// Hook assets to editor
add_action('enqueue_block_editor_assets', 'bctt_block_editor_assets');

// Server side rendering callback to output shortcode
register_block_type('bctt/clicktotweet', [
    'render_callback' => 'bctt_block_callback',
    'attributes' => array(
        'tweet' => array(
            'type' => 'string',
        ),
         'username' => array(
            'type' => 'string',
            'default' => 'not-a-real-user'
        ),
        'via' => array(
            'type' => 'boolean',
            'default' => true
        ),
        'url' => array(
            'type' => boolean,
            'default' => true
        ),
        'urlcustom' => array(
            'type' => 'string'
        ),
        'nofollow' => array(
            'type' => 'boolean',
            'default' => false
        ),      
        'prompt' => array(
            'type' => 'string',
            'default' => 'Click To Tweet'
        ),  
    ),
]);

// Callback function to render bctt on frontend
function bctt_block_callback( $attributes ) {
    extract( $attributes );

   $url = ($url ? 'yes' : 'no');
       
    $shortcode_string = '[bctt tweet="%s" url="%s" via="%s" username="%s" nofollow="%s" prompt="%s"]';
    
    return sprintf( $shortcode_string, $tweet, ($urlcustom ? $urlcustom : $url), ( $via ? 'yes' : 'no' ), $username, ( $nofollow ? 'yes' : 'no' ), $prompt);
}

