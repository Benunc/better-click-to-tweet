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
		<?php
		$active_plugins = bctt_get_active_addons(); ?>

        <table class="form-table">
			<?php settings_fields( 'bctt_license' );

			foreach ( $active_plugins

			as $addons ) {
			$shortname   = bctt_addon_shortname( $addons['Name'] );
			$license_key = 'bctt_' . bctt_addon_slug( $shortname ) . '_license';
			$key         = get_option( $license_key );
			$status      = get_option( $license_key . '_active' );
			var_dump( $status );
			?>
            <script>
                console.log(<?php echo json_encode($_POST); ?>);
            </script>
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
                <td>
					<?php if ( $status == 'valid' ) { ?>
                        <span style="color:green;"><?php _e( 'active' ); ?></span>
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
		<?php submit_button(); ?>

    </form>
	<?php
}


function bctt_register_license_option() {

	$active_plugins = bctt_get_active_addons();

	foreach ( $active_plugins as $addons ) {
		$shortname  = bctt_addon_slug( bctt_addon_shortname( $addons['Name'] ) );
		$longoption = 'bctt_' . $shortname . '_license';
		if ( ! get_option( $longoption ) ) {
			// creates our settings in the options table
			register_setting( 'bctt_license', $longoption, 'bctt_sanitize_license' );
		}
	}
}

add_action( 'admin_init', 'bctt_register_license_option' );

function bctt_sanitize_license( $new ) {
	$active_plugins = bctt_get_active_addons();
	foreach ( $active_plugins as $addons ) {
		$shortname  = bctt_addon_slug( bctt_addon_shortname( $addons['Name'] ) );
		$longoption = 'bctt_' . $shortname . '_license';
		$old        = get_option( $longoption );
		if ( $old && $old != $new ) {
			delete_option( $longoption . '_active' ); // new license has been entered, so must reactivate
		}
	}

	return $new;
}