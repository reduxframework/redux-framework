=== Redux Framework ===
Contributors: kprovance, dovyp, redux
Tags: admin, options, theme options, plugin options, options framework
Requires at least: 5.0
Requires PHP: 7.4
Tested up to: 6.9
Stable tag: 4.5.10
License: GPL-3.0+
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

Redux is a simple, truly extensible, and fully responsive options framework for WordPress themes and plugins. It ships with an integrated demo.

== Description ==
Redux was built by developers for developers. We save you months if not years in your development time. Everything we do is to help innovation in the industry.

<h4>♥️ What the Plugin does?</h4>
Redux is a simple, genuinely extensible, and fully responsive options framework for WordPress themes and plugins. Built on the WordPress Settings API; Redux supports many field types, custom error handling, custom fields & validation types, and import/export functionality.

But what does Redux actually DO? We don't believe that theme and plugin developers should have to reinvent the wheel every time they start work on a project. Redux simplifies the development cycle by providing a streamlined, extensible framework for developers to build on. Through a simple, well-documented config file, third-party developers can build out an options panel limited only by their imagination in a fraction of the time it would take to build from the ground up!

<h4>🚀 What fields does Redux offer?</h4>
<ul>
<li>Accordion</li>
<li>ACE Editor</li>
<li>Background</li>
<li>Border</li>
<li>Box Shadow</li>
<li>Button Set</li>
<li>Checkbox / Multi-Check</li>
<li>Color (WordPress Native)</li>
<li>Color Gradient</li>
<li>Color Palette</li>
<li>Color RGBA</li>
<li>Color Scheme</li>
<li>Content</li>
<li>Custom Fonts</li>
<li>Customizer</li>
<li>Date</li>
<li>Date/Time</li>
<li>Dimensions (Height/Width)</li>
<li>Divide (Divider)</li>
<li>Editor (WordPress Native)</li>
<li>Gallery (WordPress Native)</li>
<li>Google Maps</li>
<li>Icon Select</li>
<li>Image Select (Patterns/Presets)</li>
<li>Import/Export</li>
<li>Info (Header/Notice)</li>
<li>JS Button</li>
<li>Link Color</li>
<li>Media (WordPress Native)</li>
<li>Metaboxes</li>
<li>Multi Media</li>
<li>Multi-Text</li>
<li>Palette</li>
<li>Password</li>
<li>Radio (w/ WordPress Data)</li>
<li>Raw (HTML/PHP/MarkDown)</li>
<li>Repeater</li>
<li>Section (Indent and Group Fields)</li>
<li>Select (Select/Multi-Select w/ Select2 & WordPress Data)</li>
<li>Select Image</li>
<li>Slider (Drag a Handle)</li>
<li>Slides (Multiple Images, Titles, and Descriptions)</li>
<li>Social Profiles</li>
<li>Sortable (Drag/Drop Checkbox/Input Fields)</li>
<li>Sorter (Drag/Drop Manager - Works great for content blocks)</li>
<li>Spacing (Margin/Padding/Absolute)</li>
<li>Spinner</li>
<li>Switch</li>
<li>Tabbed</li>
<li>Taxonomy Metaboxes</li>
<li>Text</li>
<li>Textarea</li>
<li>Typography</li>
<li>User Profile Metaboxes</li>
 * The most advanced typography module complete with preview, Google fonts, and auto-css output!
<li>User Profile Metaboxes</li>
<li>Widget Areas (Classic Widgets only)</li>
</ul>

<h4>🎉Additional Features</h4>
<ul>
<li>Full value escaping</li>
<li>Required - Link visibility from parent fields. Set this to affect the visibility of the field on the parent's value. Fully nested with multiple required parents possible.</li>
<li>Output CSS Automatically - Redux generates CSS and the appropriate Google Fonts stylesheets for you on select fields. You need to only specify the CSS selector to apply the CSS to (limited to certain fields).</li>
<li>Compiler integration! A custom hook runs when any fields with the argument `compile => true` are changed.</li>
<li>Field validation and sanitization</li>
<li>Field and section disabling</li>
<li>Oh, and did we mention a fully integrated Google Fonts setup that will make you so happy you'll want to cry?</li>
</ul>

<h4>👍 BE A CONTRIBUTOR</h4>
If you want to help with translations, <a href="https://translate.wordpress.org/projects/wp-plugins/redux-framework">go to the Translation Portal at translate.wordpress.org</a>.

You can also contribute code via our <a href="https://github.com/reduxframework/redux-framework/">GitHub Repository</a>. Be sure to use our develop branch to submit pull requests.

<h4>📝 Documentation and Support</h4>
<ul>
<li>We have extremely extensive docs. Please visit [https://devs.redux.io/](https://devs.redux.io). If that doesn't solve your issue, search [the issue tracker on GitHub](https://github.com/reduxframework/redux-framework/issues). If you can’t locate any topics that pertain to your particular problem, [post a new issue](https://github.com/reduxframework/redux-framework/issues/new) for it. Before you submit an issue, please read [our contributing requirements](https://github.com/redux-framework/redux-framework/blob/master/CONTRIBUTING.md). We build on the dev version and push it to WordPress.org when we confirm Redux is stable and ready for release.</li>
<li>If you have additional questions, reach out to us at support@redux.io</li>
</ul>

<h4>⚡ Like the Redux Plugin?</h4>
<ul>
<li>Follow us on <a href="https://www.facebook.com/reduxframework" rel="nofollow ugc">Facebook 💬</a></li>
<li><strong>Rate us 5 ⭐ stars</strong> on <a href="https://wordpress.org/support/plugin/redux-framework/reviews/?filter=5/#new-post">WordPress.org</a></li>
<li>Follow us on Twitter 🐦: <a href="https://twitter.com/reduxframework" rel="nofollow ugc">@ReduxFramework</a></li>
</ul>

<h4>🔐 Privacy</h4>
Redux does not interact with end users on your website. If a product is using Redux, the option panel will cease to function without Redux.

For more details on our privacy policy: [https://redux.io/privacy](https://redux.io/privacy)
For more details on our terms and conditions: [https://redux.io/terms](https://redux.io/terms)

NOTE: Redux is not intended to be used on its own. It requires a config file provided by a third-party theme or plugin developer to actually do anything cool!

== Installation ==
1. Upload the entire plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Changelog ==

= 4.5.10
* Fixed: Tighten security in `import_export`, `custom_fonts`, `color_scheme`, and Google Font updating.
* Fixed: #4085 - `box_shadow` slider not updating value in real-time.
* Fixed: Missing translations. Thanks, @DAnn2012.
* Release date: January 07, 2026

= 4.5.9
* Fix: Deprecation warning in `get_wordpress_data()` function.
* Modified: Compliance with new PCP criteria.
* Improved: Tighter security for shortcode extension.
* Updated: parsedown.php for PHP 8.5.
* Release date: November 24, 2025

= 4.5.8
* Fix: Reported XSS vulnerability in the shortcode extension.
* Fix: #4074 - Gallery field won't load on screen refresh.
* Fix: Prevent fatal error for improperly coded CPTs with metaboxes.
* Added: New global arg `custom_fonts` to enable or disable the extension.
* Added: New global arg `widget_area` to enable or disable the extension.
* Added: `social_profiles` now support an argument for FA 6 classnames.
* Improved: Callback validation for data fetching. Thanks @afonsolpjr
* Release date: October 01, 2025

= 4.5.7 =
* Fixed: Bullet-proofed some global args when omitted from the config.
* Fixed: PHP 8.4 deprecation notices.
* Fixed: The Options constructor now filters out blank strings, causing fatal errors. Options must always be in an array.
* Fixed: `gallery` field errors on failed demo imports not installing images into the WP gallery.
* Release date: March 26, 2025

= 4.5.6 =
* Fixed: Setting CHMOD defaults in construct to avoid errors in certain use cases.
* Fixed: Installed empty placeholder for old `search` extension as WP did not remove it from old versions to updates, thus causing errors.
* Fixed: `raw` field in sample-config.php trigger WP filesystem error when `FS_METHOD` set to `FTP_EXT` and creds are not entered.
* Fixed: Sorter not saving in customizer.
* Fixed: `users` metabox not saving on some setups.
* Fixed: Disable search bar on user profile and taxonomy metaboxes.
* Updated: Font Awesome 6.7.2
* Modified: `custom_fonts` now enqueues local font CSS with a version resource of last modified file time and not current time.
* Modified: Will pass mandatory PCP checks.
* Release date: January 20, 2025

= 4.5.4 =
* Fixed: Filesystem class $creds not accepting bool value.
* Fixed: #4045: Old `search` extension throwing `class not found` error.
* Fixed: `accordion` extension throwing `Type of Redux_Extension_Accordion::$version must be string` error.
* Fixed: `color_scheme` typed property must not be accessed before initialization.
* Release date: December 16, 2024

= 4.5.3 =
* Removed: Deprecation notice for $filesystem. Too many people think it's an error.  We'll have to support old Redux 3 code for the foreseeable future.
* Release date: December 5, 2024

= 4.5.2 =
* Fixed: New global filesystem access broke old methods used on old extensions. Deprecation notice added.
* Release date: December 5, 2024

= 4.5.1 =
* Updated: Font Awesome 6.7.1
* Fixed: Options Search bar rendering multiple time on customizer UI.
* Fixed: Changed typesafe declarations to transient variables from `array` to `mixed` to prevent fatal errors.
* Fixed: `color_scheme` and `social_profiles` giving `cannot assign null to array` errors when fields not in use.
* Fixed: JavaScript errors in regard to TinyMCE when not loaded via `editor` field.
* Fixed: `repeater` "Add" button failing when no `editor` field was loaded.
* Fixed: WP 6.7 broke Redux menus in customizer.
* Fixed: "Reset Section" resetting everything to blank or zero.
* Fixed: Float loses precision in `color_rgba` when `show_default` is set to true. Thanks @andrejarh
* Fixed: `multi_media` field not saving or retaining data in customizer.
* Modified: Customizer HTML output to support WordPress installations prior to version 6.7.
* Modified: Option panel search bar moved to core (previously an extension).
* Modified: Allow `null` assignments to core variable to prevent fatal errors when devs disable Google Fonts.
* Added `null` to multiple typesafe declarations.
* Added: CSS output added to `slider` field.
* Added: Minimum PHP 7.4 warning message to admin screen to prevent fatal errors. Some people are, apparently, still using outdated PHP.
* Release date: December 4, 2024

= 4.5.0 =
* Changed: Minimum PHP version now 7.4.
* Fixed: Datetime wasn't escaping some translations and domain was incorrect.
* Fixed: `required` functionality in `taxonomy` and `users`.
* Fixed: `repeater` not rendering inside `taxonomy` metaboxes.
* Fixed: `repeater` not saving inside `users` metaboxes.
* Fixed: Metaboxes `page_template` feature not showing/hiding properly under Gutenberg due to class name change.
* Fixed: #4023 - `google_maps` instances bleeding over from previous issue.
* Fixed: `google_maps` deprecation notice regarding map markers.
* Fixed: `repeater` in `taxonomy` and `user` metaboxes.
* Fixed: Unnecessary loading of default data on load (unless `metaboxes` are in use).
* Fixed: `editor` and `checkbox` fields not saving in `tabbed` field.
* Fixed: `custom_fonts` not saving uploaded font on conversion failure.
* Fixed: #4009 - Google Font update issue resolved.
* Fixed: #4011 - `editor` in `repeater` field not saving.
* Fixed: `editor` in added `repeater` fields not properly initializing.
* Fixed: #4008 - Font conversion failure fallback.
* Fixed: Replaced `validate_values` deprecation in `taxonomy` metabox.
* Updated: Deprecated JavaScript in all the Metabox extensions.
* Updated: JavaScript for jQuery 4.0 release.
* Release date: October 28, 2024

= 4.4.18 =
* Fixed: #4006: XSS fix in 'color_scheme' import.
* Updated: Font Awesome 6.6.0
* Release date: July 19, 2024

= 4.4.17 =
* Fixed: `social_profiles` in customizer.
* Fixed: Section divide returning `null`, which caused a PHP warning.
* Fixed: Undefined index in `tabbed` when resetting settings.
* Release date: May 23, 2024

= 4.4.16 =
* Modified: Temporarily disable `social_profiles` and `color_scheme` from customizer. They don't work.
* Removed: Finished removing Redux Pro support.
* Removed: Extendify plugin banner at first launch.
* Updated: Font Awesome 6.5.2
* Release date: April 27, 2024

= 4.4.15 =
* Fixed: `spacing`, `dimension`, and `border` fields not saving changed values.
* Fixed: `switch` and `button_set` not saving within `tabbed` interface.
* Release date: March 22, 2024

= 4.4.14 =
* Fixed: "No Field ID is set" message causing jumbled backend layout.
* New: Content Field [https://devs.redux.io/core-fields/content.html](https://devs.redux.io/core-fields/content.html)
* Updated: Bring inputs up to W3C standards.
* Updated: First round of PHP 8.3 compatibility.
* Release date: March 14, 2024

= 4.4.13 =
* Fixed: `color_scheme` crashing WordPress with 'critical error' for users still using PHP 7.1.
* Added: Filter to disable Google Font updates: `"redux/{opt_name}/field/typography/google_font_update"`. Return `false` to disable.
* Added: WordPress 6.5 compatibility.
* Release date: February 13, 2024

= 4.4.12 =
* New: Color Schemes Extension [https://devs.redux.io/core-extensions/color-schemes.html](https://devs.redux.io/core-extensions/color-schemes.html)
* Fixed: PHP Error when `color_scheme` data doesn't exist.
* Fixed: `custom_fonts` not importing original font on conversion failure.
* Fixed: Remove debug info from JS. FA version change.
* Fixed: #3988 - Warning/error count displayed NaN on color field validation.
* Fixed: Erroneous error in `color_scheme` when saved scheme array was blank (string).
* Fixed: Color schemes would not switch via select box after saving a new scheme.
* Fixed: `typography` sunset dropdown not rendering select2 styling.
* Fixed: Efficiency for extension loading improved.
* Removed: Redux Pro support. It's no longer required as all Pro features are now part of Redux.
* Release date: February 12, 2024

= 4.4.11 =
* Fixed: Cosmetic `box_shadow` fix.
* Fixed: Required not hiding linked fields in customizer.
* Fixed: `tabbed` and `repeater` fields not resetting when using Section Reset.
* Fixed: #3983 - Continued damage done by WP Filesystem PR
* Updated: Font Awesome 6.5.1
* Release date: December 18, 2023

= 4.4.10 =
* New: Tabbed Extension [https://devs.redux.io/core-extensions/tabbed.html](https://devs.redux.io/core-extensions/tabbed.html)
* Modified: Typography preview background will shift to black when lighter font colors are selected. Thanks, @herculesdesign
* Modified: Additional rollback changes made to the filesystem class causing false file permission issue messages.
* Fixed: Errant spaces in ACE Editor field.
* Fixed: Array check in color validation to avoid errors. It works ONLY with the color field. Nothing else.
* Improved: Filesystem killswitch logic.
* Release date: December 05, 2023

= 4.4.9 =
* Modified: Rollback changes made to the filesystem class causing false file permission issue messages.
* Release date: October 26, 2023

= 4.4.8 =
* Modified: Additional safeguards against read-only filesystems. Thanks @cezarpopa-cognita.
* Fixed: #3970 - Added `is_string` check to WordPress data callback argument.
* Removed: Unused code for Support Ticket Submission feature that was never finished.
* Fixed: Removed extra spaces from `textarea`.
* Added: WordPress 6.4 compatibility.
* Release date: October 17, 2023

= 4.4.7 =
* Removed: CDN vendor support for `ace_editor`. Devs won't update their code, leaving us no choice. Use the `redux/<opt_name>/fields/ace/script` filter to enqueue a local ACE Editor script if needed.
* Fixed: Redux template PHP not autoloading.
* Release date: September 14, 2023

= 4.4.6 =
* New: Global arg `fontawesome_frontend` to enqueue the internal Font Awesome CSS on the front end.
* New: Taxonomy Metaboxes Extension [https://devs.redux.io/core-extensions/taxonomy.html](https://devs.redux.io/core-extensions/taxonomy.html)
* Fixed: Font Awesome not enqueueing on the frontend for `social_profiles` field.
* Fixed: HTML Output for User Profile Metaboxes.
* Fixed: Admin panel CSS.
* Fixed: Adjusted translation for Google Font update message.
* Fixed: Continuing effort to combat old CDN code because some devs aren't updating their code.
* Fixed: REDUX_PLUGIN_FILE failed with embedded installed.  WE NO LONGER SUPPORT EMBEDDED. IT'S FOR LEGACY INSTALLS ONLY.
* Release date: September 13, 2023

= 4.4.5 =
* Fixed: Redux catches error when Google Fonts JSON cannot be read/written due to server misconfiguration.
* Fixed: Output HTML in the admin panel now complies with W3C standards.
* Fixed: `typography` letter-spacing and word-spacing stuck on zero value.
* Fixed: Field classes were disregarded when using `hidden` or `disable` arguments.
* Fixed: Added class alias for the old version of Redux Vendor Support so Redux doesn't crash.
* Fixed: Added additional shim to fix Vendor Support code embedded by themes that are doing it incorrectly.
* Fixed: Add `wp-util` dependency to `icon_select` since, in some cases, WordPress does not.
* Added: Error trapping for panel template loading for missing or unreadable files to avoid crashing the site.
* Updated: Default Google Fonts.
* Updated: Font Awesome 6.4.2
* Release date: August 07, 2023

= 4.4.4 =
* Fixed: Revert `redux-admin` CSS handle to previous handle.
* Fixed: `color_rgba` field not rendering properly due to misspelled CSS enqueue handle.
* Fixed: jQuery deprecation notices in `typography` JavaScript.
* Fixed: Error in connection banner on first-time activation.
* Fixed: Missing redux-banner-admin.min.js file.
* Fixed: Added extra check for the existence of the function name with callbacks. Some themes are not doing it correctly and crashing WordPress.
* Release date: July 02, 2023

= 4.4.3 =
* Fixed: Typo in JavaScript enqueue handle broke `typography` and `slider` fields.
* Release date: June 29, 2023

= 4.4.2 =
* New: Icon Select Extension. Please review notes in README.md. [https://devs.redux.io/core-extensions/icon-select.html](https://devs.redux.io/core-extensions/icon-select.html)
* Added: `init_empty` argument for `repeater` field.
* Added: Class alias for customizer extension for Redux 3.x backward compatibility.
* Modified: Unused code cleanup.
* Modified: Moved `font-display` to Google font API enqueue and out of `output` CSS string.
* Updated: Default Google font list.
* Updated: ACE Editor 1.23.0
* Fixed: jQuery deprecation notices in `typography` field.
* Fixed: Special characters validation not catching special characters.
* Fixed: Validation routines not working complete with multiple metaboxes
* Improved: Redux no longer enqueues resources for each field instance.
* Release date: June 29, 2023

= 4.4.1 =
* New: User Metaboxes Extensions [https://devs.redux.io/core-extensions/user-metaboxes.html](https://devs.redux.io/core-extensions/user-metaboxes.html)
* Fixed: Multiple `multi_media` fields in the same section not respecting `max_upload_count`.
* Fixed: Glitch in validation causing color pickers to fail in rare use case.
* Fixed: Google Maps JavaScript.
* Updated: Minimum WordPress version to 5.0
* Release date: April 26, 2023

= 4.4.0 =
* Fixed: `Invalid argument` error inside `custom_fonts` on certain setups.
* Fixed: Deprecated Google Map API broke `google_maps` extension.
* Removed: Extendify Template Library
* Added: Connection banner to display Extendify removal notice with a plugin download option.
* Release date: March 29, 2023

= 4.3.26 =
* Modified: Empty `custom_font` list no longer creates empty fonts.css file.
* Release date: February 02, 2023

= 4.3.25 =
* Modified: Reworked directory enumeration for `custom_fonts` to avoid potential fatal errors.

= 4.3.24 =
* Additional work to make `custom_fonts` override old standalone extension version.
* Release date: January 20, 2023

= 4.3.23 =
* Tweaked Custom Fonts extension to avoid conflicts with the older standalone extension.
* Update: Extendify Library 1.2.4
* Release date: January 20, 2023

= 4.3.22 =
* Added: Custom Fonts extension [https://devs.redux.io/core-extensions/google-maps.html](https://devs.redux.io/core-extension/custom-fonts.html)
* Fixed: Metaboxes `post_format` selections not responding to clicks when Gutenberg is active due to class name changes.
* Fixed: Custom font data added via filter would trigger a warning if not an array.
* Update: Extendify Library 1.2.3
* Release date: January 19, 2023

= 4.3.21 =
* Added: Google Maps extension.
* Fixed: Widget area UI improperly aligned when `dev_mode` set to `false`.
* Fixed: `spinner` field not outputting `output` data.
* Fixed: Metaboxes CSS causing layout issues when `dev_mode` set to `false`.
* Update: Extendify Library 1.2.1
* Update: Font Awesome 6.2.1
* Release date: December 05, 2022

= 4.3.20 =
* Added: Widget Areas extension (for use with Classic Widgets only).
* Fixed: `spinner` field returning JavaScript error.
* Fixed: `required` not working outside a `repeater` when `repeater` field is loaded somewhere in the project.
* Fixed: JS error when `typography` `font-style` set to `false`.
* Updated: Removed registration verbiage from Google Fonts update notice.
* Updated: Extendify Library 1.0.1
* Release date: November 2, 2022

= 4.3.19 =
* Fixed: Extendify menu item appearing when it should not.
* Fixed: Blank page template would cause a fatal error.
* Release date: September 30, 2022

= 4.3.18 =
* New: Typography `weights` argument to override standard default weights.
* Updated Extendify Library 0.10.2
* Updated: Font Awesome 6.2.0
* Modified: Attempt to override old theme embedded extensions that use the 3.x loading method.
* Fixed: Social Profiles in metaboxes, hopefully.
* Fixed: `slides` field not showing image upon select. Thanks, @animeiswrong
* Removed: Social Profiles Widget (use the shortcode in HTML widget instead. See docs).
* Removed: Redux template library (use Extendify template library instead).
* Removed: Appsero registration for Redux Pro.
* Modified: Cleanup of old or outdated code.
* Release date: September 26, 2022

= 4.3.17 =
* Added: Social Profiles extension. [https://devs.redux.io/core-extensions/social-profiles.html](https://devs.redux.io/core-extensions/social-profiles.html)
* Fixed: Metabox post-types and templates selection inoperative on new posts.
* Updated: Extendify Library.
* Release date: August 22, 2002

= 4.3.16 =
* Added: Accordion extension. [https://devs.redux.io/core-extensions/accordion.html](https://devs.redux.io/core-extensions/accordion.html)
* Added: JS Button extension. [https://devs.redux.io/core-extensions/js-button.html](https://devs.redux.io/core-extensions/js-button.html)
* Fixed: Validation messages dismissed when using `ace_editor` field after `redux_change` event.
* Updated: Extendify Library.
* Release date: July 21, 2022

= 4.3.15 =
* Added: Multi Media extension. [https://devs.redux.io/core-extensions/multi-media.html](https://devs.redux.io/core-extensions/multi-media.html)
* Added: DateTime extension. [https://devs.redux.io/core-extensions/date-time-picker.html](https://devs.redux.io/core-extensions/date-time-picker.html)
* Fixed: Deprecation error surrounding `add_menu_page` in WordPress 6.0.
* Fixed: `undefined` unit entry in `letter-spacing` subfield of the `typography` field.
* Modified: Deprecation notices for outdated API.
* Updated: Extendify Library.
* Release date: June 21, 2022

= 4.3.14 =
* New: `typography` field supports individual unit types for subfields that support them (font-size, line-height, etc.)  See: [https://devs.redux.io/core-fields/typography.html](https://devs.redux.io/core-fields/typography.html)
* Fixed: Redux installed via TGMPA failing with "This plugin does not have a valid header."
* Updated: Extendify Library.
* Release date: May 19, 2022

= 4.3.13 =
* Fixed: Work for `required` functionality within the `repeater` field.
* Fixed: Filter out bad default values for `color_rgba` field.
* Fixed: jQuery deprecation notice.
* Fixed: Type error in `import_export` field.  Additional `repeater` JS fix.
* Fixed: `required` functionality within the `repeater` field.
* Modified: Additional sanitizing on color hex values.
* Modified: Customizer code to eliminate `init()` error.
* Updated: Extendify Library.
* Release date: May 05, 2022

= 4.3.12 =
* Updated: Vendor libraries.
* Updated: Extendify Library.
* Fixed: jQuery deprecation notices.
* Fixed: Filesystem class error.
* Fixed: Customizer not saving data for sections not shown in the customizer.
* Fixed: Fix deprecation errors in customizer.
* Fixed: Fix core deprecation notices in metaboxes.
* Release date: March 08, 2022

= 4.3.11 =
* Added: Advanced Customizer!
* Added: Font Awesome 6 Library for future extensions.
* Modified: Enforcing deprecation notices for deprecated functions. Developers: Please update your code as necessary.
* Modified: Connection banner to meet wp.org library standards.
* Updated: Extendify Library.
* Release date: February 23, 2022

= 4.3.10 =
* Added: Repeater field for beta testing.
* Modified: Background field will now show background styling options even if `background-image` is not set.
* Modified: Connection banner now promotes Extendify plugin with download/activate option.
* Updated: Extendify Library.
* Release date: February 09, 2022

= 4.3.9 =
* Fixed: Extendify Library JavaScript error.
* Release date: January 26, 2022

= 4.3.8 =
* Fixed: Spacing field defaults to `px` if no default is set.
* Fixed: Remove plugin.php hack in Appsero SDK.
* Updated: Default Google Fonts list brought up to current release
* Updated: Extendify Library.
* Release date: January 25, 2022

= 4.3.7 =
* Fixed: Incorrect global variable assignment. Thanks, @webbudesign.
* Release date: January 11, 2022

= 4.3.6 =
* Modified: Update to the Extendify Library.
* Modified: Moved Extendify and Redux templates libraries back to root folder.
* Modified: Removed "Gutenberg is currently disabled" notice when the Classic Editor plugin is active.
* Fixed: `date` shortcode without attributes producing error.
* Fixed: Various jQuery deprecation fixes.
* Release date: January 11, 2022

= 4.3.5 =
* Added: Add former premium feature: Option panel Search Bar. See Sample demo or the [docs site](https://devs.redux.io/core-extensions).
* Added: Add former premium feature: Shortcodes.  See Sample demo or the [docs site](https://devs.redux.io/core-extensions).
* Fixed: Editor in metaboxes not saving HTML.  WIll now save the same HTML posts/pages allows.
* Fixed: Front end formatting issue with the Extendify template library.
* Release date: December 01, 2021

= 4.3.4 =
* Fixed: CSS and JS not loading when embedding Redux due to a malformed path.
* Modified: Update to the Extendify template library.
* Release date: November, 24 2021

= 4.3.3 =
* Modified: Move template libraries to redux-core directory.
* Modified: Update to the Extendify template library.
* Release date: November 16, 2021

= 4.3.2 =
* Added: Metaboxes!
* Fixed: Incorrect return type in Options Constructor.
* Modified: Prefixed Browser class to avoid conflict with older versions in other projects.
* Release date: November 11, 2021

= 4.3.1 =
* Fixed: `wp_mail has been declared by another process or plugin` message.
* Fixed: Malformed README wouldn't allow clicking of some support links.
* Release date: September 22, 2021

= 4.3.0 =
* Added: Gutenberg Template Library updated to the new Extendify library. See more information here about this upgrade and how to access the legacy library: [https://redux.io/gutenberg-template-library-upgrade](https://redux.io/gutenberg-template-library-upgrade).
* Added: Option to enable/disable Template libraries.  Found under Settings > Redux > Templates
* Added: Redux debug data moved to WordPress Site Health Info screen.
* Removed: Redux Framework Health Screen.
* Modified: Tools > Redux Framework screen moved to Settings > Redux
* Modified: Redux Templates disabled by default.
* Release date: September 21, 2021

** For a full changelog, see https://github.com/reduxframework/redux-framework/blob/master/CHANGELOG.md **

== Frequently Asked Questions ==

= Why doesn't this plugin do anything? =

Redux is an option framework... in other words, it's not designed to do anything on its own! You can, however, activate a demo mode to see how it works.

= How can I learn more about Redux? =

Visit our website at [https://redux.io/](https://redux.io)

