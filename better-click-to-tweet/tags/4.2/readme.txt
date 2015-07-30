=== Better Click To Tweet ===
Contributors: ben.meredith@gmail.com
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=HDSGWRJYFQQNJ
Tags: click to tweet, twitter, tweet, twitter plugin, Twitter boxes, share, social media, post, posts, plugin, auto post, bitly, bit.ly, yourls, yourls.org, translation-ready 
Requires at least: 3.8
Tested up to: 4.2
Stable tag: 4.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Insert click to tweet boxes into your posts, simply and securely. This plugin is regularly updated, translation-ready, and secure.

== Description ==

= The Best Click To Tweet Plugin on the Market, FREE! =

This plugin allows you to easily create tweetable content for your readers. Using a simple shortcode, your selected text is highlighted and made tweetable. 

* Now [fully customizable](http://benlikes.us/bcttcustom "power user guide customization instructions") if you know CSS.
* Easily [remove the "via"](http://benlikes.us/7m "how to customize the better click to tweet plugin")
* Selectively [remove the URL](http://benlikes.us/7r "power user guide") from the resulting Tweet
* For the SEO-conscious: [make links nofollow.](http://benlikes.us/7r "power user guide")

><strong> Why Better?</strong><br>
>This plugin started as a complete retool of the "Click To Tweet" plugin by Todaymade. In addition to the user-focused changes above, the under-the-hood changes include correct character counting when dealing with non-Roman characters, translation-readiness, and use of the official shortcode API (which means security and forward-compatibility)


**Speaking of the area under the hood:** developers, please hop in and suggest improvements. You can submit pull requests at the [github repo](https://github.com/Benunc/better-click-to-tweet "plugin github repo") for this plugin. I'm also actively seeking translators to bring the usefulness of this plugin to non-English speakers. Please message me in the forums. 

Huge thanks to Andrew Norcross @norcross for the help with making the plugin even better as of v4.0 with enhancements to the Visual Editor's button. But you can't blame him for any of the other code!

Translators:

* Español (es_ES): Jordi Rosalez 
* Serbian (sr_RS): [Borisa Djuraskovic](http://www.webhostinghub.com/ "Web Hosting Hub")
* Finnish/Suomi (fi): [Sampsa Daavitsainen](http://www.calltoaction.fi/ "CallToAction.fi")
* Russian (ru_RU): webbolt 

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

Then, wherever you want to insert a Click to Tweet quote, use a shortcode in the format `[bctt tweet="xxxxxxxxxxxx"]` replacing the `xxxxxxx` with your tweetable quote. 

As of version 3.1, you can leave off the "via @YourHandle" on a tweet-by-tweet basis by including using a shortcode in the format of `[bctt tweet="xxxxxxxxxxx" via="no"]` 

In the visual editor, you can click the blue birdy icon in the toolbar and a correctly formatted shortcode will be inserted in your text. For more info or clarifications, start a [support thread](https://wordpress.org/support/plugin/better-click-to-tweet "support forum"). I'll actively answer.

= How does the URL shortener functionality work? =
Better Click To Tweet gives you the maximum number of characters possible. Allow me to explain:

Twitter automatically routes every link through its own URL shortener (you might recognize t.co as the domain they use). For their t.co links, the length is automatically truncated to 23 characters for URLs. This leaves 117 characters after the URL for you to use to compose your tweet. Even a link that is run through bit.ly or a yourls.org install is still routed through t.co in the tweet. 

The benefit of URL shorteners goes beyond just character length, though. Many users use bit.ly or a similar service to track numbers of clicks and other analytical data. I personally use yourls.org to power http://benlikes.us for my shortened links.

As of version 3.0, my plugin works alongside url shortening plugins to harness that power, if you choose to. 

WordPress has a feature called "shortlinks" which changes the long URL to something like yourdomain.com/?p=3435. Various plugins in the official repository exist to change that shortlink to one using other outside services. Using a combination of those plugins and mine, your Better Click To Tweet boxes can now display a trackable link. 

On the settings page for Better Click To Tweet, simply check the box indicating you'd like to use the short URL, and save changes. If you've got a plugin that correctly hijacks the built-in WordPress shortlink functionality, you're all set! I've tested my plugin with the following plugins, and will make every effort to keep this list updated:

* [WP Bitly](https://wordpress.org/plugins/wp-bitly/ "WP Bit.ly")
* [YOURLS Link Creator](https://wordpress.org/support/plugin/yourls-link-creator "YOURLS Link Creator")
* [Goo.gl](https://wordpress.org/plugins/googl/ "Goo.gl")

If you run into any issues with my plugin not working alongside a certain link shortener, start a [support thread](https://wordpress.org/support/plugin/better-click-to-tweet "support forum") and include a link to the other plugin. I'll see what I can do to work with the other developer.

I've also written a tutorial at http://benlikes.us/79 for how to set up the shortlinks with bit.ly and yourls.org.

= Are there any other hidden tricks? =
Yes! Because I want the majority of users (who aren't as concerned with options like nofollow links and getting rid of the URL in the tweet) to be happy, most options are hidden. I've written a tutorial for using those advanced options over at http://benlikes.us/7r 

= What do I do if it's not working right? =
I am active in [the support forums](https://wordpress.org/support/plugin/better-click-to-tweet "Better CTT Support"), and in patching the plugin. Start a thread there, and I will gladly help you out. Most of the time you can expect a few hours before a response. I'm in the eastern US, and I like playing with my kids at night, so you might not hear back immediately. Don't panic. 

= What are the differences between this plugin and Click To Tweet by Todaymade? =
I originally was planning on contacting Justin at Todaymade to suggest some improvements to the code on his plugin, and in the midst of that, his plugin was unexpectedly pulled from the official repository, for having a (frowned upon) "Powered By" link. So, I set about fully overhauling the plugin. 

Since that time Justin and the team over at Todaymade have rereleased a version of their plugin without the "Powered By" link. His plugin (which was originally released before the advent of the shortcode API) still doesn't use it, and for that reason is less secure than mine. For him to change to support the shortcode API is a more comlicated issue, as he'll need to still provide support for how his plugin currently handles the pseudo-shortcode system, or risk breaking every current user's website. 

The bottom line for an end user is that both plugins are great, with the main difference being mine using the official WordPress shortcode API, and some other minor tweaks to the code. For example, my plugin adds some calculation based on the length of your Twitter handle to prevent truncated tweets from still being over 140 characters. 

Also from an end user perspective, I am more active in the support forums than Justin (who has a paid product to support, which justifiably takes more of his time and energy), and you can count on my response and resolution to your issues! 

= How can I help? =
I want to maximize the usefulness of this plugin by translating it into multiple languages. So far I have included Spanish and Serbian. If you have experience translating .po files, please consider helping in that way. To include those translations in the official plugin, email me, or submit pull requests at Github.

== Screenshots ==

1. This in the editor...
1. Becomes this in your blog post!

== Changelog ==

= 4.2 =
* added the ability to specify a custom URL as a shortcode parameter. (more info at the power user tutorial at http://benlikes.us/7r )

= 4.1.1 = 
* added Russian translation
* added sample bcttstyle.css file (for moving to the root of the /uploads folder) to assets/css

= 4.1 =
* the plugin now looks for a custom css stylesheet before falling back to the default style, giving designers and developers full access to the CSS, without disrupting user experience for folks just looking to plug and play.
* added Finnish (fi) translation courtesy of Sampsa Daavitsainen at http://calltoaction.fi

= 4.0 = 
* complete overhaul of the visual editor button courtesy @norcross 
* added the ability to make all links "nofollow" by adding the parameter `nofollow="yes"` to the shortcode.
* complete overhaul of the settings page for better readability.

= 3.4.1 =
* added Serbian translation courtesy of [Borisa Djuraskovic](http://www.webhostinghub.com/ "Web Hosting Hub")

= 3.4 =
* small change to the outputted URL for vc3 compliance. Thanks to @tomazzaman on Github for the fix!

= 3.3 =
* fixed a bug that was creating (rare, server-configuration-related) "Fatal Error" notices for `mb_strlen()` and related multibyte functions.
* various code tweaks for readability and compliance with WordPress standards.

= 3.2.2 =
* fixed bug that was causing the URL not to display on certain clicks after the 3.2 update (thanks @aa_stardust for the heads up!)

= 3.2.1 =
* fixed the truncation math given the new options to remove the url (as of 3.2) and via (as of 3.1), to correctly get back all those lost characters.

= 3.2 =
* added the ability to leave off the url on a tweet-by-tweet basis (handwritten shortcodes only, the visual editor will not show it as an option on the popup window)
* code modifications for clarity.

= 3.1 =
* added the ability to leave off the `via @YourTwitterName` on a tweet-by-tweet basis.
* complete overhaul of the javascript file to enable that functionality without having to mess with hand-coding the shortcode.

= 3.0 =
* added option to use WordPress shortlink in place of full URL. 
* further refinement of the math used in calculating tweet truncation length.

= 2.0.3 =
* updated CSS to remove underline on "Click to Tweet" on Twenty Fifteen theme (and others!)

= 2.0.2 =
* fixed bug introduced in 2.0 related to javascript.

= 2.0.1 =
* added in support for RSS feeds: when outputting to an RSS feed, the click-to-tweet text will be smartly formatted.
* added css declarations to deal with issues in the Twenty Fifteen theme.
* readme enhancements for better user experience.

= 2.0 = 
* Major version release for internationalization: added Spanish (ES) translation, and updated code throughout for internationalization. Still to-do: add translation support for the tinymce plugin on the visual editor.

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

== Upgrade Notice ==

= 4.1 =
* added full customizability (instructions forthcoming at http://benlikes.us/7r )
* added Finnish translation.

= 4.0 = 
* complete overhaul of the visual editor button courtesy @norcross 
* added the ability to make all links "nofollow" by adding the parameter `nofollow="yes"` to the shortcode.
* complete overhaul of the settings page for better readability.

= 3.4.1 =
* added Serbian language files.

= 3.4 =
* small change to the outputted URL for vc3 compliance. Thanks to @tomazzaman on Github for the fix!

= 3.3 =
* fixed a bug for users experiencing Fatal Errors related to multibyte string functions.

= 3.2.2 =
* fixed bug that was causing the URL not to display on certain clicks after the 3.2 update (thanks @aa_stardust for the heads up!)

= 3.2.1 =
* fixed the truncation math given the new options to remove the url (as of 3.2) and via (as of 3.1), to correctly get back all those lost characters.

= 3.2 =
* added the ability to leave off the url on a tweet-by-tweet basis (handwritten shortcodes only, the visual editor will not show it as an option on the popup window)

= 3.1 = 
* Added ability to leave off the via @YourTwitterName

= 2.0.3 =
* CSS update for "Click to Tweet" text being underlined in some themes.

= 2.0.2 =
* Fixes bug that was causing the visual editor to not show the BCTT button.

= 2.0 =
* Adds internationalization, and out of the box support for Spanish (ES) language.


