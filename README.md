# Redux Options Framework

Wordpress options framework which uses the [WordPress Settings API](http://codex.wordpress.org/Settings_API "WordPress Settings API"), Custom Error/Validation Handling, Custom Field/Validation Types, and import/export functionality.

## Getting Started with Redux ##

ReduxFramework has been built from the group up as a auto-updating plugin. Updates are served from the Wordpress.org plugin directory. In this way Redux can be used by Themes and Plugins multiple time through the same insance.

To install the plugin, just download the master branch zip file, and install as you would any other Wordpress plugin.


## Creating a config file ##

Inside the plugin directory is a `sample` folder. Copy this into your theme or plugin. DO NOT modify anything within the plugin or you will all your work at each update of the Redux Plugin.

Include the `sample-config.php` file in your theme `functions.php` or plugin as follows:

```php
require_once('path/to/copied/sample/sample-config.php');
```

Edit ```sample-config.php``` as needed.




## Donate to the Framework ##

If you can, please donate to help support the ongoing development of Redux Framework!

[![Donate to the framework](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif "Donate to the framework")](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=N5AD7TSH8YA5U)

## Features ##

* Uses the [WordPress Settings API](http://codex.wordpress.org/Settings_API "WordPress Settings API")
* Multiple built in field types
* Multple layout field types
* Fields can be overloaded with a callback function, for custom field types
* Easily extendable by creating Field Classes
* Built in Validation Classes
* Easily extendable by creating Validation Classes
* Custom Validation error handling, including error counts for each section, and custom styling for error fields
* Custom Validation warning handling, including warning counts for each section, and custom styling for warning fields
* Multiple Hook Points for customisation
* Import / Export Functionality - including cross site importing of settings
* Easily add page help through the class
* Fully responsive options panel
* Much more

## Stay In The Loop! ##

[![Follow us on Twitter](http://iod.unh.edu/Images/Twitter_follow_us.png "Follow us on Twitter")](https://www.twitter.com/ReduxFramework)

## FAQs ##

1. Why should we use ```require_once``` instead of ```get_template_part```?
 * First, because ```get_template_part``` is for... you guessed it, themes! Redux is designed to work with both themes *and* plugins.
 * Second, read [this](http://kovshenin.com/2013/get_template_part/).
2. Why shouldn't we edit ```sample-config.php``` in the plugin directory?
 * Because ```sample-config.php``` will be replaced at each update of the plugin. You will lose all your effort

## Are you using Redux? ##

Send me an email at ghost1227@reduxframework.com so I can add you to our user spotlight!

## Changelog ##

### Master ###

= 3.0.5 =
* Fixed how Redux is intitialized so it works in any and all files without hooking into the init function.
* Issue #151: Added thumbnails to media and displayed those instead of full image.
* Issue #144: Slides had error if last slide was deleted.
* Color field was outputting hex in the wrong location.
* Added ACE Editor field, allowing for better inline editing.

= 3.0.4 =
* Fixed an odd saving issue.
* Fixed link issues in the framework
* Issue #135: jQuery UI wasn't being properly queued
* Issue #140: Admin notice glitch. See http://reduxframework.com/2013/10/wordpress-notifications-custom-options-panels/
* Use hooks instead of custom variable for custom admin CSS
* Added "raw" field that allows PHP or a hook to embed anything in the panel.
* Submenus in Admin now change the tabs without reloading the page.
* Small fix for multi-text.
* Added IT_it and RO_ro languages.
* Updated readme file for languages.

= 3.0.3 =
* Fixed Issue #129: Spacing field giving an undefined.
* Fixed Issue #131: Google Fonts stylesheet appending to body and also to the top of the header. Now properly placed both at the end of the head tag as to overload any theme stylesheets.
* Fixed issue #132 (See #134, thanks @andreilupu): Could not have multiple Wordpress Editors (wp_editor) as the same ID was shared. Also fixed various styles to match Wordpress for this field.
* Fixed Issue #133: Issue when custom admin stylesheet was used, a JS error resulted.

= 3.0.2 =
* Improvements to slides, various field fixes and improvements. Also fixed a few user submitted issues.

= 3.0.1 =
* Backing out a bit of submitted code that caused the input field to not properly break.

= 3.0.0 =
* Initial Wordpress.org plugin release.

== Upgrade Notice ==

= 3.0 =
Redux is now hosted on Wordpress.org! Update in order to get proper, stable updates.

* Removed get() and show()
* Fixed huge performance bug
* More bugfixes
* Fixed spacing field
* Converted Redux to run as an auto-updating plugin. Getting ready to post to wordpress.org
* Fixed the auto updater to properly show changes since the last update
* Various fields including link_color, spacing, dimensions
* Compiler hooks to allow developers to generate CSS files only when needed
* Stability and standardizing in HTML output throughout
* PHP/CSS/JS fixes
* Compress JS and use LESS (and compressed CSS) throughout

### Version 3.0.0 Beta (September 12, 2013)

* Massive code overhaul
* Replaced redundant field types with data elements
* Migrated to company repo
* Added several new storage methods
* Numerous bugfixes
* Renamed std argument to default
* Added MP6 support
* Complete CSS rewrite
* Globals are now conditional
* Added nesting support
* Added repeatable field
* Restyled Dev Mode
* Added System Info tab
* Added compiler hooks
* Added style and icon support to info field
* Switched to Elusive Icons
* Huge performance updates

### Version 2.0.1 Final (September 1, 2013) ###

* Added option to override ```icon_type``` per icon
* Minor bug/versioning fixes
* Added Font Awesome intro
* Added ```raw_html``` option
* Added ```text_sortable``` option
* Switched from Aristo to Bootstrap jQuery UI theme

### Version 2.0.0 (January 31, 2013) ###

* Fixed SSL error which occurred occasionally with Google Webfonts 
* Added optional flag for ```wpautop``` on editors
* Added password field type
* Added ```checkbox_hide_all``` option
* Added WP3.5 media chooser
* Added Google webfonts previews
* Updated to WP3.5 color picker
* Minor style tweaks
* Added graphical 'switch' option for checkboxes
* Removed dependency on class extension for fields
* Deprecated icons in favor of iconfonts

### Version 1.0.0 (December 5, 2012) ###

* Based on NHP Theme Options Framework v1.0.6
* Cleaned up codebase
* Changed option group name to allow multiple instances
* Changed checkbox name attribute to id
* Added rows attribute to textareas
* Removed extra linebreak in upload field
* Set default menu position to null to avoid conflicts
* Added sample content for dashboard credit line
* Minor style changes
* Changed name of upload button
* Refactored Google Webfonts function
* Replaced ```stylesheet_override``` with ```admin_stylesheet```
* Made text domain a constant
* Removed PHP closing tags to prevent issues with newlines
* Added option to define custom start tab
