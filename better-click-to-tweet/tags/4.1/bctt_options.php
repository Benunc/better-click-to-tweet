<?php
defined('ABSPATH') or die("No script kiddies please!");


// Cache bust tinymce
add_filter('tiny_mce_version', 'refresh_mce');

// Add button to visual editor
include dirname(__FILE__).'/assets/tinymce/bctt-tinymce.php';

// Add Settings Link
add_action('admin_menu', 'bctt_admin_menu');

			


function bctt_admin_menu() {
			add_action('admin_init', 'bctt_register_settings', 100, 1 );
			add_options_page('Better Click To Tweet Options', 'Better Click To Tweet', 'manage_options', 'better-click-to-tweet', 'bctt_settings_page');
		}

function bctt_register_settings() {
			register_setting( 'bctt_clicktotweet-options', 'bctt-twitter-handle', 'bctt_validate_settings');
            register_setting( 'bctt_clicktotweet-options', 'bctt-short-url', 'bctt_validate_checkbox' );
		}

function bctt_validate_settings($input) {	
                      return str_replace('@', '', strip_tags(stripslashes($input)));
		}

function bctt_validate_checkbox( $input) {
                      if ( ! isset( $input ) || $input != '1' )
                        return 0;
                      else
                        return 1;
        }

function bctt_settings_page() {
			if ( !current_user_can( 'manage_options' ) )  {
				wp_die( __( 'You do not have sufficient permissions to access this page.', 'better-click-to-tweet' ) );
			} ?>

			<div class="wrap">

				<h2><?php /* translators: Treat "Better Click To Tweet" as a brand name, don't translate it */ _e('Better Click To Tweet — a plugin by Ben Meredith', 'better-click-to-tweet');?></h2>

				<hr/>
				<div id="bctt_admin" class="metabox-holder has-right-sidebar">
					<div class="inner-sidebar">
						<div id="normal-sortables" class="meta-box-sortables ui-sortable">
							<div class="postbox">
			 					<div class="inside">
			 						<h3 class="hndle ui-sortable-handle"><?php _e('About the Author', 'better-click-to-tweet');?> </h3>
									<div id="bctt_signup">
										<form action="//benandjacq.us1.list-manage.com/subscribe/post?u=8f88921110b81f81744101f4d&amp;id=bd909b5f89" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
											<div id="mc_embed_signup_scroll">
												<p> <?php echo sprintf( __('This plugin is developed by <a href="%s">Ben Meredith</a>. I am a freelance developer specializing in <a href="%s">outrunning and outsmarting hackers</a>.', 'better-click-to-tweet'), esc_url('http://benandjacq.com'), esc_url('http://benandjacq.com/wordpress-maintenance-plans') );?></p>
												<h4><?php _e('Sign up to receive my FREE web strategy guide', 'better-click-to-tweet'); ?></h4>
												<p><input type="email" value="" name="EMAIL" class="widefat" id="mce-EMAIL" placeholder="<?php _ex('Your Email Address', 'placeholder text for input field', 'better-click-to-tweet');?>">
												<small><?php _e('No Spam. One-click unsubscribe in every message', 'better-click-to-tweet'); ?></small></p>
												<div style="position: absolute; left: -5000px;"><input type="text" name="b_8f88921110b81f81744101f4d_bd909b5f89" tabindex="-1" value="">
												</div>	
												<p class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button-secondary"></p>

											</div>
										</form>
									</div>
								</div>
							</div>
						</div>	
						<div class="meta-box-sortables">
							<div class="postbox">
								<div class="inside">
									<p><?php $url2 = 'https://github.com/Benunc/better-click-to-tweet'; $link2 = sprintf(  __( 'Are you a developer? I would love your help making this plugin better. Check out the <a href=%s>plugin on Github.</a>' , 'better-click-to-tweet'), esc_url($url2 ) ); echo $link2; ?></p>
									<p><?php $url3 = 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=HDSGWRJYFQQNJ'; $link3 = sprintf( __( 'The best way you can support this and other plugins is to <a href=%s>donate</a>', 'better-click-to-tweet' ), esc_url($url3) ); echo $link3; ?></a>. <?php $url4 = 'https://wordpress.org/support/view/plugin-reviews/better-click-to-tweet'; $link4 = sprintf( __( 'The second best way is to <a href=%s>leave an honest review.</a>', 'better-click-to-tweet'), esc_url($url4) ); echo $link4; ?></p>
									<p><?php _e( 'Did this plugin save you enough time to be worth some money?', 'better-click-to-tweet'); ?></p>
									<p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=HDSGWRJYFQQNJ" target="_blank"><?php _e( 'Click here to buy me a Coke to say thanks.', 'better-click-to-tweet' ); ?></a></p>	 
								</div>
							</div>
						</div>
					</div>
					<div id="post-body" class="has-sidebar">
						<div id="post-body-content" class="has-sidebar-content">
							<div id="normal-sortables" class="meta-box-sortables">
								<div class="postbox">
									<div class="inside">
										<h2 class="hndle"><?php _e( 'Instructions', 'better-click-to-tweet'); ?></h2>
										<p><?php /* translators: Treat "Better Click To Tweet" as a brand name, don't translate it */ _e('To add styled click-to-tweet quote boxes include the Better Click To Tweet shortcode in your post.', 'better-click-to-tweet' ); ?></p> 
										<p><?php _e( 'Here\'s how you format the shortcode:', 'better-click-to-tweet' ); ?><pre>[bctt tweet="<?php /* translators: This text shows up as a sample tweet in the instructions for how to use the plugin. */ _e('Meaningful, tweetable quote.', 'better-click-to-tweet' ); ?>"]</pre></p> 				
										<p><?php /* translators: Also, treat "BCTT" as a brand name, don't translate it */ _e( 'If you are using the visual editor, click the BCTT birdie in the toolbar to add a pre-formatted shortcode to your post.', 'better-click-to-tweet' ); ?></p>					
										<p><?php _e( 'Tweet length is automatically shortened to 117 characters minus the length of your twitter name, to leave room for it and a link back to the post.', 'better-click-to-tweet' ); ?></p>
									</div>
								</div>
								<div class="postbox">
									<div class="inside">										
										<h2 class="hndle"><?php _e( 'Settings', 'better-click-to-tweet' ); ?></h2>
										<p><?php _e( 'Enter your Twitter handle to add "via @yourhandle" to your tweets. Do not include the @ symbol.', 'better-click-to-tweet' ); ?></p>
										<p><?php _e('Checking the box below will force the plugin to show the WordPress shortlink in place of the full URL. While this does not impact tweet character length, it is useful alongside plugins which customize the WordPress shortlink using services like bit.ly or yourls.org for tracking', 'better-click-to-tweet')?> </p>	
										<form method="post" action="options.php" style="display: inline-block;">
											<?php settings_fields( 'bctt_clicktotweet-options' ); ?>

											<table class="form-table">
		        								<tr valign="top">
		        									<th style="width: 200px;"><label><?php _ex('Your Twitter Handle', 'label for text input on settings screen', 'better-click-to-tweet'); ?></label></th>
														<td><input type="text" name="bctt-twitter-handle" value="<?php echo get_option('bctt-twitter-handle'); ?>" /></td></tr>
												<tr valign="top">        
                                	        	    <th style="width: 200px;"><label><?php _ex('Use Short URL?', 'label for checkbox on settings screen', 'better-click-to-tweet'); ?></label></th>
                                    	       	     	<td><input type="checkbox" name="bctt-short-url" value="1" <?php if ( 1 == get_option( 'bctt-short-url') ) echo 'checked="checked"'; ?>" /></td>	
                                 		       	</tr>
											</table>
										<br class="clear" />
										<p><input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'better-click-to-tweet' ); ?>" /></p>
										<br class="clear" />
										<em><?php $url = 'http://benandjacq.com'; $link = sprintf( __( 'An open source plugin by <a href=%s>Ben Meredith</a>', 'better-click-to-tweet'), esc_url($url) ); echo $link; ?></em>
			 							</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
<?php
		}

 
