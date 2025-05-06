<?php
declare(strict_types=1);

namespace BCTT;

// Import necessary classes
use BCTT\Admin\AdminManager;
use BCTT\Admin\AdminNotices;
use BCTT\Admin\WelcomeHandler;
use BCTT\Admin\Updater;

if (!defined('ABSPATH')) {
    die('No soup for you. You leave now.');
}

/**
 * Main plugin class for Better Click To Tweet.
 */
final class BetterClickToTweet
{
    /**
     * Plugin version.
     */
    public const VERSION = '6.0.0'; // Example: Update version for refactor

    /**
     * Plugin basename.
     */
    private static string $plugin_basename = '';

    /**
     * Singleton instance.
     * @var BetterClickToTweet|null
     */
    private static ?BetterClickToTweet $instance = null;

    /** @var Options */
    private ?Options $options = null;

    /** @var ShortcodeHandler */
    private ?ShortcodeHandler $shortcode_handler = null;

    /** @var StyleManager */
    private ?StyleManager $style_manager = null;

    /** @var AdminManager */
    private ?AdminManager $admin_manager = null;

    /** @var BlockHandler */
    private ?BlockHandler $block_handler = null;

    /** @var AdminNotices */
    private ?AdminNotices $admin_notices = null;

    /** @var WelcomeHandler */
    private ?WelcomeHandler $welcome_handler = null;

    /** @var Updater */
    private ?Updater $updater = null;

    /**
     * Private constructor - keep minimal.
     */
    private function __construct() {
        // Initialize the plugin basename
        if (empty(self::$plugin_basename) && defined('BCTT_PLUGIN_BASENAME')) {
            self::$plugin_basename = BCTT_PLUGIN_BASENAME;
        }
    }

    /**
     * Get the singleton instance.
     *
     * @return BetterClickToTweet
     */
    public static function get_instance(): BetterClickToTweet
    {
        if (null === self::$instance) {
            self::$instance = new self(); // Assign instance FIRST
            self::$instance->initialize_plugin(); // THEN initialize
        }
        return self::$instance;
    }

    /**
     * Initialize the plugin's components.
     * Contains logic previously in the constructor.
     */
    private function initialize_plugin(): void
    {
        // Moved from constructor
        // $this->define_constants(); // Remove this call
        $this->instantiate_classes();
        $this->register_hooks();
    }

    /**
     * Instantiate core plugin classes.
     */
    private function instantiate_classes(): void
    {
        // Instantiate dependencies (ensure properties are nullable now)
        $this->options = new Options();
        $this->shortcode_handler = new ShortcodeHandler($this->options);
        $this->style_manager = new StyleManager($this->options);
        $this->admin_manager = new AdminManager($this->options);
        $this->block_handler = new BlockHandler($this->options, $this->style_manager, $this->shortcode_handler);
        $this->admin_notices = new AdminNotices($this->options);
        $this->welcome_handler = new WelcomeHandler($this->options);
        $this->updater = new Updater($this->options);

        // Fire the action
        do_action('bctt_loaded', $this);
    }

    /**
     * Register WordPress hooks.
     */
    private function register_hooks(): void
    {
        // Core hooks
        add_action('plugins_loaded', [$this, 'load_textdomain']);

        // Shortcode registration
        add_action('init', [$this->shortcode_handler, 'register']);

        // Style registration and enqueueing
        $this->style_manager->register_hooks(); // Let the manager handle its own hooks

        // Admin area hooks
        $this->admin_manager->register_hooks();

        // Admin notices hooks
        $this->admin_notices->register_hooks();

        // Welcome page / redirect hooks
        $this->welcome_handler->register_hooks();

        // Updater and Licensing hooks
        $this->updater->register_hooks();

        // Block hooks (only if function exists - redundant check?) - BlockHandler internally checks block.json
        if (function_exists('register_block_type')) {
             $this->block_handler->register_hooks();
        }

        // Activation/Deactivation/Uninstall Hooks
        if (defined('BCTT_PLUGIN_FILE')) {
            register_activation_hook(BCTT_PLUGIN_FILE, [$this, 'activate']);
            register_deactivation_hook(BCTT_PLUGIN_FILE, [$this, 'deactivate']);
        } else {
            error_log('BCTT Error: BCTT_PLUGIN_FILE constant not defined for hooks.');
        }
         // Uninstall hook is handled in the main plugin file.

        // Other hooks (Blocks) will be added here later
        // Example: add_action('admin_menu', [$this->admin_manager, 'add_menu_page']);

    }

     /**
      * Load plugin textdomain.
      */
     public function load_textdomain(): void
     {
         load_plugin_textdomain(
             'better-click-to-tweet',
             false,
             dirname(plugin_basename(BCTT_PLUGIN_FILE)) . '/languages/'
         );
     }

    /**
     * Plugin activation logic.
     */
    public function activate(): void
    {
        // Set transient for welcome page redirect
        set_transient('_bctt_activation_redirect', '1', 30); // Use string '1' for consistency

        // Use the Options class
        // Check if the main settings array exists, if not, add defaults.
        $current_settings = $this->options->get('bctt-settings');
        if ($current_settings === false) { // Option doesn't exist yet
            $default_settings = [
                'bctt-twitter-handle' => '',
                'bctt-short-url' => '0'
                // Add other defaults as needed
            ];
            $this->options->add('bctt-settings', $default_settings);
        } else {
            // If upgrading from old individual options, potentially migrate them here.
            // Example migration (only run once, maybe check a version option):
            // $migrated = $this->options->get('bctt_settings_migrated', false);
            // if (!$migrated) {
            //      $old_handle = $this->options->get('bctt-twitter-handle');
            //      $old_shorturl = $this->options->get('bctt-short-url');
            //      if ($old_handle !== false) $current_settings['bctt-twitter-handle'] = $old_handle;
            //      if ($old_shorturl !== false) $current_settings['bctt-short-url'] = $old_shorturl;
            //      $this->options->update('bctt-settings', $current_settings);
            //      $this->options->add('bctt_settings_migrated', '1');
                 // Optionally delete old options: $this->options->delete('bctt-twitter-handle'); ...
            // }
        }

        flush_rewrite_rules(); // Good practice on activation
    }

    /**
     * Plugin deactivation logic.
     */
    public function deactivate(): void
    {
        // Deactivation tasks
        flush_rewrite_rules(); // Good practice on deactivation
    }

    // Prevent cloning and unserialization
    private function __clone() {}
    public function __wakeup() {}

    /**
     * Get the Options handler instance.
     *
     * @return Options
     */
    public function get_options_instance(): Options
    {
        return $this->options;
    }

    /**
     * Get the BlockHandler instance.
     *
     * @return BlockHandler
     */
    public function get_block_handler(): ?BlockHandler
    {
        return $this->block_handler;
    }

    /**
     * Get the plugin basename.
     *
     * @return string The plugin basename.
     */
    public static function get_plugin_basename(): string
    {
        return self::$plugin_basename;
    }
} 