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
	?>
    <div class="wrap">
    <h2><?php _e( 'Activate Your Add-on Licenses', 'better-click-to-tweet' ); ?></h2>
    <p><?php _e('An active license is required for updates (including bug fixes and security updates) as well as support. Licenses don\'t affect the functionality of the add-ons in any way, but in order to receive support for installed add-ons you\'ll need an active license. Thanks again for your support!', 'better-click-to-tweet' );?></p>


    <form method="post" action="<?php echo admin_url( 'admin.php?page=better-click-to-tweet&tab=bctt-licenses' ); ?>">
	    <?php settings_errors('bctt-license');
        if ( function_exists( 'bcttps_license_menu' ) ) {
            echo sprintf( __( '<div style="background-color:#b22222; text-align:center; color: white; padding: 1em;"><p><strong>IMPORTANT:</strong></p><p>Your version of the Premium Styles add-on needs to be updated before you can input a functional license key.</p> <p><strong>You haven\'t done anything wrong here</strong>, but the below input for Premium Styles will not work until you address this. For more information, see <a style="color:yellow;"href="%1$s">this post</a></p></div>', 'better-click-to-tweet' ), esc_url('http://benlikes.us/premiumstylesupdate1') );

        }
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
