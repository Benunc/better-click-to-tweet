<?php

defined( 'ABSPATH' ) or die( "No script kiddies please!" );

// Hook the callout box function to the bctt_settings_top action
add_action('bctt_settings_top', 'bctt_settings_clarifier');

function bctt_settings_clarifier() {
	?>
	<div class="bctt-card bctt-card-clarifier">
		<h2 class="bctt-card-title"><?php esc_html_e( 'Better Click To Share', 'better-click-to-tweet' ); ?></h2>
		<div class="bctt-card-content">
			<p><?php esc_html_e( "We've rebranded to Better Click To Share to reflect that readers share on X (formerly Twitter). The shortcode, block, and all features work the same.", 'better-click-to-tweet' ); ?></p>
			<p><?php esc_html_e( "I'm committed to keeping this plugin functional as long as the X platform allows posting via web intents.", 'better-click-to-tweet' ); ?></p>
			<p><?php echo sprintf( esc_html__( 'If you have any questions whatsoever, don\'t be a stranger. Reach out over at %s and I\'m happy to clarify!', 'better-click-to-tweet' ), '<a href="https://www.betterclicktoshare.com">' . esc_html__( 'My website', 'better-click-to-tweet' ) . '</a>' ); ?></p>
			<p><?php esc_html_e( 'Happy posting to X!', 'better-click-to-tweet' ); ?></p>
		</div>
	</div>
	<?php
}
?>
