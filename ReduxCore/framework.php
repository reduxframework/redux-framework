<?php

    /**
     * Redux Framework is free software: you can redistribute it and/or modify
     * it under the terms of the GNU General Public License as published by
     * the Free Software Foundation, either version 3 of the License, or
     * any later version.
     * Redux Framework is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
     * GNU General Public License for more details.
     * You should have received a copy of the GNU General Public License
     * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
     *
     * @package     Redux_Framework
     * @subpackage  Core
     * @author      Redux Framework Team
     */

    // Exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    // Fix for the GT3 page builder: http://www.gt3themes.com/wordpress-gt3-page-builder-plugin/
    /** @global string $pagenow */
    if ( has_action( 'ecpt_field_options_' ) ) {
        global $pagenow;
        if ( $pagenow === 'admin.php' ) {
            /** @noinspection PhpUndefinedCallbackInspection */
            remove_action( 'admin_init', 'pb_admin_init' );
        }
    }

    if ( ! class_exists( 'ReduxFrameworkInstances' ) ) {
        // Instance Container
        include_once( dirname( __FILE__ ) . '/inc/class.redux_instances.php' );
        include_once( dirname( __FILE__ ) . '/inc/lib.redux_instances.php' );

    }

    if ( class_exists( 'ReduxFrameworkInstances' ) ) {
        add_action( 'redux/init', 'ReduxFrameworkInstances::get_instance' );
    }

    // Don't duplicate me!
    if ( ! class_exists( 'ReduxFramework' ) ) {

        // General helper functions
        include_once( dirname( __FILE__ ) . '/inc/class.redux_helpers.php' );

        // General functions
        include_once( dirname( __FILE__ ) . '/inc/class.redux_functions.php' );

        include_once( dirname( __FILE__ ) . '/inc/class.redux_filesystem.php' );
        
        /**
         * Main ReduxFramework class
         *
         * @since       1.0.0
         */
        class ReduxFramework {

            // ATTENTION DEVS
            // Please update the build number with each push, no matter how small.
            // This will make for easier support when we ask users what version they are using.
            public static $_version = '3.3.5.6';
            public static $_dir;
            public static $_url;
            public static $_upload_dir;
            public static $_upload_url;
            public static $wp_content_url;
            public static $base_wp_content_url;
            public static $_is_plugin = true;
            public static $_as_plugin = false;

            public static function init() {

                // Windows-proof constants: replace backward by forward slashes. Thanks to: @peterbouwmeester
                self::$_dir           = trailingslashit( Redux_Helpers::cleanFilePath( dirname( __FILE__ ) ) );
                $wp_content_dir       = trailingslashit( Redux_Helpers::cleanFilePath( WP_CONTENT_DIR ) );
                $wp_content_dir       = trailingslashit( str_replace( '//', '/', $wp_content_dir ) );
                $relative_url         = str_replace( $wp_content_dir, '', self::$_dir );
                self::$wp_content_url = trailingslashit( Redux_Helpers::cleanFilePath( ( is_ssl() ? str_replace( 'http://', 'https://', WP_CONTENT_URL ) : WP_CONTENT_URL ) ) );
                self::$_url           = self::$wp_content_url . $relative_url;

                // See if Redux is a plugin or not
                if ( strpos( Redux_Helpers::cleanFilePath( __FILE__ ), Redux_Helpers::cleanFilePath( get_stylesheet_directory() ) ) !== false ) {
                    self::$_is_plugin = false;
                }
            }

            // ::init()

            public $framework_url = 'http://www.reduxframework.com/';
            public static $instance = null;
            public $admin_notices = array();
            public $page = '';
            public $saved = false;
            public $fields = array(); // Fields by type used in the panel
            public $current_tab = ''; // Current section to display, cookies
            public $extensions = array(); // Extensions by type used in the panel
            public $sections = array(); // Sections and fields
            public $errors = array(); // Errors
            public $warnings = array(); // Warnings
            public $options = array(); // Option values
            public $options_defaults = null; // Option defaults
            public $notices = array(); // Option defaults
            public $compiler_fields = array(); // Fields that trigger the compiler hook
            public $required = array(); // Information that needs to be localized
            public $required_child = array(); // Information that needs to be localized
            public $localize_data = array(); // Information that needs to be localized
            public $fonts = array(); // Information that needs to be localized
            public $folds = array(); // The itms that need to fold.
            public $path = '';
            public $changed_values = array(); // Values that have been changed on save. Orig values.
            public $output = array(); // Fields with CSS output selectors
            public $outputCSS = null; // CSS that get auto-appended to the header
            public $compilerCSS = null; // CSS that get sent to the compiler hook
            public $customizerCSS = null; // CSS that goes to the customizer
            public $fieldsValues = array(); //all fields values in an id=>value array so we can check dependencies
            public $fieldsHidden = array(); //all fields that didn't pass the dependency test and are hidden
            public $toHide = array(); // Values to hide on page load
            public $typography = null; //values to generate google font CSS
            public $import_export = null;
            public $debug = null;
            private $show_hints = false;
            private $hidden_perm_fields = array(); //  Hidden fields specified by 'permissions' arg.
            private $hidden_perm_sections = array(); //  Hidden sections specified by 'permissions' arg.
            public $typography_preview = array();
            public $args = array();
            public $filesystem = null;

            /**
             * Class Constructor. Defines the args for the theme options class
             *
             * @since       1.0.0
             *
             * @param       array $sections   Panel sections.
             * @param       array $args       Class constructor arguments.
             * @param       array $extra_tabs Extra panel tabs. // REMOVE
             *
             * @return \ReduxFramework
             */
            public function __construct( $sections = array(), $args = array(), $extra_tabs = array() ) {

                // Disregard WP AJAX 'heartbeat'call.  Why waste resources?
                if ( isset( $_POST ) && isset( $_POST['action'] ) && $_POST['action'] == 'heartbeat' ) {

                    // Hook, for purists.
                    if ( ! has_action( 'redux/ajax/heartbeat' ) ) {
                        do_action( 'redux/ajax/heartbeat', $this );
                    }

                    // Buh bye!
                    return;
                }

                // Pass parent pointer to function helper.
                Redux_Functions::$_parent = $this;

                // Set values
                $this->set_default_args();
                $this->args = wp_parse_args( $args, $this->args );

//                logconsole('post', $_GET['page']);
//                // Getting started page
//                if (  is_admin () && $this->args['dev_mode'] ) {
//                    if ($_GET['page'] != 'redux-about') {
//                        logconsole('welcome');
//                        include_once( dirname( __FILE__ ) . '/inc/welcome.php' );
//
//                        update_option( 'redux_version_upgraded_from', self::$_version );
//
//                        set_transient( '_redux_activation_redirect', true, 30 );
//                    }                    
//                }
                
                if ( empty( $this->args['transient_time'] ) ) {
                    $this->args['transient_time'] = 60 * MINUTE_IN_SECONDS;
                }

                if ( empty( $this->args['footer_credit'] ) ) {
                    $this->args['footer_credit'] = '<span id="footer-thankyou">' . sprintf( __( 'Options panel created using %1$s', 'redux-framework' ), '<a href="' . esc_url( $this->framework_url ) . '" target="_blank">' . __( 'Redux Framework', 'redux-framework' ) . '</a> v' . self::$_version ) . '</span>';
                }

                if ( empty( $this->args['menu_title'] ) ) {
                    $this->args['menu_title'] = __( 'Options', 'redux-framework' );
                }

                if ( empty( $this->args['page_title'] ) ) {
                    $this->args['page_title'] = __( 'Options', 'redux-framework' );
                }

                /**
                 * filter 'redux/args/{opt_name}'
                 *
                 * @param  array $args ReduxFramework configuration
                 */
                $this->args = apply_filters( "redux/args/{$this->args['opt_name']}", $this->args );

                /**
                 * filter 'redux/options/{opt_name}/args'
                 *
                 * @param  array $args ReduxFramework configuration
                 */
                $this->args = apply_filters( "redux/options/{$this->args['opt_name']}/args", $this->args );

                if ( ! empty( $this->args['opt_name'] ) ) {
                    /**
                     * SHIM SECTION
                     * Old variables and ways of doing things that need correcting.  ;)
                     **/
                    // Variable name change
                    if ( ! empty( $this->args['page_cap'] ) ) {
                        $this->args['page_permissions'] = $this->args['page_cap'];
                        unset( $this->args['page_cap'] );
                    }

                    if ( ! empty( $this->args['page_position'] ) ) {
                        $this->args['page_priority'] = $this->args['page_position'];
                        unset( $this->args['page_position'] );
                    }

                    if ( ! empty( $this->args['page_type'] ) ) {
                        $this->args['menu_type'] = $this->args['page_type'];
                        unset( $this->args['page_type'] );
                    }

                    // Get rid of extra_tabs! Not needed.
                    if ( is_array( $extra_tabs ) && ! empty( $extra_tabs ) ) {
                        foreach ( $extra_tabs as $tab ) {
                            array_push( $this->sections, $tab );
                        }
                    }

                    // Move to the first loop area!
                    /**
                     * filter 'redux-sections'
                     *
                     * @deprecated
                     *
                     * @param  array $sections field option sections
                     */
                    $this->sections = apply_filters( 'redux-sections', $sections ); // REMOVE LATER
                    /**
                     * filter 'redux-sections-{opt_name}'
                     *
                     * @deprecated
                     *
                     * @param  array $sections field option sections
                     */
                    $this->sections = apply_filters( "redux-sections-{$this->args['opt_name']}", $this->sections ); // REMOVE LATER
                    /**
                     * filter 'redux/options/{opt_name}/sections'
                     *
                     * @param  array $sections field option sections
                     */
                    $this->sections = apply_filters( "redux/options/{$this->args['opt_name']}/sections", $this->sections );

                    /**
                     * Construct hook
                     * action 'redux/construct'
                     *
                     * @param object $this ReduxFramework
                     */
                    do_action( 'redux/construct', $this );

                    $this->filesystem = new Redux_Filesystem( $this );

                    //set redux upload folder
                    $this->set_redux_content();

                    // Set the default values
                    $this->_default_cleanup();

                    // Internataionalization 
                    $this->_internationalization();

                    // Register extra extensions
                    $this->_register_extensions();

                    // Grab database values
                    $this->get_options();

                    // Tracking
                    $this->_tracking();

                    // Set option with defaults
                    //add_action( 'init', array( &$this, '_set_default_options' ), 101 );

                    // Options page
                    add_action( 'admin_menu', array( $this, '_options_page' ) );

                    // Add a network menu
                    if ( $this->args['database'] == "network" && $this->args['network_admin'] ) {
                        add_action( 'network_admin_menu', array( $this, '_options_page' ) );
                    }

                    // Admin Bar menu
                    add_action( 'admin_bar_menu', array( $this, '_admin_bar_menu' ), 999 );

                    // Register setting
                    add_action( 'admin_init', array( $this, '_register_settings' ) );

                    // Display admin notices in dev_mode
                    if ( true == $this->args['dev_mode'] ) {
                        include_once( self::$_dir . 'inc/debug.php' );
                        $this->debug = new ReduxDebugObject( $this );

                        if ( true == $this->args['update_notice'] ) {
                            add_action( 'admin_init', array( $this, '_update_check' ) );
                        }
                    }

                    // Display admin notices
                    add_action( 'admin_notices', array( $this, '_admin_notices' ) );

                    // Check for dismissed admin notices.
                    add_action( 'admin_init', array( $this, '_dismiss_admin_notice' ), 9 );

                    // Enqueue the admin page CSS and JS
                    if ( isset( $_GET['page'] ) && $_GET['page'] == $this->args['page_slug'] ) {
                        add_action( 'admin_enqueue_scripts', array( $this, '_enqueue' ), 1 );
                    }

                    // Output dynamic CSS
                    add_action( 'wp_head', array( &$this, '_output_css' ), 150 );

                    // Enqueue dynamic CSS and Google fonts
                    add_action( 'wp_enqueue_scripts', array( &$this, '_enqueue_output' ), 150 );

                    require_once( self::$_dir . 'inc/import_export.php' );
                    $this->import_export = new Redux_import_export( $this );

                    if ( $this->args['database'] == "network" && $this->args['network_admin'] ) {
                        add_action( 'network_admin_edit_redux_' . $this->args['opt_name'], array(
                            $this,
                            'save_network_page'
                        ), 10, 0 );
                        add_action( 'admin_bar_menu', array( $this, 'network_admin_bar' ), 999 );


                    }

                    // mod_rewrite check
                    //Redux_Functions::modRewriteCheck();
                }

                /**
                 * Loaded hook
                 * action 'redux/loaded'
                 *
                 * @param  object $this ReduxFramework
                 */
                do_action( 'redux/loaded', $this );

            } // __construct()

            private function set_redux_content() {
                $wp_content_dir    = Redux_Helpers::cleanFilePath( trailingslashit( WP_CONTENT_DIR ) );
                self::$_upload_dir = $wp_content_dir . '/uploads/redux/';
                self::$_upload_url = Redux_Helpers::cleanFilePath( trailingslashit( content_url() ) ) . '/uploads/redux/';

                if ( ! is_dir( self::$_upload_dir ) ) {
                    $this->filesystem->execute( 'mkdir', self::$_upload_dir );
                }
            }

            private function set_default_args() {
                $this->args = array(
                    'opt_name'           => '',
                    // Must be defined by theme/plugin
                    'google_api_key'     => '',
                    // Must be defined to add google fonts to the typography module
                    'last_tab'           => '',
                    // force a specific tab to always show on reload
                    'menu_icon'          => '',
                    // menu icon
                    'menu_title'         => '',
                    // menu title/text
                    'page_icon'          => 'icon-themes',
                    'page_title'         => '',
                    // option page title
                    'page_slug'          => '_options',
                    'page_permissions'   => 'manage_options',
                    'menu_type'          => 'menu',
                    // ('menu'|'submenu')
                    'page_parent'        => 'themes.php',
                    // requires menu_type = 'submenu
                    'page_priority'      => null,
                    'allow_sub_menu'     => true,
                    // allow submenus to be added if menu_type == menu
                    'save_defaults'      => true,
                    // Save defaults to the DB on it if empty
                    'footer_credit'      => '',
                    'async_typography'   => false,
                    'disable_google_fonts_link'  => false,
                    'class'              => '',
                    // Class that gets appended to all redux-containers
                    'admin_bar'          => true,
                    // Show the panel pages on the admin bar
                    'help_tabs'          => array(),
                    'help_sidebar'       => '',
                    'database'           => '',
                    // possible: options, theme_mods, theme_mods_expanded, transient, network
                    'customizer'         => false,
                    // setting to true forces get_theme_mod_expanded
                    'global_variable'    => '',
                    // Changes global variable from $GLOBALS['YOUR_OPT_NAME'] to whatever you set here. false disables the global variable
                    'output'             => true,
                    // Dynamically generate CSS
                    'compiler'           => true,
                    // Initiate the compiler hook
                    'output_tag'         => true,
                    // Print Output Tag
                    'transient_time'     => '',
                    'default_show'       => false,
                    // If true, it shows the default value
                    'default_mark'       => '',
                    // What to print by the field's title if the value shown is default
                    'update_notice'      => true,
                    // Recieve an update notice of new commits when in dev mode
                    'disable_save_warn'  => false,
                    // Disable the save warn
                    'open_expanded'      => false,
                    // Start the panel fully expanded to start with
                    'network_admin'      => false,
                    // Enable network admin when using network database mode
                    'network_sites'      => true,
                    // Enable sites as well as admin when using network database mode
                    'hide_reset'         => false,
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
                            'my' => 'top_left',
                            'at' => 'bottom_right',
                        ),
                        'tip_effect'    => array(
                            'show' => array(
                                'effect'   => 'slide',
                                'duration' => '500',
                                'event'    => 'mouseover',
                            ),
                            'hide' => array(
                                'effect'   => 'fade',
                                'duration' => '500',
                                'event'    => 'click mouseleave',
                            ),
                        ),
                    ),
                    'show_import_export' => true,
                    'dev_mode'           => false,
                    'system_info'        => false,
                );
            }

            public function network_admin_bar( $wp_admin_bar ) {

                $args = array(
                    'id'     => $this->args['opt_name'] . '_network_admin',
                    'title'  => $this->args['menu_title'],
                    'parent' => 'network-admin',
                    'href'   => network_admin_url( 'settings.php' ) . '?page=' . $this->args['page_slug'],
                    'meta'   => array( 'class' => 'redux-network-admin' )
                );
                $wp_admin_bar->add_node( $args );

            }

            private function stripslashes_deep( $value ) {
                $value = is_array( $value ) ?
                    array_map( 'stripslashes_deep', $value ) :
                    stripslashes( $value );

                return $value;
            }

            public function save_network_page() {

                $data = $this->_validate_options( $_POST[ $this->args['opt_name'] ] );

                if ( ! empty( $data ) ) {
                    $this->set_options( $data );
                }

                wp_redirect( add_query_arg( array(
                    'page'    => $this->args['page_slug'],
                    'updated' => 'true'
                ), network_admin_url( 'settings.php' ) ) );
                exit();
            }

            public function _update_check() {
                // Only one notice per instance please
                if ( ! isset( $GLOBALS['redux_update_check'] ) ) {
                    Redux_Functions::updateCheck( self::$_version );
                    $GLOBALS['redux_update_check'] = 1;
                }
            }

            public function _admin_notices() {
                Redux_Functions::adminNotices();
            }

            public function _dismiss_admin_notice() {
                Redux_Functions::dismissAdminNotice();
            }

            /**
             * Load the plugin text domain for translation.
             *
             * @since    3.0.5
             */
            private function _internationalization() {

                /**
                 * Locale for text domain
                 * filter 'redux/textdomain/{opt_name}'
                 *
                 * @param string     The locale of the blog or from the 'locale' hook
                 * @param string     'redux-framework'  text domain
                 */
                $locale = apply_filters( "redux/textdomain/{$this->args['opt_name']}", get_locale(), 'redux-framework' );

                if ( strpos( $locale, '_' ) === false ) {
                    if ( file_exists( self::$_dir . 'languages/' . strtolower( $locale ) . '_' . strtoupper( $locale ) . '.mo' ) ) {
                        $locale = strtolower( $locale ) . '_' . strtoupper( $locale );
                    }
                }
                load_textdomain( 'redux-framework', self::$_dir . 'languages/' . $locale . '.mo' );
            } // _internationalization()

            /**
             * @return ReduxFramework
             */
            public function get_instance() {
                //self::$_instance = $this;
                return self::$instance;
            } // get_instance()

            private function _tracking() {
                include_once( dirname( __FILE__ ) . '/inc/tracking.php' );
                $tracking = Redux_Tracking::get_instance();
                $tracking->load( $this );
            } // _tracking()

            /**
             * ->_get_default(); This is used to return the default value if default_show is set
             *
             * @since       1.0.1
             * @access      public
             *
             * @param       string $opt_name The option name to return
             * @param       mixed  $default  (null)  The value to return if default not set
             *
             * @return      mixed $default
             */
            public function _get_default( $opt_name, $default = null ) {
                if ( $this->args['default_show'] == true ) {

                    if ( empty( $this->options_defaults ) ) {
                        $this->_default_values(); // fill cache
                    }

                    $default = array_key_exists( $opt_name, $this->options_defaults ) ? $this->options_defaults[ $opt_name ] : $default;
                }

                return $default;
            } // _get_default()

            /**
             * ->get(); This is used to return and option value from the options array
             *
             * @since       1.0.0
             * @access      public
             *
             * @param       string $opt_name The option name to return
             * @param       mixed  $default  (null) The value to return if option not set
             *
             * @return      mixed
             */
            public function get( $opt_name, $default = null ) {
                return ( ! empty( $this->options[ $opt_name ] ) ) ? $this->options[ $opt_name ] : $this->_get_default( $opt_name, $default );
            } // get()

            /**
             * ->set(); This is used to set an arbitrary option in the options array
             *
             * @since       1.0.0
             * @access      public
             *
             * @param       string $opt_name The name of the option being added
             * @param       mixed  $value    The value of the option being added
             *
             * @return      void
             */
            public function set( $opt_name = '', $value = '' ) {
                if ( $opt_name != '' ) {
                    $this->options[ $opt_name ] = $value;
                    $this->set_options( $this->options );
                }
            } // set()

            /**
             * Set a global variable by the global_variable argument
             *
             * @since   3.1.5
             * @return  bool          (global was set)
             */
            private function set_global_variable() {
                if ( $this->args['global_variable'] ) {
                    $option_global = $this->args['global_variable'];
                    /**
                     * filter 'redux/options/{opt_name}/global_variable'
                     *
                     * @param array $value option value to set global_variable with
                     */

                    $GLOBALS[ $this->args['global_variable'] ] = apply_filters( "redux/options/{$this->args['opt_name']}/global_variable", $this->options );
                    if ( isset( $this->transients['last_save'] ) ) {
                        // Deprecated
                        $GLOBALS[ $this->args['global_variable'] ]['REDUX_last_saved'] = $this->transients['last_save'];
                        // Last save key
                        $GLOBALS[ $this->args['global_variable'] ]['REDUX_LAST_SAVE'] = $this->transients['last_save'];
                    }
                    if ( isset( $this->transients['last_compiler'] ) ) {
                        // Deprecated
                        $GLOBALS[ $this->args['global_variable'] ]['REDUX_COMPILER'] = $this->transients['last_compiler'];
                        // Last compiler hook key
                        $GLOBALS[ $this->args['global_variable'] ]['REDUX_LAST_COMPILER'] = $this->transients['last_compiler'];
                    }

                    return true;
                }

                return false;
            } // set_global_variable()


            /**
             * ->set_options(); This is used to set an arbitrary option in the options array
             *
             * @since ReduxFramework 3.0.0
             *
             * @param mixed $value the value of the option being added
             */
            private function set_options( $value = '' ) {

                $this->transients['last_save'] = time();

                if ( ! empty( $value ) ) {
                    $this->options = $value;

                    if ( $this->args['database'] === 'transient' ) {
                        set_transient( $this->args['opt_name'] . '-transient', $value, $this->args['transient_time'] );
                    } else if ( $this->args['database'] === 'theme_mods' ) {
                        set_theme_mod( $this->args['opt_name'] . '-mods', $value );
                    } else if ( $this->args['database'] === 'theme_mods_expanded' ) {
                        foreach ( $value as $k => $v ) {
                            set_theme_mod( $k, $v );
                        }
                    } else if ( $this->args['database'] === 'network' ) {
                        // Strip those slashes!
                        $value = json_decode( stripslashes( json_encode( $value ) ), true );
                        update_site_option( $this->args['opt_name'], $value );
                    } else {
                        update_option( $this->args['opt_name'], $value );
                    }

                    // Store the changed values in the transient
                    if ( $value != $this->options ) {
                        foreach ( $value as $k => $v ) {
                            if ( ! isset( $this->options[ $k ] ) ) {
                                $this->options[ $k ] = "";
                            } else if ( $v == $this->options[ $k ] ) {
                                unset( $this->options[ $k ] );
                            }
                        }
                        $this->transients['changed_values'] = $this->options;
                    }

                    $this->options = $value;

                    // Set a global variable by the global_variable argument.
                    $this->set_global_variable();

                    // Saving the transient values
                    $this->set_transients();

                    //do_action( "redux-saved-{$this->args['opt_name']}", $value ); // REMOVE
                    //do_action( "redux/options/{$this->args['opt_name']}/saved", $value, $this->transients['changed_values'] );

                }
            } // set_options()

            /**
             * ->get_options(); This is used to get options from the database
             *
             * @since ReduxFramework 3.0.0
             */
            public function get_options() {
                $defaults = false;

                if ( ! empty( $this->defaults ) ) {
                    $defaults = $this->defaults;
                }

                if ( $this->args['database'] === "transient" ) {
                    $result = get_transient( $this->args['opt_name'] . '-transient' );
                } else if ( $this->args['database'] === "theme_mods" ) {
                    $result = get_theme_mod( $this->args['opt_name'] . '-mods' );
                } else if ( $this->args['database'] === 'theme_mods_expanded' ) {
                    $result = get_theme_mods();
                } else if ( $this->args['database'] === 'network' ) {
                    $result = get_site_option( $this->args['opt_name'], array() );
                    $result = json_decode( stripslashes( json_encode( $result ) ), true );
                } else {
                    $result = get_option( $this->args['opt_name'], array() );
                }

                if ( empty( $result ) && ! empty( $defaults ) ) {
                    $results = $defaults;
                    $this->set_options( $results );
                } else {
                    $this->options = $result;
                }

                /**
                 * action 'redux/options/{opt_name}/options'
                 *
                 * @param mixed $value option values
                 */
                $this->options = apply_filters( "redux/options/{$this->args['opt_name']}/options", $this->options );

                // Get transient values
                $this->get_transients();

                // Set a global variable by the global_variable argument.
                $this->set_global_variable();
            } // get_options()

            /**
             * ->get_wordpress_date() - Get Wordpress specific data from the DB and return in a usable array
             *
             * @since ReduxFramework 3.0.0
             */
            public function get_wordpress_data( $type = false, $args = array() ) {
                $data = "";

                /**
                 * filter 'redux/options/{opt_name}/wordpress_data/{type}/'
                 *
                 * @deprecated
                 *
                 * @param string $data
                 */
                $data = apply_filters( "redux/options/{$this->args['opt_name']}/wordpress_data/$type/", $data ); // REMOVE LATER

                /**
                 * filter 'redux/options/{opt_name}/data/{type}'
                 *
                 * @param string $data
                 */
                $data = apply_filters( "redux/options/{$this->args['opt_name']}/data/$type", $data );

                $argsKey = "";
                foreach ( $args as $key => $value ) {
                    if ( ! is_array( $value ) ) {
                        $argsKey .= $value . "-";
                    } else {
                        $argsKey .= implode( "-", $value );
                    }
                }

                if ( empty( $data ) && isset( $this->wp_data[ $type . $argsKey ] ) ) {
                    $data = $this->wp_data[ $type . $argsKey ];
                }

                if ( empty( $data ) && ! empty( $type ) ) {

                    /**
                     * Use data from Wordpress to populate options array
                     **/
                    if ( ! empty( $type ) && empty( $data ) ) {
                        if ( empty( $args ) ) {
                            $args = array();
                        }

                        $data = array();
                        $args = wp_parse_args( $args, array() );

                        if ( $type == "categories" || $type == "category" ) {
                            $cats = get_categories( $args );
                            if ( ! empty( $cats ) ) {
                                foreach ( $cats as $cat ) {
                                    $data[ $cat->term_id ] = $cat->name;
                                }
                                //foreach
                            } // If
                        } else if ( $type == "menus" || $type == "menu" ) {
                            $menus = wp_get_nav_menus( $args );
                            if ( ! empty( $menus ) ) {
                                foreach ( $menus as $item ) {
                                    $data[ $item->term_id ] = $item->name;
                                }
                                //foreach
                            }
                            //if
                        } else if ( $type == "pages" || $type == "page" ) {
                            if ( ! isset( $args['posts_per_page'] ) ) {
                                $args['posts_per_page'] = 20;
                            }
                            $pages = get_pages( $args );
                            if ( ! empty( $pages ) ) {
                                foreach ( $pages as $page ) {
                                    $data[ $page->ID ] = $page->post_title;
                                }
                                //foreach
                            }
                            //if
                        } else if ( $type == "terms" || $type == "term" ) {
                            $taxonomies = $args['taxonomies'];
                            unset( $args['taxonomies'] );
                            $terms = get_terms( $taxonomies, $args ); // this will get nothing
                            if ( ! empty( $terms ) ) {
                                foreach ( $terms as $term ) {
                                    $data[ $term->term_id ] = $term->name;
                                }
                                //foreach
                            } // If
                        } else if ( $type == "taxonomy" || $type == "taxonomies" ) {
                            $taxonomies = get_taxonomies( $args );
                            if ( ! empty( $taxonomies ) ) {
                                foreach ( $taxonomies as $key => $taxonomy ) {
                                    $data[ $key ] = $taxonomy;
                                }
                                //foreach
                            } // If
                        } else if ( $type == "posts" || $type == "post" ) {
                            $posts = get_posts( $args );
                            if ( ! empty( $posts ) ) {
                                foreach ( $posts as $post ) {
                                    $data[ $post->ID ] = $post->post_title;
                                }
                                //foreach
                            }
                            //if
                        } else if ( $type == "post_type" || $type == "post_types" ) {
                            global $wp_post_types;

                            $defaults   = array(
                                'public'              => true,
                                'exclude_from_search' => false,
                            );
                            $args       = wp_parse_args( $args, $defaults );
                            $output     = 'names';
                            $operator   = 'and';
                            $post_types = get_post_types( $args, $output, $operator );

                            ksort( $post_types );

                            foreach ( $post_types as $name => $title ) {
                                if ( isset( $wp_post_types[ $name ]->labels->menu_name ) ) {
                                    $data[ $name ] = $wp_post_types[ $name ]->labels->menu_name;
                                } else {
                                    $data[ $name ] = ucfirst( $name );
                                }
                            }
                        } else if ( $type == "tags" || $type == "tag" ) { // NOT WORKING!
                            $tags = get_tags( $args );
                            if ( ! empty( $tags ) ) {
                                foreach ( $tags as $tag ) {
                                    $data[ $tag->term_id ] = $tag->name;
                                }
                                //foreach
                            }
                            //if
                        } else if ( $type == "menu_location" || $type == "menu_locations" ) {
                            global $_wp_registered_nav_menus;

                            foreach ( $_wp_registered_nav_menus as $k => $v ) {
                                $data[ $k ] = $v;
                            }
                        } //if
                        else if ( $type == "elusive-icons" || $type == "elusive-icon" || $type == "elusive" ||
                                  $type == "font-icon" || $type == "font-icons" || $type == "icons"
                        ) {

                            /**
                             * filter 'redux-font-icons'
                             *
                             * @deprecated
                             *
                             * @param array $font_icons array of elusive icon classes
                             */
                            $font_icons = apply_filters( 'redux-font-icons', array() ); // REMOVE LATER

                            /**
                             * filter 'redux/font-icons'
                             *
                             * @deprecated
                             *
                             * @param array $font_icons array of elusive icon classes
                             */
                            $font_icons = apply_filters( 'redux/font-icons', $font_icons );

                            /**
                             * filter 'redux/{opt_name}/field/font/icons'
                             *
                             * @deprecated
                             *
                             * @param array $font_icons array of elusive icon classes
                             */
                            $font_icons = apply_filters( "redux/{$this->args['opt_name']}/field/font/icons", $font_icons );

                            foreach ( $font_icons as $k ) {
                                $data[ $k ] = $k;
                            }
                        } else if ( $type == "roles" ) {
                            /** @global WP_Roles $wp_roles */
                            global $wp_roles;

                            $data = $wp_roles->get_names();
                        } else if ( $type == "sidebars" || $type == "sidebar" ) {
                            /** @global array $wp_registered_sidebars */
                            global $wp_registered_sidebars;

                            foreach ( $wp_registered_sidebars as $key => $value ) {
                                $data[ $key ] = $value['name'];
                            }
                        } else if ( $type == "capabilities" ) {
                            /** @global WP_Roles $wp_roles */
                            global $wp_roles;

                            foreach ( $wp_roles->roles as $role ) {
                                foreach ( $role['capabilities'] as $key => $cap ) {
                                    $data[ $key ] = ucwords( str_replace( '_', ' ', $key ) );
                                }
                            }
                        } else if ( $type == "callback" ) {
                            if ( ! is_array( $args ) ) {
                                $args = array( $args );
                            }
                            $data = call_user_func( $args[0] );
                        }
                        //if
                    }
                    //if

                    $this->wp_data[ $type . $argsKey ] = $data;
                }

                //if

                return $data;
            } // get_wordpress_data()

            /**
             * ->show(); This is used to echo and option value from the options array
             *
             * @since       1.0.0
             * @access      public
             *
             * @param       string $opt_name The name of the option being shown
             * @param       mixed  $default  The value to show if $opt_name isn't set
             *
             * @return      void
             */
            public function show( $opt_name, $default = '' ) {
                $option = $this->get( $opt_name );
                if ( ! is_array( $option ) && $option != '' ) {
                    echo $option;
                } elseif ( $default != '' ) {
                    echo $this->_get_default( $opt_name, $default );
                }
            } // show()

            /**
             * Get default options into an array suitable for the settings API
             *
             * @since       1.0.0
             * @access      public
             * @return      array $this->options_defaults
             */
            public function _default_values() {
                if ( ! is_null( $this->sections ) && is_null( $this->options_defaults ) ) {

                    // fill the cache
                    foreach ( $this->sections as $sk => $section ) {
                        if ( ! isset( $section['id'] ) ) {
                            if ( ! is_numeric( $sk ) || ! isset( $section['title'] ) ) {
                                $section['id'] = $sk;
                            } else {
                                $section['id'] = sanitize_title( $section['title'], $sk );
                            }
                            $this->sections[ $sk ] = $section;
                        }
                        if ( isset( $section['fields'] ) ) {
                            foreach ( $section['fields'] as $k => $field ) {
                                if ( empty( $field['id'] ) && empty( $field['type'] ) ) {
                                    continue;
                                }

                                if ( in_array( $field['type'], array( 'ace_editor' ) ) && isset( $field['options'] ) ) {
                                    $this->sections[ $sk ]['fields'][ $k ]['args'] = $field['options'];
                                    unset( $this->sections[ $sk ]['fields'][ $k ]['options'] );
                                }

                                if ( $field['type'] == "section" && isset( $field['indent'] ) && $field['indent'] == "true" ) {
                                    $field['class'] = isset( $field['class'] ) ? $field['class'] : '';
                                    $field['class'] .= "redux-section-indent-start";
                                    $this->sections[ $sk ]['fields'][ $k ] = $field;
                                }
                                // Detect what field types are being used
                                if ( ! isset( $this->fields[ $field['type'] ][ $field['id'] ] ) ) {
                                    $this->fields[ $field['type'] ][ $field['id'] ] = 1;
                                } else {
                                    $this->fields[ $field['type'] ] = array( $field['id'] => 1 );
                                }
                                if ( isset( $field['default'] ) ) {
                                    $this->options_defaults[ $field['id'] ] = $field['default'];
                                } elseif ( isset( $field['options'] ) && ( $field['type'] != "ace_editor" ) ) {
                                    // Sorter data filter
                                    if ( $field['type'] == "sorter" && isset( $field['data'] ) && ! empty( $field['data'] ) && is_array( $field['data'] ) ) {
                                        if ( ! isset( $field['args'] ) ) {
                                            $field['args'] = array();
                                        }
                                        foreach ( $field['data'] as $key => $data ) {
                                            if ( ! isset( $field['args'][ $key ] ) ) {
                                                $field['args'][ $key ] = array();
                                            }
                                            $field['options'][ $key ] = $this->get_wordpress_data( $data, $field['args'][ $key ] );
                                        }
                                    }
                                    $this->options_defaults[ $field['id'] ] = $field['options'];
                                }
                            }
                        }
                    }
                }

                /**
                 * filter 'redux/options/{opt_name}/defaults'
                 *
                 * @param array $defaults option default values
                 */
                $this->transients['changed_values'] = isset( $this->transients['changed_values'] ) ? $this->transients['changed_values'] : array();
                $this->options_defaults             = apply_filters( "redux/options/{$this->args['opt_name']}/defaults", $this->options_defaults, $this->transients['changed_values'] );

                return $this->options_defaults;
            }

            /**
             * Set default options on admin_init if option doesn't exist
             *
             * @since       1.0.0
             * @access      public
             * @return      void
             */
            private function _default_cleanup() {

                // Fix the global variable name
                if ( $this->args['global_variable'] == "" && $this->args['global_variable'] !== false ) {
                    $this->args['global_variable'] = str_replace( '-', '_', $this->args['opt_name'] );
                }
            }

            /**
             * Class Add Sub Menu Function, creates options submenu in Wordpress admin area.
             *
             * @since       3.1.9
             * @access      private
             * @return      void
             */
            private function add_submenu( $page_parent, $page_title, $menu_title, $page_permissions, $page_slug ) {
                global $submenu;

                // Just in case. One never knows.
                $page_parent = strtolower( $page_parent );

                $test = array(
                    'index.php'               => 'dashboard',
                    'edit.php'                => 'posts',
                    'upload.php'              => 'media',
                    'link-manager.php'        => 'links',
                    'edit.php?post_type=page' => 'pages',
                    'edit-comments.php'       => 'comments',
                    'themes.php'              => 'theme',
                    'plugins.php'             => 'plugins',
                    'users.php'               => 'users',
                    'tools.php'               => 'management',
                    'options-general.php'     => 'options',
                );

                if ( isset( $test[ $page_parent ] ) ) {
                    $function   = 'add_' . $test[ $page_parent ] . '_page';
                    $this->page = $function(
                        $page_title, $menu_title, $page_permissions, $page_slug, array( $this, '_options_page_html' )
                    );
                } else {
                    // Network settings and Post type menus. These do not have
                    // wrappers and need to be appened to using add_submenu_page.
                    // Okay, since we've left the post type menu appending
                    // as default, we need to validate it, so anything that
                    // isn't post_type=<post_type> doesn't get through and mess
                    // things up.
                    $addMenu = false;
                    if ( 'settings.php' != $page_parent ) {
                        // Establish the needle
                        $needle = '?post_type=';

                        // Check if it exists in the page_parent (how I miss instr)
                        $needlePos = strrpos( $page_parent, $needle );

                        // It's there, so...
                        if ( $needlePos > 0 ) {

                            // Get the post type.
                            $postType = substr( $page_parent, $needlePos + strlen( $needle ) );

                            // Ensure it exists.
                            if ( post_type_exists( $postType ) ) {
                                // Set flag to add the menu page
                                $addMenu = true;
                            }
                            // custom menu
                        } elseif ( isset( $submenu[ $this->args['page_parent'] ] ) ) {
                            $addMenu = true;
                        }

                    } else {
                        // The page_parent was settings.php, so set menu add
                        // flag to true.
                        $addMenu = true;
                    }
                    // Add the submenu if it's permitted.
                    if ( true == $addMenu ) {
                        $this->page = add_submenu_page(
                            $page_parent, $page_title, $menu_title, $page_permissions, $page_slug, array(
                                &$this,
                                '_options_page_html'
                            )
                        );
                    }
                }
            }

            /**
             * Class Options Page Function, creates main options page.
             *
             * @since       1.0.0
             * @access      public
             * @return void
             */
            public function _options_page() {
                $this->import_export->in_field();

                if ( $this->args['menu_type'] == 'submenu' ) {
                    $this->add_submenu(
                        $this->args['page_parent'],
                        $this->args['page_title'],
                        $this->args['menu_title'],
                        $this->args['page_permissions'],
                        $this->args['page_slug']
                    );

                } else {
                    $this->page = add_menu_page(
                        $this->args['page_title'],
                        $this->args['menu_title'],
                        $this->args['page_permissions'],
                        $this->args['page_slug'],
                        array( &$this, '_options_page_html' ),
                        $this->args['menu_icon'],
                        $this->args['page_priority']
                    );

                    if ( true === $this->args['allow_sub_menu'] ) {
                        if ( ! isset( $section['type'] ) || $section['type'] != 'divide' ) {
                            foreach ( $this->sections as $k => $section ) {
                                $canBeSubSection = ( $k > 0 && ( ! isset( $this->sections[ ( $k ) ]['type'] ) || $this->sections[ ( $k ) ]['type'] != "divide" ) ) ? true : false;

                                if ( ! isset( $section['title'] ) || ( $canBeSubSection && ( isset( $section['subsection'] ) && $section['subsection'] == true ) ) ) {
                                    continue;
                                }

                                if ( isset( $section['submenu'] ) && $section['submenu'] == false ) {
                                    continue;
                                }

                                if ( isset( $section['customizer_only'] ) && $section['customizer_only'] == true ) {
                                    continue;
                                }

                                add_submenu_page(
                                    $this->args['page_slug'],
                                    $section['title'],
                                    $section['title'],
                                    $this->args['page_permissions'],
                                    $this->args['page_slug'] . '&tab=' . $k,
                                    //create_function( '$a', "return null;" )
                                    '__return_null'
                                );
                            }

                            // Remove parent submenu item instead of adding null item.
                            remove_submenu_page( $this->args['page_slug'], $this->args['page_slug'] );
                        }

                        if ( true == $this->args['show_import_export'] && false == $this->import_export->is_field ) {
                            $this->import_export->add_submenu();
                        }

                        if ( true == $this->args['dev_mode'] ) {
                            $this->debug->add_submenu();
                        }

                        if ( true == $this->args['system_info'] ) {
                            add_submenu_page(
                                $this->args['page_slug'],
                                __( 'System Info', 'redux-framework' ),
                                __( 'System Info', 'redux-framework' ),
                                $this->args['page_permissions'],
                                $this->args['page_slug'] . '&tab=system_info_default',
                                '__return_null'
                            );
                        }
                    }
                }

                add_action( "load-{$this->page}", array( &$this, '_load_page' ) );
            } // _options_page()

            /**
             * Add admin bar menu
             *
             * @since       3.1.5.16
             * @access      public
             * @global      $menu , $submenu, $wp_admin_bar
             * @return      void
             */
            public function _admin_bar_menu() {
                global $menu, $submenu, $wp_admin_bar;

                $ct         = wp_get_theme();
                $theme_data = $ct;

                if ( ! is_super_admin() || ! is_admin_bar_showing() || ! $this->args['admin_bar'] ) {
                    return;
                }

                if ( $menu ) {
                    foreach ( $menu as $menu_item ) {
                        if ( isset( $menu_item[2] ) && $menu_item[2] === $this->args["page_slug"] ) {
                            $nodeargs = array(
                                'id'    => $menu_item[2],
                                'title' => "<span class='ab-icon dashicons-admin-generic'></span>" . $menu_item[0],
                                'href'  => admin_url( 'admin.php?page=' . $menu_item[2] ),
                                'meta'  => array()
                            );
                            $wp_admin_bar->add_node( $nodeargs );

                            break;
                        }
                    }

                    if ( isset( $submenu[ $this->args["page_slug"] ] ) && is_array( $submenu[ $this->args["page_slug"] ] ) ) {
                        foreach ( $submenu[ $this->args["page_slug"] ] as $index => $redux_options_submenu ) {
                            $subnodeargs = array(
                                'id'     => $this->args["page_slug"] . '_' . $index,
                                'title'  => $redux_options_submenu[0],
                                'parent' => $this->args["page_slug"],
                                'href'   => admin_url( 'admin.php?page=' . $redux_options_submenu[2] ),
                            );

                            $wp_admin_bar->add_node( $subnodeargs );
                        }
                    }
                } else {
                    $nodeargs = array(
                        'id'    => $this->args["page_slug"],
                        'title' => "<span class='ab-icon dashicons-admin-generic'></span>" . $this->args['menu_title'],
                        // $theme_data->get( 'Name' ) . " " . __( 'Options', 'redux-framework-demo' ),
                        'href'  => admin_url( 'admin.php?page=' . $this->args["page_slug"] ),
                        'meta'  => array()
                    );

                    $wp_admin_bar->add_node( $nodeargs );
                }
            } // _admin_bar_menu()

            /**
             * Output dynamic CSS at bottom of HEAD
             *
             * @since       3.2.8
             * @access      public
             * @return      void
             */
            public function _output_css() {
                if ( $this->args['output'] == false && $this->args['compiler'] == false ) {
                    return;
                }

                if ( isset( $this->no_output ) ) {
                    return;
                }

                if ( ! empty( $this->outputCSS ) && ( $this->args['output_tag'] == true || ( isset( $_POST['customized'] ) ) ) ) {
                    echo '<style type="text/css" title="dynamic-css" class="options-output">' . $this->outputCSS . '</style>';
                }
            }

            /**
             * Enqueue CSS and Google fonts for front end
             *
             * @since       1.0.0
             * @access      public
             * @return      void
             */
            public function _enqueue_output() {
                if ( $this->args['output'] == false && $this->args['compiler'] == false ) {
                    return;
                }

                /** @noinspection PhpUnusedLocalVariableInspection */
                foreach ( $this->sections as $k => $section ) {
                    if ( isset( $section['type'] ) && ( $section['type'] == 'divide' ) ) {
                        continue;
                    }

                    if ( isset( $section['fields'] ) ) {
                        /** @noinspection PhpUnusedLocalVariableInspection */
                        foreach ( $section['fields'] as $fieldk => $field ) {
                            if ( isset( $field['type'] ) && $field['type'] != "callback" ) {
                                $field_class = "ReduxFramework_{$field['type']}";
                                if ( ! class_exists( $field_class ) ) {

                                    if ( ! isset( $field['compiler'] ) ) {
                                        $field['compiler'] = "";
                                    }

                                    /**
                                     * Field class file
                                     * filter 'redux/{opt_name}/field/class/{field.type}
                                     *
                                     * @param       string        field class file
                                     * @param array $field        field config data
                                     */
                                    $class_file = apply_filters( "redux/{$this->args['opt_name']}/field/class/{$field['type']}", self::$_dir . "inc/fields/{$field['type']}/field_{$field['type']}.php", $field );

                                    if ( $class_file && file_exists( $class_file ) && ! class_exists( $field_class ) ) {
                                        /** @noinspection PhpIncludeInspection */
                                        require_once( $class_file );
                                    }
                                }

                                if ( ! empty( $this->options[ $field['id'] ] ) && class_exists( $field_class ) && method_exists( $field_class, 'output' ) && $this->_can_output_css( $field ) ) {
                                    $field = apply_filters( "redux/field/{$this->args['opt_name']}/output_css", $field );

                                    if ( ! empty( $field['output'] ) && ! is_array( $field['output'] ) ) {
                                        $field['output'] = array( $field['output'] );
                                    }

                                    $value   = isset( $this->options[ $field['id'] ] ) ? $this->options[ $field['id'] ] : '';
                                    $enqueue = new $field_class( $field, $value, $this );

                                    if ( ( ( isset( $field['output'] ) && ! empty( $field['output'] ) ) || ( isset( $field['compiler'] ) && ! empty( $field['compiler'] ) ) || $field['type'] == "typography" || $field['type'] == "icon_select" ) ) {
                                        $enqueue->output();
                                    }
                                }
                            }
                        }
                    }
                }

                // For use like in the customizer. Stops the output, but passes the CSS in the variable for the compiler
                if ( isset( $this->no_output ) ) {
                    return;
                }

                if ( ! empty( $this->typography ) && ! empty( $this->typography ) && filter_var( $this->args['output'], FILTER_VALIDATE_BOOLEAN ) ) {
                    $version    = ! empty( $this->transients['last_save'] ) ? $this->transients['last_save'] : '';
                    $typography = new ReduxFramework_typography( null, null, $this );

                    if ( $this->args['async_typography'] && ! empty( $this->typography ) ) {
                        $families = array();
                        foreach ( $this->typography as $key => $value ) {
                            $families[] = $key;
                        }

                        ?>
                        <script>
                            /* You can add more configuration options to webfontloader by previously defining the WebFontConfig with your options */
                            if ( typeof WebFontConfig === "undefined" ) {
                                WebFontConfig = new Object();
                            }
                            WebFontConfig['google'] = {families: [<?php echo $typography->makeGoogleWebfontString( $this->typography )?>]};

                            (function() {
                                var wf = document.createElement( 'script' );
                                wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1.5.3/webfont.js';
                                wf.type = 'text/javascript';
                                wf.async = 'true';
                                var s = document.getElementsByTagName( 'script' )[0];
                                s.parentNode.insertBefore( wf, s );
                            })();
                        </script>
                    <?php
                    } elseif ( !$this->args['disable_google_fonts_link'] ) {
                        $protocol = ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443 ) ? "https:" : "http:";

                        //echo '<link rel="stylesheet" id="options-google-fonts" title="" href="'.$protocol.$typography->makeGoogleWebfontLink( $this->typography ).'&amp;v='.$version.'" type="text/css" media="all" />';
                        wp_register_style( 'redux-google-fonts', $protocol . $typography->makeGoogleWebfontLink( $this->typography ), '', $version );
                        wp_enqueue_style( 'redux-google-fonts' );
                    }
                }

            } // _enqueue_output()

            /**
             * Enqueue CSS/JS for options page
             *
             * @since       1.0.0
             * @access      public
             * @global      $wp_styles
             * @return      void
             */
            public function _enqueue() {
                global $wp_styles;

                Redux_Functions::$_parent = $this;
                $min                      = Redux_Functions::isMin();

                // Select2 business.  Fields:  Background, Border, Dimensions, Select, Slider, Typography
                if ( Redux_Helpers::isFieldInUseByType( $this->fields, array(
                    'background',
                    'border',
                    'dimensions',
                    'select',
                    'select_image',
                    'slider',
                    'spacing',
                    'typography',
                    'color_scheme'

                ) )
                ) {

                    // select2 CSS
                    wp_register_style(
                        'select2-css',
                        self::$_url . 'assets/js/vendor/select2/select2.css',
                        array(),
                        filemtime( self::$_dir . 'assets/js/vendor/select2/select2.css' ),
                        'all'
                    );

                    wp_enqueue_style( 'select2-css' );

                    // JS
                    wp_register_script(
                        'select2-sortable-js',
                        self::$_url . 'assets/js/vendor/select2.sortable.min.js',
                        array( 'jquery' ),
                        filemtime( self::$_dir . 'assets/js/vendor/select2.sortable.min.js' ),
                        true
                    );

                    wp_register_script(
                        'select2-js',
                        self::$_url . 'assets/js/vendor/select2/select2.min.js',
                        array( 'jquery', 'select2-sortable-js' ),
                        filemtime( self::$_dir . 'assets/js/vendor/select2/select2.min.js' ),
                        true
                    );

                    wp_enqueue_script( 'select2-js' );
                }

                wp_register_style(
                    'redux-css',
                    self::$_url . 'assets/css/redux.css',
                    array( 'farbtastic' ),
                    filemtime( self::$_dir . 'assets/css/redux.css' ),
                    'all'
                );

                wp_register_style(
                    'admin-css',
                    self::$_url . 'assets/css/admin.css',
                    array( 'farbtastic' ),
                    filemtime( self::$_dir . 'assets/css/admin.css' ),
                    'all'
                );

                wp_register_style(
                    'redux-elusive-icon',
                    self::$_url . 'assets/css/vendor/elusive-icons/elusive-webfont.css',
                    array(),
                    filemtime( self::$_dir . 'assets/css/vendor/elusive-icons/elusive-webfont.css' ),
                    'all'
                );

                wp_register_style(
                    'redux-elusive-icon-ie7',
                    self::$_url . 'assets/css/vendor/elusive-icons/elusive-webfont-ie7.css',
                    array(),
                    filemtime( self::$_dir . 'assets/css/vendor/elusive-icons/elusive-webfont-ie7.css' ),
                    'all'
                );

                wp_register_style(
                    'qtip-css',
                    self::$_url . 'assets/css/vendor/qtip/jquery.qtip.css',
                    array(),
                    filemtime( self::$_dir . 'assets/css/vendor/qtip/jquery.qtip.css' ),
                    'all'
                );

                $wp_styles->add_data( 'redux-elusive-icon-ie7', 'conditional', 'lte IE 7' );

                /**
                 * jQuery UI stylesheet src
                 * filter 'redux/page/{opt_name}/enqueue/jquery-ui-css'
                 *
                 * @param string  bundled stylesheet src
                 */
                wp_register_style(
                    'jquery-ui-css',
                    apply_filters( "redux/page/{$this->args['opt_name']}/enqueue/jquery-ui-css", self::$_url . 'assets/css/vendor/jquery-ui-bootstrap/jquery-ui-1.10.0.custom.css' ),
                    '',
                    filemtime( self::$_dir . 'assets/css/vendor/jquery-ui-bootstrap/jquery-ui-1.10.0.custom.css' ), // todo - version should be based on above post-filter src
                    'all'
                );

                wp_enqueue_style( 'jquery-ui-css' );
                wp_enqueue_style( 'redux-lte-ie8' );
                wp_enqueue_style( 'qtip-css' );
                wp_enqueue_style( 'redux-elusive-icon' );
                wp_enqueue_style( 'redux-elusive-icon-ie7' );

                if ( is_rtl() ) {
                    wp_register_style(
                        'redux-rtl-css',
                        self::$_url . 'assets/css/rtl.css',
                        '',
                        filemtime( self::$_dir . 'assets/css/rtl.css' ),
                        'all'
                    );
                    wp_enqueue_style( 'redux-rtl-css' );
                }

                wp_enqueue_script( 'jquery' );
                wp_enqueue_script( 'jquery-ui-core' );
                wp_enqueue_script( 'jquery-ui-dialog' );

                // Load jQuery sortable for slides, sorter, sortable and group
                if ( Redux_Helpers::isFieldInUseByType( $this->fields, array(
                    'slides',
                    'sorter',
                    'sortable',
                    'group'
                ) )
                ) {
                    wp_enqueue_script( 'jquery-ui-sortable' );
                    wp_enqueue_style( 'jquery-ui-sortable' );
                }

                // Load jQuery UI Datepicker for date
                if ( Redux_Helpers::isFieldInUseByType( $this->fields, array( 'date' ) ) ) {
                    wp_enqueue_script( 'jquery-ui-datepicker' );
                }

                // Load jQuery UI Accordion for slides and group
                if ( Redux_Helpers::isFieldInUseByType( $this->fields, array( 'slides', 'group' ) ) ) {
                    wp_enqueue_script( 'jquery-ui-accordion' );
                }

                // Load wp-color-picker for color, color_gradient, link_color, border, background and typography
                if ( Redux_Helpers::isFieldInUseByType( $this->fields, array(
                    'background',
                    'color',
                    'color_gradient',
                    'link_color',
                    'border',
                    'typography'
                ) )
                ) {

                    wp_enqueue_style(
                        'redux-color-picker-css',
                        self::$_url . 'assets/css/color-picker/color-picker.css',
                        array( 'wp-color-picker' ),
                        filemtime( self::$_dir . 'assets/css/color-picker/color-picker.css' ),
                        'all'
                    );
                    
                    wp_enqueue_style( 'color-picker-css' );


                    wp_enqueue_script( 'wp-color-picker' );
                    wp_enqueue_style( 'wp-color-picker' );
                }

                if ( function_exists( 'wp_enqueue_media' ) ) {
                    wp_enqueue_media();
                } else {
                    wp_enqueue_script( 'media-upload' );
                }

                add_thickbox();

                wp_register_script(
                    'qtip-js',
                    self::$_url . 'assets/js/vendor/qtip/jquery.qtip.js',
                    array( 'jquery' ),
                    '2.2.0',
                    true
                );

                wp_register_script(
                    'serializeForm-js',
                    self::$_url . 'assets/js/vendor/jquery.serializeForm.js',
                    array( 'jquery' ),
                    '1.0.0',
                    true
                );

                // Embed the compress version unless in dev mode
                // dev_mode = true
                if ( isset( $this->args['dev_mode'] ) && $this->args['dev_mode'] == true ) {
                    wp_enqueue_style( 'admin-css' );
                    wp_register_script(
                        'redux-vendor',
                        self::$_url . 'assets/js/vendor.min.js',
                        array( 'jquery' ),
                        filemtime( self::$_dir . 'assets/js/vendor.min.js' ),
                        true
                    );

                    // dev_mode - false
                } else {
                    wp_enqueue_style( 'redux-css' );
                }

                $depArray = array( 'jquery', 'qtip-js', 'serializeForm-js', );

                if ( true == $this->args['dev_mode'] ) {
                    array_push( $depArray, 'redux-vendor' );
                }

                wp_register_script(
                    'redux-js',
                    self::$_url . 'assets/js/redux' . $min . '.js',
                    $depArray,
                    filemtime( self::$_dir . 'assets/js/redux' . $min . '.js' ),
                    true
                );

                foreach ( $this->sections as $section ) {
                    if ( isset( $section['fields'] ) ) {
                        foreach ( $section['fields'] as $field ) {
                            // TODO AFTER GROUP WORKS - Revert IF below
                            // if( isset( $field['type'] ) && $field['type'] != 'callback' ) {
                            if ( isset( $field['type'] ) && $field['type'] != 'callback' ) {

                                $field_class = 'ReduxFramework_' . $field['type'];

                                /**
                                 * Field class file
                                 * filter 'redux/{opt_name}/field/class/{field.type}
                                 *
                                 * @param       string        field class file path
                                 * @param array $field        field config data
                                 */
                                $class_file = apply_filters( "redux/{$this->args['opt_name']}/field/class/{$field['type']}", self::$_dir . "inc/fields/{$field['type']}/field_{$field['type']}.php", $field );
                                if ( $class_file ) {
                                    if ( ! class_exists( $field_class ) ) {
                                        if ( file_exists( $class_file ) ) {
                                            require_once( $class_file );
                                        }
                                    }

                                    if ( ( method_exists( $field_class, 'enqueue' ) ) || method_exists( $field_class, 'localize' ) ) {
                                        if ( ! isset( $this->options[ $field['id'] ] ) ) {
                                            $this->options[ $field['id'] ] = "";
                                        }
                                        $theField = new $field_class( $field, $this->options[ $field['id'] ], $this );

                                        // Move dev_mode check to a new if/then block
                                        if ( ! wp_script_is( 'redux-field-' . $field['type'] . '-js', 'enqueued' ) && class_exists( $field_class ) && method_exists( $field_class, 'enqueue' ) ) {

                                            // Checking for extension field AND dev_mode = false OR dev_mode = true
                                            // Since extension fields use 'extension_dir' exclusively, we can detect them here.
                                            // Also checking for dev_mode = true doesn't mess up the JS combinine.
                                            //if ( /*$this->args['dev_mode'] === false && */ isset($theField->extension_dir) && (!'' == $theField->extension_dir) /* || ($this->args['dev_mode'] === true) */) {
                                            $theField->enqueue();
                                            //}
                                        }

                                        if ( method_exists( $field_class, 'localize' ) ) {
                                            $params = $theField->localize( $field );
                                            if ( ! isset( $this->localize_data[ $field['type'] ] ) ) {
                                                $this->localize_data[ $field['type'] ] = array();
                                            }
                                            $this->localize_data[ $field['type'] ][ $field['id'] ] = $theField->localize( $field );
                                        }

                                        unset( $theField );
                                    }
                                }
                            }
                        }
                    }
                }

                $this->localize_data['required']       = $this->required;
                $this->localize_data['fonts']          = $this->fonts;
                $this->localize_data['required_child'] = $this->required_child;
                $this->localize_data['fields']         = $this->fields;

                if ( isset( $this->font_groups['google'] ) ) {
                    $this->localize_data['googlefonts'] = $this->font_groups['google'];
                }

                if ( isset( $this->font_groups['std'] ) ) {
                    $this->localize_data['stdfonts'] = $this->font_groups['std'];
                }

                if ( isset( $this->font_groups['customfonts'] ) ) {
                    $this->localize_data['customfonts'] = $this->font_groups['customfonts'];
                }

                $this->localize_data['folds'] = $this->folds;

                // Make sure the children are all hidden properly.
                foreach ( $this->fields as $key => $value ) {
                    if ( in_array( $key, $this->fieldsHidden ) ) {
                        foreach ( $value as $k => $v ) {
                            if ( ! in_array( $k, $this->fieldsHidden ) ) {
                                $this->fieldsHidden[] = $k;
                                $this->folds[ $k ]    = "hide";
                            }
                        }
                    }
                }

                if ( isset( $this->args['dev_mode'] ) && $this->args['dev_mode'] == true ) {

                    $base = ReduxFramework::$_url.'inc/p.php?url=';
                    $url = $base.urlencode('http://ads.reduxframework.com/api/index.php?js&g&1&v=2').'&proxy='.urlencode($base);
                    $this->localize_data['rAds'] = '<span data-id="1" class="mgv1_1"><script type="text/javascript">(function(){if (mysa_mgv1_1) return; var ma = document.createElement("script"); ma.type = "text/javascript"; ma.async = true; ma.src = "'.$url.'"; var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ma, s) })();var mysa_mgv1_1=true;</script></span>';
                }
                
                $this->localize_data['fieldsHidden'] = $this->fieldsHidden;
                $this->localize_data['options']      = $this->options;
                $this->localize_data['defaults']     = $this->options_defaults;

                /**
                 * Save pending string
                 * filter 'redux/{opt_name}/localize/save_pending
                 *
                 * @param       string        save_pending string
                 */
                $save_pending = apply_filters( "redux/{$this->args['opt_name']}/localize/save_pending", __( 'You have changes that are not saved. Would you like to save them now?', 'redux-framework' ) );

                /**
                 * Reset all string
                 * filter 'redux/{opt_name}/localize/reset
                 *
                 * @param       string        reset all string
                 */
                $reset_all = apply_filters( "redux/{$this->args['opt_name']}/localize/reset", __( 'Are you sure? Resetting will lose all custom values.', 'redux-framework' ) );

                /**
                 * Reset section string
                 * filter 'redux/{opt_name}/localize/reset_section
                 *
                 * @param       string        reset section string
                 */
                $reset_section = apply_filters( "redux/{$this->args['opt_name']}/localize/reset_section", __( 'Are you sure? Resetting will lose all custom values in this section.', 'redux-framework' ) );

                /**
                 * Preset confirm string
                 * filter 'redux/{opt_name}/localize/preset
                 *
                 * @param       string        preset confirm string
                 */
                $preset_confirm = apply_filters( "redux/{$this->args['opt_name']}/localize/preset", __( 'Your current options will be replaced with the values of this preset. Would you like to proceed?', 'redux-framework' ) );

                $this->localize_data['args'] = array(
                    'save_pending'          => $save_pending,
                    'reset_confirm'         => $reset_all,
                    'reset_section_confirm' => $reset_section,
                    'preset_confirm'        => $preset_confirm,
                    'please_wait'           => __( 'Please Wait', 'redux-framework' ),
                    'opt_name'              => $this->args['opt_name'],
                    'slug'                  => $this->args['page_slug'],
                    'hints'                 => $this->args['hints'],
                    'disable_save_warn'     => $this->args['disable_save_warn'],
                    'class'                 => $this->args['class'],
                );

                // Construct the errors array.
                if ( isset( $this->transients['last_save_mode'] ) && ! empty( $this->transients['notices']['errors'] ) ) {
                    $theTotal  = 0;
                    $theErrors = array();

                    foreach ( $this->transients['notices']['errors'] as $error ) {
                        $theErrors[ $error['section_id'] ]['errors'][] = $error;

                        if ( ! isset( $theErrors[ $error['section_id'] ]['total'] ) ) {
                            $theErrors[ $error['section_id'] ]['total'] = 0;
                        }

                        $theErrors[ $error['section_id'] ]['total'] ++;
                        $theTotal ++;
                    }

                    $this->localize_data['errors'] = array( 'total' => $theTotal, 'errors' => $theErrors );
                    unset( $this->transients['notices']['errors'] );
                }

                // Construct the warnings array.
                if ( isset( $this->transients['last_save_mode'] ) && ! empty( $this->transients['notices']['warnings'] ) ) {
                    $theTotal    = 0;
                    $theWarnings = array();

                    foreach ( $this->transients['notices']['warnings'] as $warning ) {
                        $theWarnings[ $warning['section_id'] ]['warnings'][] = $warning;

                        if ( ! isset( $theWarnings[ $warning['section_id'] ]['total'] ) ) {
                            $theWarnings[ $warning['section_id'] ]['total'] = 0;
                        }

                        $theWarnings[ $warning['section_id'] ]['total'] ++;
                        $theTotal ++;
                    }

                    unset( $this->transients['notices']['warnings'] );
                    $this->localize_data['warnings'] = array( 'total' => $theTotal, 'warnings' => $theWarnings );
                }

                if ( empty( $this->transients['notices'] ) ) {
                    unset( $this->transients['notices'] );
                }

                // Values used by the javascript
                wp_localize_script(
                    'redux-js',
                    'redux',
                    $this->localize_data
                );

                wp_enqueue_script( 'redux-js' ); // Enque the JS now

                wp_enqueue_script(
                    'webfontloader',
                    'https://ajax.googleapis.com/ajax/libs/webfont/1.5.0/webfont.js',
                    array( 'jquery' ),
                    '1.5.0',
                    true
                );

                /**
                 * action 'redux-enqueue-{opt_name}'
                 *
                 * @deprecated
                 *
                 * @param  object $this ReduxFramework
                 */
                do_action( "redux-enqueue-{$this->args['opt_name']}", $this ); // REMOVE

                /**
                 * action 'redux/page/{opt_name}/enqueue'
                 */
                do_action( "redux/page/{$this->args['opt_name']}/enqueue" );
            } // _enqueue()

            /**
             * Show page help
             *
             * @since       1.0.0
             * @access      public
             * @return      void
             */
            public function _load_page() {

                // Do admin head action for this page
                add_action( 'admin_head', array( &$this, 'admin_head' ) );

                // Do admin footer text hook
                add_filter( 'admin_footer_text', array( &$this, 'admin_footer_text' ) );

                $screen = get_current_screen();

                if ( is_array( $this->args['help_tabs'] ) ) {
                    foreach ( $this->args['help_tabs'] as $tab ) {
                        $screen->add_help_tab( $tab );
                    }
                }

                // If hint argument is set, display hint tab
                if ( true == $this->show_hints ) {
                    global $current_user;

                    // Users enable/disable hint choice
                    $hint_status = get_user_meta( $current_user->ID, 'ignore_hints' ) ? get_user_meta( $current_user->ID, 'ignore_hints', true ) : 'true';

                    // current page parameters
                    $curPage = $_GET['page'];

                    $curTab = '0';
                    if ( isset( $_GET['tab'] ) ) {
                        $curTab = $_GET['tab'];
                    }

                    // Default url values for enabling hints.
                    $dismiss = 'true';
                    $s       = 'Enable';

                    // Values for disabling hints.
                    if ( 'true' == $hint_status ) {
                        $dismiss = 'false';
                        $s       = 'Disable';
                    }

                    // Make URL
                    $url = '<a class="redux_hint_status" href="?dismiss=' . $dismiss . '&amp;id=hints&amp;page=' . $curPage . '&amp;tab=' . $curTab . '">' . $s . ' hints</a>';

                    $event = 'moving the mouse over';
                    if ( 'click' == $this->args['hints']['tip_effect']['show']['event'] ) {
                        $event = 'clicking';
                    }

                    // Construct message
                    $msg = 'Hints are tooltips that popup when ' . $event . ' the hint icon, offering addition information about the field in which they appear.  They can be ' . strtolower( $s ) . 'd by using the link below.<br/><br/>' . $url;

                    // Construct hint tab
                    $tab = array(
                        'id'      => 'redux-hint-tab',
                        'title'   => __( 'Hints', 'redux-framework-demo' ),
                        'content' => __( '<p>' . $msg . '</p>', 'redux-framework-demo' )
                    );

                    $screen->add_help_tab( $tab );
                }

                // Sidebar text
                if ( $this->args['help_sidebar'] != '' ) {

                    // Specify users text from arguments
                    $screen->set_help_sidebar( $this->args['help_sidebar'] );
                } else {

                    // If sidebar text is empty and hints are active, display text
                    // about hints.
                    if ( true == $this->show_hints ) {
                        $screen->set_help_sidebar( '<p><strong>Redux Framework</strong><br/><br/>Hint Tooltip Preferences</p>' );
                    }
                }

                /**
                 * action 'redux-load-page-{opt_name}'
                 *
                 * @deprecated
                 *
                 * @param object $screen WP_Screen
                 */
                do_action( "redux-load-page-{$this->args['opt_name']}", $screen ); // REMOVE

                /**
                 * action 'redux/page/{opt_name}/load'
                 *
                 * @param object $screen WP_Screen
                 */
                do_action( "redux/page/{$this->args['opt_name']}/load", $screen );

            } // _load_page()

            /**
             * Do action redux-admin-head for options page
             *
             * @since       1.0.0
             * @access      public
             * @return      void
             */
            public function admin_head() {
                /**
                 * action 'redux-admin-head-{opt_name}'
                 *
                 * @deprecated
                 *
                 * @param  object $this ReduxFramework
                 */
                do_action( "redux-admin-head-{$this->args['opt_name']}", $this ); // REMOVE

                /**
                 * action 'redux/page/{opt_name}/header'
                 *
                 * @param  object $this ReduxFramework
                 */
                do_action( "redux/page/{$this->args['opt_name']}/header", $this );
            } // admin_head()

            /**
             * Return footer text
             *
             * @since       2.0.0
             * @access      public
             * @return      string $this->args['footer_credit']
             */
            public function admin_footer_text() {
                return $this->args['footer_credit'];
            } // admin_footer_text()

            /**
             * Return default output string for use in panel
             *
             * @since       3.1.5
             * @access      public
             * @return      string default_output
             */
            private function get_default_output_string( $field ) {
                $default_output = "";

                if ( ! isset( $field['default'] ) ) {
                    $field['default'] = "";
                }

                if ( ! is_array( $field['default'] ) ) {
                    if ( ! empty( $field['options'][ $field['default'] ] ) ) {
                        if ( ! empty( $field['options'][ $field['default'] ]['alt'] ) ) {
                            $default_output .= $field['options'][ $field['default'] ]['alt'] . ', ';
                        } else {
                            // TODO: This serialize fix may not be the best solution. Look into it. PHP 5.4 error without serialize
                            if ( ! is_array( $field['options'][ $field['default'] ] ) ) {
                                $default_output .= $field['options'][ $field['default'] ] . ", ";
                            } else {
                                $default_output .= serialize( $field['options'][ $field['default'] ] ) . ", ";
                            }
                        }
                    } else if ( ! empty( $field['options'][ $field['default'] ] ) ) {
                        $default_output .= $field['options'][ $field['default'] ] . ", ";
                    } else if ( ! empty( $field['default'] ) ) {
                        if ( $field['type'] == 'switch' && isset( $field['on'] ) && isset( $field['off'] ) ) {
                            $default_output .= ( $field['default'] == 1 ? $field['on'] : $field['off'] ) . ', ';
                        } else {
                            $default_output .= $field['default'] . ', ';
                        }
                    }
                } else {
                    foreach ( $field['default'] as $defaultk => $defaultv ) {
                        if ( ! empty( $field['options'][ $defaultv ]['alt'] ) ) {
                            $default_output .= $field['options'][ $defaultv ]['alt'] . ', ';
                        } else if ( ! empty( $field['options'][ $defaultv ] ) ) {
                            $default_output .= $field['options'][ $defaultv ] . ", ";
                        } else if ( ! empty( $field['options'][ $defaultk ] ) ) {
                            $default_output .= $field['options'][ $defaultk ] . ", ";
                        } else if ( ! empty( $defaultv ) ) {
                            $default_output .= $defaultv . ', ';
                        }
                    }
                }

                if ( ! empty( $default_output ) ) {
                    $default_output = __( 'Default', 'redux-framework' ) . ": " . substr( $default_output, 0, - 2 );
                }

                if ( ! empty( $default_output ) ) {
                    $default_output = '<span class="showDefaults">' . $default_output . '</span><br class="default_br" />';
                }

                return $default_output;
            } // get_default_output_string()

            public function get_header_html( $field ) {
                global $current_user;

                // Set to empty string to avoid wanrings.
                $hint = '';
                $th   = "";

                if ( isset( $field['title'] ) && isset( $field['type'] ) && $field['type'] !== "info" && $field['type'] !== "section" ) {
                    $default_mark = ( ! empty( $field['default'] ) && isset( $this->options[ $field['id'] ] ) && $this->options[ $field['id'] ] == $field['default'] && ! empty( $this->args['default_mark'] ) && isset( $field['default'] ) ) ? $this->args['default_mark'] : '';

                    // If a hint is specified in the field, process it.
                    if ( isset( $field['hint'] ) && ! '' == $field['hint'] ) {

                        // Set show_hints flag to true, so helptab will be displayed.
                        $this->show_hints = true;

                        // Get user pref for displaying hints.
                        $metaVal = get_user_meta( $current_user->ID, 'ignore_hints', true );
                        if ( 'true' == $metaVal || empty( $metaVal ) ) {

                            // Set hand cursor for clickable hints
                            $pointer = '';
                            if ( isset( $this->args['hints']['tip_effect']['show']['event'] ) && 'click' == $this->args['hints']['tip_effect']['show']['event'] ) {
                                $pointer = 'pointer';
                            }

                            $size = '16px';
                            if ( 'large' == $this->args['hints']['icon_size'] ) {
                                $size = '18px';
                            }

                            // In case docs are ignored.
                            $titleParam   = isset( $field['hint']['title'] ) ? $field['hint']['title'] : '';
                            $contentParam = isset( $field['hint']['content'] ) ? $field['hint']['content'] : '';

                            $hint_color = isset( $this->args['hints']['icon_color'] ) ? $this->args['hints']['icon_color'] : '#d3d3d3';

                            // Set hint html with appropriate position css
                            $hint = '<div class="redux-hint-qtip" style="float:' . $this->args['hints']['icon_position'] . '; font-size: ' . $size . '; color:' . $hint_color . '; cursor: ' . $pointer . ';" qtip-title="' . $titleParam . '" qtip-content="' . $contentParam . '"><i class="el-icon-question-sign"></i>&nbsp&nbsp</div>';
                        }
                    }

                    if ( ! empty( $field['title'] ) ) {
                        if ( 'left' == $this->args['hints']['icon_position'] ) {
                            $th = $hint . $field['title'] . $default_mark . "";
                        } else {
                            $th = $field['title'] . $default_mark . "" . $hint;
                        }
                    }

                    if ( isset( $field['subtitle'] ) ) {
                        $th .= '<span class="description">' . $field['subtitle'] . '</span>';
                    }
                }

                if ( ! empty( $th ) ) {
                    $th = '<div class="redux_field_th">' . $th . '</div>';
                }

                if ( $this->args['default_show'] === true && isset( $field['default'] ) && isset( $this->options[ $field['id'] ] ) && $this->options[ $field['id'] ] != $field['default'] && $field['type'] !== "info" && $field['type'] !== "group" && $field['type'] !== "section" && $field['type'] !== "editor" && $field['type'] !== "ace_editor" ) {
                    $th .= $this->get_default_output_string( $field );
                }

                return $th;
            }


            /**
             * Register Option for use
             *
             * @since       1.0.0
             * @access      public
             * @return      void
             */
            public function _register_settings() {

                // TODO - REMOVE
                // Not used by new sample-config, but in here for legacy builds
                // This is bad and can break things. Hehe.
                if ( ! function_exists( 'wp_get_current_user' ) ) {
                    include( ABSPATH . "wp-includes/pluggable.php" );
                }

                register_setting( $this->args['opt_name'] . '_group', $this->args['opt_name'], array(
                    $this,
                    '_validate_options'
                ) );

                if ( is_null( $this->sections ) ) {
                    return;
                }

                $this->options_defaults = $this->_default_values();

                $runUpdate = false;

                foreach ( $this->sections as $k => $section ) {
                    if ( isset( $section['type'] ) && $section['type'] == 'divide' ) {
                        continue;
                    }

                    $display = true;

                    if ( isset( $_GET['page'] ) && $_GET['page'] == $this->args['page_slug'] ) {
                        if ( isset( $section['panel'] ) && $section['panel'] == false ) {
                            $display = false;
                        }
                    }

                    if ( ! $display ) {
                        continue;
                    }

                    // DOVY! Replace $k with $section['id'] when ready
                    /**
                     * filter 'redux-section-{index}-modifier-{opt_name}'
                     *
                     * @param array $section section configuration
                     */
                    $section = apply_filters( "redux-section-{$k}-modifier-{$this->args['opt_name']}", $section );

                    /**
                     * filter 'redux/options/{opt_name}/section/{section.id}'
                     *
                     * @param array $section section configuration
                     */
                    if ( isset( $section['id'] ) ) {
                        $section = apply_filters( "redux/options/{$this->args['opt_name']}/section/{$section['id']}", $section );
                    }

                    if ( ! isset( $section['title'] ) ) {
                        $section['title'] = "";
                    }

                    $heading = isset( $section['heading'] ) ? $section['heading'] : $section['title'];

                    if ( isset( $section['permissions'] ) ) {
                        if ( ! current_user_can( $section['permissions'] ) ) {
                            $this->hidden_perm_sections[] = $section['title'];

                            foreach ( $section['fields'] as $num => $field_data ) {
                                $field_type = $field_data['type'];

                                if ( $field_type != 'section' || $field_type != 'divide' || $field_type != 'info' || $field_type != 'raw' ) {
                                    $field_id = $field_data['id'];
                                    $default  = isset( $this->options_defaults[ $field_id ] ) ? $this->options_defaults[ $field_id ] : '';
                                    $data     = isset( $this->options[ $field_id ] ) ? $this->options[ $field_id ] : $default;

                                    $this->hidden_perm_fields[ $field_id ] = $data;
                                }
                            }

                            continue;
                        }
                    }

                    add_settings_section( $this->args['opt_name'] . $k . '_section', $heading, array(
                        &$this,
                        '_section_desc'
                    ), $this->args['opt_name'] . $k . '_section_group' );

                    $sectionIndent = false;
                    if ( isset( $section['fields'] ) ) {
                        foreach ( $section['fields'] as $fieldk => $field ) {
                            if ( ! isset( $field['type'] ) ) {
                                continue; // You need a type!
                            }

                            if ( isset( $field['customizer_only'] ) && $field['customizer_only'] == true ) {
                                continue; // ok
                            }

                            /**
                             * filter 'redux/options/{opt_name}/field/{field.id}'
                             *
                             * @param array $field field config
                             */
                            $field = apply_filters( "redux/options/{$this->args['opt_name']}/field/{$field['id']}/register", $field );

                            $display = true;
                            if ( isset( $_GET['page'] ) && $_GET['page'] == $this->args['page_slug'] ) {
                                if ( isset( $field['panel'] ) && $field['panel'] == false ) {
                                    $display = false;
                                }
                            }

                            if ( ! $display ) {
                                continue;
                            }

                            // TODO AFTER GROUP WORKS - Remove IF statement
//                            if ( $field['type'] == "group" && isset( $_GET['page'] ) && $_GET['page'] == $this->args['page_slug'] ) {
//                                if ( $this->args['dev_mode'] ) {
//                                    $this->admin_notices[] = array(
//                                        'type'    => 'error',
//                                        'msg'     => 'The <strong>group field</strong> has been <strong>removed</strong> while we retool it for improved performance.',
//                                        'id'      => 'group_err',
//                                        'dismiss' => true,
//                                    );
//                                }
//                                continue; // Disabled for now
//                            }


                            if ( isset( $field['permissions'] ) ) {

                                if ( ! current_user_can( $field['permissions'] ) ) {
                                    $data = isset( $this->options[ $field['id'] ] ) ? $this->options[ $field['id'] ] : $this->options_defaults[ $field['id'] ];

                                    $this->hidden_perm_fields[ $field['id'] ] = $data;

                                    continue;
                                }
                            }

                            if ( ! isset( $field['id'] ) ) {
                                echo '<br /><h3>No field ID is set.</h3><pre>';
                                print_r( $field );
                                echo "</pre><br />";
                                continue;
                            }

                            if ( isset( $field['type'] ) && $field['type'] == "section" ) {
                                if ( isset( $field['indent'] ) && $field['indent'] == true ) {
                                    $sectionIndent = true;
                                } else {
                                    $sectionIndent = false;
                                }
                            }

                            if ( isset( $field['type'] ) && $field['type'] == "info" && $sectionIndent ) {
                                $field['indent'] = $sectionIndent;
                            }

                            $th = $this->get_header_html( $field );

                            $field['name'] = $this->args['opt_name'] . '[' . $field['id'] . ']';

                            // Set the default value if present
                            $this->options_defaults[ $field['id'] ] = isset( $this->options_defaults[ $field['id'] ] ) ? $this->options_defaults[ $field['id'] ] : '';

                            // Set the defaults to the value if not present
                            $doUpdate = false;

                            // Check fields for values in the default parameter
                            if ( ! isset( $this->options[ $field['id'] ] ) && isset( $field['default'] ) ) {
                                $this->options_defaults[ $field['id'] ] = $this->options[ $field['id'] ] = $field['default'];
                                $doUpdate                               = true;

                                // Check fields that hae no default value, but an options value with settings to
                                // be saved by default
                            } elseif ( ! isset( $this->options[ $field['id'] ] ) && isset( $field['options'] ) ) {

                                // If sorter field, check for options as save them as defaults
                                if ( $field['type'] == 'sorter' || $field['type'] == 'sortable' ) {
                                    $this->options_defaults[ $field['id'] ] = $this->options[ $field['id'] ] = $field['options'];
                                    $doUpdate                               = true;
                                }
                            }

                            // CORRECT URLS if media URLs are wrong, but attachment IDs are present.
                            if ( $field['type'] == "media" ) {
                                if ( isset( $this->options[ $field['id'] ]['id'] ) && isset( $this->options[ $field['id'] ]['url'] ) && ! empty( $this->options[ $field['id'] ]['url'] ) && strpos( $this->options[ $field['id'] ]['url'], str_replace( 'http://', '', WP_CONTENT_URL ) ) === false ) {
                                    $data = wp_get_attachment_url( $this->options[ $field['id'] ]['id'] );

                                    if ( isset( $data ) && ! empty( $data ) ) {
                                        $this->options[ $field['id'] ]['url']       = $data;
                                        $data                                       = wp_get_attachment_image_src( $this->options[ $field['id'] ]['id'], array(
                                            150,
                                            150
                                        ) );
                                        $this->options[ $field['id'] ]['thumbnail'] = $data[0];
                                        $doUpdate                                   = true;
                                    }
                                }
                            }

                            if ( $field['type'] == "background" ) {
                                if ( isset( $this->options[ $field['id'] ]['media']['id'] ) && isset( $this->options[ $field['id'] ]['background-image'] ) && ! empty( $this->options[ $field['id'] ]['background-image'] ) && strpos( $this->options[ $field['id'] ]['background-image'], str_replace( 'http://', '', WP_CONTENT_URL ) ) === false ) {
                                    $data = wp_get_attachment_url( $this->options[ $field['id'] ]['media']['id'] );

                                    if ( isset( $data ) && ! empty( $data ) ) {
                                        $this->options[ $field['id'] ]['background-image']   = $data;
                                        $data                                                = wp_get_attachment_image_src( $this->options[ $field['id'] ]['media']['id'], array(
                                            150,
                                            150
                                        ) );
                                        $this->options[ $field['id'] ]['media']['thumbnail'] = $data[0];
                                        $doUpdate                                            = true;
                                    }
                                }
                            }

                            if ( $field['type'] == "slides" ) {
                                if ( isset( $this->options[ $field['id'] ][0]['attachment_id'] ) && isset( $this->options[ $field['id'] ][0]['image'] ) && ! empty( $this->options[ $field['id'] ][0]['image'] ) && strpos( $this->options[ $field['id'] ][0]['image'], str_replace( 'http://', '', WP_CONTENT_URL ) ) === false ) {
                                    foreach ( $this->options[ $field['id'] ] as $k => $v ) {
                                        $data = wp_get_attachment_url( $v['attachment_id'] );

                                        if ( isset( $data ) && ! empty( $data ) ) {
                                            $this->options[ $field['id'] ][ $k ]['image'] = $data;
                                            $data                                         = wp_get_attachment_image_src( $v['attachment_id'], array(
                                                150,
                                                150
                                            ) );
                                            $this->options[ $field['id'] ][ $k ]['thumb'] = $data[0];
                                            $doUpdate                                     = true;
                                        }
                                    }
                                }
                            }
                            // END -> CORRECT URLS if media URLs are wrong, but attachment IDs are present.

                            if ( true == $doUpdate && ! isset( $this->never_save_to_db ) ) {
                                if ( $this->args['save_defaults'] ) { // Only save that to the DB if allowed to
                                    $runUpdate = true;
                                }
                                // elseif($this->saved != '' && $this->saved != false) {
                                // $runUpdate = true;
                                //}
                            }

                            if ( ! isset( $field['class'] ) ) { // No errors please
                                $field['class'] = "";
                            }
                            $id = $field['id'];

                            /**
                             * filter 'redux-field-{field.id}modifier-{opt_name}'
                             *
                             * @deprecated
                             *
                             * @param array $field field config
                             */
                            $field = apply_filters( "redux-field-{$field['id']}modifier-{$this->args['opt_name']}", $field ); // REMOVE LATER

                            /**
                             * filter 'redux/options/{opt_name}/field/{field.id}'
                             *
                             * @param array $field field config
                             */
                            $field = apply_filters( "redux/options/{$this->args['opt_name']}/field/{$field['id']}", $field );

                            if ( empty( $field ) || ! $field || $field == false ) {
                                unset( $this->sections[ $k ]['fields'][ $fieldk ] );
                                continue;
                            }

                            if ( ! empty( $this->folds[ $field['id'] ]['parent'] ) ) { // This has some fold items, hide it by default
                                $field['class'] .= " fold";
                            }

                            if ( ! empty( $this->folds[ $field['id'] ]['children'] ) ) { // Sets the values you shoe fold children on
                                $field['class'] .= " foldParent";
                            }

                            if ( ! empty( $field['compiler'] ) ) {
                                $field['class'] .= " compiler";
                                $this->compiler_fields[ $field['id'] ] = 1;
                            }

                            if ( isset( $field['unit'] ) && ! isset( $field['units'] ) ) {
                                $field['units'] = $field['unit'];
                                unset( $field['unit'] );
                            }

                            $this->sections[ $k ]['fields'][ $fieldk ] = $field;

                            if ( isset( $this->args['display_source'] ) ) {
                                $th .= '<div id="' . $field['id'] . '-settings" style="display:none;"><pre>' . var_export( $this->sections[ $k ]['fields'][ $fieldk ], true ) . '</pre></div>';
                                $th .= '<br /><a href="#TB_inline?width=600&height=800&inlineId=' . $field['id'] . '-settings" class="thickbox"><small>View Source</small></a>';
                            }

                            /**
                             * action 'redux/options/{opt_name}/field/field.type}/register'
                             */
                            do_action( "redux/options/{$this->args['opt_name']}/field/{$field['type']}/register", $field );

                            $this->check_dependencies( $field );

                            add_settings_field(
                                "{$fieldk}_field",
                                $th,
                                array( &$this, '_field_input' ),
                                "{$this->args['opt_name']}{$k}_section_group",
                                "{$this->args['opt_name']}{$k}_section",
                                $field
                            ); // checkbox
                        }
                    }
                }

                /**
                 * action 'redux-register-settings-{opt_name}'
                 *
                 * @deprecated
                 */
                do_action( "redux-register-settings-{$this->args['opt_name']}" ); // REMOVE

                /**
                 * action 'redux/options/{opt_name}/register'
                 *
                 * @param array option sections
                 */
                do_action( "redux/options/{$this->args['opt_name']}/register", $this->sections );

                if ( $runUpdate && ! isset( $this->never_save_to_db ) ) { // Always update the DB with new fields
                    $this->set_options( $this->options );
                }

                if ( isset( $this->transients['run_compiler'] ) && $this->transients['run_compiler'] ) {
                    $this->args['output_tag'] = false;
                    $this->_enqueue_output();


                    /**
                     * action 'redux-compiler-{opt_name}'
                     *
                     * @deprecated
                     *
                     * @param array  options
                     * @param string CSS that get sent to the compiler hook
                     */
                    do_action( "redux-compiler-{$this->args['opt_name']}", $this->options, $this->compilerCSS, $this->transients['changed_values'] ); // REMOVE

                    /**
                     * action 'redux/options/{opt_name}/compiler'
                     *
                     * @param array  options
                     * @param string CSS that get sent to the compiler hook
                     */
                    do_action( "redux/options/{$this->args['opt_name']}/compiler", $this->options, $this->compilerCSS, $this->transients['changed_values'] );

                    unset( $this->transients['run_compiler'] );
                    $this->set_transients();
                }
            } // _register_settings()

            /**
             * Register Extensions for use
             *
             * @since       3.0.0
             * @access      public
             * @return      void
             */
            private function _register_extensions() {
                $path    = dirname( __FILE__ ) . '/extensions/';
                $folders = scandir( $path, 1 );

                /**
                 * action 'redux/extensions/{opt_name}/before'
                 *
                 * @param object $this ReduxFramework
                 */
                do_action( "redux/extensions/{$this->args['opt_name']}/before", $this );

                foreach ( $folders as $folder ) {
                    if ( $folder === '.' || $folder === '..' || ! is_dir( $path . $folder ) || substr( $folder, 0, 1 ) === '.' || substr( $folder, 0, 1 ) === '@' ) {
                        continue;
                    }

                    $extension_class = 'ReduxFramework_Extension_' . $folder;

                    /**
                     * filter 'redux-extensionclass-load'
                     *
                     * @deprecated
                     *
                     * @param        string                    extension class file path
                     * @param string $extension_class          extension class name
                     */
                    $class_file = apply_filters( "redux-extensionclass-load", "$path/$folder/extension_{$folder}.php", $extension_class ); // REMOVE LATER

                    /**
                     * filter 'redux/extension/{opt_name}/{folder}'
                     *
                     * @param        string                    extension class file path
                     * @param string $extension_class          extension class name
                     */
                    $class_file = apply_filters( "redux/extension/{$this->args['opt_name']}/$folder", "$path/$folder/extension_{$folder}.php", $class_file );

                    if ( $class_file ) {
                        if ( file_exists( $class_file ) ) {
                            require_once( $class_file );
                        }

                        $this->extensions[ $folder ] = new $extension_class( $this );
                    }

                }

                /**
                 * action 'redux-register-extensions-{opt_name}'
                 *
                 * @deprecated
                 *
                 * @param object $this ReduxFramework
                 */
                do_action( "redux-register-extensions-{$this->args['opt_name']}", $this ); // REMOVE

                /**
                 * action 'redux/extensions/{opt_name}'
                 *
                 * @param object $this ReduxFramework
                 */
                do_action( "redux/extensions/{$this->args['opt_name']}", $this );
            }

            private function get_transients() {
                if ( ! isset( $this->transients ) ) {
                    $this->transients       = get_option( $this->args['opt_name'] . '-transients', array() );
                    $this->transients_check = $this->transients;
                }
            }

            private function set_transients() {
                if ( ! isset( $this->transients ) || ! isset( $this->transients_check ) || $this->transients != $this->transients_check ) {
                    update_option( $this->args['opt_name'] . '-transients', $this->transients );
                    $this->transients_check = $this->transients;
                }
            }

            /**
             * Validate the Options options before insertion
             *
             * @since       3.0.0
             * @access      public
             *
             * @param       array $plugin_options The options array
             *
             * @return array|mixed|string|void
             */
            public function _validate_options( $plugin_options ) {

                if ( ! empty( $this->hidden_perm_fields ) && is_array( $this->hidden_perm_fields ) ) {
                    foreach ( $this->hidden_perm_fields as $id => $data ) {
                        $plugin_options[ $id ] = $data;
                    }
                }

                if ( $plugin_options == $this->options ) {
                    return $plugin_options;
                }

                $time = time();

                // Sets last saved time
                $this->transients['last_save'] = $time;

                // Import
                if ( ! empty( $plugin_options['import'] ) ) {
                    $this->transients['last_save_mode'] = "import"; // Last save mode
                    $this->transients['last_compiler']  = $time;
                    $this->transients['last_import']    = $time;
                    $this->transients['run_compiler']   = 1;

                    if ( $plugin_options['import_code'] != '' ) {
                        $import = $plugin_options['import_code'];
                    } elseif ( $plugin_options['import_link'] != '' ) {
                        $import = wp_remote_retrieve_body( wp_remote_get( $plugin_options['import_link'] ) );
                    }

                    if ( ! empty( $import ) ) {
                        $imported_options = json_decode( $import, true );
                    }

                    if ( ! empty( $imported_options ) && is_array( $imported_options ) && isset( $imported_options['redux-backup'] ) && $imported_options['redux-backup'] == '1' ) {

                        $this->transients['changed_values'] = array();
                        foreach ( $plugin_options as $key => $value ) {
                            if ( isset( $imported_options[ $key ] ) && $imported_options[ $key ] != $value ) {
                                $this->transients['changed_values'][ $key ] = $value;
                                $plugin_options[ $key ]                     = $value;
                            }
                        }

                        /**
                         * action 'redux/options/{opt_name}/import'
                         *
                         * @param  &array [&$plugin_options, redux_options]
                         */
                        do_action_ref_array( "redux/options/{$this->args['opt_name']}/import", array(
                            &$plugin_options,
                            $imported_options,
                            $this->transients['changed_values']
                        ) );

                        // Remove the import/export tab cookie.
                        if ( $_COOKIE['redux_current_tab'] == 'import_export_default' ) {
                            setcookie( 'redux_current_tab', '', 1, '/' );
                            $_COOKIE['redux_current_tab'] = 1;
                        }

                        setcookie( 'redux_current_tab', '', 1, '/', $time + 1000, "/" );
                        $_COOKIE['redux_current_tab'] = 1;

                        unset( $plugin_options['defaults'], $plugin_options['compiler'], $plugin_options['import'], $plugin_options['import_code'] );
                        if ( $this->args['database'] == 'transient' || $this->args['database'] == 'theme_mods' || $this->args['database'] == 'theme_mods_expanded' || $this->args['database'] == 'network' ) {
                            $this->set_options( $plugin_options );

                            return;
                        }

                        $plugin_options = wp_parse_args( $imported_options, $plugin_options );
                        $this->set_transients(); // Update the transients

                        return $plugin_options;
                    }
                }

                // Reset all to defaults
                if ( ! empty( $plugin_options['defaults'] ) ) {
                    if ( empty( $this->options_defaults ) ) {
                        $this->options_defaults = $this->_default_values();
                    }

                    // Section reset
                    //setcookie('redux-compiler-' . $this->args['opt_name'], 1, time() + 3000, '/');
                    $plugin_options = $this->options_defaults;

                    $this->transients['changed_values'] = array();
                    foreach ( $this->options as $key => $value ) {
                        if ( isset( $plugin_options[ $key ] ) && $value != $plugin_options[ $key ] ) {
                            $this->transients['changed_values'][ $key ] = $value;
                        }
                    }

                    $this->transients['run_compiler']   = 1;
                    $this->transients['last_save_mode'] = "defaults"; // Last save mode

                    //setcookie('redux-compiler-' . $this->args['opt_name'], 1, time() + 1000, "/");
                    //setcookie("redux-saved-{$this->args['opt_name']}", 'defaults', time() + 1000, "/");
                    $this->set_transients(); // Update the transients

                    return $plugin_options;
                }

                // Section reset to defaults
                if ( ! empty( $plugin_options['defaults-section'] ) ) {
                    if ( isset( $plugin_options['redux-section'] ) && isset( $this->sections[ $plugin_options['redux-section'] ]['fields'] ) ) {
                        foreach ( $this->sections[ $plugin_options['redux-section'] ]['fields'] as $field ) {
                            if ( isset( $this->options_defaults[ $field['id'] ] ) ) {
                                $plugin_options[ $field['id'] ] = $this->options_defaults[ $field['id'] ];
                            } else {
                                $plugin_options[ $field['id'] ] = "";
                            }

                            if ( isset( $field['compiler'] ) ) {
                                $compiler = true;
                            }
                        }
                    }

                    $this->transients['changed_values'] = array();
                    foreach ( $this->options as $key => $value ) {
                        if ( isset( $plugin_options[ $key ] ) && $value != $plugin_options[ $key ] ) {
                            $this->transients['changed_values'][ $key ] = $value;
                        }
                    }

                    if ( isset( $compiler ) ) {
                        //$this->run_compiler = true;
                        //setcookie('redux-compiler-' . $this->args['opt_name'], 1, time()+1000, '/');
                        //$plugin_options['REDUX_COMPILER'] = time();
                        $this->transients['last_compiler'] = $time;
                        $this->transients['run_compiler']  = 1;
                    }

                    $this->transients['last_save_mode'] = "defaults_section"; // Last save mode

                    //setcookie("redux-saved-{$this->args['opt_name']}", 'defaults_section', time() + 1000, "/");
                    unset( $plugin_options['defaults'], $plugin_options['defaults_section'], $plugin_options['import'], $plugin_options['import_code'], $plugin_options['import_link'], $plugin_options['compiler'], $plugin_options['redux-section'] );
                    $this->set_transients();

                    return $plugin_options;
                }

                $this->transients['last_save_mode'] = "normal"; // Last save mode

                // Validate fields (if needed)
                $plugin_options = $this->_validate_values( $plugin_options, $this->options, $this->sections );

                if ( ! empty( $this->errors ) || ! empty( $this->warnings ) ) {
                    $this->transients['notices'] = array( 'errors' => $this->errors, 'warnings' => $this->warnings );
                }

                /**
                 * action 'redux-validate-{opt_name}'
                 *
                 * @deprecated
                 *
                 * @param  &array [&$plugin_options, redux_options]
                 */
                do_action_ref_array( "redux-validate-{$this->args['opt_name']}", array(
                    &$plugin_options,
                    $this->options
                ) ); // REMOVE

                /**
                 * action 'redux/options/{opt_name}/validate'
                 *
                 * @param  &array [&$plugin_options, redux_options]
                 */
                do_action_ref_array( "redux/options/{$this->args['opt_name']}/validate", array(
                    &$plugin_options,
                    $this->options,
                    $this->transients['changed_values']
                ) );

                if ( ! empty( $plugin_options['compiler'] ) ) {
                    unset( $plugin_options['compiler'] );

                    $this->transients['last_compiler'] = $time;
                    $this->transients['run_compiler']  = 1;
                }

                $this->transients['changed_values'] = array(); // Changed values since last save
                foreach ( $this->options as $key => $value ) {
                    if ( isset( $plugin_options[ $key ] ) && $value != $plugin_options[ $key ] ) {
                        $this->transients['changed_values'][ $key ] = $value;
                    }
                }

                unset( $plugin_options['defaults'], $plugin_options['defaults_section'], $plugin_options['import'], $plugin_options['import_code'], $plugin_options['import_link'], $plugin_options['compiler'], $plugin_options['redux-section'] );
                if ( $this->args['database'] == 'transient' || $this->args['database'] == 'theme_mods' || $this->args['database'] == 'theme_mods_expanded' ) {
                    $this->set_options( $plugin_options );

                    return;
                }

                if ( defined( 'WP_CACHE' ) && WP_CACHE && class_exists( 'W3_ObjectCache' ) ) {
                    //echo "here";
                    $w3  = W3_ObjectCache::instance();
                    $key = $w3->_get_cache_key( $this->args['opt_name'] . '-transients', 'transient' );
                    //echo $key;
                    $w3->delete( $key, 'transient', true );
                    //set_transient($this->args['opt_name'].'-transients', $this->transients);
                    //exit();
                }

                $this->set_transients( $this->transients );


                return $plugin_options;
            }

            /**
             * Validate values from options form (used in settings api validate function)
             * calls the custom validation class for the field so authors can override with custom classes
             *
             * @since       1.0.0
             * @access      public
             *
             * @param       array $plugin_options
             * @param       array $options
             *
             * @return      array $plugin_options
             */
            public function _validate_values( $plugin_options, $options, $sections ) {
                foreach ( $sections as $k => $section ) {
                    if ( isset( $section['fields'] ) ) {
                        foreach ( $section['fields'] as $fkey => $field ) {
                            $field['section_id'] = $k;

                            if ( isset( $field['type'] ) && ( $field['type'] == 'checkbox' || $field['type'] == 'checkbox_hide_below' || $field['type'] == 'checkbox_hide_all' ) ) {
                                if ( ! isset( $plugin_options[ $field['id'] ] ) ) {
                                    $plugin_options[ $field['id'] ] = 0;
                                }
                            }

                            // Default 'not_empty 'flag to false.
                            $isNotEmpty = false;

                            // Make sure 'validate' field is set.
                            if ( isset( $field['validate'] ) ) {

                                // Make sure 'validate field' is set to 'not_empty' or 'email_not_empty'
                                if ( $field['validate'] == 'not_empty' || $field['validate'] == 'email_not_empty' || $field['validate'] == 'numeric_not_empty' ) {

                                    // Set the flag.
                                    $isNotEmpty = true;
                                }
                            }

                            // Check for empty id value

                            if ( ! isset( $plugin_options[ $field['id'] ] ) || $plugin_options[ $field['id'] ] == '' ) {

                                // If we are looking for an empty value, in the case of 'not_empty'
                                // then we need to keep processing.
                                if ( ! $isNotEmpty ) {

                                    // Empty id and not checking for 'not_empty.  Bail out...
                                    continue;
                                }
                            }

                            // Force validate of custom field types
                            if ( isset( $field['type'] ) && ! isset( $field['validate'] ) ) {
                                if ( $field['type'] == 'color' || $field['type'] == 'color_gradient' ) {
                                    $field['validate'] = 'color';
                                } elseif ( $field['type'] == 'date' ) {
                                    $field['validate'] = 'date';
                                }
                            }

                            if ( isset( $field['validate'] ) ) {
                                $validate = 'Redux_Validation_' . $field['validate'];

                                if ( ! class_exists( $validate ) ) {
                                    /**
                                     * filter 'redux-validateclass-load'
                                     *
                                     * @deprecated
                                     *
                                     * @param        string             validation class file path
                                     * @param string $validate          validation class name
                                     */
                                    $class_file = apply_filters( "redux-validateclass-load", self::$_dir . "inc/validation/{$field['validate']}/validation_{$field['validate']}.php", $validate ); // REMOVE LATER

                                    /**
                                     * filter 'redux/validate/{opt_name}/class/{field.validate}'
                                     *
                                     * @param        string                validation class file path
                                     * @param string $class_file           validation class file path
                                     */
                                    $class_file = apply_filters( "redux/validate/{$this->args['opt_name']}/class/{$field['validate']}", self::$_dir . "inc/validation/{$field['validate']}/validation_{$field['validate']}.php", $class_file );

                                    if ( $class_file ) {
                                        if ( file_exists( $class_file ) ) {
                                            require_once( $class_file );
                                        }
                                    }
                                }

                                if ( class_exists( $validate ) ) {

                                    //!DOVY - DB saving stuff. Is this right?
                                    if ( empty ( $options[ $field['id'] ] ) ) {
                                        $options[ $field['id'] ] = '';
                                    }

                                    if ( isset( $plugin_options[ $field['id'] ] ) && is_array( $plugin_options[ $field['id'] ] ) && ! empty( $plugin_options[ $field['id'] ] ) ) {
                                        foreach ( $plugin_options[ $field['id'] ] as $key => $value ) {
                                            $before = $after = null;
                                            if ( isset( $plugin_options[ $field['id'] ][ $key ] ) && ! empty( $plugin_options[ $field['id'] ][ $key ] ) ) {
                                                if ( is_array( $plugin_options[ $field['id'] ][ $key ] ) ) {
                                                    $before = $plugin_options[ $field['id'] ][ $key ];
                                                } else {
                                                    $before = trim( $plugin_options[ $field['id'] ][ $key ] );
                                                }
                                            }

                                            if ( isset( $options[ $field['id'] ][ $key ] ) && ! empty( $options[ $field['id'] ][ $key ] ) ) {
                                                $after = $options[ $field['id'] ][ $key ];
                                            }

                                            $validation = new $validate( $this, $field, $before, $after );
                                            if ( ! empty( $validation->value ) ) {
                                                $plugin_options[ $field['id'] ][ $key ] = $validation->value;
                                            } else {
                                                unset( $plugin_options[ $field['id'] ][ $key ] );
                                            }

                                            if ( isset( $validation->error ) ) {
                                                $this->errors[] = $validation->error;
                                            }

                                            if ( isset( $validation->warning ) ) {
                                                $this->warnings[] = $validation->warning;
                                            }
                                        }
                                    } else {
                                        if ( is_array( $plugin_options[ $field['id'] ] ) ) {
                                            $pofi = $plugin_options[ $field['id'] ];
                                        } else {
                                            $pofi = trim( $plugin_options[ $field['id'] ] );
                                        }

                                        $validation                     = new $validate( $this, $field, $pofi, $options[ $field['id'] ] );
                                        $plugin_options[ $field['id'] ] = $validation->value;

                                        if ( isset( $validation->error ) ) {
                                            $this->errors[] = $validation->error;
                                        }

                                        if ( isset( $validation->warning ) ) {
                                            $this->warnings[] = $validation->warning;
                                        }
                                    }

                                    continue;
                                }
                            }

                            if ( isset( $field['validate_callback'] ) && function_exists( $field['validate_callback'] ) ) {
                                $callbackvalues                 = call_user_func( $field['validate_callback'], $field, $plugin_options[ $field['id'] ], $options[ $field['id'] ] );
                                $plugin_options[ $field['id'] ] = $callbackvalues['value'];

                                if ( isset( $callbackvalues['error'] ) ) {
                                    $this->errors[] = $callbackvalues['error'];
                                }

                                if ( isset( $callbackvalues['warning'] ) ) {
                                    $this->warnings[] = $callbackvalues['warning'];
                                }
                            }
                        }
                    }
                }

                return $plugin_options;
            }

            /**
             * Return Section Menu HTML
             *
             * @since       3.1.5
             * @access      public
             * @return      void
             */
            public function section_menu( $k, $section, $suffix = "", $sections = array() ) {
                $display = true;

                $section['class'] = isset( $section['class'] ) ? ' ' . $section['class'] : '';

                if ( isset( $_GET['page'] ) && $_GET['page'] == $this->args['page_slug'] ) {
                    if ( isset( $section['panel'] ) && $section['panel'] == false ) {
                        $display = false;
                    }
                }

                if ( ! $display ) {
                    return "";
                }

                if ( empty( $sections ) ) {
                    $sections = $this->sections;
                }

                $string = "";
                if ( ( isset( $this->args['icon_type'] ) && $this->args['icon_type'] == 'image' ) || ( isset( $section['icon_type'] ) && $section['icon_type'] == 'image' ) ) {
                    //if( !empty( $this->args['icon_type'] ) && $this->args['icon_type'] == 'image' ) {
                    $icon = ( ! isset( $section['icon'] ) ) ? '' : '<img class="image_icon_type" src="' . $section['icon'] . '" /> ';
                } else {
                    if ( ! empty( $section['icon_class'] ) ) {
                        $icon_class = ' ' . $section['icon_class'];
                    } elseif ( ! empty( $this->args['default_icon_class'] ) ) {
                        $icon_class = ' ' . $this->args['default_icon_class'];
                    } else {
                        $icon_class = '';
                    }
                    $icon = ( ! isset( $section['icon'] ) ) ? '<i class="el-icon-cog' . $icon_class . '"></i> ' : '<i class="' . $section['icon'] . $icon_class . '"></i> ';
                }

                $canBeSubSection = ( $k > 0 && ( ! isset( $sections[ ( $k ) ]['type'] ) || $sections[ ( $k ) ]['type'] != "divide" ) ) ? true : false;

                if ( ! $canBeSubSection && isset( $section['subsection'] ) && $section['subsection'] == true ) {
                    unset( $section['subsection'] );
                }

                if ( isset( $section['type'] ) && $section['type'] == "divide" ) {
                    $string .= '<li class="divide' . $section['class'] . '">&nbsp;</li>';
                } else if ( ! isset( $section['subsection'] ) || $section['subsection'] != true ) {

                    // DOVY! REPLACE $k with $section['ID'] when used properly.
                    //$active = ( ( is_numeric($this->current_tab) && $this->current_tab == $k ) || ( !is_numeric($this->current_tab) && $this->current_tab === $k )  ) ? ' active' : '';
                    $subsections      = ( isset( $sections[ ( $k + 1 ) ] ) && isset( $sections[ ( $k + 1 ) ]['subsection'] ) && $sections[ ( $k + 1 ) ]['subsection'] == true ) ? true : false;
                    $subsectionsClass = $subsections ? ' hasSubSections' : '';
                    $extra_icon       = $subsections ? '<span class="extraIconSubsections"><i class="el el-icon-chevron-down">&nbsp;</i></span>' : '';
                    $string .= '<li id="' . $k . $suffix . '_section_group_li" class="redux-group-tab-link-li' . $section['class'] . $subsectionsClass . '">';
                    $string .= '<a href="javascript:void(0);" id="' . $k . $suffix . '_section_group_li_a" class="redux-group-tab-link-a" data-key="' . $k . '" data-rel="' . $k . $suffix . '">' . $extra_icon . $icon . '<span class="group_title">' . $section['title'] . '</span></a>';
                    $nextK = $k;

                    // Make sure you can make this a subsection
                    if ( $subsections ) {
                        $string .= '<ul id="' . $nextK . $suffix . '_section_group_li_subsections" class="subsection">';
                        $doLoop = true;

                        while ( $doLoop ) {
                            $nextK += 1;
                            $display = true;

                            if ( isset( $_GET['page'] ) && $_GET['page'] == $this->args['page_slug'] ) {
                                if ( isset( $sections[ $nextK ]['panel'] ) && $sections[ $nextK ]['panel'] == false ) {
                                    $display = false;
                                }
                            }

                            if ( count( $sections ) < $nextK || ! isset( $sections[ $nextK ] ) || ! isset( $sections[ $nextK ]['subsection'] ) || $sections[ $nextK ]['subsection'] != true ) {
                                $doLoop = false;
                            } else {
                                if ( ! $display ) {
                                    continue;
                                }

                                if ( ( isset( $this->args['icon_type'] ) && $this->args['icon_type'] == 'image' ) || ( isset( $sections[ $nextK ]['icon_type'] ) && $sections[ $nextK ]['icon_type'] == 'image' ) ) {
                                    //if( !empty( $this->args['icon_type'] ) && $this->args['icon_type'] == 'image' ) {
                                    $icon = ( ! isset( $sections[ $nextK ]['icon'] ) ) ? '' : '<img class="image_icon_type" src="' . $sections[ $nextK ]['icon'] . '" /> ';
                                } else {
                                    if ( ! empty( $sections[ $nextK ]['icon_class'] ) ) {
                                        $icon_class = ' ' . $sections[ $nextK ]['icon_class'];
                                    } elseif ( ! empty( $this->args['default_icon_class'] ) ) {
                                        $icon_class = ' ' . $this->args['default_icon_class'];
                                    } else {
                                        $icon_class = '';
                                    }
                                    $icon = ( ! isset( $sections[ $nextK ]['icon'] ) ) ? '' : '<i class="' . $sections[ $nextK ]['icon'] . $icon_class . '"></i> ';
                                }
                                $section[ $nextK ]['class'] = isset( $section[ $nextK ]['class'] ) ? $section[ $nextK ]['class'] : '';
                                $string .= '<li id="' . $nextK . $suffix . '_section_group_li" class="redux-group-tab-link-li ' . $section[ $nextK ]['class'] . ( $icon ? ' hasIcon' : '' ) . '">';
                                $string .= '<a href="javascript:void(0);" id="' . $nextK . $suffix . '_section_group_li_a" class="redux-group-tab-link-a" data-key="' . $nextK . '" data-rel="' . $nextK . $suffix . '">' . $icon . '<span class="group_title">' . $sections[ $nextK ]['title'] . '</span></a>';
                                $string .= '</li>';
                            }
                        }

                        $string .= '</ul>';
                    }

                    $string .= '</li>';
                }

                return $string;

            } // section_menu()


            /**
             * HTML OUTPUT.
             *
             * @since       1.0.0
             * @access      public
             * @return      void
             */
            public function _options_page_html() {
                echo '<div class="wrap"><h2></h2></div>'; // Stupid hack for Wordpress alerts and warnings

                echo '<div class="clear"></div>';
                echo '<div class="wrap">';

                // Do we support JS?
                echo '<noscript><div class="no-js">' . __( 'Warning- This options panel will not work properly without javascript!', 'redux-framework' ) . '</div></noscript>';

                // Security is vital!
                echo '<input type="hidden" id="ajaxsecurity" name="security" value="' . wp_create_nonce( 'redux_ajax_nonce' ) . '" />';

                /**
                 * action 'redux-page-before-form-{opt_name}'
                 *
                 * @deprecated
                 */
                do_action( "redux-page-before-form-{$this->args['opt_name']}" ); // Remove

                /**
                 * action 'redux/page/{opt_name}/form/before'
                 *
                 * @param object $this ReduxFramework
                 */
                do_action( "redux/page/{$this->args['opt_name']}/form/before", $this );

                // Main container
                $expanded = ( $this->args['open_expanded'] ) ? ' fully-expanded' : '';

                echo '<div class="redux-container' . $expanded . ( ! empty( $this->args['class'] ) ? ' ' . $this->args['class'] : '' ) . '">';
                $url = './options.php';
                if ( $this->args['database'] == "network" && $this->args['network_admin'] ) {
                    if ( is_network_admin() ) {
                        $url = './edit.php?action=redux_' . $this->args['opt_name'];
                    }
                }
                echo '<form method="post" action="' . $url . '" enctype="multipart/form-data" id="redux-form-wrapper">';
                echo '<input type="hidden" id="redux-compiler-hook" name="' . $this->args['opt_name'] . '[compiler]" value="" />';
                echo '<input type="hidden" id="currentSection" name="' . $this->args['opt_name'] . '[redux-section]" value="" />';

                settings_fields( "{$this->args['opt_name']}_group" );

                // Last tab?
                $this->options['last_tab'] = ( isset( $_GET['tab'] ) && ! isset( $this->transients['last_save_mode'] ) ) ? $_GET['tab'] : '';

                echo '<input type="hidden" id="last_tab" name="' . $this->args['opt_name'] . '[last_tab]" value="' . $this->options['last_tab'] . '" />';

                // Header area
                echo '<div id="redux-header">';

                if ( ! empty( $this->args['display_name'] ) ) {
                    echo '<div class="display_header">';

                    if ( isset( $this->args['dev_mode'] ) && $this->args['dev_mode'] ) {
                        echo '<span class="redux-dev-mode-notice">' . __( 'Developer Mode Enabled', 'redux-framework' ) . '</span>';
                    }

                    echo '<h2>' . $this->args['display_name'] . '</h2>';

                    if ( ! empty( $this->args['display_version'] ) ) {
                        echo '<span>' . $this->args['display_version'] . '</span>';
                    }

                    echo '</div>';
                }

                // Page icon
                // DOVY!
                echo '<div id="' . $this->args['page_icon'] . '" class="icon32"></div>';

                echo '<div class="clear"></div>';
                echo '</div>';

                // Intro text
                if ( isset( $this->args['intro_text'] ) ) {
                    echo '<div id="redux-intro-text">';
                    echo $this->args['intro_text'];
                    echo '</div>';
                }

                // Stickybar
                echo '<div id="redux-sticky">';
                echo '<div id="info_bar">';

                $expanded = ( $this->args['open_expanded'] ) ? ' expanded' : '';

                echo '<a href="javascript:void(0);" class="expand_options' . $expanded . '">' . __( 'Expand', 'redux-framework' ) . '</a>';
                echo '<div class="redux-action_bar">';
                submit_button( __( 'Save Changes', 'redux-framework' ), 'primary', 'redux_save', false );

                if ( false === $this->args['hide_reset'] ) {
                    echo '&nbsp;';
                    submit_button( __( 'Reset Section', 'redux-framework' ), 'secondary', $this->args['opt_name'] . '[defaults-section]', false );
                    echo '&nbsp;';
                    submit_button( __( 'Reset All', 'redux-framework' ), 'secondary', $this->args['opt_name'] . '[defaults]', false );
                }

                echo '</div>';

                echo '<div class="redux-ajax-loading" alt="' . __( 'Working...', 'redux-framework' ) . '">&nbsp;</div>';
                echo '<div class="clear"></div>';
                echo '</div>';

                // Warning bar
                if ( isset( $this->transients['last_save_mode'] ) ) {

                    if ( $this->transients['last_save_mode'] == "import" ) {
                        /**
                         * action 'redux/options/{opt_name}/import'
                         *
                         * @param object $this ReduxFramework
                         */
                        do_action( "redux/options/{$this->args['opt_name']}/import", $this, $this->transients['changed_values'] );

                        /**
                         * filter 'redux-imported-text-{opt_name}'
                         *
                         * @param string  translated "settings imported" text
                         */
                        echo '<div class="admin-notice notice-blue saved_notice"><strong>' . apply_filters( "redux-imported-text-{$this->args['opt_name']}", __( 'Settings Imported!', 'redux-framework' ) ) . '</strong></div>';
                        //exit();
                    } else if ( $this->transients['last_save_mode'] == "defaults" ) {
                        /**
                         * action 'redux/options/{opt_name}/reset'
                         *
                         * @param object $this ReduxFramework
                         */
                        do_action( "redux/options/{$this->args['opt_name']}/reset", $this );

                        /**
                         * filter 'redux-defaults-text-{opt_name}'
                         *
                         * @param string  translated "settings imported" text
                         */
                        echo '<div class="saved_notice admin-notice notice-yellow"><strong>' . apply_filters( "redux-defaults-text-{$this->args['opt_name']}", __( 'All Defaults Restored!', 'redux-framework' ) ) . '</strong></div>';
                    } else if ( $this->transients['last_save_mode'] == "defaults_section" ) {
                        /**
                         * action 'redux/options/{opt_name}/section/reset'
                         *
                         * @param object $this ReduxFramework
                         */
                        do_action( "redux/options/{$this->args['opt_name']}/section/reset", $this );

                        /**
                         * filter 'redux-defaults-section-text-{opt_name}'
                         *
                         * @param string  translated "settings imported" text
                         */
                        echo '<div class="saved_notice admin-notice notice-yellow"><strong>' . apply_filters( "redux-defaults-section-text-{$this->args['opt_name']}", __( 'Section Defaults Restored!', 'redux-framework' ) ) . '</strong></div>';
                    } else {
                        /**
                         * action 'redux/options/{opt_name}/saved'
                         *
                         * @param mixed $value set/saved option value
                         */
                        do_action( "redux/options/{$this->args['opt_name']}/saved", $this->options, $this->transients['changed_values'] );

                        /**
                         * filter 'redux-saved-text-{opt_name}'
                         *
                         * @param string translated "settings saved" text
                         */
                        echo '<div class="saved_notice admin-notice notice-green"><strong>' . apply_filters( "redux-saved-text-{$this->args['opt_name']}", __( 'Settings Saved!', 'redux-framework' ) ) . '</strong></div>';
                    }
                    unset( $this->transients['last_save_mode'] );

                }

                /**
                 * action 'redux/options/{opt_name}/settings/changes'
                 *
                 * @param mixed $value set/saved option value
                 */
                do_action( "redux/options/{$this->args['opt_name']}/settings/change", $this->options, $this->transients['changed_values'] );

                /**
                 * filter 'redux-changed-text-{opt_name}'
                 *
                 * @param string translated "settings have changed" text
                 */
                echo '<div class="redux-save-warn notice-yellow"><strong>' . apply_filters( "redux-changed-text-{$this->args['opt_name']}", __( 'Settings have changed, you should save them!', 'redux-framework' ) ) . '</strong></div>';

                /**
                 * action 'redux/options/{opt_name}/errors'
                 *
                 * @param array $this ->errors error information
                 */
                do_action( "redux/options/{$this->args['opt_name']}/errors", $this->errors );
                echo '<div class="redux-field-errors notice-red"><strong><span></span> ' . __( 'error(s) were found!', 'redux-framework' ) . '</strong></div>';

                /**
                 * action 'redux/options/{opt_name}/warnings'
                 *
                 * @param array $this ->warnings warning information
                 */
                do_action( "redux/options/{$this->args['opt_name']}/warnings", $this->warnings );
                echo '<div class="redux-field-warnings notice-yellow"><strong><span></span> ' . __( 'warning(s) were found!', 'redux-framework' ) . '</strong></div>';

                echo '</div>';

                echo '<div class="clear"></div>';

                // Sidebar
                echo '<div class="redux-sidebar">';
                echo '<ul class="redux-group-menu">';

                foreach ( $this->sections as $k => $section ) {
                    $title = isset( $section['title'] ) ? $section['title'] : '';

                    $skip_sec = false;
                    foreach ( $this->hidden_perm_sections as $num => $section_title ) {
                        if ( $section_title == $title ) {
                            $skip_sec = true;
                        }
                    }

                    if ( isset( $section['customizer_only'] ) && $section['customizer_only'] == true ) {
                        continue;
                    }

                    if ( false == $skip_sec ) {
                        echo $this->section_menu( $k, $section );
                        $skip_sec = false;
                    }
                }

                /**
                 * action 'redux-page-after-sections-menu-{opt_name}'
                 *
                 * @param object $this ReduxFramework
                 */
                do_action( "redux-page-after-sections-menu-{$this->args['opt_name']}", $this );

                /**
                 * action 'redux/page/{opt_name}/menu/after'
                 *
                 * @param object $this ReduxFramework
                 */
                do_action( "redux/page/{$this->args['opt_name']}/menu/after", $this );

                // Import / Export tab
                if ( true == $this->args['show_import_export'] && false == $this->import_export->is_field ) {
                    $this->import_export->render_tab();
                }

                // Debug tab
                if ( $this->args['dev_mode'] == true ) {
                    $this->debug->render_tab();
                }

                if ( $this->args['system_info'] === true ) {
                    echo '<li id="system_info_default_section_group_li" class="redux-group-tab-link-li">';

                    if ( ! empty( $this->args['icon_type'] ) && $this->args['icon_type'] == 'image' ) {
                        $icon = ( ! isset( $this->args['system_info_icon'] ) ) ? '' : '<img src="' . $this->args['system_info_icon'] . '" /> ';
                    } else {
                        $icon_class = ( ! isset( $this->args['system_info_icon_class'] ) ) ? '' : ' ' . $this->args['system_info_icon_class'];
                        $icon       = ( ! isset( $this->args['system_info_icon'] ) ) ? '<i class="el-icon-info-sign' . $icon_class . '"></i>' : '<i class="icon-' . $this->args['system_info_icon'] . $icon_class . '"></i> ';
                    }

                    echo '<a href="javascript:void(0);" id="system_info_default_section_group_li_a" class="redux-group-tab-link-a custom-tab" data-rel="system_info_default">' . $icon . ' <span class="group_title">' . __( 'System Info', 'redux-framework' ) . '</span></a>';
                    echo '</li>';
                }

                echo '</ul>';
                echo '</div>';

                echo '<div class="redux-main">';

                foreach ( $this->sections as $k => $section ) {
                    if ( isset( $section['customizer_only'] ) && $section['customizer_only'] == true ) {
                        continue;
                    }

                    //$active = ( ( is_numeric($this->current_tab) && $this->current_tab == $k ) || ( !is_numeric($this->current_tab) && $this->current_tab === $k )  ) ? ' style="display: block;"' : '';
                    $section['class'] = isset( $section['class'] ) ? ' ' . $section['class'] : '';
                    echo '<div id="' . $k . '_section_group' . '" class="redux-group-tab' . $section['class'] . '" data-rel="' . $k . '">';
                    //echo '<div id="' . $k . '_nav-bar' . '"';
                    /*
                if ( !empty( $section['tab'] ) ) {

                    echo '<div id="' . $k . '_section_tabs' . '" class="redux-section-tabs">';

                    echo '<ul>';

                    foreach ($section['tab'] as $subkey => $subsection) {
                        //echo '-=' . $subkey . '=-';
                        echo '<li style="display:inline;"><a href="#' . $k . '_section-tab-' . $subkey . '">' . $subsection['title'] . '</a></li>';
                    }

                    echo '</ul>';
                    foreach ($section['tab'] as $subkey => $subsection) {
                        echo '<div id="' . $k .'sub-'.$subkey. '_section_group' . '" class="redux-group-tab" style="display:block;">';
                        echo '<div id="' . $k . '_section-tab-' . $subkey . '">';
                        echo "hello ".$subkey;
                        do_settings_sections( $this->args['opt_name'] . $k . '_tab_' . $subkey . '_section_group' );
                        echo "</div>";
                        echo "</div>";
                    }
                    echo "</div>";
                } else {
                    */

                    // Don't display in the
                    $display = true;
                    if ( isset( $_GET['page'] ) && $_GET['page'] == $this->args['page_slug'] ) {
                        if ( isset( $section['panel'] ) && $section['panel'] == "false" ) {
                            $display = false;
                        }
                    }

                    if ( $display ) {
                        do_settings_sections( $this->args['opt_name'] . $k . '_section_group' );
                    }
                    //}
                    echo "</div>";
                    //echo '</div>';
                }

                // Import / Export output
                if ( true == $this->args['show_import_export'] && false == $this->import_export->is_field ) {
                    $this->import_export->enqueue();

                    echo '<fieldset id="' . $this->args['opt_name'] . '-import_export_core" class="redux-field-container redux-field redux-field-init redux-container-import_export" data-id="import_export_core" data-type="import_export">';
                    $this->import_export->render();
                    echo '</fieldset>';

                }

                // Debug object output
                if ( $this->args['dev_mode'] == true ) {
                    $this->debug->render();
                }

                if ( $this->args['system_info'] === true ) {
                    require_once 'inc/sysinfo.php';
                    $system_info = new Simple_System_Info();

                    echo '<div id="system_info_default_section_group' . '" class="redux-group-tab">';
                    echo '<h3>' . __( 'System Info', 'redux-framework' ) . '</h3>';

                    echo '<div id="redux-system-info">';
                    echo $system_info->get( true );
                    echo '</div>';

                    echo '</div>';
                }

                /**
                 * action 'redux/page-after-sections-{opt_name}'
                 *
                 * @deprecated
                 *
                 * @param object $this ReduxFramework
                 */
                do_action( "redux/page-after-sections-{$this->args['opt_name']}", $this ); // REMOVE LATER

                /**
                 * action 'redux/page/{opt_name}/sections/after'
                 *
                 * @param object $this ReduxFramework
                 */
                do_action( "redux/page/{$this->args['opt_name']}/sections/after", $this );

                echo '<div class="clear"></div>';
                echo '</div>';
                echo '<div class="clear"></div>';

                echo '<div id="redux-sticky-padder" style="display: none;">&nbsp;</div>';
                echo '<div id="redux-footer-sticky"><div id="redux-footer">';

                if ( isset( $this->args['share_icons'] ) ) {
                    echo '<div id="redux-share">';

                    foreach ( $this->args['share_icons'] as $link ) {
                        // SHIM, use URL now
                        if ( isset( $link['link'] ) && ! empty( $link['link'] ) ) {
                            $link['url'] = $link['link'];
                            unset( $link['link'] );
                        }

                        echo '<a href="' . $link['url'] . '" title="' . $link['title'] . '" target="_blank">';

                        if ( isset( $link['icon'] ) && ! empty( $link['icon'] ) ) {
                            echo '<i class="' . $link['icon'] . '"></i>';
                        } else {
                            echo '<img src="' . $link['img'] . '"/>';
                        }

                        echo '</a>';
                    }

                    echo '</div>';
                }

                echo '<div class="redux-action_bar">';
                submit_button( __( 'Save Changes', 'redux-framework' ), 'primary', 'redux_save', false );

                if ( false === $this->args['hide_reset'] ) {
                    echo '&nbsp;';
                    submit_button( __( 'Reset Section', 'redux-framework' ), 'secondary', $this->args['opt_name'] . '[defaults-section]', false );
                    echo '&nbsp;';
                    submit_button( __( 'Reset All', 'redux-framework' ), 'secondary', $this->args['opt_name'] . '[defaults]', false );
                }

                echo '</div>';

                echo '<div class="redux-ajax-loading" alt="' . __( 'Working...', 'redux-framework' ) . '">&nbsp;</div>';
                echo '<div class="clear"></div>';

                echo '</div>';
                echo '</form>';
                echo '</div></div>';

                echo ( isset( $this->args['footer_text'] ) ) ? '<div id="redux-sub-footer">' . $this->args['footer_text'] . '</div>' : '';

                /**
                 * action 'redux-page-after-form-{opt_name}'
                 *
                 * @deprecated
                 */
                do_action( "redux-page-after-form-{$this->args['opt_name']}" ); // REMOVE

                /**
                 * action 'redux/page/{opt_name}/form/after'
                 *
                 * @param object $this ReduxFramework
                 */
                do_action( "redux/page/{$this->args['opt_name']}/form/after", $this );

                echo '<div class="clear"></div>';
                echo '</div><!--wrap-->';

                if ( $this->args['dev_mode'] == true ) {
                    if ( current_user_can( 'administrator' ) ) {
                        global $wpdb;
                        echo "<br /><pre>";
                        print_r( $wpdb->queries );
                        echo "</pre>";
                    }

                    echo '<br /><div class="redux-timer">' . get_num_queries() . ' queries in ' . timer_stop( 0 ) . ' seconds<br/>Redux is currently set to developer mode.</div>';
                }

                $this->set_transients();

            }

            /**
             * Section HTML OUTPUT.
             *
             * @since       1.0.0
             * @access      public
             *
             * @param       array $section
             *
             * @return      void
             */
            public function _section_desc( $section ) {
                $id = trim( rtrim( $section['id'], '_section' ), $this->args['opt_name'] );

                if ( isset( $this->sections[ $id ]['desc'] ) && ! empty( $this->sections[ $id ]['desc'] ) ) {
                    echo '<div class="redux-section-desc">' . $this->sections[ $id ]['desc'] . '</div>';
                }
            }

            /**
             * Field HTML OUTPUT.
             * Gets option from options array, then calls the specific field type class - allows extending by other devs
             *
             * @since       1.0.0
             *
             * @param array  $field
             * @param string $v
             *
             * @return      void
             */
            public function _field_input( $field, $v = null ) {

                if ( isset( $field['callback'] ) && function_exists( $field['callback'] ) ) {
                    $value = ( isset( $this->options[ $field['id'] ] ) ) ? $this->options[ $field['id'] ] : '';

                    /**
                     * action 'redux-before-field-{opt_name}'
                     *
                     * @deprecated
                     *
                     * @param array  $field field data
                     * @param string $value field.id
                     */
                    do_action( "redux-before-field-{$this->args['opt_name']}", $field, $value ); // REMOVE

                    /**
                     * action 'redux/field/{opt_name}/{field.type}/callback/before'
                     *
                     * @param array  $field field data
                     * @param string $value field.id
                     */
                    do_action( "redux/field/{$this->args['opt_name']}/{$field['type']}/callback/before", $field, $value );

                    /**
                     * action 'redux/field/{opt_name}/callback/before'
                     *
                     * @param array  $field field data
                     * @param string $value field.id
                     */
                    do_action( "redux/field/{$this->args['opt_name']}/callback/before", $field, $value );

                    call_user_func( $field['callback'], $field, $value );


                    /**
                     * action 'redux-after-field-{opt_name}'
                     *
                     * @deprecated
                     *
                     * @param array  $field field data
                     * @param string $value field.id
                     */
                    do_action( "redux-after-field-{$this->args['opt_name']}", $field, $value ); // REMOVE

                    /**
                     * action 'redux/field/{opt_name}/{field.type}/callback/after'
                     *
                     * @param array  $field field data
                     * @param string $value field.id
                     */
                    do_action( "redux/field/{$this->args['opt_name']}/{$field['type']}/callback/after", $field, $value );

                    /**
                     * action 'redux/field/{opt_name}/callback/after'
                     *
                     * @param array  $field field data
                     * @param string $value field.id
                     */
                    do_action( "redux/field/{$this->args['opt_name']}/callback/after", $field, $value );

                    return;
                }

                if ( isset( $field['type'] ) ) {

                    // If the field is set not to display in the panel
                    $display = true;
                    if ( isset( $_GET['page'] ) && $_GET['page'] == $this->args['page_slug'] ) {
                        if ( isset( $field['panel'] ) && $field['panel'] == false ) {
                            $display = false;
                        }
                    }

                    if ( ! $display ) {
                        return;
                    }

                    $field_class = "ReduxFramework_{$field['type']}";

                    if ( ! class_exists( $field_class ) ) {
//                    $class_file = apply_filters( 'redux/field/class/'.$field['type'], self::$_dir . 'inc/fields/' . $field['type'] . '/field_' . $field['type'] . '.php', $field ); // REMOVE
                        /**
                         * filter 'redux/{opt_name}/field/class/{field.type}'
                         *
                         * @param       string        field class file path
                         * @param array $field        field data
                         */
                        $class_file = apply_filters( "redux/{$this->args['opt_name']}/field/class/{$field['type']}", self::$_dir . "inc/fields/{$field['type']}/field_{$field['type']}.php", $field );

                        if ( $class_file ) {
                            if ( file_exists( $class_file ) ) {
                                require_once( $class_file );
                            }
                        }

                    }

                    if ( class_exists( $field_class ) ) {
                        $value = isset( $this->options[ $field['id'] ] ) ? $this->options[ $field['id'] ] : '';

                        if ( $v !== null ) {
                            $value = $v;
                        }

                        /**
                         * action 'redux-before-field-{opt_name}'
                         *
                         * @deprecated
                         *
                         * @param array  $field field data
                         * @param string $value field id
                         */
                        do_action( "redux-before-field-{$this->args['opt_name']}", $field, $value ); // REMOVE

                        /**
                         * action 'redux/field/{opt_name}/{field.type}/render/before'
                         *
                         * @param array  $field field data
                         * @param string $value field id
                         */
                        do_action( "redux/field/{$this->args['opt_name']}/{$field['type']}/render/before", $field, $value );

                        /**
                         * action 'redux/field/{$this->args['opt_name']}/render/before'
                         *
                         * @param array  $field field data
                         * @param string $value field id
                         */
                        do_action( "redux/field/{$this->args['opt_name']}/render/before", $field, $value );

                        if ( ! isset( $field['name_suffix'] ) ) {
                            $field['name_suffix'] = "";
                        }

                        $render = new $field_class( $field, $value, $this );
                        ob_start();

                        $render->render();

                        /*

                    echo "<pre>";
                    print_r($value);
                    echo "</pre>";
                    */

                        /**
                         * filter 'redux-field-{opt_name}'
                         *
                         * @deprecated
                         *
                         * @param       string        rendered field markup
                         * @param array $field        field data
                         */
                        $_render = apply_filters( "redux-field-{$this->args['opt_name']}", ob_get_contents(), $field ); // REMOVE

                        /**
                         * filter 'redux/field/{opt_name}/{field.type}/render/after'
                         *
                         * @param       string        rendered field markup
                         * @param array $field        field data
                         */
                        $_render = apply_filters( "redux/field/{$this->args['opt_name']}/{$field['type']}/render/after", $_render, $field );

                        /**
                         * filter 'redux/field/{opt_name}/render/after'
                         *
                         * @param       string        rendered field markup
                         * @param array $field        field data
                         */
                        $_render = apply_filters( "redux/field/{$this->args['opt_name']}/render/after", $_render, $field );

                        ob_end_clean();

                        //save the values into a unique array in case we need it for dependencies
                        $this->fieldsValues[ $field['id'] ] = ( isset( $value['url'] ) && is_array( $value ) ) ? $value['url'] : $value;

                        //create default data und class string and checks the dependencies of an object
                        $class_string = '';
                        $data_string  = '';

                        $this->check_dependencies( $field );

                        /**
                         * action 'redux/field/{opt_name}/{field.type}/fieldset/before/{opt_name}'
                         *
                         * @param array  $field field data
                         * @param string $value field id
                         */
                        do_action( "redux/field/{$this->args['opt_name']}/{$field['type']}/fieldset/before/{$this->args['opt_name']}", $field, $value );

                        /**
                         * action 'redux/field/{opt_name}/fieldset/before/{opt_name}'
                         *
                         * @param array  $field field data
                         * @param string $value field id
                         */
                        do_action( "redux/field/{$this->args['opt_name']}/fieldset/before/{$this->args['opt_name']}", $field, $value );

                        if ( ! isset( $field['fields'] ) || empty( $field['fields'] ) ) {
                            echo '<fieldset id="' . $this->args['opt_name'] . '-' . $field['id'] . '" class="redux-field-container redux-field redux-field-init redux-container-' . $field['type'] . ' ' . $class_string . '" data-id="' . $field['id'] . '" ' . $data_string . ' data-type="' . $field['type'] . '">';
                        }

                        echo $_render;

                        if ( ! empty( $field['desc'] ) ) {
                            $field['description'] = $field['desc'];
                        }

                        echo ( isset( $field['description'] ) && $field['type'] != "info" && $field['type'] !== "section" && ! empty( $field['description'] ) ) ? '<div class="description field-desc">' . $field['description'] . '</div>' : '';

                        if ( ! isset( $field['fields'] ) || empty( $field['fields'] ) ) {
                            echo '</fieldset>';
                        }

                        /**
                         * action 'redux-after-field-{opt_name}'
                         *
                         * @deprecated
                         *
                         * @param array  $field field data
                         * @param string $value field id
                         */
                        do_action( "redux-after-field-{$this->args['opt_name']}", $field, $value ); // REMOVE

                        /**
                         * action 'redux/field/{opt_name}/{field.type}/fieldset/after/{opt_name}'
                         *
                         * @param array  $field field data
                         * @param string $value field id
                         */
                        do_action( "redux/field/{$this->args['opt_name']}/{$field['type']}/fieldset/after/{$this->args['opt_name']}", $field, $value );

                        /**
                         * action 'redux/field/{opt_name}/fieldset/after/{opt_name}'
                         *
                         * @param array  $field field data
                         * @param string $value field id
                         */
                        do_action( "redux/field/{$this->args['opt_name']}/fieldset/after/{$this->args['opt_name']}", $field, $value );
                    }
                }
            } // _field_input()

            /**
             * Can Output CSS
             * Check if a field meets its requirements before outputting to CSS
             *
             * @param $field
             *
             * @return bool
             */
            public function _can_output_css( $field ) {
                $return = true;

                $field = apply_filters( "redux/field/{$this->args['opt_name']}/_can_output_css", $field );
                if ( isset( $field['force_output'] ) && $field['force_output'] == true ) {
                    return $return;
                }

                if ( ! empty( $field['required'] ) ) {
                    if ( isset( $field['required'][0] ) ) {
                        if ( ! is_array( $field['required'][0] ) && count( $field['required'] ) == 3 ) {
                            $parentValue = $GLOBALS[ $this->args['global_variable'] ][ $field['required'][0] ];
                            $checkValue  = $field['required'][2];
                            $operation   = $field['required'][1];
                            $return      = $this->compareValueDependencies( $parentValue, $checkValue, $operation );
                        } else if ( is_array( $field['required'][0] ) ) {
                            foreach ( $field['required'] as $required ) {
                                if ( ! is_array( $required[0] ) && count( $required ) == 3 ) {
                                    $parentValue = $GLOBALS[ $this->args['global_variable'] ][ $required[0] ];
                                    $checkValue  = $required[2];
                                    $operation   = $required[1];
                                    $return      = $this->compareValueDependencies( $parentValue, $checkValue, $operation );
                                }
                                if ( ! $return ) {
                                    return $return;
                                }
                            }
                        }
                    }
                }

                return $return;
            } // _can_output_css

            /**
             * Checks dependencies between objects based on the $field['required'] array
             * If the array is set it needs to have exactly 3 entries.
             * The first entry describes which field should be monitored by the current field. eg: "content"
             * The second entry describes the comparison parameter. eg: "equals, not, is_larger, is_smaller ,contains"
             * The third entry describes the value that we are comparing against.
             * Example: if the required array is set to array('content','equals','Hello World'); then the current
             * field will only be displayed if the field with id "content" has exactly the value "Hello World"
             *
             * @param array $field
             *
             * @return array $params
             */
            private function check_dependencies( $field ) {
                //$params = array('data_string' => "", 'class_string' => "");

                if ( ! empty( $field['required'] ) ) {

                    //$this->folds[$field['id']] = $this->folds[$field['id']] ? $this->folds[$field['id']] : array();
                    if ( ! isset( $this->required_child[ $field['id'] ] ) ) {
                        $this->required_child[ $field['id'] ] = array();
                    }

                    if ( ! isset( $this->required[ $field['id'] ] ) ) {
                        $this->required[ $field['id'] ] = array();
                    }

                    if ( is_array( $field['required'][0] ) ) {
                        foreach ( $field['required'] as $value ) {
                            if ( is_array( $value ) && count( $value ) == 3 ) {
                                $data               = array();
                                $data['parent']     = $value[0];
                                $data['operation']  = $value[1];
                                $data['checkValue'] = $value[2];

                                $this->required[ $data['parent'] ][ $field['id'] ][] = $data;

                                if ( ! in_array( $data['parent'], $this->required_child[ $field['id'] ] ) ) {
                                    $this->required_child[ $field['id'] ][] = $data;
                                }

                                $this->checkRequiredDependencies( $field, $data );
                            }
                        }
                    } else {
                        $data               = array();
                        $data['parent']     = $field['required'][0];
                        $data['operation']  = $field['required'][1];
                        $data['checkValue'] = $field['required'][2];

                        $this->required[ $data['parent'] ][ $field['id'] ][] = $data;

                        if ( ! in_array( $data['parent'], $this->required_child[ $field['id'] ] ) ) {
                            $this->required_child[ $field['id'] ][] = $data;
                        }

                        $this->checkRequiredDependencies( $field, $data );
                    }

                }
                //return $params;
            }

            // Compare data for required field
            private function compareValueDependencies( $parentValue, $checkValue, $operation ) {
                $return = false;

                switch ( $operation ) {
                    case '=':
                    case 'equals':
                        $data['operation'] = "=";
                        if ( is_array( $checkValue ) ) {
                            if ( in_array( $parentValue, $checkValue ) ) {
                                $return = true;
                            }
                        } else {
                            if ( $parentValue == $checkValue ) {
                                $return = true;
                            } else if ( is_array( $parentValue ) ) {
                                if ( in_array( $checkValue, $parentValue ) ) {
                                    $return = true;
                                }
                            }
                        }
                        break;
                    case '!=':
                    case 'not':
                        $data['operation'] = "!==";
                        if ( is_array( $checkValue ) ) {
                            if ( ! in_array( $parentValue, $checkValue ) ) {
                                $return = true;
                            }
                        } else {
                            if ( $parentValue != $checkValue ) {
                                $return = true;
                            } else if ( is_array( $parentValue ) ) {
                                if ( ! in_array( $checkValue, $parentValue ) ) {
                                    $return = true;
                                }
                            }
                        }
                        break;
                    case '>':
                    case 'greater':
                    case 'is_larger':
                        $data['operation'] = ">";
                        if ( $parentValue > $checkValue ) {
                            $return = true;
                        }
                        break;
                    case '>=':
                    case 'greater_equal':
                    case 'is_larger_equal':
                        $data['operation'] = ">=";
                        if ( $parentValue >= $checkValue ) {
                            $return = true;
                        }
                        break;
                    case '<':
                    case 'less':
                    case 'is_smaller':
                        $data['operation'] = "<";
                        if ( $parentValue < $checkValue ) {
                            $return = true;
                        }
                        break;
                    case '<=':
                    case 'less_equal':
                    case 'is_smaller_equal':
                        $data['operation'] = "<=";
                        if ( $parentValue <= $checkValue ) {
                            $return = true;
                        }
                        break;
                    case 'contains':
                        if ( strpos( $parentValue, $checkValue ) !== false ) {
                            $return = true;
                        }
                        break;
                    case 'doesnt_contain':
                    case 'not_contain':
                        if ( strpos( $parentValue, $checkValue ) === false ) {
                            $return = true;
                        }
                        break;
                    case 'is_empty_or':
                        if ( empty( $parentValue ) || $parentValue == $checkValue ) {
                            $return = true;
                        }
                        break;
                    case 'not_empty_and':
                        if ( ! empty( $parentValue ) && $parentValue != $checkValue ) {
                            $return = true;
                        }
                        break;
                    case 'is_empty':
                    case 'empty':
                    case '!isset':
                        if ( empty( $parentValue ) || $parentValue == "" || $parentValue == null ) {
                            $return = true;
                        }
                        break;
                    case 'not_empty':
                    case '!empty':
                    case 'isset':
                        if ( ! empty( $parentValue ) && $parentValue != "" && $parentValue != null ) {
                            $return = true;
                        }
                        break;
                }

                return $return;
            }

            private function checkRequiredDependencies( $field, $data ) {
                //required field must not be hidden. otherwise hide this one by default

                if ( ! in_array( $data['parent'], $this->fieldsHidden ) && ( ! isset( $this->folds[ $field['id'] ] ) || $this->folds[ $field['id'] ] != "hide" ) ) {
                    if ( isset( $this->options[ $data['parent'] ] ) ) {
                        $return = $this->compareValueDependencies( $this->options[ $data['parent'] ], $data['checkValue'], $data['operation'] );
                    }
                }

                if ( ( isset( $return ) && $return ) && ( ! isset( $this->folds[ $field['id'] ] ) || $this->folds[ $field['id'] ] != "hide" ) ) {
                    $this->folds[ $field['id'] ] = "show";
                } else {
                    $this->folds[ $field['id'] ] = "hide";
                    if ( ! in_array( $field['id'], $this->fieldsHidden ) ) {
                        $this->fieldsHidden[] = $field['id'];
                    }
                }
            }

            /**
             * converts an array into a html data string
             *
             * @param array $data example input: array('id'=>'true')
             *
             * @return string $data_string example output: data-id='true'
             */
            public function create_data_string( $data = array() ) {
                $data_string = "";

                foreach ( $data as $key => $value ) {
                    if ( is_array( $value ) ) {
                        $value = implode( "|", $value );
                    }
                    $data_string .= " data-$key='$value' ";
                }

                return $data_string;
            }
        } // ReduxFramework

        /**
         * action 'redux/init'
         *
         * @param null
         */
        do_action( 'redux/init', ReduxFramework::init() );

    } // class_exists('ReduxFramework')
