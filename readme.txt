=== Plugin Name ===
Contributors: jag1989
Donate link: http://www.jgmediahouse.com
Tags: strings, custom fields
Requires at least: 3.4.0
Tested up to: 3.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin will allow you to input custom fields into the admin control panel, then you will be able to call these strings from your template files.

== Description ==

Have you ever wanted to have string input fields where you can input any string, and then call this from your templates? If so this is the plugin for you!
WP-Strings allows you to input strings, that you can then call as many times as you want within your wordpress template.

If you update one of these strings from within the admin section, the string will change accross all of your templates.


For example, if you had a phone number that you used in multipe locations across your theme, but knew it might change,
you can put this into the WP-Strings admin panel. You can then reference the telephone number from your template in the required
locations and know in confidence that when you change the number in the backend, the telephone number will change across the site.

== Installation ==



1. Upload the WP-Strings folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place 'string_option();' in your templates
4. string_option() takes two arguments, firstly the name of the String you want to output (not case sensative). Secondly the echo value, default is 1(echo), 0 will just return the value


`<?php string_option('telephone', 1);  ?>`

If you wish to use a string from within the content, you can use the shortcode [string s=""], within the 's' attribute add in the name of your string you require.


== Screenshots ==

1. Here's a screenshot a list of all custom strings that are stored in the database
2. Here's a screenshot of the custom strings option

== Changelog ==

= 0.1 =
* First itteration of WP-Strings
* Added custom strings
* Added support to delete custom strings

= 0.2 =
* Added shortcode functionality
