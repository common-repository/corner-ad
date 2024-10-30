=== Corner Ad ===
Contributors: codepeople
Donate link: https://wordpress.dwbooster.com/content-tools/corner-ad
Tags:corner ad,ad,ads,advertising,promotion,advertiser,banner,image,links,url,tracking,images,audio,admin,posts,Post,page,plugin,shortcode
Requires at least: 3.0.5
Tested up to: 6.7
Stable tag: 1.1.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Corner Ad is a minimally invasive advertising display that uses any of your webpage's top corners - a position typically under-utilized by developers - and attracts users' attention by a cool visual effect imitating a page flip.

== Description ==


Corner Ad features:

» Minimally invasive
» Display the ad at top corner area of the page
» The animation in the Corner Ad captures the user's attention
» It is possible to associate an image and an URL to the Corner Ad
» The target of the link can be selected
» Configurable background color for the ad to match the web page design
» Automatic mirror effect
» Configurable time for automatic open and close actions


**Corner Ad** is a minimally invasive advertising display that uses any of your webpage's top corners - a position typically under-utilized by developers - and attracts users' attention by a cool visual effect imitating a page flip. **Corner Ad** initially displays a partial ad, which attracts attention; when user hover the mouse over it, the ad opens to its full size while imitating a page-flip effect; once the mouse is moved away, the ad returns to its initial size.

**Corner Ad** can also be used for announcements, newsflashes, and basically for anything else that you consider important and want your website visitors to see. It is common nowadays that visitors quickly scan the contents of a page and often fail to notice messages that you would like them to see; **Corner Ad** attempts to resolve this issue.

**More about the Main Features:**

* Minimally invasive.
* Display the ad at top corner area of the page.
* The target of the link can be selected, so it can open the new page in the same browser's window or into a new window.
* Configurable background color for the ad to match the web page design.
* Automatic mirror effect
* Configurable time for automatic open and close actions.
* The corner ad effect is based on HTML 5 using the SVG tags.

The base plugin, available for free from the WordPress Plugin Directory has all the features you need to create an audio and video player on your website.

**Premium Features**

* Supports multiple ads into the same website (each ad in a different page) to promote different products.
* Allows the selection of which top corner area of the page (right or left) will be used to display the ad to avoid overwriting important elements like logos, menus or search features.
* Count the number of click for Ads. The number of clicks on the ads, measures the impact of advertising on the web site's users.
* Allows to associate multiple images to the same ad. The ad image is selected  randomly each time is loaded the page containing the Corner Ad.
* Allows to associate a audio file to the advertising obtaining a bigger impact. The audio file will play in background.
* Allows to enter multiple ids in the shortcode, separated by comme, for example: `[corner-ad id="1,2,3"]`, to select randomly the ad to include in the webpage.

The "Corner Ad" is the perfect replacement of the old and invasive banners, the "Corner Ad" is small but its location and animation is able to capture the user's attention.

The "Corner Ad" plugin allows the specific integration with the following editors:

* Classic WordPress Editor.
* Gutenberg Editor.
* Elementor.
* Page Builder by SiteOrigin.
* BeaverBuilder Editor.
* For other editors, the shortcodes should be inserted using other controls of general purpose, like the controls for inserting HTML or shortcodes.

**Demo of Premium Version of Plugin**

[https://demos.dwbooster.com/corner-ad/wp-login.php](https://demos.dwbooster.com/corner-ad/wp-login.php "Click to access the Administration Area demo")

[https://demos.dwbooster.com/corner-ad/](https://demos.dwbooster.com/corner-ad/ "Click to access the Public Page")


If you want more information about this plugin or another one don't doubt to visit my website:

[https://wordpress.dwbooster.com](https://wordpress.dwbooster.com "CodePeople WordPress Repository")

== Installation ==

**To install Corner Ad, follow these steps:**

1. Download the plugin (zip file).
2. Go to the WordPress Plugins menu in the dashboard area.
3. Press the "Add New" button.
4. Click the "Upload" link and select the downloaded plugin.
6. Once installed click "Activate" to enable it.

== Interface ==

**Creating an Ad**

To create an ad go to the settings page under the menu "Settings > Corner Ad" or the link "settings" into the plugins area. The first screen will display the list of ads already created and a button "Create New Ad" for adding new ads.

* To create a new ad, use the "Create New Ad" button.
* To edit an existent ad, use the "Edit" button related to the ad.
* To delete an ad, use the "Remove" button related to the ad.

The edition screen allows the configuration of the ad featuring the following options:

* Ad name: Name used to identify the ad in the list.
* Ad Link: Link to a page that will be opened when the user clicks on the ad. The link must be an absolute URL if you are promoting an external website.
* Open Ad in: The options are "New page", to open the page in a new browser window, or "Self Page", to keep the navigation in the same browser window.
* Ad image: Address (URL) of the image that will be used for the ad. If you click the "Browse" button the WordPress media library will be opened to select one of the images already available in your WordPress website and you can also add new images there. It is possible to associate multiple images to the same Ad, the images will be randomly selected to display in page.
* Ad audio: Address (URL) of audio file to play in background.
* Set as mirror: Automatically enable the image mirror on the ad.
* Use corner with color:  Applies a color to the ad cover.
* Display Ad in corner:  For selecting which top corner (Left or Right) will be used to display the ad.
* Open corner in: Time in seconds to automatically open the ad.
* Close corner in: Time in seconds to automatically closet the ad.

Schedule

* From (optional): Date with the format yyyy-mm-dd. If a date is entered, the ad is displayed from this date (included).
* To (optional): Date with the format yyyy-mm-dd. If a date is entered, the ad is displayed until this date (included).


**Inserting the Ad**

To inset the ad into a post or page open the post/page for editing and use the "Corner Ad" icon that is located above the editor.

A floating panel will appear allowing the selection of the corner ad to be inserted into the post/page. Once selected a shortcode with the ad's ID will be inserted into the content., for example: `[corner-ad id="3"]` (the 3 is the ID of the ad in this sample).

To insert an ad directly into the website theme to display it across all the pages or into specific sections, edit the template and insert the following code fragment:

	<?php echo do_shortcode('[corner-ad id="3"]'); ?>

To identify the ID that belongs to each ad, go to the page in the settings area where the ads are created. On that list each ad has its shortcode with its ID.

== Frequently Asked Questions ==

= Q: Why the image does not cover the entire corner? =

A: The Corner Ad has square form, so it is recommended to use proportional images (it is recommended the use of square images greater than 400x400 pixels).

= Q: Is supported the plugin "Corner Ad" on mobiles? =

A: Yes it is. From the version 1.0.5, the "Corner Ad" plugin was re-implemented to be supported natively by the most popular browsers, even their mobile versions.

== Screenshots ==
1. Corner Ad Preview
2. Plugin Installation
3. Menu location
4. Plugin Settings
5. Setting Page with the List of Ads
6. Isertion Icon
7. Corner Ad Insertion Interface
8. Shortcode of Corner Ad with the Corresponding ID
9. Gutenberg Block
10. Elementor Widget
11. Page Builder by SiteOrigin
12. BeaverBuilder

== Changelog ==

= 1.0 =

* First version released.

= 1.0.1 =

* Improves the plugin documentation.
* Increase the z-index assigned to the corner ad, to solve an issue with the headers in some themes.
* Allows the use of new versions of jQuery framework, include with the latest updates of WordPress.
* Crop the images used in the corner ad in square size.

= 1.0.2 =

* Reimplementation of the Corner Ad. The new version uses SVG to extend the support to the mobiles devices.

= 1.0.3 =

* Loads the Corner Ad directly, and not through jQuery, to prevent an issue on web pages, where jQuery is loaded directly from a CDN server, and not from the website.

= 1.0.4 =

* Modifies the z-index assigned to the Corner Ad.

= 1.0.5 =

* Modifies the styles assigned to the Corner Ad.

= 1.0.6 =

* Allows to select a different image for the shrunken status of the corner ad.

= 1.0.7 =

* Modifies the Ad animation, loading better the big image.

= 1.0.8 =

* Calls the esc_html function of WordPress for fixing a vulnerability with the name of the "Corner Ad" (Thanks to www.exploit-db.com for identifying the vulnerability and warn us)

= 1.0.9 =

* Improves the access to the help.
* Includes a predefined language file.

= 1.0.10 =

* Includes some changes in the dialog to insert the shortcode.
* Improves the access to the plugin page.

= 1.0.11 =

* Modifies the module for accessing the WordPress reviews section.

= 1.0.12 =

* Fixes an issue in the promote banner.

= 1.0.13 =

* Fixes some minor issues.

= 1.0.14 =

* Includes the integration with Gutenberg, the editor that will be distributed with the next versions of WordPress.

= 1.0.15 =

* Improves the integration with the Gutenberg editor.

= 1.0.16 =

* Modifies the way the shortcode is displayed in the settings page of the plugin.

= 1.0.17 =

* Removes and optimize some queries to increase the plugin's performance.

= 1.0.18 =

* Modifies the activation/deactivation modules to improve these processes.

= 1.0.19 =

* Modifies the integration with the Gutenberg editor, to adjust the plugin to the latest version of Gutenberg.

= 1.0.20 =

* Hides the promotion banner for the majority of roles and fixes a conflict between the promotion banner and the Gutenberg editor.

= 1.0.21 =

* Fixes some texts in the deactivation window.

= 1.0.22 =

* Includes a new section in the settings page of the plugin to allow showing an Ad by default on every page of the website, the homepage, or pages of specific post types (the shortcodes inserted on pages have precedence)

= 1.0.23 =

* Fixes an issue scaling the images in Ads.

= 1.0.24 =

* Solves a conflict with the "Speed Booster Pack" plugin.

= 1.0.25 =

* Fixes a conflict with the latest update of the Gutenberg editor.

= 1.0.26 =

* Fixes an issue between the Promote Banner and the official distribution of WP5.0
* Includes an auto-update and registration module, in the commercial version of the plugin.

= 1.0.27 =

* Modifies the language files and the header section of the plugin.

= 1.0.28 =

* Includes two new attributes for controlling the ads by date intervals.

= 1.0.29 =

* Fixes an issue with from and to attributes in the ads settings.

= 1.0.30 =

* Includes specific widgets to integrate the plugin with the Elementor editor.
* Modifies the blocks for the Gutenberg editor,  preparing the plugin for WordPress 5.1

= 1.0.31 =

* Includes specific widgets to integrate the plugin with the Page Builder by SiteOrigin.

= 1.0.32 =

* Fixes an issue with the from and to attributes in the ads settings.

= 1.0.33 =

* Fixes some notices in the code.

= 1.0.34 =

* Modifies the shortcode to allow inserting them without the id attribute, in whose case the plugin would display the existent ad in the free version, or any of ads in the professional version of the plugin.
* Includes a new option in the settings page of the plugin allowing to select the default Ad randomly.

= 1.0.35 =

* Fixes some issues with the quotes signs.
* Fixes some conflicts with the pages builders, and browsers extensions.
* The commercial version of the plugin allows to insert two ads at once in the same page, one per corner-top.

= 1.0.36 =

* Includes the integration with the BeaverBuilder Editor.

= 1.0.37 =

* Modifies the plugin to allow the click events on ads be registered by JetPack.

= 1.0.38 =

* Modifies the access to the demos.

= 1.0.39 =

* Includes tips directly in the ads settings.
* Updates the language files.

= 1.0.40 =

* Adapts the plugin's block to the latest version of the Gutenberg editor.

= 1.0.41 =

* Fixes an issue escaping the ampersand symbols in some URLs.

= 1.0.42 =

* Includes a new section to integrate the ads with Google Analytics, to generate a new event with every click action.

= 1.0.43 =

* Includes a video tutorial in the plugin's settings to improve the users' experience.

= 1.0.44 =

* Fixes a conflict with Elementor.

= 1.0.45 =

* Improves the accessibility.

= 1.0.46 =

* Modifies the behavior of the ads for scaling in small screens.

= 1.0.47 =

* Improves the integration with the gutenberg editor.

= 1.0.48 =

* Modifies the Elementor widget.

= 1.0.49 =

* Includes a new option in the Ad settings to configure the device (Mobile or Desktop) where display the Ad.

= 1.0.50 =

* Modifies the Elementor widget.

= 1.0.51 =

* Modifies the Google Analytics integration giving more control over the Analytics events information.
* Optimizes the plugin code.

= 1.0.52 =

* Fixes an issue in the redirection to the Ads' URLs.

= 1.0.53 =

* Modifies functions deprecated by the latest Elementor update.

= 1.0.54 =
= 1.0.55 =
= 1.0.56 =

* Improves the plugin code and security.

= 1.0.57 =

* Fixes a vulnerability detected by the Wordfence Threat Intelligence Team, and special thanks to Marco, Associate Vulnerability Analyst.

= 1.0.58 =

* Improves the plugin security.

= 1.0.59 =

* Modifies the integration with Elementor to ensure compatibility with the latest version of Elementor.

= 1.0.60 =

* Modifies the preprocessing of the images.

= 1.0.61 =

* Modifies the benner module.

= 1.1.0 =

* Removes deprecated jQuery code.

= 1.1.1 =

* Fixes a conflict in the activation process with WP6.5.