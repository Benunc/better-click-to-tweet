<?php

defined( 'ABSPATH' ) or die( "No script kiddies please!" );

// Prominent link to rebrand blog post (replaces the former rebrand callout box).
add_action( 'bctt_settings_top', 'bctt_settings_rebrand_link' );

function bctt_settings_rebrand_link() {
	$url = 'https://benlikes.us/insider-bcts-rebrand';
	?>
	<p class="bctt-rebrand-link">
		<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'What happened to Better Click To Tweet?', 'better-click-to-tweet' ); ?></a>
	</p>
	<?php
}
