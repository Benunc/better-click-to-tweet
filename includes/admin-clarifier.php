<?php

defined( 'ABSPATH' ) or die( "No script kiddies please!" );

// Hook the callout box function to the bctt_settings_top action
add_action('bctt_settings_top', 'bctt_settings_clarifier');

function bctt_settings_clarifier() {
    ?>
    <div style="background-color: #333; color: #fff; padding: 20px; margin-bottom: 20px; border-radius: 5px;">
        <h2 style="color:#fff;">Better Click To Share (formerly Better Click To Tweet)</h2>
        <p>We've rebranded to Better Click To Share to reflect that readers share on X (formerly Twitter). The shortcode, block, and all features work the same.</p>
        <p>I'm committed to keeping this plugin functional as long as the X platform allows posting via web intents.</p>
        <p>If you have any questions whatsoever, don't be a stranger. Reach out over at <a href="https://www.betterclicktoshare.com">My website</a> and I'm happy to clarify!</p>
        <p>Happy posting to X!</p>
    </div>
    <?php
}
?>
