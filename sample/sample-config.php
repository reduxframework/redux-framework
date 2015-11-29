<?php
    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }


    // This is your option name where all the Redux data is stored.
    $opt_name = "redux_demo";

    // This line is only for altering the demo. Can be easily removed.
    $opt_name = apply_filters( 'redux_demo/opt_name', $opt_name );

    /*
     *
     * --> Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
     *
     */

    $sampleHTML = '';
    if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
        Redux_Functions::initWpFilesystem();

        global $wp_filesystem;

        $sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
    }

    // Background Patterns Reader
    $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
    $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
    $sample_patterns      = array();

    if ( is_dir( $sample_patterns_path ) ) {

        if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) {
            $sample_patterns = array();

            while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

                if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                    $name              = explode( '.', $sample_patterns_file );
                    $name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                    $sample_patterns[] = array(
                        'alt' => $name,
                        'img' => $sample_patterns_url . $sample_patterns_file
                    );
                }
            }
        }
    }

    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $theme = wp_get_theme(); // For use with some settings. Not necessary.

    $args = array(
        // TYPICAL -> Change these values as you need/desire
        'opt_name'             => $opt_name,
        // This is where your data is stored in the database and also becomes your global variable name.
        'display_name'         => $theme->get( 'Name' ),
        // Name that appears at the top of your panel
        'display_version'      => $theme->get( 'Version' ),
        // Version that appears at the top of your panel
        'menu_type'            => 'menu',
        //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
        'allow_sub_menu'       => true,
        // Show the sections below the admin menu item or not
        'menu_title'           => __( 'Sample Options', 'redux-framework-demo' ),
        'page_title'           => __( 'Sample Options', 'redux-framework-demo' ),
        // You will need to generate a Google API key to use this feature.
        // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_api_key'       => '',
        // Set it you want google fonts to update weekly. A google_api_key value is required.
        'google_update_weekly' => false,
        // Must be defined to add google fonts to the typography module
        'async_typography'     => true,
        // Use a asynchronous font on the front end or font string
        //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
        'admin_bar'            => true,
        // Show the panel pages on the admin bar
        'admin_bar_icon'       => 'dashicons-portfolio',
        // Choose an icon for the admin bar menu
        'admin_bar_priority'   => 50,
        // Choose an priority for the admin bar menu
        'global_variable'      => '',
        // Set a different name for your global variable other than the opt_name
        'dev_mode'             => true,
        // Show the time the page took to load, etc
        'update_notice'        => true,
        // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
        'customizer'           => true,
        // Enable basic customizer support
        //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
        //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

        // OPTIONAL -> Give you extra features
        'page_priority'        => null,
        // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
        'page_parent'          => 'themes.php',
        // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions'     => 'manage_options',
        // Permissions needed to access the options panel.
        'menu_icon'            => '',
        // Specify a custom URL to an icon
        'last_tab'             => '',
        // Force your panel to always open to a specific tab (by id)
        'page_icon'            => 'icon-themes',
        // Icon displayed in the admin panel next to your menu_title
        'page_slug'            => '',
        // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
        'save_defaults'        => true,
        // On load save the defaults to DB before user clicks save or not
        'default_show'         => false,
        // If true, shows the default value next to each field that is not the default value.
        'default_mark'         => '',
        // What to print by the field's title if the value shown is default. Suggested: *
        'show_import_export'   => true,
        // Shows the Import/Export panel when not used as a field.

        // CAREFUL -> These options are for advanced use only
        'transient_time'       => 60 * MINUTE_IN_SECONDS,
        'output'               => true,
        // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
        'output_tag'           => true,
        // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
        // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

        // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
        'database'             => '',
        // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
        'use_cdn'              => true,
        // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

        // HINTS
        'hints'                => array(
            'icon'          => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color'    => 'lightgray',
            'icon_size'     => 'normal',
            'tip_style'     => array(
                'color'   => 'red',
                'shadow'  => true,
                'rounded' => false,
                'style'   => '',
            ),
            'tip_position'  => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect'    => array(
                'show' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'mouseover',
                ),
                'hide' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click mouseleave',
                ),
            ),
        )
    );

    // ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
    $args['admin_bar_links'][] = array(
        'id'    => 'redux-docs',
        'href'  => 'http://docs.reduxframework.com/',
        'title' => __( 'Documentation', 'redux-framework-demo' ),
    );

    $args['admin_bar_links'][] = array(
        //'id'    => 'redux-support',
        'href'  => 'https://github.com/ReduxFramework/redux-framework/issues',
        'title' => __( 'Support', 'redux-framework-demo' ),
    );

    $args['admin_bar_links'][] = array(
        'id'    => 'redux-extensions',
        'href'  => 'reduxframework.com/extensions',
        'title' => __( 'Extensions', 'redux-framework-demo' ),
    );

    // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
    $args['share_icons'][] = array(
        'url'   => 'https://github.com/ReduxFramework/ReduxFramework',
        'title' => 'Visit us on GitHub',
        'icon'  => 'el el-github'
        //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
    );
    $args['share_icons'][] = array(
        'url'   => 'https://www.facebook.com/pages/Redux-Framework/243141545850368',
        'title' => 'Like us on Facebook',
        'icon'  => 'el el-facebook'
    );
    $args['share_icons'][] = array(
        'url'   => 'http://twitter.com/reduxframework',
        'title' => 'Follow us on Twitter',
        'icon'  => 'el el-twitter'
    );
    $args['share_icons'][] = array(
        'url'   => 'http://www.linkedin.com/company/redux-framework',
        'title' => 'Find us on LinkedIn',
        'icon'  => 'el el-linkedin'
    );

    // Panel Intro text -> before the form
    if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
        if ( ! empty( $args['global_variable'] ) ) {
            $v = $args['global_variable'];
        } else {
            $v = str_replace( '-', '_', $args['opt_name'] );
        }
        $args['intro_text'] = sprintf( __( '<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'redux-framework-demo' ), $v );
    } else {
        $args['intro_text'] = __( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'redux-framework-demo' );
    }

    // Add content after the form.
    $args['footer_text'] = __( '<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'redux-framework-demo' );

    Redux::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */


    /*
     * ---> START HELP TABS
     */

    $tabs = array(
        array(
            'id'      => 'redux-help-tab-1',
            'title'   => __( 'Theme Information 1', 'redux-framework-demo' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo' )
        ),
        array(
            'id'      => 'redux-help-tab-2',
            'title'   => __( 'Theme Information 2', 'redux-framework-demo' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo' )
        )
    );
    Redux::setHelpTab( $opt_name, $tabs );

    // Set the help sidebar
    $content = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'redux-framework-demo' );
    Redux::setHelpSidebar( $opt_name, $content );


    /*
     * <--- END HELP TABS
     */


    /*
     *
     * ---> START SECTIONS
     *
     */

    /*

        As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for


     */

    // -> START Basic Fields
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Basic Fields', 'redux-framework-demo' ),
        'id'               => 'basic',
        'desc'             => __( 'These are really basic fields!', 'redux-framework-demo' ),
        'customizer_width' => '400px',
        'icon'             => 'el el-home'
    ) );

    Redux::setSection( $opt_name, array(
        'title'            => __( 'Checkbox', 'redux-framework-demo' ),
        'id'               => 'basic-checkbox',
        'subsection'       => true,
        'customizer_width' => '450px',
        'desc'             => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/checkbox/" target="_blank">docs.reduxframework.com/core/fields/checkbox/</a>',
        'fields'           => array(
            array(
                'id'       => 'opt-checkbox',
                'type'     => 'checkbox',
                'title'    => __( 'Checkbox Option', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                'default'  => '1'// 1 = on | 0 = off
            ),
            array(
                'id'       => 'opt-multi-check',
                'type'     => 'checkbox',
                'title'    => __( 'Multi Checkbox Option', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                //Must provide key => value pairs for multi checkbox options
                'options'  => array(
                    '1' => 'Opt 1',
                    '2' => 'Opt 2',
                    '3' => 'Opt 3'
                ),
                //See how std has changed? you also don't need to specify opts that are 0.
                'default'  => array(
                    '1' => '1',
                    '2' => '0',
                    '3' => '0'
                )
            ),
            array(
                'id'       => 'opt-checkbox-data',
                'type'     => 'checkbox',
                'title'    => __( 'Multi Checkbox Option (with menu data)', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                'data'     => 'menu'
            ),
            array(
                'id'       => 'opt-checkbox-sidebar',
                'type'     => 'checkbox',
                'title'    => __( 'Multi Checkbox Option (with sidebar data)', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                'data'     => 'sidebars'
            ),
        )
    ) );
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Radio', 'redux-framework-demo' ),
        'id'               => 'basic-Radio',
        'subsection'       => true,
        'customizer_width' => '500px',
        'desc'             => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/radio/" target="_blank">docs.reduxframework.com/core/fields/radio/</a>',
        'fields'           => array(
            array(
                'id'       => 'opt-radio',
                'type'     => 'radio',
                'title'    => __( 'Radio Option', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                //Must provide key => value pairs for radio options
                'options'  => array(
                    '1' => 'Opt 1',
                    '2' => 'Opt 2',
                    '3' => 'Opt 3'
                ),
                'default'  => '2'
            ),
            array(
                'id'       => 'opt-radio-data',
                'type'     => 'radio',
                'title'    => __( 'Radio Option w/ Menu Data', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                'data'     => 'menu'
            ),
        )
    ) );
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Sortable', 'redux-framework-demo' ),
        'id'         => 'basic-Sortable',
        'subsection' => true,
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/sortable/" target="_blank">docs.reduxframework.com/core/fields/sortable/</a>',
        'fields'     => array(
            array(
                'id'       => 'opt-sortable',
                'type'     => 'sortable',
                'title'    => __( 'Sortable Text Option', 'redux-framework-demo' ),
                'subtitle' => __( 'Define and reorder these however you want.', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                'label'    => true,
                'options'  => array(
                    'Text One'   => 'Item 1',
                    'Text Two'   => 'Item 2',
                    'Text Three' => 'Item 3',
                )
            ),
            array(
                'id'       => 'opt-check-sortable',
                'type'     => 'sortable',
                'mode'     => 'checkbox', // checkbox or text
                'title'    => __( 'Sortable Text Option', 'redux-framework-demo' ),
                'subtitle' => __( 'Define and reorder these however you want.', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                'options'  => array(
                    'cb1' => 'Checkbox One',
                    'cb2' => 'Checkbox Two',
                    'cb3' => 'Checkbox Three',
                ),
                'default'  => array(
                    'cb1' => false,
                    'cb2' => true,
                    'cb3' => false,
                )
            ),
        )
    ) );


    Redux::setSection( $opt_name, array(
        'title'            => __( 'Text', 'redux-framework-demo' ),
        'desc'             => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/text/" target="_blank">docs.reduxframework.com/core/fields/text/</a>',
        'id'               => 'basic-Text',
        'subsection'       => true,
        'customizer_width' => '700px',
        'fields'           => array(
            array(
                'id'       => 'text-example',
                'type'     => 'text',
                'title'    => __( 'Text Field', 'redux-framework-demo' ),
                'subtitle' => __( 'Subtitle', 'redux-framework-demo' ),
                'desc'     => __( 'Field Description', 'redux-framework-demo' ),
                'default'  => 'Default Text',
            ),
            array(
                'id'        => 'text-example-hint',
                'type'      => 'text',
                'title'     => __( 'Text Field w/ Hint', 'redux-framework-demo' ),
                'subtitle'  => __( 'Subtitle', 'redux-framework-demo' ),
                'desc'      => __( 'Field Description', 'redux-framework-demo' ),
                'default'   => 'Default Text',
                'text_hint' => array(
                    'title'   => 'Hint Title',
                    'content' => 'Hint content about this field!'
                )
            ),
            array(
                'id'          => 'text-placeholder',
                'type'        => 'text',
                'title'       => __( 'Text Field', 'redux-framework-demo' ),
                'subtitle'    => __( 'Subtitle', 'redux-framework-demo' ),
                'desc'        => __( 'Field Description', 'redux-framework-demo' ),
                'placeholder' => 'Placeholder Text',
            ),

        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Multi Text', 'redux-framework-demo' ),
        'id'         => 'basic-Multi Text',
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/multi-text/" target="_blank">docs.reduxframework.com/core/fields/multi-text/</a>',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'opt-multitext',
                'type'     => 'multi_text',
                'title'    => __( 'Multi Text Option', 'redux-framework-demo' ),
                'subtitle' => __( 'Field subtitle', 'redux-framework-demo' ),
                'desc'     => __( 'Field Decription', 'redux-framework-demo' )
            ),
        )
    ) );
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Password', 'redux-framework-demo' ),
        'id'         => 'basic-Password',
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/password/" target="_blank">docs.reduxframework.com/core/fields/password/</a>',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'password',
                'type'     => 'password',
                'username' => true,
                'title'    => 'Password Field',
                //'placeholder' => array(
                //    'username' => 'Username',
                //    'password' => 'Password',
                //)
            )
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Textarea', 'redux-framework-demo' ),
        'id'         => 'basic-Textarea',
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/textarea/" target="_blank">docs.reduxframework.com/core/fields/textarea/</a>',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'opt-textarea',
                'type'     => 'textarea',
                'title'    => __( 'Textarea Option - HTML Validated Custom', 'redux-framework-demo' ),
                'subtitle' => __( 'Subtitle', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                'default'  => 'Default Text',
            )
        )
    ) );

    // -> START Editors
    Redux::setSection( $opt_name, array(
        'title'            => __( 'Editors', 'redux-framework-demo' ),
        'id'               => 'editor',
        'customizer_width' => '500px',
        'icon'             => 'el el-edit',
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'WordPress Editor', 'redux-framework-demo' ),
        'id'         => 'editor-wordpress',
        //'icon'  => 'el el-home'
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/editor/" target="_blank">docs.reduxframework.com/core/fields/editor/</a>',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'opt-editor',
                'type'     => 'editor',
                'title'    => __( 'Editor', 'redux-framework-demo' ),
                'subtitle' => __( 'Use any of the features of WordPress editor inside your panel!', 'redux-framework-demo' ),
                'default'  => 'Powered by Redux Framework.',
            ),
            array(
                'id'      => 'opt-editor-tiny',
                'type'    => 'editor',
                'title'   => __( 'Editor w/o Media Button', 'redux-framework-demo' ),
                'default' => 'Powered by Redux Framework.',
                'args'    => array(
                    'wpautop'       => false,
                    'media_buttons' => false,
                    'textarea_rows' => 5,
                    //'tabindex' => 1,
                    //'editor_css' => '',
                    'teeny'         => false,
                    //'tinymce' => array(),
                    'quicktags'     => false,
                )
            ),
            array(
                'id'         => 'opt-editor-full',
                'type'       => 'editor',
                'title'      => __( 'Editor - Full Width', 'redux-framework-demo' ),
                'full_width' => true
            ),
        ),
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/editor/" target="_blank">docs.reduxframework.com/core/fields/editor/</a>',
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'ACE Editor', 'redux-framework-demo' ),
        'id'         => 'editor-ace',
        //'icon'  => 'el el-home'
        'subsection' => true,
        'desc'       => __( 'For full documentation on the this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/ace-editor/" target="_blank">docs.reduxframework.com/core/fields/ace-editor/</a>',
        'fields'     => array(
            array(
                'id'       => 'opt-ace-editor-css',
                'type'     => 'ace_editor',
                'title'    => __( 'CSS Code', 'redux-framework-demo' ),
                'subtitle' => __( 'Paste your CSS code here.', 'redux-framework-demo' ),
                'mode'     => 'css',
                'theme'    => 'monokai',
                'desc'     => 'Possible modes can be found at <a href="' . 'http://' . 'ace.c9.io" target="_blank">' . 'http://' . 'ace.c9.io/</a>.',
                'default'  => "#header{\n   margin: 0 auto;\n}"
            ),
            array(
                'id'       => 'opt-ace-editor-js',
                'type'     => 'ace_editor',
                'title'    => __( 'JS Code', 'redux-framework-demo' ),
                'subtitle' => __( 'Paste your JS code here.', 'redux-framework-demo' ),
                'mode'     => 'javascript',
                'theme'    => 'chrome',
                'desc'     => 'Possible modes can be found at <a href="' . 'http://' . 'ace.c9.io" target="_blank">' . 'http://' . 'ace.c9.io/</a>.',
                'default'  => "jQuery(document).ready(function(){\n\n});"
            ),
            array(
                'id'         => 'opt-ace-editor-php',
                'type'       => 'ace_editor',
                'full_width' => true,
                'title'      => __( 'PHP Code', 'redux-framework-demo' ),
                'subtitle'   => __( 'Paste your PHP code here.', 'redux-framework-demo' ),
                'mode'       => 'php',
                'theme'      => 'chrome',
                'desc'       => 'Possible modes can be found at <a href="' . 'http://' . 'ace.c9.io" target="_blank">' . 'http://' . 'ace.c9.io/</a>.',
                'default'    => '<?php
    echo "PHP String";'
            ),


        )
    ) );

    // -> START Color Selection
    Redux::setSection( $opt_name, array(
        'title' => __( 'Color Selection', 'redux-framework-demo' ),
        'id'    => 'color',
        'desc'  => __( '', 'redux-framework-demo' ),
        'icon'  => 'el el-brush'
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Color', 'redux-framework-demo' ),
        'id'         => 'color-Color',
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/color/" target="_blank">docs.reduxframework.com/core/fields/color/</a>',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'opt-color-title',
                'type'     => 'color',
                'output'   => array( '.site-title' ),
                'title'    => __( 'Site Title Color', 'redux-framework-demo' ),
                'subtitle' => __( 'Pick a title color for the theme (default: #000).', 'redux-framework-demo' ),
                'default'  => '#000000',
            ),
            array(
                'id'       => 'opt-color-footer',
                'type'     => 'color',
                'title'    => __( 'Footer Background Color', 'redux-framework-demo' ),
                'subtitle' => __( 'Pick a background color for the footer (default: #dd9933).', 'redux-framework-demo' ),
                'default'  => '#dd9933',
                'validate' => 'color',
            ),
        ),
    ) );
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Color Gradient', 'redux-framework-demo' ),
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/color-gradient/" target="_blank">docs.reduxframework.com/core/fields/color-gradient/</a>',
        'id'         => 'color-gradient',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'opt-color-header',
                'type'     => 'color_gradient',
                'title'    => __( 'Header Gradient Color Option', 'redux-framework-demo' ),
                'subtitle' => __( 'Only color validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                'default'  => array(
                    'from' => '#1e73be',
                    'to'   => '#00897e'
                )
            ),
        )
    ) );
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Color RGBA', 'redux-framework-demo' ),
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/color-rgba/" target="_blank">docs.reduxframework.com/core/fields/color-rgba/</a>',
        'id'         => 'color-rgba',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'opt-color-rgba',
                'type'     => 'color_rgba',
                'title'    => __( 'Color RGBA', 'redux-framework-demo' ),
                'subtitle' => __( 'Gives you the RGBA color.', 'redux-framework-demo' ),
                'default'  => array(
                    'color' => '#7e33dd',
                    'alpha' => '.8'
                ),
                //'output'   => array( 'body' ),
                'mode'     => 'background',
                //'validate' => 'colorrgba',
            ),
        )
    ) );
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Link Color', 'redux-framework-demo' ),
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/link-color/" target="_blank">docs.reduxframework.com/core/fields/link-color/</a>',
        'id'         => 'color-link',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'opt-link-color',
                'type'     => 'link_color',
                'title'    => __( 'Links Color Option', 'redux-framework-demo' ),
                'subtitle' => __( 'Only color validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                //'regular'   => false, // Disable Regular Color
                //'hover'     => false, // Disable Hover Color
                //'active'    => false, // Disable Active Color
                //'visited'   => true,  // Enable Visited Color
                'default'  => array(
                    'regular' => '#aaa',
                    'hover'   => '#bbb',
                    'active'  => '#ccc',
                )
            ),
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Palette Colors', 'redux-framework-demo' ),
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/palette-color/" target="_blank">docs.reduxframework.com/core/fields/palette-color/</a>',
        'id'         => 'color-palette',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'opt-palette-color',
                'type'     => 'palette',
                'title'    => __( 'Palette Color Option', 'redux-framework-demo' ),
                'subtitle' => __( 'Only color validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                'default'  => 'red',
                'palettes' => array(
                    'red'  => array(
                        '#ef9a9a',
                        '#f44336',
                        '#ff1744',
                    ),
                    'pink' => array(
                        '#fce4ec',
                        '#f06292',
                        '#e91e63',
                        '#ad1457',
                        '#f50057',
                    ),
                    'cyan' => array(
                        '#e0f7fa',
                        '#80deea',
                        '#26c6da',
                        '#0097a7',
                        '#00e5ff',
                    ),
                )
            ),
        )
    ) );


    // -> START Design Fields
    Redux::setSection( $opt_name, array(
        'title' => __( 'Design Fields', 'redux-framework-demo' ),
        'id'    => 'design',
        'desc'  => __( '', 'redux-framework-demo' ),
        'icon'  => 'el el-wrench'
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Background', 'redux-framework-demo' ),
        'id'         => 'design-background',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'opt-background',
                'type'     => 'background',
                'output'   => array( 'body' ),
                'title'    => __( 'Body Background', 'redux-framework-demo' ),
                'subtitle' => __( 'Body background with image, color, etc.', 'redux-framework-demo' ),
                //'default'   => '#FFFFFF',
            ),

        ),
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/background/" target="_blank">docs.reduxframework.com/core/fields/background/</a>',
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Border', 'redux-framework-demo' ),
        'id'         => 'design-border',
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/border/" target="_blank">docs.reduxframework.com/core/fields/border/</a>',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'opt-header-border',
                'type'     => 'border',
                'title'    => __( 'Header Border Option', 'redux-framework-demo' ),
                'subtitle' => __( 'Only color validation can be done on this field type', 'redux-framework-demo' ),
                'output'   => array( '.site-header' ),
                // An array of CSS selectors to apply this font style to
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                'default'  => array(
                    'border-color'  => '#1e73be',
                    'border-style'  => 'solid',
                    'border-top'    => '3px',
                    'border-right'  => '3px',
                    'border-bottom' => '3px',
                    'border-left'   => '3px'
                )
            ),
            array(
                'id'       => 'opt-header-border-expanded',
                'type'     => 'border',
                'title'    => __( 'Header Border Option', 'redux-framework-demo' ),
                'subtitle' => __( 'Only color validation can be done on this field type', 'redux-framework-demo' ),
                'output'   => array( '.site-header' ),
                'all'      => false,
                // An array of CSS selectors to apply this font style to
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                'default'  => array(
                    'border-color'  => '#1e73be',
                    'border-style'  => 'solid',
                    'border-top'    => '3px',
                    'border-right'  => '3px',
                    'border-bottom' => '3px',
                    'border-left'   => '3px'
                )
            ),
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Dimensions', 'redux-framework-demo' ),
        'id'         => 'design-dimensions',
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/dimensions/" target="_blank">docs.reduxframework.com/core/fields/dimensions/</a>',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'             => 'opt-dimensions',
                'type'           => 'dimensions',
                'units'          => array( 'em', 'px', '%' ),    // You can specify a unit value. Possible: px, em, %
                'units_extended' => 'true',  // Allow users to select any type of unit
                'title'          => __( 'Dimensions (Width/Height) Option', 'redux-framework-demo' ),
                'subtitle'       => __( 'Allow your users to choose width, height, and/or unit.', 'redux-framework-demo' ),
                'desc'           => __( 'You can enable or disable any piece of this field. Width, Height, or Units.', 'redux-framework-demo' ),
                'default'        => array(
                    'width'  => 200,
                    'height' => 100,
                )
            ),
            array(
                'id'             => 'opt-dimensions-width',
                'type'           => 'dimensions',
                'units'          => array( 'em', 'px', '%' ),    // You can specify a unit value. Possible: px, em, %
                'units_extended' => 'true',  // Allow users to select any type of unit
                'title'          => __( 'Dimensions (Width) Option', 'redux-framework-demo' ),
                'subtitle'       => __( 'Allow your users to choose width, height, and/or unit.', 'redux-framework-demo' ),
                'desc'           => __( 'You can enable or disable any piece of this field. Width, Height, or Units.', 'redux-framework-demo' ),
                'height'         => false,
                'default'        => array(
                    'width'  => 200,
                    'height' => 100,
                )
            ),
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Spacing', 'redux-framework-demo' ),
        'id'         => 'design-spacing',
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/spacing/" target="_blank">docs.reduxframework.com/core/fields/spacing/</a>',
        'subsection' => true,
        'fields'     => array(

            array(
                'id'       => 'opt-spacing',
                'type'     => 'spacing',
                'output'   => array( '.site-header' ),
                // An array of CSS selectors to apply this font style to
                'mode'     => 'margin',
                // absolute, padding, margin, defaults to padding
                'all'      => true,
                // Have one field that applies to all
                //'top'           => false,     // Disable the top
                //'right'         => false,     // Disable the right
                //'bottom'        => false,     // Disable the bottom
                //'left'          => false,     // Disable the left
                //'units'         => 'em',      // You can specify a unit value. Possible: px, em, %
                //'units_extended'=> 'true',    // Allow users to select any type of unit
                //'display_units' => 'false',   // Set to false to hide the units if the units are specified
                'title'    => __( 'Padding/Margin Option', 'redux-framework-demo' ),
                'subtitle' => __( 'Allow your users to choose the spacing or margin they want.', 'redux-framework-demo' ),
                'desc'     => __( 'You can enable or disable any piece of this field. Top, Right, Bottom, Left, or Units.', 'redux-framework-demo' ),
                'default'  => array(
                    'margin-top'    => '1px',
                    'margin-right'  => '2px',
                    'margin-bottom' => '3px',
                    'margin-left'   => '4px'
                )
            ),
            array(
                'id'             => 'opt-spacing-expanded',
                'type'           => 'spacing',
                // An array of CSS selectors to apply this font style to
                'mode'           => 'margin',
                // absolute, padding, margin, defaults to padding
                'all'            => false,
                // Have one field that applies to all
                //'top'           => false,     // Disable the top
                //'right'         => false,     // Disable the right
                //'bottom'        => false,     // Disable the bottom
                //'left'          => false,     // Disable the left
                'units'          => array( 'em', 'px', '%' ),      // You can specify a unit value. Possible: px, em, %
                'units_extended' => 'true',    // Allow users to select any type of unit
                //'display_units' => 'false',   // Set to false to hide the units if the units are specified
                'title'          => __( 'Padding/Margin Option', 'redux-framework-demo' ),
                'subtitle'       => __( 'Allow your users to choose the spacing or margin they want.', 'redux-framework-demo' ),
                'desc'           => __( 'You can enable or disable any piece of this field. Top, Right, Bottom, Left, or Units.', 'redux-framework-demo' ),
                'default'        => array(
                    'margin-top'    => '1px',
                    'margin-right'  => '2px',
                    'margin-bottom' => '3px',
                    'margin-left'   => '4px'
                )
            ),
        )
    ) );

    // -> START Media Uploads
    Redux::setSection( $opt_name, array(
        'title' => __( 'Media Uploads', 'redux-framework-demo' ),
        'id'    => 'media',
        'desc'  => __( '', 'redux-framework-demo' ),
        'icon'  => 'el el-picture'
    ) );


    Redux::setSection( $opt_name, array(
        'title'      => __( 'Gallery', 'redux-framework-demo' ),
        'id'         => 'media-gallery',
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/gallery/" target="_blank">docs.reduxframework.com/core/fields/gallery/</a>',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'opt-gallery',
                'type'     => 'gallery',
                'title'    => __( 'Add/Edit Gallery', 'redux-framework-demo' ),
                'subtitle' => __( 'Create a new Gallery by selecting existing or uploading new images using the WordPress native uploader', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
            ),
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Media', 'redux-framework-demo' ),
        'id'         => 'media-media',
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/media/" target="_blank">docs.reduxframework.com/core/fields/media/</a>',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'opt-media',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'Media w/ URL', 'redux-framework-demo' ),
                'compiler' => 'true',
                //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc'     => __( 'Basic media uploader with disabled URL input field.', 'redux-framework-demo' ),
                'subtitle' => __( 'Upload any media using the WordPress native uploader', 'redux-framework-demo' ),
                'default'  => array( 'url' => 'http://s.wordpress.org/style/images/codeispoetry.png' ),
                //'hint'      => array(
                //    'title'     => 'Hint Title',
                //    'content'   => 'This is a <b>hint</b> for the media field with a Title.',
                //)
            ),
            array(
                'id'       => 'media-no-url',
                'type'     => 'media',
                'title'    => __( 'Media w/o URL', 'redux-framework-demo' ),
                'desc'     => __( 'This represents the minimalistic view. It does not have the preview box or the display URL in an input box. ', 'redux-framework-demo' ),
                'subtitle' => __( 'Upload any media using the WordPress native uploader', 'redux-framework-demo' ),
            ),
            array(
                'id'       => 'media-no-preview',
                'type'     => 'media',
                'preview'  => false,
                'title'    => __( 'Media No Preview', 'redux-framework-demo' ),
                'desc'     => __( 'This represents the minimalistic view. It does not have the preview box or the display URL in an input box. ', 'redux-framework-demo' ),
                'subtitle' => __( 'Upload any media using the WordPress native uploader', 'redux-framework-demo' ),
                'hint'     => array(
                    'title'   => 'Test',
                    'content' => 'This is a <b>hint</b> tool-tip for the webFonts field.<br/><br/>Add any HTML based text you like here.',
                )
            ),
            array(
                'id'         => 'opt-random-upload',
                'type'       => 'media',
                'title'      => __( 'Upload Anything - Disabled Mode', 'redux-framework-demo' ),
                'full_width' => true,
                'mode'       => false,
                // Can be set to false to allow any media type, or can also be set to any mime type.
                'desc'       => __( 'Basic media uploader with disabled URL input field.', 'redux-framework-demo' ),
                'subtitle'   => __( 'Upload any media using the WordPress native uploader', 'redux-framework-demo' ),
            ),
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Slides', 'redux-framework-demo' ),
        'id'         => 'additional-slides',
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/slides/" target="_blank">docs.reduxframework.com/core/fields/slides/</a>',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'          => 'opt-slides',
                'type'        => 'slides',
                'title'       => __( 'Slides Options', 'redux-framework-demo' ),
                'subtitle'    => __( 'Unlimited slides with drag and drop sortings.', 'redux-framework-demo' ),
                'desc'        => __( 'This field will store all slides values into a multidimensional array to use into a foreach loop.', 'redux-framework-demo' ),
                'placeholder' => array(
                    'title'       => __( 'This is a title', 'redux-framework-demo' ),
                    'description' => __( 'Description Here', 'redux-framework-demo' ),
                    'url'         => __( 'Give us a link!', 'redux-framework-demo' ),
                ),
            ),
        )
    ) );

    // -> START Presentation Fields
    Redux::setSection( $opt_name, array(
        'title' => __( 'Presentation Fields', 'redux-framework-demo' ),
        'id'    => 'presentation',
        'desc'  => __( '', 'redux-framework-demo' ),
        'icon'  => 'el el-screen'
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Divide', 'redux-framework-demo' ),
        'id'         => 'presentation-divide',
        'desc'       => __( 'The spacer to the section menu as seen to the left (after this section block) is the divide "section". Also the divider below is the divide "field".', 'redux-framework-demo' ) . '<br />' . __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/divide/" target="_blank">docs.reduxframework.com/core/fields/divide/</a>',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'   => 'opt-divide',
                'type' => 'divide'
            ),
        ),
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Info', 'redux-framework-demo' ),
        'id'         => 'presentation-info',
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/info/" target="_blank">docs.reduxframework.com/core/fields/info/</a>',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'   => 'opt-info-field',
                'type' => 'info',
                'desc' => __( 'This is the info field, if you want to break sections up.', 'redux-framework-demo' )
            ),
            array(
                'id'    => 'opt-notice-info1',
                'type'  => 'info',
                'style' => 'info',
                'title' => __( 'This is a title.', 'redux-framework-demo' ),
                'desc'  => __( 'This is an info field with the <strong>info</strong> style applied. By default the <strong>normal</strong> style is applied.', 'redux-framework-demo' )
            ),
            array(
                'id'    => 'opt-info-warning',
                'type'  => 'info',
                'style' => 'warning',
                'title' => __( 'This is a title.', 'redux-framework-demo' ),
                'desc'  => __( 'This is an info field with the <strong>warning</strong> style applied.', 'redux-framework-demo' )
            ),
            array(
                'id'    => 'opt-info-success',
                'type'  => 'info',
                'style' => 'success',
                'icon'  => 'el el-info-circle',
                'title' => __( 'This is a title.', 'redux-framework-demo' ),
                'desc'  => __( 'This is an info field with the <strong>success</strong> style applied and an icon.', 'redux-framework-demo' )
            ),
            array(
                'id'    => 'opt-info-critical',
                'type'  => 'info',
                'style' => 'critical',
                'icon'  => 'el el-info-circle',
                'title' => __( 'This is a title.', 'redux-framework-demo' ),
                'desc'  => __( 'This is an info field with the <strong>critical</strong> style applied and an icon.', 'redux-framework-demo' )
            ),
            array(
                'id'    => 'opt-info-custom',
                'type'  => 'info',
                'style' => 'custom',
                'color' => 'purple',
                'icon'  => 'el el-info-circle',
                'title' => __( 'This is a title.', 'redux-framework-demo' ),
                'desc'  => __( 'This is an info field with the <strong>custom</strong> style applied, color arg passed, and an icon.', 'redux-framework-demo' )
            ),
            array(
                'id'     => 'opt-info-normal',
                'type'   => 'info',
                'notice' => false,
                'title'  => __( 'This is a title.', 'redux-framework-demo' ),
                'desc'   => __( 'This is an info non-notice field with the <strong>normal</strong> style applied.', 'redux-framework-demo' )
            ),
            array(
                'id'     => 'opt-notice-info',
                'type'   => 'info',
                'notice' => false,
                'style'  => 'info',
                'title'  => __( 'This is a title.', 'redux-framework-demo' ),
                'desc'   => __( 'This is an info non-notice field with the <strong>info</strong> style applied.', 'redux-framework-demo' )
            ),
            array(
                'id'     => 'opt-notice-warning',
                'type'   => 'info',
                'notice' => false,
                'style'  => 'warning',
                'icon'   => 'el el-info-circle',
                'title'  => __( 'This is a title.', 'redux-framework-demo' ),
                'desc'   => __( 'This is an info non-notice field with the <strong>warning</strong> style applied and an icon.', 'redux-framework-demo' )
            ),
            array(
                'id'     => 'opt-notice-success',
                'type'   => 'info',
                'notice' => false,
                'style'  => 'success',
                'icon'   => 'el el-info-circle',
                'title'  => __( 'This is a title.', 'redux-framework-demo' ),
                'desc'   => __( 'This is an info non-notice field with the <strong>success</strong> style applied and an icon.', 'redux-framework-demo' )
            ),
            array(
                'id'     => 'opt-notice-critical',
                'type'   => 'info',
                'notice' => false,
                'style'  => 'critical',
                'icon'   => 'el el-info-circle',
                'title'  => __( 'This is a title.', 'redux-framework-demo' ),
                'desc'   => __( 'This is an non-notice field with the <strong>critical</strong> style applied and an icon.', 'redux-framework-demo' )
            ),
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Section', 'redux-framework-demo' ),
        'id'         => 'presentation-section',
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/section/" target="_blank">docs.reduxframework.com/core/fields/section/</a>',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'section-start',
                'type'     => 'section',
                'title'    => __( 'Section Example', 'redux-framework-demo' ),
                'subtitle' => __( 'With the "section" field you can create indented option sections.', 'redux-framework-demo' ),
                'indent'   => true, // Indent all options below until the next 'section' option is set.
            ),
            array(
                'id'       => 'section-test',
                'type'     => 'text',
                'title'    => __( 'Field Title', 'redux-framework-demo' ),
                'subtitle' => __( 'Field Subtitle', 'redux-framework-demo' ),
            ),
            array(
                'id'       => 'section-test-media',
                'type'     => 'media',
                'title'    => __( 'Field Title', 'redux-framework-demo' ),
                'subtitle' => __( 'Field Subtitle', 'redux-framework-demo' ),
            ),
            array(
                'id'     => 'section-end',
                'type'   => 'section',
                'indent' => false, // Indent all options below until the next 'section' option is set.
            ),
            array(
                'id'   => 'section-info',
                'type' => 'info',
                'desc' => __( 'And now you can add more fields below and outside of the indent.', 'redux-framework-demo' ),
            ),
        ),
    ) );
    Redux::setSection( $opt_name, array(
        'id'   => 'presentation-divide-sample',
        'type' => 'divide',
    ) );

    // -> START Switch & Button Set
    Redux::setSection( $opt_name, array(
        'title' => __( 'Switch & Button Set', 'redux-framework-demo' ),
        'id'    => 'switch_buttonset',
        'desc'  => __( '', 'redux-framework-demo' ),
        'icon'  => 'el el-cogs'
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Button Set', 'redux-framework-demo' ),
        'id'         => 'switch_buttonset-set',
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/button-set/" target="_blank">docs.reduxframework.com/core/fields/button-set/</a>',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'opt-button-set',
                'type'     => 'button_set',
                'title'    => __( 'Button Set Option', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                //Must provide key => value pairs for radio options
                'options'  => array(
                    '1' => 'Opt 1',
                    '2' => 'Opt 2',
                    '3' => 'Opt 3'
                ),
                'default'  => '2'
            ),
            array(
                'id'       => 'opt-button-set-multi',
                'type'     => 'button_set',
                'title'    => __( 'Button Set, Multi Select', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                'multi'    => true,
                //Must provide key => value pairs for radio options
                'options'  => array(
                    '1' => 'Opt 1',
                    '2' => 'Opt 2',
                    '3' => 'Opt 3'
                ),
                'default'  => array( '2', '3' )
            ),

        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Switch', 'redux-framework-demo' ),
        'id'         => 'switch_buttonset-switch',
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/switch/" target="_blank">docs.reduxframework.com/core/fields/switch/</a>',
        'subsection' => true,
        'fields'     => array(

            array(
                'id'       => 'switch-on',
                'type'     => 'switch',
                'title'    => __( 'Switch On', 'redux-framework-demo' ),
                'subtitle' => __( 'Look, it\'s on!', 'redux-framework-demo' ),
                'default'  => true,
            ),
            array(
                'id'       => 'switch-off',
                'type'     => 'switch',
                'title'    => __( 'Switch Off', 'redux-framework-demo' ),
                'subtitle' => __( 'Look, it\'s on!', 'redux-framework-demo' ),
                //'options' => array('on', 'off'),
                'default'  => false,
            ),
            array(
                'id'       => 'switch-parent',
                'type'     => 'switch',
                'title'    => __( 'Switch - Nested Children, Enable to show', 'redux-framework-demo' ),
                'subtitle' => __( 'Look, it\'s on! Also hidden child elements!', 'redux-framework-demo' ),
                'default'  => 0,
                'on'       => 'Enabled',
                'off'      => 'Disabled',
            ),
            array(
                'id'       => 'switch-child1',
                'type'     => 'switch',
                'required' => array( 'switch-parent', '=', '1' ),
                'title'    => __( 'Switch - This and the next switch required for patterns to show', 'redux-framework-demo' ),
                'subtitle' => __( 'Also called a "fold" parent.', 'redux-framework-demo' ),
                'desc'     => __( 'Items set with a fold to this ID will hide unless this is set to the appropriate value.', 'redux-framework-demo' ),
                'default'  => false,
            ),
            array(
                'id'       => 'switch-child2',
                'type'     => 'switch',
                'required' => array( 'switch-parent', '=', '1' ),
                'title'    => __( 'Switch2 - Enable the above switch and this one for patterns to show', 'redux-framework-demo' ),
                'subtitle' => __( 'Also called a "fold" parent.', 'redux-framework-demo' ),
                'desc'     => __( 'Items set with a fold to this ID will hide unless this is set to the appropriate value.', 'redux-framework-demo' ),
                'default'  => false,
            ),
        )
    ) );

    // -> START Select Fields
    Redux::setSection( $opt_name, array(
        'title' => __( 'Select Fields', 'redux-framework-demo' ),
        'id'    => 'select',
        'icon'  => 'el el-list-alt'
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Select', 'redux-framework-demo' ),
        'id'         => 'select-select',
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/select/" target="_blank">docs.reduxframework.com/core/fields/select/</a>',
        'subsection' => true,
        'fields'     => array(

            array(
                'id'       => 'opt-select',
                'type'     => 'select',
                'title'    => __( 'Select Option', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                //Must provide key => value pairs for select options
                'options'  => array(
                    '1' => 'Opt 1',
                    '2' => 'Opt 2',
                    '3' => 'Opt 3',
                ),
                'default'  => '2'
            ),
            array(
                'id'       => 'opt-select-stylesheet',
                'type'     => 'select',
                'title'    => __( 'Theme Stylesheet', 'redux-framework-demo' ),
                'subtitle' => __( 'Select your themes alternative color scheme.', 'redux-framework-demo' ),
                'options'  => array( 'default.css' => 'default.css', 'color1.css' => 'color1.css' ),
                'default'  => 'default.css',
            ),
            array(
                'id'       => 'opt-select-optgroup',
                'type'     => 'select',
                'title'    => __( 'Select Option with optgroup', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                //Must provide key => value pairs for select options
                'options'  => array(
                    'Group 1' => array(
                        '1' => 'Opt 1',
                        '2' => 'Opt 2',
                        '3' => 'Opt 3',
                    ),
                    'Group 2' => array(
                        '4' => 'Opt 4',
                        '5' => 'Opt 5',
                        '6' => 'Opt 6',
                    ),
                    '7'       => 'Opt 7',
                    '8'       => 'Opt 8',
                    '9'       => 'Opt 9',
                ),
                'default'  => '2'
            ),
            array(
                'id'       => 'opt-multi-select',
                'type'     => 'select',
                'multi'    => true,
                'title'    => __( 'Multi Select Option', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                //Must provide key => value pairs for radio options
                'options'  => array(
                    '1' => 'Opt 1',
                    '2' => 'Opt 2',
                    '3' => 'Opt 3'
                ),
                //'required' => array( 'opt-select', 'equals', array( '1', '3' ) ),
                'default'  => array( '2', '3' )
            ),
            array(
                'id'       => 'opt-select-image',
                'type'     => 'select_image',
                'title'    => __( 'Select Image', 'redux-framework-demo' ),
                'subtitle' => __( 'A preview of the selected image will appear underneath the select box.', 'redux-framework-demo' ),
                'options'  => $sample_patterns,
                // Alternatively
                //'options'   => Array(
                //                'img_name' => 'img_path'
                //             )
                'default'  => 'tree_bark.png',
            ),
            array(
                'id'   => 'opt-info',
                'type' => 'info',
                'desc' => __( 'You can easily add a variety of data from WordPress.', 'redux-framework-demo' ),
            ),
            array(
                'id'       => 'opt-select-categories',
                'type'     => 'select',
                'data'     => 'categories',
                'title'    => __( 'Categories Select Option', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
            ),
            array(
                'id'       => 'opt-select-categories-multi',
                'type'     => 'select',
                'data'     => 'categories',
                'multi'    => true,
                'title'    => __( 'Categories Multi Select Option', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
            ),
            array(
                'id'       => 'opt-select-pages',
                'type'     => 'select',
                'data'     => 'pages',
                'title'    => __( 'Pages Select Option', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
            ),
            array(
                'id'       => 'opt-multi-select-pages',
                'type'     => 'select',
                'data'     => 'pages',
                'multi'    => true,
                'title'    => __( 'Pages Multi Select Option', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
            ),
            array(
                'id'       => 'opt-select-tags',
                'type'     => 'select',
                'data'     => 'tags',
                'title'    => __( 'Tags Select Option', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
            ),
            array(
                'id'       => 'opt-multi-select-tags',
                'type'     => 'select',
                'data'     => 'tags',
                'multi'    => true,
                'title'    => __( 'Tags Multi Select Option', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
            ),
            array(
                'id'       => 'opt-select-menus',
                'type'     => 'select',
                'data'     => 'menus',
                'title'    => __( 'Menus Select Option', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
            ),
            array(
                'id'       => 'opt-multi-select-menus',
                'type'     => 'select',
                'data'     => 'menu',
                'multi'    => true,
                'title'    => __( 'Menus Multi Select Option', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
            ),
            array(
                'id'       => 'opt-select-post-type',
                'type'     => 'select',
                'data'     => 'post_type',
                'title'    => __( 'Post Type Select Option', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
            ),
            array(
                'id'       => 'opt-multi-select-post-type',
                'type'     => 'select',
                'data'     => 'post_type',
                'multi'    => true,
                'title'    => __( 'Post Type Multi Select Option', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
            ),
            array(
                'id'       => 'opt-multi-select-sortable',
                'type'     => 'select',
                'data'     => 'post_type',
                'multi'    => true,
                'sortable' => true,
                'title'    => __( 'Post Type Multi Select Option + Sortable', 'redux-framework-demo' ),
                'subtitle' => __( 'This field also has sortable enabled!', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
            ),
            array(
                'id'       => 'opt-select-posts',
                'type'     => 'select',
                'data'     => 'post',
                'title'    => __( 'Posts Select Option2', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
            ),
            array(
                'id'       => 'opt-multi-select-posts',
                'type'     => 'select',
                'data'     => 'post',
                'multi'    => true,
                'title'    => __( 'Posts Multi Select Option', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
            ),
            array(
                'id'       => 'opt-select-roles',
                'type'     => 'select',
                'data'     => 'roles',
                'title'    => __( 'User Role Select Option', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
            ),
            array(
                'id'       => 'opt-select-capabilities',
                'type'     => 'select',
                'data'     => 'capabilities',
                'multi'    => true,
                'title'    => __( 'Capabilities Select Option', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
            ),
            array(
                'id'       => 'opt-select-elusive',
                'type'     => 'select',
                'data'     => 'elusive-icons',
                'title'    => __( 'Elusive Icons Select Option', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'Here\'s a list of all the elusive icons by name and icon.', 'redux-framework-demo' ),
            ),
            array(
                'id'       => 'opt-select-users',
                'type'     => 'select',
                'data'     => 'users',
                'title'    => __( 'Users Select Option', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
            ),
        )
    ) );
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Image Select', 'redux-framework-demo' ),
        'id'         => 'select-image_select',
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/image-select/" target="_blank">docs.reduxframework.com/core/fields/image-select/</a>',
        'subsection' => true,
        'fields'     => array(

            array(
                'id'       => 'opt-image-select-layout',
                'type'     => 'image_select',
                'title'    => __( 'Images Option for Layout', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This uses some of the built in images, you can use them for layout options.', 'redux-framework-demo' ),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '1' => array(
                        'alt' => '1 Column',
                        'img' => ReduxFramework::$_url . 'assets/img/1col.png'
                    ),
                    '2' => array(
                        'alt' => '2 Column Left',
                        'img' => ReduxFramework::$_url . 'assets/img/2cl.png'
                    ),
                    '3' => array(
                        'alt' => '2 Column Right',
                        'img' => ReduxFramework::$_url . 'assets/img/2cr.png'
                    ),
                    '4' => array(
                        'alt' => '3 Column Middle',
                        'img' => ReduxFramework::$_url . 'assets/img/3cm.png'
                    ),
                    '5' => array(
                        'alt' => '3 Column Left',
                        'img' => ReduxFramework::$_url . 'assets/img/3cl.png'
                    ),
                    '6' => array(
                        'alt' => '3 Column Right',
                        'img' => ReduxFramework::$_url . 'assets/img/3cr.png'
                    )
                ),
                'default'  => '2'
            ),
            array(
                'id'       => 'opt-patterns',
                'type'     => 'image_select',
                'tiles'    => true,
                'title'    => __( 'Images Option (with tiles => true)', 'redux-framework-demo' ),
                'subtitle' => __( 'Select a background pattern.', 'redux-framework-demo' ),
                'default'  => 0,
                'options'  => $sample_patterns
                ,
            ),
            array(
                'id'       => 'opt-image-select',
                'type'     => 'image_select',
                'title'    => __( 'Images Option', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                //Must provide key => value(array:title|img) pairs for radio options
                'options'  => array(
                    '1' => array( 'title' => 'Opt 1', 'img' => 'images/align-none.png' ),
                    '2' => array( 'title' => 'Opt 2', 'img' => 'images/align-left.png' ),
                    '3' => array( 'title' => 'Opt 3', 'img' => 'images/align-center.png' ),
                    '4' => array( 'title' => 'Opt 4', 'img' => 'images/align-right.png' )
                ),
                'default'  => '2'
            ),
            array(
                'id'         => 'opt-presets',
                'type'       => 'image_select',
                'presets'    => true,
                'full_width' => true,
                'title'      => __( 'Preset', 'redux-framework-demo' ),
                'subtitle'   => __( 'This allows you to set a json string or array to override multiple preferences in your theme.', 'redux-framework-demo' ),
                'default'    => 0,
                'desc'       => __( 'This allows you to set a json string or array to override multiple preferences in your theme.', 'redux-framework-demo' ),
                'options'    => array(
                    '1' => array(
                        'alt'     => 'Preset 1',
                        'img'     => ReduxFramework::$_url . '../sample/presets/preset1.png',
                        'presets' => array(
                            'switch-on'     => 1,
                            'switch-off'    => 1,
                            'switch-parent' => 1
                        )
                    ),
                    '2' => array(
                        'alt'     => 'Preset 2',
                        'img'     => ReduxFramework::$_url . '../sample/presets/preset2.png',
                        'presets' => '{"opt-slider-label":"1", "opt-slider-text":"10"}'
                    ),
                ),
            ),
        )
    ) );
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Select Image', 'redux-framework-demo' ),
        'id'         => 'select-select_image',
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/select-image/" target="_blank">docs.reduxframework.com/core/fields/select-image/</a>',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'      => 'opt-select_image',
                'type'    => 'select_image',
                'presets' => true,
                'title'   => __( 'Select Image', 'redux-framework-demo' ),
                'default' => 0,
                'options' => array(
                    '1' => array(
                        'alt' => 'Preset 1',
                        'img' => ReduxFramework::$_url . '../sample/presets/preset1.png',
                    ),
                    '2' => array(
                        'alt' => 'Preset 2',
                        'img' => ReduxFramework::$_url . '../sample/presets/preset2.png',
                    ),
                ),
            ),
        )
    ) );

    // -> START Slider / Spinner
    Redux::setSection( $opt_name, array(
        'title' => __( 'Slider / Spinner', 'redux-framework-demo' ),
        'id'    => 'slider_spinner',
        'desc'  => __( '', 'redux-framework-demo' ),
        'icon'  => 'el el-adjust-alt'
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Slider', 'redux-framework-demo' ),
        'id'         => 'slider_spinner-slider',
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/slider/" target="_blank">docs.reduxframework.com/core/fields/slider/</a>',
        'fields'     => array(

            array(
                'id'            => 'opt-slider-label',
                'type'          => 'slider',
                'title'         => __( 'Slider Example 1', 'redux-framework-demo' ),
                'subtitle'      => __( 'This slider displays the value as a label.', 'redux-framework-demo' ),
                'desc'          => __( 'Slider description. Min: 1, max: 500, step: 1, default value: 250', 'redux-framework-demo' ),
                'default'       => 250,
                'min'           => 1,
                'step'          => 1,
                'max'           => 500,
                'display_value' => 'label'
            ),
            array(
                'id'            => 'opt-slider-text',
                'type'          => 'slider',
                'title'         => __( 'Slider Example 2 with Steps (5)', 'redux-framework-demo' ),
                'subtitle'      => __( 'This example displays the value in a text box', 'redux-framework-demo' ),
                'desc'          => __( 'Slider description. Min: 0, max: 300, step: 5, default value: 75', 'redux-framework-demo' ),
                'default'       => 75,
                'min'           => 0,
                'step'          => 5,
                'max'           => 300,
                'display_value' => 'text'
            ),
            array(
                'id'            => 'opt-slider-select',
                'type'          => 'slider',
                'title'         => __( 'Slider Example 3 with two sliders', 'redux-framework-demo' ),
                'subtitle'      => __( 'This example displays the values in select boxes', 'redux-framework-demo' ),
                'desc'          => __( 'Slider description. Min: 0, max: 500, step: 5, slider 1 default value: 100, slider 2 default value: 300', 'redux-framework-demo' ),
                'default'       => array(
                    1 => 100,
                    2 => 300,
                ),
                'min'           => 0,
                'step'          => 5,
                'max'           => '500',
                'display_value' => 'select',
                'handles'       => 2,
            ),
            array(
                'id'            => 'opt-slider-float',
                'type'          => 'slider',
                'title'         => __( 'Slider Example 4 with float values', 'redux-framework-demo' ),
                'subtitle'      => __( 'This example displays float values', 'redux-framework-demo' ),
                'desc'          => __( 'Slider description. Min: 0, max: 1, step: .1, default value: .5', 'redux-framework-demo' ),
                'default'       => .5,
                'min'           => 0,
                'step'          => .1,
                'max'           => 1,
                'resolution'    => 0.1,
                'display_value' => 'text'
            ),

        ),
        'subsection' => true,
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Spinner', 'redux-framework-demo' ),
        'id'         => 'slider_spinner-spinner',
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/spinner/" target="_blank">docs.reduxframework.com/core/fields/spinner/</a>',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'      => 'opt-spinner',
                'type'    => 'spinner',
                'title'   => __( 'JQuery UI Spinner Example 1', 'redux-framework-demo' ),
                'desc'    => __( 'JQuery UI spinner description. Min:20, max: 100, step:20, default value: 40', 'redux-framework-demo' ),
                'default' => '40',
                'min'     => '20',
                'step'    => '20',
                'max'     => '100',
            ),
        )
    ) );

    // -> START Typography
    Redux::setSection( $opt_name, array(
        'title'  => __( 'Typography', 'redux-framework-demo' ),
        'id'     => 'typography',
        'desc'   => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/typography/" target="_blank">docs.reduxframework.com/core/fields/typography/</a>',
        'icon'   => 'el el-font',
        'fields' => array(
            array(
                'id'       => 'opt-typography-body',
                'type'     => 'typography',
                'title'    => __( 'Body Font', 'redux-framework-demo' ),
                'subtitle' => __( 'Specify the body font properties.', 'redux-framework-demo' ),
                'google'   => true,
                'default'  => array(
                    'color'       => '#dd9933',
                    'font-size'   => '30px',
                    'font-family' => 'Arial,Helvetica,sans-serif',
                    'font-weight' => 'Normal',
                ),
            ),
            array(
                'id'          => 'opt-typography',
                'type'        => 'typography',
                'title'       => __( 'Typography h2.site-description', 'redux-framework-demo' ),
                //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                //'google'      => false,
                // Disable google fonts. Won't work if you haven't defined your google api key
                'font-backup' => true,
                // Select a backup non-google font in addition to a google font
                //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                //'subsets'       => false, // Only appears if google is true and subsets not set to false
                //'font-size'     => false,
                //'line-height'   => false,
                //'word-spacing'  => true,  // Defaults to false
                //'letter-spacing'=> true,  // Defaults to false
                //'color'         => false,
                //'preview'       => false, // Disable the previewer
                'all_styles'  => true,
                // Enable all Google Font style/weight variations to be added to the page
                'output'      => array( 'h2.site-description, .entry-title' ),
                // An array of CSS selectors to apply this font style to dynamically
                'compiler'    => array( 'h2.site-description-compiler' ),
                // An array of CSS selectors to apply this font style to dynamically
                'units'       => 'px',
                // Defaults to px
                'subtitle'    => __( 'Typography option with each property can be called individually.', 'redux-framework-demo' ),
                'default'     => array(
                    'color'       => '#333',
                    'font-style'  => '700',
                    'font-family' => 'Abel',
                    'google'      => true,
                    'font-size'   => '33px',
                    'line-height' => '40px'
                ),
            ),
        )
    ) );

    // -> START Additional Types
    Redux::setSection( $opt_name, array(
        'title' => __( 'Additional Types', 'redux-framework-demo' ),
        'id'    => 'additional',
        'desc'  => __( '', 'redux-framework-demo' ),
        'icon'  => 'el el-magic',
        //'fields' => array(
        //    array(
        //        'id'              => 'opt-customizer-only-in-section',
        //        'type'            => 'select',
        //        'title'           => __( 'Customizer Only Option', 'redux-framework-demo' ),
        //        'subtitle'        => __( 'The subtitle is NOT visible in customizer', 'redux-framework-demo' ),
        //        'desc'            => __( 'The field desc is NOT visible in customizer.', 'redux-framework-demo' ),
        //        'customizer_only' => true,
        //        //Must provide key => value pairs for select options
        //        'options'         => array(
        //            '1' => 'Opt 1',
        //            '2' => 'Opt 2',
        //            '3' => 'Opt 3'
        //        ),
        //        'default'         => '2'
        //    ),
        //)
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Date', 'redux-framework-demo' ),
        'id'         => 'additional-date',
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/date/" target="_blank">docs.reduxframework.com/core/fields/date/</a>',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'opt-datepicker',
                'type'     => 'date',
                'title'    => __( 'Date Option', 'redux-framework-demo' ),
                'subtitle' => __( 'No validation can be done on this field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' )
            ),
        ),
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Sorter', 'redux-framework-demo' ),
        'id'         => 'additional-sorter',
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/sorter/" target="_blank">docs.reduxframework.com/core/fields/sorter/</a>',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'opt-homepage-layout',
                'type'     => 'sorter',
                'title'    => 'Layout Manager Advanced',
                'subtitle' => 'You can add multiple drop areas or columns.',
                'compiler' => 'true',
                'options'  => array(
                    'enabled'  => array(
                        'highlights' => 'Highlights',
                        'slider'     => 'Slider',
                        'staticpage' => 'Static Page',
                        'services'   => 'Services'
                    ),
                    'disabled' => array(),
                    'backup'   => array(),
                ),
                'limits'   => array(
                    'disabled' => 1,
                    'backup'   => 2,
                ),
            ),
            array(
                'id'       => 'opt-homepage-layout-2',
                'type'     => 'sorter',
                'title'    => 'Homepage Layout Manager',
                'desc'     => 'Organize how you want the layout to appear on the homepage',
                'compiler' => 'true',
                'options'  => array(
                    'disabled' => array(
                        'highlights' => 'Highlights',
                        'slider'     => 'Slider',
                    ),
                    'enabled'  => array(
                        'staticpage' => 'Static Page',
                        'services'   => 'Services'
                    ),
                ),
            ),
        )

    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Raw', 'redux-framework-demo' ),
        'id'         => 'additional-raw',
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/raw/" target="_blank">docs.reduxframework.com/core/fields/raw/</a>',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'opt-raw_info_4',
                'type'     => 'raw',
                'title'    => __( 'Standard Raw Field', 'redux-framework-demo' ),
                'subtitle' => __( 'Subtitle', 'redux-framework-demo' ),
                'desc'     => __( 'Description', 'redux-framework-demo' ),
                'content'  => $sampleHTML,
            ),
            array(
                'id'         => 'opt-raw_info_5',
                'type'       => 'raw',
                'full_width' => false,
                'title'      => __( 'Raw Field <code>full_width</code> False', 'redux-framework-demo' ),
                'subtitle'   => __( 'Subtitle', 'redux-framework-demo' ),
                'desc'       => __( 'Description', 'redux-framework-demo' ),
                'content'    => $sampleHTML,
            ),
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title' => __( 'Advanced Features', 'redux-framework-demo' ),
        'icon'  => 'el el-thumbs-up',
        // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Callback', 'redux-framework-demo' ),
        'id'         => 'additional-callback',
        'desc'       => __( 'For full documentation on this field, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/fields/callback/" target="_blank">docs.reduxframework.com/core/fields/callback/</a>',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'opt-custom-callback',
                'type'     => 'callback',
                'title'    => __( 'Custom Field Callback', 'redux-framework-demo' ),
                'subtitle' => __( 'This is a completely unique field type', 'redux-framework-demo' ),
                'desc'     => __( 'This is created with a callback function, so anything goes in this field. Make sure to define the function though.', 'redux-framework-demo' ),
                'callback' => 'redux_my_custom_field'
            ),
        )
    ) );

    // -> START Validation
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Field Validation', 'redux-framework-demo' ),
        'id'         => 'validation',
        'desc'       => __( 'For full documentation on validation, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/the-basics/validation/" target="_blank">docs.reduxframework.com/core/the-basics/validation/</a>',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'opt-text-email',
                'type'     => 'text',
                'title'    => __( 'Text Option - Email Validated', 'redux-framework-demo' ),
                'subtitle' => __( 'This is a little space under the Field Title in the Options table, additional info is good in here.', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                'validate' => 'email',
                'msg'      => 'custom error message',
                'default'  => 'test@test.com',
            ),
            array(
                'id'       => 'opt-text-post-type',
                'type'     => 'text',
                'title'    => __( 'Text Option with Data Attributes', 'redux-framework-demo' ),
                'subtitle' => __( 'You can also pass an options array if you want. Set the default to whatever you like.', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                'data'     => 'post_type',
            ),
            array(
                'id'       => 'opt-multi-text',
                'type'     => 'multi_text',
                'title'    => __( 'Multi Text Option - Color Validated', 'redux-framework-demo' ),
                'validate' => 'color',
                'subtitle' => __( 'If you enter an invalid color it will be removed. Try using the text "blue" as a color.  ;)', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' )
            ),
            array(
                'id'       => 'opt-text-url',
                'type'     => 'text',
                'title'    => __( 'Text Option - URL Validated', 'redux-framework-demo' ),
                'subtitle' => __( 'This must be a URL.', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                'validate' => 'url',
                'default'  => 'http://reduxframework.com',
            ),
            array(
                'id'       => 'opt-text-numeric',
                'type'     => 'text',
                'title'    => __( 'Text Option - Numeric Validated', 'redux-framework-demo' ),
                'subtitle' => __( 'This must be numeric.', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                'validate' => 'numeric',
                'default'  => '0',
            ),
            array(
                'id'       => 'opt-text-comma-numeric',
                'type'     => 'text',
                'title'    => __( 'Text Option - Comma Numeric Validated', 'redux-framework-demo' ),
                'subtitle' => __( 'This must be a comma separated string of numerical values.', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                'validate' => 'comma_numeric',
                'default'  => '0',
            ),
            array(
                'id'       => 'opt-text-no-special-chars',
                'type'     => 'text',
                'title'    => __( 'Text Option - No Special Chars Validated', 'redux-framework-demo' ),
                'subtitle' => __( 'This must be a alpha numeric only.', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                'validate' => 'no_special_chars',
                'default'  => '0'
            ),
            array(
                'id'       => 'opt-text-str_replace',
                'type'     => 'text',
                'title'    => __( 'Text Option - Str Replace Validated', 'redux-framework-demo' ),
                'subtitle' => __( 'You decide.', 'redux-framework-demo' ),
                'desc'     => __( 'This field\'s default value was changed by a filter hook!', 'redux-framework-demo' ),
                'validate' => 'str_replace',
                'str'      => array(
                    'search'      => ' ',
                    'replacement' => 'thisisaspace'
                ),
                'default'  => 'This is the default.'
            ),
            array(
                'id'       => 'opt-text-preg_replace',
                'type'     => 'text',
                'title'    => __( 'Text Option - Preg Replace Validated', 'redux-framework-demo' ),
                'subtitle' => __( 'You decide.', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                'validate' => 'preg_replace',
                'preg'     => array(
                    'pattern'     => '/[^a-zA-Z_ -]/s',
                    'replacement' => 'no numbers'
                ),
                'default'  => '0'
            ),
            array(
                'id'                => 'opt-text-custom_validate',
                'type'              => 'text',
                'title'             => __( 'Text Option - Custom Callback Validated', 'redux-framework-demo' ),
                'subtitle'          => __( 'You decide.', 'redux-framework-demo' ),
                'desc'              => __( 'Enter <code>1</code> and click <strong>Save Changes</strong> for an error message, or enter <code>2</code> and click <strong>Save Changes</strong> for a warning message.', 'redux-framework-demo' ),
                'validate_callback' => 'redux_validate_callback_function',
                'default'           => '0'
            ),
            //array(
            //    'id'                => 'opt-text-custom_validate-class',
            //    'type'              => 'text',
            //    'title'             => __( 'Text Option - Custom Callback Validated - Class', 'redux-framework-demo' ),
            //    'subtitle'          => __( 'You decide.', 'redux-framework-demo' ),
            //    'desc'              => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
            //    'validate_callback' => array( 'Class_Name', 'validate_callback_function' ),
            //    // You can pass the current class
            //    // Or pass the class name and method
            //    //'validate_callback' => array(
            //    //    'Redux_Framework_sample_config',
            //    //    'validate_callback_function'
            //    //),
            //    'default'           => '0'
            //),
            array(
                'id'       => 'opt-textarea-no-html',
                'type'     => 'textarea',
                'title'    => __( 'Textarea Option - No HTML Validated', 'redux-framework-demo' ),
                'subtitle' => __( 'All HTML will be stripped', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                'validate' => 'no_html',
                'default'  => 'No HTML is allowed in here.'
            ),
            array(
                'id'       => 'opt-textarea-html',
                'type'     => 'textarea',
                'title'    => __( 'Textarea Option - HTML Validated', 'redux-framework-demo' ),
                'subtitle' => __( 'HTML Allowed (wp_kses)', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                'validate' => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
                'default'  => 'HTML is allowed in here.'
            ),
            array(
                'id'           => 'opt-textarea-some-html',
                'type'         => 'textarea',
                'title'        => __( 'Textarea Option - HTML Validated Custom', 'redux-framework-demo' ),
                'subtitle'     => __( 'Custom HTML Allowed (wp_kses)', 'redux-framework-demo' ),
                'desc'         => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                'validate'     => 'html_custom',
                'default'      => '<p>Some HTML is allowed in here.</p>',
                'allowed_html' => array(
                    'a'      => array(
                        'href'  => array(),
                        'title' => array()
                    ),
                    'br'     => array(),
                    'em'     => array(),
                    'strong' => array()
                ) //see http://codex.wordpress.org/Function_Reference/wp_kses
            ),
            array(
                'id'       => 'opt-textarea-js',
                'type'     => 'textarea',
                'title'    => __( 'Textarea Option - JS Validated', 'redux-framework-demo' ),
                'subtitle' => __( 'JS will be escaped', 'redux-framework-demo' ),
                'desc'     => __( 'This is the description field, again good for additional info.', 'redux-framework-demo' ),
                'validate' => 'js'
            ),
        )
    ) );

    // -> START Required
    Redux::setSection( $opt_name, array(
        'title'      => __( 'Field Required / Linking', 'redux-framework-demo' ),
        'id'         => 'required',
        'desc'       => __( 'For full documentation on validation, visit: ', 'redux-framework-demo' ) . '<a href="//docs.reduxframework.com/core/the-basics/required/" target="_blank">docs.reduxframework.com/core/the-basics/required/</a>',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'opt-required-basic',
                'type'     => 'switch',
                'title'    => 'Basic Required Example',
                'subtitle' => 'Click <code>On</code> to see the text field appear.',
                'default'  => false
            ),
            array(
                'id'       => 'opt-required-basic-text',
                'type'     => 'text',
                'title'    => 'Basic Text Field',
                'subtitle' => 'This text field is only show when the above switch is set to <code>On</code>, using the <code>required</code> argument.',
                'required' => array( 'opt-required-basic', '=', true )
            ),
            array(
                'id'   => 'opt-required-divide-1',
                'type' => 'divide'
            ),
            array(
                'id'       => 'opt-required-nested',
                'type'     => 'switch',
                'title'    => 'Nested Required Example',
                'subtitle' => 'Click <code>On</code> to see another set of options appear.',
                'default'  => false
            ),
            array(
                'id'       => 'opt-required-nested-buttonset',
                'type'     => 'button_set',
                'title'    => 'Multiple Nested Required Examples',
                'subtitle' => 'Click any buton to show different fields based on their <code>required</code> statements.',
                'options'  => array(
                    'button-text'     => 'Show Text Field',
                    'button-textarea' => 'Show Textarea Field',
                    'button-editor'   => 'Show WP Editor',
                    'button-ace'      => 'Show ACE Editor'
                ),
                'required' => array( 'opt-required-nested', '=', true ),
                'default'  => 'button-text'
            ),
            array(
                'id'       => 'opt-required-nested-text',
                'type'     => 'text',
                'title'    => 'Nested Text Field',
                'required' => array( 'opt-required-nested-buttonset', '=', 'button-text' )
            ),
            array(
                'id'       => 'opt-required-nested-textarea',
                'type'     => 'textarea',
                'title'    => 'Nested Textarea Field',
                'required' => array( 'opt-required-nested-buttonset', '=', 'button-textarea' )
            ),
            array(
                'id'       => 'opt-required-nested-editor',
                'type'     => 'editor',
                'title'    => 'Nested Editor Field',
                'required' => array( 'opt-required-nested-buttonset', '=', 'button-editor' )
            ),
            array(
                'id'       => 'opt-required-nested-ace',
                'type'     => 'ace_editor',
                'title'    => 'Nested ACE Editor Field',
                'required' => array( 'opt-required-nested-buttonset', '=', 'button-ace' )
            ),
            array(
                'id'   => 'opt-required-divide-2',
                'type' => 'divide'
            ),
            array(
                'id'       => 'opt-required-select',
                'type'     => 'select',
                'title'    => 'Select Required Example',
                'subtitle' => 'Select a different option to display its value.  Required may be used to display multiple & reusable fields',
                'options'  => array(
                    'no-sidebar'    => 'No Sidebars',
                    'left-sidebar'  => 'Left Sidebar',
                    'right-sidebar' => 'Right Sidebar',
                    'both-sidebars' => 'Both Sidebars'
                ),
                'default'  => 'no-sidebar',
                'select2'  => array( 'allowClear' => false )
            ),
            array(
                'id'       => 'opt-required-select-left-sidebar',
                'type'     => 'select',
                'title'    => 'Select Left Sidebar',
                'data'     => 'sidebars',
                'default'  => '',
                'required' => array( 'opt-required-select', '=', array( 'left-sidebar', 'both-sidebars' ) )
            ),
            array(
                'id'       => 'opt-required-select-right-sidebar',
                'type'     => 'select',
                'title'    => 'Select Right Sidebar',
                'data'     => 'sidebars',
                'default'  => '',
                'required' => array( 'opt-required-select', '=', array( 'right-sidebar', 'both-sidebars' ) )
            ),
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'WPML Integration', 'redux-framework-demo' ),
        'desc'       => __( 'These fields can be fully translated by WPML (WordPress Multi-Language). This serves as an example for you to implement. For extra details look at our <a href="//docs.reduxframework.com/core/advanced/wpml-integration/" target="_blank">WPML Implementation</a> documentation.', 'redux-framework-demo' ),
        'subsection' => true,
        // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
        'fields'     => array(
            array(
                'id'    => 'wpml-text',
                'type'  => 'textarea',
                'title' => __( 'WPML Text', 'redux-framework-demo' ),
                'desc'  => __( 'This string can be translated via WPML.', 'redux-framework-demo' ),
            ),
            array(
                'id'      => 'wpml-multicheck',
                'type'    => 'checkbox',
                'title'   => __( 'WPML Multi Checkbox', 'redux-framework-demo' ),
                'desc'    => __( 'You can literally translate the values via key.', 'redux-framework-demo' ),
                //Must provide key => value pairs for multi checkbox options
                'options' => array(
                    '1' => 'Option 1',
                    '2' => 'Option 2',
                    '3' => 'Option 3'
                ),
            ),
        )
    ) );

    Redux::setSection( $opt_name, array(
        'icon'            => 'el el-list-alt',
        'title'           => __( 'Customizer Only', 'redux-framework-demo' ),
        'desc'            => __( '<p class="description">This Section should be visible only in Customizer</p>', 'redux-framework-demo' ),
        'customizer_only' => true,
        'fields'          => array(
            array(
                'id'              => 'opt-customizer-only',
                'type'            => 'select',
                'title'           => __( 'Customizer Only Option', 'redux-framework-demo' ),
                'subtitle'        => __( 'The subtitle is NOT visible in customizer', 'redux-framework-demo' ),
                'desc'            => __( 'The field desc is NOT visible in customizer.', 'redux-framework-demo' ),
                'customizer_only' => true,
                //Must provide key => value pairs for select options
                'options'         => array(
                    '1' => 'Opt 1',
                    '2' => 'Opt 2',
                    '3' => 'Opt 3'
                ),
                'default'         => '2'
            ),
        )
    ) );

    if ( file_exists( dirname( __FILE__ ) . '/../README.md' ) ) {
        $section = array(
            'icon'   => 'el el-list-alt',
            'title'  => __( 'Documentation', 'redux-framework-demo' ),
            'fields' => array(
                array(
                    'id'       => '17',
                    'type'     => 'raw',
                    'markdown' => true,
                    'content_path' => dirname( __FILE__ ) . '/../README.md', // FULL PATH, not relative please
                    //'content' => 'Raw content here',
                ),
            ),
        );
        Redux::setSection( $opt_name, $section );
    }
    /*
     * <--- END SECTIONS
     */


    /*
     *
     * YOU MUST PREFIX THE FUNCTIONS BELOW AND ACTION FUNCTION CALLS OR ANY OTHER CONFIG MAY OVERRIDE YOUR CODE.
     *
     */

    /*
    *
    * --> Action hook examples
    *
    */

    // If Redux is running as a plugin, this will remove the demo notice and links
    //add_action( 'redux/loaded', 'remove_demo' );

    // Function to test the compiler hook and demo CSS output.
    // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
    //add_filter('redux/options/' . $opt_name . '/compiler', 'compiler_action', 10, 3);

    // Change the arguments after they've been declared, but before the panel is created
    //add_filter('redux/options/' . $opt_name . '/args', 'change_arguments' );

    // Change the default value of a field after it's been set, but before it's been useds
    //add_filter('redux/options/' . $opt_name . '/defaults', 'change_defaults' );

    // Dynamically add a section. Can be also used to modify sections/fields
    //add_filter('redux/options/' . $opt_name . '/sections', 'dynamic_section');

    /**
     * This is a test function that will let you see when the compiler hook occurs.
     * It only runs if a field    set with compiler=>true is changed.
     * */
    if ( ! function_exists( 'compiler_action' ) ) {
        function compiler_action( $options, $css, $changed_values ) {
            echo '<h1>The compiler hook has run!</h1>';
            echo "<pre>";
            print_r( $changed_values ); // Values that have changed since the last save
            echo "</pre>";
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )
        }
    }

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ) {
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error   = false;
            $warning = false;

            //do your validation
            if ( $value == 1 ) {
                $error = true;
                $value = $existing_value;
            } elseif ( $value == 2 ) {
                $warning = true;
                $value   = $existing_value;
            }

            $return['value'] = $value;

            if ( $error == true ) {
                $return['error'] = $field;
                $field['msg']    = 'your custom error message';
            }

            if ( $warning == true ) {
                $return['warning'] = $field;
                $field['msg']      = 'your custom warning message';
            }

            return $return;
        }
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ) {
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    }

    /**
     * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
     * Simply include this function in the child themes functions.php file.
     * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
     * so you must use get_template_directory_uri() if you want to use any of the built in icons
     * */
    if ( ! function_exists( 'dynamic_section' ) ) {
        function dynamic_section( $sections ) {
            //$sections = array();
            $sections[] = array(
                'title'  => __( 'Section via hook', 'redux-framework-demo' ),
                'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'redux-framework-demo' ),
                'icon'   => 'el el-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }
    }

    /**
     * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
     * */
    if ( ! function_exists( 'change_arguments' ) ) {
        function change_arguments( $args ) {
            //$args['dev_mode'] = true;

            return $args;
        }
    }

    /**
     * Filter hook for filtering the default value of any given field. Very useful in development mode.
     * */
    if ( ! function_exists( 'change_defaults' ) ) {
        function change_defaults( $defaults ) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }
    }

    /**
     * Removes the demo link and the notice of integrated demo from the redux-framework plugin
     */
    if ( ! function_exists( 'remove_demo' ) ) {
        function remove_demo() {
            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                remove_filter( 'plugin_row_meta', array(
                    ReduxFrameworkPlugin::instance(),
                    'plugin_metalinks'
                ), null, 2 );

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
            }
        }
    }

