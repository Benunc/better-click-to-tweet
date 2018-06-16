<?php

function bctt_license_menu() {
	add_submenu_page( 'better-click-to-tweet', 'Add-on Licenses', 'Add-on Licenses', 'manage_options', 'bctt-licenses','bctt_license_page' );

}

add_action( 'admin_menu', 'bctt_license_menu', 40 );

/*
 * The output of the submenu page
 */

function bctt_license_page() {
	$premium_styles = function_exists('bcttps_dequeue_default_style') ? array( 'Premium Styles' ) : array();
	$utm_tags = function_exists('bcttutm_add_shortcode_att') ? array( 'UTM Tags' ) : array();
	$active_addons = array_merge( $premium_styles, $utm_tags );
	var_dump($active_addons);
	?>
	<div class="wrap">
	<h2><?php _e( 'Activate Your Addons' ); ?></h2>
	<p>An active license is required for updates (including bugfixes and security updates) as well as support.</p>
	<form method="post" action="options.php">

		<?php settings_fields( 'bctt_license' ); ?>

		<table class="form-table">
			<tbody>
			<tr valign="top">
			<?php
			foreach ($active_addons as $license) {
			$shortname = 'bctt_' . preg_replace( '/[^a-zA-Z0-9_\s]/', '', str_replace( ' ', '_', strtolower( $license ) ) ) . '_license_key';
		$key = get_option( $shortname );
		$status = get_option( $shortname . '_active' );
		?>

					<th scope="row" valign="top">
						<?php echo $license; ?>
					</th>
					<td>
						<input id="<?php echo $shortname ?>" name="<?php echo $shortname ?>" type="text" class="regular-text"
						       value="<?php esc_attr_e( $key ); ?>"/>
						<label class="description"
						       for="<?php echo $shortname ?>"><?php _e( 'Enter your license key' ); ?></label>
					</td>
				</tr>
				<?php if ( false == $status ) { ?>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e( 'Activate License' ); ?>
						</th>
						<td>
							<?php if ( $status !== false && $status == 'valid' ) { ?>
								<span style="color:green;"><?php _e( 'active' ); ?></span>
								<?php wp_nonce_field( 'bctt_nonce', 'bctt_nonce' ); ?>
								<input type="submit" class="button-secondary" name="edd_license_deactivate"
								       value="<?php _e( 'Deactivate License' ); ?>"/>
							<?php } else {
								wp_nonce_field( 'bctt_nonce', 'bctt_nonce' ); ?>
								<input type="submit" class="button-secondary" name="edd_license_activate"
								       value="<?php _e( 'Activate License' ); ?>"/>
							<?php } } ?>
						</td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
			<?php submit_button(); ?>

		</form>
		<?php

}

function bctt_register_license_option() {
	// creates our settings in the options table
	register_setting( 'bctt_license', 'bctt_premium_styles_license_key', 'bctt_premium_styles_sanitize_license' );
	register_setting( 'bctt_license', 'bctt_utm_tags_license_key', 'bctt_utm_tags_sanitize_license' );
}
add_action( 'admin_init', 'bctt_register_license_option' );

function bctt_premium_styles_sanitize_license( $new ) {

	$old = get_option( 'bctt_premium_styles_license_key' );
	if ( $old && $old != $new ) {
		delete_option( 'bctt_premium_styles_license_key_active' ); // new license has been entered, so must reactivate
	}

	return $new;
}

function bctt_utm_tags_sanitize_license( $new ) {

	$old = get_option( 'bctt_utm_tags_license_key' );
	if ( $old && $old != $new ) {
		delete_option( 'bctt_utm_tags_license_key_active' ); // new license has been entered, so must reactivate
	}

	return $new;
}