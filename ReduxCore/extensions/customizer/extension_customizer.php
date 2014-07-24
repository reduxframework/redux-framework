<?php

    /**
     * Redux Framework is free software: you can redistribute it and/or modify
     * it under the terms of the GNU General Public License as published by
     * the Free Software Foundation, either version 2 of the License, or
     * any later version.
     * Redux Framework is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
     * GNU General Public License for more details.
     * You should have received a copy of the GNU General Public License
     * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
     *
     * @package     ReduxFramework
     * @author      Dovy Paukstys (dovy)
     * @version     3.0.0
     */

// Exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

// Don't duplicate me!
    if ( ! class_exists( 'ReduxFramework_extension_customizer' ) ) {

        /**
         * Main ReduxFramework customizer extension class
         *
         * @since       1.0.0
         */
        class ReduxFramework_extension_customizer {

            // Protected vars
            protected $redux;
            private $_extension_url;
            private $_extension_dir;
            private $parent;
            public static $version = "2.0";

            /**
             * Class Constructor. Defines the args for the extions class
             *
             * @since       1.0.0
             * @access      public
             *
             * @param       array $sections   Panel sections.
             * @param       array $args       Class constructor arguments.
             * @param       array $extra_tabs Extra panel tabs.
             *
             * @return      void
             */
            public function __construct( $parent ) {
                //add_action('wp_head', array( $this, '_enqueue_new' ));

                global $pagenow;
                if ( ( $pagenow !== "customize.php" && $pagenow !== "admin-ajax.php" && ! isset( $GLOBALS['wp_customize'] ) ) ) {
                    return;
                }

                $this->parent = $parent;

                if ( empty( $this->_extension_dir ) ) {
                    $this->_extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
                    $this->_extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->_extension_dir ) );
                }

                // Create defaults array
                $defaults = array();
                /*
                  customize_controls_init
                  customize_controls_enqueue_scripts
                  customize_controls_print_styles
                  customize_controls_print_scripts
                  customize_controls_print_footer_scripts
                 */

                if ( ! isset( $_POST['customized'] ) || $pagenow == "admin-ajax.php" ) {
                    if (  current_user_can ( $this->parent->args['page_permissions'])) {
                        add_action( 'customize_register', array($this, '_register_customizer_controls' ) ); // Create controls
                    }
                }

                if ( isset( $_POST['customized'] ) ) {
                    if ( $pagenow == "admin-ajax.php" && $_POST['action'] == 'customize_save' ) {
                        //$this->parent->
                    }
                    add_action( "redux/options/{$this->parent->args['opt_name']}/options", array( $this, '_override_values' ), 100 );
                    add_action( 'customize_save', array( $this, 'customizer_save_before' ) ); // Before save
                    add_action( 'customize_save_after', array( &$this, 'customizer_save_after' ) ); // After save
                    add_action( 'wp_head', array( $this, 'customize_preview_init' ) );
                }


                //add_action( 'wp_enqueue_scripts', array( &$this, '_enqueue_previewer_css' ) ); // Enqueue previewer css
                //add_action( 'wp_enqueue_scripts', array( &$this, '_enqueue_previewer_js' ) ); // Enqueue previewer javascript
                //add_action( "wp_footer", array( $this, '_enqueue_new' ), 100 );
                //$this->_enqueue_new();
            }

            function customize_preview_init() {
                do_action( 'redux/customizer/live_preview' );
            }

            public function _override_values( $data ) {
                if ( isset( $_POST['customized'] ) ) {
                    $this->orig_options = $this->parent->options;
                    $options            = json_decode( stripslashes_deep( $_POST['customized'] ), true );
                    if( !empty( $options ) && is_array( $options ) ){
                        foreach ( $options as $key => $value ) {
                            if ( strpos( $key, $this->parent->args['opt_name'] ) !== false ) {
                                $key                                                       = str_replace( $this->parent->args['opt_name'] . '[', '', rtrim( $key, "]" ) );
                                $data[ $key ]                                              = $value;
                                $GLOBALS[ $this->parent->args['global_variable'] ][ $key ] = $value;
                                $this->parent->options[ $key ]                             = $value;
                            }
                        }  
                    }
                    
                }

                return $data;
            }

            public function _enqueue_new() {
                //echo "<script type='text/javascript' src='".$this->_extension_url . 'new/codemirror.min.js'."'></script>";
                //echo "<script type='text/javascript' src='".$this->_extension_url . 'new/colors-control.js'."'></script>";
                //echo "<script type='text/javascript' src='".$this->_extension_url . 'new/customizer-control.js'."'></script>";
                //echo "<script type='text/javascript' src='".$this->_extension_url . 'new/fonts-customizer-admin.js'."'></script>";
                //echo "<script type='text/javascript' src='".$this->_extension_url . 'new/header-control.js'."'></script>";
                //echo "<script type='text/javascript' src='".$this->_extension_url . 'new/header-models.js'."'></script>";
                //echo "<script type='text/javascript' src='".$this->_extension_url . 'new/jquery.slimscroll.js'."'></script>";
                //echo "<script type='text/javascript' src='".$this->_extension_url . 'new/jquery.ui.droppable.min.js'."'></script>";
                //echo "<script type='text/javascript' src='".$this->_extension_url . 'new/media-editor.min.js'."'></script>";
                //echo "<script type='text/javascript' src='".$this->_extension_url . 'new/new-customizer.js'."'></script>";
                //echo "<script type='text/javascript' src='".$this->_extension_url . 'new/previewing.js'."'></script>";
                //echo "<script type='text/javascript' src='".$this->_extension_url . 'new/theme-customizer.js'."'></script>";

                /*
                  wp_enqueue_script('redux-extension-customizer-codemirror-js', $this->_extension_url . 'new/codemirror.min.js');
                  wp_enqueue_script('redux-extension-customizer-color-js', $this->_extension_url . 'new/colors-control.js');
                  wp_enqueue_script('redux-extension-customizer-controls-js', $this->_extension_url . 'new/customizer-control.js');
                  wp_enqueue_script('redux-extension-customizer-fonts-js', $this->_extension_url . 'new/fonts-customizer-admin.js');
                  wp_enqueue_script('redux-extension-customizer-header-js', $this->_extension_url . 'new/header-control.js');
                  wp_enqueue_script('redux-extension-customizer-models-js', $this->_extension_url . 'new/header-models.js');
                  wp_enqueue_script('redux-extension-customizer-slimscroll-js', $this->_extension_url . 'new/jquery.slimscroll.js');
                  wp_enqueue_script('redux-extension-customizer-droppable-js', $this->_extension_url . 'new/jquery.ui.droppable.min.js');
                  wp_enqueue_script('redux-extension-customizer-editor-js', $this->_extension_url . 'new/media-editor.min.js');
                  wp_enqueue_script('redux-extension-customizer-new-js', $this->_extension_url . 'new/new-customizer.js');
                  wp_enqueue_script('redux-extension-customizer-previewing-js', $this->_extension_url . 'new/previewing.js');
                  wp_enqueue_script('redux-extension-customizer-theme-js', $this->_extension_url . 'new/theme-customizer.js');
                 */
            }

            // All sections, settings, and controls will be added here
            public function _register_customizer_controls( $wp_customize ) {
                $order    = array(
                    'heading' => - 500,
                    'option'  => - 500,
                );
                $defaults = array(
                    'default-color'          => '',
                    'default-image'          => '',
                    'wp-head-callback'       => '',
                    'admin-head-callback'    => '',
                    'admin-preview-callback' => ''
                );

                foreach ( $this->parent->sections as $key => $section ) {

                    // Not a type that should go on the customizer
                    if ( empty( $section['fields'] ) || ( isset( $section['type'] ) && $section['type'] == "divide" ) ) {
                        continue;
                    }

                    // If section customizer is set to false
                    if ( isset( $section['customizer'] ) && $section['customizer'] === false ) {
                        continue;
                    }

                    // Evaluate section permissions
                    if ( isset( $section['permissions'] ) ) {
                        if ( ! current_user_can( $section['permissions'] ) ) {
                            continue;
                        }
                    }
                    
                    // No errors please
                    if ( ! isset( $section['desc'] ) ) {
                        $section['desc'] = "";
                    }

                    // Fill the description if there is a subtitle
                    if ( empty( $section['desc'] ) && ! empty( $section['subtitle'] ) ) {
                        $section['desc'] = $section['subtitle'];
                    }

                    // Let's make a section ID from the title
                    if ( empty( $section['id'] ) ) {
                        $section['id'] = strtolower( str_replace( " ", "", $section['title'] ) );
                    }
                    
                    // No title is present, let's show what section is missing a title
                    if ( ! isset( $section['title'] ) ) {
                        print_r( $section );
                    }
                    
                    // Let's set a default priority
                    if ( empty( $section['priority'] ) ) {
                        $section['priority'] = $order['heading'];
                        $order['heading'] ++;
                    }

                    $wp_customize->add_section( $section['id'], array(
                        'title'       => $section['title'],
                        'priority'    => $section['priority'],
                        'description' => $section['desc']
                    ) );


                    foreach ( $section['fields'] as $skey => $option ) {
                        
                        // Evaluate section permissions
                        if ( isset( $option['permissions'] ) ) {
                            if ( ! current_user_can( $option['permissions'] ) ) {
                                continue;
                            }
                        }
                        
                        if ( isset( $option['customizer'] ) && $option['customizer'] === false ) {
                            continue;
                        }
                        if ( $this->parent->args['customizer'] === false && ( ! isset( $option['customizer'] ) || $option['customizer'] !== true ) ) {
                            continue;
                        }

                        //Change the item priority if not set
                        if ( $option['type'] != 'heading' && ! isset( $option['priority'] ) ) {
                            $option['priority'] = $order['option'];
                            $order['option'] ++;
                        }

                        if ( ! empty( $this->options_defaults[ $option['id'] ] ) ) {
                            $option['default'] = $this->options_defaults['option']['id'];
                        }

                        //$option['id'] = $this->parent->args['opt_name'].'['.$option['id'].']';
                        //echo $option['id'];

                        if ( ! isset( $option['default'] ) ) {
                            $option['default'] = "";
                        }
                        if ( ! isset( $option['title'] ) ) {
                            $option['title'] = "";
                        }

                        // Wordpress doesn't support multi-select
                        if ( $option['type'] == "select" && isset( $option['multi'] ) && $option['multi'] == true ) {
                            continue;
                        }

                        $customSetting = array(
                            'default'        => $option['default'],
                            'type'           => 'option',
                            'capabilities'   => 'edit_theme_options',
                            //'capabilities'   => $this->parent->args['page_permissions'],
                            'transport'      => 'refresh',
                            'theme_supports' => '',
                            //'sanitize_callback' => array( $this, '_field_validation' ),
                            //'sanitize_js_callback' =>array( &$parent, '_field_input' ),
                        );


                        $option['id'] = $this->parent->args['opt_name'] . '[' . $option['id'] . ']';

                        if ( $option['type'] != "heading" || ! empty( $option['type'] ) ) {
                            $wp_customize->add_setting( $option['id'], $customSetting );
                        }

                        if ( ! empty( $option['data'] ) && empty( $option['options'] ) ) {
                            if ( empty( $option['args'] ) ) {
                                $option['args'] = array();
                            }

                            if ( $option['data'] == "elusive-icons" || $option['data'] == "elusive-icon" || $option['data'] == "elusive" ) {
                                $icons_file = ReduxFramework::$_dir . 'inc/fields/select/elusive-icons.php';
                                $icons_file = apply_filters( 'redux-font-icons-file', $icons_file );

                                if ( file_exists( $icons_file ) ) {
                                    require_once $icons_file;
                                }
                            }
                            $option['options'] = $this->parent->get_wordpress_data( $option['data'], $option['args'] );
                        }

                        switch ( $option['type'] ) {
                            case 'heading':
                                // We don't want to put up the section unless it's used by something visible in the customizer
                                $section          = $option;
                                $section['id']    = strtolower( str_replace( " ", "", $option['title'] ) );
                                $order['heading'] = - 500;

                                if ( ! empty( $option['priority'] ) ) {
                                    $section['priority'] = $option['priority'];
                                } else {
                                    $section['priority'] = $order['heading'];
                                    $order['heading'] ++;
                                }
                                break;

                            case 'text':
                                if ( isset( $option['data'] ) && $option['data'] ) {
                                    continue;
                                }
                                $wp_customize->add_control( $option['id'], array(
                                    'label'    => $option['title'],
                                    'section'  => $section['id'],
                                    'settings' => $option['id'],
                                    'priority' => $option['priority'],
                                    'type'     => 'text',
                                ) );
                                break;

                            case 'select':
                            case 'button_set':
                                if ( ( isset( $option['sortable'] ) && $option['sortable'] ) ) {
                                    continue;
                                }
                                $wp_customize->add_control( $option['id'], array(
                                    'label'    => $option['title'],
                                    'section'  => $section['id'],
                                    'settings' => $option['id'],
                                    'priority' => $option['priority'],
                                    'type'     => 'select',
                                    'choices'  => $option['options']
                                ) );
                                break;

                            case 'radio':
                                //continue;
                                $wp_customize->add_control( $option['id'], array(
                                    'label'    => $option['title'],
                                    'section'  => $section['id'],
                                    'settings' => $option['id'],
                                    'priority' => $option['priority'],
                                    'type'     => 'radio',
                                    'choices'  => $option['options']
                                ) );
                                break;

                            case 'checkbox':
                                if ( ( isset( $option['data'] ) && $option['data'] ) || ( ( isset( $option['multi'] ) && $option['multi'] ) ) || ( ( isset( $option['options'] ) && ! empty( $option['options'] ) ) ) ) {
                                    continue;
                                }
                                $wp_customize->add_control( $option['id'], array(
                                    'label'    => $option['title'],
                                    'section'  => $section['id'],
                                    'settings' => $option['id'],
                                    'priority' => $option['priority'],
                                    'type'     => 'checkbox',
                                ) );
                                break;

                            case 'media':
                                continue;
                                $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $option['id'], array(
                                    'label'    => $option['title'],
                                    'section'  => $section['id'],
                                    'settings' => $option['id'],
                                    'priority' => $option['priority']
                                ) ) );
                                break;

                            case 'color':
                                $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $option['id'], array(
                                    'label'    => $option['title'],
                                    'section'  => $section['id'],
                                    'settings' => $option['id'],
                                    'priority' => $option['priority']
                                ) ) );
                                break;

                            case 'switch':
                                continue;
                                $wp_customize->add_control( new Redux_customizer_switch( $wp_customize, $option['id'], array(
                                    'label'          => $option['title'],
                                    'section'        => $section['id'],
                                    'settings'       => $option['id'],
                                    'field'          => $option,
                                    'ReduxFramework' => $this->parent,
                                    'priority'       => $option['priority'],
                                ) ) );

                                break;

                            default:
                                break;
                        }
                    }
                }

                /*
                  title_tagline - Site Title & Tagline
                  colors - Colors
                  header_image - Header Image
                  background_image - Background Image
                  nav - Navigation
                  static_front_page - Static Front Page
                 */
            }

            public function customizer_save_before( $plugin_options ) {
                $this->before_save = $this->parent->options;
                //$parent->_field_input( $plugin_options );
            }

            public function customizer_save_after( $wp_customize ) {
                //if( isset( $_POST['customized'] ) ) {
                $options  = json_decode( stripslashes_deep( $_POST['customized'] ), true );
                $compiler = false;
                $changed  = array();

                foreach ( $options as $key => $value ) {
                    if ( strpos( $key, $this->parent->args['opt_name'] ) !== false ) {
                        $key = str_replace( $this->parent->args['opt_name'] . '[', '', rtrim( $key, "]" ) );

                        if ( ! isset( $this->orig_options[ $key ] ) || $this->orig_options[ $key ] != $value || ( isset( $this->orig_options[ $key ] ) && ! empty( $this->orig_options[ $key ] ) && empty( $value ) ) ) {
                            $changed[ $key ] = $value;
                            if ( isset( $this->parent->compiler_fields[ $key ] ) ) {
                                $compiler = true;
                            }
                        }
                    }
                }

                if ( ! empty( $changed ) ) {
                    setcookie( "redux-saved-{$this->parent->args['opt_name']}", 1, time() + 1000, "/" );
                }

                if ( $compiler ) {
                    // Have to set this to stop the output of the CSS and typography stuff.
                    $this->parent->no_output = true;
                    $this->parent->_enqueue_output();
                    do_action( "redux/options/{$this->parent->args['opt_name']}/compiler", $this->parent->options, $this->parent->compilerCSS );
                }

                //}
                //      print_r($wp_customize);
                //exit();
                //return $wp_customize;
            }

            /**
             * Enqueue CSS/JS for preview pane
             *
             * @since       1.0.0
             * @access      public
             * @global      $wp_styles
             * @return      void
             */
            public function _enqueue_previewer() {
                wp_enqueue_script(
                    'redux-extension-previewer-js',
                    $this->_extension_url . 'assets/js/preview.js'
                );

                $localize = array(
                    'save_pending'   => __( 'You have changes that are not saved. Would you like to save them now?', 'redux-framework' ),
                    'reset_confirm'  => __( 'Are you sure? Resetting will lose all custom values.', 'redux-framework' ),
                    'preset_confirm' => __( 'Your current options will be replaced with the values of this preset. Would you like to proceed?', 'redux-framework' ),
                    'opt_name'       => $this->args['opt_name'],
                    //'folds'             => $this->folds,
                    'options'        => $this->parent->options,
                    'defaults'       => $this->parent->options_defaults,
                );

                wp_localize_script(
                    'redux-extension-previewer-js',
                    'reduxPost',
                    $localize
                );
            }

            /**
             * Enqueue CSS/JS for the customizer controls
             *
             * @since       1.0.0
             * @access      public
             * @global      $wp_styles
             * @return      void
             */
            public function _enqueue() {
                global $wp_styles;

                //wp_enqueue_style( 'wp-pointer' );
                //wp_enqueue_script( 'wp-pointer' );
                // Remove when code is in place!
                //wp_enqueue_script('redux-extension-customizer-js', $this->_extension_url . 'assets/js/customizer.js');
                // Get styles
                //wp_enqueue_style('redux-extension-customizer-css', $this->_extension_url . 'assets/css/customizer.css');

                $localize = array(
                    'save_pending'   => __( 'You have changes that are not saved.  Would you like to save them now?', 'redux-framework' ),
                    'reset_confirm'  => __( 'Are you sure?  Resetting will lose all custom values.', 'redux-framework' ),
                    'preset_confirm' => __( 'Your current options will be replaced with the values of this preset.  Would you like to proceed?', 'redux-framework' ),
                    'opt_name'       => $this->args['opt_name'],
                    //'folds'             => $this->folds,
                    'field'          => $this->parent->options,
                    'defaults'       => $this->parent->options_defaults,
                );

                // Values used by the javascript
                wp_localize_script(
                    'redux-js',
                    'redux_opts',
                    $localize
                );

                do_action( 'redux-enqueue-' . $this->args['opt_name'] );

                foreach ( $this->sections as $section ) {
                    if ( isset( $section['fields'] ) ) {
                        foreach ( $section['fields'] as $field ) {
                            if ( isset( $field['type'] ) ) {
                                $field_class = 'ReduxFramework_' . $field['type'];

                                if ( ! class_exists( $field_class ) ) {
                                    $class_file = apply_filters( 'redux-typeclass-load', $this->path . 'inc/fields/' . $field['type'] . '/field_' . $field['type'] . '.php', $field_class );
                                    if ( $class_file ) {
                                        /** @noinspection PhpIncludeInspection */
                                        require_once( $class_file );
                                    }
                                }

                                if ( class_exists( $field_class ) && method_exists( $field_class, 'enqueue' ) ) {
                                    $enqueue = new $field_class( '', '', $this );
                                    $enqueue->enqueue();
                                }
                            }
                        }
                    }
                }
            }

            /**
             * Register Option for use
             *
             * @since       1.0.0
             * @access      public
             * @return      void
             */
            public function _register_setting() {

            }

            /**
             * Validate the options before insertion
             *
             * @since       3.0.0
             * @access      public
             *
             * @param       array $plugin_options The options array
             *
             * @return
             */
            public function _field_validation( $plugin_options, $two ) {
                echo "dovy";
                echo $two;

                return $plugin_options;

                return $this->parent->_validate_options( $plugin_options );
            }

            /**
             * HTML OUTPUT.
             *
             * @since       1.0.0
             * @access      public
             * @return      void
             */
            public function _customizer_html_output() {

            }
        } // class
    } // if
