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

/**
 * Get add-on requirements registered via the bctt_addon_requirements filter.
 * Add-ons pass: tab_slug => array( 'name' => 'Add-on Name', 'version' => 'x.y.z', 'min_core' => 'q.r.s' ).
 *
 * @since 6.0.0
 * @return array
 */
function bctt_get_addon_requirements() {
	return apply_filters( 'bctt_addon_requirements', array() );
}

/**
 * Check if the current core version meets the requirement for an add-on tab.
 * If not, returns message data for display; otherwise returns null.
 *
 * @since 6.0.0
 * @param string $tab_slug Tab slug (e.g. 'bctt-premium-styles', 'bctt-utm-tags').
 * @return array|null Array with 'addon_name', 'addon_version', 'min_core' if core is too old; null if OK or unknown.
 */
function bctt_get_addon_requirement_message( $tab_slug ) {
	$requirements = bctt_get_addon_requirements();
	if ( empty( $requirements[ $tab_slug ] ) || ! defined( 'BCTT_VERSION' ) ) {
		return null;
	}
	$r = $requirements[ $tab_slug ];
	if ( ! isset( $r['min_core'], $r['version'], $r['name'] ) ) {
		return null;
	}
	if ( version_compare( BCTT_VERSION, $r['min_core'], '>=' ) ) {
		return null;
	}
	return array(
		'addon_name'    => $r['name'],
		'addon_version' => $r['version'],
		'min_core'      => $r['min_core'],
	);
}

/**
 * Output the "update core to change settings" message for an add-on tab.
 * Single source of truth: copy and layout live in core only.
 *
 * @since 6.0.0
 * @param array $msg_data Keys: addon_name, addon_version, min_core.
 */
function bctt_addon_requirement_message_html( $msg_data ) {
	if ( empty( $msg_data['addon_name'] ) || empty( $msg_data['addon_version'] ) || empty( $msg_data['min_core'] ) ) {
		return;
	}
	$addon_name    = $msg_data['addon_name'];
	$addon_version = $msg_data['addon_version'];
	$min_core      = $msg_data['min_core'];
	?>
	<div class="bctt-settings-page">
		<div class="bctt-settings-grid">
			<section class="bctt-card bctt-card-addon-requirement" aria-labelledby="bctt-addon-requirement-heading">
				<h2 id="bctt-addon-requirement-heading" class="bctt-card-title">
					<?php esc_html_e( 'Update required', 'better-click-to-tweet' ); ?>
				</h2>
				<div class="bctt-card-content">
					<p>
						<?php
						echo wp_kses_post(
							sprintf(
								/* translators: 1: add-on version (e.g. 1.9.0), 2: add-on name, 3: required core version (e.g. 6.0.0), 4: add-on name again */
								__( 'Version %1$s of %2$s requires version %3$s of the main Better Click To Share plugin. Update there to change settings for %4$s.', 'better-click-to-tweet' ),
								esc_html( $addon_version ),
								esc_html( $addon_name ),
								esc_html( $min_core ),
								esc_html( $addon_name )
							)
						);
						?>
					</p>
					<p>
						<?php esc_html_e( 'As a note: none of your existing settings have changed.', 'better-click-to-tweet' ); ?>
					</p>
				</div>
			</section>
		</div>
	</div>
	<?php
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
          $allowed_tabs = array( 'bctt-settings', 'bctt-licenses', 'bctt-premium-styles', 'bctt-utm-tags' );
          $active_tab   = isset( $_GET['tab'] ) && in_array( $_GET['tab'], $allowed_tabs, true ) ? sanitize_key( $_GET['tab'] ) : 'bctt-settings';
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
                        if ( ! function_exists( 'bcttps_register_settings' ) ) {
                            echo '<h2 style="text-align: center; margin-top: 20%;">';
                            echo sprintf( __( 'Want Premium styles? Add the <a href=%s>Premium Styles add-on</a> today!', 'better-click-to-tweet' ), esc_url( 'http://benlikes.us/bcttpsdirect' ) );
                            echo '</h2>';
                        } else {
                            $req_msg = bctt_get_addon_requirement_message( 'bctt-premium-styles' );
                            if ( $req_msg ) {
                                bctt_addon_requirement_message_html( $req_msg );
                            } else {
                                bcttps_settings_output();
                            }
                        }
                    break;

                case 'bctt-utm-tags':
                        if ( ! defined( 'BCTTUTM_VERSION' ) ) {
                            echo '<h2 style="text-align: center; margin-top: 20%;">';
                            echo sprintf( __( 'Want to add UTM tags to the return URL to track how well BCTT boxes are performing? Add the <a href=%s>UTM tags add-on</a> today!', 'better-click-to-tweet' ), esc_url( 'http://benlikes.us/bcttutmdirect' ) );
                            echo '</h2>';
                        } else {
                            $req_msg = bctt_get_addon_requirement_message( 'bctt-utm-tags' );
                            if ( $req_msg ) {
                                bctt_addon_requirement_message_html( $req_msg );
                            } else {
                                $BCTT_Utm_tags = Bctt_Utm_Tags::get_instance();
                                $BCTT_Utm_tags->bctt_utm_tags_settings_output();
                            }
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