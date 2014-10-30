# Redux Framework Changelog

## 3.3.9.6
* Added:    #1803 - Optgroup support for select field.

## 3.3.9.5
* Added:    Decimal increments to spinner.

## 3.3.9.4
* Added:    Customizer now supports PANEL! Yay 4.0.

## 3.3.9.3
* Fixed:    #1789 - Customizer now properly working again with WP 4.0. Odd bug.
* Modified: README.md updates as per @cmwwebfx suggestions.

## 3.3.9.2
* Fixed:    #1782 - Fixed some extra themecheck and customizer issues.

## 3.3.9.1
* Fixed:    #1782 - Media field not showing files after upload?  Hopefully this fixes it.

## 3.3.9
* Fixed:    #1775 - Call to undefined function is_customize_preview() in pre WP 4.0.
* Fixed:    Issue where in some cases tracking still occuring after opt-out.
* Modified: Documentation URL.

## 3.3.8.8
* Fixed:    #1742 - Sidebar subsections don't always expand.

## 3.3.8.7
* Fixed:    #1758 - Thanks @echo1consulting!
* Added:    'hidden' to menu_type argument to allow for hidden menus until available.

## 3.3.8.6
* Fixed:    #1749 - Remove font-wight and font-style from css output when not in use.

## 3.3.8.5
* Modified: Added the "redux/options/{$this->args['opt_name']}/compiler/advanced" hook for more advanced compiling.
* Added:    Suggestions as per #1709. Thanks @echo1consulting.

## 3.3.8.4
* Modified: Removed a cURL instance from the core and fixed the developer ad resizing.
* Fixed: PHP 5.2 issues. *sigh*

## 3.3.8.3
* Added:   #1593 - Great pull request by @JonasDoebertin. Now you can enqueue dynamic output to the login screen or admin backend.

## 3.3.8.2
* Fixed:   Customizer wasn't saving at all! That's been like 4 months. No one's reported it. Hmm.
* Fixed: #1702 - Customizer only fields were being erased on panel save.

## 3.3.8.1
* Fixed:   Various Theme-Check errors with languages.
* Added: Theme-Check class to help devs know what is what.
* Fixed: The way we include files from include_once to require_once everywhere.
* Modified: Language files to reflect new strings.
* Modified: Formatted a bunch of old class files.
* Added: Notice on the updates for non-devs to use the new dev_mode disabler plugin and notify their developer.  ;)

## 3.3.8
* Modified:   Updated potomo, thanks @shivapoudel.
* Added: Grunt checktextdomain and made improvements. Thanks @shivapoudel.

## 3.3.7.11   
* Modified:   #1685 - Specifying no default argument for image_select caused errors on reset.

## 3.3.7.10   
* Fixed:      #1667 - Slides Upload button causing JS error.

## 3.3.7.9    
* Fixed: #1670 - Fix for Theme Check -> `add_setting() method needs to have a sanitization callback function passed.`

## 3.3.7.8    
* Fixed:  #1661 - Fix for undefined index in some versions of PHP. Thanks @gianbalex!
* Modified: #1658 - Improvements from @shivapoudel, including:
  * Removed makepot and used grunt-wp-i18n instead.
  * Added a jshintrc file
  * Added a `grunt addtextdomain` to correct any bad textdomains in the core.
  * Updated .gitignore for better readability
  * Updates to a few other files including package.json.
  * Updated language files.
  * Update codestyles/.editorconfig to reflect the project's standards.

## 3.3.7.7    
* Modified:  #1653 - Better admin bar with external links: Admin bar menu priority, icon, and external links. Thanks @shivapoudel!

## 3.3.7.6
* Added:      #1651 - `library_filter` argument.  Allows specification of what files to display in the media library.
* Modified:   #1651 - `mode` argument accepts either file type or mime type (but not both).

## 3.3.7.5
* Fixed:      #1650 - Toogle error with responsive CSS.

## 3.3.7.4
* Fixed:      #1643 - Slight border issue (2px) on sticky footer.

## 3.3.7.3
* Fixed:      #1642 - Added `font_family_clear` arg, enabling the clear option for font-family.
* Fixed:      #1638 - Spacing field not outputting when units values attached to default values.
* Modified    #1644 - `import_icon` argument now accepts wordpress dashicons

## 3.3.7.2 
* Fixed:      #1634 - Double border for sections field. Thanks @AlexandruDoda
* Modified:   Changelog location to now Changelog.md.

## 3.3.7.1 
* Fixed:      #1632 - Sortable with no defaults set revert to false (instead of options values).
* Fixed:      Labels for sortable in text mode updated to match framework.

## 3.3.7 
* Added:      #1586 - Class-level declaration for callbacks and validation. Thanks @echo1consulting.
* Modified:   Typography field now fully dynamic.
* Modified:   No longer require a google_api_key for the typography module.  :)
* Fixed:      FTP credentials screen giving a "undefined submit_button function". Resolved.
* Modified:   #1628 - Spacing and dimensions now only output 0 if the entry is a 0, not empty.
              Thanks @Webcreations907
* Modified:   CSS for menu items when active (no hover).
* Added:      Visual feedback to left menu if active.

## 3.3.6.9 
* Fixed:      #1623 - Registered older noUISlider JS under a new name to avoid conflicts.
* Modified:   #1622 - Removed googlefonts.js dependency.

## 3.3.6.8 
* Fixed:      #1600 - ACE Editor bombing in PHP 5.2 environments.

## 3.3.6.7 
* Fixed:      #1591 - Erroneous outputting of font-weight and font-style when no font-family selected.
* Updated:    #1569 - Improved submenu highlighting.
* Added:      #1487 - Added `get_default_value` function into the framework.php

## 3.3.6.6 
* Fixed:      Framework URI errors when using child themes. Some restructuring.

## 3.3.6.5 
* Fixed:      Framework URI errors when embedded in theme with Windows.

## 3.3.6.4 
* Added:      image_size as an option for the data argument. Thanks @Gyroscopic!

## 3.3.6.3 
* Modified:   How Redux paths are run. Should cover all use cases now. Child themes can also embed 
              Redux properly now. Thanks @cfoellmann for the suggestions. Fix for issue #1566.

## 3.3.6.2 
* Modified:   How we declare the uploads directory and URL. Using core WP functions now.

## 3.3.6.1 
* Modified:   Now if a section is empty, but has subsections, that section will be "skipped" when 
              clicked and the first subsection will then be shown.

## 3.3.6 
* Modified:   Language files.
* Fixed:      #1560 - IE8 RGBA fallack

## 3.3.5.12 
* Fixed:      #1543 - Hint icon not changing when set in args.

## 3.3.5.11 
* Fixed:      #1537 - Media field not accepting images with mode set to false.

## 3.3.5.10 
* Fixed:      #1529 - ACE Editor conflict with Visual Composer.
* Added:      #1530 - Added argument to specify admin bar icon, `admin_bar_icon`.  Thanks Ninos!
* Fixed:      #1532 - Media field not accepting any mime type when `'mode' => false`.

## 3.3.5.9 
* Fixed:      #1520 - Checkbox field not displaying Wordpress data when using data argument.

## 3.3.5.8 
* Fixed:      #1516 - Invalid index and foreach when using compiler and async_typography.

## 3.3.5.7 
* Fixed:      #1509 - Sorter adding unnecessary bits on some items.
* Fixed:      #1514 - Customizer and multisite not getting on properly.
* Fixed:      #1512 - Slides 'Upload' button not showing or saving selected image.

## 3.3.5.6 
* Fixed:      Checkboxes with required were working in reverse.

## 3.3.5.5 
* Fixed:      ASync Typography now works! No more flashing fonts.

## 3.3.5.4 
* Fixed:      #1489 - Color picker UI lining up improperly.
* Fixed:      #1497 - dev_mode spinner issue.

## 3.3.5.3 
* Fixed:      Spelling error in tracking dialog.
* Modified:   Updated ace_editor.
* Modified:   Many MANY fields for the group field.
* Fixed:      Some CSS bugs.

## 3.3.5.2 
* Fixed:      #1481 - Custom fonts loading in google font CSS.
* Fixed:      #1485 - Customizer 'invalid argument' error.  Thanks @rnlmedia.

## 3.3.5.1 
* Fixed:      #1472 - font style not displaying saved valie with no font-family argument set.
* Fixed:      #1471 - raw field and required not playing nice together.

## 3.3.5 
* Added:      An annoying notice at the top so our devs don't ship with dev_mode on.  ;)

## 3.3.4.9 
* Fixed:      #1462 - Google fonts not loading in font drop down.

## 3.3.4.8 
* Fixed:      More WP FileSystem tanking. Did PHP fallback before FTP. Works 99.9% of the time without credentials.

## 3.3.4.7 
* Fixed:      Incorrect folder CHMOD in filesystem class.

## 3.3.4.6 
* Fixed:      #1454 - Chmod permissions for redux folder.

## 3.3.4.5 
* Fixed:      #1451 - Googlefonts not loading due to failing copy function.

## 3.3.4.4 
* Fixed:      #1450 - Saves witch values with no `on` or `off` args make the core unhappy.

## 3.3.4.3 
* Fixed:      #1444, again, due to filesystem growing pains.
* Fixed:      #1449 - Restoring `options` argument over a lousy attempt to fix placeholder.

## 3.3.4.2 
* Fixed:      More file permission issues.

## 3.3.4.1 
* Fixed:      Font debug was left from last commit. Sorry all.

## 3.3.3.8 
* Fixed:      Issues with file writing. Basically many users don't install WordPress with all the permissions correct. 
              So... Had to move it back to /uploads/. Sorry Otto, that's just how it is.
* Fixed:      #1444 - output of typography all_styles when font_style UI was hidden.              

## 3.3.3.7 
* Fixed:      #1440 - flaw in new cleanFilePath logic.

## 3.3.3.6 
* Fixed:      #1432 - Theme check failing when double-slashes existed in get_template_directory() return.
* Removed:    curlRead from helper class.

## 3.3.3.5 
* Fixed:      #1426 - menu_name not appearing on front end admin bar.
* Added:      #1427 - button_set added to customizer UI.  Thanks @wpexplorer.

## 3.3.3.4 
* Fixed:      #1429 - ACE Editor erroring with no default value set.
* Fixed:      wp_filesystem now initialized with credentials in an effort to combat the tmp file issue.

## 3.3.3.3 
* Modified:   Code purification.

## 3.3.3.2 
* Modified:   How section tabs work. Isolated within the redux-container class.

## 3.3.3 
* Modified:   #1412 - Redesigned text label, placeholder fix.

## 3.3.2.10 
* Fixed:      #1408 & #1357 - Typography subsets losing value after multiple saves 
              on other panels.

## 3.3.2.9 
* Fixed:      #1403 - unit value no longer prints after empty typography values
* Modified:   Typography: Backup font no longer appends to `font-family` variable.  
              Please use the `backup-font` variable to specify backup fonts.  This 
              does not apply to output/compiler strings.

## 3.3.2.8 
* Fixed:      #1403 - Backup font not appearing in font-family variable.

## 3.3.2.7 
* Modified:   Customizer now supports section and field `permissions` argument.
* Fixed:      #1399 - Customizer respects `page_permissions` argument.

## 3.3.2.6 
* Fixed:      #1400 - output/compiler string incomplete using multiple selectors.

## 3.3.2.5 
* Fixed:      #1396 - Custom fonts cutting off multiple families in selector, after save.
* Fixed:      Typography attempting to queue up non google fonts on backend.
* Added:      #1395 - Display of child theme status in sysinfo, thanks @SiR-DanieL.

## 3.3.2.4 
* Fixed:      #1387 - Page jump when clicking "Options Object".  Thanks @rrikesh.
* Added:      #1392 - Filters to change the following localized strings:
              redux/{opt_name}/localize/reset
              redux/{opt_name}/localize/reset_all
              redux/{opt_name}/localize/save_pending
              redux/{opt_name}/localize/preset

## 3.3.2.3 
* Fixed:      #1376 - checkbox.min.js missing.

## 3.3.2.2 
* Fixed:      Static variable changes for instances and basic comment cleanup

## 3.3.2.1 
* Fixed:      #1361 - Raw field not hiding with required.
* Fixed:      Datepicker not formatting properly.  Still needs some work.

## 3.3.1.9 
* Fixed:      #1357 - Preview not rendering font on page load.

## 3.3.1.8 
* Fixed:      #1356 - Color fields and transparency not syncing due to new JS.

## 3.3.1.7 
* Fixed:      #1354 - Add class check for W3_ObjectCache.

## 3.3.1.6 
* Fixed:      #1341 - JS not initializing properly in import_export.

## 3.3.1.5 
* Fixed:      #1339 - Typography would lose Font Weight and Style. value was 
              named val in the HTML, so it would be destroyed on the next save 
              if not initialized.

## 3.3.1.4 
* Fixed:      #1226 - W3 Total Cache was affecting validation and compiler hooks.
* Fixed:      Menu errors weren't showing properly for non-subsectioned items.

## 3.3.1.3 
* Fixed:      #1341 - Import/Export buttons not functioning. Also fixed sortable somehow.

## 3.3.1.2 
* Fixed:      Slides not initializing with the last fix.

## 3.3.1.1 
* Fixed:      Slides field was not properly initialized for the media elements. Fixed.

## 3.3.0.6 
* Fixed:      #1337 - `redux` JS dependency loading issue.  Many thanks @tpaksu

## 3.3.0.5 
* Modified:   Drastically changed the way JavaScript is used in the panel. Forced as-needed
              initialization of fields. Thus reducing dramatically the overall load time of
              the panel. The effects have been seen up to 300% speed improvement. The only
              time a field will be initialized is if it's visible, thus reducing the processing
              needed in DOM overall.

## 3.3.0.4 
* Fixed:      #1336 - fixed default font in preview.

## 3.3.0.3 
* Fixed:      #1334 - Typography not un-saving italics.

## 3.3.0.2 
* Added:      #1332 - New validation: numeric_not_empty.

## 3.3.0.1 
* Fixed:      #1330 - Required not working on all fields.

## 3.3.0 
* Added:      #1329 - `'preview' = array('always_display' => true)` argument to typography, to determine if preview field show always be shown.

## 3.2.9.38 
* Fixed:      #1322 - Sections not folding with required argument.
* Modified:   Portions of core javascript rewritten into object code.

## 3.2.9.37 
* Fixed:      #1270 - Editor field compiler hook not firing in visual mode.

## 3.2.9.36 
* Added:      `hide_reset` argument, to hide the Reset All and Reset Section buttons.        

## 3.2.9.35 
* Fixed:      select2 dependency in select_image, and other fields.

## 3.2.9.34 
* Fixed:      Filter out `@eaDir` directories in extensions folder.
* Added:      `content_title` argument to slides field.  Thanks @psaikali!

## 3.2.9.33 
* Fixed:      Fixed the image_select presets to work again. Also now will function even if import/export is disabled.

## 3.2.9.32 
* Fixed:      Minor tweaks for metabox update.

## 3.2.9.31 
* Fixed:      #1297 - Missing space in image_select class.
* Fixed:      Slider field tweaked for metaboxes.

## 3.2.9.30
* Fixed:      #1291 - Change of font-family would not trigger preview, or show in open preview.  

## 3.2.9.29
* Fixed:      #1289 - Typography not retaining size/height/spacing/word/letter spacing settings.

## 3.2.9.28 
* Fixed:      #1288 - Background color-picker dependency missing.  Thanks @farhanwazir.

## 3.2.9.27 
* Fixed:      Search extension failed do to dependency issue from the core.

## 3.2.9.26 
* Fixed:      #1281 - color field output/compiler outputting incorrect selector when only one array present.

## 3.2.9.25 
* Fixed:      Update check only appears once if multiple instances of Redux are loaded in the same wordpress instance.

## 3.2.9.24 
* Fixed:      Changing font-family in typography didn't trigger 'save changes' notification.
* Fixed:      More typography: Back up font appearing in font-family when opening selector.
* Fixed:      Typography: undefined message when NOT using google fonts.  Thanks @farhanwazir

## 3.2.9.23 
* Added:      `customizer_only` argument for fields & sections, contributed by @andreilupu.

## 3.2.9.22 
* Fixed:      Typography font backup not in sync with font-family.
* Fixed:      Typography not saving font-family after switching back and forth between standard
              and google fonts.
* Fixed:      Background field selects not properly aligned.

## 3.2.9.21 
* Added:      select2 args for spacing field.
* Modified:   All field javascript rewritten using jQuery objects (versus standard function).
              Prepping for another crack at group field.

## 3.2.9.20 
* Added:      select2 args for the following fields: typography, background, border, dimensions and slider.
* Fixed:      Removed select field dependency from background field.

## 3.2.9.19 
* Fixed:      #1264 - Color-picker/transparent checkbox functionality.
* Fixed:      Typography fine-tuning.

## 3.2.9.18 
* Modified:   Typography field rewritten to fill out font-family field dynamically, versus on page load.
* Fixed:      All typography select fields render as select2.

## 3.2.9.17 
* Fixed:      Switching between transparency on and off now restores the last chosen color in 
              all color fields. 

## 3.2.9.16 
* Fixed:      Redux uploads dir should NOT be ~/wp-content/uploads, but just wp-content. 
              As per Otto.
* Fixed:      Navigation no longer has that annoying outline around the links. Yuk.

## 3.2.9.15 
* Fixed:      #1218 - Select2 multi select not accepting any keyboard input.

## 3.2.9.14 
* Fixed:      #1228 - CSS fixes

## 3.2.9.13 
* Fixed:      #1255 - button_set multi field not saving when all buttons not selected.

## 3.2.9.12 
* Fixed:      #1254 - Border field with 0px not outputting properly.
* Fixed:      #1250 - Typography preview font-size not set in preview.
* Fixed:      #1247 - Spacing field not outputting properly in `absolute` mode.
* Modified:   Typography previewing hidden until font inputs are changed.

## 3.2.9.11 
* Fixed:      Vendor js not loading properly when dev_mode = true
* Fixed:      Border field not outputting properly.

## 3.2.9.10 
* Modified:   Centralized import/export code in anticipation of new builder features.
* Fixed:      Removed rogue echo statement.

## 3.2.9.9 
* Modified:   select2 loads only when a field requires it.

## 3.2.9.8 
* Modified:   More code to load JS on demand for fields require it.

## 3.2.9.7 
* Modified:   Field specific JS only loads with active field.
* Fixed:      Hints stopped working due to classname change.

## 3.2.9.6 
* Fixed:      Permissions argument on section array not filtering out raw field.

## 3.2.9.5 
* Fixed:      Too many CSS tweaks to list, due to last build.
* Fixed:      Sortable and Sorter fields now sort without page scroll when page size is 
              under 782px.
* Fixed:      Hint icon defaults to left position when screen size is under 782px.
* Fixed:      `permissions` argument for fields and sections erasing saved field data.  See #1231

## 3.2.9.4 
* Modified:   Woohoo! Nearly fully responsive. Yanked out all SMOF and NHP field customizations.
              Lots of little fixes on all browser screens. This will also greatly benefit Metaboxes
              and other areas of Redux.
* Fixed:      In dev_mode panel CSS was being loaded 2x.

## 3.2.9.3 
* Fixed:      Typography color picker bleeding under other elements.  #1225
* Fixed:      Hint icon_color index error from builder.  #1222

## 3.2.9.2 
* Fixed:      Tracking. It was... odd. Also started our support hooks, UI to come.
* Fixed:      Now import/export supports multiple instances. I can't believe this has been this way for so long.

## 3.2.9.1 
* Fixed:      Spacing field not outputting proper CSS when `mode` was set to absolute, and `all` was set to true.
* Fixed:      CSS fix for typography.  Color picker would interfere with save/reset bar.

## 3.2.8.21 
* Added:      Network admin support! Set argument 'database' to network and data will be saved site-wide.
              Also two new arguments: network_admin & network_sites for where to show the panel.

## 3.2.8.20 
* Fixed:      Redux now ignores any directories that begin with `.` in the extension folder.  See #1213.

## 3.2.8.19 
* Fixed:      Redux not saving when validating uploads.
* Modified:   Dimension field default now accepts either `units` or `unit`.

## 3.2.8.18 
* Fixed:      Border field output/compiler formatting.  Removed 'inherit' in place of default values.  See #1208.
* Fixed:      Trim() warning in framework.php when saving.  See #1209, #1201.

## 3.2.8.17 
* Fixed:      Typography not outputting all styles when `all_styles` set to true.

## 3.2.8.16 
* Added:      `output` argument for `color` and `color_rgba` fields accepts key/pairs for different modes.  Example:
```
              'output' => array('color' => '.site-title, .site-header', 'background-color' => '.site-background')
```

## 3.2.8.15 
* Added:      Customizer hook that can be used to simulate the customizer for live preview in the customizer.
              `redux/customizer/live_preview`

## 3.2.8.14 
* Fixed:      'Cannot send header' issues with typography.
* Modified:   Google CSS moved into HEAD via WP enqueue.

## 3.2.8.13 
* Added:      `class` argument to the Redux Arguments, section array, and metabox array. If set, a class will be
              appended to whichever level is used. This allows further customization for our users.

## 3.2.8.12 
* Fixed:      Small fix for validation if subsection parent is free of errors, remove the red highlight when
              not expanded.
* Fixed:      Small CSS classes for flashing fonts where web-font-loader.
* Fixed:      ASync Flash on fonts. FINALLY. What a pain.
* Modified:   Now do a trim on all fields before validating. No need to alert because of a space...

## 3.2.8.11 
* Modified:   Typography field CSS completely rewritten. All thanks to @eplanetdesign!
* Modified:   Validation now works in metaboxes as well as updates numbers as changes occur. Validation for
              subsections is SO hot now.
* Modified:   Various CSS fixes and improvements.
* Fixed:      3+ JavaScript errors found in the background field. Now works flawlessly.
* Added:      disable_save_warn flags to the arguments to disable the "you should save" slidedown.

## 3.2.8.10 
* Fixed:      PHP warnings in background field.  #1173.  Thanks, @abossola.
* Fixed:      CSS validation not respecting child selector symbol. #1162

## 3.2.8.9 
* Modified:   Turned of mod_rewrite check.

## 3.2.8.8 
* Modified:   How errors are displayed, no longer dependent on the ID, now proper classes.
* Fixed:      Extra check for typography bug.
* Fixed:      Error css alignment issue with subsections.
* Modified:   Error notice stays until all errors are gone. Also updates it's number as errors fixed!

## 3.2.8.7 
* Modified:   Moved google font files to proprietary folder in upload to help with permission issues.

## 3.2.8.6 
* Fixed:      javascript error in typography field.

## 3.2.8.5 
* Fixed:      Added a title to the google fonts stylesheet to fix validation errors.

## 3.2.8.4 
* Fixed:      One more slides field error check, and an extra JS goodie for an extension.

## 3.2.8.3 
* Fixed:      Leftover debug code messing up slides field.

## 3.2.8.2 
* Fixed:      More reliable saved action hook.
* Added:      Actions hooks for errors and warnings.

## 3.2.8.1 
* Fixed:      Removed erroneous debug output in link_color field.

## 3.2.7.3 
* Added:      is_empty / empty / !isset    AND    not_empty / !empty / isset as required operations

## 3.2.7.2 
* Fixed:      Reset defaults error.
* Added:      `show` argument to turn on and off input boxes in slider.

## 3.2.7.1 
* Fixed:      Required now works with muti-check fields and button set when set to multi.

## 3.2.7 
* Fixed:      Import works again. A single line was missed...

## 3.2.6.2 
* Fixed:      link_color field not outputting CSS properly via compiler or output.  Thanks @vertigo7x 
* Fixed:      Sorter field CSS.  Buttons were all smushed together.

## 3.2.6.1 
* Fixed:      'undefined' error in typography.js.  Thanks @ksere.

## 3.2.6 
* Fixed:      Another stray undefined index. Oy.

## 3.2.5.1 
* Added:      `open_expanded` argument to start the panel completely expanded initially.

## 3.2.5 
* Fixed:      Various bad mistakes. Oy.

## 3.2.4 
* Fixed:      Slight typography speed improvement. Less HTML hopefully faster page loads.
* Fixed:      Unload error on first load if the typography defaults are not set.

## 3.2.3.5 
* Modified:   Moved update check functions to class file and out of the core.
* Fixed:      Errors pertaining to mod_rewrite check.

## 3.2.3.4 
* Fixed:      All those headers already set errors.

## 3.2.3.3 
* Added:      $changed_values variable to save hooks denoting the old values on a save.
* Added:      Pointers to Extensions on load.
* Modified:   CSS Output for the background field.

## 3.2.3.2 
* Fixed:      Validation error messages not appearing on save.
* Modified:   Speed boost on validation types.
* Added:      Apache mod_rewrite check.  This should solve many issues we've been seeing regarding mod_rewrite noe being enabled.

## 3.2.3.1 
* Fixed:      Sortable field not saving properly.
* Fixed:      Erroneous data in admin.less
* Updated:    sample-config.php.  Sortable checkbox field example now uses true/false instead of text
              meant for textbox example.

## 3.2.3 
* Fixed:      Responsive issues with spacing and dimension fields.

## 3.2.2.16 
* Fixed:      Style conflicts with WP 3.9. Added register filter to fields via id.

## 3.2.2.15 
* Fixed:      Metaboxes issues.

## 3.2.2.14 
* Modified:   Some admin panel stylings. Now perfect with mobile hover. Also fixed an issue
              with the slidedown width for sections. No more 2 empty pixels.

## 3.2.2.13 
* Added:      Tick mark if section has sub sections. Hidden when subsections expanded.

## 3.2.2.12 
* Fixed:      Compiler hook in the customizer now passes the CSS.

## 3.2.2.11 
* Fixed:      Compiler hook now properly fires in the customizer.

## 3.2.2.10 
* Fixed:      Validation error with headers already being set.

## 3.2.2.9 
* Fixed:      Added mode for width/height to override dimensions css output.

## 3.2.2.8 
* Fixed:      Restoring lost formatting from multiple merges.

## 3.2.2.7 
* Fixed:      New sorter default values get set properly now.  ;)

## 3.2.2.6 
* Added:      `data` and `args` can now be set to sorter! Just make sure to have it be a key based on what
              you want it to display as. IE: `array('Main'=>'sidebars')`

## 3.2.2.5 
* Added:      Prevent Redux from firing on AJAX heartbeat, but added hook for it 'redux/ajax/heartbeat'.
* Fixed:      Removed erroneous 's' character from HTML.

## 3.2.2.4 
* Added:        Check to make sure a field isn't empty after the filter. If it is empty, skip over it.

## 3.2.2.3 
* Added:        Subsections now show icon if they have it. Show text only (without indent) if they do not.

## 3.2.2.2 
* Added:        Set a section or field argument of `'panel' => false` to skip over that field or panel and
              hide it. It will still be registered with defaults saved, but not display. This can be useful
              for things like the customizer.

## 3.2.2.1 
* Added:        SUBSECTIONS! Just add `'subsection' => true` to any section that isn't a divide/callback and
              isn't the first section in your panel.  ;)

## 3.2.1.2 
* Fixed:      Info field didn't intend within section.

## 3.2.1.1 
* Fixed:      Compiler hook wasn't running.


## 3.1.9.44 
* Fixed:      Small bug in image_select javascript.

## 3.1.9.43 
* Added:      Import hook, just because we can.  :)

## 3.1.9.42 
* Fixed:      Customizer now TRULY outputting CSS if output_tag is set to false.

## 3.1.9.41 
* Fixed:      Reset section, etc. Discovered an odd WordPress thing.

## 3.1.9.40 
* Fixed:      Image_select size override.
* Fixed:      Customizer save not firing the compiler hook.
* Fixed:      Customizer not outputting CSS if output_tag is set to false.
* Fixed:      Small empty variable check. Undefined index in the defaults generating function.

## 3.1.9.39 
* Fixed:      WP 3.9 update made editor field button look ugly.
* Fixed:      Save hook not firing when save_default set to false.

## 3.1.9.38 
* Fixed:      Reset section anomalies.  Maybe.

## 3.1.9.37 
* Fixed:      Array of values in required not recognized.

## 3.1.9.36 
* Fixed:      Updated hint defaults to prevent index warning.

## 3.1.9.35 
* Fixed:      Removed leftover debug code.

## 3.1.9.34 
* Added:      New readonly argument for text field.

## 3.1.9.33 
* Fixed:      Reset/Reset section actions hooks now fire properly.

## 3.1.9.32 
* Fixed:      When developer uses section field but does not specify an indent argument.

## 3.1.9.31 
* Fixed:      Dynamic URL for slides
* Fixed:      Accidently removed reset action on section reset. Restored.

## 3.1.9.30 
* Fixed:      Section defaults bug for certain field types.

## 3.1.9.29 
* Fixed:      Dynamic URL if site URL changed now updates media properly if attachement exists.

## 3.1.9.28 
* Fixed:      Customizer now correctly does live preview.

## 3.1.9.27 
* Fixed:      Special enqueue case fix.

## 3.1.9.26 
* Added:      A few more hooks for defaults and options.
* Fixed:      Small undefined index error.
* Added:      Section key generation via title.
* Modified:   File intending.

## 3.1.9.25 
* Fixed:      Custom menus not displaying options panel.

## 3.1.9.24 
* Fixed:      Single checkbox option not retaining checked value.
* Fixed:      Border field returning bad CSS in CSS compiler.

## 3.1.9.23 
* Fixed:      Import/Export fix.  Thanks, CGlingener!

## 3.1.9.22 
* Added:      Save warning now is sticky to the top and responsive.
* Fixed:      Mobile fixes for Redux. Looks great on small screens how.
* Fixed:      Slight CSS fixes.
* Fixed:      Compiler fixes and added notices.
* Added:      Import/Export more reasonable text.

## 3.1.9.21 
* Added:      `force_output` are on the field level to bypass the required check that removes the output
              if the field is hidden. Thanks @rffaguiar.

## 3.1.9.20 
* Fixed:      Rare case (mediatemple grid server) when file_get_contents won't work outside of the
              uploads dir. Used curl to grab the font HTML.  ;)

## 3.1.9.19 
* Fixed:      Undefined index for admin bar.

## 3.1.9.18 
* Fixed:      SMALL issue with WordPress 3.9. Now it works.  ;)

## 3.1.9.17 
* Fixed:      Info and divide field now work with required.

## 3.1.9.16 
* Added:      Fallback. Now if the media, slides, or background URL doesn't match the site URL, but the
              attachment ID is present, the data is updated.

## 3.1.9.15 
* Fixed:      Last tab not properly set.  Slow rendering.

## 3.1.9.14 
* Modified:   Replaced transients with cookies.

## 3.1.9.13 
* Fixed:      Undefined variable issues for new required methods.

## 3.1.9.12 
* Fixed:      Default_show display error with a non-array being steralized.
* Added:      Multiple required parent value checking! Booya!
* Fixed:      Sections now fold with required.

## 3.1.9.11 
* Fixed:      select2 not rendering properly when dev_mode = false, because of ace_editor fix.
* Fixed:      Removed mistakenly compiled test code from redux.js.

## 3.1.9.10 
* Fixed:      ace_editor not rendering properly in certain instances.
* Modified:   Small change to import_export field in checking for existing instance of itself.

## 3.1.9.9 
* Fixed:      import_export not rendering when the menutype argument was set to menu

## 3.1.9.8 
* Fixed:      Ace_editor not enqueued unless used. MEMORY HOG.

## 3.1.9.7 
* Fixed:      Color_Gradient transparency to was being auto-selected if from way transparent.
* Fixed:        Enqueue select with slider for local dev.

## 3.1.9.6 
* Modified:   removed add_submenu_page when creating a submenu for us in the WP admin area.  WP
              approved API is used in it's place to being Redux up to wp.org theme check standards.

## 3.1.9.5 
* Fixed:      Massive speed issue with button_set. Resolved.
* Fixed:      Issue where default values throws an error if ID is not set.

## 3.1.9.4 
* Fixed:      Continuing effort to ensure proper loading of config from child themes.

## 3.1.9.3 
* Fixed:      Import/Export array search bug if section['fields'] is not defined.

## 3.1.9.2 
* Fixed:      Inconsistencies in import/export across different versions of PHP.

## 3.1.9.1 
* Fixed:      Redux checks for child or parent theme exclusively before loading.

## 3.1.9 
* Updated:    RGBA Field stability.  Thank you, SilverKenn.

## 3.1.8.23 
* Modified:   Separated Import/Export from the core.  It can now be used as a field.

## 3.1.8.22 
* Fixed:      Typography custom preview text/size not outputting.
* Fixed:      No font selected in typography would default to 'inherit'.
* Fixed:      Hint feature kicking back a notice if no title was specified.

## 3.1.8.21 
* Fixed:      Sortable field, when used a checkboxes, were all checked by default, even when set not to be.
* Fixed:      button_set field not setting properly in multi mode.

## 3.1.8.20 
* Fixed:      Javascript console object not printing options object.
* Fixed:      Load errors from child themes no longer occur.

## 3.1.8.19 
* Modified:   Typography word and letter spacing now accept negative values.
* Modified:   Typography preview shows spaces between upper and lower case groupings.
* Fixed:      Compiler output for slider field.

## 3.1.8.18 
* Fixed:      update_check produced a fatal error on a local install with no internet connection.
* Modified:   Google font CSS moved to header so pages will pass HTML5 validation.

## 3.1.8.17 
* Fixed:      Compiler hook failing on slider.

## 3.1.8.16 
* Fixed:      Error on update_check when the response code was something other than 200.
* Modified:   Removed Google font CSS line from header (because it's in the footer via wp_enqueue_style.

## 3.1.8.15 
* Added:      Admin notice for new builds of Redux on Github as they become available.  This feature is
              available on in dev_mode, and may be turned off by setting the `update_notice` argument to
              false.  See the Arguments page of the wiki for more details.
* Added:      text-transform option for the typography field.
* Fixed:      image_select images not resizing properly in FF and IE.
* Fixed:      Layout for the typography field, so everything isn't smushed together.  The new layout is
              as follows:
                  [family-font] [backup-font]
                  [style] [script] [align] [transform]
                  [size] [height] [word space] [letter space]
                  [color]

## 3.1.8.14 
* Added:      Newsletter sign-up popup at first load of the Redux options panel.

## 3.1.8.12 
* Added:      Added PHP 5.2 support for import/export.

## 3.1.8.11 
* Added:      Action hooks for options reset and options reset section.
* Added:      Theme responsive for date picker.

## 3.1.8.10 
* Added:      New slider.  Better looking UI, double handles and support for floating
              point values.  See the wiki for more info.

## 3.1.8.9 
* Fixed:      link_color field showing notice on default, if user enters no defaults.
* Fixed:      Fixed tab notice in framework.php if no tab parameter is set in URL.

## 3.1.8.8 
* Added:      Typography improvements.

## 3.1.8.7 
* Added:      Hints!  More info:  https://github.com/ReduxFramework/ReduxFramework/wiki/Using-Hints-in-Fields

## 3.1.8.6 
* Added:      Complete Wordpress admin color styles. Blessed LESS/SCSS mixins.  ;)

## 3.1.8.5 
* Added:      Font family not required for the typography module any longer.

## 3.1.8.4 
* Added:      Support for using the divide field in folding.
* Added:      Error trapping in typography.js for those still attempting to use
              typography with no font-family.

## 3.1.8.3 
* Added:      Full asynchronous font loading.
* 
## 3.1.8.2 
* Added:      email_not_empty validation field.
* Reverted:   email validation field only checks for valid email.  not_empty check moved
              to new validation field.

## 3.1.8.1 
* Fixed:      Hide demo hook wasn't hiding demo links.

## 3.1.8 
* Fixed:    Improper enqueue in tracking class.
* Fixed:    Few classes missed for various fields.
* Fixed:    Spacing field kicking back notices and warnings when 'output' wasn't set.
* Modified: Added file_exists check to all include lines in framework.php
* Fixed:    Background field now works with dynamic preview as it should.
* Fixed:    Extension fields now enqueueing properly.
* Added:    Text-align to typography field.
* Fixed:    Servers returning forwards slashes in TEMPLATEPATH, while Redux is installed
            embedded would not show options menu.
* Fixed:    On and Off for switch field not displaying language translation.
* Fixed:    email validation allowing a blank field.
* Fixed:    Now allow for empty values as valid keys.
* Added:    Dismiss option to admin notices (internal function)

## 3.1.6 
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
* Modified: Removed raw_align field and added align option to raw field.
            See wiki for more info.
* Modified: media field 'read-only' to 'readonly' to vonform to HTML standards.
* Removed:  EDD extension. It never belonged in Core and will be re-released as a
            downloadable extension shortly
* Removed:  Group field, temporarily.
* Removed:  wp_get_current_user check.
 
## 3.1.5 
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

## 3.1.4 
* Fixed error in redux-framework.php.
* Added select_image field.

## 3.1.3 
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

## 3.1.2 
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
* Fixed Slider and Spinner Input Field - Values now move to the closest valid
  value in regards to the step, automatically.
* Fixed Ace Editor
* FEATURE - All CSS/JS files are compiled into a single file now! Speed
  improvements for the backend.
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

## 3.1.0 
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
* Fix Issue 247 - Media thumbnails were not showing. Also fixed media to keep the largest file, but display the small
                  version in the panel as a thumb. Thanks @kwayyinfotech.
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

## 3.0.9 
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

## 3.0.8 
* Version push to ensure all bugs fixes were deployed to users. Various.

## 3.0.7 
* Feature - Completely redone spacing field. Choose to apply to sides or all at once with CSS output!
* Feature - Completely redone border field. Choose to apply to sides or all at once with CSS output!
* Feature - Added opt-in anonymous tracking, allowing us to further analyze usage.
* Feature - Enable weekly updates of the Google Webfonts cache is desired. Also remove the Google Webfont files from
            shipping with Redux. Will re-download at first panel run to ensure users always have the most recent copy.
* Language translation of german updated alone with ReduxFramework pot file.
* Fix Issue 146 - Spacing field not storing data.
* Fix - Firefox field description rendering bug.
* Fix - Small issue where themes without tags were getting errors from the sample data.

## 3.0.6 
* Hide customizer fields by default while still under development.
* Fix Issue 123 - Language translations to actually function properly embedded as well as in the plugin.
* Fix Issue 151 - Media field uses thumbnail not full image for preview. Also now storing the thumbnail URL. Uses
                  the smallest available size as the thumb regardless of the name.
* Fix Issue 147 - Option to pass params to select2. Contributed by @andreilupu. Thanks!
* Added trim function to ace editor value to prevent whitespace before and after value keep being added
* htmlspecialchars() value in pre editor for ace. to prevent html tags being hidden in editor and rendered in dom
* Feature: Added optional 'add_text' argument for multi_text field so users can define button text.
* Added consistent remove button on multi text, and used sanitize function for section id
* Feature: Added roles as data for field data
* Feature: Adding data layout options for multi checkbox and radio, we now have quarter, third, half, and full
           column layouts for these fields.
* Feature: Eliminate REDUX_DIR and REDUX_URL constants and instead created static ReduxFramework::$\_url and
           ReduxFramework::$\_dir for cleaner code.
* Feature: Code at bottom of sample-config.php to hide plugin activation text about a demo plugin as well as
           code to demo how to hide the plugin demo_mode link.
* Started work on class definitions of each field and class. Preparing for the panel builder we are planning to make.

## 3.0.5 
* Fixed how Redux is initialised so it works in any and all files without hooking into the init function.
* Issue #151: Added thumbnails to media and displayed those instead of full image.
* Issue #144: Slides had error if last slide was deleted.
* Color field was outputting hex in the wrong location.
* Added ACE Editor field, allowing for better inline editing.

## 3.0.4 
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

## 3.0.3 
* Fixed Issue #129: Spacing field giving an undefined.
* Fixed Issue #131: Google Fonts stylesheet appending to body and also to the top of the header. Now properly placed
                    both at the end of the head tag as to overload any theme stylesheets.
* Fixed issue #132 (See #134, thanks @andreilupu): Could not have multiple WordPress Editors (wp_editor) as the
                    same ID was shared. Also fixed various styles to match WordPress for this field.
* Fixed Issue #133: Issue when custom admin stylesheet was used, a JS error resulted.

## 3.0.2 
* Improvements to slides, various field fixes and improvements. Also fixed a few user submitted issues.

## 3.0.1 
* Backing out a bit of submitted code that caused the input field to not properly break.

## 3.0.0 
* Initial WordPress.org plugin release.

## 3.0
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

## Version 3.0.0 Beta (September 12, 2013)

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

## Version 2.0.1 Final (September 1, 2013)

* Added option to override ```icon_type``` per icon
* Minor bug/versioning fixes
* Added Font Awesome intro
* Added ```raw_html``` option
* Added ```text_sortable``` option
* Switched from Aristo to Bootstrap jQuery UI theme

## Version 2.0.0 (January 31, 2013)

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

## Version 1.0.0 (December 5, 2012)

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
