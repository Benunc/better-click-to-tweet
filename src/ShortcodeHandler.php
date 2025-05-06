<?php
declare(strict_types=1);

namespace BCTT;

if (!defined('ABSPATH')) {
    die('No soup for you. You leave now.');
}

/**
 * Handles the [bctt] shortcode.
 */
class ShortcodeHandler
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
     * Register the shortcode.
     */
    public function register(): void
    {
        add_shortcode('bctt', [$this, 'render']);
    }

    /**
     * Helper to get a specific setting value from the main option array.
     *
     * @param string $key The key within the 'bctt-settings' array.
     * @param mixed $default Default value if the key is not set.
     * @return mixed The setting value.
     */
    private function get_setting(string $key, mixed $default = null): mixed
    {
        $settings = $this->options->get('bctt-settings', []);
        return $settings[$key] ?? $default;
    }

    /**
     * Render the shortcode output.
     *
     * @param array|string $atts Shortcode attributes.
     * @return string Shortcode output HTML.
     */
    public function render(array|string $atts): string
    {
        // Ensure atts is an array
        $atts = (array) $atts;

        // Get settings using the new helper method
        $twitter_handle = $this->get_setting('bctt-twitter-handle', '');

        // Define defaults, using null coalescing operator (PHP 7.0+)
        $default_atts = apply_filters('bctt_atts', [
            'tweet'    => $this->get_default_tweet_text(),
            'via'      => 'yes',
            'username' => $twitter_handle ?: 'not-a-real-user', // Use handle if set, else placeholder
            'url'      => 'yes',
            'nofollow' => 'no',
            'prompt'   => _x('Share on X', 'Text for the box on the reader-facing box', 'better-click-to-tweet')
        ]);

        $atts = shortcode_atts($default_atts, $atts, 'bctt');

        // Determine the handle to use (per-shortcode or default)
        $handle = ($atts['username'] !== 'not-a-real-user') ? $atts['username'] : $twitter_handle;

        $handle_length = 0;
        $via = null;
        $related = '';

        // Calculate handle length and set via/related if handle exists and 'via' is not 'no'
        if (!empty($handle) && $atts['via'] !== 'no') {
            $via = $handle;
            $related = $handle; // Use the same handle for related
            // mb_strlen is safer for multi-byte characters (PHP needs mbstring extension)
            $handle_length = function_exists('mb_strlen') ? (6 + mb_strlen($via)) : (6 + strlen($via));
        }

        $text = $atts['tweet'];

        // Determine the URL to include
        $bcttURL = $this->get_url_for_tweet($atts);

        // Calculate max length for tweet text based on URL presence
        $max_length = $bcttURL ? (280 - 23 - 1 - $handle_length) : (280 - $handle_length); // 23 for URL, 1 for space
        $short_text = $this->shorten($text, $max_length);

        // Determine rel attribute
        $rel = ($atts['nofollow'] !== 'no') ? 'rel="noopener noreferrer nofollow"' : 'rel="noopener noreferrer"';

        // Apply filters to CSS classes
        $bctt_span_class        = apply_filters('bctt_span_class', 'bctt-click-to-tweet');
        $bctt_text_span_class   = apply_filters('bctt_text_span_class', 'bctt-ctt-text');
        $bctt_button_span_class = apply_filters('bctt_button_span_class', 'bctt-ctt-btn');

        // Construct the Twitter intent URL
        $intent_url = add_query_arg(array_filter([
            'text'    => rawurlencode(html_entity_decode($short_text)),
            'url'     => $bcttURL ? rawurlencode($bcttURL) : null, // Pass null if no URL
            'via'     => $via, // Pass null if no via
            'related' => $related ?: null, // Pass null if no related
        ]), 'https://twitter.com/intent/tweet');

        // Generate output HTML
        if (!is_feed()) {
            $output = sprintf(
                '<span class="%1$s"><span class="%2$s"><a href="%4$s" target="_blank" %5$s>%6$s</a></span><a href="%4$s" target="_blank" class="%3$s" %5$s>%7$s</a></span>',
                esc_attr($bctt_span_class),
                esc_attr($bctt_text_span_class),
                esc_attr($bctt_button_span_class),
                esc_url($intent_url),
                $rel,
                esc_html($short_text),
                esc_html($atts['prompt'])
            );
        } else {
            // Simpler output for RSS feeds
            $output = sprintf(
                '<hr /><p><em>%1$s</em><br /><a href="%2$s" target="_blank" %3$s>%4$s</a><br /><hr />',
                esc_html($short_text),
                esc_url($intent_url),
                $rel,
                esc_html($atts['prompt'])
            );
        }

        return apply_filters('bctt_output', $output, $short_text, $bctt_button_span_class, $bctt_span_class, $bctt_text_span_class, $intent_url, $rel, $atts);
    }

    /**
     * Get the default text for the tweet if none is provided.
     *
     * @return string
     */
    private function get_default_tweet_text(): string
    {
        // Use null coalescing assignment operator (PHP 7.4+)
        $post_id = get_the_ID();
        return $post_id ? get_the_title($post_id) : '';
    }

    /**
     * Determine the URL to include in the tweet based on attributes and settings.
     *
     * @param array $atts Shortcode attributes.
     * @return string|null The URL string or null if no URL should be included.
     */
    private function get_url_for_tweet(array $atts): ?string
    {
        $url_param = $atts['url'];

        if ($url_param === 'no') {
            return null;
        }

        // Check setting for using short URL
        $use_short_url = $this->get_setting('bctt-short-url', '0') === '1';

        if (filter_var($url_param, FILTER_VALIDATE_URL)) {
            $url = $url_param;
        } elseif ($use_short_url) {
            $url = wp_get_shortlink(); // Returns empty string if not available
        } else {
            $url = get_permalink(); // Returns false if used outside the loop
        }

        // Ensure we have a valid URL string or use permalink as fallback
        $final_url = ($url && is_string($url) && filter_var($url, FILTER_VALIDATE_URL)) ? $url : get_permalink();
        $final_url = ($final_url && is_string($final_url)) ? $final_url : null;

        // Allow filtering of the final URL
        // Changed filter name slightly for consistency
        return apply_filters('bctt_shortcode_url', $final_url, $atts);
    }

    /**
     * Shorten text to a specified length, respecting word boundaries.
     *
     * Strips HTML tags and adds an ellipsis if truncated.
     *
     * @param string $input Raw text string.
     * @param int $length Max length for truncation.
     * @param bool $ellipsis Add ellipsis if truncated.
     * @param bool $strip_html Strip HTML tags.
     * @return string The processed text.
     */
    public function shorten(string $input, int $length, bool $ellipsis = true, bool $strip_html = true): string
    {
        if ($strip_html) {
            $input = strip_tags($input);
        }

        // Use mbstring if available for better multi-byte character handling
        if (function_exists('mb_strlen')) {
            if (mb_strlen($input) <= $length) {
                return $input;
            }
            // Find the last space within the allowed length
            $last_space = mb_strrpos(mb_substr($input, 0, $length), ' ');
            // If no space found, truncate hard at length (or could decide differently)
            $trimmed_text = ($last_space !== false) ? mb_substr($input, 0, $last_space) : mb_substr($input, 0, $length);
        } else {
            // Fallback for environments without mbstring
            if (strlen($input) <= $length) {
                return $input;
            }
            $last_space = strrpos(substr($input, 0, $length), ' ');
            $trimmed_text = ($last_space !== false) ? substr($input, 0, $last_space) : substr($input, 0, $length);
        }

        if ($ellipsis && ( ($last_space !== false && mb_strlen($input) > $length) || strlen($input) > $length) ) {
             $trimmed_text .= '…'; // Unicode ellipsis - ensure ellipsis only added if actually truncated
         }

        return $trimmed_text;
    }
} 