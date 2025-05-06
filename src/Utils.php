<?php
declare(strict_types=1);

namespace BCTT;

if (!defined('ABSPATH')) {
    die('No soup for you. You leave now.');
}

/**
 * General utility functions for the plugin.
 */
class Utils
{
    /**
     * Get active Better Click To Tweet add-ons.
     *
     * Filters installed plugins to find active BCTT add-ons based on naming conventions.
     *
     * @return array Array of active add-on data, keyed by plugin path.
     */
    public static function get_active_addons(): array
    {
        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $all_plugins = get_plugins();
        $active_plugin_paths = (array) get_option('active_plugins', []);

        if (is_multisite()) {
            $network_activated_plugin_paths = array_keys(get_site_option('active_sitewide_plugins', []));
            $active_plugin_paths = array_merge($active_plugin_paths, $network_activated_plugin_paths);
        }

        $active_addons = [];
        foreach ($all_plugins as $plugin_path => $plugin_data) {
            // Check if active
            if (!in_array($plugin_path, $active_plugin_paths, true)) {
                continue;
            }

            // Check if it's a BCTT add-on (excluding core)
            $dirname = strtolower(dirname($plugin_path));
            if ($plugin_path !== 'better-click-to-tweet/better-click-to-tweet.php' &&
                str_contains($dirname, 'better-click') && // Looser check, perhaps check Author URI too?
                isset($plugin_data['AuthorURI']) && str_contains($plugin_data['AuthorURI'], 'betterclicktotweet.com')
            ) {
                // Add status and type for consistency if needed elsewhere, though we only return active addons
                $plugin_data['Status'] = 'active';
                $plugin_data['Type'] = 'add-on';
                $active_addons[$plugin_path] = $plugin_data;
            }
        }

        return $active_addons;
    }

    /**
     * Get the shortname for a BCTT add-on.
     *
     * @param string $addon_full_name Full name (e.g., "Better Click To Tweet Premium Styles").
     * @return string Short name (e.g., "Premium Styles").
     */
    public static function get_addon_shortname(string $addon_full_name): string
    {
        // Remove prefix and trim whitespace
        $shortname = str_replace('Better Click To Tweet ', '', $addon_full_name);
        return trim($shortname);
    }

    /**
     * Generate a slug from an add-on shortname.
     *
     * @param string $shortname Add-on short name (e.g., "Premium Styles").
     * @return string Slug version (e.g., "premium_styles").
     */
    public static function generate_addon_slug(string $shortname): string
    {
        $slug = str_replace(' ', '_', strtolower($shortname));
        return sanitize_key($slug); // Use sanitize_key for robustness
    }
} 