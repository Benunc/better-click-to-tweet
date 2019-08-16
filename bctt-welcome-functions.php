<?php

/**
 * Get wizard nav css class
 *
 * @param string $step
 * @return string
 */
function bctt_get_step_class( $step ) {
   $class= "";

   switch ( $step ) {
       case 'bctt-twitter-setup':
            if ( 'bctt-twitter-setup' === bctt_get_step() ) {
                $class = 'class="active"';
            } else {
                $class = 'class="done"';
            }
           break;
       case 'bctt-usage':
            $done = array( 'bctt-advanced', 'bctt-mailing-list', 'bctt-ready' );
                 
            if ( 'bctt-usage' === bctt_get_step() ) {
                $class = 'class="active"';
            } else if ( in_array( bctt_get_step(), $done ) ) {
                $class = 'class="done"';
            }
           break;
       case 'bctt-advanced':
             $done = array( 'bctt-mailing-list', 'bctt-ready' );

            if ( 'bctt-advanced' === bctt_get_step() ) {
                $class = 'class="active"';
            } else if ( in_array( bctt_get_step(), $done ) ) {
                $class = 'class="done"';
            }
           break;
       case 'bctt-mailing-list':
            $done = array( 'bctt-ready' );

            if ( 'bctt-mailing-list' === bctt_get_step() ) {
                $class = 'class="active"';
            } else if ( in_array( bctt_get_step(), $done ) ) {
                $class = 'class="done"';
            }
           break;
       case 'bctt-ready':
            if ( 'bctt-ready' === bctt_get_step() ) {
                $class = 'class="active"';
            }
           break;
       
       default:
           # code...
           break;
   }

   return $class;
}

/**
 * Get wizard step
 *
 * @return string
 */
function bctt_get_step() {
    return isset( $_GET['step'] ) ? wp_unslash( $_GET['step'] ) : 'bctt-twitter-setup';
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
            wp_safe_redirect( bctt_get_step_url( 'bctt-twitter-setup' ) );
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