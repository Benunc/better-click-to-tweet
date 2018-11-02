=== Better Click To Tweet ===
Contributors: ben.meredith@gmail.com, wpsteward
Donate link: https://www.wpsteward.com/donations/plugin-support/
Tags: click to tweet, twitter, tweet,
Requires at least: 3.8
Tested up to: 4.9
Stable tag: 5.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Insert click to tweet boxes into your posts, simply and securely. This plugin is customizable, regularly updated, and future-proof.

== Description ==

= The most popular Click To Tweet Plugin for WordPress (by a mile), for good reason. =

This plugin allows you to easily create tweetable content for your readers. Using a simple shortcode, your selected text is highlighted and made tweetable. 

* Now [fully customizable](http://benlikes.us/bcttcustom "power user guide customization instructions") if you know CSS.
* Easily [remove the "via"](http://benlikes.us/7m "how to customize the better click to tweet plugin")
* Selectively [remove the URL](http://benlikes.us/7r "power user guide") from the resulting Tweet
* For the SEO-conscious: [make links nofollow.](http://benlikes.us/7r "power user guide")

><strong> Why Better?</strong><br>
>This plugin started as a complete retool of the "Click To Tweet" plugin by Todaymade. In addition to the user-focused changes above, the under-the-hood changes include correct character counting when dealing with non-Roman characters, translation-readiness, and use of the official shortcode API (which means security and forward-compatibility)


**Speaking of the area under the hood:** developers, please hop in and suggest improvements. You can submit pull requests at the [github repo](https://github.com/Benunc/better-click-to-tweet "plugin github repo") for this plugin.

Huge thanks to Andrew Norcross @norcross for the help with making the plugin even better as of v4.0 with enhancements to the Visual Editor's button. But you can't blame him for any of the other code!

Translation is now managed at [the official WordPress translation page](https://translate.wordpress.org/projects/wp-plugins/better-click-to-tweet "WordPress translation"). Thanks to the folks who helped translate in the past.

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

= Are there other style options? =
Yes! If you are a developer, there are [limitless options for styling](http://benlikes.us/bcttcustom "custom style for Better Click To Tweet").

If you are not a developer, I have released a premium add-on for selecting among several (and growing) different style options. Check out [Premium Styles](http://benlikes.us/bcttps "Premium Styles for Better Click To Tweet")

= How does Better Click To Tweet work? =
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

I've also written [a tutorial](http://benlikes.us/79 "shortlink tutorial") for how to set up the shortlinks with bit.ly and yourls.org.

= Are there any other hidden tricks? =
Yes! Because I want the majority of users (who aren't as concerned with options like nofollow links and getting rid of the URL in the tweet) to be happy, most options are hidden. I've written [a tutorial for using those advanced options](http://benlikes.us/7r "power user guide")

= What do I do if it's not working right? =
I am active in [the support forums](https://wordpress.org/support/plugin/better-click-to-tweet "BCTT Support"), and in patching the plugin. Start a thread there, and I will gladly help you out. Most of the time you can expect a day or two before a response. I'm in the eastern US, and I like playing with my kids at night, so you might not hear back immediately. Don't panic.

= How can I help? =
Translations: https://translate.wordpress.org/projects/wp-plugins/better-click-to-tweet
Pull Requests: https://github.com/Benunc/better-click-to-tweet
Donations: http://benlikes.us/donate

== Screenshots ==

1. This in the editor...
1. Becomes this in your blog post!

== Changelog ==

= 5.6.0 =
* fix — improving compatibility with WordPress 5.0 and the new block-based (Gutenberg) editor. Thanks again to @ajitbohra for the help
* fix — resolve "invalid headers" problem on certain new installs

= 5.5 =
* Enhancement — added compatibility with WordPress 5.0's new editor, codenamed Gutenberg (thanks to Ajit Bohra @ajitbohra for all the great work!)
* Enhancement — Some CSS modifications to make the plugin play nicely with the new editor.
* Enhancement — changed the twitter bird icon for an updated one on the front end. (Thanks to @oliverpitsch for the PR on Github.)


= 5.4.3 =
* Security fix — added a line to the output that secures the target _blank vulnerability. Thanks to @dmv912 on the wordpress forums for the call-out!

= 5.4.2 =
* Fix — removed a line that was breaking things.

= 5.4.1 =
* Fix — made the settings page more accessible with the help of Rachel Cherry's fantastic wa11y plugin. https://wordpress.org/plugins/wa11y/ Thanks Rachel!
* Started tinkering with Gutenberg compatibility, the new editor experience coming to WordPress, to make BCTT compatible with the Future of WordPress (no file changes on this, just wnate to let you know it's on the radar. Learn more at https://wordpress.org/gutenberg

= 5.4 =
* Enhancement — Now supports Twitter's new 280 character limit. Note that non-roman characters may effect the new truncation in strange ways. Please report any such strangeness to me in the support forums.
* Enhancement — updated some links in the back end to link to the all new (and still kinda bland) https://www.betterclicktotweet.com

= 5.3.1 =
* Security Fix — potential XSS vulnerability on settings page. (mild security risk only affecting logged-in administrator accounts previously compromised). Thanks Robbie at DXW @robbiepaul on Github for the responsible disclosure.
* If the last point didn't make any sense, please translate it to "UPDATE NOW. Previous versions of the plugin are mildly vulnerable to enterprising attackers."

= 5.3 =
* enhancement — cleaned up the various options checks that were happening on the front end (thanks @igmoweb on GitHub!)
* Readme changes. I also celebrated a milestone of 20K active installs, which was cause for great celebration, and at least one undocumented happy dance.

= 5.2.1 =
* fix — unused $handle_code variable has been removed. Was causing some errors for folks.
* fix — code introduced in 5.2 messed up the "via" option and the option to not include the URL. This has been fixed.

= 5.2 =
* security fix — adds (more) escaping throughout the plugin. Thanks to Paul de Wouters from HumanMade for the PR!
* enhancement — tested for compatibility with WordPress core 4.8
* but seriously, you should check out Premium Styles. http://benlikes.us/bcttps

= 5.1 =
* enhancement — I added a highly dismissable notice to the plugins page upon update, encouraging people to purchase my Premium styles add-on. The notice only shows to folks who have not previously customized their own styles, and once dismissed will never be shown to that user again.
* enhancement — I made several changes to the readme file so that the plugin is showcased nicely in the WordPress Plugin Directory's new design.

= 5.0.2 =
* fix — I was incorrectly calling translation module, breaking things when people updated to the latest version of Yoast SEO.

= 5.0.1 =
* fix — "subscribe" text on plugin settings page was not translatable.
* New customers are enjoying Premium Styles: http://benlikes.us/bcttps

= 5.0 =
* enhancement — settings page now looks good on mobile. Before it looked a bit like someone was actively hitting it with a bat.
* enhancement — added multiple action hooks to the settings page. This provides third party developers with the ability to add things to that page without hacking the core code.
* enhancement — made the function enqueuing the front end styles DRYer.
* change — the previous way (version 4.9) I had filtered the function enqueuing styles was throwing PHP warnings when I used it, so I introduced an options-based method for never enqueueing it in the first first place. Updated gist for that: http://benlikes.us/bcttgist2 This method will also pave the way for the most exciting update (for me) in the history of Better Click To Tweet:
* change — introduced the first premium add-on (Premium Styles), and made some changes to the settings page to facilitate that. The goal is threefold: (1) Don't introduce the option to folks who have already replaced the custom stylesheet using the power user guide http://benlikes.us/7r or by dequeueing the stylesheet using this gist http://benlikes.us/bcttgist1  (2) Introduce the option to purchase custom styles in a non-sleazy way. (3) Um, introduce the option to my tens of thousands of happy users to pay me for something.


= 4.10 =
* enhancement — added a 'prompt' shortcode attribute, so not you can change the words "Click To Tweet" on a per-box basis. Thanks to @apearlman on the Wordpress.org support forums for the suggestion!
* fix — changed the way the shortcode attributes are called, because my IDE kept throwing strange errors because the code wasn't clean enough.
* fix — removed some unused local variables because I used to not know how to code things good.
* dream — thought about ways to monetize this plugin, but ultimately decided against it for now, because I hate dashboard nags. But if you like it, donate: http://benlikes.us/donate
* change — decided to go with 4.10 instead of 5.0 in an attempt to move toward a version numbering system that makes sense, because I have learned much since 0.X --> 1.0.
* just for kicks and totally unrelated — wrote a cool post about rolling back WordPress plugins: http://benlikes.us/fk

= 4.9 =
* enhancement - Made the function that registers and enqueues the scripts filterable, so that developers who want to put all styles for Better Click To Tweet boxes in their theme files are able to do so using this snippet: https://gist.github.com/Benunc/533985231bbfde551d019620f628a921
* two updates in a row with no love for the non developer, but MUCH love for the developer. Trust me, this one is big time for CSS developers to be able to add styles to themes.
* changed some specifics in the FAQ, and the Readme in general. Encouraged people to donate at http://benlikes.us/donate

= 4.8 =
* made span classes filterable, so that other developers don't hate me when they try to extend the plugin.
* I know that first point doesn't sound like much, but it's huge for developer geeks. You're just gonna have to trust me.
* removed the names of translators from the "description" since they are now being handled by official language packs, and giving credit where it is due was getting complicated.

= 4.7.1 =
* updated links throughout the back end of the plugin to send me money, for people who are into that sort of thing. Shoutout to https://givewp.com for the SWEET donation integration on my site.

= 4.7 =
* added the ability to change the "via" addendum on a per-box basis using the new "username" shortcode attribute. The default behavior is (still) to go with the username you saved on the settings page. 
* (non-geek explanation of that first point) Now if you have a guest post by @KanyeWest, your Better Click To Tweet box can add "via @KanyeWest" automatically to your reader's tweets!
* made some changes to the toolbar popup in the visual editor to facilitate the new "username" attribute, limiting confusion and causing much rejoicing.
* Made unsuccessful attempt at getting Kanye West to guest post as the ultimate demonstration of the new feature.
* Tested for compatibility with the upcoming WordPress 4.5, and I don't mean to sound arrogant, but it pretty much NAILS compatibility with 4.5.
* Added a module that shows up when a user is using WordPress in a language for which there is incomplete (or non-existent) translations for this plugin. For users where there is a complete (+90%) translation, nothing will show up. But for users where the translation is incomplete, they'll be encouraged to help with the translation efforts!

= 4.6.2 =
* Removed extra (old and unused) js file.
* changed some back-end links to go to my new page, www.wpsteward.com
* realized that my use of tags in the WP repo miiiight have been a touch on the obnoxious and unhelpful side, so now I just use 3.
* successfully overthrew an oppressive regime in my 5-year-old's preschool "mystery reader" cartel. Take that, Fox in Socks.

= 4.6.1 =
* Removed call to external twitter script for security concerns.
* changed some wording on the description.

= 4.6 =
* The click to tweet box is now output as a `<span` instead of a `<div>` giving the ability (with custom CSS) to "inline" a click to tweet box.

= 4.5.3 =
* fixed an issue that was causing browsers to load older cached versions of the css.
* made no noticable attempts at overthrowing governments, but I did post something on facebook about Donald Trump which made some waves.

= 4.5.2 =
* updated css for compatibility with the twenty sixteen theme.
* updated compatibility to WordPress 4.4.
* unsuccessful overthrow of all world governments. I would have gotten away with it too, if it weren't for those meddling kids.

= 4.5.1 =
* fixed bug introduced in v 4.5 incorrectly displaying ellipses on truncated tweets.
* added Greek translation.

= 4.5 =
* fixed issue causing tweets to display incorrectly on iOS Twitter App (thanks to Cameron Conaway for the bug report in the forums)
* Ampersands are now correctly displayed.
* began master plan moving toward world domination.

= 4.4.1 =
* Fixed CSS issue with spacing in the default theme.

= 4.4 =
* Twitter now opens in a new modal box instead of a new tab, (developers: this is done using a call to platform.twitter.com/widgets.js within the code of the output from the shortcode. This means that the script won't be loaded on pages or posts where it's not needed.)

= 4.3.1 =
* added Italian language

= 4.3 =
* added translation support (internationalization or i18n) to the button on the visual editor. Now the entire plugin is translatable!
* updated swedish, finnish, and spanish language files.

= 4.2.1 =
* added German and Swedish translations, updated info in readme with link to instructions for migrating from Click To Tweet to Better Click To Tweet.
* made minor change to the bcttstyle.css (sample) file that was bugging me on hover.

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
= 4.3 =
* added translation support, updated three languages.

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


