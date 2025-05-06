<?php
declare(strict_types=1);

namespace BCTT\Admin;

use BCTT\Options;

if (!defined('ABSPATH')) {
    die('No soup for you. You leave now.');
}

/**
 * Handles the welcome/onboarding wizard.
 */
class WelcomeHandler
{
    private const ACTIVATION_TRANSIENT = '_bctt_activation_redirect';
    private const MENU_SLUG = 'bctt-welcome';
    private const SETUP_STEP = 'bctt-setup';
    private const USAGE_STEP = 'bctt-usage';

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
     * Register hooks related to the welcome wizard.
     */
    public function register_hooks(): void
    {
        // Redirect hook
        add_action('admin_init', [$this, 'handle_redirect']);
        // Add welcome page
        add_action('admin_menu', [$this, 'add_welcome_page']);
        // Handle form submission from welcome page
        add_action('admin_init', [$this, 'handle_form_submission']);
        // Enqueue assets for welcome page
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    /**
     * Check for activation transient and redirect if appropriate.
     */
    public function handle_redirect(): void
    {
        if (get_transient(self::ACTIVATION_TRANSIENT)) {
            // Always delete transient after check
            delete_transient(self::ACTIVATION_TRANSIENT);

            $do_redirect = true;
            $current_page = $_GET['page'] ?? null;

            // Bailout redirect during these events.
            if (wp_doing_ajax() || is_network_admin() || !current_user_can('manage_options')) {
                $do_redirect = false;
            }
            // Bailout redirect on welcome page or during bulk activation.
            if ($current_page === self::MENU_SLUG || isset($_GET['activate-multi'])) {
                $do_redirect = false;
            }

            if ($do_redirect) {
                wp_safe_redirect($this->get_step_url(self::SETUP_STEP));
                exit;
            }
        }
    }

    /**
     * Add the hidden dashboard page for the welcome wizard.
     */
    public function add_welcome_page(): void
    {
        add_dashboard_page(
            __('Welcome to Better Click To Tweet', 'better-click-to-tweet'), // Page title
            '', // Menu title (hidden)
            'manage_options', // Capability
            self::MENU_SLUG, // Menu slug
            [$this, 'render_welcome_page'] // Callback
        );
    }

    /**
     * Handle form submissions from the welcome page (e.g., saving Twitter handle).
     */
    public function handle_form_submission(): void
    {
        // Only run on our welcome page and if form is submitted
        if (($_GET['page'] ?? null) !== self::MENU_SLUG || !isset($_POST['save-the-handle'])) {
            return;
        }

        // Check capability and nonce
        if (!current_user_can('manage_options') || !wp_verify_nonce($_POST['save-the-handle'] ?? '', 'change-handle')) {
            wp_die(esc_html__('Security check failed.', 'better-click-to-tweet'));
        }

        // Update twitter handle in the main settings array
        if (isset($_POST['bctt-twitter'])) {
             $settings = $this->options->get('bctt-settings', []);
             if (!is_array($settings)) $settings = []; // Ensure array

             $handle = sanitize_text_field(str_replace(['@', ' '], '', $_POST['bctt-twitter']));
             $settings['bctt-twitter-handle'] = $handle;

             $this->options->update('bctt-settings', $settings);

             // Redirect to the next step
             wp_safe_redirect($this->get_step_url(self::USAGE_STEP));
             exit;
        }
    }

    /**
     * Render the welcome page content.
     */
    public function render_welcome_page(): void
    {
        // Check capability just in case
        if (!current_user_can('manage_options')) {
             wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'better-click-to-tweet'));
        }

        // Define view paths relative to plugin root
        $view_base_path = BCTT_PLUGIN_DIR . 'includes/views/welcome/';
        $header_path = $view_base_path . '_header.php';
        $footer_path = $view_base_path . '_footer.php';
        $current_step = $this->get_current_step();
        $step_view_path = '';

        // Determine step content view
        switch ($current_step) {
            case 'bctt-setup':
                $step_view_path = $view_base_path . '_welcome.php';
                break;
            case 'bctt-usage':
                $step_view_path = $view_base_path . '_usage.php';
                break;
            case 'bctt-content':
                $step_view_path = $view_base_path . '_content.php';
                break;
            case 'bctt-grow':
                $step_view_path = $view_base_path . '_grow.php';
                break;
            case 'bctt-done':
                $step_view_path = $view_base_path . '_done.php';
                break;
            default:
                 $step_view_path = $view_base_path . '_welcome.php';
                break;
        }

        // Render the page
        if (file_exists($header_path)) include $header_path;
        if (file_exists($step_view_path)) include $step_view_path;
        if (file_exists($footer_path)) include $footer_path;

        // Note: The original BCTT_Welcome class had an `exit;` here.
        // This is generally bad practice inside an admin page callback unless absolutely necessary.
        // WordPress expects the callback to finish normally.
    }

    /**
     * Enqueue styles and scripts for the welcome page.
     *
     * @param string $hook_suffix The current admin page hook.
     */
    public function enqueue_assets(string $hook_suffix): void
    {
        // Only load on the welcome page
        // Hook for page added via add_dashboard_page is `dashboard_page_{menu_slug}`
        if ($hook_suffix !== 'dashboard_page_' . self::MENU_SLUG) {
            return;
        }

        // Ensure asset paths are correct relative to plugin root
        $utility_css_url = BCTT_PLUGIN_URL . 'assets/css/utility.css';
        $welcome_css_url = BCTT_PLUGIN_URL . 'assets/css/bctt-welcome.css';

        wp_enqueue_style('bctt-welcome-utilities', $utility_css_url, [], BCTT_VERSION);
        wp_enqueue_style('bctt-welcome-styles', $welcome_css_url, ['bctt-welcome-utilities'], BCTT_VERSION);

        // Enqueue scripts if needed
    }

    // --- Helper methods adapted from bctt-welcome-functions.php ---

    /**
     * Get the current wizard step.
     *
     * @return string
     */
    private function get_current_step(): string
    {
        return isset($_GET['step']) ? sanitize_key($_GET['step']) : self::SETUP_STEP;
    }

    /**
     * Get the welcome wizard step URL.
     *
     * @param string $step The step slug.
     * @return string URL for the step.
     */
    public function get_step_url(string $step): string // Public for use in views
    {
        return admin_url('admin.php?page=' . self::MENU_SLUG . '&step=' . sanitize_key($step));
    }

    /**
     * Get the CSS class for a navigation step.
     *
     * @param string $step The step slug being checked.
     * @return string HTML class attribute string (e.g., 'class="active"').
     */
    public function get_step_class(string $step): string // Public for use in views
    {
        $current_step = $this->get_current_step();
        $class = '';

        // Define steps after the current one for 'done' status
        $steps_order = ['bctt-setup', 'bctt-usage', 'bctt-content', 'bctt-grow', 'bctt-done'];
        $current_step_index = array_search($current_step, $steps_order);
        $step_index = array_search($step, $steps_order);

        if ($step_index === false || $current_step_index === false) {
            return ''; // Invalid step
        }

        if ($step === $current_step) {
            $class = 'active';
        } elseif ($step_index < $current_step_index) {
            $class = 'done';
        }

        return $class ? sprintf('class="%s"', esc_attr($class)) : '';
    }
} 