<?php
/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     Redux_Framework
 * @subpackage  Core
 * @author      Redux Framework Team
 * @version     3.1.5
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Fix for the GT3 page builder: http://www.gt3themes.com/wordpress-gt3-page-builder-plugin/
/** @global string $pagenow */
if(has_action('ecpt_field_options_')) {
    global $pagenow;
    if ( $pagenow === 'admin.php' ) {
        /** @noinspection PhpUndefinedCallbackInspection */
        remove_action( 'admin_init', 'pb_admin_init' );
    }
}


// Don't duplicate me!
if( !class_exists( 'ReduxFramework' ) ) {

    // General helper functions
    include_once(dirname(__FILE__).'/inc/class.redux_helpers.php');

    /**
     * Main ReduxFramework class
     *
     * @since       1.0.0
     */
    class ReduxFramework {

        // ATTENTION DEVS
        // Please update the build number with each push, no matter how small.
        // This will make for easier support when we ask users what version they are using.
        public static $_version = '3.1.6.3';
        public static $_dir;
        public static $_url;
        public static $_properties;
        public static $_is_plugin = true;

        static function init() {

            // Windows-proof constants: replace backward by forward slashes. Thanks to: @peterbouwmeester
            self::$_dir     = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
            $wp_content_dir = trailingslashit( str_replace( '\\', '/', WP_CONTENT_DIR ) );
            $wp_content_dir = trailingslashit( str_replace( '//', '/', $wp_content_dir ) );
            $relative_url   = str_replace( $wp_content_dir, '', self::$_dir );
            $wp_content_url = ( is_ssl() ? str_replace( 'http://', 'https://', WP_CONTENT_URL ) : WP_CONTENT_URL );
            self::$_url     = trailingslashit( $wp_content_url ) . $relative_url;                     

            // See if Redux is a plugin or not
            if ( defined('TEMPLATEPATH') && strpos(__FILE__,TEMPLATEPATH) !== false) {
                self::$_is_plugin = false;
            }

/**
        Still need to port these.

            $defaults['footer_credit']      = '<span id="footer-thankyou">' . __( 'Options panel created using', $this->args['domain']) . ' <a href="' . $this->framework_url . '" target="_blank">' . __('Redux Framework', $this->args['domain']) . '</a> v' . self::$_version . '</span>';
            $defaults['help_tabs']          = array();
            $defaults['help_sidebar']       = ''; // __( '', $this->args['domain'] );
            $defaults['database']           = ''; // possible: options, theme_mods, theme_mods_expanded, transient
            $defaults['customizer']         = false; // setting to true forces get_theme_mod_expanded
            $defaults['global_variable']    = '';
            $defaults['output']             = true; // Dynamically generate CSS
            $defaults['transient_time']     = 60 * MINUTE_IN_SECONDS;

            // The defaults are set so it will preserve the old behavior.
            $defaults['default_show']       = false; // If true, it shows the default value
            $defaults['default_mark']       = ''; // What to print by the field's title if the value shown is default
**/

            self::$_properties = array( 
                'args' => array(
                    'opt_name' => array(
                            'required', 
                            'data_type'=>'string', 
                            'label'=>'Option Name', 
                            'desc'=>'Must be defined by theme/plugin. Is the unique key allowing multiple instance of Redux within a single Wordpress instance.', 
                            'default'=>''
                        ),
                    'google_api_key' => array(
                            'data_type'=>'string', 
                            'label'=>'Google Web Fonts API Key', 
                            'desc'=>'Key used to request Google Webfonts. Google fonts are omitted without this.', 
                            'default'=>''
                        ),
                    'last_tab' => array( // Do we need this?
                            'data_type'=>'string', 
                            'label'=>'Last Tab', 
                            'desc'=>'Last tab used.', 
                            'default'=>'0'
                        ),  
                    'menu_icon' => array( 
                            'data_type'=>'string', 
                            'label'=>'Default Menu Icon', 
                            'desc'=>'Default menu icon used by sections when one is not specified.', 
                            'default'=> self::$_url . 'assets/img/menu_icon.png'
                        ),                  

                    'menu_title' => array( 
                            'data_type'=>'string', 
                            'label'=>'Menu Title', 
                            'desc'=>'Label displayed when the admin menu is available.', 
                            'default'=> __( 'Options', 'redux-framework' )
                        ),              
                    'page_title' => array( 
                            'data_type'=>'string', 
                            'label'=>'Page Title', 
                            'desc'=>'Title used on the panel page.', 
                            'default'=> __( 'Options', 'redux-framework' )
                        ),  
                   'page_icon' => array( 
                            'data_type'=>'string', 
                            'label'=>'Page Title', 
                            'desc'=>'Icon class to be used on the options page.', 
                            'default'=> 'icon-themes'
                        ),      
                   'page_slug' => array( 
                            'required', 
                            'data_type'=>'string', 
                            'label'=>'Page Slug', 
                            'desc'=>'Slug used to access options panel.', 
                            'default'=> '_options'
                        ),    
                   'page_permissions' => array( 
                            'required', 
                            'data_type'=>'string', 
                            'label'=>'Page Capabilities', 
                            'desc'=>'Permissions needed to access the options panel.', 
                            'default'=> 'manage_options'
                        ),  
                    'menu_type' => array(
                        'required', 
                        'data_type' => 'varchar',
                        'label' => 'Page Type',
                        'desc' => 'Specify if the admin menu should appear or not.',
                        'default' => 'menu',
                        'form' => array('type' => 'select', 'options' => array('menu' => 'Admin Menu', 'submenu' => 'Submenu Only')),
                        'validation' => array('required'),
                    ), 
                    'page_parent' => array(
                        'required', 
                        'data_type' => 'varchar',
                        'label' => 'Page Parent',
                        'desc' => 'Specify if the admin menu should appear or not.',
                        'default' => 'themes.php',
                        'form' => array('type' => 'select', 'options' => array('index.php' => 'Dashboard', 'edit.php' => 'Posts', 'upload.php' => 'Media', 'link-manager.php' => 'Links', 'edit.php?post_type=page' => 'pages', 'edit-comments.php' => 'Comments', 'themes.php' => 'Appearance', 'plugins.php' => 'Plugins', 'users.php' => 'Users', 'tools.php' => 'Tools', 'options-general.php' => 'Settings', )),
                        'validation' => array('required'),
                    ),                       
                   'page_priority' => array( 
                            'type'=>'int', 
                            'label'=>'Page Position', 
                            'desc'=>'Location where this menu item will appear in the admin menu. Warning, beware of overrides.', 
                            'default'=> null
                        ),  
                    'output' => array(
                            'required', 
                            'data_type'=>'bool',
                            'form' => array('type' => 'radio', 'options' => array(true => 'Enabled', false => 'Disabled')),
                            'label'=>'Output/Generate CSS', 
                            'desc'=>'Global shut-off for dynamic CSS output by the framework',
                            'default'=>true
                        ),
                    'allow_sub_menu' => array(
                            'data_type'=>'bool',
                            'form' => array('type' => 'radio', 'options' => array(true => 'Enabled', false => 'Disabled')),
                            'label'=>'Allow Submenu', 
                            'desc'=>'Turn on or off the submenu that will typically be shown under Appearance.', 
                            'default'=>true
                        ),                        
                    'show_import_export' => array(
                            'data_type'=>'bool',
                            'form' => array('type' => 'radio', 'options' => array(true => 'Show', false => 'Hide')),
                            'label'=>'Show Import/Export', 
                            'desc'=>'Show/Hide the import/export tab.', 
                            'default'=>true
                        ),  
                    'dev_mode' => array(
                            'data_type'=>'bool',
                            'form' => array('type' => 'radio', 'options' => array(true => 'Enabled', false => 'Disabled')),
                            'label'=>'Developer Mode', 
                            'desc'=>'Turn on or off the dev mode tab.', 
                            'default'=>false
                        ), 
                    'system_info' => array(
                            'data_type'=>'bool',
                            'form' => array('type' => 'radio', 'options' => array(true => 'Enabled', false => 'Disabled')),
                            'label'=>'System Info', 
                            'desc'=>'Turn on or off the system info tab.', 
                            'default'=>false
                        ),                                                         
                ),
            );  

        }// ::init() 

        public $framework_url       = 'http://www.reduxframework.com/';
        public $instance            = null;
        public $admin_notices       = array();
        public $page                = '';
        public $args                = array(
            'opt_name'           => '', // Must be defined by theme/plugin
            'domain'             => 'redux-framework', // Translation domain key
            'google_api_key'     => '', // Must be defined to add google fonts to the typography module
            'last_tab'           => '', // force a specific tab to always show on reload
            'menu_icon'          => '', // menu icon
            'menu_title'         => '', // menu title/text
            'page_icon'          => 'icon-themes',
            'page_title'         => '', // option page title
            'page_slug'          => '_options',
            'page_permissions'   => 'manage_options',
            'menu_type'          => 'menu', // ('menu'|'submenu')
            'page_parent'        => 'themes.php', // requires menu_type = 'submenu
            'page_priority'      => null,
            'allow_sub_menu'     => true, // allow submenus to be added if menu_type == menu
            'save_defaults'      => true, // Save defaults to the DB on it if empty
            'footer_credit'      => '',
            'admin_bar'          => true, // Show the panel pages on the admin bar
            'help_tabs'          => array(),
            'help_sidebar'       => '', // __( '', $this->args['domain'] );
            'database'           => '', // possible: options, theme_mods, theme_mods_expanded, transient
            'customizer'         => false, // setting to true forces get_theme_mod_expanded
            'global_variable'    => '', // Changes global variable from $GLOBALS['YOUR_OPT_NAME'] to whatever you set here. false disables the global variable
            'output'             => true, // Dynamically generate CSS
            'compiler'           => true, // Initiate the compiler hook
            'output_tag'         => true, // Print Output Tag
            'transient_time'     => '',
            'default_show'       => false, // If true, it shows the default value
            'default_mark'       => '', // What to print by the field's title if the value shown is default
            /**
             * 'show_import_export'
             * @deprecated
             */
            'show_import_export' => true, // REMOVE
            /**
             * 'dev_mode'
             * @deprecated
             */
            'dev_mode'           => false, // REMOVE
            /**
             * 'system_info'
             * @deprecated
             */
            'system_info'        => false, // REMOVE
        );

        public $sections            = array(); // Sections and fields
        public $errors              = array(); // Errors
        public $warnings            = array(); // Warnings
        public $options             = array(); // Option values
        public $options_defaults    = null; // Option defaults
        public $localize_data       = array(); // Information that needs to be localized
        public $folds           = array(); // The itms that need to fold.
        public $path            = '';
        public $output          = array(); // Fields with CSS output selectors
        public $outputCSS           = null; // CSS that get auto-appended to the header
        public $compilerCSS         = null; // CSS that get sent to the compiler hook
        public $customizerCSS       = null; // CSS that goes to the customizer
        public $fieldsValues        = array(); //all fields values in an id=>value array so we can check dependencies
        public $fieldsHidden        = array(); //all fields that didn't pass the dependency test and are hidden
        public $toHide              = array(); // Values to hide on page load
        public $typography      = null; //values to generate google font CSS
    
        /**
         * Class Constructor. Defines the args for the theme options class
         * @since       1.0.0
         * @param       array $sections   Panel sections.
         * @param       array $args       Class constructor arguments.
         * @param       array $extra_tabs Extra panel tabs. // REMOVE
         * @return \ReduxFramework
         */
        public function __construct( $sections = array(), $args = array(), $extra_tabs = array() ) {

            global $wp_version;
            
            // Set values
            $this->args = wp_parse_args( $args, $this->args );
            if ( empty( $this->args['transient_time'] ) ) {
                $this->args['transient_time'] = 60 * MINUTE_IN_SECONDS;
            }
            if ( empty( $this->args['footer_credit'] ) ) {
                $this->args['footer_credit'] = '<span id="footer-thankyou">' . sprintf( __( 'Options panel created using %1$s', $this->args['domain'] ), '<a href="'.esc_url( $this->framework_url ).'" target="_blank">'.__( 'Redux Framework', $this->args['domain'] ).'</a> v'.self::$_version ) . '</span>';
            }
            if ( empty( $this->args['menu_title'] ) ) {
                $this->args['menu_title'] = __( 'Options', $this->args['domain'] );
            }
            if ( empty( $this->args['page_title'] ) ) {
                $this->args['page_title'] = __( 'Options', $this->args['domain'] );
            }

            /**
             * filter 'redux/args/{opt_name}'
             * @param  array $args  ReduxFramework configuration
             */
            $this->args = apply_filters( "redux/args/{$this->args['opt_name']}", $this->args );

            /**
             * filter 'redux/options/{opt_name}/args'
             * @param  array $args  ReduxFramework configuration
             */
            $this->args = apply_filters( "redux/options/{$this->args['opt_name']}/args", $this->args );

            if ( !empty( $this->args['opt_name'] ) ) {
                /**
                 
                    SHIM SECTION
                    Old variables and ways of doing things that need correcting.  ;) 

                 **/
                // Variable name change
                if ( !empty( $this->args['page_cap'] ) ) {
                    $this->args['page_permissions'] = $this->args['page_cap'];
                    unset( $this->args['page_cap'] );
                }
                if ( !empty( $this->args['page_position'] ) ) {
                    $this->args['page_priority'] = $this->args['page_position'];
                    unset( $this->args['page_position'] );
                }
                if ( !empty( $this->args['page_type'] ) ) {
                    $this->args['menu_type'] = $this->args['page_type'];
                    unset( $this->args['page_type'] );
                }

                // Get rid of extra_tabs! Not needed.
                if( is_array( $extra_tabs ) && !empty( $extra_tabs ) ) {
                    foreach( $extra_tabs as $tab ) {
                        array_push($this->sections, $tab);
                    }
                }            

                // Move to the first loop area!
                /**
                 * filter 'redux-sections'
                 * @deprecated
                 * @param  array $sections field option sections
                 */
                $this->sections = apply_filters('redux-sections', $sections); // REMOVE LATER
                /**
                 * filter 'redux-sections-{opt_name}'
                 * @deprecated
                 * @param  array $sections field option sections
                 */
                $this->sections = apply_filters("redux-sections-{$this->args['opt_name']}", $this->sections); // REMOVE LATER
                /**
                 * filter 'redux/options/{opt_name}/sections'
                 * @param  array $sections field option sections
                 */
                $this->sections = apply_filters("redux/options/{$this->args['opt_name']}/sections", $this->sections);

                /**
                 * Construct hook
                 * action 'redux/construct'
                 * @param object $this ReduxFramework
                 */
                do_action( 'redux/contruct', $this );

                // Set the default values
                $this->_default_cleanup(); 
                $this->_internationalization();

                // Register extra extensions
                $this->_register_extensions(); 

                // Grab database values
                $this->get_options();                
                
                $this->_tracking();

                // Set option with defaults
                //add_action( 'init', array( &$this, '_set_default_options' ), 101 );

                // Options page
                add_action( 'admin_menu', array( $this, '_options_page' ) );

                // Admin Bar menu
                add_action( 'admin_bar_menu', array( $this, '_admin_bar_menu' ) , 999 );

                // Register setting
                add_action( 'admin_init', array( $this, '_register_settings' ) );

                // Display admin notices
                add_action( 'admin_notices', array( $this, '_admin_notices' ) );

                // Check for dismissed admin notices.
                add_action( 'admin_init', array( $this, '_dismiss_admin_notice' ) );

                // Enqueue the admin page CSS and JS
                if ( isset( $_GET['page'] ) && $_GET['page'] == $this->args['page_slug'] ) {
                    add_action( 'admin_enqueue_scripts', array( $this, '_enqueue' ) );
                }

                // Any dynamic CSS output, let's run
                add_action( 'wp_head', array( &$this, '_enqueue_output' ), 150 );
                
                // Add tracking. PLEASE leave this in tact! It helps us gain needed statistics of uses. Opt-in of course.
                //add_action( 'init', array( &$this, '_tracking' ), 200 );   

                // Start internationalization
                //add_action( 'init', array( &$this, '_internationalization' ), 100 );            

                // Hook into the WP feeds for downloading exported settings
                add_action( "do_feed_redux_options_{$this->args['opt_name']}", array( $this, '_download_options' ), 1, 1 );

                // Hook into the WP feeds for downloading exported settings
                add_action( "do_feed_redux_settings_{$this->args['opt_name']}", array( $this, '_download_settings' ), 1, 1 );


            }

            
            /**
             * Loaded hook
             *
             * action 'redux/loaded'
             * @param  object $this ReduxFramework
             */
            do_action( 'redux/loaded', $this );

        } // __construct()

        public function _admin_notices() {
            global $current_user, $pagenow;

            // Check for an active admin notice array
            if (!empty($this->admin_notices)) {

                // Enum admin notices
                foreach( $this->admin_notices as $notice ) {
                    if (true == $notice['dismiss']) {
                        
                        // Get user ID
                        $userid = $current_user->ID;
                        
                        if ( !get_user_meta( $userid, 'ignore_' . $notice['id'] ) ) {
                            
                            // Check if we are on admin.php.  If we are, we have
                            // to get the current page slug and tab, so we can
                            // feed it back to Wordpress.  Why>  admin.php cannot
                            // be accessed without the page parameter.  We add the
                            // tab to return the user to the last panel they were
                            // on.
                            if ($pagenow == 'admin.php') {
                                
                                // Get the current page.  To avoid errors, we'll set
                                // the redux page slug if the GET is empty.
                                $pageName   = empty($_GET['page']) ? '&amp;page=' . $this->args['page_slug'] : '&amp;page=' . $_GET['page'];
                                
                                // Ditto for the current tab.
                                $curTab     = empty($_GET['tab']) ? '&amp;tab=0' : '&amp;tab=' . $_GET['tab'];
                            }   
                            
                            // Print the notice with the dismiss link
                            echo '<div class="' . $notice['type'] . '"><p>' . $notice['msg'] . '&nbsp;&nbsp;<a href="?dismiss=true&amp;id=' . $notice['id'] . $pageName . $curTab . '">' . __('Dismiss', $this->args['domain']) . '</a>.</p></div>';
                        }                        
                    } else {
                        
                        // Standard notice
                        echo '<div class="' . $notice['type'] . '"><p>' . $notice['msg'] . '</a>.</p></div>';
                    }

                }
                
                // Clear the admin notice array
                $this->admin_notices = array();
                
            }
        }

        public function _dismiss_admin_notice() {
	        global $current_user;
            
	        // Verify the dismiss and id parameters are present.
	        if ( isset( $_GET['dismiss'] ) && 'true' == $_GET['dismiss'] && isset( $_GET['id']  ) ) {
                
                // Get the user id
                $userid = $current_user->ID;
                
                // Get the notice id
                $id = $_GET['id'];
                
                // Add the dismiss request to the user meta.
	            add_user_meta( $userid, 'ignore_' . $id, 'true', true );
	        }
	    }

        /**
         * Load the plugin text domain for translation.
         * @param string $opt_name
         * @since    3.0.5
         */
        public function _internationalization() {

            /**
             * Locale for text domain
             *
             * filter 'redux/textdomain/{opt_name}'
             * @param string     The locale of the blog or from the 'locale' hook
             * @param string     $this->args['domain']  text domain
             */
            $locale = apply_filters( "redux/textdomain/{$this->args['opt_name']}", get_locale(), $this->args['domain'] );

            if (strpos($locale, '_') === false ) {
                if ( file_exists( dirname( __FILE__ ) . '/languages/' . strtolower($locale).'_'.strtoupper($locale) . '.mo' ) ) {
                    $locale = strtolower($locale).'_'.strtoupper($locale);    
                }
            }
            load_textdomain( $this->args['domain'], dirname( __FILE__ ) . '/languages/' . $locale . '.mo' );
        } // _internationalization()

        /**
         * @return ReduxFramework
         */
        public function get_instance() {
            return self::$instance;
        } // get_instance()

        public function _tracking() {
            include_once( dirname( __FILE__ ) . '/inc/tracking.php' );
            new Redux_Tracking($this);
        } // _tracking()

        /**
         * ->_get_default(); This is used to return the default value if default_show is set
         *
         * @since       1.0.1
         * @access      public
         * @param       string $opt_name The option name to return
         * @param       mixed $default (null)  The value to return if default not set
         * @return      mixed $default
         */
        public function _get_default( $opt_name, $default = null ) {
            if( $this->args['default_show'] == true ) {

                if( is_null( $this->options_defaults ) ) {
                    $this->_default_values(); // fill cache
                }

                $default = array_key_exists( $opt_name, $this->options_defaults ) ? $this->options_defaults[$opt_name] : $default;
            }
            return $default;
        } // _get_default()

        /**
         * ->get(); This is used to return and option value from the options array
         *
         * @since       1.0.0
         * @access      public
         * @param       string $opt_name The option name to return
         * @param       mixed $default (null) The value to return if option not set
         * @return      mixed
         */
        public function get( $opt_name, $default = null ) {
            return ( !empty( $this->options[$opt_name] ) ) ? $this->options[$opt_name] : $this->_get_default( $opt_name, $default );
        } // get()

        /**
         * ->set(); This is used to set an arbitrary option in the options array
         *
         * @since       1.0.0
         * @access      public
         * @param       string $opt_name The name of the option being added
         * @param       mixed $value The value of the option being added
         * @return      void
         */
        public function set( $opt_name = '', $value = '' ) {
            if( $opt_name != '' ) {
                $this->options[$opt_name] = $value;
                $this->set_options( $this->options );
            }
        } // set()

        /**
         * Set a global variable by the global_variable argument
         * @since   3.1.5
         * @return  bool          (global was set)
         */
        function set_global_variable( ) {
            if ( $this->args['global_variable'] ) {
                $option_global = $this->args['global_variable'];
                /**
                 * filter 'redux/options/{opt_name}/global_variable'
                 * @param array $value  option value to set global_variable with
                 */
                $GLOBALS[ $this->args['global_variable'] ] = apply_filters( "redux/options/{$this->args['opt_name']}/global_variable", $this->options );
                return true;                    
            }
            return false;
        } // set_global_variable()


        /**
         * ->set_options(); This is used to set an arbitrary option in the options array
         *
         * @since ReduxFramework 3.0.0
         * @param mixed $value the value of the option being added
         */
        function set_options( $value = '' ) {
            $value['REDUX_last_saved'] = time();
            if( !empty($value) ) {
                $this->options = $value;
                if ( $this->args['database'] === 'transient' ) {
                    set_transient( $this->args['opt_name'] . '-transient', $value, $this->args['transient_time'] );
                } else if ( $this->args['database'] === 'theme_mods' ) {
                    set_theme_mod( $this->args['opt_name'] . '-mods', $value ); 
                } else if ( $this->args['database'] === 'theme_mods_expanded' ) {
                    foreach ( $value as $k=>$v ) {
                        set_theme_mod( $k, $v );
                    }
                } else {
                    update_option( $this->args['opt_name'], $value );
                }

                $this->options = $value;

                // Set a global variable by the global_variable argument.
                $this->set_global_variable();

                /**
                 * action 'redux-saved-{opt_name}'
                 * @deprecated
                 * @param mixed $value set/saved option value
                 */
                do_action( "redux-saved-{$this->args['opt_name']}", $value ); // REMOVE
                /**
                 * action 'redux/options/{opt_name}/saved'
                 * @param mixed $value set/saved option value
                 */
                do_action( "redux/options/{$this->args['opt_name']}/saved", $value );

            }
        } // set_options()

        /**
         * ->get_options(); This is used to get options from the database
         *
         * @since ReduxFramework 3.0.0
         */
        function get_options() {
            $defaults = false;
            $results = array();
            if ( !empty( $this->defaults ) ) {
                $defaults = $this->defaults;
            }           

            if ( $this->args['database'] === "transient" ) {
                $result = get_transient( $this->args['opt_name'] . '-transient' );
            } else if ($this->args['database'] === "theme_mods" ) {
                $result = get_theme_mod( $this->args['opt_name'] . '-mods' );
            } else if ( $this->args['database'] === 'theme_mods_expanded' ) {
                $result = get_theme_mods();
            } else {
                $result = get_option( $this->args['opt_name']);
            }

            if ( empty( $result ) && !empty( $defaults ) ) {
                $results = $defaults;
                $this->set_options( $results );
            } else {
                $this->options = $result;
            }
            // Set a global variable by the global_variable argument.
            $this->set_global_variable();
        } // get_options()

        /**
         * ->get_wordpress_date() - Get Wordpress specific data from the DB and return in a usable array
         *
         * @since ReduxFramework 3.0.0
         */
        function get_wordpress_data($type = false, $args = array()) {
            
            $data = "";

            /**
             * filter 'redux/options/{opt_name}/wordpress_data/{type}/'
             * @deprecated
             * @param string $data
             */
            $data = apply_filters( "redux/options/{$this->args['opt_name']}/wordpress_data/$type/", $data ); // REMOVE LATER
            /**
             * filter 'redux/options/{opt_name}/data/{type}'
             * @param string $data
             */
            $data = apply_filters( "redux/options/{$this->args['opt_name']}/data/$type", $data ); 
            $argsKey = "";
            foreach($args as $key => $value) {
                if (!is_array($value)) {
                    $argsKey .= $value."-";
                } else {
                    $argsKey .= implode( "-", $value);
                }
            }
            if ( empty( $data ) && isset( $this->wp_data[$type.$argsKey] ) ) {
                $data = $this->wp_data[$type.$argsKey];
            }

            if ( empty($data) && !empty($type) ) {
   
                /**
                    Use data from Wordpress to populate options array
                **/
                if (!empty($type) && empty($data)) {
                    if (empty($args)) {
                        $args = array();
                    }
                    $data = array();
                    $args = wp_parse_args($args, array());  
                    if ($type == "categories" || $type == "category") {
                        $cats = get_categories($args); 
                        if (!empty($cats)) {        
                            foreach ( $cats as $cat ) {
                                $data[$cat->term_id] = $cat->name;
                            }//foreach
                        } // If
                    } else if ($type == "menus" || $type == "menu") {
                        $menus = wp_get_nav_menus($args);
                        if(!empty($menus)) {
                            foreach ($menus as $item) {
                                $data[$item->term_id] = $item->name;
                            }//foreach
                        }//if
                    } else if ($type == "pages" || $type == "page") {
                        $pages = get_pages($args); 
                        if (!empty($pages)) {
                            foreach ( $pages as $page ) {
                                $data[$page->ID] = $page->post_title;
                            }//foreach
                        }//if
                    } else if ($type == "terms" || $type == "term") {
                        $taxonomies = $args['taxonomies'];
                        unset($args['taxonomies']);
                        $terms = get_terms($taxonomies, $args); // this will get nothing
                        if (!empty($terms)) {       
                            foreach ( $terms as $term ) {
                                $data[$term->term_id] = $term->name;
                            }//foreach
                        } // If
                    } else if ($type == "taxonomy" || $type == "taxonomies") {
                        $taxonomies = get_taxonomies($args); 
                        if (!empty($taxonomies)) {
                            foreach ( $taxonomies as $key => $taxonomy ) {
                                $data[$key] = $taxonomy;
                            }//foreach
                        } // If
                    } else if ($type == "posts" || $type == "post") {
                        $posts = get_posts($args); 
                        if (!empty($posts)) {
                            foreach ( $posts as $post ) {
                                $data[$post->ID] = $post->post_title;
                            }//foreach
                        }//if
                    } else if ($type == "post_type" || $type == "post_types") {
                        global $wp_post_types;
                        $defaults = array(
                            'public' => true,
                            //'publicly_queryable' => true,
                            'exclude_from_search' => false,
                            //'_builtin' => true,
                        );
                        $args = wp_parse_args( $args, $defaults );
                        $output = 'names';
                        $operator = 'and';
                        $post_types = get_post_types($args, $output, $operator);

                        //$post_types['page'] = 'page';
                        //$post_types['post'] = 'post';
                        ksort($post_types);

                        foreach ( $post_types as $name => $title ) {
                            if ( isset($wp_post_types[$name]->labels->menu_name) ) {
                                $data[$name] = $wp_post_types[$name]->labels->menu_name;
                            } else {
                                $data[$name] = ucfirst($name);
                            }
                        }
                    } else if ($type == "tags" || $type == "tag") { // NOT WORKING!
                        $tags = get_tags($args); 
                        if (!empty($tags)) {
                            foreach ( $tags as $tag ) {
                                $data[$tag->term_id] = $tag->name;
                            }//foreach
                        }//if
                    } else if ($type == "menu_location" || $type == "menu_locations") {
                        global $_wp_registered_nav_menus;
                        foreach($_wp_registered_nav_menus as $k => $v) {
                            $data[$k] = $v;
                        }
                    }//if
                    else if ($type == "elusive-icons" || $type == "elusive-icon" || $type == "elusive" || 
                             $type == "font-icon" || $type == "font-icons" || $type == "icons") {
                        /**
                        * filter 'redux-font-icons'
                        * @deprecated
                        * @param array $font_icons  array of elusive icon classes
                        */                        
                        $font_icons = apply_filters( 'redux-font-icons', array() ); // REMOVE LATER
                        /**
                        * filter 'redux/font-icons'
                        * @deprecated
                        * @param array $font_icons  array of elusive icon classes
                        */                        
                        $font_icons = apply_filters( 'redux/font-icons', $font_icons );
                        /**
                        * filter 'redux/{opt_name}/field/font/icons'
                        * @deprecated
                        * @param array $font_icons  array of elusive icon classes
                        */                        
                        $font_icons = apply_filters( "redux/{$this->args['opt_name']}/field/font/icons", $font_icons );                        
                        foreach($font_icons as $k) {
                            $data[$k] = $k;
                        }
                    }else if ($type == "roles") {
                        /** @global WP_Roles $wp_roles */
                        global $wp_roles;
                        $data = $wp_roles->get_names();
                    }else if ($type == "sidebars" || $type == "sidebar") {
                        /** @global array $wp_registered_sidebars */
                        global $wp_registered_sidebars;
                        foreach ($wp_registered_sidebars as $key=>$value) {
                            $data[$key] = $value['name'];
                        }
                    }else if ($type == "capabilities") {
                        /** @global WP_Roles $wp_roles */
                        global $wp_roles;
                        foreach( $wp_roles->roles as $role ){
                            foreach( $role['capabilities'] as $key => $cap ){
                                $data[$key] = ucwords(str_replace('_', ' ', $key));
                            }
                        }
                    }else if ($type == "callback") {
                        if ( !is_array( $args ) ) {
                            $args = array( $args );
                        }
                        $data = call_user_func($args[0]);
                    }//if           
                }//if
                
                $this->wp_data[$type.$argsKey] = $data;

            }//if
            
            return $data;
        } // get_wordpress_data()    

        /**
         * ->show(); This is used to echo and option value from the options array
         *
         * @since       1.0.0
         * @access      public
         * @param       string $opt_name The name of the option being shown
         * @param       mixed $default The value to show if $opt_name isn't set
         * @return      void
         */
        public function show( $opt_name, $default = '' ) {
            $option = $this->get( $opt_name );
            if( !is_array( $option ) && $option != '' ) {
                echo $option;
            } elseif( $default != '' ) {
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
            if( !is_null( $this->sections ) && is_null( $this->options_defaults ) ) {
                // fill the cache
                foreach( $this->sections as $section ) {
                    if( isset( $section['fields'] ) ) {
                        foreach( $section['fields'] as $field ) {
                            if( isset( $field['default'] ) ) {
                                $this->options_defaults[$field['id']] = $field['default'];
                            } elseif (isset($field['options'])) {
                                $this->options_defaults[$field['id']] = $field['options'];
                            }
                        }
                    }
                }
            }

            /**
            * filter 'redux/options/{opt_name}/defaults'
            * @param array $defaults  option default values
            */
            $this->options_defaults = apply_filters( "redux/options/{$this->args['opt_name']}/defaults", $this->options_defaults );

            return $this->options_defaults;
        }


        /**
         * Get fold values into an array suitable for setting folds
         *
         * @since ReduxFramework 1.0.0
         */
        function _fold_values() {

           /*
            Folds work by setting the folds value like so
            $this->folds['parentID']['parentValue'][] = 'childId'
           */ 
//          $folds = array();
            if( !is_null( $this->sections ) ) {

                foreach( $this->sections as $section ) {
                    if( isset( $section['fields'] ) ) {
                        foreach( $section['fields'] as $field ) {
                            //if we have required option in group field
                            if(isset($field['fields']) && is_array($field['fields'])){
                                foreach ($field['fields'] as $subfield) {
                                    if(isset($subfield['required']))
                                        $this->get_fold($subfield);
                                }
                            }
                            if( isset( $field['required'] ) ) {
                                $this->get_fold($field);
                            }
                        }
                    }
                }
            }
            
            
            $parents = array();
            
            foreach ($this->folds as $k=>$fold) { // ParentFolds WITHOUT parents
                if ( empty( $fold['children'] ) || !empty( $fold['children']['parents'] ) ) {
                    continue;
                }
                $fold['value'] = $this->options[$k];
                foreach ($fold['children'] as $key =>$value) {
                    if ($key == $fold['value']) {
                        unset($fold['children'][$key]);
                    }
                }
                if (empty($fold['children'])) {
                    continue;
                }
                foreach ($fold['children'] as $key => $value) {
                    foreach ($value as $k=> $hidden) {
                        if ( !in_array( $hidden, $this->toHide ) ) {
                            $this->toHide[] = $hidden;    
                        }
                    }
                }               
                $parents[] = $fold;
            }


            
            return $this->folds;
            
        } // _fold_values()

        /**
         * get_fold() - Get the fold values
         * 
         * @param array $field
         * @return array
         */
        function get_fold($field){
            if ( !is_array( $field['required'] ) ) {
                /*
                Example variable:
                    $var = array(
                    'fold' => 'id'
                    );
                */
                $this->folds[$field['required']]['children'][1][] = $field['id'];
                $this->folds[$field['id']]['parent'] = $field['required'];
            } else {
//                $parent = $foldk = $field['required'][0];
                $foldk = $field['required'][0];
//                $comparison = $field['required'][1];
                $value = $foldv = $field['required'][2];                                                                                    
                //foreach( $field['required'] as $foldk=>$foldv ) {
                    

                    if ( is_array( $value ) ) {
                        /*
                        Example variable:
                            $var = array(
                            'fold' => array( 'id' , '=', array(1, 5) )
                            );
                        */
                        
                        foreach ($value as $foldvValue) {
                            //echo 'id: '.$field['id']." key: ".$foldk.' f-val-'.print_r($foldv)." foldvValue".$foldvValue;
                            $this->folds[$foldk]['children'][$foldvValue][] = $field['id'];
                            $this->folds[$field['id']]['parent'] = $foldk;
                        }
                    } else {
                        
                        //!DOVY If there's a problem, this is where it's at. These two cases.
                        //This may be able to solve this issue if these don't work
                        //if (count($field['fold']) == count($field['fold'], COUNT_RECURSIVE)) {
                        //}

                        if (count($field['required']) === 1 && is_numeric($foldk)) {
                            /*
                            Example variable:
                                $var = array(
                                'fold' => array( 'id' )
                                );
                            */  
                            $this->folds[$field['id']]['parent'] = $foldk;
                            $this->folds[$foldk]['children'][1][] = $field['id'];
                        } else {
                            /*
                            Example variable:
                                $var = array(
                                'fold' => array( 'id' => 1 )
                                );
                            */                      
                            if (empty($foldv)) {
                                $foldv = 0;
                            }
                            $this->folds[$field['id']]['parent'] = $foldk;
                            $this->folds[$foldk]['children'][$foldv][] = $field['id'];    
                        }
                    }
                //}
            }
            return $this->folds;
        } // get_fold()

        /**
         * Set default options on admin_init if option doesn't exist
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function _default_cleanup() {

            //$this->instance = $this;

            // Fix the global variable name
            if ( $this->args['global_variable'] == "" && $this->args['global_variable'] !== false ) {
                $this->args['global_variable'] = str_replace('-', '_', $this->args['opt_name']);
            }
        
        }

        /**
         * Class Options Page Function, creates main options page.
         * @since       1.0.0
         * @access      public
         * @return void
         */
        function _options_page() {

            if( $this->args['menu_type'] == 'submenu' ) {
                $this->page = add_submenu_page(
                    $this->args['page_parent'],
                    $this->args['page_title'],
                    $this->args['menu_title'],
                    $this->args['page_permissions'],
                    $this->args['page_slug'],
                    array( &$this, '_options_page_html' )
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

                if( true === $this->args['allow_sub_menu'] ) {
                    if( !isset( $section['type'] ) || $section['type'] != 'divide' ) {

                        foreach( $this->sections as $k => $section ) {
                            if ( !isset( $section['title'] ) )
                                continue;

                            if ( isset( $section['submenu'] ) && $section['submenu'] == false )
                                continue;

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

                    if( true === $this->args['show_import_export'] ) {
                        add_submenu_page(
                            $this->args['page_slug'],
                            __( 'Import / Export', $this->args['domain'] ),
                            __( 'Import / Export', $this->args['domain'] ),
                            $this->args['page_permissions'],
                            $this->args['page_slug'] . '&tab=import_export_default', 
                            //create_function( '$a', "return null;" )
                            '__return_null'
                        );
                    }

                    if( true === $this->args['dev_mode'] ) {
                        add_submenu_page(
                            $this->args['page_slug'],
                            __( 'Options Object', $this->args['domain'] ),
                            __( 'Options Object', $this->args['domain'] ),
                            $this->args['page_permissions'],
                            $this->args['page_slug'] . '&tab=dev_mode_default',
                            //create_function('$a', "return null;")
                            '__return_null'
                        );
                    }

                    if( true === $this->args['system_info'] ) {
                        add_submenu_page(
                            $this->args['page_slug'],
                            __( 'System Info', $this->args['domain'] ),
                            __( 'System Info', $this->args['domain'] ),
                            $this->args['page_permissions'],
                            $this->args['page_slug'] . '&tab=system_info_default',
                            //create_function( '$a', "return null;" )
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
         * @global      $wp_styles
         * @return      void
         */
        function _admin_bar_menu()
        {
            global $menu, $submenu, $wp_admin_bar, $redux_demo;
            $ct = wp_get_theme();
            $theme_data = $ct;
            if ( !is_super_admin() || !is_admin_bar_showing() || !$this->args['admin_bar'] )
                return;
            if($menu){
                foreach($menu as $menu_item):
                    if($menu_item[2]===$this->args["page_slug"]){
                        $nodeargs = array(
                            'id'    => $menu_item[2],
                            'title' => "<span class='ab-icon dashicons-admin-generic'></span>".$menu_item[0],
                            'href'  => admin_url('admin.php?page='.$menu_item[2]),
                            'meta'  => array( )
                        );
                        $wp_admin_bar->add_node( $nodeargs );
                        break;
                    }
                    endforeach;
                if ( isset( $submenu[$this->args["page_slug"]] ) && is_array( $submenu[$this->args["page_slug"]] ) ) {
                    foreach($submenu[$this->args["page_slug"]] as $index => $redux_options_submenu):
                        $subnodeargs = array(
                            'id'    => $this->args["page_slug"] . '_' . $index,
                            'title' => $redux_options_submenu[0],
                            'parent'=> $this->args["page_slug"],
                            'href'  => admin_url('admin.php?page='.$redux_options_submenu[2]),
                        );
                        $wp_admin_bar->add_node( $subnodeargs );
                    endforeach;
                }
            }else{
                $nodeargs = array(
                    'id'    => $this->args["page_slug"],
                    'title' => "<span class='ab-icon dashicons-admin-generic'></span>" . $theme_data->get('Name') . " " . __('Options', 'redux-framework-demo'),
                    'href'  => admin_url('admin.php?page='.$this->args["page_slug"]),
                    'meta'  => array()
                );
                $wp_admin_bar->add_node( $nodeargs );
            }
        } // _admin_bar_menu()

        /**
         * Enqueue CSS/JS for options page
         *
         * @since       1.0.0
         * @access      public
         * @global      $wp_styles
         * @return      void
         */
        public function _enqueue_output() {

            if( $this->args[ 'output' ] == false && $this->args[ 'compiler' ] == false ) {
                return;
            }

            /** @noinspection PhpUnusedLocalVariableInspection */
            foreach( $this->sections as $k => $section ) {
                if( isset($section['type'] ) && ( $section['type'] == 'divide' ) ) {
                    continue;
                }
                if( isset( $section['fields'] ) ) {
                    /** @noinspection PhpUnusedLocalVariableInspection */
                    foreach( $section['fields'] as $fieldk => $field ) {
                        if( isset( $field['type'] ) && $field['type'] != "callback"  ) {
                            $field_class = "ReduxFramework_{$field['type']}";
                            if( !class_exists( $field_class ) ) {

                                if ( !isset( $field['compiler'] ) ) {
                                    $field['compiler'] = "";
                                }

                                 /**
                                 * Field class file
                                 * 
                                 * filter 'redux/{opt_name}/field/class/{field.type}
                                 * @param string        field class file
                                 * @param array $field  field config data
                                 */
                                $class_file = apply_filters( "redux/{$this->args['opt_name']}/field/class/{$field['type']}", self::$_dir . "inc/fields/{$field['type']}/field_{$field['type']}.php", $field );
                                
                                if( $class_file && file_exists($class_file) && !class_exists( $field_class ) ) {
                                    /** @noinspection PhpIncludeInspection */
                                    require_once( $class_file );
                                }
                            }   

                            if( !empty( $this->options[$field['id']] ) && class_exists( $field_class ) && method_exists( $field_class, 'output' ) && $this->_can_output_css($field) ) {
                                
                                if ( !empty($field['output']) && !is_array( $field['output'] ) ) {
                                    $field['output'] = array( $field['output'] );
                                }
                                $value = isset($this->options[$field['id']])?$this->options[$field['id']]:'';
                                $enqueue = new $field_class( $field, $value, $this );
                                /** @noinspection PhpUndefinedMethodInspection */
                                if ( ( ( isset( $field['output'] ) && !empty( $field['output'] ) ) || ( isset( $field['compiler'] ) && !empty( $field['compiler'] ) ) || $field['type'] == "typography" ) ) {
                                    $enqueue->output();
                                }
                            }
                        }           
                    }
                    
                }
            }
            if ( !empty( $this->outputCSS ) && $this->args['output_tag'] == true ) {
                echo '<style type="text/css" class="options-output">'.$this->outputCSS.'</style>';  
            }

            
            if ( !empty( $this->typography ) && !empty( $this->typography ) && filter_var( $this->args['output'], FILTER_VALIDATE_BOOLEAN ) ) {
                $version = !empty( $this->options['REDUX_last_saved'] ) ? $this->options['REDUX_last_saved'] : '';
                $typography = new ReduxFramework_typography( null, null, $this );
                echo '<link rel="stylesheet" id="options-google-fonts"  href="'.$typography->makeGoogleWebfontLink( $this->typography ).'&amp;v='.$version.'" type="text/css" media="all" />';
                //wp_register_style( 'redux-google-fonts', $typography->makeGoogleWebfontLink( $this->typography ), '', $version );
                //wp_enqueue_style( 'redux-google-fonts' ); 
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

            wp_register_style(
                'redux-css',
                self::$_url . 'assets/css/redux.css',
                array( 'farbtastic' ),
                filemtime( self::$_dir . 'assets/css/redux.css' ),
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
                'select2-css',
                self::$_url . 'assets/js/vendor/select2/select2.css',
                array(),
                filemtime( self::$_dir . 'assets/js/vendor/select2/select2.css' ),
                'all'
            );          

            $wp_styles->add_data( 'redux-elusive-icon-ie7', 'conditional', 'lte IE 7' );

            /**
             * jQuery UI stylesheet src
             * filter 'redux/page/{opt_name}/enqueue/jquery-ui-css'
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

            wp_enqueue_style( 'redux-css' );

            wp_enqueue_style( 'select2-css' );

            wp_enqueue_style( 'redux-elusive-icon' );
            wp_enqueue_style( 'redux-elusive-icon-ie7' );

            if(is_rtl()){
                wp_register_style(
                    'redux-rtl-css',
                    self::$_url . 'assets/css/rtl.css',
                    '',
                    filemtime( self::$_dir . 'assets/css/rtl.css' ),
                    'all'
                );
                wp_enqueue_style( 'redux-rtl-css' );
            } 

            if ( $this->args['dev_mode'] === true) { // Pretty object output
                /*
                wp_enqueue_script(
                    'json-view-js',
                    self::$_url . 'assets/js/vendor/jsonview.min.js',
                    array( 'jquery' ),
                    time(),
                    true
                );
                */
            }

            wp_enqueue_script('jquery');
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_style('jquery-ui-sortable');
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_script('jquery-ui-dialog');
            wp_enqueue_script('jquery-ui-slider');
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_script('jquery-ui-accordion');
            wp_enqueue_style( 'wp-color-picker' );

            if ( function_exists( 'wp_enqueue_media' ) ) {
                wp_enqueue_media();
            } else {
                wp_enqueue_script( 'media-upload' );
            }

            add_thickbox();

            wp_register_script( 
                'select2-js', 
                self::$_url . 'assets/js/vendor/select2/select2.min.js',
                array( 'jquery' ),
                filemtime( self::$_dir . 'assets/js/vendor/select2/select2.min.js' ),
                true
            );

            wp_register_script( 
                'ace-editor-js', 
                self::$_url . 'assets/js/vendor/ace_editor/ace.js',
                array( 'jquery' ),
                filemtime( self::$_dir . 'assets/js/vendor/ace_editor/ace.js' ),
                true
            );          
            
            // Embed the compress version unless in dev mode
            if ( isset($this->args['dev_mode'] ) && $this->args['dev_mode'] === true) {
                wp_register_script(
                    'redux-vendor',
                    self::$_url . 'assets/js/vendor.min.js',
                    array( 'jquery'),
                    time(),
                    true
                );                                        
                wp_register_script(
                    'redux-js',
                    self::$_url . 'assets/js/redux.js',
                    array( 'jquery', 'select2-js', 'ace-editor-js', 'redux-vendor' ),
                    time(),
                    true
                );
            } else {
                if ( file_exists( self::$_dir . 'assets/js/redux.min.js' ) ) {
                    wp_register_script(
                        'redux-js',
                        self::$_url . 'assets/js/redux.min.js',
                        array( 'jquery', 'select2-js', 'ace-editor-js' ),
                        filemtime( self::$_dir . 'assets/js/redux.min.js' ),
                        true
                    );
                }
            }
  
            
            foreach( $this->sections as $section ) {
                if( isset( $section['fields'] ) ) {
                    foreach( $section['fields'] as $field ) {
                        // TODO AFTER GROUP WORKS - Revert IF below
                        // if( isset( $field['type'] ) && $field['type'] != 'callback' ) {
                        if( isset( $field['type'] ) && $field['type'] != 'callback' && $field['type'] != 'group' ) {
                            $field_class = 'ReduxFramework_' . $field['type'];
                            /**
                             * Field class file
                             * 
                             * filter 'redux/{opt_name}/field/class/{field.type}
                             * @param string        field class file path
                             * @param array $field  field config data
                             */
                            $class_file = apply_filters( "redux/{$this->args['opt_name']}/field/class/{$field['type']}", self::$_dir . "inc/fields/{$field['type']}/field_{$field['type']}.php", $field );
                            if( $class_file ) {
                                if( !class_exists($field_class) ) {
                                    /** @noinspection PhpIncludeInspection */
                                    require_once( $class_file );
                                }

                                

                                if ( ( method_exists( $field_class, 'enqueue' ) ) || method_exists( $field_class, 'localize' ) ) {
                                    if ( !isset( $this->options[$field['id']] ) ) {
                                        $this->options[$field['id']] = "";
                                    }
                                    $theField = new $field_class( $field, $this->options[$field['id']], $this );
                                    
                                    if ( !wp_script_is( 'redux-field-'.$field['type'].'-js', 'enqueued' ) && class_exists($field_class) && $this->args['dev_mode'] === true && method_exists( $field_class, 'enqueue' ) ) {
                                        /** @noinspection PhpUndefinedMethodInspection */
                                        //echo "DOVY";
                                        $theField->enqueue();    
                                    }
                                    if ( method_exists( $field_class, 'localize' ) ) {
                                        /** @noinspection PhpUndefinedMethodInspection */
                                        $params = $theField->localize($field);
                                        if ( !isset( $this->localize_data[$field['type']] ) ) {
                                            $this->localize_data[$field['type']] = array();
                                        }
                                        $this->localize_data[$field['type']][$field['id']] = $theField->localize($field);
                                    } 
                                    unset($theField);                               
                                }
                            }
                        }
                    }
                }
            }


            $this->localize_data['folds'] = $this->folds;
            $this->localize_data['fieldsHidden'] = $this->fieldsHidden;
            $this->localize_data['options'] = $this->options;
            $this->localize_data['defaults'] = $this->options_defaults;
            $this->localize_data['args'] = array(
                'save_pending'          => __( 'You have changes that are not saved. Would you like to save them now?', $this->args['domain'] ), 
                'reset_confirm'         => __( 'Are you sure? Resetting will lose all custom values.', $this->args['domain'] ), 
                'reset_section_confirm' => __( 'Are you sure? Resetting will lose all custom values in this section.', $this->args['domain'] ), 
                'preset_confirm'        => __( 'Your current options will be replaced with the values of this preset. Would you like to proceed?', $this->args['domain'] ), 
                'opt_name'              => $this->args[
