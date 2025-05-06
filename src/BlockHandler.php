<?php
declare(strict_types=1);

namespace BCTT;

if (!defined('ABSPATH')) {
    die('No soup for you. You leave now.');
}

/**
 * Handles Gutenberg block registration and rendering.
 */
class BlockHandler
{
    private Options $options;
    private StyleManager $style_manager;
    private ShortcodeHandler $shortcode_handler;

    /**
     * Constructor.
     *
     * @param Options $options Plugin options instance.
     * @param StyleManager $style_manager Style manager instance.
     * @param ShortcodeHandler $shortcode_handler Shortcode handler instance.
     */
    public function __construct(
        Options $options,
        StyleManager $style_manager,
        ShortcodeHandler $shortcode_handler
    ) {
        $this->options = $options;
        $this->style_manager = $style_manager;
        $this->shortcode_handler = $shortcode_handler;
    }

    /**
     * Register block-related hooks.
     */
    public function register_hooks(): void
    {
        // Register block type on init
        add_action('init', [$this, 'register_block']);

        // Enqueue block editor assets
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_block_editor_assets']);
    }

    /**
     * Enqueue Block editor assets (styles and scripts).
     */
    public function enqueue_block_editor_assets(): void
    {
        // Enqueue the frontend styles in the editor for preview, respecting dequeue setting.
        if (!$this->style_manager->is_default_styles_dequeued()) {
            $stylesheet_url = $this->style_manager->get_stylesheet_url();
            $version = $this->style_manager->is_custom_stylesheet_active() ? filemtime($this->style_manager->get_custom_stylesheet_path()) : BCTT_VERSION;
            wp_enqueue_style('bctt-block-editor-preview-styles', $stylesheet_url, [], (string)$version);
        }

        // Get the Twitter handle from the settings array
        $settings = $this->options->get('bctt-settings', []);
        $twitter_handle = $settings['bctt-twitter-handle'] ?? '';

        // Add plugin options needed by the block's JS
        // Assumes 'bctt-block-js' is the script handle defined in block.json
        // We need to ensure block.json uses this handle for editorScript.
        $bctt_data = [
            'username' => $twitter_handle,
            // Add other settings if the block JS needs them
        ];
        wp_localize_script('bctt-block-js', 'bctt_options_js', $bctt_data);
    }

    /**
     * Register the Gutenberg block type using block.json.
     */
    public function register_block(): void
    {
        // Path to the block.json file relative to the main plugin file
        $block_json_path = WP_PLUGIN_DIR . '/better-click-to-tweet/assets/block/block.json';

        if (!file_exists($block_json_path)) {
            // Log error or handle missing file
            error_log('BCTT Error: block.json not found at ' . $block_json_path);
            return;
        }

        // Get the Twitter handle from the settings array for default attribute
        $settings = $this->options->get('bctt-settings', []);
        $default_twitter_handle = $settings['bctt-twitter-handle'] ?? '';

        // Define default attributes, potentially overriding or supplementing block.json
        // Note: block.json is the primary source, but defaults can be set here too.
        // Using the filter is generally better for dynamic defaults.
        $block_attributes = apply_filters('bctt_block_attributes', [
            'tweet' => [
                'type' => 'string',
                'default' => $this->get_default_tweet_text(), // Use helper like in shortcode
            ],
            'username' => [
                'type' => 'string',
                'default' => $default_twitter_handle,
            ],
            'via' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'url' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'urlcustom' => [
                'type' => 'string',
                'default' => '',
            ],
            'nofollow' => [
                'type' => 'boolean',
                'default' => false,
            ],
            'prompt' => [
                'type' => 'string',
                'default' => _x('Share on X', 'Text for the box on the reader-facing box', 'better-click-to-tweet')
            ],
        ]);

        // Register the block type using block.json and provide the render callback
        register_block_type(
            $block_json_path,
            [
                'render_callback' => [$this, 'render_block'],
                'attributes' => $block_attributes,
            ]
        );
    }

    /**
     * Server-side rendering callback for the block.
     *
     * Converts block attributes to shortcode attributes and calls the shortcode handler.
     *
     * @param array $attributes Block attributes.
     * @return string HTML output of the block (rendered via shortcode).
     */
    public function render_block(array $attributes): string
    {
        // Default values should be handled by register_block_type, but check just in case
        $tweet     = $attributes['tweet'] ?? $this->get_default_tweet_text();
        $via       = $attributes['via'] ?? true;
        $username  = $attributes['username'] ?? ($this->options->get('bctt-settings', [])['bctt-twitter-handle'] ?? '');
        $url       = $attributes['url'] ?? true;
        $urlcustom = $attributes['urlcustom'] ?? '';
        $nofollow  = $attributes['nofollow'] ?? false;
        $prompt    = $attributes['prompt'] ?? _x('Share on X', 'Text for the box on the reader-facing box', 'better-click-to-tweet');

        // Map block attributes to shortcode attributes
        $showUrl = $url ? 'yes' : 'no';
        $shortcode_attributes = apply_filters('bctt_block_render_attributes', [
            'tweet'    => $tweet,
            'via'      => $via ? 'yes' : 'no',
            'username' => $username,
            'url'      => !empty($urlcustom) ? $urlcustom : $showUrl,
            'nofollow' => $nofollow ? 'yes' : 'no',
            'prompt'   => $prompt,
        ], $attributes);

        // Call the ShortcodeHandler's render method
        // We inject ShortcodeHandler into the constructor for this
        return $this->shortcode_handler->render($shortcode_attributes);
    }

    /**
     * Helper to get default tweet text (similar to ShortcodeHandler).
     *
     * @return string
     */
    private function get_default_tweet_text(): string
    {
        $post_id = get_the_ID();
        return $post_id ? get_the_title($post_id) : '';
    }

} 