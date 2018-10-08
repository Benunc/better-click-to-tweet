<?php

function bctt_license_menu() {

    if ( ! empty( bctt_get_active_addons())) {
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
    <h2><?php _e( 'Activate Your Addons' ); ?></h2>
    <p>An active license is required for updates (including bug fixes and security updates) as well as support.</p>
    <form method="post" action="options.php">
		<?php
		$active_plugins = bctt_get_active_addons(); ?>

        <table class="form-table">
            <?php settings_fields( 'bctt_license' );

		foreach ( $active_plugins as $addons ) {
		    $shortname   = bctt_addon_shortname( $addons['Name'] );
		    $license_key = 'bctt_' . bctt_addon_slug($shortname) . '_license_key';
		    $key         = get_option( $license_key );
		    $status      = get_option( $license_key . '_active' );

		//var_dump( $active_plugins );

            ?>

            <tbody>
            <tr valign="top">

                <th scope="row" valign="top">
					<?php echo $shortname; ?>
                </th>
                <td>
                    <input id="<?php echo $license_key ?>" name="<?php echo $license_key ?>" type="text"
                           class="regular-text"
                           value="<?php echo $key; ?>" placeholder="<?php _e( 'Enter your license key', 'better-click-to-tweet' ); ?>"/>
                </td>
            </tr>
			<?php if ( false == $status ) { ?>
            <tr valign="top">
                <th scope="row" valign="top">
                    <?php //empty column for spacing ?>
                </th>
                <td>
					<?php if ( $status !== false && $status == 'valid' ) { ?>
                        <span style="color:green;"><?php _e( 'active' ); ?></span>
						<?php wp_nonce_field( $license_key . '_nonce',  $license_key . '_nonce' ); ?>
                        <input type="submit" class="button-secondary" name="bctt_license_deactivate"
                               value="<?php _e( 'Deactivate License', 'better-click-to-tweet' ); ?>"/>
					<?php } else {
						wp_nonce_field( $license_key . '_nonce', $license_key . '_nonce' ); ?>
                        <input type="submit" class="button-secondary" name="bctt_license_activate"
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
		$longoption = 'bctt_' . $shortname . '_license_key';
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
		$longoption = 'bctt_' . $shortname . '_license_key';
        $old = get_option( $longoption );
		if ( $old && $old != $new ) {
			delete_option( $longoption . '_active' ); // new license has been entered, so must reactivate
		}
	}

	return $new;
}