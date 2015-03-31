<?php

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    
    if (!class_exists('ReduxAdminNoticeOptions')) {
        class ReduxAdminNoticeOptions {
            public $opt_name = 'redux_notice_admin';
            
            public function __construct () {
                $this->setArgs();
                $this->setSections();
                
                add_filter('redux/options/' . $this->opt_name . '/compiler', array($this, 'set_json_file'), 10, 3);
            }

            function set_json_file($options, $css, $changed){
                $arr = array(
                    'type'      => !empty($options['opt-message-type']) ? $options['opt-message-type'] : 'updated' ,
                    'title'     => !empty ($options['opt-message-title']) ? $options['opt-message-title']: 'NewsFlash From Redux!',
                    'message'   => $options['opt-message']
                );
                
                $json = json_encode($arr);
                
                $redux = ReduxFrameworkInstances::get_instance($this->opt_name);
                
                $params = array(
                    'content' => $json
                );
                
                $redux->filesystem->execute('put_contents', ReduxFramework::$_upload_dir . 'redux_notice.json', $params);
            }    
            
            
            private function setArgs() {
                $args = array(
                    // TYPICAL -> Change these values as you need/desire
                    'opt_name'           => $this->opt_name,
                    // This is where your data is stored in the database and also becomes your global variable name.
                    'display_name'       => 'Redux Notice Editor',
                    // Name that appears at the top of your panel
                    //'display_version'   => '1.0.3',  // Version that appears at the top of your panel
                    'menu_type'          => 'menu',
                    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                    'allow_sub_menu'     => true,
                    // Show the sections below the admin menu item or not
                    'menu_title'         => __( 'Redux Notice Editor', 'redux-framework-demo' ),
                    'page_title'         => __( 'Redux Notice Editor', 'redux-framework-demo' ),
                    // You will need to generate a Google API key to use this feature.
                    // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                    'google_api_key'     => '1',
                    // Must be defined to add google fonts to the typography module

                    'async_typography'   => false,
                    // Use a asynchronous font on the front end or font string
                    'admin_bar'          => true,
                    // Show the panel pages on the admin bar
                    'global_variable'    => '',
                    // Set a different name for your global variable other than the opt_name
                    'dev_mode'           => false,
                    // Show the time the page took to load, etc
                    'customizer'         => true,
                    // Enable basic customizer support

                    // OPTIONAL -> Give you extra features
                    'page_priority'      => null,
                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                    'page_parent'        => 'themes.php',
                    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                    'page_permissions'   => 'manage_options',
                    // Permissions needed to access the options panel.
                    'menu_icon'          => '',
                    // Specify a custom URL to an icon
                    'last_tab'           => '',
                    // Force your panel to always open to a specific tab (by id)
                    'page_icon'          => 'icon-themes',
                    // Icon displayed in the admin panel next to your menu_title
                    'page_slug'          => 'redux_notice_admin',
                    // Page slug used to denote the panel
                    'save_defaults'      => true,
                    // On load save the defaults to DB before user clicks save or not
                    'default_show'       => false,
                    // If true, shows the default value next to each field that is not the default value.
                    'default_mark'       => '',
                    // What to print by the field's title if the value shown is default. Suggested: *
                    'show_import_export' => false,
                    // Shows the Import/Export panel when not used as a field.
                    'open_expanded'      => false,
                    // CAREFUL -> These options are for advanced use only
                    'transient_time'     => 60 * MINUTE_IN_SECONDS,
                    'output'             => true,
                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                    'output_tag'         => true,
                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                    // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

                    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                    'database'           => '',
                    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                    'system_info'        => false,
                    // REMOVE
                    'update_notice'      => false,
                    'font_control'       => false,
                    // HINTS
                    'hints'              => array(
                        'icon'          => 'icon-question-sign',
                        'icon_position' => 'right',
                        'icon_color'    => 'lightgray',
                        'icon_size'     => 'normal',
                        'tip_style'     => array(
                            'color'   => 'light',
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


                // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
                $args['share_icons'][] = array(
                    'url'   => 'https://github.com/reduxframework/redux-framework',
                    'title' => 'GitHub',
                    'icon'  => 'el el-github'
                    //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
                );
                $args['share_icons'][] = array(
                    'url'   => 'https://www.facebook.com/reduxframework',
                    'title' => 'Facebook',
                    'icon'  => 'el el-facebook'
                );
                $args['share_icons'][] = array(
                    'url'   => 'http://twitter.com/reduxframework',
                    'title' => 'Twitter',
                    'icon'  => 'el el-twitter'
                );

                // Panel Intro text -> before the form
                if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
                    if ( ! empty( $args['global_variable'] ) ) {
                        $v = $args['global_variable'];
                    } else {
                        $v = str_replace( '-', '_', $args['opt_name'] );
                    }
                    $args['intro_text'] = '';// 'Redux Extension demonstrations.  For a complete list of Redux extensions, please visit the <a href="http://reduxframework.com/extensions/">Redux Extensions page</a>.';
                } else {
                    $args['intro_text'] = __( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'redux-framework-demo' );
                }

                // Add content after the form.
                //$args['footer_text'] = '<center>You are currently viewing demos of the Redux Color Scheme, Social Profiles, Date/Time Picker, Spectrum Color Picker, CSS Layout, Multi Media Selector, and JS Button extensions.<br/>Like what you see?  Click a link to purchase: <a href="http://reduxframework.com/extension/color-schemes" title="Color Schemes" target="_blank">Color Schemes</a> | <a href="http://reduxframework.com/extension/social-profiles" title="Social Profiles" target="_blank">Social Profiles</a> | <a href="http://reduxframework.com/extension/date-time-picker" title="Date/Time Picker" target="_blank">Date/Time Picker</a> | <a href="http://reduxframework.com/extension/spectrum-color-picker" title="Spectrum Color Picker" target="_blank">Spectrum Color Picker</a> | <a href="http://reduxframework.com/extension/css-layout" title="CSS Layout" target="_blank">CSS Layout</a> | <a href="http://reduxframework.com/extension/multi-media" title="Multi Media Selector" target="_blank">Multi Media Selector</a> | <a href="http://reduxframework.com/extension/js-button" title="JS Button" target="_blank">JS Button</a> </center>';

                //$args = apply_filters( "extensions/args", $args );
                Redux::setArgs( $this->opt_name, $args );
            }

            private function setSections() {
                $section = array(
                    'title'  => __( 'Admin Notice Editor', 'redux-framework-demo' ),
                    'heading' => __('Redux Framework Admin Notice Editor', 'redux-framework-demo'),
                    'icon'   => 'el-icon-cogs',
                    'fields' => array(
                        array(
                            'id'        => 'opt-message-type',
                            'type'      => 'select',
                            'title'     => 'Message Type',
                            'subtitle'  => 'Determines the type of admin notice style to display.',
                            'options'   => array(
                                'updated'   => 'Updated (Green)',
                                'error'     => 'Error (Red)',
                                'update-nag' => 'Nag (Orange)',
                                'notice'    => 'Notice (No Color)',
                                'redux-message'     => 'Redux Blue',
                            ),
                            'default'   => 'updated',
                            'compiler'  => true
                        ),
                        array(
                            'id'        => 'opt-message-title',
                            'type'      => 'textarea',
                            'title'     => 'Message Title',
                            'subtitle'  => 'The title of the admin notice.',
                            'default'   => '<strong>Newsflash From Redux!</strong><br/>',
                            'compiler'  => true
                        ),
                        array(
                            'id'        => 'opt-message',
                            'type'      => 'textarea',
                            'title'     => 'Notice Message',
                            'subtitle'  => 'Message to display in the admin notice.',
                            'desc'      => 'Leave this field blank to show no admin message.',
                            'compiler'  => true,
                            'default'   => ''
                        ),
                    )
                );
                Redux::setSection($this->opt_name, $section);
            }            
        }
        
        new ReduxAdminNoticeOptions();
    }