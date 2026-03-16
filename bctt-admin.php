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

/**
 * Minimum add-on versions required by this core version.
 * If the user has an older add-on, we show an "update add-on" message instead of the tab content to avoid a broken settings page.
 *
 * @since 6.0.0
 * @return array Tab slug => minimum add-on version (e.g. 'bctt-premium-styles' => '1.9.0').
 */
function bctt_get_core_min_addon_versions() {
	return array(
		'bctt-premium-styles' => '1.9.0',  // Card layout and tab integration; older versions bork the settings page.
		'bctt-utm-tags'       => '1.2.0',  // Versions that register with bctt_addon_requirements and work with core 6.0.
		'bctt-test-addon'     => '1.1.0',  // Test add-on tab; used to exercise add-on version checks without touching real add-ons.
	);
}

/**
 * Plugin basename for each settings tab, for reading version from header when add-on doesn't register.
 * Used as fallback for add-ons that predate the bctt_addon_requirements filter (e.g. Premium Styles 1.8.x).
 *
 * @since 6.0.0
 * @return array Tab slug => plugin basename (e.g. 'better-click-to-tweet-premium-styles/better-click-to-tweet-premium-styles.php').
 */
function bctt_get_addon_plugin_basenames() {
	return array(
		'bctt-premium-styles' => 'better-click-to-tweet-premium-styles/better-click-to-tweet-premium-styles.php',
		'bctt-utm-tags'       => 'better-click-to-tweet-utm-tags/better-click-to-tweet-utm-tags.php',
	);
}

/**
 * Get add-on name and version from plugin header when the add-on does not register via bctt_addon_requirements.
 *
 * @since 6.0.0
 * @param string $tab_slug Tab slug.
 * @return array|null Array with 'name' and 'version', or null if not found.
 */
function bctt_get_addon_version_from_plugin_header( $tab_slug ) {
	$basenames = bctt_get_addon_plugin_basenames();
	if ( empty( $basenames[ $tab_slug ] ) ) {
		return null;
	}
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$plugins = get_plugins();
	$basename = $basenames[ $tab_slug ];
	if ( empty( $plugins[ $basename ] ) || ! is_plugin_active( $basename ) ) {
		return null;
	}
	$data = $plugins[ $basename ];
	$version = isset( $data['Version'] ) ? $data['Version'] : '';
	$name    = isset( $data['Name'] ) ? $data['Name'] : '';
	if ( $version === '' || $name === '' ) {
		return null;
	}
	return array(
		'name'    => $name,
		'version' => $version,
	);
}

/**
 * Check if the active add-on for a tab is older than this core's minimum.
 * If so, returns message data for display; otherwise returns null.
 * Uses bctt_addon_requirements when the add-on registers; otherwise falls back to plugin header (Version/Name).
 *
 * @since 6.0.0
 * @param string $tab_slug Tab slug (e.g. 'bctt-premium-styles', 'bctt-utm-tags').
 * @return array|null Array with 'addon_name', 'addon_version', 'min_addon_version' if add-on is too old; null if OK or unknown.
 */
function bctt_get_addon_too_old_message( $tab_slug ) {
	$requirements = bctt_get_addon_requirements();
	$min_versions = bctt_get_core_min_addon_versions();
	if ( empty( $min_versions[ $tab_slug ] ) ) {
		return null;
	}
	$min_addon = $min_versions[ $tab_slug ];

	// Prefer addon-registered data (add-ons that hook bctt_addon_requirements).
	if ( ! empty( $requirements[ $tab_slug ] ) ) {
		$r = $requirements[ $tab_slug ];
		if ( isset( $r['version'], $r['name'] ) && version_compare( $r['version'], $min_addon, '<' ) ) {
			return array(
				'addon_name'         => $r['name'],
				'addon_version'      => $r['version'],
				'min_addon_version'  => $min_addon,
			);
		}
		if ( isset( $r['version'] ) && version_compare( $r['version'], $min_addon, '>=' ) ) {
			return null;
		}
	}

	// Fallback: add-on does not register (e.g. Premium Styles 1.8.x). Read version from plugin header.
	$header = bctt_get_addon_version_from_plugin_header( $tab_slug );
	if ( $header && version_compare( $header['version'], $min_addon, '<' ) ) {
		return array(
			'addon_name'         => $header['name'],
			'addon_version'      => $header['version'],
			'min_addon_version'  => $min_addon,
		);
	}

	return null;
}

/**
 * Output the "update add-on to use this tab" message.
 * Shown when core is new enough but the add-on is too old (avoids broken/incompatible tab content).
 *
 * @since 6.0.0
 * @param array $msg_data Keys: addon_name, addon_version, min_addon_version.
 */
function bctt_addon_too_old_message_html( $msg_data ) {
	if ( empty( $msg_data['addon_name'] ) || empty( $msg_data['addon_version'] ) || empty( $msg_data['min_addon_version'] ) ) {
		return;
	}
	$addon_name        = $msg_data['addon_name'];
	$addon_version     = $msg_data['addon_version'];
	$min_addon_version = $msg_data['min_addon_version'];
	$plugins_url  = admin_url( 'plugins.php' );
	$updates_url  = admin_url( 'update-core.php' );
	$licenses_url = admin_url( 'options-general.php?page=better-click-to-tweet&tab=bctt-licenses' );
	?>
	<div class="bctt-settings-page">
		<div class="bctt-settings-grid">
			<section class="bctt-card bctt-card-addon-requirement" aria-labelledby="bctt-addon-too-old-heading">
				<h2 id="bctt-addon-too-old-heading" class="bctt-card-title">
					<?php esc_html_e( 'Update your add-on', 'better-click-to-tweet' ); ?>
				</h2>
				<div class="bctt-card-content">
					<p>
						<?php
						echo wp_kses_post(
							sprintf(
								/* translators: 1: add-on name, 2: installed version (e.g. 1.8.0), 3: required version (e.g. 1.9.0) */
								__( 'You have %1$s version %2$s. This version of Better Click To Share requires %1$s version %3$s or newer to change site-wide settings for %1$s.', 'better-click-to-tweet' ),
								esc_html( $addon_name ),
								esc_html( $addon_version ),
								esc_html( $min_addon_version )
							)
						);
						?>
					</p>
					<h3 class="bctt-addon-update-steps-heading">
						<?php
						echo esc_html(
							sprintf(
								/* translators: %s: add-on name (e.g. Premium Styles) */
								__( 'How to get access to your %s site-wide settings', 'better-click-to-tweet' ),
								$addon_name
							)
						);
						?>
					</h3>
					<ol class="bctt-addon-update-steps">
						<li>
							<?php
							echo wp_kses_post(
								sprintf(
									/* translators: %s: link to Licenses tab */
									__( 'Make sure your license is active at the <a href="%s">Licenses tab</a>.', 'better-click-to-tweet' ),
									esc_url( $licenses_url )
								)
							);
							?>
						</li>
						<li>
							<?php
							echo wp_kses_post(
								sprintf(
									/* translators: 1: link to Plugins page, 2: link to updates page */
									__( 'Check for an update on the <a href="%1$s">Plugins page</a> (or <a href="%2$s">check for updates</a> if you don\'t see it).', 'better-click-to-tweet' ),
									esc_url( $plugins_url ),
									esc_url( $updates_url )
								)
							);
							?>
						</li>
						<li>
							<?php
							echo wp_kses_post(
								sprintf(
									/* translators: %s: link to account login */
									__( 'If you need to re-enter your license key, you can find it at <a href="%s" target="_blank" rel="noopener noreferrer">your Better Click To Share account</a>.', 'better-click-to-tweet' ),
									esc_url( 'https://benlikes.us/bcttlogin' )
								)
							);
							?>
							<p class="bctt-addon-update-step-sublist"><?php esc_html_e( "If you've misplaced your credentials, use the lost password option there to reset them.", 'better-click-to-tweet' ); ?></p>
						</li>
					</ol>
					<p>
						<?php esc_html_e( 'Your add-on is still active and functional. You\'ll need the latest version only to change site-wide settings on this tab. Functionality like updating settings for particular BCTS boxes is still fully functional.', 'better-click-to-tweet' ); ?>
					</p>
					<p>
						<?php
						echo wp_kses_post(
							sprintf(
								/* translators: %s: link to contact form */
								__( 'You can always reach out at our <a href="%s" target="_blank" rel="noopener noreferrer">contact form</a>.', 'better-click-to-tweet' ),
								esc_url( 'https://benlikes.us/bctscontact' )
							)
						);
						?>
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
          if ( defined( 'BCTT_DEVELOPMENT_MODE' ) && BCTT_DEVELOPMENT_MODE ) {
              $allowed_tabs[] = 'bctt-test-addon';
          }
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
           
            <?php if ( defined( 'BCTT_DEVELOPMENT_MODE' ) && BCTT_DEVELOPMENT_MODE ) : ?>
                <a 
                    href="<?php echo esc_url( $base_url . '&tab=bctt-test-addon' ); ?>" 
                    class="nav-tab <?php echo $active_tab == 'bctt-test-addon' ? 'nav-tab-active' : ''; ?>">
                    <?php _e( 'Test Add-on', 'better-click-to-tweet' ); ?>
                </a>
            <?php endif; ?>
        </nav>
         
        <?php
            switch ($active_tab) {
                case 'bctt-licenses':
                        bctt_license_page();
                    break;

                case 'bctt-premium-styles':
                        if ( ! function_exists( 'bcttps_register_settings' ) ) {
                            echo '<h2 style="text-align: center; margin-top: 20%;">';
                            echo sprintf( __( 'Want Premium styles? Add the <a href=%s>Premium Styles add-on</a> today!', 'better-click-to-tweet' ), esc_url( 'https://benlikes.us/bcttpsdirect' ) );
                            echo '</h2>';
                        } else {
                            $req_msg = bctt_get_addon_requirement_message( 'bctt-premium-styles' );
                            if ( $req_msg ) {
                                bctt_addon_requirement_message_html( $req_msg );
                            } elseif ( $addon_old_msg = bctt_get_addon_too_old_message( 'bctt-premium-styles' ) ) {
                                bctt_addon_too_old_message_html( $addon_old_msg );
                            } else {
                                bcttps_settings_output();
                            }
                        }
                    break;

                case 'bctt-utm-tags':
                        if ( ! defined( 'BCTTUTM_VERSION' ) ) {
                            echo '<h2 style="text-align: center; margin-top: 20%;">';
                            echo sprintf( __( 'Want to add UTM tags to the return URL to track how well BCTT boxes are performing? Add the <a href=%s>UTM tags add-on</a> today!', 'better-click-to-tweet' ), esc_url( 'https://benlikes.us/bcttutmdirect' ) );
                            echo '</h2>';
                        } else {
                            $req_msg = bctt_get_addon_requirement_message( 'bctt-utm-tags' );
                            if ( $req_msg ) {
                                bctt_addon_requirement_message_html( $req_msg );
                            } elseif ( $addon_old_msg = bctt_get_addon_too_old_message( 'bctt-utm-tags' ) ) {
                                bctt_addon_too_old_message_html( $addon_old_msg );
                            } else {
                                $BCTT_Utm_tags = Bctt_Utm_Tags::get_instance();
                                $BCTT_Utm_tags->bctt_utm_tags_settings_output();
                            }
                        }
                    break;

                case 'bctt-test-addon':
                        if ( ! ( defined( 'BCTT_DEVELOPMENT_MODE' ) && BCTT_DEVELOPMENT_MODE ) ) {
                            // Hard safeguard: do not show this tab unless development mode is on (addon or wp-config).
                            bctt_settings_page();
                            break;
                        }
                        if ( ! defined( 'BCTTTEST_VERSION' ) ) {
                            echo '<h2 style="text-align: center; margin-top: 20%;">';
                            echo sprintf( __( '<a href=%s>Activate The Test Addon</a> to populate this tab with content from the addon.', 'better-click-to-tweet' ), esc_url( '/wp-admin/plugins.php' ) );
                            echo '</h2>';
                        } else {
                            $req_msg = bctt_get_addon_requirement_message( 'bctt-test-addon' );
                            if ( $req_msg ) {
                                bctt_addon_requirement_message_html( $req_msg );
                            } elseif ( $addon_old_msg = bctt_get_addon_too_old_message( 'bctt-test-addon' ) ) {
                                bctt_addon_too_old_message_html( $addon_old_msg );
                            } else {
                                if ( function_exists( 'bctttest_settings_tab_output' ) ) {
                                    bctttest_settings_tab_output();
                                } else {
                                    echo '<p>' . esc_html__( 'The Test Add-on is active, but no tab output function is available.', 'better-click-to-tweet' ) . '</p>';
                                }
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