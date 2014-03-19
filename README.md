# [docs.reduxframework.com](http://docs.reduxframework.com)
Need a little help with Redux?  Come check out our brand new documentation site, chock full of tutorials and examples!
 

--------



## Redux Options Framework [![Build Status](https://travis-ci.org/ReduxFramework/redux-framework.png?branch=master)](https://travis-ci.org/ReduxFramework/redux-framework) [![Stories in Ready](https://badge.waffle.io/ReduxFramework/redux-framework.png?label=ready)](https://waffle.io/ReduxFramework/redux-framework) [![Built with Grunt](https://cdn.gruntjs.com/builtwith.png)](http://gruntjs.com/)

WordPress options framework which uses the [WordPress Settings API](http://codex.wordpress.org/Settings_API "WordPress Settings API"), Custom Error/Validation Handling, Custom Field/Validation Types, and import/export functionality.

## SMOF (Simple Modified Option Users) Converter! ##

Hot off the press, our Redux Converter plugin. It takes your SMOF instance, and allows you to try out Redux without any fear. It also spits out valid PHP source for you if you want to migrate complete with data migration! Give it a try today. It will be in the WordPress.org repo shortly.  ;)
https://github.com/ReduxFramework/redux-converter

## Help Us Translate Redux ##

Please head over to the wiki to learn how you can help us translate Redux quickly. Any and all are welcome. We appreciate your help!
https://github.com/ReduxFramework/ReduxFramework/wiki/translate

## Getting Started with Redux ##

ReduxFramework has been built from the groud up to be the most flexible framework around. You can run it as an auto-updating plugin, or embed it inside your plugin or theme. It allows for multiple copies of itself within the same WordPress instance. For a guide on getting started please refer to [https://github.com/ReduxFramework/redux-framework/wiki/Getting-Started](https://github.com/ReduxFramework/redux-framework/wiki/Getting-Started).

You can also [download our sample theme available here](https://github.com/ReduxFramework/ReduxSampleTheme) to start developing right away.

## Please Post Reviews and Spread the Word ##

ReduxFramework has just released to the WordPress Plugins directory. Please spread the word, tweet, and (most importantly) post reviews on http://wordpress.org/plugins/redux-framework/. 


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

= 3.1.9.2 =
* Fixed:      Inconsistencies in import/export across different versions of PHP.

= 3.1.9.1 =
* Fixed:      Redux checks for child or parent theme exclusively before loading.

= 3.1.9 =
* Updated:    RGBA Field stability.  Thank you, SilverKenn.

= 3.1.8.23 =
* Modified:   Separated Import/Export from the core.  It can now be used as a field.

= 3.1.8.22 =
* Fixed:      Typography custom preview text/size not outputting.
* Fixed:      No font selected in typography would default to 'inherit'.
* Fixed:      Hint feature kicking back a notice if no title was specified.

= 3.1.8.21 =
* Fixed:      Sortable field, when used a checkboxes, were all checked by default, even when set not to be.
* Fixed:      button_set field not setting properly in multi mode.

= 3.1.8.20 =
* Fixed:      Javascript console object not printing options object.
* Fixed:      Load errors from child themes no longer occur.

= 3.1.8.19 =
* Modified:   Typography word and letter spacing now accept negative values.
* Modified:   Typography preview shows spaces between upper and lower case groupings.
* Fixed:      Compiler output for slider field.

= 3.1.8.18 =
* Fixed:      update_check produced a fatal error on a local install with no internet connection.
* Modified:   Google font CSS moved to header so pages will pass HTML5 validation.

= 3.1.8.17 =
* Fixed:      Compiler hook failing on slider.

= 3.1.8.16 =
* Fixed:      Error on update_check when the response code was something other than 200.
* Modified:   Removed Google font CSS line from header (because it's in the footer via wp_enqueue_style.

= 3.1.8.15 =
* Added:      Admin notice for new builds of Redux on Github as they become available.  This feature is available on in dev_mode, and may be turned off by setting the `update_notice` argument to false.  See the Arguments page of the wiki for more details.
* Added:      text-transform option for the typography field.
* Fixed:      image_select images not resizing properly in FF and IE.
* Fixed:      Layout for the typography field, so everything isn't smushed together.  The new layout is as follows:

              [family-font] [backup-font]
              [style] [script] [align] [transform]
              [size] [height] [word space] [letter space]
              [color]

= 3.1.8.14 =
* Added:      Newsletter sign-up popup at first load of the Redux options panel.

= 3.1.8.12 =
* Added:      Added PHP 5.2 support for import/export.

= 3.1.8.11 =
* Added:      Action hooks for options reset and options reset section.
* Added:      Theme responsive for date picker.

= 3.1.8.10 =
* Added:      New slider.  Better looking UI, double handles and support for floating point values.  See the wiki for more info.

= 3.1.8.9 =
* Fixed:      link_color field showing notice on default, if user enters no defaults.
* Fixed:      Fixed tab notice in framework.php if no tab parameter is set in URL.

= 3.1.8.8 =
* Added:      Typography improvements.

= 3.1.8.7 =
* Added:      Hints!  More info:  https://github.com/ReduxFramework/ReduxFramework/wiki/Using-Hints-in-Fields

= 3.1.8.6 =
* Added:      Complete Wordpress admin color styles. Blessed LESS/SCSS mixins.  ;)

= 3.1.8.5 =
* Added:      Font family not required for the typography module any longer.

= 3.1.8.4 =
* Added:      Support for using the divide field in folding.
* Added:      Error trapping in typography.js for those still attempting to use typography with no font-family.

= 3.1.8.3 =
* Added:      Full asynchronous font loading.
* 
= 3.1.8.2 =
* Added:      email_not_empty validation field.
* Reverted:   email validation field only checks for valid email.  not_empty check moved to new validation field.

= 3.1.8.1 =
* Fixed:      Hide demo hook wasn't hiding demo links.

= 3.1.8 =
* Fixed:    Improper enqueue in tracking class.
* Fixed:    Few classes missed for various fields.
* Fixed:    Spacing field kicking back notices and warnings when 'output' wasn't set.
* Modified: Added file_exists check to all include lines in framework.php
* Fixed:    Background field now works with dynamic preview as it should.
* Fixed:    Extension fields now enqueueing properly.
* Added:    Text-align to typography field.
* Fixed:    Servers returning forwards slashes in TEMPLATEPATH, while Redux is installed embedded would not show options menu.
* Fixed:    On and Off for switch field not displaying language translation.
* Fixed:    email validation allowing a blank field.
* Fixed:    Now allow for empty values as valid keys.
* Added:    Dismiss option to admin notices (internal function)

= 3.1.6 =
* Fixed:    CSS spacing issue
* Fixed:    Customizer now works and doesn't break other customizer fields outside of Redux.
* Fixed:    Several minor bug fixes
* Added:    Metabox support via extension http://reduxframework.com/extensions/
* Added:    Admin-bar menu
* Fixed:    Section field now folds.
* Fixed:    wp_content_dir path now handles double forward slashes.
* Fixed:    Typography field missing italics in Google fonts.
* Fixed:    Default color in border field not saving properly.
* Fixed:    hex2rgba in class.redux_helpers.php changed to static.
* Fixed:    'sortable' field type not saving options as default.
* Fixed:    Specified default color not set when clicking the color box default button.
* Fixed:    Sorter field options are now saved as default in database.
* Fixed:    Issues with checkboxes displaying default values instead of labels.
* Fixed:    Outstanding render issues with spacing field.
* Fixed:    Plugins using Redux from load failure.
* Fixed:    'not_empty' field validation.
* Fixed:    Media field.
* Added:    'read-only' option for media text field.
* Added:    'mode' option to image_select, so CSS output element may be specified.
* Added:    Admin Bar menu for option panel.
* Modified: Removed raw_align field and added align option to raw field.  See wiki for more info.
* Modified: media field 'read-only' to 'readonly' to vonform to HTML standards.
* Removed:  EDD extension. It never belonged in Core and will be re-released as a downloadable extension shortly
* Removed:  Group field, temporarily.
* Removed:  wp_get_current_user check.  See https://github.com/ReduxFramework/ReduxFramework/wiki/How-to-fix-%22Fatal-error%3A-Call-to-undefined-function-wp_get_current_user%28%29-%22
 
= 3.1.5 =
* Typography font arrays may not contain comma spaces.
* Merge in pull request - 542, code cleanup and better readability
* Change how HTML is output to support metaboxes
* CSS only on pages that matter, better checks.
* font-backup in typography now appends to font-family in output and compiler.
* More fixes for Google font css outputting.
* Addded output and compiler to field_image_select.  Images will be output as 'background-image'.
* Fixed output in field_background.
* Prevent standard fonts from outputting to Google fonts CSS call.
* class_exists in field_section checking for incorrect classname.
* sample_config fix.
* Compiler not outputting CSS without output set to comthing other than false.
* Google fonts not rendering on frontend.
* Rewrote sample_config as a class

= 3.1.4 =
* Fixed error in redux-framework.php.
* Added select_image field.

= 3.1.3 =
* Fixed a few undefined variables
* Removed old code from the repo.
* Fix for validation.
* Remove the compiler hook by default.
* Fix to sortable field.
* Added an extra check for link color. Removes user error.
* Localization updates.
* Error in slides.
* Fixed the info box bug with spacing and padding.
* Fixed the first item in each section having WAY too much padding.  ;)
* Fixed section reset issue where values weren't being saved to the db properly.

= 3.1.2 =
* Feature - Sortable select boxes!
* Feature - Reset a section only or the whole panel!
* New Field - RGBA Color Field!
* Improvement - Use of REM throughout.
* Fixed Typography - Fix output option and various small bugs.
* Fixed Border - Fix output option and various small bugs.
* Fixed Dimensions - Fix output option and various small bugs.
* Fixed Image_select - Various small bugs.
* Fixed Slides - Various small bugs.
* Fixed Sortable - Using native jQuery UI library same as within WordPress.
* Fixed Slider and Spinner Input Field - Values now move to the closest valid value in regards to the step, automatically.
* Fixed Ace Editor
* FEATURE - All CSS/JS files are compiled into a single file now! Speed improvements for the backend. 
* Fix in how WordPress data is received, improved some output.
* Fix for various fields not triggering fold/compiler/save.
* Fixed elusive icons to use the new version and classes.
* Fixed media thumb to only be the thumbnail version.
* Fixed admin https error with WordPress core not renaming URL.
* Placeholders throughout the framework are now properly there.
* Feature - Setting to not save defaults to database on load.
* Fixed - Computability issue with GT3 builder.
* Fixed localization issue with default values.
* Language - Added Russian
* Feature - Media now can have any content type passed in to limit content types.
* Allow negative values in typography and other fields.
* WordPress 3.8 computability.
* CSS validation issue.
* Feature - User contributed text direction feature.
* EDD Extension now fully function for plugins or themes.
* Removed get_theme_data() fallbacks, we're well pass WordPress 3.4 now.  ;)
* A ton of other small updates and improvements.

= 3.1.0 =
* Fix Issue 224 - Image Select width was breaking the panel.
* Fix Issue 181 - Broken panel in firefox
* Fix Issue 225 - 0px typography bug. Thanks @partnuz.
* Fix Issue 228 - Resolved a duplicated enqueue on color_link field. Thanks @vertigo7x.
* Fix Issue 231 - Field spacing bug fixes.
* Fix Issue 232 & 233 - Dimensions: bug fix with units and multiple units. Thanks @kpodemski
* Fix Issue 234 - Pass options as a ref so validating actions can modify/sanitize them. Thanks @ZeroBeeOne
* Fix Issue 222 - Tab cookie function wasn't working.
* Feature - Pass params to Select2. Thanks @andreilupu
* Fix Issue 238 - Fix for conditional output. Thanks @partnuz.
* Fix Issue 211 - Google Web font wasn't loading at first init of theme.
* Fix Issue 210 - Elusive Icons update. Changed classes to force use of full elusive name.
* Fix Issue 247 - Media thumbnails were not showing. Also fixed media to keep the largest file, but display the small version in the panel as a thumb. Thanks @kwayyinfotech.
* Fix Issue 144 - JS error when no item found in slider.
* Fix Issue 246 - Typography output errors.
* Feature & Issue 259 - Multi-Text now support validation!
* Fix Issue 248/261 - Links color issue. Also fixed color validation.
* Feature & Issue 262 - Now registered sidebars can be used as a data type.
* Fix Issue 194/276 - Custom taxonomy terms now passing properly. Thanks @kprovance.
* Feature & Issue 273 - Argument save_defaults: Disable the auto-save of the default options to the database if not set.
* Feature - Docs now being moved to the wiki for community participation.
* Issue 283 - Date placeholder. Thanks @kprovance.
* Issue 285 - HTTPS errors on admin. Known WordPress bug. Resolved.
* Fix Issue 288 - Float values now possible for border, dimensions, and spacing.
* Feature - Media field can now accept non-image files with a argument being set.
* Fix Issue 252 - Post Type data wasn't working properly. Thanks @Abu-Taymiyyah.
* Fix Issue 213 - Radio and Button Set wasn't folding.

= 3.0.9 =
* Feature - Added possibility to set default icon class for all sections and tabs.
* Feature - Make is to the WP dir can be moved elsewhere and Redux still function.
* Added Spanish Language. Thanks @vertigo7x.
* Fix Issue 5 - Small RGBA validation fix.
* Fix Issue 176 - Fold by Image Select. Thanks @andreilupu.
* Fix Issue 194 - Custom taxonomy terms in select field.
* Fix Issue 195 - Border defaults not working.
* Fix Issue 197 - Hidden elements were showing up on a small screen. Thanks @ThinkUpThemes.
* Fix issue 200 - Compiler not working with media field.
* Fix Issue 201 - Spacing field not using default values.
* Fix Issue 202 - Dimensions field not using units.
* Fix Issue 208 - Checkbox + Required issue.
* Fix Issue 211 - Google Font default not working on page load.
* Fix Issue 214 - Validation notice not working for fields.
* Fix Issue 181/224 - Firefox 24 image resize errors.
* Fix Issue 223 - Slides were losing the url input field for the image link.
* Fix - Various issues in the password field.
* Fixed various spelling issues and typos in sample-config file.
* Initialize vars before extract() - to shut down undefined vars wargnings.
* Various other fixes.

= 3.0.8 =
* Version push to ensure all bugs fixes were deployed to users. Various.

= 3.0.7 =
* Feature - Completely redone spacing field. Choose to apply to sides or all at once with CSS output!
* Feature - Completely redone border field. Choose to apply to sides or all at once with CSS output!
* Feature - Added opt-in anonymous tracking, allowing us to further analyze usage.
* Feature - Enable weekly updates of the Google Webfonts cache is desired. Also remove the Google Webfont files from shipping with Redux. Will re-download at first panel run to ensure users always have the most recent copy.
* Language translation of german updated alone with ReduxFramework pot file.
* Fix Issue 146 - Spacing field not storing data.
* Fix - Firefox field description rendering bug.
* Fix - Small issue where themes without tags were getting errors from the sample data.

= 3.0.6 =
* Hide customizer fields by default while still under development.
* Fix Issue 123 - Language translations to actually function properly embedded as well as in the plugin.
* Fix Issue 151 - Media field uses thumbnail not full image for preview. Also now storing the thumbnail URL. Uses the smallest available size as the thumb regardless of the name.
* Fix Issue 147 - Option to pass params to select2. Contributed by @andreilupu. Thanks!
* Added trim function to ace editor value to prevent whitespace before and after value keep being added
* htmlspecialchars() value in pre editor for ace. to prevent html tags being hidden in editor and rendered in dom
* Feature: Added optional 'add_text' argument for multi_text field so users can define button text.
* Added consistent remove button on multi text, and used sanitize function for section id
* Feature: Added roles as data for field data
* Feature: Adding data layout options for multi checkbox and radio, we now have quarter, third, half, and full column layouts for these fields.
* Feature: Eliminate REDUX_DIR and REDUX_URL constants and instead created static ReduxFramework::$\_url and ReduxFramework::$\_dir for cleaner code.
* Feature: Code at bottom of sample-config.php to hide plugin activation text about a demo plugin as well as code to demo how to hide the plugin demo_mode link.
* Started work on class definitions of each field and class. Preparing for the panel builder we are planning to make.

= 3.0.5 =
* Fixed how Redux is initialised so it works in any and all files without hooking into the init function.
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
* Fixed issue #132 (See #134, thanks @andreilupu): Could not have multiple WordPress Editors (wp_editor) as the same ID was shared. Also fixed various styles to match WordPress for this field.
* Fixed Issue #133: Issue when custom admin stylesheet was used, a JS error resulted.

= 3.0.2 =
* Improvements to slides, various field fixes and improvements. Also fixed a few user submitted issues.

= 3.0.1 =
* Backing out a bit of submitted code that caused the input field to not properly break.

= 3.0.0 =
* Initial WordPress.org plugin release.

== Upgrade Notice ==

= 3.0 =
Redux is now hosted on WordPress.org! Update in order to get proper, stable updates.

* Removed get() and show()
* Fixed huge performance bug
* More bugfixes
* Fixed spacing field
* Converted Redux to run as an auto-updating plugin. Getting ready to post to WordPress.org
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


[![githalytics.com alpha](https://cruel-carlota.pagodabox.com/dbb3b94f2607cb4a119a7863c230a98e "githalytics.com")](http://githalytics.com/ReduxFramework/ReduxFramework)
