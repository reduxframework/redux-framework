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

        public function trackingObject() {
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
                '_id'       => $this->options['hash'],
                'localhost' => ( $_SERVER['REMOTE_ADDR'] === '127.0.0.1' ) ? 1 : 0,
                'php'       => $version,
                'site'      => array(
                    'hash'      => $this->options['hash'],
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
    }
}
