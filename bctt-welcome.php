<?php 
/*
 * Welcome Wizard Handler Class
 *
 * This is the base class for adding Welcome onboarding
 *
 * @since 5.7.0
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'BCTT_Welcome' ) ):

    class BCTT_Welcome {
        public function __construct( ) {
			$this->hooks();
        }
        
        private function hooks() {
            add_action( 'admin_menu', array( $this, 'admin_menu' ) );
            add_action( 'admin_init', array( $this, 'welcome_page' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        }
        
        public function admin_menu() {
            add_dashboard_page( '', '', 'manage_options', 'bctt-welcome', '' );
        }

        public function welcome_page() {
            set_current_screen();

            // Update twitter handle
            if ( isset( $_POST['bctt-twitter'] ) ) {
                update_option( 'bctt-twitter-handle', $_POST['bctt-twitter'] );
                wp_safe_redirect( bctt_get_step_url( 'step2' ) );
                exit;
            }
            
            // Get current step
            $current_step = isset( $_GET['step'] ) ? wp_unslash( $_GET['step'] ) : 'step1';

            // Get page header
            require_once 'includes/views/welcome/_header.php'; 

            // Get step content
            switch ($current_step) {
                case 'step1':
                        require_once 'includes/views/welcome/_step1.php';
                    break;

                case 'step2':
                        require_once 'includes/views/welcome/_step2.php';
                    break;

                case 'step3':
                        require_once 'includes/views/welcome/_step3.php';
                    break;

                case 'step4':
                        require_once 'includes/views/welcome/_step4.php';
                    break;

                case 'finish':
                        require_once 'includes/views/welcome/_finish.php';
                    break;
                
                default:
                        require_once 'includes/views/welcome/_step1.php';
                    break;
            }

            // Get page footer
            require_once 'includes/views/welcome/_footer.php';

            exit;
        }

        public function enqueue_scripts() {
            wp_enqueue_style( 'bctt_welcome_styles', plugins_url( '/assets/css/utility.css',  __FILE__ ) , array(), null );
        }
    
    }

    $bctt_welcome = new BCTT_Welcome();

endif;
