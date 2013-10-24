=== Redux Framework ===
Contributors: ghost1227, dovyp
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=N5AD7TSH8YA5U
Tags: admin, admin interface, options, theme options, plugin options, options framework, settings
Requires at least: 3.5.1
Tested up to: 3.7
Stable tag: 3.0.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Redux is a simple, truly extensible and fully responsive options framework for WordPress themes and plugins which comes with a full demo integrated so you can dive right in!

== Description ==

Redux is a simple, truly extensible and fully responsive options framework for WordPress themes and plugins. Built on the WordPress Settings API, Redux supports a multitude of field types as well as custom error handling, custom field & validation types, and import/export functionality.

But what does Redux actually DO? We don't believe that theme and plugin
developers should have to reinvent the wheel every time they start work on a
project. Redux is designed to simplify the development cycle by providing a
streamlined, extensible framework for developers to build on. Through a
simple, well-documented config file, third-party developers can build out an
options panel limited only by their own imagination in a fraction of the time
it would take to build from the ground up!

Redux is an ever-changing, living system. Want to stay up to date or
contribute? Subscribe to one of our mailing lists or join us on [Twitter](https://twitter.com/reduxframework) or [Github](https://github.com/ReduxFramework/ReduxFramework)!

NOTE: Redux is not intended to be used on its own. It requires a config file
provided by a third-party theme or plugin developer to actual do anything
cool!

== Installation ==

1. Upload the "redux-framework" directory to "~/wp-content/plugins/".
2. Activate the plugin through the "Plugins" area in WordPress admin panel.

Once you've done that, you can activate the "Demo Mode" from the "Plugins" area of the admin panel. If you'd prefer to start building your own panel:

1. Copy the "~/redux-framework/sample/" directory from within the plugin to a directory within your own theme or plugin.
2. Click on "Deactivate Demo Mode" in the "Plugins" area of the WordPress admin panel to turn off the Redux integrated demo.
3. Edit the "~/sample/sample-config.php" file (now copied to your plugin or theme directory) and change the $args['opt_name'] value to anything custom you would like. Make sure this is truly unque so other plugins/themes can use Redux.
4. Include the sample-config.php file: ` <?php require_once(dirname(__FILE__).'/sample/sample-config.php'); ?>` in your theme functions.php file or within your theme's init file.
5. Modify the sample file to your heart's content.

For complete documentation and examples please visit: [http://reduxframework.com/docs/](http://reduxframework.com/docs/)


== Frequently Asked Questions ==

= Why doesn't this plugin do anything? =

Redux is an options framework... in other words, it's not designed to do anything on its own!

= How can I learn more about Redux? =

Visit our website at [http://reduxframework.com/](http://reduxframework.com/

== Screenshots ==

1. This is the demo mode of Redux Framework. Activate it and you will find a fully-function admin panel that you can play with.

== Changelog ==

= 3.0.0 =
* Initial Wordpress.org plugin release.

== Upgrade Notice ==

= 3.0 =
Redux is now hosted on Wordpress.org! Update in order to get proper, stable updates.

== Arbitrary section ==

Redux is primarily based on [NHP](https://github.com/leemason/NHP-Theme-Options-Framework) and [SMOF](https://github.com/syamilmj/Options-Framework "Slightly Modified Options Framework").