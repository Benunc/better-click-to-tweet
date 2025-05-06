<?php
/**
 * Plugin Name: Better Click To Tweet
 * Description: Add styled boxes to posts and pages so that readers can share your content on X. Increase engagement by asking for it. All the features of a premium plugin, for FREE!
 * Version: 6.0.0
 * Author: Ben Meredith
 * Author URI: https://www.betterclicktotweet.com
 * Plugin URI: https://wordpress.org/plugins/better-click-to-tweet/
 * License: GPL2
 * Text Domain: better-click-to-tweet
 * Requires PHP: 8.0
 */

// Exit if accessed directly.
defined('ABSPATH') or die("No soup for you. You leave now.");

// === Define Core Constants ===
$plugin_data = get_file_data(__FILE__, ['Version' => 'Version'], false);
define('BCTT_VERSION', $plugin_data['Version'] ?? '6.0.0'); // Ensure this matches header

if (!defined('BCTT_PLUGIN_FILE')) {
    define('BCTT_PLUGIN_FILE', __FILE__);
}
if (!defined('BCTT_PLUGIN_DIR')) {
    define('BCTT_PLUGIN_DIR', plugin_dir_path(__FILE__));
}
if (!defined('BCTT_PLUGIN_URL')) {
    define('BCTT_PLUGIN_URL', plugin_dir_url(__FILE__));
}
// === End Core Constants ===

// --- PSR-4 Autoloader ---
spl_autoload_register(function ($class) {
    // Project-specific namespace prefix
    $prefix = 'BCTT\\';

    // Base directory for the namespace prefix
    $base_dir = __DIR__ . '/src/';

    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // No, move to the next registered autoloader
        return;
    }

    // Get the relative class name
    $relative_class = substr($class, $len);

    // Replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // If the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});
// --- End Autoloader ---


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * initiating the plugin class is the only step needed.
 */
function bctt_run_plugin() {
    // error_log('BCTT Trace: Running bctt_run_plugin directly.'); // Remove log
    \BCTT\BetterClickToTweet::get_instance();
}

// Reinstate the hook:
add_action('plugins_loaded', 'bctt_run_plugin');

// --- Uninstall Hook ---
// Needs to be outside the class. It references the old function name for now.
// We'll create a dedicated uninstall.php later or a static method if preferred.
register_uninstall_hook(__FILE__, 'bctt_on_uninstall');

/**
 * Fired when the plugin is uninstalled.
 *
 * Removes the twitter handle option and the custom css option.
 *
 * @since 2.0
 */
function bctt_on_uninstall() {
    // We need to use the Options class here if we want to be consistent,
    // but uninstall hooks run in a separate process where the plugin
    // might not be fully loaded. Using direct delete_option is safer.
    delete_option('bctt-settings'); // Delete the main settings array
    delete_option('bctt_disable_css'); // Still potentially used by addons
    delete_option('_bctt_activation_redirect'); // Delete transient
    // Delete user meta (loop through users? maybe too intensive for uninstall?)
    // Example: delete_metadata('user', 0, 'bctt_has_dismissed_nag', '', true);
    // Example: delete_metadata('user', 0, 'bctt_has_dismissed_utm_nag', '', true);

    // Consider deleting custom CSS file if created?
    // $style_manager = new \BCTT\StyleManager(new \BCTT\Options()); // Requires loading classes...
    // $custom_path = $style_manager->get_custom_stylesheet_path();
    // if ($custom_path && file_exists($custom_path)) { @unlink($custom_path); }
}


