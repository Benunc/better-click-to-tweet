<?php
declare(strict_types=1);

namespace BCTT;

if (!defined('ABSPATH')) {
    die('No soup for you. You leave now.');
}

// use BCTT\Admin\AdminManager; // Not strictly needed in this file if only used for type hinting elsewhere

/**
 * General utility functions for the plugin.
 */
class Utils
{
    /**
     * Get active Better Click To Tweet add-ons with their updater metadata.
     *
     * @return array Array of active add-on data, keyed by a simplified slug (e.g., 'premium-styles').
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

        $discovered_addons = [];
        foreach ($all_plugins as $plugin_path => $plugin_data) {
            if (!in_array($plugin_path, $active_plugin_paths, true)) {
                continue;
            }

            $dirname = strtolower(dirname($plugin_path));
            if ($plugin_path !== BetterClickToTweet::get_plugin_basename() && // Exclude core plugin
                str_contains($dirname, 'better-click-to-tweet') && // Check directory name convention
                isset($plugin_data['AuthorURI']) && str_contains(strtolower($plugin_data['AuthorURI']), 'betterclicktotweet.com')
            ) {
                $discovered_addons[$plugin_path] = $plugin_data; // Store original plugin data
            }
        }

        $addons_with_updater_meta = [];

        // Define metadata for known add-ons
        $known_addons_meta = [
            'better-click-to-tweet-premium-styles/better-click-to-tweet-premium-styles.php' => [
                'slug' => 'premium-styles',
                'constants_prefix' => 'BCTTPS\\',
                'item_name_const' => 'BCTTPS_ITEM_NAME',
                'item_name_default' => 'Premium Styles',
                'version_const' => 'BCTTPS_VERSION',
                'version_default' => '1.0.0',
                'author_const' => 'BCTTPS_AUTHOR', // Optional: if author is in a constant
                'author_default' => 'WP Social Proof',
                'store_url_const' => 'BCTTPS_STORE_URL',
                'store_url_default' => 'https://www.betterclicktotweet.com',
                'license_option_key' => 'bcttps_license_key',
                'main_class' => '\BCTTPS\PremiumStyles',
            ],
            'better-click-to-tweet-utm-tags/better-click-to-tweet-utm-tags.php' => [
                'slug' => 'utm-tags',
                'constants_prefix' => '', // UTM constants are global
                'item_name_const' => 'BCTTUTM_ITEM_NAME',
                'item_name_default' => 'Better Click To Tweet UTM Tags',
                'version_const' => 'BCTTUTM_VERSION',
                'version_default' => '1.0.0',
                'author_default' => 'Ben Meredith',
                'store_url_const' => 'BCTTUTM_STORE_URL',
                'store_url_default' => 'https://www.betterclicktotweet.com',
                'license_option_key' => 'utm_tags_license_key',
                'main_class' => '\Bctt_Utm_Tags',
            ],
            // Add other known add-ons here
        ];

        foreach ($discovered_addons as $plugin_path => $plugin_data) {
            if (isset($known_addons_meta[$plugin_path])) {
                $meta = $known_addons_meta[$plugin_path];
                $prefix = $meta['constants_prefix'];

                // Check if the main class for the add-on exists
                if (!class_exists($meta['main_class'])) {
                    continue; // Skip if main class isn't loaded
                }

                $addons_with_updater_meta[$meta['slug']] = [
                    'file_path'         => WP_PLUGIN_DIR . '/' . $plugin_path,
                    'item_name'         => defined($prefix . $meta['item_name_const']) ? constant($prefix . $meta['item_name_const']) : $meta['item_name_default'],
                    'version'           => defined($prefix . $meta['version_const']) ? constant($prefix . $meta['version_const']) : $meta['version_default'],
                    'author'            => isset($meta['author_const']) && defined($prefix . $meta['author_const']) ? constant($prefix . $meta['author_const']) : $meta['author_default'],
                    'store_url'         => defined($prefix . $meta['store_url_const']) ? constant($prefix . $meta['store_url_const']) : $meta['store_url_default'],
                    'license_option_key' => $meta['license_option_key'],
                    'name'              => $plugin_data['Name'] ?? $meta['item_name_default'], // Original plugin name from WP
                ];
            }
        }
        
        // Example of how a filter could be used in the future for extensibility
        // $addons_with_updater_meta = apply_filters( 'bctt_active_addons_for_updater', $addons_with_updater_meta );

        return $addons_with_updater_meta;
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