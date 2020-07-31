<?php

function bctt_alerts() {
	if (is_plugin_active('better-click-to-tweet-premium-styles/better-click-to-tweet-premium-styles.php')) {
		return;
	}

	$screen = get_current_screen();

	$screenparent = $screen->parent_file;
	$screen_id = $screen->id;


	if ( $screenparent == 'plugins.php' && $screen_id == 'plugins' && current_user_can( 'install_plugins' ) ) {

		$user_id       = wp_get_current_user()->ID;
		$has_dismissed = get_user_meta( $user_id, 'bctt_has_dismissed_nag', true );

		if ( ! $has_dismissed && ! bctt_add_custom_style_option() ) {
			//add style inline so that it isn't enqueued if not used
			?>
			<style>
				span.bctt-addon-nag-header {
					font-size: large;
					font-weight: bold;
				}

				.updated.bctt-addon-nag {
					padding: 1em;
				}

				p.bctt-addon-nag-copy {

				}

				ul.bctt-addon-nag-list {
					list-style-type: none;
					margin-left: 2em;
				}

				.bctt-addon-nag-list li:before {
					content: "\f155";
					display: inline-block;
					-webkit-font-smoothing: antialiased;
					font: normal 16px/1 'dashicons';
					color: #46b450;
					margin-right: 10px;
					word-wrap: break-word;
				}

				.bctt-addon-nag-dismiss {
					float: right;
					text-decoration: none;
				}
				.bctt-nag-photo {
					float: right;
					margin:0;
					max-height:105px;
					max-width:98%;
				}
				@media screen and (max-width: 782px) {
					.bctt-nag-photo {
						float:none;
					}
				}
				.bctt-nag-purchase-link {
					font-size: large;
				}
			</style>
			<div class="updated bctt-addon-nag">

				<a href="<?php
				//The Dismiss Button.
				$nag_meta_key          = 'bctt_has_dismissed_nag';
				$nag_admin_dismiss_url = 'plugins.php?' . $nag_meta_key . '=0';
				echo esc_url( admin_url( $nag_admin_dismiss_url ) ); ?>" class="dismiss bctt-addon-nag-dismiss">Dismiss this <span
						class="dashicons dashicons-dismiss"></span></a>
				<h3
					class="bctt-addon-nag-header"><?php esc_html_e( 'Add Premium Style to your Better Click To Tweet boxes!', 'better-click-to-tweet' ) ?></h3>

				<a href="http://benlikes.us/bcttnag"
				   target="_blank" rel="noopener noreferrer"><img class="bctt-nag-photo" src="<?php echo esc_url( plugins_url() . '/better-click-to-tweet/assets/img/premium_style.png' ); ?>"/></a>

				<p class="bctt-addon-nag-copy"><?php esc_attr_e( 'Choose from multiple options when styling your Better Click To Tweet boxes, with no code.', 'better-click-to-tweet' ) ?></p>
				<ul class="bctt-addon-nag-list">
					<li><?php esc_html_e( 'Make your Better Click To Tweet boxes stand out.', 'better-click-to-tweet' ) ?></li>
					<li><?php esc_html_e( 'Get more clicks and tweets.', 'better-click-to-tweet' ) ?></li>
					<li><?php esc_html_e( 'Support the development of Better Click To Tweet!', 'better-click-to-tweet' ) ?></li>
				</ul>
				<p class="bctt-addon-nag-copy">
					<a class="bctt-nag-purchase-link" href="http://benlikes.us/bcttnag"
					   target="_blank"><?php esc_html_e( 'Purchase it today', 'better-click-to-tweet' ) ?></a> <?php esc_html_e( 'Save 8% with the code NAGSareTHEbest', 'better-click-to-tweet') ?></p>
			</div>
			<?php
		}
	}
}

// Commented out in 5.8.1 to try the other new nag for a bit.
// Added back in 5.9.3
 add_action( 'admin_notices', 'bctt_alerts' );

function bctt_addon_notice_ignore() {

	/**
	 * If user clicks to ignore the notice, add that to their user meta the banner then checks whether this tag exists already or not.
	 * See here: http://codex.wordpress.org/Function_Reference/add_user_meta
	 */

	if ( isset( $_GET['bctt_has_dismissed_nag'] ) && '0' == $_GET['bctt_has_dismissed_nag'] ) {

		//Get the global user
		$current_user = wp_get_current_user();
		$user_id      = $current_user->ID;

		add_user_meta( $user_id, 'bctt_has_dismissed_nag', 'true', true );
	}
}

// Commented out in 5.8.1 to try the other new nag for a bit.
// Added back in 5.9.3
add_action( 'current_screen', 'bctt_addon_notice_ignore' );


function bctt_more_alerts() {
	if (is_plugin_active('better-click-to-tweet-utm-tags/better-click-to-tweet-utm-tags.php')) {
		return;
	}

	$screen = get_current_screen();

	$screenparent = $screen->parent_file;


	if ( $screenparent == 'plugins.php' && current_user_can( 'install_plugins' ) ) {

		$user_id       = wp_get_current_user()->ID;
		$has_dismissed = get_user_meta( $user_id, 'bctt_has_dismissed_utm_nag', true );

		if ( ! $has_dismissed && ! defined( 'BCTTUTM_VERSION' ) ) {
				//add style inline so that it isn't enqueued if not used
			?>
            <style>
                span.bctt-more-addon-nag-header {
                    font-size: large;
                    font-weight: bold;
                }

                .updated.bctt-more-addon-nag {
                    padding: 1em;
                }

                p.bctt-more-addon-nag-copy {

                }

                ul.bctt-more-addon-nag-list {
                    list-style-type: none;
                    margin-left: 2em;
                }

                .bctt-more-addon-nag-list li:before {
                    content: "\f155";
                    display: inline-block;
                    -webkit-font-smoothing: antialiased;
                    font: normal 16px/1 'dashicons';
                    color: #46b450;
                    margin-right: 10px;
                    word-wrap: break-word;
                }

                .bctt-more-addon-nag-dismiss {
                    float: right;
                    text-decoration: none;
                }
                .bctt-nag-photo {
                    float: right;
                    margin:0;
                    max-height:105px;
                    max-width:98%;
                }
                @media screen and (max-width: 782px) {
                    .bctt-nag-photo {
                        float:none;
                    }
                }
                .bctt-more-nag-purchase-link {
                    font-size: large;
                }
            </style>
            <div class="updated bctt-more-addon-nag">

            <a href="<?php
		//The Dismiss Button.
		$nag_meta_key          = 'bctt_has_dismissed_utm_nag';
		$nag_admin_dismiss_url = 'plugins.php?' . $nag_meta_key . '=0';
		echo esc_url( admin_url( $nag_admin_dismiss_url ) ); ?>" class="dismiss bctt-more-addon-nag-dismiss">Dismiss this <span
                    class="dashicons dashicons-dismiss"></span></a>
        <h3
                class="bctt-more-addon-nag-header"><?php esc_html_e( 'NEW: Track how well your Better Click To Tweets are performing!', 'better-click-to-tweet' ) ?></h3>

        <a href="http://benlikes.us/bcttutmnag"
           target="_blank" rel="noopener noreferrer"><img class="bctt-nag-photo" src="<?php echo esc_url( plugins_url() . '/better-click-to-tweet/assets/img/utm-tags.jpg' ); ?>"/></a>

        <p class="bctt-more-addon-nag-copy"><?php esc_attr_e( 'Add UTM Codes to the URL that twitter users use to click back to your site!', 'better-click-to-tweet' ) ?></p>
        <ul class="bctt-more-addon-nag-list">
            <li><?php esc_html_e( 'Determine which BCTT boxes are converting best.', 'better-click-to-tweet' ) ?></li>
            <li><?php esc_html_e( 'Configurable site-wide and on individual boxes.', 'better-click-to-tweet' ) ?></li>
            <li><?php esc_html_e( 'Works with the block editor and with shortcodes.', 'better-click-to-tweet' ) ?></li>
        </ul>
        <p class="bctt-more-addon-nag-copy">
            <a class="bctt-more-nag-purchase-link" href="http://benlikes.us/bcttutmnag"
               target="_blank"><?php esc_html_e( 'Purchase it today', 'better-click-to-tweet' ) ?></a> <?php esc_html_e( 'Save 8% with the code NAGSareTHEbest', 'better-click-to-tweet') ?></p>
        </div>
		<?php
	}
	}
}

// commented out in 5.9.3 to try the other nag for a bit
// add_action( 'admin_notices', 'bctt_more_alerts' );

function bctt_more_addon_notice_ignore() {

	/**
	 * If user clicks to ignore the notice, add that to their user meta the banner then checks whether this tag exists already or not.
	 * See here: http://codex.wordpress.org/Function_Reference/add_user_meta
	 */

	if ( isset( $_GET['bctt_has_dismissed_utm_nag'] ) && '0' == $_GET['bctt_has_dismissed_utm_nag'] ) {

		//Get the global user
		$current_user = wp_get_current_user();
		$user_id      = $current_user->ID;

		add_user_meta( $user_id, 'bctt_has_dismissed_utm_nag', 'true', true );
	}
}

// commented out in 5.9.3 to try the other nag for a bit
// add_action( 'current_screen', 'bctt_more_addon_notice_ignore' );