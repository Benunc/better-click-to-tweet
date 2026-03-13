<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );

function bctt_register_settings() {
	register_setting( 'bctt_clicktotweet-options', 'bctt-twitter-handle', 'bctt_validate_settings' );
	register_setting( 'bctt_clicktotweet-options', 'bctt-short-url', 'bctt_validate_checkbox' );
	if ( function_exists( 'wp_register_ability' ) ) {
		register_setting(
			'bctt_clicktotweet-options',
			'bctt_connector_usage_agreed',
			array(
				'type'              => 'boolean',
				'sanitize_callback' => function ( $value ) {
					return ( isset( $value ) && '1' === $value ) ? 1 : 0;
				},
			)
		);
	}
}

function bctt_validate_settings( $input ) {
	return preg_replace('/[^0-9a-zA-Z_]/', '', $input);
}

function bctt_validate_checkbox( $input ) {
	if ( ! isset( $input ) || $input != '1' ) {
		return 0;
	} else {
		return 1;
	}
}

function bctt_add_custom_style_option() {
	$bctt_dequeued_with_custom_funtion = bctt_is_default_styles_dequeued();

	$bctt_custom_style = bctt_is_custom_stylesheet();

	if ( $bctt_dequeued_with_custom_funtion || $bctt_custom_style ) {
		return true;
	} else {
		return false;
	}

}

function bctt_admin_styles() {
	wp_register_style( 'bctt_admin_style', plugins_url( 'assets/css/bctt-admin.css', __FILE__ ) );
	wp_enqueue_style( 'bctt_admin_style' );
}

function bctt_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'better-click-to-tweet' ) );
	}

	bctt_admin_styles();
	?>

	<div class="bctt-settings-page">

	<?php do_action( 'bctt_settings_top' ); ?>

	<div class="bctt-settings-grid">
		<main class="bctt-settings-main">

			<section class="bctt-card bctt-card-instructions" id="bctt_instructions" aria-labelledby="bctt-instructions-heading">
				<h2 id="bctt-instructions-heading" class="bctt-card-title"><?php _e( 'Instructions', 'better-click-to-tweet' ); ?></h2>
				<div class="bctt-card-content">
					<p class="bctt-setup-alert">
						<strong><?php echo sprintf( __( 'Don\'t miss the <a href="%s">Setup Wizard</a>', 'better-click-to-tweet' ), admin_url( '?page=bctt-welcome&step=bctt-setup' ) ); ?></strong>
					</p>
					<p><?php /* translators: Treat "Better Click To Share" as a brand name, don't translate it */
						_e( 'To add styled one-click-to-share-on-X quote boxes include the Better Click To Share shortcode or Gutenberg block in your post.', 'better-click-to-tweet' ); ?></p>
					<p><?php _e( 'Here\'s how you format the shortcode:', 'better-click-to-tweet' ); ?></p>
					<pre class="bctt-code">[bctt tweet="<?php /* translators: This text shows up as a sample tweet in the instructions for how to use the plugin. */
						_e( 'Meaningful, shareable quote.', 'better-click-to-tweet' ); ?>"]</pre>
					<p><?php /* translators: Treat "Better Click To Share" as a brand name, don't translate it */
						_e( 'If you are using the visual editor, click the Better Click To Share birdie in the toolbar to add a pre-formatted shortcode to your post.', 'better-click-to-tweet' ); ?></p>
					<p><?php _e( 'In the Block-based editor, there is a Better Click To Share block.', 'better-click-to-tweet' ); ?></p>
					<p><?php _e( 'If you include a link back to the post using the URL parameter (or leaving it out, the default behavior), the post length is automatically shortened to 253 characters minus the length of your x.com username, to leave room for the handle and link back to the post.', 'better-click-to-tweet' ); ?></p>
					<p><?php _e( 'NOTE: X.com allows premium members to post longer than 280 characters. Better Click To Share is designed to allow ALL users there to be able to share your post. That\'s why we do not plan to support longer posts to X.', 'better-click-to-tweet' ); ?></p>
					<p><?php echo sprintf( __( 'Learn more about the URL parameter as well as the other power user features in the <a href="%s">Power User Guide</a>.', 'better-click-to-tweet' ), esc_url( 'https://benlikes.us/7r' ) ); ?></p>
				</div>
			</section>
			<?php do_action( 'bctt_instructions_bottom' ); ?>

			<section class="bctt-card bctt-card-settings" aria-labelledby="bctt-settings-heading">
				<h2 id="bctt-settings-heading" class="bctt-card-title"><?php _e( 'Settings', 'better-click-to-tweet' ); ?></h2>
				<div class="bctt-card-content">
					<p class="bctt-settings-intro"><?php _e( 'Enter your X Username to add "via @yourhandle" to the posts that are shared on X. Do not include the @ symbol.', 'better-click-to-tweet' ); ?></p>
					<p class="bctt-settings-intro"><?php _e( 'Checking the box below will force the plugin to show the WordPress shortlink in place of the full URL. While this does not impact character length, it is useful alongside plugins which customize the WordPress shortlink using services like bit.ly or yourls.org for tracking.', 'better-click-to-tweet' ); ?></p>

					<form method="post" action="options.php" class="bctt-settings-form">
						<?php settings_fields( 'bctt_clicktotweet-options' ); ?>

						<div class="bctt-form-table">
							<div class="bctt-form-row">
								<div class="bctt-form-label">
									<label for="bctt-twitter-handle"><?php _ex( 'Your X Username', 'label for text input on settings screen', 'better-click-to-tweet' ); ?></label>
								</div>
								<div class="bctt-form-field">
									<input id="bctt-twitter-handle" type="text" name="bctt-twitter-handle" value="<?php echo esc_attr( get_option( 'bctt-twitter-handle' ) ); ?>" class="regular-text" />
								</div>
							</div>
							<div class="bctt-form-row">
								<div class="bctt-form-label">
									<label for="bctt-short-url"><?php _ex( 'Use Short URL?', 'label for checkbox on settings screen', 'better-click-to-tweet' ); ?></label>
								</div>
								<div class="bctt-form-field">
									<input id="bctt-short-url" type="checkbox" name="bctt-short-url" value="1" <?php checked( 1, get_option( 'bctt-short-url' ) ); ?> />
								</div>
							</div>
							<?php if ( ! bctt_add_custom_style_option() ) { ?>
							<div class="bctt-form-row">
								<div class="bctt-form-label">
									<label><?php _ex( 'Use Premium Styles?', 'label for checkbox on settings screen', 'better-click-to-tweet' ); ?></label>
								</div>
								<div class="bctt-form-field">
									<input type="checkbox" name="bctt-custom-style" value="1" <?php echo is_plugin_active( 'better-click-to-tweet-styles/better-click-to-tweet-premium-styles.php' ) ? 'checked="checked"' : 'disabled="disabled"'; ?> />
									<?php if ( ! is_plugin_active( 'better-click-to-tweet-styles/better-click-to-tweet-premium-styles.php' ) ) { ?>
										<p class="description"><?php echo sprintf( __( 'Want Premium styles? Add the <a href="%s">Premium Styles add-on</a> today!', 'better-click-to-tweet' ), esc_url( 'https://benlikes.us/bcttpsdirect' ) ); ?></p>
									<?php } ?>
								</div>
							</div>
							<?php } ?>
							<?php if ( ! defined( 'BCTTUTM_VERSION' ) ) { ?>
							<div class="bctt-form-row">
								<div class="bctt-form-label">
									<label><?php _ex( 'Use UTM Tags?', 'label for checkbox on settings screen', 'better-click-to-tweet' ); ?></label>
								</div>
								<div class="bctt-form-field">
									<input type="checkbox" name="bctt-utm-tags" value="1" disabled="disabled" />
									<p class="description"><?php echo sprintf( __( 'Want to add UTM tags to the return URL to track how well BCTT boxes are performing? Add the <a href="%s">UTM tags add-on</a> today!', 'better-click-to-tweet' ), esc_url( 'https://benlikes.us/bcttutmdirect' ) ); ?></p>
								</div>
							</div>
							<?php } ?>
							<?php if ( function_exists( 'wp_register_ability' ) ) { ?>
							<div class="bctt-form-row">
								<div class="bctt-form-label">
									<label for="bctt-connector-usage-agreed"><?php _ex( 'Allow AI tweet suggestions?', 'label for checkbox on settings screen', 'better-click-to-tweet' ); ?></label>
								</div>
								<div class="bctt-form-field">
									<input type="hidden" name="bctt_connector_usage_agreed" value="0" />
									<input id="bctt-connector-usage-agreed" type="checkbox" name="bctt_connector_usage_agreed" value="1" <?php checked( get_option( 'bctt_connector_usage_agreed', false ) ); ?> />
									<p class="description"><?php _e( 'When enabled, editors can use AI to suggest tweet text from the Suggest X Content panel. Usage charges from your connected AI model apply. Uncheck to disable AI features for all users.', 'better-click-to-tweet' ); ?></p>
								</div>
							</div>
							<?php } ?>
						</div>
						<?php do_action( 'bctt_before_settings_submit' ); ?>
						<p class="bctt-form-actions">
							<button type="submit" class="button button-primary"><?php _e( 'Save Changes', 'better-click-to-tweet' ); ?></button>
						</p>
						<p class="bctt-settings-footer">
							<?php
							$url = 'https://www.betterclicktoshare.com';
							echo sprintf( __( 'An open source plugin by <a href="%s">Ben Meredith</a>', 'better-click-to-tweet' ), esc_url( $url ) );
							?>
						</p>
					</form>
				</div>
			</section>
		</main>

		<?php do_action( 'bctt_after_settings' ); ?>

		<aside class="bctt-settings-sidebar">
			<div class="bctt-card bctt-card-sidebar" id="bctt-author">
				<h2 class="bctt-card-title"><?php _e( 'Write Better Content', 'better-click-to-tweet' ); ?></h2>
				<div id="bctt_signup" class="bctt-card-content">
					<p><?php _e( 'We\'ve seen all the ways content gets shared online. Now we\'re building some tools to help you write better content.', 'better-click-to-tweet' ); ?></p>
					<p><strong><a href="https://benlikes.us/bcttsubscribe" target="_blank" rel="nofollow noopener noreferrer"><?php _e( 'Be the first to Know', 'better-click-to-tweet' ); ?></a></strong></p>
				</div>
				<?php do_action( 'bctt_author_box_bottom' ); ?>
			</div>

			<div class="bctt-card bctt-card-sidebar" id="bctt-contrib">
				<div class="bctt-card-content">
					<p><?php echo sprintf( __( 'This plugin is developed out in the open. Make requests for features and bugfixes <a href="%s">on the public GitHub repository.</a>', 'better-click-to-tweet' ), esc_url( 'https://github.com/Benunc/better-click-to-tweet' ) ); ?></p>
					<p><?php echo sprintf( __( 'The best way you can support this and other plugins is to <a href="%s">donate</a>.', 'better-click-to-tweet' ), esc_url( 'https://benlikes.us/donate' ) ); ?>
					<?php echo sprintf( __( 'The second best way is to <a href="%s">leave an honest review.</a>', 'better-click-to-tweet' ), esc_url( 'https://wordpress.org/support/view/plugin-reviews/better-click-to-tweet' ) ); ?></p>
					<p><?php _e( 'Did this plugin save you enough time to be worth some money?', 'better-click-to-tweet' ); ?></p>
					<p><a href="https://benlikes.us/donate" target="_blank" rel="noopener noreferrer" class="button button-secondary"><?php _e( 'Say thanks with a donation', 'better-click-to-tweet' ); ?></a></p>
				</div>
				<?php do_action( 'bctt_contrib_bottom' ); ?>
			</div>
		</aside>
	</div>
	</div>
	<?php
}

 
