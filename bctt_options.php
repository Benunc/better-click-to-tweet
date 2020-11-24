<?php
defined( 'ABSPATH' ) or die( "No script kiddies please!" );

function bctt_register_settings() {
	register_setting( 'bctt_clicktotweet-options', 'bctt-twitter-handle', 'bctt_validate_settings' );
	register_setting( 'bctt_clicktotweet-options', 'bctt-short-url', 'bctt_validate_checkbox' );
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

	<div class="wrap">

	<h1 class="wp-heading-inline"><?php /* translators: Treat "Better Click To Tweet" as a brand name, don't translate it */
		_e( 'Better Click To Tweet — a plugin by Ben Meredith', 'better-click-to-tweet' ); ?></h1>

	<hr/>
	<?php do_action( 'bctt_settings_top' ); ?>


	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2 ">
			<div id="post-body-content" style="position: relative;">
				<div id="bctt_instructions" class="postbox ">
					<h2><?php _e( 'Instructions', 'better-click-to-tweet' ); ?></h2>
					<div class="inside">
					<p class="bctt-setup-alert"><strong><?php echo sprintf( __( 'Don\'t miss the <a href="%s">Setup Wizard</a>', 'better-click-to-tweet' ), admin_url( '?page=bctt-welcome&step=bctt-setup' ) ); ?></strong></p>

						<p><?php /* translators: Treat "Better Click To Tweet" as a brand name, don't translate it */
							_e( 'To add styled click-to-tweet quote boxes include the Better Click To Tweet shortcode or Gutenberg block in your post.', 'better-click-to-tweet' ); ?></p>

						<p><?php _e( 'Here\'s how you format the shortcode:', 'better-click-to-tweet' ); ?></p>
						<pre>[bctt tweet="<?php /* translators: This text shows up as a sample tweet in the instructions for how to use the plugin. */
							_e( 'Meaningful, tweetable quote.', 'better-click-to-tweet' ); ?>"]</pre>
						<p><?php /* translators: Also, treat "BCTT" as a brand name, don't translate it */
							_e( 'If you are using the visual editor, click the BCTT birdie in the toolbar to add a pre-formatted shortcode to your post.', 'better-click-to-tweet' ); ?></p>
                        <p><?php
							_e( 'In the WordPress 5.0 editor (codenamed Gutenberg), there is a Better Click To Tweet block.', 'better-click-to-tweet' ); ?></p>
						<p><?php _e( 'If you include a link back to the post using the URL parameter (or leaving it out, the default behavior), the tweet length is automatically shortened to 253 characters minus the length of your twitter handle, to leave room for the handle and link back to the post.', 'better-click-to-tweet' ); ?></p>
                        <p><?php echo sprintf( __( 'Learn more about the URL parameter as well as the other power user features in the <a href=%s>Power User Guide</a>.', 'better-click-to-tweet' ), esc_url( 'http://benlikes.us/7r') ); ?></p>
					</div>
					<!--/inside-->
				</div>
				<!--/bctt_instructions-->
				<?php do_action( 'bctt_instructions_bottom' ); ?>
				<div class="postbox">
					<h2><?php _e( 'Settings', 'better-click-to-tweet' ); ?></h2>
					<div class="inside">
						<div class="main">
							<p><?php _e( 'Enter your Twitter handle to add "via @yourhandle" to your tweets. Do not include the @ symbol.', 'better-click-to-tweet' ); ?></p>

							<p><?php _e( 'Checking the box below will force the plugin to show the WordPress shortlink in place of the full URL. While this does not impact tweet character length, it is useful alongside plugins which customize the WordPress shortlink using services like bit.ly or yourls.org for tracking.', 'better-click-to-tweet' ) ?> </p>

							<form method="post" action="options.php" style="">
								<?php settings_fields( 'bctt_clicktotweet-options' ); ?>

								<table class="form-table">
									<tr valign="top">
										<th style="width: 200px;">
											<label for="bctt-twitter-handle"><?php _ex( 'Your Twitter Handle', 'label for text input on settings screen', 'better-click-to-tweet' ); ?></label>
										</th>
										<td><input id="bctt-twitter-handle" type="text" name="bctt-twitter-handle"
										           value="<?php echo esc_attr( get_option( 'bctt-twitter-handle' ) ); ?>"/>
										</td>
									<tr valign="top">
										<th style="width: 200px;">
											<label for="bctt-short-url"><?php _ex( 'Use Short URL?', 'label for checkbox on settings screen', 'better-click-to-tweet' ); ?></label>
										</th>
										<td><input id="bctt-short-url" type="checkbox" name="bctt-short-url"
										           value="1" <?php if ( 1 == get_option( 'bctt-short-url' ) ) {
												echo 'checked="checked"';
											} ?>" />
										</td>
									</tr>

									<?php if ( ! bctt_add_custom_style_option() ) { ?>
										<tr valign="top">
											<th style="width:200px;">
												<label><?php _ex( 'Use Premium Styles?', 'label for checkbox on settings screen', 'better-click-to-tweet' ); ?></label>
											</th>
											<td><input type="checkbox" name="bctt-custom-style"
											           value="1" style="float:left;"<?php if ( is_plugin_active( 'better-click-to-tweet-styles/better-click-to-tweet-premium-styles.php' ) ) {
													echo 'checked="checked"';
												} else {
													echo 'disabled="disabled"';
												} ?>" /> <div
													style="font-size: .85em; margin-left:3em; line-height:1.5em"><em>  <?php if ( ! is_plugin_active( 'better-click-to-tweet-styles/better-click-to-tweet-premium-styles.php' ) ) {
															echo sprintf( __( 'Want Premium styles? Add the <a href=%s>Premium Styles add-on</a> today!', 'better-click-to-tweet' ), esc_url( 'http://benlikes.us/bcttpsdirect' ) );
														} ?></em></div>

											</td>
										</tr>
									<?php } ?>
									<?php if ( ! defined( 'BCTTUTM_VERSION' ) ) { ?>
                                        <tr valign="top">
                                            <th style="width:200px;">
                                                <label><?php _ex( 'Use UTM Tags?', 'label for checkbox on settings screen', 'better-click-to-tweet' ); ?></label>
                                            </th>
                                            <td><input type="checkbox" name="bctt-utm-tags"
                                                       value="1" disabled="disabled" style="float:left;" /> <div
                                                        style="font-size: .85em; margin-left:3em; line-height:1.5em"><em>  <?php
															echo sprintf( __( 'Want to add UTM tags to the return URL to track how well BCTT boxes are performing? Add the <a href=%s>UTM tags add-on</a> today!', 'better-click-to-tweet' ), esc_url( 'http://benlikes.us/bcttutmdirect' ) );
														 ?></em></div>

                                            </td>
                                        </tr>
									<?php } ?>
								</table>
								<?php do_action( 'bctt_before_settings_submit' ); ?>
								<br class="clear"/>

								<p><input type="submit" class="button-primary"
								          value="<?php _e( 'Save Changes', 'better-click-to-tweet' ); ?>"/>
								</p>
								<br class="clear"/>
								<em><?php $url = 'https://www.betterclicktotweet.com';
									$link      = sprintf( __( 'An open source plugin by <a href=%s>Ben Meredith</a>', 'better-click-to-tweet' ), esc_url( $url ) );
									echo $link; ?></em>
							</form>

						</div>
						<!--/main-->
					</div>
					<!--/inside-->
				</div>
				<!--/postbox-->
			</div>
			<!--/post-body-content-->
			<?php do_action( 'bctt_after_settings' ); ?>

			<div id="post-box-container-1" class="post-box-container">
				<div id="side-sortables" class="meta-box-sortables ui-sortable">

					<div id="bctt-author" class="postbox " style="display:block;">
						<h2><?php _e( 'Write Better Content', 'better-click-to-tweet' ); ?> </h2>

						<div id="bctt_signup" class="inside">
							<form
								action="//benandjacq.us1.list-manage.com/subscribe/post?u=8f88921110b81f81744101f4d&amp;id=bd909b5f89&amp;SUBSET=settings_<?php echo urlencode( site_url() ) ?>"
								method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form"
								class="validate" target="_blank" novalidate>
								<div id="mc_embed_signup_scroll">
									<p> <?php  _e( 'We\'re building something big to not only amplify your content, but make it better to begin with.', 'better-click-to-tweet' ); ?></p>
									<h3 class="bctt-signup"><?php _e( 'Sign up below to hear about it before anyone else.', 'better-click-to-tweet' ); ?></h3>

									<p><label for="bctt-email-signup" class="hidden">
                                            Your Email Address
                                        </label>
                                        <input id="bctt-email-signup" type="email" value="" name="EMAIL" class="widefat" id="mce-EMAIL"
									          placeholder="<?php _ex( 'Your Email Address', 'placeholder text for input field', 'better-click-to-tweet' ); ?>">
										<small><?php _e( 'No Spam. One-click unsubscribe in every message', 'better-click-to-tweet' ); ?></small>
									</p>
									<div style="position: absolute; left: -5000px;">
                                        <label for="bctt-mailchimp-subscribe-hidden" class="hidden">
                                            Hidden field for MailChimp verification
                                        </label>
                                        <input id="bctt-mailchimp-subscribe-hidden" type="text"
									                                                       name="b_8f88921110b81f81744101f4d_bd909b5f89"
									                                                       tabindex="-1" value="">
									</div>
									<p class="clear"><input type="submit" value="<?php _e( 'Subscribe', 'better-click-to-tweet' ); ?>" name="subscribe"
									                        id="mc-embedded-subscribe" class="button-secondary"></p>

								</div>
								<!--/mc_embed_signup_scroll-->
							</form>
						</div>
						<!--/bctt_signup-->
						<?php do_action( 'bctt_author_box_bottom' ); ?>
					</div>
					<!--/bctt-author-->
				</div>
				<!--/side-sortables-->

				<div id="side-sortables" class="meta-box-sortables ui-sortable">
					<div id="bctt-contrib" class="postbox">
						<div class="inside">
							<p><?php $url2 = 'https://github.com/Benunc/better-click-to-tweet';
								$link2     = sprintf( __( 'This plugin is developed out in the open. Make requests for features and bugfixes <a href=%s>on the public GitHub repository.</a>', 'better-click-to-tweet' ), esc_url( $url2 ) );
								echo $link2; ?></p>

							<p><?php $url3 = 'https://benlikes.us/donate';
								$link3     = sprintf( __( 'The best way you can support this and other plugins is to <a href=%s>donate</a>.', 'better-click-to-tweet' ), esc_url( $url3 ) );
								echo $link3; ?>
								<?php $url4 = 'https://wordpress.org/support/view/plugin-reviews/better-click-to-tweet';
								$link4        = sprintf( __( 'The second best way is to <a href=%s>leave an honest review.</a>', 'better-click-to-tweet' ), esc_url( $url4 ) );
								echo $link4; ?></p>

							<p><?php _e( 'Did this plugin save you enough time to be worth some money?', 'better-click-to-tweet' ); ?></p>

							<p>
								<a href="https://benlikes.us/donate"
								   target="_blank" rel="noopener noreferrer"><?php _e( 'Click here to buy me a Coke to say thanks.', 'better-click-to-tweet' ); ?></a>
							</p>
						</div>
						<!--/inside-->
					</div>
					<!--/donate-contrib-->
					<?php do_action( 'bctt_contrib_bottom' ); ?>
				</div>
				<!--side-sortables-->
			</div>
			<!--/post-box-container-1-->
		</div>
		<!--/post-body-->
	</div>
	<!--/wrap-->
	<?php
}

 
