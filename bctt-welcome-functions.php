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
       case 'bctt-setup':
            if ( 'bctt-setup' === bctt_get_step() ) {
                $class = 'class="active"';
            } else {
                $class = 'class="done"';
            }
           break;
       case 'bctt-usage':
            $done = array( 'bctt-content', 'bctt-grow', 'bctt-done' );
                 
            if ( 'bctt-usage' === bctt_get_step() ) {
                $class = 'class="active"';
            } else if ( in_array( bctt_get_step(), $done ) ) {
                $class = 'class="done"';
            }
           break;
       case 'bctt-content':
             $done = array( 'bctt-grow', 'bctt-done' );

            if ( 'bctt-content' === bctt_get_step() ) {
                $class = 'class="active"';
            } else if ( in_array( bctt_get_step(), $done ) ) {
                $class = 'class="done"';
            }
           break;
       case 'bctt-grow':
            $done = array( 'bctt-done' );

            if ( 'bctt-grow' === bctt_get_step() ) {
                $class = 'class="active"';
            } else if ( in_array( bctt_get_step(), $done ) ) {
                $class = 'class="done"';
            }
           break;
       case 'bctt-done':
            if ( 'bctt-done' === bctt_get_step() ) {
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
    return isset( $_GET['step'] ) ? wp_unslash( $_GET['step'] ) : 'bctt-setup';
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
        $current_page = isset( $_GET['page'] ) ? wp_unslash( $_GET['page'] ) : false;
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
            wp_safe_redirect( bctt_get_step_url( 'bctt-setup' ) );
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