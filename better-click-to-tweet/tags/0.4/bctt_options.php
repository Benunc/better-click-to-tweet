<?php
defined('ABSPATH') or die("No script kiddies please!");


// Cache bust tinymce
add_filter('tiny_mce_version', 'refresh_mce');

// Add Settings Link
add_action('admin_menu', 'bctt_admin_menu');

// Add settings link to plugins listing page
			
// Add button plugin to TinyMCE
add_action('init', 'bctt_tinymce_button');
	
function bctt_tinymce_button() {
			if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
				return;
			}

			if ( get_user_option( 'rich_editing' ) == 'true' ) {
				add_filter( 'mce_external_plugins', 'bctt_tinymce_register_plugin' );
				add_filter( 'mce_buttons', 'bctt_tinymce_register_button' );
			}
		}
function bctt_tinymce_register_button($buttons) {
		   array_push($buttons, "|", "bctt_clicktotweet");
		   return $buttons;
		}

function bctt_tinymce_register_plugin($plugin_array) {
		   $plugin_array['bctt_clicktotweet'] = plugins_url( '/assets/js/bctt_clicktotweet_plugin.js', __FILE__);
		   return $plugin_array;
		}

		
function bctt_admin_menu() {
			add_action('admin_init', 'bctt_register_settings');
			add_options_page('Better Click To Tweet Options', 'Better Click To Tweet', 'manage_options', 'better-click-to-tweet', 'bctt_settings_page');
		}

function bctt_register_settings() {
			register_setting('bctt_clicktotweet-options', 'bctt-twitter-handle', 'bctt_validate_settings');
		}

function bctt_validate_settings($input) {	
                      return str_replace('@', '', strip_tags(stripslashes($input)));
		}

function bctt_settings_page() {
			if ( !current_user_can( 'manage_options' ) )  {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			} ?>

			<div class="wrap">

				<?php screen_icon(); ?>
				<h2>Better Click To Tweet</h2>

				<hr/>

				<h2>Instructions</h2>
				<p>
					To add styled click-to-tweet quote boxes include the Better Click To Tweet shortcode in your post.</p> 
				<p>Here's how you format the shortcode: <pre>[bctt tweet="Meaningful, tweetable quote."]</pre></p> 				
				<p>If you are using the visual editor, click the BCTT birdie in the toolbar to add a pre-formatted shortcode to your post.</p>					
				<p>Tweet length will be automatically shortened to 117 characters minus the length of your twitter name, to leave room for it and a link back to the post.
				</p>

				<h2>Settings</h2>

				<p>Enter your Twitter handle to add "via @yourhandle" to your tweets. Do not include the @ symbol.</p>
				<form method="post" action="options.php" style="display: inline-block;">
					<?php settings_fields( 'bctt_clicktotweet-options' ); ?>

					<table class="form-table">
		        		<tr valign="top">
		        			<th style="width: 200px;"><label>Your Twitter Handle</label></th>
							<td><input type="text" name="bctt-twitter-handle" value="<?php echo get_option('bctt-twitter-handle'); ?>" /></td>
						</tr>
						<tr>
							<td></td>
							<td><?php submit_button(); ?></td>
					</table>
			 	</form>

			 	<hr/>
			 	<em>An open source plugin by <a href="http://benandjacq.com" target="_blank">Ben Meredith</a></em>
			 	<p>Are you a developer? Help make it the (even) Better Click To Tweet plugin. Check out the <a target="_blank" href="https://github.com/Benunc/better-click-to-tweet">plugin on Github.</a></p>
			 	<p>The best way you can support this and other plugins is to <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=HDSGWRJYFQQNJ" target="_blank">donate</a>. The second best way is to <a href="https://wordpress.org/support/view/plugin-reviews/better-click-to-tweet" target="_blank">leave an honest review.</a></p>
		<p>Did this plugin save you enough time to be worth $3?</p>
		<p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=HDSGWRJYFQQNJ" target="_blank">Click here to buy me a Coke to say thanks.</a></p>	 
			</div>
			<?php
		}

 
