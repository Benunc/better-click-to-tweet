<?php
declare(strict_types=1);

namespace BCTT\Admin;

use BCTT\Options; // Although not strictly needed by current logic, good practice to include

if (!defined('ABSPATH')) {
    die('No soup for you. You leave now.');
}

/**
 * Handles displaying admin notices and nags.
 */
class AdminNotices
{
    private const PREMIUM_STYLES_SLUG = 'better-click-to-tweet-premium-styles/better-click-to-tweet-premium-styles.php';
    private const PREMIUM_STYLES_DISMISS_META = 'bctt_has_dismissed_nag';
    private const PREMIUM_STYLES_DISMISS_QUERY_VAR = 'bctt_dismiss_style_nag'; // Renamed for clarity

    // UTM Nag constants (keeping logic, but hooks are off by default)
    private const UTM_TAGS_SLUG = 'better-click-to-tweet-utm-tags/better-click-to-tweet-utm-tags.php';
    private const UTM_TAGS_DISMISS_META = 'bctt_has_dismissed_utm_nag';
    private const UTM_TAGS_DISMISS_QUERY_VAR = 'bctt_dismiss_utm_nag';

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
     * Register admin notice hooks.
     */
    public function register_hooks(): void
    {
        // Handle dismissal actions early, before headers are sent.
        add_action('admin_init', [$this, 'handle_notice_dismissal']);

        // Display notices.
        add_action('admin_notices', [$this, 'display_notices']);

        // Enqueue styles needed for notices (only when notices might be shown).
        add_action('admin_print_styles', [$this, 'enqueue_notice_styles']);
    }

    /**
     * Check GET parameters and handle notice dismissal requests.
     */
    public function handle_notice_dismissal(): void
    {
        $user_id = get_current_user_id();
        if (!$user_id) return;

        // Check for Premium Styles dismissal
        // Nonce verification recommended here for security
        if (isset($_GET[self::PREMIUM_STYLES_DISMISS_QUERY_VAR]) && (int) $_GET[self::PREMIUM_STYLES_DISMISS_QUERY_VAR] === 1) {
             // Check nonce if implemented: if (!wp_verify_nonce($_GET['_wpnonce'], 'bctt_dismiss_style_nag_nonce')) return;
             update_user_meta($user_id, self::PREMIUM_STYLES_DISMISS_META, 'true');
             // Redirect to remove query arg (optional but cleaner)
             // wp_safe_redirect(remove_query_arg([self::PREMIUM_STYLES_DISMISS_QUERY_VAR, '_wpnonce']));
             // exit;
        }

        // Check for UTM Tags dismissal (if hooks were active)
        // Nonce verification recommended here for security
        if (isset($_GET[self::UTM_TAGS_DISMISS_QUERY_VAR]) && (int) $_GET[self::UTM_TAGS_DISMISS_QUERY_VAR] === 1) {
            // Check nonce if implemented: if (!wp_verify_nonce($_GET['_wpnonce'], 'bctt_dismiss_utm_nag_nonce')) return;
            update_user_meta($user_id, self::UTM_TAGS_DISMISS_META, 'true');
            // Redirect to remove query arg
            // wp_safe_redirect(remove_query_arg([self::UTM_TAGS_DISMISS_QUERY_VAR, '_wpnonce']));
            // exit;
        }
    }

    /**
     * Display relevant admin notices.
     */
    public function display_notices(): void
    {
        $screen = get_current_screen();
        if (!$screen) return;

        // Only show on specific screens and for capable users
        // $screen->parent_file check is less reliable than $screen->id
        if ($screen->id === 'plugins' && current_user_can('install_plugins')) {
            $this->maybe_display_premium_styles_nag();
            // $this->maybe_display_utm_tags_nag(); // Keep disabled unless needed
        }
        // Add other notice checks here if needed for different screens/conditions
    }

    /**
     * Enqueue styles specifically for admin notices.
     */
     public function enqueue_notice_styles(): void
     {
         // Only enqueue if a notice *might* be shown to avoid unnecessary load
         $screen = get_current_screen();
         if (!$screen || $screen->id !== 'plugins' || !current_user_can('install_plugins')) {
             return;
         }

         // Check dismissal status without rendering the whole notice
         $user_id = get_current_user_id();
         $show_styles_nag = !is_plugin_active(self::PREMIUM_STYLES_SLUG) && !get_user_meta($user_id, self::PREMIUM_STYLES_DISMISS_META, true);
         // $show_utm_nag = !is_plugin_active(self::UTM_TAGS_SLUG) && !defined('BCTTUTM_VERSION') && !get_user_meta($user_id, self::UTM_TAGS_DISMISS_META, true);

         if ($show_styles_nag /* || $show_utm_nag */) {
             // Output inline styles (or enqueue a dedicated CSS file)
             // Using inline styles as per the original code
             echo '<style>' . $this->get_nag_styles() . '</style>';
         }
     }

    /**
     * Check conditions and display the Premium Styles add-on nag.
     */
    private function maybe_display_premium_styles_nag(): void
    {
        if (is_plugin_active(self::PREMIUM_STYLES_SLUG)) {
            return;
        }

        $user_id = get_current_user_id();
        if (!$user_id || get_user_meta($user_id, self::PREMIUM_STYLES_DISMISS_META, true)) {
            return;
        }

        // Removed check for bctt_add_custom_style_option() as it seemed outdated.

        $this->render_premium_styles_nag();
    }

    /**
     * Render the HTML for the Premium Styles nag.
     */
    private function render_premium_styles_nag(): void
    {
        $dismiss_url = wp_nonce_url( // Add nonce for security
            add_query_arg(self::PREMIUM_STYLES_DISMISS_QUERY_VAR, '1', admin_url('plugins.php')),
            'bctt_dismiss_style_nag_nonce' // Action name for nonce
        );
        $image_url = BCTT_PLUGIN_URL . 'assets/img/premium_style.png';
        $purchase_url = 'http://benlikes.us/bcttnag';

        ?>
        <div class="notice notice-info is-dismissible bctt-admin-nag bctt-style-nag">
            <a href="<?php echo esc_url($dismiss_url); ?>" class="bctt-nag-dismiss-link" title="<?php esc_attr_e('Dismiss this notice', 'better-click-to-tweet'); ?>">
                 <span class="dashicons dashicons-dismiss"></span>
                 <span class="screen-reader-text"><?php esc_html_e('Dismiss this notice', 'better-click-to-tweet'); ?></span>
            </a>
             <div class="bctt-nag-content">
                 <img class="bctt-nag-photo" src="<?php echo esc_url($image_url); ?>" alt="Premium Styles Preview"/>
                <h3 class="bctt-nag-header"><?php esc_html_e('Add Premium Style to your Better Click To Tweet boxes!', 'better-click-to-tweet'); ?></h3>
                <p class="bctt-nag-copy"><?php esc_html_e('Choose from multiple options when styling your Better Click To Tweet boxes, with no code.', 'better-click-to-tweet'); ?></p>
                <ul class="bctt-nag-list">
                    <li><?php esc_html_e('Make your Better Click To Tweet boxes stand out.', 'better-click-to-tweet'); ?></li>
                    <li><?php esc_html_e('Get more engagement!', 'better-click-to-tweet'); ?></li>
                    <li><?php esc_html_e('Support the development of Better Click To Tweet!', 'better-click-to-tweet'); ?></li>
                </ul>
                <p class="bctt-nag-copy">
                    <a class="bctt-nag-purchase-link" href="<?php echo esc_url($purchase_url); ?>" target="_blank" rel="noopener noreferrer">
                        <?php esc_html_e('Purchase it today', 'better-click-to-tweet'); ?>
                    </a> <?php esc_html_e('Save 8% with the code NAGSareTHEbest', 'better-click-to-tweet'); ?>
                 </p>
             </div>
        </div>
        <?php
    }

    // --- UTM Nag Methods (Disabled by default) ---

    /**
     * Check conditions and display the UTM Tags add-on nag.
     */
    private function maybe_display_utm_tags_nag(): void
    {
        if (is_plugin_active(self::UTM_TAGS_SLUG) || defined('BCTTUTM_VERSION')) {
            return;
        }

        $user_id = get_current_user_id();
        if (!$user_id || get_user_meta($user_id, self::UTM_TAGS_DISMISS_META, true)) {
            return;
        }

        $this->render_utm_tags_nag();
    }

    /**
     * Render the HTML for the UTM Tags nag.
     */
    private function render_utm_tags_nag(): void
    {
        $dismiss_url = wp_nonce_url(
             add_query_arg(self::UTM_TAGS_DISMISS_QUERY_VAR, '1', admin_url('plugins.php')),
             'bctt_dismiss_utm_nag_nonce'
         );
        $image_url = BCTT_PLUGIN_URL . 'assets/img/utm-tags.jpg';
        $purchase_url = 'http://benlikes.us/bcttutmnag';

         ?>
        <div class="notice notice-info is-dismissible bctt-admin-nag bctt-utm-nag">
             <a href="<?php echo esc_url($dismiss_url); ?>" class="bctt-nag-dismiss-link" title="<?php esc_attr_e('Dismiss this notice', 'better-click-to-tweet'); ?>">
                 <span class="dashicons dashicons-dismiss"></span>
                 <span class="screen-reader-text"><?php esc_html_e('Dismiss this notice', 'better-click-to-tweet'); ?></span>
            </a>
            <div class="bctt-nag-content">
                 <img class="bctt-nag-photo" src="<?php echo esc_url($image_url); ?>" alt="UTM Tags Preview"/>
                <h3 class="bctt-nag-header"><?php esc_html_e('NEW: Track how well your Better Click To Tweets are performing!', 'better-click-to-tweet'); ?></h3>
                <p class="bctt-nag-copy"><?php esc_html_e('Add UTM Codes to the URL that readers on X use to click back to your site!', 'better-click-to-tweet'); ?></p>
                <ul class="bctt-nag-list">
                    <li><?php esc_html_e('Determine which BCTT boxes are converting best.', 'better-click-to-tweet'); ?></li>
                    <li><?php esc_html_e('Configurable site-wide and on individual boxes.', 'better-click-to-tweet'); ?></li>
                    <li><?php esc_html_e('Works with the block editor and with shortcodes.', 'better-click-to-tweet'); ?></li>
                </ul>
                <p class="bctt-nag-copy">
                    <a class="bctt-nag-purchase-link" href="<?php echo esc_url($purchase_url); ?>" target="_blank" rel="noopener noreferrer">
                        <?php esc_html_e('Purchase it today', 'better-click-to-tweet'); ?>
                    </a> <?php esc_html_e('Save 8% with the code NAGSareTHEbest', 'better-click-to-tweet'); ?>
                </p>
            </div>
        </div>
         <?php
    }

    /**
     * Get the inline CSS for the nags.
     *
     * @return string CSS rules.
     */
    private function get_nag_styles(): string
    {
        // Consolidate styles from both nags
        return ' ' .
        '.bctt-admin-nag .bctt-nag-dismiss-link { float: right; text-decoration: none; color: #555; } ' .
        '.bctt-admin-nag .bctt-nag-dismiss-link .dashicons { font-size: 16px; vertical-align: middle; } ' .
        '.bctt-admin-nag .bctt-nag-content { overflow: hidden; padding: 1px 12px; } ' .
        '.bctt-admin-nag .bctt-nag-header { font-size: 1.1em; font-weight: bold; margin: 0.5em 0; } ' .
        '.bctt-admin-nag .bctt-nag-copy { margin: 0.5em 0; } ' .
        '.bctt-admin-nag .bctt-nag-list { list-style-type: none; margin: 0.5em 0 0.5em 2em; } ' .
        '.bctt-admin-nag .bctt-nag-list li { margin-bottom: 0.3em; } ' .
        '.bctt-admin-nag .bctt-nag-list li:before { content: "\f155"; font-family: dashicons; display: inline-block; -webkit-font-smoothing: antialiased; font-size: 16px; line-height: 1; color: #46b450; margin-right: 10px; vertical-align: middle; } ' .
        '.bctt-admin-nag .bctt-nag-purchase-link { font-size: large; } ' .
        '.bctt-admin-nag .bctt-nag-photo { float: right; margin: 5px 0 5px 15px; max-height: 105px; max-width: 40%; border: 1px solid #ccc; } ' .
        '@media screen and (max-width: 782px) { .bctt-admin-nag .bctt-nag-photo { float: none; max-width: 98%; margin: 10px 0; display: block; } } ';
    }

} 