<?php

/*
 *  This file adds in a license page, if any premium add-ons are installed and active.
 *  If no premium add-ons are active, this file doesn't do anything.
 *
 *  @since 5.7.0
 */


// Backward compatibility for the previous version of the Premium Styles add-on (version 1.3 and earlier)
// removes the submenu page added by those versions of the add-on.
function maybe_remove_premium_styles_license_page() {

	if ( function_exists( 'bcttps_license_menu' ) ) {

		remove_submenu_page('better-click-to-tweet', 'bcttps-license' );

	}
}

add_action( 'admin_menu', 'maybe_remove_premium_styles_license_page', 999 );

function bctt_license_menu() {
    $addons = bctt_get_active_addons();



    if ( ! empty( $addons ) ) {

		add_submenu_page(
			'better-click-to-tweet',
			'Add-on Licenses',
			'Add-on Licenses',
			'manage_options',
			'bctt-licenses',
			'bctt_license_page' );
	}

	return;
}

//add_action( 'admin_menu', 'bctt_license_menu', 40 );

/*
 * The output of the submenu page
 */

function bctt_license_page() {
	$active_plugins = bctt_get_active_addons();
	$license_url   = admin_url( 'options-general.php?page=better-click-to-tweet&tab=bctt-licenses' );
	?>
	<div class="bctt-settings-page bctt-licenses-tab">
		<div class="bctt-settings-grid">
			<main class="bctt-settings-main">
				<section class="bctt-card bctt-card-instructions" id="bctt_license_instructions" aria-labelledby="bctt-license-instructions-heading">
					<h2 id="bctt-license-instructions-heading" class="bctt-card-title"><?php esc_html_e( 'Activate Your Add-on Licenses', 'better-click-to-tweet' ); ?></h2>
					<div class="bctt-card-content">
						<p><?php esc_html_e( 'An active license is required for updates (including bug fixes and security updates) as well as support. Licenses don\'t affect the functionality of the add-ons in any way, but in order to receive support for installed add-ons you\'ll need an active license. Thanks again for your support!', 'better-click-to-tweet' ); ?></p>
					</div>
				</section>

				<section class="bctt-card bctt-card-settings" id="bctt_license_settings" aria-labelledby="bctt-license-settings-heading">
					<h2 id="bctt-license-settings-heading" class="bctt-card-title"><?php esc_html_e( 'Licenses', 'better-click-to-tweet' ); ?></h2>
					<div class="bctt-card-content">
						<form method="post" action="<?php echo esc_url( $license_url ); ?>" class="bctt-settings-form">
							<?php
							if ( function_exists( 'bcttps_license_menu' ) ) {
								echo wp_kses_post( sprintf( __( '<div class="notice notice-error inline"><p><strong>IMPORTANT:</strong></p><p>Your version of the Premium Styles add-on needs to be updated before you can input a functional license key.</p><p><strong>You haven\'t done anything wrong here</strong>, but the below input for Premium Styles will not work until you address this. For more information, see <a href="%1$s">this post</a>.</p></div>', 'better-click-to-tweet' ), esc_url( 'https://benlikes.us/premiumstylesupdate1' ) ) );
							}
							?>
							<?php settings_fields( 'bctt_license' ); ?>
							<div class="bctt-form-table">
								<?php
								foreach ( $active_plugins as $addons ) {
									$shortname   = bctt_addon_shortname( $addons['Name'] );
									$license_key = 'bctt_' . bctt_addon_slug( $shortname ) . '_license';
									$key         = get_option( $license_key );
									$status      = get_option( $license_key . '_active' );
									$is_active   = ( 'valid' === $status );
									$display_val = $is_active && ! empty( $key )
										? ( strlen( $key ) > 6 ? str_repeat( '•', strlen( $key ) - 6 ) . substr( $key, -6 ) : $key )
										: $key;
									?>
									<div class="bctt-form-row">
										<div class="bctt-form-label">
											<label for="<?php echo esc_attr( $license_key ); ?>"><?php echo esc_html( $shortname ); ?></label>
										</div>
										<div class="bctt-form-field">
											<?php if ( $is_active && ! empty( $key ) ) { ?>
												<input type="hidden" name="<?php echo esc_attr( $license_key ); ?>" value="<?php echo esc_attr( $key ); ?>" />
												<input id="<?php echo esc_attr( $license_key ); ?>" type="text" class="regular-text" value="<?php echo esc_attr( $display_val ); ?>" disabled="disabled" aria-label="<?php esc_attr_e( 'License key (last 6 characters shown)', 'better-click-to-tweet' ); ?>" />
											<?php } else { ?>
												<input id="<?php echo esc_attr( $license_key ); ?>" name="<?php echo esc_attr( $license_key ); ?>" type="text" class="regular-text" value="<?php echo esc_attr( $key ); ?>" placeholder="<?php esc_attr_e( 'Enter your license key', 'better-click-to-tweet' ); ?>"/>
											<?php } ?>
											<?php if ( false == $status || 'valid' == $status ) { ?>
												<?php if ( 'valid' == $status ) { ?>
													<p class="bctt-license-status"><?php esc_html_e( 'License active!', 'better-click-to-tweet' ); ?></p>
													<?php wp_nonce_field( $license_key . '_nonce', $license_key . '_nonce' ); ?>
													<button type="submit" class="button button-secondary" name="<?php echo esc_attr( $license_key ); ?>_deactivate" value="1"><?php esc_html_e( 'Deactivate License', 'better-click-to-tweet' ); ?></button>
												<?php } else { ?>
													<?php wp_nonce_field( $license_key . '_nonce', $license_key . '_nonce' ); ?>
													<button type="submit" class="button button-primary" name="<?php echo esc_attr( $license_key ); ?>_activate" value="1"><?php esc_html_e( 'Activate License', 'better-click-to-tweet' ); ?></button>
												<?php } ?>
											<?php } ?>
										</div>
									</div>
								<?php } ?>
							</div>
						</form>
					</div>
				</section>
			</main>
		</div>
	</div>
	<?php
}


function bctt_register_license_option() {

	$active_plugins = bctt_get_active_addons();
	$args = array(
		'type' => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'default' => null,
	);
	foreach ( $active_plugins as $addons ) {
		$shortname  = bctt_addon_slug( bctt_addon_shortname( $addons['Name'] ) );
		$longoption = 'bctt_' . $shortname . '_license';
		if ( ! get_option( $longoption ) ) {
			// creates our settings in the options table
			register_setting( 'bctt_license', $longoption, $args );
		}
	}
}

add_action( 'admin_init', 'bctt_register_license_option' );
