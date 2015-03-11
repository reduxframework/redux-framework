<?php

    /**
     * Redux Framework API Class
     * Makes instantiating a Redux object an absolute piece of cake.
     *
     * @package     Redux_Framework
     * @author      Dovy Paukstys
     * @subpackage  Core
     */

    // Exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    // Don't duplicate me!
    if ( ! class_exists( 'Redux' ) ) {

        /**
         * Redux API Class
         * Simple API for Redux Framework
         *
         * @since       1.0.0
         */
        class Redux {

            public static $fields = array();
            public static $sections = array();
            public static $help = array();
            public static $args = array();
            public static $priority = array();
            public static $errors = array();
            public static $init = array();
            public static $extensions = array();
            public static $uses_extensions = array();

            public function __call( $closure, $args ) {
                return call_user_func_array( $this->{$closure}->bindTo( $this ), $args );
            }

            public function __toString() {
                return call_user_func( $this->{"__toString"}->bindTo( $this ) );
            }

            public static function load() {
                add_action( 'after_setup_theme', array( 'Redux', 'createRedux' ) );
                add_action( 'init', array( 'Redux', 'createRedux' ) );
            }

            public static function init( $opt_name = "" ) {
                if ( ! empty( $opt_name ) ) {
                    self::loadRedux( $opt_name );
                    remove_action( 'setup_theme', array( 'Redux', 'createRedux' ) );
                }
            }

            public static function loadExtensions( $ReduxFramework ) {
                if ( $instanceExtensions = self::getExtensions( '', $ReduxFramework->args['opt_name'] ) ) {
                    foreach ( $instanceExtensions as $name => $extension ) {
                        if ( ! class_exists( $extension['class'] ) ) {
                            // In case you wanted override your override, hah.
                            $extension['path'] = apply_filters( 'redux/extension/' . $ReduxFramework->args['opt_name'] . '/' . $name, $extension['path'] );
                            if ( file_exists( $extension['path'] ) ) {
                                require_once( $extension['path'] );
                            }
                        }
                        if ( ! isset( $ReduxFramework->extensions[ $name ] ) ) {
                            if ( class_exists( $extension['class'] ) ) {
                                $ReduxFramework->extensions[ $name ] = new $extension['class']( $ReduxFramework );
                            } else {
                                echo '<div id="message" class="error"><p>No class named <strong>' . $extension['class'] . '</strong> exists. Please verify your extension path.</p></div>';
                            }

                        }
                    }
                }
            }

            public static function loadRedux( $opt_name = "" ) {
                $check = ReduxFrameworkInstances::get_instance( $opt_name );
                if ( isset( $check->apiHasRun ) ) {
                    return;
                }

                $args     = self::constructArgs( $opt_name );
                $sections = self::constructSections( $opt_name );
                if ( ! class_exists( 'ReduxFramework' ) ) {
                    echo '<div id="message" class="error"><p>Redux Framework is <strong>not installed</strong>. Please install it.</p></div>';

                    return;
                }
                if ( isset( self::$uses_extensions[ $opt_name ] ) && ! empty( self::$uses_extensions[ $opt_name ] ) ) {
                    add_action( "redux/extensions/{$opt_name}/before", array( 'Redux', 'loadExtensions' ), 0 );
                }

                $redux            = new ReduxFramework( $sections, $args );
                $redux->apiHasRun = 1;
            }

            public static function createRedux() {
                foreach ( self::$sections as $opt_name => $theSections ) {
                    if ( ! self::$init[ $opt_name ] ) {
                        self::loadRedux( $opt_name );
                    }
                }
            }

            public static function constructArgs( $opt_name ) {
                $args             = self::$args[ $opt_name ];
                $args['opt_name'] = $opt_name;
                if ( ! isset( $args['menu_title'] ) ) {
                    $args['menu_title'] = ucfirst( $opt_name ) . ' Options';
                }
                if ( ! isset( $args['page_title'] ) ) {
                    $args['page_title'] = ucfirst( $opt_name ) . ' Options';
                }
                if ( ! isset( $args['page_slug'] ) ) {
                    $args['page_slug'] = $opt_name . '_options';
                }

                return $args;
            }

            public static function constructSections( $opt_name ) {
                $sections = array();
                foreach ( self::$sections[ $opt_name ] as $section_id => $section ) {
                    $section['fields'] = self::constructFields( $opt_name, $section_id );
                    $p                 = $section['priority'];
                    while ( isset( $sections[ $p ] ) ) {
                        echo $p ++;
                    }
                    $sections[ $p ] = $section;
                }
                ksort( $sections );

                return $sections;
            }

            public static function constructFields( $opt_name = "", $section_id = "" ) {
                $fields = array();
                if ( ! empty( self::$fields[ $opt_name ] ) ) {
                    foreach ( self::$fields[ $opt_name ] as $key => $field ) {
                        if ( $field['section_id'] == $section_id ) {
                            $p = $field['priority'];
                            while ( isset( $fields[ $p ] ) ) {
                                echo $p ++;
                            }
                            $fields[ $p ] = $field;
                        }
                    }
                }
                ksort( $fields );

                return $fields;
            }

            public static function getSection( $opt_name = '', $id = '' ) {
                self::check_opt_name( $opt_name );
                if ( ! empty( $opt_name ) && ! empty( $id ) ) {
                    if ( ! isset( self::$sections[ $opt_name ][ $id ] ) ) {
                        $id = strtolower( sanitize_html_class( $id ) );
                    }

                    return isset( self::$sections[ $opt_name ][ $id ] ) ? self::$sections[ $opt_name ][ $id ] : false;
                }

                return false;
            }

            public static function setSection( $opt_name = '', $section = array() ) {
                self::check_opt_name( $opt_name );
                if ( ! isset( $section['id'] ) ) {
                    $section['id'] = strtolower( sanitize_html_class( $section['title'] ) );
                    if ( isset( self::$sections[ $opt_name ][ $section['id'] ] ) ) {
                        $orig = $section['id'];
                        $i    = 0;
                        while ( isset( self::$sections[ $opt_name ][ $section['id'] ] ) ) {
                            $section['id'] = $orig . '_' . $i;
                        }
                    }
                }

                if ( ! empty( $opt_name ) && is_array( $section ) && ! empty( $section ) ) {
                    if ( ! isset( $section['id'] ) && ! isset( $section['title'] ) ) {
                        self::$errors[ $opt_name ]['section']['missing_title'] = "Unable to create a section due to missing id and title.";

                        return;
                    }
                    if ( ! isset( $section['priority'] ) ) {
                        $section['priority'] = self::getPriority( $opt_name, 'sections' );
                    }
                    if ( isset( $section['fields'] ) ) {
                        if ( ! empty( $section['fields'] ) && is_array( $section['fields'] ) ) {
                            self::processFieldsArray( $opt_name, $section['id'], $section['fields'] );
                        }
                        unset( $section['fields'] );
                    }
                    self::$sections[ $opt_name ][ $section['id'] ] = $section;
                } else {
                    self::$errors[ $opt_name ]['section']['empty'] = "Unable to create a section due an empty section array or the section variable passed was not an array.";

                    return;
                }
            }

            public static function processFieldsArray( $opt_name = "", $section_id = "", $fields = array() ) {
                if ( ! empty( $opt_name ) && ! empty( $section_id ) && is_array( $fields ) && ! empty( $fields ) ) {
                    foreach ( $fields as $field ) {
                        $field['section_id'] = $section_id;
                        self::setField( $opt_name, $field );
                    }
                }
            }

            public static function getField( $opt_name = '', $id = '' ) {
                self::check_opt_name( $opt_name );
                if ( ! empty( $opt_name ) && ! empty( $id ) ) {
                    return isset( self::$fields[ $opt_name ][ $id ] ) ? self::$fields[ $opt_name ][ $id ] : false;
                }

                return false;
            }

            public static function setField( $opt_name = '', $field = array() ) {
                self::check_opt_name( $opt_name );

                if ( ! empty( $opt_name ) && is_array( $field ) && ! empty( $field ) ) {

                    if ( ! isset( $field['priority'] ) ) {
                        $field['priority'] = self::getPriority( $opt_name, 'fields' );
                    }
                    self::$fields[ $opt_name ][ $field['id'] ] = $field;
                }
            }

            public static function setHelpTab( $opt_name = "", $tab = array() ) {
                self::check_opt_name( $opt_name );
                if ( ! empty( $opt_name ) && ! empty( $tab ) ) {
                    if ( ! isset( self::$args[ $opt_name ]['help_tabs'] ) ) {
                        self::$args[ $opt_name ]['help_tabs'] = array();
                    }
                    if ( isset( $tab['id'] ) ) {
                        self::$args[ $opt_name ]['help_tabs'][] = $tab;
                    } else if ( is_array( end( $tab ) ) ) {
                        foreach ( $tab as $tab_item ) {
                            self::$args[ $opt_name ]['help_tabs'][] = $tab_item;
                        }
                    }
                }
            }

            public static function setHelpSidebar( $opt_name = "", $content = "" ) {
                self::check_opt_name( $opt_name );
                if ( ! empty( $opt_name ) && ! empty( $content ) ) {
                    self::$args[ $opt_name ]['help_sidebar'] = $content;
                }
            }

            public static function setArgs( $opt_name = "", $args = array() ) {
                self::check_opt_name( $opt_name );
                if ( ! empty( $opt_name ) && ! empty( $args ) && is_array( $args ) ) {
                    self::$args[ $opt_name ] = wp_parse_args( $args, self::$args[ $opt_name ] );
                }
            }

            public static function getArgs( $opt_name = "" ) {
                self::check_opt_name( $opt_name );
                if ( ! empty( $opt_name ) && ! empty( self::$args[ $opt_name ] ) ) {
                    return self::$args[ $opt_name ];
                }
            }

            public static function getArg( $opt_name = "", $key = "" ) {
                self::check_opt_name( $opt_name );
                if ( ! empty( $opt_name ) && ! empty( $key ) && ! empty( self::$args[ $opt_name ] ) ) {
                    return self::$args[ $opt_name ][ $key ];
                } else {
                    return;
                }
            }

            public static function getPriority( $opt_name, $type ) {
                $priority = self::$priority[ $opt_name ][ $type ];
                self::$priority[ $opt_name ][ $type ] += 1;

                return $priority;
            }

            public static function check_opt_name( $opt_name = "" ) {
                if ( empty( $opt_name ) || is_array( $opt_name ) ) {
                    return;
                }
                if ( ! isset( self::$args[ $opt_name ] ) ) {
                    self::$args[ $opt_name ]             = array();
                    self::$priority[ $opt_name ]['args'] = 1;
                }
                if ( ! isset( self::$sections[ $opt_name ] ) ) {
                    self::$sections[ $opt_name ]             = array();
                    self::$priority[ $opt_name ]['sections'] = 1;
                }
                if ( ! isset( self::$fields[ $opt_name ] ) ) {
                    self::$fields[ $opt_name ]             = array();
                    self::$priority[ $opt_name ]['fields'] = 1;
                }
                if ( ! isset( self::$help[ $opt_name ] ) ) {
                    self::$help[ $opt_name ]             = array();
                    self::$priority[ $opt_name ]['help'] = 1;
                }
                if ( ! isset( self::$errors[ $opt_name ] ) ) {
                    self::$errors[ $opt_name ] = array();
                }
                if ( ! isset( self::$init[ $opt_name ] ) ) {
                    self::$init[ $opt_name ] = false;
                }
            }

            /**
             * Retrieve metadata from a file. Based on WP Core's get_file_data function
             *
             * @since 2.1.1
             *
             * @param string $file Path to the file
             *
             * @return string
             */
            public static function getFileVersion( $file, $size = 8192 ) {
                // We don't need to write to the file, so just open for reading.
                $fp = fopen( $file, 'r' );

                // Pull only the first 8kiB of the file in.
                $file_data = fread( $fp, $size );

                // PHP will close file handle, but we are good citizens.
                fclose( $fp );

                // Make sure we catch CR-only line endings.
                $file_data = str_replace( "\r", "\n", $file_data );
                $version   = '';

                if ( preg_match( '/^[ \t\/*#@]*' . preg_quote( '@version', '/' ) . '(.*)$/mi', $file_data, $match ) && $match[1] ) {
                    $version = _cleanup_header_comment( $match[1] );
                }

                return $version;
            }

            public static function checkExtensionClassFile( $opt_name, $name = "", $class_file = "" ) {
                if ( file_exists( $class_file ) ) {
                    self::$uses_extensions[ $opt_name ] = isset( self::$uses_extensions[ $opt_name ] ) ? self::$uses_extensions[ $opt_name ] : array();
                    if ( ! in_array( $name, self::$uses_extensions[ $opt_name ] ) ) {
                        self::$uses_extensions[ $opt_name ][] = $name;
                    }

                    self::$extensions[ $name ]             = isset( self::$extensions[ $name ] ) ? self::$extensions[ $name ] : array();
                    $version                               = self::getFileVersion( $class_file );
                    self::$extensions[ $name ][ $version ] = isset( self::$extensions[ $name ][ $version ] ) ? self::$extensions[ $name ][ $version ] : $class_file;
                }
            }

            public static function setExtensions( $opt_name, $path ) {
                if ( is_dir( $path ) ) {
                    $path   = trailingslashit( $path );
                    $folder = str_replace( '.php', '', basename( $path ) );
                    if ( file_exists( $path . 'extension_' . $folder . '.php' ) ) {
                        self::checkExtensionClassFile( $opt_name, $folder, $path . 'extension_' . $folder . '.php' );
                    } else {
                        $folders = scandir( $path, 1 );
                        foreach ( $folders as $folder ) {
                            if ( $folder === '.' or $folder === '..' ) {
                                continue;
                            }
                            if ( file_exists( $path . $folder . '/extension_' . $folder . '.php' ) ) {
                                self::checkExtensionClassFile( $opt_name, $folder, $path . $folder . '/extension_' . $folder . '.php' );
                            } else if ( is_dir( $path . $folder ) ) {
                                self::setExtensions( $opt_name, $path . $folder );
                                continue;
                            }
                        }
                    }
                } else if ( file_exists( $path ) ) {
                    $name = explode( 'extension_', basename( $path ) );
                    if ( isset( $name[1] ) && ! empty( $name[1] ) ) {
                        $name = str_replace( '.php', '', $name[1] );
                        self::checkExtensionClassFile( $opt_name, $name, $path );
                    }
                }
            }

            public static function getExtensions( $key = "", $opt_name = "" ) {
                if ( empty( $opt_name ) ) {
                    if ( empty( $key ) ) {
                        return self::$extension_paths[ $key ];
                    } else {
                        if ( isset( self::$extension_paths[ $key ] ) ) {
                            return self::$extension_paths[ $key ];
                        }
                    }
                } else {
                    if ( empty( self::$uses_extensions[ $opt_name ] ) ) {
                        return false;
                    }
                    $instanceExtensions = array();
                    foreach ( self::$uses_extensions[ $opt_name ] as $extension ) {
                        $class_file                       = end( self::$extensions[ $extension ] );
                        $name                             = str_replace( '.php', '', basename( $extension ) );
                        $extension_class                  = 'ReduxFramework_Extension_' . $name;
                        $instanceExtensions[ $extension ] = array(
                            'path'  => $class_file,
                            'class' => $extension_class
                        );
                    }

                    return $instanceExtensions;
                }

                return false;
            }
        }

        Redux::load();
    }
