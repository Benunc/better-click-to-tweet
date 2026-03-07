<?php
/**
 * Better Click To Share — WordPress Abilities API integration (WP 6.9+).
 *
 * Registers two abilities for AI agents, MCP, and automation tools:
 * - bctt/insert-click-to-tweet: Append a BCTT block to a post.
 * - bctt/suggest-tweetables: Return tweet-length quote suggestions from post or raw content.
 *
 * This file is only loaded when wp_register_ability() exists (WordPress 6.9+).
 * See plan-ability-insert-bctt.md and plan-ability-suggest-tweetables.md in bctt-brainstorm/.
 *
 * @package Better_Click_To_Tweet
 * @since 6.0.0 (Abilities API support)
 */

defined( 'ABSPATH' ) || exit;

/*
 * -----------------------------------------------------------------------------
 * CATEGORY REGISTRATION
 * -----------------------------------------------------------------------------
 * Abilities must belong to a category. Core only provides 'site' and 'user'.
 * We register 'content' so both BCTT abilities are grouped under content-related
 * actions. Categories are registered on wp_abilities_api_categories_init (runs
 * before wp_abilities_api_init).
 */

/**
 * Registers the ability category used by both BCTT abilities.
 *
 * @return void
 */
function bctt_register_ability_category(): void {
	wp_register_ability_category(
		'content',
		array(
			'label'       => __( 'Content', 'better-click-to-tweet' ),
			'description' => __( 'Abilities that work with post and page content.', 'better-click-to-tweet' ),
		)
	);
}
add_action( 'wp_abilities_api_categories_init', 'bctt_register_ability_category' );

/*
 * -----------------------------------------------------------------------------
 * ABILITY REGISTRATION (both abilities in one hook)
 * -----------------------------------------------------------------------------
 */

/**
 * Registers BCTT abilities with the WordPress Abilities API.
 *
 * - bctt/insert-click-to-tweet: Inserts a Better Click to Tweet block at the end
 *   of a post's content. Used by AI/automation after choosing a quote (e.g. from
 *   suggest-tweetables).
 * - bctt/suggest-tweetables: Returns an array of tweet-length text snippets
 *   extracted from a post or raw content. No LLM; sentence split + length filter.
 *
 * @return void
 */
function bctt_register_abilities(): void {
	// --- Ability: Insert a BCTT block into a post ---------------------------
	wp_register_ability(
		'bctt/insert-click-to-tweet',
		array(
			'label'               => __( 'Insert Click to Tweet', 'better-click-to-tweet' ),
			'description'         => __( 'Appends a Better Click to Tweet block to the end of a post. Use this after you have the exact tweet text to show (e.g. from suggest-tweetables).', 'better-click-to-tweet' ),
			'category'            => 'content',
			'input_schema'        => array(
				'type'                 => 'object',
				'properties'           => array(
					'post_id'  => array(
						'type'        => 'integer',
						'description' => __( 'The ID of the post to add the tweet box to.', 'better-click-to-tweet' ),
					),
					'tweet'    => array(
						'type'        => 'string',
						'description' => __( 'The tweet text to display in the box. Will be truncated on display if over the tweet limit.', 'better-click-to-tweet' ),
						'maxLength'   => 280,
					),
					'username' => array(
						'type'        => 'string',
						'description' => __( 'Optional. X (Twitter) handle for the "via" attribution. Defaults to the site setting.', 'better-click-to-tweet' ),
					),
					'via'      => array(
						'type'        => 'boolean',
						'description' => __( 'Whether to include "via @username" in the tweet. Default true.', 'better-click-to-tweet' ),
						'default'     => true,
					),
					'url'      => array(
						'type'        => 'boolean',
						'description' => __( 'Whether to include the post URL in the tweet. Default true.', 'better-click-to-tweet' ),
						'default'     => true,
					),
					'prompt'   => array(
						'type'        => 'string',
						'description' => __( 'Button label, e.g. "Share on X". Default "Share on X".', 'better-click-to-tweet' ),
					),
				),
				'required'             => array( 'post_id', 'tweet' ),
				'additionalProperties' => false,
			),
			'output_schema'       => array(
				'type'                 => 'object',
				'properties'           => array(
					'success'      => array(
						'type'        => 'boolean',
						'description' => __( 'Whether the block was inserted.', 'better-click-to-tweet' ),
					),
					'post_id'      => array(
						'type'        => 'integer',
						'description' => __( 'The post that was updated.', 'better-click-to-tweet' ),
					),
					'block_markup' => array(
						'type'        => 'string',
						'description' => __( 'The serialized block that was appended (for debugging).', 'better-click-to-tweet' ),
					),
				),
				'additionalProperties' => false,
			),
			'execute_callback'    => 'bctt_ability_insert_click_to_tweet_execute',
			'permission_callback' => 'bctt_ability_insert_click_to_tweet_permission',
			'meta'                => array(
				'show_in_rest' => true,
			),
		)
	);

	// --- Ability: Suggest tweet-length quotes from content --------------------
	wp_register_ability(
		'bctt/suggest-tweetables',
		array(
			'label'               => __( 'Suggest Tweetables', 'better-click-to-tweet' ),
			'description'         => __( 'Returns suggested tweet-length snippets from a post or raw content. Use these with insert-click-to-tweet. No AI; uses sentence boundaries and length filter.', 'better-click-to-tweet' ),
			'category'            => 'content',
			'input_schema'        => array(
				'type'                 => 'object',
				'properties'           => array(
					'post_id'         => array(
						'type'        => 'integer',
						'description' => __( 'Optional. Post ID to analyze. Provide post_id or content (or both; post_id is used when both provided).', 'better-click-to-tweet' ),
					),
					'content'          => array(
						'type'        => 'string',
						'description' => __( 'Optional. Raw HTML or plain text to analyze (e.g. draft content).', 'better-click-to-tweet' ),
					),
					'max_suggestions'  => array(
						'type'        => 'integer',
						'description' => __( 'Maximum number of suggestions to return. Default 3.', 'better-click-to-tweet' ),
						'default'     => 3,
						'minimum'     => 1,
						'maximum'     => 10,
					),
					'max_length'       => array(
						'type'        => 'integer',
						'description' => __( 'Maximum character length per suggestion (tweet limit). Default 253 for typical via+URL usage.', 'better-click-to-tweet' ),
						'default'     => 253,
						'minimum'     => 50,
						'maximum'     => 280,
					),
					'use_ai'           => array(
						'type'        => 'boolean',
						'description' => __( 'When true and a Connector is connected, use AI to generate an engaging tweet. When false, use rule-based extraction from post content. Default false.', 'better-click-to-tweet' ),
						'default'     => false,
					),
				),
				'additionalProperties' => false,
			),
			'output_schema'       => array(
				'type'  => 'array',
				'items' => array(
					'type'                 => 'object',
					'properties'           => array(
						'text'   => array(
							'type'        => 'string',
							'description' => __( 'A tweet-length snippet.', 'better-click-to-tweet' ),
						),
						'length' => array(
							'type'        => 'integer',
							'description' => __( 'Character count of the snippet.', 'better-click-to-tweet' ),
						),
					),
					'required'             => array( 'text', 'length' ),
					'additionalProperties' => false,
				),
			),
			'execute_callback'    => 'bctt_ability_suggest_tweetables_execute',
			'permission_callback' => 'bctt_ability_suggest_tweetables_permission',
			'meta'                => array(
				'show_in_rest' => true,
				// Do not set 'readonly' => true: GET requests pass input as an unparsed string,
				// so object input fails validation. Use POST with JSON body so input is parsed.
			),
		)
	);
}
add_action( 'wp_abilities_api_init', 'bctt_register_abilities' );

/*
 * -----------------------------------------------------------------------------
 * INSERT CLICK TO TWEET — Permission and Execute
 * -----------------------------------------------------------------------------
 */

/**
 * Permission callback for bctt/insert-click-to-tweet.
 *
 * Caller must be able to edit the post. The Abilities API passes the same input
 * array to this callback as to the execute callback.
 *
 * @param array $input Ability input (post_id, tweet, ...).
 * @return true|WP_Error True if allowed, WP_Error otherwise.
 */
function bctt_ability_insert_click_to_tweet_permission( $input ) {
	$input = is_array( $input ) ? $input : array();
	$post_id = isset( $input['post_id'] ) ? (int) $input['post_id'] : 0;

	if ( $post_id <= 0 ) {
		return new WP_Error(
			'bctt_missing_post_id',
			__( 'post_id is required and must be a positive integer.', 'better-click-to-tweet' ),
			array( 'status' => 400 )
		);
	}

	$post = get_post( $post_id );
	if ( ! $post ) {
		return new WP_Error(
			'bctt_post_not_found',
			__( 'Post not found.', 'better-click-to-tweet' ),
			array( 'status' => 404 )
		);
	}

	// Only allow for post types that support the block editor / content.
	if ( ! post_type_supports( $post->post_type, 'editor' ) ) {
		return new WP_Error(
			'bctt_post_type_not_supported',
			__( 'This post type does not support content.', 'better-click-to-tweet' ),
			array( 'status' => 400 )
		);
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return new WP_Error(
			'bctt_cannot_edit_post',
			__( 'You do not have permission to edit this post.', 'better-click-to-tweet' ),
			array( 'status' => 403 )
		);
	}

	return true;
}

/**
 * Execute callback for bctt/insert-click-to-tweet.
 *
 * Builds a BCTT block with the given attributes, appends it to the post's
 * post_content, and updates the post. Uses the same block name and attribute
 * shape as the BCTT block (bctt/clicktotweet) so the block editor and front
 * end render it correctly.
 *
 * @param array $input Ability input (post_id, tweet, username?, via?, url?, prompt?).
 * @return array|WP_Error Success array (success, post_id, block_markup) or WP_Error.
 */
function bctt_ability_insert_click_to_tweet_execute( $input ) {
	$input = is_array( $input ) ? $input : array();
	$post_id = isset( $input['post_id'] ) ? (int) $input['post_id'] : 0;
	$tweet   = isset( $input['tweet'] ) ? (string) $input['tweet'] : '';

	if ( $post_id <= 0 || $tweet === '' ) {
		return new WP_Error(
			'bctt_invalid_input',
			__( 'post_id and tweet are required.', 'better-click-to-tweet' ),
			array( 'status' => 400 )
		);
	}

	$post = get_post( $post_id );
	if ( ! $post || ! post_type_supports( $post->post_type, 'editor' ) ) {
		return new WP_Error(
			'bctt_post_not_found',
			__( 'Post not found or does not support content.', 'better-click-to-tweet' ),
			array( 'status' => 404 )
		);
	}

	// Build block attributes to match the BCTT block (see assets/block/block.json).
	// Username: use input, or fall back to site option, or empty string (block will use option at render).
	$username = isset( $input['username'] ) ? (string) $input['username'] : '';
	if ( $username === '' ) {
		$username = (string) get_option( 'bctt-twitter-handle', '' );
	}
	$via    = ! isset( $input['via'] ) || $input['via'];
	$url    = ! isset( $input['url'] ) || $input['url'];
	$prompt = isset( $input['prompt'] ) && is_string( $input['prompt'] ) && $input['prompt'] !== ''
		? $input['prompt']
		: _x( 'Share on X', 'Text for the box on the reader-facing box', 'better-click-to-tweet' );

	$attrs = array(
		'tweet'     => $tweet,
		'username'  => $username,
		'via'       => $via,
		'url'       => $url,
		'urlcustom' => '',   // We do not expose custom URL in this ability; url=true means use permalink.
		'nofollow'  => false,
		'prompt'    => $prompt,
	);

	// Generate the block markup. BCTT block has no inner content, so third arg is empty.
	// get_comment_delimited_block_content() is in wp-includes/blocks.php (core).
	$block_markup = get_comment_delimited_block_content( 'bctt/clicktotweet', $attrs, '' );

	// Append to post content. Add newline so the block is on its own line (readable in editor).
	$new_content = $post->post_content . "\n\n" . $block_markup;

	$updated = wp_update_post(
		array(
			'ID'           => $post_id,
			'post_content' => $new_content,
		),
		true
	);

	if ( is_wp_error( $updated ) ) {
		return $updated;
	}
	if ( $updated === 0 ) {
		return new WP_Error(
			'bctt_update_failed',
			__( 'Failed to update post.', 'better-click-to-tweet' ),
			array( 'status' => 500 )
		);
	}

	return array(
		'success'      => true,
		'post_id'      => $post_id,
		'block_markup' => $block_markup,
	);
}

/*
 * -----------------------------------------------------------------------------
 * SUGGEST TWEETABLES — Permission and Execute
 * -----------------------------------------------------------------------------
 */

/**
 * Permission callback for bctt/suggest-tweetables.
 *
 * When post_id is provided, caller must be able to edit that post (so we do not
 * leak draft content). When only content is provided, caller must be able to
 * edit posts in general.
 *
 * @param array $input Ability input (post_id?, content?, max_suggestions?, max_length?).
 * @return true|WP_Error True if allowed, WP_Error otherwise.
 */
function bctt_ability_suggest_tweetables_permission( $input ) {
	$input = is_array( $input ) ? $input : array();
	$post_id = isset( $input['post_id'] ) ? (int) $input['post_id'] : 0;
	$content = isset( $input['content'] ) ? (string) $input['content'] : '';

	// At least one of post_id or content is required; we enforce in execute, but we can bail here if both missing.
	if ( $post_id <= 0 && $content === '' ) {
		return new WP_Error(
			'bctt_missing_input',
			__( 'Provide post_id or content (or both).', 'better-click-to-tweet' ),
			array( 'status' => 400 )
		);
	}

	if ( $post_id > 0 ) {
		$post = get_post( $post_id );
		if ( ! $post ) {
			return new WP_Error(
				'bctt_post_not_found',
				__( 'Post not found.', 'better-click-to-tweet' ),
				array( 'status' => 404 )
			);
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return new WP_Error(
				'bctt_cannot_edit_post',
				__( 'You do not have permission to edit this post.', 'better-click-to-tweet' ),
				array( 'status' => 403 )
			);
		}
	} else {
		// Only content provided (e.g. draft). Require edit_posts so we don't expose to subscribers.
		if ( ! current_user_can( 'edit_posts' ) ) {
			return new WP_Error(
				'bctt_cannot_edit_posts',
				__( 'You do not have permission to use this ability.', 'better-click-to-tweet' ),
				array( 'status' => 403 )
			);
		}
	}

	return true;
}

/**
 * Execute callback for bctt/suggest-tweetables.
 *
 * 1. Get raw content (from post_id or input content).
 * 2. Strip HTML and block comments, normalize whitespace to plain text.
 * 3. Split on sentence boundaries (. ! ? followed by space).
 * 4. Filter to chunks under max_length; optionally skip very short chunks.
 * 5. Return up to max_suggestions items with text and length.
 *
 * @param array $input Ability input (post_id?, content?, max_suggestions?, max_length?).
 * @return array|WP_Error Array of { text, length } or WP_Error.
 */
function bctt_ability_suggest_tweetables_execute( $input ) {
	$input = is_array( $input ) ? $input : array();
	$post_id = isset( $input['post_id'] ) ? (int) $input['post_id'] : 0;
	$content = isset( $input['content'] ) ? (string) $input['content'] : '';
	$max_suggestions = isset( $input['max_suggestions'] ) ? (int) $input['max_suggestions'] : 3;
	$max_length      = isset( $input['max_length'] ) ? (int) $input['max_length'] : 253;
	$use_ai          = ! empty( $input['use_ai'] );

	// Clamp to schema bounds.
	$max_suggestions = max( 1, min( 10, $max_suggestions ) );
	$max_length      = max( 50, min( 280, $max_length ) );

	// --- Resolve raw content ------------------------------------------------
	// Prefer post_id when both are provided (fetch from DB).
	if ( $post_id > 0 ) {
		$post = get_post( $post_id );
		if ( ! $post ) {
			return new WP_Error(
				'bctt_post_not_found',
				__( 'Post not found.', 'better-click-to-tweet' ),
				array( 'status' => 404 )
			);
		}
		$raw = $post->post_content;
	} elseif ( $content !== '' ) {
		$raw = $content;
	} else {
		return new WP_Error(
			'bctt_missing_input',
			__( 'Provide post_id or content (or both).', 'better-click-to-tweet' ),
			array( 'status' => 400 )
		);
	}

	// --- Plain text: strip HTML and block comments, normalize whitespace ----
	$text = strip_tags( $raw );
	$text = preg_replace( '/<!--.*?-->/s', '', $text );
	$text = preg_replace( '/\s+/', ' ', trim( $text ) );

	if ( $text === '' ) {
		return array();
	}

	// --- Optional: use connected LLM (Connectors) for one engaging tweet ----
	// Require site-level usage agreement before any AI calls (charges).
	if ( $use_ai && function_exists( 'bctt_has_llm_connector' ) && bctt_has_llm_connector() && ! get_option( 'bctt_connector_usage_agreed', false ) ) {
		return new WP_Error(
			'bctt_connector_agreement_required',
			__( 'Usage agreement is required before using AI suggestions. Please confirm in the Suggest X Content panel.', 'better-click-to-tweet' ),
			array( 'status' => 403 )
		);
	}
	if ( $use_ai && function_exists( 'bctt_has_llm_connector' ) && bctt_has_llm_connector() && function_exists( 'wp_ai_client_prompt' ) ) {
		$text_for_ai = function_exists( 'mb_strlen' ) && mb_strlen( $text ) > 6000
			? mb_substr( $text, 0, 6000 ) . '…'
			: ( strlen( $text ) > 6000 ? substr( $text, 0, 6000 ) . '…' : $text );
		$prompt = sprintf(
			/* translators: %d: maximum character length for the tweet (e.g. 253). */
			__( 'You are a senior social media marketer. Read the following post and write a single engaging tweet (at most %d characters) that will make people want to click through to read more. Aim for genuine interest and curiosity, not clickbait. Return only the tweet text, nothing else.', 'better-click-to-tweet' ),
			$max_length
		);
		$full_prompt = $prompt . "\n\n" . __( 'Post content:', 'better-click-to-tweet' ) . "\n\n" . $text_for_ai;
		$result = wp_ai_client_prompt( $full_prompt )->generate_text();

		if ( is_wp_error( $result ) && defined( 'WP_DEBUG' ) && WP_DEBUG && function_exists( 'error_log' ) ) {
			error_log( 'BCTT suggest-tweetables: AI returned WP_Error: ' . $result->get_error_message() . ' (code: ' . $result->get_error_code() . ')' );
		}

		if ( ! is_wp_error( $result ) && is_string( $result ) && $result !== '' ) {
			$use_mb = function_exists( 'mb_strlen' );
			$lines  = preg_split( '/\r\n|\r|\n/', $result );
			foreach ( $lines as $line ) {
				$line = trim( $line );
				$line = preg_replace( '/^[\d]+[.)]\s*/', '', $line );
				$line = preg_replace( '/^[-*]\s*/', '', $line );
				$line = trim( $line );
				if ( $line === '' ) {
					continue;
				}
				$len = $use_mb ? mb_strlen( $line ) : strlen( $line );
				if ( $len > $max_length ) {
					$line = $use_mb ? mb_substr( $line, 0, $max_length ) : substr( $line, 0, $max_length );
					$len  = $max_length;
				}
				if ( $len >= 20 ) {
					return array( array( 'text' => $line, 'length' => $len ) );
				}
			}
		}
	}

	// --- Split into sentence-like chunks ------------------------------------
	// Split on . ! ? followed by one or more spaces (or end of string). Keeps the delimiter on the previous chunk.
	$chunks = preg_split( '/(?<=[.!?])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY );
	$chunks = array_map( 'trim', $chunks );
	$chunks = array_filter( $chunks );

	// --- Filter by length and optional minimum length -----------------------
	$use_mb = function_exists( 'mb_strlen' );
	$suggestions = array();
	$min_length = 20; // Skip very short fragments like "Yes." unless we have few options.

	foreach ( $chunks as $chunk ) {
		$len = $use_mb ? mb_strlen( $chunk ) : strlen( $chunk );
		if ( $len > $max_length ) {
			continue;
		}
		if ( $len < $min_length && count( $suggestions ) >= 1 ) {
			// Skip tiny chunks once we have at least one suggestion.
			continue;
		}
		$suggestions[] = array(
			'text'   => $chunk,
			'length' => $len,
		);
		if ( count( $suggestions ) >= $max_suggestions ) {
			break;
		}
	}

	return $suggestions;
}
