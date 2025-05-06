<?php
declare(strict_types=1);

namespace BCTT\Admin;

use BCTT\Options;

if (!defined('ABSPATH')) {
    die('No soup for you. You leave now.');
}

/**
 * Manages Admin Area functionality like Settings Pages and Menus.
 */
class AdminManager
{
    private Options $options;
    private string $plugin_base_name;
    private string $main_menu_slug;

    /**
     * Constructor.
     *
     * @param Options $options Plugin options instance.
     */
    public function __construct(Options $options)
    {
        $this->options = $options;
        $this->main_menu_slug = 'better-click-to-tweet';

        // plugin_basename() requires the main plugin file path, ensure BCTT_PLUGIN_FILE is defined
        if (!defined('BCTT_PLUGIN_FILE')) {
             // This should ideally not happen if constants are defined before instantiation
             // Maybe throw an exception or log a fatal error?
             // For now, fallback to a potentially incorrect path to avoid fatal error in constructor.
             // Consider passing the base name or file path into the constructor instead.
             trigger_error('BCTT_PLUGIN_FILE constant not defined when AdminManager constructed.', E_USER_WARNING);
             $this->plugin_base_name = 'better-click-to-tweet/better-click-to-tweet.php'; // Fallback
        } else {
             $this->plugin_base_name = plugin_basename(BCTT_PLUGIN_FILE);
        }
    }

    /**
     * Register admin-related hooks.
     */
    public function register_hooks(): void
    {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
        add_filter('plugin_action_links_' . $this->plugin_base_name, [$this, 'add_plugin_settings_link']);

        // Hook for admin notices (we'll create the Notices class later)
        // add_action('admin_notices', [$this->notices, 'display_notices']);

        // Hook for welcome page redirect (we'll handle this later)
        // add_action('admin_init', [$this->welcome_handler, 'redirect']);

        // Hook to load admin-specific assets
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
    }

    /**
     * Add the plugin settings page under the main WordPress Settings menu.
     */
    public function add_settings_page(): void
    {
        add_options_page(
            __('Better Click To Tweet Settings', 'better-click-to-tweet'),
            __('Better Click To Tweet', 'better-click-to-tweet'),
            'manage_options',
            $this->main_menu_slug,
            [$this, 'render_settings_page']
        );
    }

    /**
     * Register plugin settings using the Settings API.
     */
    public function register_settings(): void
    {
        // Register the main setting group
        register_setting(
            'bctt_options_group', // Option group name (used in settings_fields())
            'bctt-settings', // Option name stored in wp_options
            [$this, 'sanitize_settings'] // Sanitization callback
        );

        // Add settings sections
        add_settings_section(
            'bctt_general_section',
            __('General Settings', 'better-click-to-tweet'),
            null, // No description callback needed for this section
            $this->main_menu_slug // Page slug where this section appears
        );

        // Add settings fields
        add_settings_field(
            'bctt-twitter-handle',
            __('Your Twitter Handle', 'better-click-to-tweet'),
            [$this, 'render_twitter_handle_field'], // Render callback
            $this->main_menu_slug, // Page slug
            'bctt_general_section' // Section ID
            // Optional args array can be passed to render callback
        );

        add_settings_field(
            'bctt-short-url',
            __('Use Short URL?', 'better-click-to-tweet'),
            [$this, 'render_short_url_field'], // Render callback
            $this->main_menu_slug, // Page slug
            'bctt_general_section' // Section ID
        );

        // Add other settings fields as needed (e.g., for URL shortening services, etc.)

        // Note: The 'bctt_disable_css' option used by Premium Styles isn't managed here,
        // as it's typically controlled by the addon itself.
    }

    /**
     * Sanitize settings before saving.
     *
     * @param array $input Raw input data from the form.
     * @return array Sanitized data.
     */
    public function sanitize_settings(array $input): array
    {
        $sanitized_input = [];

        if (isset($input['bctt-twitter-handle'])) {
            // Remove @, spaces, and sanitize
            $handle = sanitize_text_field(str_replace(['@', ' '], '', $input['bctt-twitter-handle']));
            $sanitized_input['bctt-twitter-handle'] = $handle;
        }

        if (isset($input['bctt-short-url'])) {
            // Ensure it's '1' or '0'
            $sanitized_input['bctt-short-url'] = ($input['bctt-short-url'] === '1') ? '1' : '0';
        }

        // Sanitize other fields...

        // Important: Merge with existing options to avoid losing unset checkbox values
        // or settings managed elsewhere.
        $existing_options = $this->options->get('bctt-settings', []);
        if (!is_array($existing_options)) $existing_options = []; // Ensure it's an array

        $output = array_merge($existing_options, $sanitized_input);

        return $output;
        // Note: We are storing options in a single array 'bctt-settings'.
        // The old code used individual options ('bctt-twitter-handle', 'bctt-short-url').
        // We need to update how options are retrieved elsewhere (like in ShortcodeHandler)
        // OR decide to stick with individual options.
        // For now, I'll stick with the Settings API storing into 'bctt-settings'
        // and adjust retrieval later.
    }

    // --- Field Rendering Callbacks ---

    /**
     * Render the Twitter Handle input field.
     */
    public function render_twitter_handle_field(): void
    {
        $options = $this->options->get('bctt-settings', []);
        $value = $options['bctt-twitter-handle'] ?? '';
        echo '<input type="text" name="bctt-settings[bctt-twitter-handle]" value="' . esc_attr($value) . '" class="regular-text" placeholder="' . esc_attr__('YourTwitterHandle', 'better-click-to-tweet') . '" />';
        echo '<p class="description">' . esc_html__('Enter your Twitter handle without the @ symbol.', 'better-click-to-tweet') . '</p>';
    }

    /**
     * Render the Short URL checkbox field.
     */
    public function render_short_url_field(): void
    {
        $options = $this->options->get('bctt-settings', []);
        $checked = checked('1', $options['bctt-short-url'] ?? '0', false);
        echo '<label><input type="checkbox" name="bctt-settings[bctt-short-url]" value="1" ' . $checked . ' /> ';
        echo esc_html__('Use WordPress shortlink? (Requires a shortlink provider like Jetpack or YOURLS configured)', 'better-click-to-tweet') . '</label>';
    }


    /**
     * Render the main settings page HTML.
     *
     * This should ideally be moved to a separate template file.
     */
    public function render_settings_page(): void
    {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'better-click-to-tweet'));
        }

        // Basic structure - load template ideally
        echo '<div class="wrap bctt-settings-wrap">';
        echo '<h1>' . esc_html(get_admin_page_title()) . '</h1>';

        // Render the clarification box
        $this->render_name_clarification_box();

        echo '<form action="options.php" method="post">';

        // Output security fields for the registered setting group
        settings_fields('bctt_options_group');

        // Output setting sections and fields for the menu slug
        do_settings_sections($this->main_menu_slug);

        // Output save settings button
        submit_button(__('Save Settings', 'better-click-to-tweet'));

        echo '</form>';
        echo '</div>'; // .wrap
    }

    /**
     * Renders the informational box explaining the plugin name.
     */
    private function render_name_clarification_box(): void
    {
        ?>
        <div style="background-color: #f0f0f0; border: 1px solid #ccd0d4; color: #333; padding: 15px 20px; margin-bottom: 20px; border-radius: 4px;">
            <h2 style="color:#333; margin-top: 0; font-size: 1.2em;"><?php esc_html_e('Why is this still called "Better Click To Tweet" and not something about X?', 'better-click-to-tweet'); ?></h2>
            <p><?php esc_html_e('You may have noticed that this plugin has not been renamed to take into account the total rebrand of Twitter.com to X.com. The reason is simple, and threefold:', 'better-click-to-tweet'); ?></p>
            <ol style="margin-left: 20px;">
                <li><?php esc_html_e('I don\'t have a ton of developers sitting around with nothing to do. (or any piles of cash to pay someone)', 'better-click-to-tweet'); ?></li>
                <li><?php esc_html_e('The wider world still calls the act of posting on X "Tweeting" and will for some time. Maybe long enough for me to get a pile of cash or several bored developers with a weekend to spare?', 'better-click-to-tweet'); ?></li>
                <li><?php esc_html_e('The "brand name" itself is embedded into lots of places in the code (and the URL that I sell premium addons, etc) and would be tough to change.', 'better-click-to-tweet'); ?></li>
            </ol>
            <p><?php esc_html_e('I\'m committed to keeping this plugin functional as long as the X platform allows posting via web intents. And I\'ll still call it "tweeting," like a curmudgeon.', 'better-click-to-tweet'); ?></p>
            <p><?php printf(
                /* translators: %s: Link to betterclicktotweet.com */
                esc_html__('If you have any questions whatsoever, don\'t be a stranger. Reach out over at %s and I\'m happy to clarify!', 'better-click-to-tweet'),
                '<a href="https://www.betterclicktotweet.com" target="_blank" rel="noopener noreferrer">' . esc_html__('My website', 'better-click-to-tweet') . '</a>'
            ); ?></p>
            <p><?php esc_html_e('Happy Tweeting / Posting to X!', 'better-click-to-tweet'); ?></p>
        </div>
        <?php
    }

    /**
     * Add a settings link to the plugin actions row.
     *
     * @param array $links Existing action links.
     * @return array Modified action links.
     */
    public function add_plugin_settings_link(array $links): array
    {
        $settings_text = esc_html__('Settings', 'better-click-to-tweet');
        $settings_link = '<a href="' . esc_url(admin_url('options-general.php?page=' . $this->main_menu_slug)) . '">' . $settings_text . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    /**
     * Enqueue scripts and styles needed for the admin area.
     *
     * @param string $hook_suffix The current admin page hook.
     */
    public function enqueue_admin_assets(string $hook_suffix): void
    {
        // Hook for pages added via add_options_page is settings_page_{menu_slug}
        $plugin_page_hook = 'settings_page_' . $this->main_menu_slug;

        if ($hook_suffix === $plugin_page_hook) {
             // Example: Enqueue admin CSS
             $css_url = BCTT_PLUGIN_URL . 'assets/css/admin-styles.css';
             wp_enqueue_style('bctt-admin-styles', $css_url, [], BCTT_VERSION);

            // Example: Enqueue admin JS
            // $js_url = BCTT_PLUGIN_URL . 'assets/js/admin-script.js';
            // wp_enqueue_script('bctt-admin-script', $js_url, ['jquery'], BCTT_VERSION, true);
            // wp_localize_script('bctt-admin-script', 'bctt_admin_data', [
            //     'ajax_url' => admin_url('admin-ajax.php'),
            //     'nonce'    => wp_create_nonce('bctt_admin_nonce')
            // ]);
        }
        // Add checks for submenu pages if needed
    }
} 