# Redux Changelog

## 4.5.0.5
* Updated: Font Awesome 6.7.1
* Fixed: Entire panel over customizer panel not 'clickable.'

## 4.5.0.4
* Updated: Font Awesome 6.7
* Fixed: Options Search bar rendering multiple time on customizer UI.
* Added `null` to multiple typesafe declarations.
* Fixed: Changed typesafe declarations to transient variables from `array` to `mixed` to prevent fatal errors.
* Modified: Customizer HTML output to support WordPress installations prior to version 6.7.

## 4.5.0.3
* Fixed: `color_scheme` and `social_profiles` giving `cannot assign null to array` errors when fields not in use.
* Fixed: #4037 - Additional JavaScript errors in regard to TinyMCE when not loaded via `editor` field. 

## 4.5.0.2
* Fixed: #4037 - `repeater` "Add" button failing when no `editor` field was loaded.
* Fixed: #4040 - WP 6.7 broke  Redux menus in customizer.
* Fixed: "Reset Section" resetting everything to blank or zero.
* Modified: Option panel search bar moved to core (previously an extension).
* Added: CSS output added to `slider` field.

## 4.5.0.1
* Modified: Allow `null` assignments to core variable to prevent fatal errors when devs disable Google Fonts.
* Added: Minimum PHP 7.4 warning message to admin screen to prevent fatal errors. Some people are, apparently, still using outdated PHP.

## 4.5.0
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

## 4.4.18
* Fixed: #4006: XSS fix in 'color_scheme' import.
* Updated: Font Awesome 6.6.0
* Release date: July 19, 2024

## 4.4.17
* Fixed: `social_profiles` in customizer.
* Fixed: Section divide returning `null`, which caused a PHP warning.
* Fixed: Undefined index in `tabbed` when resetting settings.
* Release date: May 23, 2024

## 4.4.16
* Modified: Temporarily disable `social_profiles` and `color_scheme` from customizer. They don't work.
* Removed: Finished removing Redux Pro support.
* Removed: Extendify plugin banner at first launch.
* Updated: Font Awesome 6.5.2
* Release date: April 27, 2024

## 4.4.15
* Fixed: `spacing`, `dimension`, and `border` fields not saving changed values.
* Fixed: #3995 - `switch` and `button_set` not saving within `tabbed` interface.
* Release date: March 22, 2024

## 4.4.14
* Fixed: #3993, #3992 - "No Field ID is set" message causing jumbled backend layout. 
* New: Content Field [https://devs.redux.io/core-fields/content.html](https://devs.redux.io/core-fields/content.html)
* Updated: Bring inputs up to W3C standards.
* Updated: First round of PHP 8.3 compatibility.
* Release date: March 14, 2024

## 4.4.13
* Added: Filter to disable Google Font updates: `"redux/{opt_name}/field/typography/google_font_update"`. Return `false` to disable.
* Fixed: `color_scheme` crashing WordPress with 'critical error' for users still using PHP 7.1.
* Added: WordPress 6.5 compatibility.
* Release date: February 13, 2024

## 4.4.12
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


## 4.4.11
* Fixed: Cosmetic `box_shadow` fix.
* Fixed: Required not hiding linked fields in customizer.
* Fixed: `tabbed` and `repeater` fields not resetting when using Section Reset.
* Fixed: #3983 - Continued damage done by WP Filesystem PR
* Updated: Font Awesome 6.5.1
* Release date: December 18, 2023

## 4.4.10
* New: Tabbed Extension [https://devs.redux.io/core-extensions/tabbed.html](https://devs.redux.io/core-extensions/tabbed.html)
* Modified: Typography preview background will shift to black when lighter font colors are selected. Thanks, @herculesdesign
* Modified: Additional rollback changes made to the filesystem class causing false file permission issue messages.
* Fixed: Errant spaces in ACE Editor field.
* Fixed: Array check in color validation to avoid errors. It works ONLY with the color field. Nothing else.
* Improved: Filesystem killswitch logic.
* Release date: December 05, 2023

## 4.4.9
* Modified: Rollback changes made to the filesystem class causing false file permission issue messages.
* Release date: October 26, 2023

## 4.4.8
* Modified: Additional safeguards against read-only filesystems. Thanks @cezarpopa-cognita.
* Fixed: #3970 - Added `is_string` check to WordPress data callback argument.
* Removed: Unused code for Support Ticket Submission feature that was never finished.
* Fixed: Removed extra spaces from `textarea`.
* Added: WordPress 6.4 compatibility.
* Release date: October 17, 2023

## 4.4.7
* Removed: CDN vendor support for `ace_editor`. Devs won't update their code, leaving us no choice. Use the `redux/<opt_name>/fields/ace/script` filter to enqueue a local ACE Editor script if needed.
* Fixed: Redux template PHP not autoloading.
* Release date: September 14, 2023

## 4.4.6
* New: Global arg `fontawesome_frontend` to enqueue the internal Font Awesome CSS on the front end.
* New: Taxonomy Metaboxes Extension [https://devs.redux.io/core-extensions/taxonomy.html](https://devs.redux.io/core-extensions/taxonomy.html)
* Fixed: Font Awesome not enqueueing on the frontend for `social_profiles` field.
* Fixed: HTML Output for User Profile Metaboxes.
* Fixed: Admin panel CSS.
* Fixed: Adjusted translation for Google Font update message.
* Fixed: Continuing effort to combat old CDN code because some devs aren't updating their code.
* Fixed: REDUX_PLUGIN_FILE failed with embedded installed.  WE NO LONGER SUPPORT EMBEDDED. IT'S FOR LEGACY INSTALLS ONLY.
* Release date: September 13, 2023
*
## 4.4.5
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

## 4.4.4
* Fixed: Revert `redux-admin` CSS handle to previous handle.
* Fixed: `color_rgba` field not rendering properly due to misspelled CSS enqueue handle.
* Fixed: jQuery deprecation notices in `typography` JavaScript.
* Fixed: Error in connection banner on first-time activation.
* Fixed: Missing redux-banner-admin.min.js file.
* Fixed: Added extra check for the existence of the function name with callbacks. Some themes are not doing it correctly and crashing WordPress.
* Release date: July 02, 2023

## 4.4.3
* Fixed: Typo in JavaScript enqueue handle broke `typography` and `slider` fields.
* Release date: June 29, 2023

## 4.4.2
* New: Icon Select Extension. Please review notes in README.md. [https://devs.redux.io/core-extensions/icon-select.html](https://devs.redux.io/core-extensions/icon-select.html)
* Added: `init_empty` argument for `repeater` field.
* Added: Class alias for customizer extension for Redux 3.x backward compatibility.
* Modified: Unused code cleanup.
* Modified: Moved `font-display` to Google font API enqueue and out of `output` CSS string.
* Updated: Default Google font list.
* Updated: ACE Editor 1.23.0
* Fixed: jQuery deprecation notices in `typography` field.
* Fixed: Special characters validation not catching special characters.
* Fixed: #3957 - Validation routines not working complete with multiple metaboxes
* Improved: Redux no longer enqueues resources for each field instance.
* Release date: June 29, 2023

## 4.4.1
* New: User Metaboxes Extensions [https://devs.redux.io/core-extensions/user-metaboxes.html](https://devs.redux.io/core-extensions/user-metaboxes.html)
* Fixed: Multiple `multi_media` fields in same section not respecting `max_upload_count`.
* Fixed: Glitch in validation causing color pickers to fail in rare use case.
* Fixed: Google Maps JavaScript.
* Updated: Minimum WordPress version to 5.0
* Release date: April 26, 2023

## 4.4.0
* Fixed: `Invalid argument` error inside `custom_fonts` on certain set ups.
* Fixed: Deprecated Google Map API broke `google_maps` extension.
* Removed: Extendify Template Library
* Added: Connection banner to display Extendify removal notice with plugin download option.
* Updated: Font Awesome 6.4
* Release date: March 29, 2023

## 4.3.26
* Modified: Empty `custom_font` list no longer creates empty fonts.css file.
* Release date: February 02, 2023

## 4.3.25
* Modified: Reworked directory enumeration for `custom_fonts` to avoid potential fatal errors.
* Release date: January 27, 2023

## 4.3.24
* Additional work to make `custom_fonts` override old standalone extension version.
* Release date: January 20, 2023

## 4.3.23
* Tweaked Custom Fonts extension to avoid conflicts with older standalone extension.
* Update: Extendify Library 1.2.4
* Release date: January 20, 2023

## 4.3.22
* Added: Custom Fonts extension [https://devs.redux.io/core-extensions/google-maps.html](https://devs.redux.io/core-extension/custom-fonts.html)
* Fixed: #3928 - Metaboxes `post_format` selections not responding to clicks when Gutenberg is active due to class name changes.
* Fixed: Custom font data added via filter would trigger a warning if not an array.
* Update: Extendify Library 1.2.3
* Release date: January 19, 2023

## 4.3.21
* Added: Google Maps extension [https://devs.redux.io/core-extensions/google-maps.html](https://devs.redux.io/core-extensions/google-maps.html)
* Fixed: Widget area UI improperly aligned when `dev_mode` set to `false`.
* Fixed: `spinner` field not outputting `output` data.
* Fixed: Metaboxes CSS causing layout issues when `dev_mode` set to `false`.
* Update: Extendify Library 1.2.1
* Update: Font Awesome 6.2.1
* Release date: December 05, 2022

## 4.3.20
* Fixed: `spinner` field returning JavaScript error.
* Fixed: `required` not working outside a `repeater` when `repeater` field is loaded somewhere in the project.
* Fixed: JS error when `typography` `font-style` set to `false`.
* Updated: Removed registration verbiage from Google Fonts update notice.
* Added: Widget Areas extension (for use with Classic Widgets only).
* Updated: Extendify Library 1.0.1
* Release date: November 02, 2022

## 4.3.19
* Fixed: Extendify menu item appearing when it should not.
* Fixed: #3909 - Blank page template would cause fatal error.
* Release date: September 30, 2022

## 4.3.18
* New: #3903 - Typography `weights` argument to override standard default weights.
* Updated Extendify Library 0.10.2
* Updated: Font Awesome 6.2.0
* Modified: Attempt to override old theme embedded extensions that use the 3.x loading method.
* Fixed: Social Profiles in metaboxes, hopefully.
* Fixed: `slides` field not showing image upon select. Thanks @animeiswrong
* Removed: Social Profiles Widget (use the shortcode in HTML widget instead. See docs).
* Removed: Redux template library (use Extendify template library instead).
* Removed: Appsero registration for Redux Pro.
* Modified: Cleanup of old or outdated code.
* Release date: September 26, 2022

## 4.3.17
* Added: Social Profiles extension. [https://devs.redux.io/core-extensions/social-profiles.html](https://devs.redux.io/core-extensions/social-profiles.html)
* Fixed: Metabox post types and templates selection inoperative on new posts.
* Updated: Extendify Library.
* Release date: August 22, 2002

## 4.3.16
* Added: Accordion extension. [https://devs.redux.io/core-extensions/accordion.html](https://devs.redux.io/core-extensions/accordion.html)
* Added: JS Button extension. [https://devs.redux.io/core-extensions/js-button.html](https://devs.redux.io/core-extensions/js-button.html)
* Fixed: #3888 - Validation messages dismissed when using `ace_editor` field after `redux_change` event.
* Updated: Extendify Library.
* Release date: July 21, 2022

## 4.3.15
* Added: Multi Media extension. [https://devs.redux.io/core-extensions/multi-media.html](https://devs.redux.io/core-extensions/multi-media.html)
* Added: DateTime extension. [https://devs.redux.io/core-extensions/date-time-picker.html](https://devs.redux.io/core-extensions/date-time-picker.html)
* Fixed: Deprecation error surrounding `add_menu_page` in WordPress 6.0.
* Fixed: `undefined` unit entry in `letter-spacing` subfield of the `typography` field.
* Modified: Deprecation notices for outdated API.
* Updated: Extendify Library.
* Release date: June 21, 2022

## 4.3.14
* New: `typography` field supports individual unit types for subfields that support them (font-size, line-height, etc.)  See: [https://devs.redux.io/core-fields/typography.html](https://devs.redux.io/core-fields/typography.html)
* Fixed: Redux installed via TGMPA failing with "This plugin does not have a valid header."
* Updated: Extendify Library.
* Release date: May 19, 2022

## 4.3.13
* Fixed: Work for `required` functionality within the `repeater` field.
* Fixed: Filter out bad default values for `color_rgba` field.
* Fixed: jQuery deprecation notice.
* Fixed: Type error in `import_export` field.  Additional `repeater` JS fix.
* Fixed: `required` functionality within the `repeater` field.
* Modified: Additional sanitizing on color hex values.
* Modified: Customizer code to eliminate `init()` error.
* Updated: Extendify Library.
* Release date: May 05, 2022

## 4.3.12
* Updated: Vendor libraries.
* Updated: Extendify Library.
* Fixed: jQuery deprecation notices.
* Fixed: Filesystem class error.
* Fixed: Customizer not saving data for sections not shown in the customizer.
* Fixed: Fix deprecation errors in customizer.
* Fixed: Fix core deprecation notices in metaboxes.
* Release date: March 08, 2022

## 4.3.11
* Added: Advanced Customizer!
* Added: Font Awesome 6 Library for future extensions.
* Modified: Enforcing deprecation notices for deprecated functions. Developers: Please update your code as necessary.
* Modified: Connection banner to meet wp.org library standards.
* Updated: Extendify Library.
* Release date: February 23, 2022

## 4.3.10
* Added: Repeater field!
* Modified: Background field will now show background styling options even if `background-image` is not set.
* Modified: Connection banner now promotes Extendify plugin with download/activate option.
* Updated: Extendify Library.
* Release date: February 09, 2022

## 4.3.9
* Fixed: Extendify Library JavaScript error.
* Release date: January 26, 2022

## 4.3.8
* Fixed: Spacing field defaults to `px` if no default is set.
* Fixed: Remove plugin.php hack in Appsero SDK.
* Updated: Default Google Fonts list brought up to current release
* Updated: Extendify Library.
* Release date: January 25, 2022

## 4.3.7
* Fixed: #3861 - Incorrect global variable assignment. Thanks, @webbudesign.
* Release date: January 11, 2022

## 4.3.6
* Modified: Update to the Extendify Library.
* Modified: Moved Extendify and Redux templates libraries back to root folder.
* Modified: Removed "Gutenberg is currently disabled" notice when the Classic Editor plugin is active.
* Fixed: `date` shortcode without attributes producing error.
* Fixed: Various jQuery deprecation fixes.
* Release date: January 11, 2022
*
## 4.3.5
* Added: Add former premium feature: Option panel Search Bar. See Sample demo or the [docs site](https://devs.redux.io/core-extensions).
* Added: Add former premium feature: Shortcodes.  See Sample demo or the [docs site](https://devs.redux.io/core-extensions).
* Fixed: #3852 - Editor in metaboxes not saving HTML.  WIll now save the same HTML posts/pages allows.
* Fixed: Front end formatting issue with the Extendify template library.
* Release date: December 01, 2021

## 4.3.4
* Fixed: CSS and JS not loading when embedding Redux due to malformed path.
* Modified: Update to the Extendify template library.
* Release date: November 24, 2021

## 4.3.3
* Modified: Move template libraries to redux-core directory.
* Modified: Update to the Extendify template library.
* Release date: November 16, 2021

## 4.3.2
* Added: Metaboxes!
* Fixed: Incorrect return type in Options Constructor.
* Modified: Prefixed Browser class to avoid conflict with older versions in other projects.
* Release date: November 11, 2021

## 4.3.1
* Fixed: `wp_mail has been declared by another process or plugin` message.
* Fixed: Malformed README wouldn't allow clicking of some support links.
* Release date: September 22, 2021

## 4.3.0
* Added: Gutenberg Template Library updated to the new Extendify library. See more information here about this upgrade and how to access the legacy library: [https://redux.io/gutenberg-template-library-upgrade](https://redux.io/gutenberg-template-library-upgrade).
* Added: Option to enable/disable Template libraries.  Found under Settings > Redux > Templates
* Added: Redux debug data moved to WordPress Site Health Info screen.
* Removed: Redux Framework Health Screen.
* Modified: Tools > Redux Framework screen moved to Settings > Redux
* Modified: Redux Templates disabled by default.
* Release date: September 21, 2021

## 4.2.14
* Fixed: Parse error in Import/Export module due to old versions of PHP.  Remember folks, recommended minimum for WordPress is PHP 7.4.

## 4.2.13
* Fixed: #3822 - Default value function returns string or array to prevent type error.
* Modified: #3820 - Better support for Redux embedded in themes.
* Modified: `install_plugins` security level now required to install Template blocks that require additional plugins.  This was done for security reasons.
* Added: New global arg `load_on_cron`.  Set to true if you require Redux to run within a cron initiated function.
* Removed: URL based  Import/Export option due to security concerns.  Manual Import/Export features remain.
* Removed: Support URL feature due to security issues.  Please use WordPress Site Health 'copy to clipboard' compiler to submit system data when reporting issues.

## 4.2.11
* Fixed: Removed type declarations on core return values to support improperly written third-party extensions.
* Fixed: Added shim to prevent errors on functions calls outdated extensions are still using.
* Fixed: Removed type declarations on field code to support outdated versions of PHP (PHP 7.4 is the minimum recommendation from WordPress...please update if you are able. [https://wordpress.org/about/requirements](https://wordpress.org/about/requirements) ).
* Fixed: Support URL button kicking back a JavaScript error.

## 4.2.10
* Fixed: Output on the frontend triggering error.
* Fixed: Updated shims to support older extensions not authored by Redux.io

## 4.2.9
* Fixed: WordPress data class now works properly.

## 4.2.8
* Fixed Redux instances returning null.  Saved options now show on the front end.

## 4.2.7
* Fixed: Options reverting or not saving.

## 4.2.6
* Fixed: Fatal error if passing null as an option section.
* Fixed: Error in connection banner routine.

## 4.2.5
* Fixed: Return type mismatch in Redux Helpers.

## 4.2.4
* Fixed: Taxonomy WordPress data not handled properly in fields that support WordPress data.

## 4.2.3
* Fixed: Return type error in Redux templates.

## 4.2.2
* Fixed: WordPress data options not handling WP_Error properly.

## 4.2.1
* Fixed: Type mismatch regarding select boxes and callbacks.

## 4.2.0
* New: Typography features:  Top and bottom margins, text-shadow.
* New: Media Image Filters (greyscale, sepia, opacity, contrast, invert, blur, saturate, brightness, hue-rotate).
* New: Filters (type, reach, angle) for Gradient color field.
* New: Box Shadow field.
* New: Flyout Submenus: `flyout_submenus` global arg.
* New: Alpha color option for color field: `color_alpha` field arg for fields that support color pickers.
* Updated: select2 library to 4.1.0.
* Updated: readme.txt to conform to wp.org standards.
* Fixed: Remove PHP 7.2 syntax to keep older versions of PHP 7 happy.
* Fixed: Redux templates not showing on 'page attributes' for some.
* Fixed: Additional PHP 8.0 compatibility.
* Fixed: Widget screen would not load due to conflict with template library.
* Added:  WordPress 5.8 compatibility.
* Improved: Better Gutenberg block editor detection.
* Modified: Redux Pro no longer required for automatic Google font updates.
* Modified: Rename Parsedown class for the raw field to avoid conflicts with other plugins.
* Removed: `async_typography` global arg.  Google no longer supports it.  Use `font_display` with one of the following `auto|block|swap|fallback|optional`.  See: [https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display](https://developer.mozilla.org/en-US/docs/Web/CSS/@font-face/font-display)

## 4.1.29
* Fixed: All PHP 7.4 specific syntax backed out.  It caused older versions of PHP to report fatal errors.
* Fixed: Index error in the image_select field.

## 4.1.28
* Fixed: #217 - Redux templates loading on post types with no block editor.
* Fixed: #158 - Redux theme checks no longer prevent theme check plugin from functioning.
* Fixed: #215 - Heartbeat check no longer eats the function if disregarded.
* Fixed: #222 - Background field image now hides preview image upon removal.
* Fixed: RAW field in sample config now works.
* Fixed: Helper function is_field_in_use now returns false, instead of null.
* Fixed: Palette field rendering improperly.
* Fixed: Google font update fail.
* Modified: buttonset() jQuery widget deprecated.  Replaced with controlgroup().
* Modified: Additional JavaScript updates to fix jQuery deprecation notices.

## 4.1.27
* Fixed: Image select not selecting default value.
* Modified: #209 - Link color field overridden by theme.  Added 'important' arg to the output array to fix.  See sample config.
* Fixed: #208 - Same config not setting footer background in Twenty-twenty theme due to incorrect class.
* Fixed: #207 - Radio field not displaying text after save/refresh when displaying WordPress data.
* Modified: #210 - Donation text removed.
* Fixed: #206 - Link color CSS compiling incorrectly due to late escaping.

## 4.1.26
* Added: Menu accent introduced in WordPress 5.7.
* Updated: ACE Editor 1.4.12.
* Updated select2 to support cssContainer.
* Fixed: Multiple submenus in metaboxes; the last submenu it cut off.
* Fixed: #200 - Fatal error: Can't use function return value in write context.
* Fixed: #203 - PHP 8.0 deprecation warnings.
* Fixed: Malformed HTML causing Redux pro alpha color-picker to not render.
* Fixed: Improved class checks for Redux Pro.
* Fixed: jQuery 3.x deprecation notices.
* Fixed: Malformed SCSS.
* Release date: March 17, 2021

## 4.1.25
* Fixed: #186 - Erroneous icon on button_set field after WP 5.6 update.
* Fixed: #179 - Erroneous icon on palette field after WP 5.6 update.
* Fixed: PHP error in init_delay function during heartbeat API.
* Fixed: #188 - Options object field not rendering.
* Release date: Jan 21, 2021

## 4.1.24
* Fixed: Select2 callback fix for select fields.
* Added: Shim: empty field_*.php files to fix developers including files improperly.
* Fixed: Changed use of ctype_xdigit to account for hosts where it's disabled.
* Added: Shim for people using terms data key, but using taxonomies instead of taxonomy.
* Fixed: Static call mismatch in redux colors.
* Fixed: CSRF security issue with a flipped if conditional. Thanks, @ErwanLR.
* Fixed: WordPress 4.6 API warnings.
* Fixed: WordPress 4.6 customizer issue where fields not displaying properly.
* Fixed: Massive speed improvement to the library.
* Fixed: Pro template count error if previously activated and Redux Pro not enabled.
* Release date: Dec 12, 2020

## 4.1.23
* Fixed: Massive speed improvement to the library.
* Fixed: Pro template count error if previously activated and Redux Pro not enabled.
* Release date: Oct 24, 2020

## 4.1.22
* Fixed: Menu locations WordPress data object not providing name.
* Added: Undefined if menu location is not assigned to a menu.
* Fixed: Another import/export edge case.
* Fixed: Fix setField API value.
* Fixed: Older extension compatibility.
* Fixed: Text field error with data/options args not displaying properly.
* Fixed: Import/Export now properly respects order of objects. Now using PHP over JS json_encode.
* Release date: Oct 23, 2020

## 4.1.21
* Fixed: Fixed connection banner to hide even if JS is broken by jQuery migrate issue (WP 5.5).
* Fixed: Resolved all remaining legacy extension compatibility issues.
* Fixed: Custom callback with select field.
* Fixed: Typography bug when style was hidden.
* Fixed: Issue with text labels.
* Fixed: Google fonts html validation issues.
* Added: Feedback modal.
* Fixed: Import logic flaw.
* Fixed: Security bug. Thanks, @lenonleite of www.lenonleite.com.br.
* Release date: Oct 08, 2020

## 4.1.20
* Added: Properly adjust the blocked editor page width based on template selected.
* Added: Remove Qubely Pro update notice if Redux Pro is activated.
* Added: Broke out third-party premium plugins for filtering to help with understanding of what comes with Redux Pro.
* Added: Update block editor width when selecting a Redux template.
* Fixed: Some styling issues with preview modal.
* Fixed: Issue where plugin titles were not alphabetical.
* Fixed: Disabled third party premium dependencies.
* Fixed: Issue where crash would occur when Redux could not write out a file.
* Fixed: CSS selectors with HTML entities, like >, were not getting decoded for the passed compiler values.
* Fixed: Invalid logic causing some extensions not to run.
* Release date: Sep 18, 2020

## 4.1.18
* Fixed: Bug with typography output and non-array values for CSS selectors.
* Fixed: Bug with spacing field not adding the units when a default is provided.
* Added: Redux Pro install and activation flow.
* Fixed: Templates trial wasn't working properly! It works now. :)
* Release date: Sept 9, 2020

## 4.1.17
* Fixed: Edge case where enable Gutenberg notice doesn't disappear.
* Release date: Aug 27, 2020

## 4.1.16
* Fixed: Issue when null values were sent to Redux::set();
* Fixed: Default for Google fonts is now swap.
* Fixed: Fix for developers calling the API without checking for files.
* Fixed: Edge case for filter var not working on some sites.
* Fixed: Proper loading to override Redux 3 plugin.
* Added: Site name to WP data return.
* Fixed: Set height for library button when other plugins modify the CSS for the Gutenberg toolbar.
* Fixed: Don't show template messages on the front-end if an extension is missing. How did that get through?
* Fixed: Non-array values for WP data. Thanks, @wilokecom.
* Added: Notification so users can enable Gutenberg when disabled.
* Added: Welcome guide to Gutenberg screen.
* Fixed: Some readme issues.
* Release date: Aug 26, 2020

## 4.1.15
* Fixed: Defaults were not saving in some situations.
* Added: Various fallback calls for JS when fetching opt_names.
* Fixed: Warnings with Rest API due to WP 5.5.
* Fixed: Subsets now are full-width in typography when rendered after page load.
* Fixed: for subsets loading when font-family is not specified.
* Added: No opt-in to tracking when embedded. Google Fonts and panel notices are still there though.
* Fixed: Is local checks conflicting with some servers.
* Fixed: WooCommerce race condition with their autoloader causing issues with some sites.
* Updated: Complete overhaul of WordPress data class.
* Fixed: Backtrace errors when blocked on servers.
* Fixed: Select2 and required fixes.
* Fixed: Customizer sidebar not showing in some cases.
* Added: Google Fonts now load ~20% faster!!!
* Release date: Aug 19, 2020

## 4.1.14
* Added: Shim for ReduxFramework->get_default_value()
* Fixed: Local issue with WP and strtolower. Sites that couldn't find classes should work now.
* Fixed: Ajax for select boxes is now working again.
* Fixed: Autoloading to bypass other embedded versions of Redux.
* Fixed: Customizer interactions are MUCH faster now. Had a greedy CSS selector before.
* Fixed: If opt_names had multiple dashes in them, JS errors occurred by a non-global replace.
* Fixed: Fix for servers that disable output buffers.
* Fixed: Ajax now does not load anything else, faster calls.
* Fixed: .folds replace issue when opt_name selector wasn't properly found.
* Release date: Aug 11, 2020

## 4.1.13
* Fixed: Major typography bug affecting saving in the panel as well as third-party extensions.
* Fixed: Customizer issue with some external extensions.
* Added: Removed `FS_METHOD` define completely.
* Release date: Aug 5, 2020

## 4.1.12
* Fixed: Direct calls to ReduxFramework were causing unexpected errors.
* Fixed: JS error on .replace because opt_name wasn't found.
* Added: `FS_METHOD` define location, had to move lower in the stack.
* Release date: Aug 5, 2020

## 4.1.11
* Fixed: Templates JS not loading and conflicting with other plugins. Need to namespace or something.
* Added: `FS_METHOD` define method for environments where it is not properly defined.
* Release date: Aug 4, 2020

## 4.1.10
* Fixed: Minified templates directory now loads.
* Added: Shadow files from old repo to stop errors from previously included third-party developer includes.
* Release date: Aug 4, 2020

## 4.1.9
* Fixed: Compatibility issue when developers made custom panel templates. The opt_name wasn't fetched and thus saving broke.
* Release date: Aug 1, 2020

## 4.1.8
* Fixed: Map files are now all present.
* Fixed: Path fix for how developers called the typography file directory.
* Release date: Aug 1, 2020

## 4.1.7
* Fixed: Issue with sortable in text mode not properly passing the name attribute and thus not saving properly.
* Fixed: Compatibility with old extension names to not crash other plugins.
* Release date: July 31, 2020

## 4.1.6
* Fixed: Issue with customizer double loading the PHP classes and causing an exception.
* Fixed: Chanced a class name as to not conflict with a 6+ year old version of Redux.
* Release date: July 30, 2020

## 4.1.5
* Fixed: Google fonts not working when old configs used string vs an array for output.
* Release date: July 30, 2020

## 4.1.4
* Fixed: Google fonts loading over non-secure breaks fonts. Forced all SSL for Google fonts.  :)
* Release date: July 30, 2020

## 4.1.3
* Fixed: Issue where theme devs tried to bypass the framework. Literally I made an empty file to fix their coding. :P
* Release date: July 29, 2020

## 4.1.2
* Fixed: Don't try to set empty defaults when none are present.
* Fixed: Issue where the WP Data argument was misused.
* Release date: July 29, 2020

## 4.1.1
* Fixed: CSS decode when esc_attr replaces the HTML characters and CSS outputs are set with >'s.
* Release date: July 29, 2020

## 4.1.0
* Fixed: Compatibility with certain themes using the deprecated $_is_plugin variable.
* Release date: July 29, 2020

## 4.0.9
* Fixed: Complete compatibility fix for older Redux extensions.
* Release date: July 28, 2020

## 4.0.8
* Fixed: Initial library load was failing on some server setups.
* Release date: July 28, 2020

## 4.0.7
* Fixed: Race condition for PHP include for Redux_Typography causing blank white screens.
* Release date: July 28, 2020

## 4.0.5
* Fixed: Issues where the site crashes because of varied ways Redux was called.
* Fixed: Varied implementations of opt_names resulting in option panels not working as expected.
* Release date: July 28, 2020

## 4.0.4.2
* Fixed:    PHP issue when Redux was called in legacy methods.
* Fixed:    CSS output not rendering properly.

## 4.0.4
* Fixed:    PHPCS, all.
* Added:    Redux Templates.
* Added:    Complete rewrite of the underlying code base is complete and complies with all WordPress coding standards.

## 4.0.3
* Fixed:    PHPCS findings.
* Added:    New output_variables flags that dynamically add CSS variables to pages even on fields that do not support
  dynamic CSS output. Thanks, @andrejarh, for the idea!

## 4.0.2
* Fixed:    PHP backwards compatibility for extensions. Still have to work on JS, probably.

## 4.0.1.9
* Fixed:    #33 - Reset Section and Reset All not show appropriate message. Thanks, @voldemortensen!
* Fixed:    #29 - Multi-Text class not saving properly per new field. Adding to parent container only instead.
* Fixed:    #48 - Color RGBa field alpha was not showing.
* Removed:  Deprecated notices for old Redux API is fine.
* Fixed:    Fixes for color and comma numeric validations.
* Fixed:    #30 - Initial load of typography always initiates a redux_change. Resolved, thanks @kprovance.
* Fixed:    #31 - Text field not show the correct type, thanks @adrix71!

## 4.0.1.8
* Fixed:    #30 - Typography field causing a "save" notice.
* Added:    Start of Redux Builder API for fields.
* Modified: Moved some methods to new classes.
* Fixed:    Fix underscore naming convention in Redux_Field,
* Modified: Move two ajax saves routines to Redux_Functions_Ex for advanced customizer validation on save.

## 4.0.1.7
* Fixed:    #20 - variable missing $ dev.
* Fixed:    Customizer saving.
* Fixed     Customizer 'required'.
* Fixed:    button_set field not saving or loading in multimode.
* Fixed:    Section disable and section hidde in customizer.
* Fixed:    Some malformed field ids in sample-config, for some reason.
* Change:   #19 - `validate_msg` field arg replaces `msg` for validation schemes.  Shim in place for backward compatibility.

## 4.0.1.6
* Modified: Metabox lite loop not using correct extension key.
* Fixed:    Error when no theme is installed, which is possible, apparently.

## 4.0.1.5
* Fixed:    redux_post_meta returning null always.
* Added:    New Redux API get_post_meta to retrieve Metabox values.

## 4.0.1.4
* Fixed:      Metabox lite css/js not minifying on compile.
* New:        Redux APIs set_field, set_fields

## 4.0.1.3
* Improved:   Improvement record caller and warning fixes  Thanks @Torfadel.
* Fixed:      Errors on 'Get Support' page.

## 4.0.1.2
* Fixed:      #14 - Malformed enqueue URLs when embedding.

## 4.0.1.1
* Fixed:      Section field not hiding with required calls.
* Fixed:      Tour pointer not remembering closed state.

## 4.0.1
* New:        Initial public beta release.

## 4.0.0.22
* Added:    `allow_empty_line_height` arg for the typography field to prevent font-size from overriding a blank line-height field.

## 4.0.0.21
* Fixed:    Editor field not saving.

## 4.0.0.20
* Modified: Continued work for compatibility with the forthcoming Redux Pro.
* New:      Global arg `elusive_frontend` to enqueue the internal Elusive Font CSS on the front end.

## 4.0.0.19
* Added:    Metaboxes Lite.  See READ ME & sample config (sample-metabox-config.php).
* Added:    Removed "welcome" screen.  Replaced with 'What is this?' screen that no longer appears on first launch.
* Fixed:    Demo mode activates in Network Enabled mode.
* Modified: Additional WPCS work.
* Modified: Improved tracking.

## 4.0.0.18
* Added:    Field/section disabling.  See README.

## 4.0.0.17
* Fixed:    Data caching for WordPress data class.

## 4.0.0.16
* Added:    Optional AJAX loading for select2 fields.  See README.
* Disabled: WordPress Data caching.  It's broke.  See issue tracker.

## 4.0.0.15
* Added:    Field sanitizing added.  See README.
* Added:    Sanitizing examples added to sample config.
* Fixed:    Multi text not removing new added boxes until after save.

## 4.0.0.14
* Fixed:    Sections in customizer not rendering properly when customizer is set to false.  Thanks, @onurdemir.
* Fixed:    Function in ajax save class bombing when v3 is embedded.  Thanks, @danielbarenkamp.

## 4.0.0.13
* Nope.  I'm superstitious!

## 4.0.0.12
* Modified: Core to accept v3 based extensions with deprecation notice.
* Modified: @Torfindel's work on the extension/builder abstract.
* Finished: New Spinner UI, with extra args.

## 4.0.0.11
* Fixed:    Typo in redux.js caused panel to stall.  My bad.  :)
* Updated:  Gulp to version 4 to solve vulnerability issues.
* Modified: Linting of remaining JS files.

## 4.0.0.10
* Modified: redux.js opt_name logic to shim in older versions of metaboxes.
* Updated:  Spinner field mods.  New look.  No more jQuery deprecated notices.

## 4.0.0.9
* Fixed:    Import/Export feature not importing.  Damn typesafe decs got me again!!!  Thanks, WPCS.  ;-)
* Modified: Replace wpFileSystemInit in sample-config.php with a more practical solution.  Thanks, @Torfindel

## 4.0.0.8
* Modified: Changed typography update localize handle.  Too generic.  Conflicted with something else.
* Fixed:    Template head structure cause template notice to fail.  Thanks, @anikitas.
* Fixed:    Google font update choked over incorrect protocol.
* Fixed:    Required logic was operating backward.  Damn those typesafe operators!
* Fixed:    Redux v3 templates no longer crash v4 panel.
* Modified: Sample config to default settings.  They got all wonky for testing various things.

## 4.0.0.7
* Added:    'sites' to the select field data argument to return blog urls.
* Fixed:    Old extensions that extend to the ReduxFramework class failed to save.
* Fixed:    Extraneous semicolon output in admin notices.
* Fixed:    Redux v4 plugin trips fatal error on activation when v3 is embedded in a project.
* Modified: Moved new functions in Redux_Helpers due to incompatibility with embedded v3.
* Fixed:    Section field malformed when two or more section use together with no indentation.
* Fixed:    CDN loading failed even on success due to typesafe comparison (whoops, my bad) - kp.

## 4.0.0.6 (Welcome Fundraiser participants)
* Fixed:    Admin notices were malformed due to mis-escaped code.
* Added:    Abstract class for extensions.
* Modified: Last of the JavaScript mods from JSHint and JSCS.  Travis checks will no longer fail.

## 4.0.0.3
* Fixed:    Remove plugins_loaded hook to init plugin.  Broke backward compat with Redux 3.

## 4.0.0.2
* Modified: Sorter 'checkbox' now 'toggle' with UI redesign.  Full backward compatibility in place.
* Added:    Shim for redux localization JS objects from 3.x where the optName is not appended.  This broke repeater.

## 4.0.0.1
* Rewrite:  Core.  Now modularized.
* Update:   Select2 v4.0.3
* Added:    Dimension and spacing fields now contain extra and new units.
* Modified: The field 'validate' argument now supports an array of values.
* Updated:  Removed 'color_rgba' validation.  'color' validation now supports and sanitizes all color fields.
* Added:    New global arg 'admin_theme'.  The Redux Pro UI now mimics the WordPress menu system in terms of theme colors and behaviour.  Set this arg to 'classic' to use the old Redux UI.
* Fixed:    Tracking opt-in and newsletters popups now appear due to malformed inline javascript.
* Added:    Redux::disable_demo to the Redux API to disable the demo mode.  No more actions hooks.
* Added:    Redux::instance($opt_name) to the Redux API to obtain an instance of Redux based on the opt_name argument.
* Added:    Redux::get_all_instances() to the Redux API to return an array of all available Redux instances with the opt_name as they key.
* Modified: All outputting variables fully escaped to comply with wp.org and themeforest standards.
