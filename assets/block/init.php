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

// Enqueue Block assets
function bctt_block_editor_assets() {
	$asset_file = include( plugin_dir_path( __FILE__ ) . 'build/index.asset.php');
	wp_enqueue_script( 
		'bctt-block-js', 
		plugins_url( 'block/build/index.js', dirname( __FILE__ ) ), 
		$asset_file['dependencies'],
		$asset_file['version']
	);
	if ( ! bctt_is_default_styles_dequeued() ) {
		$stylesheet_url = bctt_get_stylesheet_url();
		wp_enqueue_style( 'bctt-block-editor-css', $stylesheet_url, array(), 'all' );
	}
	// Add plugin options for block
	$bctt_data = array(
		'username' => get_option( 'bctt-twitter-handle' ),
	);
	wp_localize_script( 'bctt-block-js', 'bctt_options_js', $bctt_data );
}

// Hook assets to editor
add_action( 'enqueue_block_editor_assets', 'bctt_block_editor_assets' );

// Server side rendering callback to output shortcode
register_block_type( 'bctt/clicktotweet', array(
		'render_callback' => 'bctt_block_callback',
		'attributes'      => apply_filters ( 'bctt_block_attributes' ,array(
			'tweet'     => array(
				'type' => 'string',
				'default' => !empty( get_the_ID() ) ? get_the_title( get_the_ID() ) : ''
			),
			'username'  => array(
				'type'    => 'string',
				'default' => get_option( 'bctt-twitter-handle' )
			),
			'via'       => array(
				'type'    => 'boolean',
				'default' => true
			),
			'url'       => array(
				'type'    => 'boolean',
				'default' => true
			),
			'urlcustom' => array(
				'type' => 'string',
				'default' => ''
			),
			'nofollow'  => array(
				'type'    => 'boolean',
				'default' => false
			),
			'prompt'    => array(
				'type'    => 'string',
				'default' => sprintf( _x( 'Click To Tweet', 'Text for the box on the reader-facing box', 'better-click-to-tweet' ) )
			),
		)),
	)
);

// Callback function to render bctt on frontend
function bctt_block_callback( $attributes ) {
	$tweet     = $attributes['tweet'];
	$via       = $attributes['via'];
	$username  = $attributes['username'];
	$url       = $attributes['url'];
	$urlcustom = $attributes['urlcustom'];
	$nofollow  = $attributes['nofollow'];
	$prompt    = $attributes['prompt'];
	$showUrl   = ( $url ? 'yes' : 'no' );

	$shortcode_attributes =  apply_filters ( 'bctt_block_render_attributes', array(
		'tweet'    => $tweet,
		'via'      =>  $via ? 'yes' : 'no',
		'username' => $username,
		'url'      => $urlcustom ? $urlcustom : $showUrl,
		'nofollow' => $nofollow ? 'yes' : 'no',
		'prompt'   => $prompt
	), $attributes );

	return bctt_shortcode( $shortcode_attributes );
}