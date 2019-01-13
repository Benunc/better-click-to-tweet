<?php

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

add_action( 'admin_menu', 'bctt_license_menu', 40 );

/*
 * The output of the submenu page
 */

function bctt_license_page() {
	?>
    <div class="wrap">
    <h2><?php _e( 'Activate Your Addons', 'better-click-to-tweet' ); ?></h2>
    <p><?php _e('An active license is required for updates (including bug fixes and security updates) as well as support.', 'better-click-to-tweet' );?></p>

    <form method="post" action="admin.php?page=bctt-licenses">
	    <?php settings_errors('bctt-license');

		$active_plugins = bctt_get_active_addons(); ?>

        <table class="form-table">
			<?php settings_fields( 'bctt_license' );

			foreach ( $active_plugins

			as $addons ) {
			$shortname   = bctt_addon_shortname( $addons['Name'] );
			$license_key = 'bctt_' . bctt_addon_slug( $shortname ) . '_license';
			$key         = get_option( $license_key );
			$status      = get_option( $license_key . '_active' );
			//var_dump( $status );
			?>
            <tbody>
            <tr valign="top">

                <th scope="row" valign="top">
					<?php echo $shortname; ?>
                </th>
                <td>
                    <input id="<?php echo $license_key ?>" name="<?php echo $license_key ?>" type="text"
                           class="regular-text"
                           value="<?php echo $key; ?>"
                           placeholder="<?php _e( 'Enter your license key', 'better-click-to-tweet' ); ?>"/>
                </td>
            </tr>
			<?php if (  false == $status || 'valid' == $status) { ?>
            <tr valign="top">
                <th scope="row" valign="top">
					<?php //empty column for spacing ?>
                </th>
                <td style="padding-top:0;">
					<?php if ( $status == 'valid' ) { ?>
                        <div style="color:green; margin-bottom:1em;"><?php _e( 'License active!' ); ?></div>
						<?php wp_nonce_field( $license_key . '_nonce', $license_key . '_nonce' ); ?>
                        <input type="submit" class="button-secondary" name="<?php echo $license_key ?>_deactivate"
                               value="<?php _e( 'Deactivate License', 'better-click-to-tweet' ); ?>"/>
					<?php } else {
						wp_nonce_field( $license_key . '_nonce', $license_key . '_nonce' ); ?>
                        <input type="submit" class="button-secondary" name="<?php echo $license_key ?>_activate"
                               value="<?php _e( 'Activate License', 'better-click-to-tweet' ); ?>"/>
					<?php }
					} ?>
                </td>
            </tr>
			<?php } ?>
            </tbody>
        </table>

    </form>
	<?php
}


function bctt_register_license_option() {

	$active_plugins = bctt_get_active_addons();
	$args = array(
		'type' => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'default' => NULL,
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
