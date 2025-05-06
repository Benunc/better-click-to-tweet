<?php
declare(strict_types=1);

namespace BCTT\Admin;

use BCTT\Options;
use BCTT\Utils; // To get active addons

// Required for the updater class itself
if (file_exists(BCTT_PLUGIN_DIR . 'includes/updater/BCTT_SL_Plugin_Updater.php')) {
    require_once BCTT_PLUGIN_DIR . 'includes/updater/BCTT_SL_Plugin_Updater.php';
}

if (!defined('ABSPATH')) {
    die('No soup for you. You leave now.');
}

/**
 * Handles plugin and add-on updates via EDD Software Licensing.
 */
class Updater
{
    private const API_URL = 'https://www.betterclicktotweet.com/'; // EDD Shop URL
    private const SETTINGS_SECTION_ID = 'bctt_licenses_section';
    private const LICENSE_OPTION_SUFFIX = '_license';
    private const STATUS_OPTION_SUFFIX = '_license_active';
    private const MAIN_SETTINGS_PAGE_SLUG = 'better-click-to-tweet'; // Matches AdminManager slug

    private Options $options;

    /**
     * Constructor.
     *
     * @param Options $options Plugin options instance.
     */
    public function __construct(Options $options)
    {
        $this->options = $options;
    }

    /**
     * Register hooks related to updates and licensing.
     */
    public function register_hooks(): void
    {
        // Run updater checks for active addons
        add_action('admin_init', [$this, 'run_updaters'], 0); // Run early

        // Handle license activation/deactivation POST requests
        add_action('admin_init', [$this, 'handle_license_actions']);

        // Add license settings section and fields to the main settings page
        add_action('admin_init', [$this, 'register_license_settings']);

        // Add backward compatibility for old license page removal
        add_action('admin_menu', [$this, 'remove_old_premium_styles_license_page'], 999);
    }

    /**
     * Instantiate the EDD SL Updater for each active licensed add-on.
     */
    public function run_updaters(): void
    {
        if (!class_exists('BCTT_SL_Plugin_Updater')) {
            return; // Don't run if the updater class isn't loaded
        }

        $active_addons = Utils::get_active_addons();

        foreach ($active_addons as $plugin_path => $addon_data) {
            $item_name = $addon_data['Name']; // e.g., "Better Click To Tweet Premium Styles"
            $item_shortname = Utils::get_addon_shortname($item_name); // e.g., "Premium Styles"
            $item_slug = Utils::generate_addon_slug($item_shortname); // e.g., "premium_styles"
            $license_option_name = 'bctt_' . $item_slug . self::LICENSE_OPTION_SUFFIX; // e.g., bctt_premium_styles_license
            $version = $addon_data['Version'];
            $author = $addon_data['Author'];
            $plugin_file = WP_PLUGIN_DIR . '/' . $plugin_path;

            $license_key = trim($this->options->get($license_option_name, ''));

            // Setup the updater
            new \BCTT_SL_Plugin_Updater( // Use global namespace
                self::API_URL,
                $plugin_file,
                [
                    'version'   => $version,
                    'license'   => $license_key,
                    'item_name' => $item_name,
                    'author'    => $author,
                    // 'beta'    => false, // Optional: Set to true to check for beta versions
                ]
            );
        }
    }

    /**
     * Handle license activation/deactivation form submissions.
     */
    public function handle_license_actions(): void
    {
        $active_addons = Utils::get_active_addons();

        foreach ($active_addons as $plugin_path => $addon_data) {
             $item_name = $addon_data['Name'];
             $item_shortname = Utils::get_addon_shortname($item_name);
             $item_slug = Utils::generate_addon_slug($item_shortname);
             $option_base = 'bctt_' . $item_slug; // e.g., bctt_premium_styles
             $nonce_action = $option_base . self::LICENSE_OPTION_SUFFIX . '_nonce';
             $activate_button_name = $option_base . self::LICENSE_OPTION_SUFFIX . '_activate';
             $deactivate_button_name = $option_base . self::LICENSE_OPTION_SUFFIX . '_deactivate';
             $license_key_name = $option_base . self::LICENSE_OPTION_SUFFIX;

             // Check if activate button was pressed for this addon
             if (isset($_POST[$activate_button_name])) {
                if (!isset($_POST[$nonce_action]) || !wp_verify_nonce($_POST[$nonce_action], $nonce_action)) {
                    wp_die(esc_html__('Nonce verification failed', 'better-click-to-tweet'));
                }
                $license_key = isset($_POST[$license_key_name]) ? sanitize_text_field($_POST[$license_key_name]) : '';
                $this->process_license_action('activate_license', $license_key, $item_name, $option_base);
                break; // Process one action per request
             }

             // Check if deactivate button was pressed for this addon
             if (isset($_POST[$deactivate_button_name])) {
                 if (!isset($_POST[$nonce_action]) || !wp_verify_nonce($_POST[$nonce_action], $nonce_action)) {
                     wp_die(esc_html__('Nonce verification failed', 'better-click-to-tweet'));
                 }
                 $license_key = $this->options->get($license_key_name, ''); // Get stored key for deactivation
                 $this->process_license_action('deactivate_license', $license_key, $item_name, $option_base);
                 break; // Process one action per request
             }
        }
    }

    /**
     * Process activation or deactivation via API call.
     *
     * @param string $action 'activate_license' or 'deactivate_license'.
     * @param string $license_key The license key.
     * @param string $item_name The EDD item name.
     * @param string $option_base The base name for license options (e.g., 'bctt_premium_styles').
     */
    private function process_license_action(string $action, string $license_key, string $item_name, string $option_base): void
    {
        $license_key_option = $option_base . self::LICENSE_OPTION_SUFFIX;
        $license_status_option = $option_base . self::STATUS_OPTION_SUFFIX;

        if (empty($license_key) && $action === 'activate_license') {
            // If trying to activate with empty key, treat as deactivation
            delete_option($license_status_option);
            delete_option($license_key_option);
            add_settings_error('bctt-license-error', 'empty_key', __('License key cannot be empty for activation.', 'better-click-to-tweet'), 'error');
            return;
        }

        // Data to send to the API.
        $api_params = [
            'edd_action' => $action,
            'license'    => $license_key,
            'item_name'  => urlencode($item_name),
            'url'        => home_url(),
        ];

        // Call the API.
        $response = wp_remote_post(
            self::API_URL,
            [
                'timeout'   => 15,
                'sslverify' => false, // Consider setting to true if server supports it
                'body'      => $api_params,
            ]
        );

        $message = '';
        $message_type = 'error';

        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
            $error_message = is_wp_error($response) ? $response->get_error_message() : __('An error occurred, please try again.', 'better-click-to-tweet');
            $message = $error_message;
        } else {
            $license_data = json_decode(wp_remote_retrieve_body($response));

            if ($license_data === null) {
                 $message = __('Invalid response from license server.', 'better-click-to-tweet');
            } elseif (isset($license_data->success) && $license_data->success === false) {
                // Handle specific EDD errors
                switch ($license_data->error ?? '') {
                    case 'expired':
                        $message = sprintf(
                            __('Your license key expired on %s.', 'better-click-to-tweet'),
                            date_i18n(get_option('date_format'), strtotime($license_data->expires, current_time('timestamp')))
                        );
                        break;
                    case 'revoked':
                        $message = __('Your license key has been disabled.', 'better-click-to-tweet');
                        break;
                    case 'missing':
                        $message = __('Invalid license.', 'better-click-to-tweet');
                        break;
                    case 'invalid':
                    case 'site_inactive':
                        $message = __('Your license is not active for this URL.', 'better-click-to-tweet');
                        break;
                    case 'item_name_mismatch':
                        $message = sprintf(__('This appears to be an invalid license key for %s.', 'better-click-to-tweet'), $item_name);
                        break;
                    case 'no_activations_left':
                        $message = __('Your license key has reached its activation limit.', 'better-click-to-tweet');
                        break;
                    default:
                        $message = __('An error occurred, please try again.', 'better-click-to-tweet');
                        break;
                }
                 if ($action === 'activate_license') {
                     update_option($license_status_option, 'invalid');
                     update_option($license_key_option, $license_key); // Save the key even if invalid
                 }
            } else {
                // Success
                if ($action === 'activate_license' && isset($license_data->license) && $license_data->license === 'valid') {
                     update_option($license_status_option, 'valid');
                     update_option($license_key_option, $license_key);
                     $message = __('License activated successfully!', 'better-click-to-tweet');
                     $message_type = 'success';
                     // Tell WordPress to check for updates after activation
                     set_site_transient('update_plugins', null);
                } elseif ($action === 'deactivate_license' && isset($license_data->license) && $license_data->license === 'deactivated') {
                     delete_option($license_status_option);
                     // Keep the key in the field unless user clears it
                     // delete_option($license_key_option);
                     $message = __('License deactivated successfully.', 'better-click-to-tweet');
                     $message_type = 'success';
                } else {
                    // Unexpected response structure
                    $message = __('Received an unexpected response from the license server.', 'better-click-to-tweet');
                     if ($action === 'activate_license') {
                         update_option($license_status_option, 'invalid');
                         update_option($license_key_option, $license_key);
                     }
                }
            }
        }

        // Add settings error to display feedback
        add_settings_error('bctt-license-' . $option_base, 'license-feedback', $message, $message_type);
    }

    /**
     * Register settings sections and fields for licenses.
     */
    public function register_license_settings(): void
    {
        $active_addons = Utils::get_active_addons();

        // Only add the section if there are active add-ons
        if (!empty($active_addons)) {
            add_settings_section(
                self::SETTINGS_SECTION_ID,
                __('Add-On Licenses', 'better-click-to-tweet'),
                [$this, 'render_licenses_section_header'],
                self::MAIN_SETTINGS_PAGE_SLUG // Add to main settings page
            );

            // Register settings and add fields for each add-on
            foreach ($active_addons as $plugin_path => $addon_data) {
                $item_name = $addon_data['Name'];
                $item_shortname = Utils::get_addon_shortname($item_name);
                $item_slug = Utils::generate_addon_slug($item_shortname);
                $option_base = 'bctt_' . $item_slug;
                $license_key_option = $option_base . self::LICENSE_OPTION_SUFFIX;

                // Register the setting for the license key itself
                register_setting(
                    'bctt_options_group', // Use the main settings group from AdminManager
                    $license_key_option,
                    [
                        'type' => 'string',
                        'sanitize_callback' => 'sanitize_text_field',
                        'default' => '',
                    ]
                );

                // Add the settings field to display the input and buttons
                add_settings_field(
                    $license_key_option . '_field',
                    esc_html($item_shortname), // Field title is the add-on name
                    [$this, 'render_license_field'],
                    self::MAIN_SETTINGS_PAGE_SLUG,
                    self::SETTINGS_SECTION_ID,
                    ['option_base' => $option_base] // Pass needed data to the render callback
                );
            }
        }
    }

    /**
     * Render the header text for the licenses section.
     */
    public function render_licenses_section_header(): void
    {
        echo '<p>' . esc_html__('Enter the license keys for your Better Click To Tweet add-ons below to receive updates and support.', 'better-click-to-tweet') . '</p>';
        // Display settings errors specific to licenses
        settings_errors('bctt-license-error'); // General errors like empty key
         foreach (Utils::get_active_addons() as $addon_data) { // Errors specific to an addon action
             $item_slug = Utils::generate_addon_slug(Utils::get_addon_shortname($addon_data['Name']));
             settings_errors('bctt-license-bctt_' . $item_slug);
         }
    }

    /**
     * Render the license key input field and activate/deactivate buttons for an add-on.
     *
     * @param array $args Arguments passed from add_settings_field.
     */
    public function render_license_field(array $args): void
    {
        $option_base = $args['option_base']; // e.g., bctt_premium_styles
        $license_key_option = $option_base . self::LICENSE_OPTION_SUFFIX;
        $license_status_option = $option_base . self::STATUS_OPTION_SUFFIX;
        $nonce_action = $license_key_option . '_nonce';
        $activate_button_name = $license_key_option . '_activate';
        $deactivate_button_name = $license_key_option . '_deactivate';

        $license_key = $this->options->get($license_key_option, '');
        $status = $this->options->get($license_status_option);

        ?>
        <input id="<?php echo esc_attr($license_key_option); ?>"
               name="<?php echo esc_attr($license_key_option); ?>"
               type="text"
               class="regular-text"
               value="<?php echo esc_attr($license_key); ?>"
               placeholder="<?php esc_attr_e('Enter your license key', 'better-click-to-tweet'); ?>"
               <?php echo ($status === 'valid') ? 'readonly' : ''; // Make readonly if active ?>
               />
        <div style="padding-top: 5px;">
            <?php wp_nonce_field($nonce_action, $nonce_action); ?>
            <?php if ($status === 'valid') : ?>
                <input type="submit" class="button button-secondary" name="<?php echo esc_attr($deactivate_button_name); ?>"
                       value="<?php esc_attr_e('Deactivate License', 'better-click-to-tweet'); ?>"/>
                <span style="color:green; margin-left: 10px;"><?php esc_html_e('License active!', 'better-click-to-tweet'); ?></span>
            <?php else : ?>
                 <input type="submit" class="button button-secondary" name="<?php echo esc_attr($activate_button_name); ?>"
                        value="<?php esc_attr_e('Activate License', 'better-click-to-tweet'); ?>"/>
                 <?php if ($status === 'invalid') : ?>
                     <span style="color:red; margin-left: 10px;"><?php esc_html_e('License invalid or inactive.', 'better-click-to-tweet'); ?></span>
                 <?php elseif (!empty($license_key)) : ?>
                      <span style="color:orange; margin-left: 10px;"><?php esc_html_e('License inactive.', 'better-click-to-tweet'); ?></span>
                 <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php
    }

     /**
      * Removes the old premium styles license page for backward compatibility.
      */
     public function remove_old_premium_styles_license_page(): void
     {
         if (function_exists('bcttps_license_menu')) {
             remove_submenu_page(self::MAIN_SETTINGS_PAGE_SLUG, 'bcttps-license');
         }
     }

} 