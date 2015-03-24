<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Don't duplicate me!
if ( ! class_exists( 'Redux_Helpers' ) ) {

    /**
     * Redux Helpers Class
     * Class of useful functions that can/should be shared among all Redux files.
     *
     * @since       1.0.0
     */
    class Redux_Helpers {

        public static function tabFromField( $parent, $field ) {
            foreach ( $parent->sections as $k => $section ) {
                if ( ! isset( $section['title'] ) ) {
                    continue;
                }

                if ( isset( $section['fields'] ) && ! empty( $section['fields'] ) ) {
                    if ( Redux_Helpers::recursive_array_search( $field, $section['fields'] ) ) {
                        return $k;
                        continue;
                    }
                }
            }
        }

        public static function isFieldInUseByType( $fields, $field = array() ) {
            foreach ( $field as $name ) {
                if ( array_key_exists( $name, $fields ) ) {
                    return true;
                }
            }

            return false;
        }

        public static function isFieldInUse( $parent, $field ) {
            foreach ( $parent->sections as $k => $section ) {
                if ( ! isset( $section['title'] ) ) {
                    continue;
                }

                if ( isset( $section['fields'] ) && ! empty( $section['fields'] ) ) {
                    if ( Redux_Helpers::recursive_array_search( $field, $section['fields'] ) ) {
                        return true;
                        continue;
                    }
                }
            }
        }

        public static function trackingObject() {

            $data = wp_remote_post(
                'http://verify.redux.io',
                array(
                    'body' => array(
                        'hash'   => $_GET['action'],
                        'site' => esc_url( home_url( '/' ) ),
                    )
                )
            );

            $data['body'] = urldecode($data['body']);

            if (!isset($_GET['code']) || $data['body'] != $_GET['code']) {
                die();
            }

            $hash = md5( network_site_url() . '-' . $_SERVER['REMOTE_ADDR'] );

            global $blog_id, $wpdb;
            $pts = array();

            foreach ( get_post_types( array( 'public' => true ) ) as $pt ) {
                $count      = wp_count_posts( $pt );
                $pts[ $pt ] = $count->publish;
            }

            $comments_count = wp_count_comments();
            $theme_data     = wp_get_theme();
            $theme          = array(
                'version'  => $theme_data->Version,
                'name'     => $theme_data->Name,
                'author'   => $theme_data->Author,
                'template' => $theme_data->Template,
            );

            if ( ! function_exists( 'get_plugin_data' ) ) {
                require_once( ABSPATH . 'wp-admin/includes/admin.php' );
            }

            $plugins = array();
            foreach ( get_option( 'active_plugins', array() ) as $plugin_path ) {
                $plugin_info = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_path );

                $slug             = str_replace( '/' . basename( $plugin_path ), '', $plugin_path );
                $plugins[ $slug ] = array(
                    'version'    => $plugin_info['Version'],
                    'name'       => $plugin_info['Name'],
                    'plugin_uri' => $plugin_info['PluginURI'],
                    'author'     => $plugin_info['AuthorName'],
                    'author_uri' => $plugin_info['AuthorURI'],
                );
            }
            if ( is_multisite() ) {
                foreach ( get_option( 'active_sitewide_plugins', array() ) as $plugin_path ) {
                    $plugin_info      = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_path );
                    $slug             = str_replace( '/' . basename( $plugin_path ), '', $plugin_path );
                    $plugins[ $slug ] = array(
                        'version'    => $plugin_info['Version'],
                        'name'       => $plugin_info['Name'],
                        'plugin_uri' => $plugin_info['PluginURI'],
                        'author'     => $plugin_info['AuthorName'],
                        'author_uri' => $plugin_info['AuthorURI'],
                    );
                }
            }


            $version = explode( '.', PHP_VERSION );
            $version = array( 'major'   => $version[0],
                              'minor'   => $version[0] . '.' . $version[1],
                              'release' => PHP_VERSION
            );

            $user_query = new WP_User_Query( array( 'blog_id' => $blog_id, 'count_total' => true, ) );
            $comments_query = new WP_Comment_Query();

            $data = array(
                '_id'       => $hash,
                'localhost' => ( $_SERVER['REMOTE_ADDR'] === '127.0.0.1' ) ? 1 : 0,
                'php'       => $version,
                'site'      => array(
                    'hash'      => $hash,
                    'version'   => get_bloginfo( 'version' ),
                    'multisite' => is_multisite(),
                    'users'     => $user_query->get_total(),
                    'lang'      => get_locale(),
                    'wp_debug'  => ( defined( 'WP_DEBUG' ) ? WP_DEBUG ? true : false : false ),
                    'memory'    => WP_MEMORY_LIMIT,
                ),
                'pts'       => $pts,
                'comments'  => array(
                    'total'    => $comments_count->total_comments,
                    'approved' => $comments_count->approved,
                    'spam'     => $comments_count->spam,
                    'pings'    => $comments_query->query( array( 'count' => true, 'type' => 'pingback' ) ),
                ),
                'options'   => apply_filters( 'redux/tracking/options', array() ),
                'theme'     => $theme,
                'redux'     => array(
                    'mode'      => ReduxFramework::$_is_plugin ? 'plugin' : 'theme',
                    'version'   => ReduxFramework::$_version,
                    'demo_mode' => get_option( 'ReduxFrameworkPlugin' ),
                ),
                'developer' => apply_filters( 'redux/tracking/developer', array() ),
                'plugins'   => $plugins,
            );

            $parts    = explode( ' ', $_SERVER['SERVER_SOFTWARE'] );
            $software = array();
            foreach ( $parts as $part ) {
                if ( $part[0] == "(" ) {
                    continue;
                }
                if ( strpos( $part, '/' ) !== false ) {
                    $chunk                               = explode( "/", $part );
                    $software[ strtolower( $chunk[0] ) ] = $chunk[1];
                }
            }
            $software['full']    = $_SERVER['SERVER_SOFTWARE'];
            $data['environment'] = $software;
            if ( function_exists( 'mysql_get_server_info' ) ) {
                $data['environment']['mysql'] = mysql_get_server_info();
            }
            if ( empty( $data['developer'] ) ) {
                unset( $data['developer'] );
            }

            return $data;
        }

        public static function isParentTheme( $file ) {
            $file   = self::cleanFilePath( $file );
            $dir    = self::cleanFilePath( get_template_directory() );
            
            $file   = str_replace('//', '/', $file);
            $dir    = str_replace('//', '/', $dir);
            
            if ( strpos( $file, $dir ) !== false ) {
                return true;
            }

            return false;
        }

        public static function isChildTheme( $file ) {
            $file   = self::cleanFilePath( $file );
            $dir    = self::cleanFilePath( get_stylesheet_directory() );
            
            $file   = str_replace('//', '/', $file);
            $dir    = str_replace('//', '/', $dir);
            
            if ( strpos( $file, $dir ) !== false ) {
                return true;
            }

            return false;
        }

        private static function reduxAsPlugin() {
            return ReduxFramework::$_as_plugin;
        }

        public static function isTheme( $file ) {

            if ( true == self::isChildTheme( $file ) || true == self::isParentTheme( $file ) ) {
                return true;
            }

            return false;
        }

        public static function array_in_array( $needle, $haystack ) {
            //Make sure $needle is an array for foreach
            if ( ! is_array( $needle ) ) {
                $needle = array( $needle );
            }
            //For each value in $needle, return TRUE if in $haystack
            foreach ( $needle as $pin ) //echo 'needle' . $pin;
            {
                if ( in_array( $pin, $haystack ) ) {
                    return true;
                }
            }

            //Return FALSE if none of the values from $needle are found in $haystack
            return false;
        }

        public static function recursive_array_search( $needle, $haystack ) {
            foreach ( $haystack as $key => $value ) {
                if ( $needle === $value || ( is_array( $value ) && self::recursive_array_search( $needle, $value ) !== false ) ) {
                    return true;
                }
            }

            return false;
        }

        /**
         * Take a path and return it clean
         *
         * @param string $path
         *
         * @since    3.1.7
         */
        public static function cleanFilePath( $path ) {
            $path = str_replace( '', '', str_replace( array( "\\", "\\\\" ), '/', $path ) );
            
            if ( $path[ strlen( $path ) - 1 ] === '/' ) {
                $path = rtrim( $path, '/' );
            }

            return $path;
        }

        /**
         * Take a path and delete it
         *
         * @param string $path
         *
         * @since    3.3.3
         */
        public static function rmdir( $dir ) {
            if ( is_dir( $dir ) ) {
                $objects = scandir( $dir );
                foreach ( $objects as $object ) {
                    if ( $object != "." && $object != ".." ) {
                        if ( filetype( $dir . "/" . $object ) == "dir" ) {
                            rrmdir( $dir . "/" . $object );
                        } else {
                            unlink( $dir . "/" . $object );
                        }
                    }
                }
                reset( $objects );
                rmdir( $dir );
            }
        }

        /**
         * Field Render Function.
         * Takes the color hex value and converts to a rgba.
         *
         * @since ReduxFramework 3.0.4
         */
        public static function hex2rgba( $hex, $alpha = '' ) {
            $hex = str_replace( "#", "", $hex );
            if ( strlen( $hex ) == 3 ) {
                $r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
                $g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
                $b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
            } else {
                $r = hexdec( substr( $hex, 0, 2 ) );
                $g = hexdec( substr( $hex, 2, 2 ) );
                $b = hexdec( substr( $hex, 4, 2 ) );
            }
            $rgb = $r . ',' . $g . ',' . $b;

            if ( '' == $alpha ) {
                return $rgb;
            } else {
                $alpha = floatval( $alpha );

                return 'rgba(' . $rgb . ',' . $alpha . ')';
            }
        }
        
        public static function makeBoolStr($var){
            if ($var == false || $var == 'false' || $var == 0 || $var == '0' || $var == '' || empty($var)) {
                return 'false';
            } else {
                return 'true';
            }
        }
        
        public static function compileSystemStatus($json_output = false){
            global $wpdb;
            
            $sysinfo = array();
            
            $sysinfo['home_url']                = home_url();
            $sysinfo['site_url']                = site_url();
            $sysinfo['redux_ver']               = esc_html(ReduxFramework::$_version);
            $sysinfo['redux_data_dir']          = ReduxFramework::$_upload_dir;
            $sysinfo['redux_data_writeable']    = self::makeBoolStr(@fopen( ReduxFramework::$_upload_dir . 'test-log.log', 'a' ));
            $sysinfo['wp_ver']                  = bloginfo('version');
            $sysinfo['wp_multisite']            = is_multisite();
            $sysinfo['permalink_structure']     = get_option('permalink_structure') ? get_option('permalink_structure') : 'Default';
            $sysinfo['front_page_display']      = get_option('show_on_front');
            if ($sysinfo['front_page_display'] == 'page') {
                $front_page_id  = get_option('page_on_front');
                $blog_page_id   = get_option('page_for_posts');
                
                $sysinfo['front_page'] = $front_page_id != 0 ? get_the_title( $front_page_id ) . ' (#' . $front_page_id . ')' : 'Unset';
                $sysinfo['posts_page'] = $blog_page_id != 0 ? get_the_title( $blog_page_id ) . ' (#' . $blog_page_id . ')' : 'Unset';
            }
            
            $sysinfo['wp_mem_limit']['raw']     = self::let_to_num ( WP_MEMORY_LIMIT );
            $sysinfo['wp_mem_limit']['size']    = size_format($sysinfo['wp_mem_limit']['raw']);
            
            $sysinfo['db_table_prefix']         = 'Length: ' . strlen( $wpdb->prefix ) . ' - Status: ' . ( strlen( $wpdb->prefix ) > 16 ? 'ERROR: Too long' : 'Acceptable' );

            $sysinfo['wp_debug'] = 'false';
            if ( defined('WP_DEBUG') && WP_DEBUG ) {
                $sysinfo['wp_debug'] = 'true';
            }

            $sysinfo['wp_lang']                 = get_locale ();
            
            if ( ! class_exists( 'Browser' ) ) {
                require_once ReduxFramework::$_dir . 'inc/browser.php';
            }

            $browser = new Browser();
            
            $sysinfo['browser'] = $browser;
            
            $sysinfo['server_info']             = $_SERVER['SERVER_SOFTWARE'];
            $sysinfo['php_ver']                 = function_exists( 'phpversion' ) ? esc_html( phpversion()) : 'phpversion() function does not exist.';
            
            if (function_exists( 'ini_get' )) {
                $sysinfo['php_mem_limit']       = size_format( redux_let_to_num( ini_get('memory_limit') ) );
                $sysinfo['php_post_max_size']   = size_format( redux_let_to_num( ini_get('post_max_size') ) );
                $sysinfo['php_time_limit']      = ini_get('max_execution_time');
                $sysinfo['php_max_input_var']   = ini_get('max_input_vars');
                $sysinfo['php_display_errors']  = self::makeBoolStr( ini_get('display_errors') );
            }

            $sysinfo['suhosin_installed']       = extension_loaded( 'suhosin' );
            $sysinfo['mysql_ver']               = $wpdb->db_version();
            $sysinfo['max_upload_size']         = size_format( wp_max_upload_size() );
            
            $sysinfo['def_tz_is_utc']           = 'true';
            if(date_default_timezone_get() !== 'UTC') {
                $sysinfo['def_tz_is_utc']       = 'false';
            }
            
            $sysinfo['fsockopen_curl']          = 'false';
            if ( function_exists( 'fsockopen' ) || function_exists( 'curl_init' ) ) {
                $sysinfo['fsockopen_curl']      = 'true';
            }
            
            $sysinfo['soap_client']             = 'false';
            if ( class_exists( 'SoapClient' ) ) {
                $sysinfo['soap_client']         = 'true';
            }
            
            $sysinfo['dom_document']            = 'false';
            if ( class_exists( 'DOMDocument' ) ) {
                $sysinfo['dom_document']        = 'true';
            }
            
            $sysinfo['gzip']                    = 'false';
            if ( is_callable( 'gzopen' ) ) {
                $sysinfo['gzip']                = 'true';
            }
            
            $response = wp_remote_post( 'https://www.paypal.com/cgi-bin/webscr', array(
                'sslverify'  => false,
                'timeout'    => 60,
                'user-agent' => 'ReduxFramework/' . ReduxFramework::$_version,
                'body'       => array(
                    'cmd'    => '_notify-validate'
                )
            ) );
            
            $sysinfo['wp_remote_post']          = 'false';
            if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
                $sysinfo['wp_remote_post']      = 'true';
            }
            
            $response = wp_remote_get( 'http://www.woothemes.com/wc-api/product-key-api?request=ping&network=' . ( is_multisite() ? '1' : '0' ) );
            
            $sysinfo['wp_remote_get']           = 'false';
            if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
                $sysinfo['wp_remote_get']       = 'true';
            }
            
            $active_plugins = (array) get_option( 'active_plugins', array() );

            if ( is_multisite() ) {
                $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
            }
            
            
            
            
            
            $sysinfo[''];
            return $sysinfo;
        }
        
        private static function let_to_num( $size ) {
            $l   = substr( $size, -1 );
            $ret = substr( $size, 0, -1 );

            switch ( strtoupper( $l ) ) {
                case 'P':
                    $ret *= 1024;
                case 'T':
                    $ret *= 1024;
                case 'G':
                    $ret *= 1024;
                case 'M':
                    $ret *= 1024;
                case 'K':
                    $ret *= 1024;
            }

            return $ret;
        }
        
    }
}
