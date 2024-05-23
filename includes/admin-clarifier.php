<?php

defined( 'ABSPATH' ) or die( "No script kiddies please!" );

// Hook the callout box function to the bctt_settings_top action
add_action('bctt_settings_top', 'bctt_settings_clarifier');

function bctt_settings_clarifier() {
    ?>
    <div style="background-color: #333; color: #fff; padding: 20px; margin-bottom: 20px; border-radius: 5px;">
        <h2 style="color:#fff;">Why is this still called &quot;Better Click To Tweet&quot; and not something about X?</h2>
        <p>You may have noticed that this plugin has not been renamed to take into account the total rebrand of Twitter.com to X.com. The reason is simple, and threefold:</p>
        <ol>
            <li>I don't have a ton of developers sitting around with nothing to do. (or any piles of cash to pay someone)</li>
            <li>The wider world still calls the act of posting on X &quot;Tweeting&quot; and will for some time. Maybe long enough for me to get a pile of cash or several bored developers with a weekend to spare?</li>
            <li>The &quot;brand name&quot; itself is embedded into lots of places in the code (and the URL that I sell premium addons, etc) and would be tough to change.</li>
        </ol>
        <p>I'm committed to keeping this plugin functional as long as the X platform allows posting via web intents. And I'll still call it "tweeting," like a curmudgeon.</p>
        <p>If you have any questions whatsoever, don't be a stranger. Reach out over at <a href="https://www.betterclicktotweet.com">My website</a> and I'm happy to clarify!</p>
        <p>Happy <s>Tweeting</s> Posting to X!</p>
    </div>
    <?php
}
?>
