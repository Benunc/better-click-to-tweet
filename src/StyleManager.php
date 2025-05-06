<?php
declare(strict_types=1);

namespace BCTT;

if (!defined('ABSPATH')) {
    die('No soup for you. You leave now.');
}

/**
 * Manages plugin stylesheets.
 */
class StyleManager
{
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
     * Register hooks related to styles.
     */
    public function register_hooks(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
        // Add other style-related hooks if necessary (e.g., admin styles, block editor styles)
    }

    /**
     * Enqueue frontend stylesheets.
     */
    public function enqueue_styles(): void
    {
        // Bail if styles are dequeued by an option (e.g., set by premium styles addon)
        if ($this->is_default_styles_dequeued()) {
            // Optional: Could add cleanup logic here if needed, like the old foreach loop in bctt_scripts
            // However, that loop seemed misplaced (deleting 'bcct_' options unrelated to dequeuing).
            // Let's omit it unless there's a specific reason for it.
            return;
        }

        $stylesheet_url = $this->get_stylesheet_url();
        $is_custom = $this->is_custom_stylesheet_active();

        // Use a consistent handle
        $handle = 'bctt-style';

        // Versioning: Use filemtime for custom styles, plugin version for default
        $version = BCTT_VERSION;
        if ($is_custom) {
            $custom_path = $this->get_custom_stylesheet_path();
            if ($custom_path && file_exists($custom_path)) {
                $file_time = filemtime($custom_path);
                $version = $file_time ?: $version; // Use filemtime if available
            }
        }

        wp_register_style($handle, $stylesheet_url, [], (string)$version); // Cast version to string
        wp_enqueue_style($handle);
    }

    /**
     * Check if default styles should be dequeued based on the 'bctt_disable_css' option.
     *
     * This option is typically set by the Premium Styles add-on.
     *
     * @return bool True if default styles should be dequeued, false otherwise.
     */
    public function is_default_styles_dequeued(): bool
    {
        // Check for 'yes' specifically, treat other values or non-existence as false.
        return $this->options->get('bctt_disable_css') === 'yes';
    }

    /**
     * Check if a custom stylesheet exists and should be used.
     *
     * @return bool True if a custom stylesheet exists at the expected path.
     */
    public function is_custom_stylesheet_active(): bool
    {
        $custom_path = $this->get_custom_stylesheet_path();
        return $custom_path && file_exists($custom_path);
    }

    /**
     * Get the path to the custom stylesheet.
     *
     * @return string|null Path to the custom stylesheet or null if upload dir info unavailable.
     */
    public function get_custom_stylesheet_path(): ?string
    {
        $upload_dir = wp_upload_dir();
        if (empty($upload_dir['basedir'])) {
            return null;
        }
        // Ensure consistent naming
        return $upload_dir['basedir'] . '/bcttstyle.css';
    }

    /**
     * Get the URL for the custom stylesheet.
     *
     * @return string|null URL for the custom stylesheet or null if upload dir info unavailable.
     */
    public function get_custom_stylesheet_url(): ?string
    {
        $upload_dir = wp_upload_dir();
        // Check both basedir (for existence check consistency) and baseurl
        if (empty($upload_dir['basedir']) || empty($upload_dir['baseurl'])) {
            return null;
        }
        // Ensure consistent naming
        return $upload_dir['baseurl'] . '/bcttstyle.css';
    }

    /**
     * Get the URL for the default plugin stylesheet.
     *
     * @return string URL for the default stylesheet.
     */
    public function get_default_stylesheet_url(): string
    {
        // Ensure constant is defined
        $base_url = defined('BCTT_PLUGIN_URL') ? BCTT_PLUGIN_URL : plugins_url('/', BCTT_PLUGIN_FILE);
        return $base_url . 'assets/css/styles.css';
    }

    /**
     * Get the URL for the stylesheet to be enqueued (custom or default).
     *
     * @return string The URL of the active stylesheet.
     */
    public function get_stylesheet_url(): string
    {
        $custom_url = $this->get_custom_stylesheet_url();

        // Use custom URL only if it exists and the file is present
        if ($custom_url && $this->is_custom_stylesheet_active()) {
             // Allow filtering the custom URL
             return apply_filters('bctt_custom_stylesheet_url', $custom_url);
        }

        // Otherwise, return the default URL
        $default_url = $this->get_default_stylesheet_url();
        // Allow filtering the default URL
        return apply_filters('bctt_default_stylesheet_url', $default_url);
    }

    // NOTE: Functions like bctt_get_custom_styles_path() and bctt_get_custom_styles_url()
    // from the old code seem redundant if they only return the directory path/url.
    // The functions above handle getting the full path/URL to the specific file (bcttstyle.css).
    // If directory path/URL is needed elsewhere, specific methods can be added.
} 