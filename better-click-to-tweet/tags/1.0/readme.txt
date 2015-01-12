=== Better Click To Tweet ===
Contributors: ben.meredith@gmail.com
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=HDSGWRJYFQQNJ
Tags: click to tweet, twitter, tweet, twitter plugin, Twitter boxes, share, social media, post, posts, plugin, auto post
Requires at least: 3.8
Tested up to: 4.1
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add click to tweet boxes securely, using the official WordPress API. An overhaul of the Click To Tweet plugin by Todaymade.

== Description ==

This plugin allows you to easily create tweetable content for your readers. Using a simple shortcode, your selected text is highlighted and made tweetable. 

This is a complete retool of the "Click To Tweet" plugin by Todaymade. It now uses the WordPress shortcode API, making it more secure, and I've cleaned up the CSS from the old plugin. Perhaps most significantly, I removed the "powered by Coschedule" link. For more on the differences between this plugin and the Click To Tweet plugin by Todaymade, read the FAQ section.

I am working on a tutorial to swtich to my plugin from the previous version in a way that doesn't mean you have to switch every shortcode by hand. Stay tuned! 

Don't be scared to donate, if this plugin makes your blogging life any better.

Also, developers, please hop in and suggest improvements. You can submit pull requests at the [github repo](https://github.com/Benunc/better-click-to-tweet "plugin github repo") for this plugin, or go through the official svn repo here. 

== Installation ==

**To install the plugin manually in WordPress:**

1. Login as Admin on your WordPress blog.
2. Click on the "Plugins" tab in the left menu.
3. Select "Add New."
4. Click on "Upload" at the top of the page.
5. Select the 'better-click-to-tweet.zip' on your computer, and upload. Activate the plugin once it is uploaded.

**To install the plugin manually with FTP:**

1. Unzip the 'better-click-to-tweet.zip' file. Upload that folder to the '/wp-content/plugins/' directory.
2. Login to your WordPress dashboard and activate the plugin through the "Plugins" tab in the left menu.

== Frequently Asked Questions ==

= How Does Better Click To Tweet Work? =
Better Click To Tweet enables you to create beautiful Click To Tweet boxes in your blog posts. Once you've installed and activated the plugin, click on the settings link to put in your Twitter username, and save the settings. 

Then, wherever you want to insert a Click to Tweet quote, use a shortcode in the format `[bctt tweet="xxxxxxxxxxxx"]` replacing the xxxxxxx with your tweetable quote. 

In the visual editor, you can click the blue birdy icon in the toolbar and a correctly formatted shortcode will be inserted in your text. For more info or clarifications, start a [support thread](https://wordpress.org/support/plugin/better-click-to-tweet "support forum"). I'll actively answer.

= What do I do if it's not working right? =
I am active in [the support forums](https://wordpress.org/support/plugin/better-click-to-tweet "Better CTT Support"), and in patching the plugin. Start a thread there, and I will gladly help you out. Most of the time you can expect a few hours before a response. I'm in the eastern US, and I like playing with my kids at night, so you might not hear back immediately. Don't panic. 

= What are the differences between this plugin and Click To Tweet by Todaymade? =
I originally was planning on contacting Justin at Todaymade to suggest some improvements to the code on his plugin, and in the midst of that, his plugin was unexpectedly pulled from the official repository, for having a (frowned upon) "Powered By" link. So, I set about fully overhauling the plugin. 

Since that time Justin and the team over at Todaymade have rereleased a version of their plugin without the "Powered By" link. His plugin (which was originally released before the advent of the shortcode API) still doesn't use it, and for that reason is less secure than mine. For him to change to support the shortcode API is a more comlicated issue, as he'll need to still provide support for how his plugin currently handles the pseudo-shortcode system, or risk breaking every current user's website. 

The bottom line for an end user is that both plugins are great, with the main difference being mine using the official WordPress shortcode API, and some other minor tweaks to the code. For example, my plugin adds some calculation based on the length of your Twitter handle to prevent truncated tweets from still being over 140 characters. 

Also from an end user perspective, I am more active in the support forums than Justin (who has a paid product to support, which justifiably takes more of his time and energy), and you can count on my response and resolution to your issues! 

== Screenshots ==

1. This in the editor...
1. Becomes this in your blog post!

== Changelog ==

= 1.0 =
* updated the tweet length math to correctly parse text with non-standard characters. Thanks to WordPress forum user zachop at https://wordpress.org/support/topic/incorrect-handling-length-count-of-multi-byte-strings for the tip.
* various code cleanup issues, to make my code more readable and complaint with WordPress standards.

= 0.4 =
* rescued 9 characters that were being stolen by some incorrect math in my tweet-generating function. (now tweets won't be truncated until they actually need to be).

= 0.3 =
* added margin on the bottom of the bcct-clicktotweet div

= 0.2 =
* fixed a bug that was not displaying the CSS correctly.
* updated the FAQ and other readme items.

= 0.1 =
* Initial release. 
