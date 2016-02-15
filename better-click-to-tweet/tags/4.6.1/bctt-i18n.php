<?php

add_action( 'plugins_loaded', 'bctt_load_textdomain' );

function bctt_load_textdomain() {
	load_plugin_textdomain( 'better-click-to-tweet', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
