<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );

// Cache bust tinymce
add_filter( 'tiny_mce_version', 'refresh_mce' );

// Add button to visual editor
include dirname( __FILE__ ) . '/assets/tinymce/bctt-tinymce.php';


// instantiate i18n encouragement module
$bctt_i18n = new bctt_i18n(
	array(
		'textdomain'     => 'better-click-to-tweet',
		'project_slug'   => '/wp-plugins/better-click-to-tweet/stable',
		'plugin_name'    => 'Better Click To Share (Formerly Better Click To Tweet)',
		'hook'           => 'bctt_settings_top',
		'glotpress_url'  => 'https://translate.wordpress.org/',
		'glotpress_name' => 'Translating WordPress',
		'glotpress_logo' => 'https://plugins.svn.wordpress.org/better-click-to-tweet/assets/icon-256x256.png',
		'register_url '  => 'https://translate.wordpress.org/projects/wp-plugins/better-click-to-tweet/',
	)
);

// Add Settings Link
add_action( 'admin_menu', 'bctt_admin_menu' );


function bctt_admin_menu() {
    add_action( 'admin_init', 'bctt_register_settings', 100, 1 );
    
	add_submenu_page(
        'options-general.php', 
        __( 'Better Click To Share Main Settings', 'better-click-to-tweet' ), 
        __( 'Better Click To Share (Formerly Better Click To Tweet)', 'better-click-to-tweet' ), 
        'manage_options', 
        'better-click-to-tweet', 
        'bctt_settings' 
    );
}

function bctt_settings() {
    $addons = bctt_get_active_addons();

    // Enqueue settings page styles for all tabs (main tab only called bctt_settings_page(), so styles were missing on Licenses / Premium Styles / UTM).
    bctt_admin_styles();

    $logo_url = plugins_url( 'assets/img/bcts-logo.svg', __FILE__ );
    $base_url = admin_url( 'options-general.php?page=better-click-to-tweet' );
    ?>
    <div class="wrap bctt-settings-wrap">
        <header class="bctt-settings-header">
            <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php esc_attr_e( 'Better Click To Share', 'better-click-to-tweet' ); ?>" class="bctt-settings-logo" />
            <div class="bctt-settings-header-text">
                <h1 class="bctt-settings-title"><?php _e( 'Settings', 'better-click-to-tweet' ); ?></h1>
                <p class="bctt-settings-subtitle"><?php _e( 'Configure your share boxes and preferences.', 'better-click-to-tweet' ); ?></p>
            </div>
        </header>

        <?php
          $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'bctt-settings';
        ?>

        <nav class="nav-tab-wrapper bctt-nav-tabs" aria-label="<?php esc_attr_e( 'Settings tabs', 'better-click-to-tweet' ); ?>">
            <a 
                href="<?php echo esc_url( $base_url . '&tab=bctt-settings' ); ?>" 
                class="nav-tab <?php echo $active_tab == 'bctt-settings' ? 'nav-tab-active' : ''; ?>">
                    <?php _e( 'Settings', 'better-click-to-tweet' ); ?>
            </a>

            <?php     if ( ! empty( $addons ) ) {  ?>
                <a 
                    href="<?php echo esc_url( $base_url . '&tab=bctt-licenses' ); ?>" 
                    class="nav-tab <?php echo $active_tab == 'bctt-licenses' ? 'nav-tab-active' : ''; ?>">
                    <?php _e( 'Licenses', 'better-click-to-tweet' ); ?>
                </a>
            <?php } ?>
           
            <a 
                href="<?php echo esc_url( $base_url . '&tab=bctt-premium-styles' ); ?>" 
                class="nav-tab <?php echo $active_tab == 'bctt-premium-styles' ? 'nav-tab-active' : ''; ?>">
                <?php _e( 'Premium Styles', 'better-click-to-tweet' ); ?>
            </a>
           
            <a 
                href="<?php echo esc_url( $base_url . '&tab=bctt-utm-tags' ); ?>" 
                class="nav-tab <?php echo $active_tab == 'bctt-utm-tags' ? 'nav-tab-active' : ''; ?>">
                <?php _e( 'UTM Tags', 'better-click-to-tweet' ); ?>
            </a>
        </nav>
         
        <?php
            switch ($active_tab) {
                case 'bctt-licenses':
                        bctt_license_page();
                    break;

                case 'bctt-premium-styles':
                        if ( ! function_exists( 'bcttps_register_settings' )) { 
                            echo '<h2 style="text-align: center; margin-top: 20%;">';
                            echo sprintf( __( 'Want Premium styles? Add the <a href=%s>Premium Styles add-on</a> today!', 'better-click-to-tweet' ), esc_url( 'http://benlikes.us/bcttpsdirect' ) );
                            echo '</h2>';
                        } else {
                            bcttps_settings_output();
                        }
                    break;

                case 'bctt-utm-tags':
                        if ( ! defined( 'BCTTUTM_VERSION' ) ) {
                            echo '<h2 style="text-align: center; margin-top: 20%;">';
                            echo sprintf( __( 'Want to add UTM tags to the return URL to track how well BCTT boxes are performing? Add the <a href=%s>UTM tags add-on</a> today!', 'better-click-to-tweet' ), esc_url( 'http://benlikes.us/bcttutmdirect' ) );
                            echo '</h2>';
                        } else {
                            $BCTT_Utm_tags = Bctt_Utm_Tags::get_instance();
                            $BCTT_Utm_tags->bctt_utm_tags_settings_output();
                        }
                    break;
                
                default:
                        bctt_settings_page();
                    break;
            }
        ?>
         
    </div><!-- /.wrap -->
<?php
} // end settings