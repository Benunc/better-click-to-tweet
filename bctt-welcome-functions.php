<?php

/**
 * Get wizard step
 *
 * @return string
 */
function bctt_get_step() {
    return isset( $_GET['step'] ) ? wp_unslash( $_GET['step'] ) : 'step1';
}

/**
 * Get welcome step url
 *
 * @param string $step
 * @return string url
 */
function bctt_get_step_url( $step ) {
    return admin_url( 'admin.php?page=bctt-welcome&step=' . $step );
}

/**
 * Redirect to welcome page
 *
 * @return void
 */
function bctt_welcome_redirect() {
    if ( get_transient( '_bctt_activation_redirect' ) ) {
        $do_redirect  = true;
        $current_page = isset( $_GET['page'] ) ? wc_clean( wp_unslash( $_GET['page'] ) ) : false;
        // Bailout redirect during these events.
        if ( wp_doing_ajax() || is_network_admin() || ! current_user_can( 'manage_options' ) ) {
            $do_redirect = false;
        }
        // Bailout redirect on these pages & events.
        if ( 'bctt-welcome' === $current_page || isset( $_GET['activate-multi'] ) ) {
            delete_transient( '_bctt_activation_redirect' );
            $do_redirect = false;
        }
        if ( $do_redirect ) {
            delete_transient( '_bctt_activation_redirect' );
            wp_safe_redirect( bctt_get_step_url( 'step1' ) );
            exit;
        }
    }
}
add_action( 'admin_init', 'bctt_welcome_redirect' );

/**
 * Include Welcome Class
 */
if ( ! empty( $_GET['page'] ) ) {
	switch ( $_GET['page'] ) {
		case 'bctt-welcome':
				include_once 'bctt-welcome.php';
			break;
	}
}