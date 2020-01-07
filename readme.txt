=== Redux Framework ===
Contributors: dovyp, redux
Donate link: http://paypal.me/reduxframework
Tags: admin, admin interface, options, theme options, plugin options, options framework, settings, web fonts, google fonts
Requires at least: 3.5.1
Tested up to: 5.3.3
Stable tag: 3.6.17
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Redux is a simple, truly extensible and fully responsive options framework for WordPress themes and plugins. Ships with an integrated demo.

== Description ==

Redux is a simple, truly extensible and fully responsive options framework for WordPress themes and plugins. Built on the WordPress Settings API, Redux supports a multitude of field types as well as: custom error handling, custom fields & validation types, and import/export functionality.

But what does Redux actually DO?  We don't believe that theme and plugin developers should have to reinvent the wheel every time they start work on a project. Redux is designed to simplify the development cycle by providing a streamlined, extensible framework for developers to build on. Through a simple, well-documented config file, third-party developers can build out an options panel limited only by their own imagination in a fraction of the time it would take to build from the ground up!

= Online Demo =
Don't take our word for it, check out our online demo and try Redux without installing a thing!
[**http://demo.redux.io/**](http://demo.redux.io/)

= Use the Redux Builder to Get Started =
Want to use Redux, but not sure what to do? Use our [builder](http://build.reduxframework.com/)! It will allow you to make a custom theme based on [_s](http://underscores.me), [TGM](http://tgmpluginactivation.com), and [Redux](http://reduxframework.com), and any Redux arguments you want to set.

Don't want to make your own theme? Then output a custom admin folder that you can place in a theme or plugin. Oh and did we mention it's free? Try it today at:
[**http://build.reduxframework.com/**](http://build.reduxframework.com/)


= Docs & Support =
We have extremely extensive docs. Please visit [http://docs.reduxframework.com/](http://docs.reduxframework.com/) If that doesn’t solve your concern, you should search [the issue tracker on Github](https://github.com/reduxframework/redux-framework/issues). If you can't locate any topics that pertain to your particular issue, [post a new issue](https://github.com/reduxframework/redux-framework/issues/new) for it. Before you submit an issue, please read [our contributing requirements](https://github.com/redux-framework/redux-framework/blob/master/CONTRIBUTING.md). We build off of the dev version and push to WordPress.org when all is confirmed stable and ready for release.


= Redux Framework Needs Your Support =
It is hard to continue development and support for this free plugin without contributions from users like you. If you enjoy using Redux Framework, and find it useful, please consider [making a donation](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=MMFMHWUPKHKPW). Your donation will help encourage and support the plugin's continued development and better user support.

= Fields Types =

* Background
* Border
* Button Set
* Checkbox / Multi-Check
* Color (WordPress Native)
* Color Gradient
* Color RGBA
* Date
* Dimensions (Height/Width)
* Divide (Divider)
* Editor (WordPress Native)
* Gallery (WordPress Native)
* Image Select (Patterns/Presets)
* Import/Export
* Info (Header/Notice)
* Link Color
* Media (WordPress Native)
* Multi-Text
* Password
* Radio (w/ WordPress Data)
* Raw (HTML/PHP/MarkDown)
* Section (Indent and Group Fields)
* Select (Select/Multi-Select w/ Select2 & WordPress Data)
* Select Image
* Slider (Drag a Handle)
* Slides (Multiple Images, Titles, and Descriptions)
* Sortable (Drag/Drop Checkbox/Input Fields)
* Sorter (Drag/Drop Manager - Works great for content blocks)
* Spacing (Margin/Padding/Absolute)
* Spinner
* Switch
* Text
* Textarea
* Typography 
 * The most advanced typography module complete with preview, Google fonts, and auto-css output!

= Additional Features =

* Field Validation
* MANY translations. (See below)
* Full value escaping.
* Required - Link visibility from parent fields. Set this to affect the visibility of the field on the parent's value. Fully nested with multiple required parents possible.
* Output CSS Automatically - Redux generates CSS and the appropriate Google Fonts stylesheets for you on select fields. You need only specify the CSS selector to apply the CSS to (limited to certain fields).
* Compiler integration! A custom hook runs when any fields with the argument `compile => true` are changed.
* Oh, and did we mention a fully integrated Google Webfonts setup that will make you so happy you'll want to cry?

  
= Translators & Non-English Speakers =
We need your help to translate Redux into your language! Redux is part of the WP-Translations.org team. To help us translate Redux create a few account here: <a href="https://www.transifex.com/organization/wp-translations">https://www.transifex.com/organization/wp-translations</a>. Once you're in, you can head over to the <a href="https://www.transifex.com/projects/p/redux-framework/">Redux sub-project</a> and translate away. Thank you for your assistance.

= Get Involved =
Redux is an ever-changing, living system. Want to stay up to date or contribute? Subscribe to one of our mailing lists or join us on [Facebook](https://facebook.com/reduxframework) or [Twitter](https://twitter.com/reduxframework) or [Github](https://github.com/ReduxFramework/ReduxFramework)!

NOTE: Redux is not intended to be used on its own. It requires a config file provided by a third-party theme or plugin developer to actual do anything cool!

== Installation ==

= For Complete Documentation and Examples =
Visit: [http://docs.reduxframework.com/](http://docs.reduxframework.com/)

== Frequently Asked Questions ==

= Why doesn't this plugin do anything? =

Redux is an options framework... in other words, it's not designed to do anything on its own! You can however activate a demo mode to see how it works. 

= How can I learn more about Redux? =

Visit our website at [http://reduxframework.com/](http://reduxframework.com/)

= You don't have much content in this FAQ section =
That's because the real FAQ section is on our site! Please visit [http://docs.reduxframework.com/faq/](http://docs.reduxframework.com/faq/)

== Screenshots ==

1. This is the demo mode of Redux Framework. Activate it and you will find a fully-functional admin panel that you can play with. On the Plugins page, beneath the description and an activated Redux Framework, you will find a Demo Mode link. Click that link to activate or deactivate the sample-config file Redux ships with.  Don't take our word for it, check out our online demo and try Redux without installing a thing! [**http://demo.reduxframework.com/wp-admin/**](http://demo.reduxframework.com/wp-admin/)

== Changelog ==
= 3.6.16 =
* Fixed:    WordPress 5.3.1 compatibility. Also added the new @redux account to the plugin.

= 3.6.15 =
* Fixed:    Redux API setSections would hang up when several sections with no ID share the same title.

= 3.6.14 =
* Fixed:    #3583:  Import failing when max_input_vars exceeded.  Function moved to ajax_save to avoid this issue.

= 3.6.13 =
* Skipping.  I'm superstitious! - kp

= 3.6.12.2 =
* Fixed:    #3586:  Database not saving properly in 'network' mode.  Thanks @Tofandel.
* Fixed:    #3584:  Improved fox for #3580.  Thanks @Enchiridion.

= 3.6.12.1 =
* Fixed:    #3580 - 'tax_query' array in args for WordPress data arg throwing a string conversation error.

= 3.6.12 =
* Fixed:    #3577 - Added isset to REMOTE_ADDR check to prevent error.

= 3.6.11 =
* Fixed:    #3561, #3562 - Not all selectors in async typography were properly formed, causing them not to render properly on screen.

= 3.6.10 =
* Updated   Google font update.
* Updated:  #3447 - Updated RTL CSS.  Thanks @Abolfazlrt.
* Fixed:    Duplicate ID warnings.
* Fixed:    http warnings in https environments.
* Fixed:    #3539 - Checkbox label not appearing unless 'desc' was set. Thanks @Enchiridion.
* Fixed:    #3547 - ace_editor not rendering properly within a subsection. Thanks @Tofandel.
* Fixed:    #3534 - Fix invalid CSS in asycn_typography (trailing commas).  Thanks @ksere
* Fixed:    Spacing between Save and Reset buttons.
* Added:    #3285 - dir and url filters for customizer extension.  Thanks @aaronhuisinga.
            add_filter ("redux/extension/customizer/dir", $dir)
            add_filter ("redux/extension/customizer/url", $url)

= 3.6.9 =
* Fixed:    Bypassing a WP bug where the gallery field would show a spinner on first open with no selected images.
* Fixed:    #3512 - Image select in tile mode not highlighting default.

= 3.6.8 =
* Fixed:    Error in AJAX save due to incorrect object reference in redux.js.
* Fixed:    Removed unused set_transient in welcome routine.  It was causing slow queries.
* Updated:  Google Font update.
* Fixed:    #3440: Parent object not being properly set in the Redux filesystem.
* Fixed:    Color picker CSS issues as a result of WP 4.9.
* Fixed:    #3429 - Select2 Sortable needed jQuery Sortable dependency.
* Fixed:    Admin noticies when multiple instances of Redux running not displaying per panel.

= 3.6.7.7 =
* Fixed:    Filesystem path correction.
* Fixed:    #3414: Incorrect classname causing an error on load, via the filesystem API.
* Fixed:    #3413 - Restored old code allowing non array value for mode. This is for backward compatibility 
            only and is unsupported.
* Fixed:    #3410, #3409 - Dimensions field output causing index errors when mode not set in option array.
* Fixed:    #3406 - javascript hasClass improperly used, affected customizer.
* Fixed:    Array declarations PHP 7.1 now requires.
* Updated:  Updated newsletter subscribe submit to support our newer newsletter server.
* Fixed:    #3379 - select_image field not properly displaying default.  'default' arg must now be the 
            full path to default image.
* Updated:  Google Fonts.

= 3.6.6 =
* Misspelled class name is system info compiler causing System Status to fail.
* #3359 - Responsive issue on option panel.  Too much blank space on panel in smartphone mode.
* #2914, #3356 - Default image_select preset image not selected.  This was originally by design.  Now it's a thing.
* Update:   Parsedown.php for PHP 7.x
* Fixed:    System status improperly reporting writable status on upload folder.
* Fixed:    #3124 - User submitted 'current_user_can' failing on PHP version <= 3.5.13.  Thanks for the assist, @sourabgupta88
* Modified: #3321 - Font subset in typography not rendering on IE and Edge (Seriously?  People still use those?)
* Modified: PHP7 compatibility.

= 3.6.5 =
* Modified: #3321 - Font subset in typography not rendering on IE and Edge (Seriously?  People still use those?)
* Fixed:    #3293 - Required not liking/hiding fields with switch default of 'false'.
* Fixed:    Remove leftover var_dump from the core.
* Fixed:    Tracking and newsletter popups were failing due to broken javascript.
* Fixed:    #3291: Required with parent as an array not checking properly.  Someone took out my object check!! - kp
* Modified: Date field calander now renders on .redux-container div.
* Updated:  googlegonts.php file.
* Modified: = and != required statements now use typesafe comparisons.
* Fixed:    link_color field now properly displays all color fields.
* Added:    link_color field now included 'focus' color block.
* Modified: Re-styled link_color field to be more inline with other fields of it's type, that is, it looks better.
* Added:    'title' attribute to image_select field, since 'alt' was no longer working to display hover tooltip.
* Fixed:    Default data not saving correctly in sorter field.


= 3.6.4 =
* Modified: Changed gitignore file to exclude sublime text files.
* Fixed:    #2966 - Translation bug. Identified and fixed by @iiandrade. Thanks!
* Modified: Generated all CSS map files to get rid of Chrome warnings.
* Added:    Required for the Advanced Customizer thanks to @britner!
* Modified: Various customizer fixes and changes to match new styles.
* Modified: Customizer only code in Redux.js, moved to the customizer.js file.
* Modified: Isolated Redux CSS to be nested and not affect other products or WP UI.
* Added:    #3222 - HUGE update by @enchiridion to allow for advanced and complicated permissions. WTG!
* Added:    New hooks for how Extension APIs are called. Much cleaner.
* Fixed:    #3214 - Typography color field not triggering compiler hook.
* Fixed:    #3201 - Index error when using compiler argument with spinner field.
* Updated:  #3189 - PHP7 compatibility for preg_replace validation.
* Fixed:    #3186 - Multi text field not removing single field when clicking "Remove".
* Fixed:    #3180, #2641 - Button set multi mode saving incorrectly.  Please check your code for a possible backward compatibility issue when using this mode.  The foreach() function with an empty() check must now be used, versus individual array keys as only selected options are saved to the database.

= 3.6.3 =
* Modified: Change customizer hover styles to match WP 4.7.
* Modified: #3169 - print_r of wpdb queries in dev_mode removed.
* Fixed: #3159 - Support for SVG in gallery media selection.
* Fixed: #3158 - PHP warning for _validate_values function when extensions installed on PHP7.

= 3.6.2 =
* Fixed:   #3105 - link_color output failing due to PHP error.
* Fixed:   #3103 - WP 4.6 forces new default date format, breaking date validation.
* Fixed:   Typography subsets error due to typo.
* Fixed:   Extra dead files on WP.org repo. Bah SVN.

= 3.6.1 =
* Removed   Empty PHP file from editor field.
* Modified: Replaced class primary function name in browser.php to __construct for PHP7 compatibility.
* Fixed:    #3051 - Color_RBGA field RGBA value outputting zeros when color is left blank.
* Fixed:    #3048 - Subsection tabs not including specified section class name.
* Fixed:    Incorrect string comparison result in admin link check.  Thanks @ksere.
* Fixed:    Check value exists before validating when used with Metabox extension.  Thanks @Enchiridion
* Fixed:    Empty values not passing to validation_callback.
* Fixed:    Javascript error in customizer javascript, preventing save of changed options.
* Fixed:    #3019 - Section descriptions incorrect when opt_name contains digits.
* Reverted: Changes to typography.  The on input variable solution was not working.
* Fixed:    Support URL has generator was failing with an error.
* Changed:  Typography field is now only ONE input variable. Should reduce our
            max_input_vars errors dramatically.
* Fixed:    Some XSS vulnerabilities only available in the backend when authenticated as a user.
* Fixed:    Deleted old deleted files stuck in our WP.org SVN repo.


= 3.6.0.2 =
* Fixed     Outdated customizer.min.js on wp.org causing customizer failure.

= 3.6.0.1 =
* Fixed     Outdated redux.min.js on wp.org causing option panel failure.
* Fixed:    #2936 - Border field outputting px with blank value.
* Fixed:    Resolved Theme-Check php shortcode false notice.
* Modified: No more major redirect for the Redux page, only on first install with the plugin.
* Fixed:    IE11 bug in the customizer. Thanks @anikitas!
* Fixed:    Customizer path issues
* Added:    New default arguments filter by opt_name and type.  :)
* Fixed:    #2903 - False positive flag in border field JS.  Avast doesn't like empty document ready statements.
* Fixed:    #2880 - More issues with the extensions_url routine.
* Fixed:    #2876 - Fixing more unvetted user contributions.
* Modified: #2855 - Extensions now have a helper class to help composer-based installs. Thanks @2ndkauboy!
* Fixed:    #2857 - Required 'contains' not properly evaluating with checkboxes.
* Fixed:    #2831 - Localization was complete broken.
* Fixed:    #2832 - CSS conflicts with Rev Slider (Hey, Rev Slider guys, you don't have to load your CSS on every admin page.  Really?)
* Fixed:    Leftover debug echo line in basic customizer extension.
* Added:    EXPERIMENTAL:  New parsing code in an effort to break the 1000 max_input_var issue that crops up from time to time. Thanks, @harunbasic
* Added:    EXPERIMENTAL:  "Bugfix" for extension_url in an effort to make it correct.  Thanks, @ottok
