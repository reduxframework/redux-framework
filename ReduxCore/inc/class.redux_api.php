<?php

// Exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

// Don't duplicate me!
    if ( ! class_exists( 'ReduxAPI' ) ) {

        /**
         * Redux API Class
         * Simple API for Redux Framework
         *
         * @since       1.0.0
         */
        class ReduxAPI {

            public static $fields = array();
            public static $sections = array();
            public static $help = array();
            public static $args = array();
            public static $priority = array();
            public static $errors = array();
            public static $init = array();

            public static function load() {
                add_action( 'init', array( 'ReduxAPI', 'createRedux' ) );
            }

            public static function init( $opt_name = "" ) {
                if ( ! empty( $opt_name ) ) {
                    self::loadRedux( $opt_name );
                }
            }

            public static function loadRedux( $opt_name = "" ) {
                $args     = self::constructArgs( $opt_name );
                $sections = self::constructSections( $opt_name );
                new ReduxFramework( $sections, $args );
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

            public static function setHelpTab( $opt_name = "", $content = "" ) {
                self::check_opt_name( $opt_name );
                if ( ! empty( $opt_name ) && ! empty( $content ) ) {
                    if ( ! isset( self::$args[ $opt_name ]['help_tabs'] ) ) {
                        self::$args[ $opt_name ]['help_tabs'] = array();
                    }
                    if ( isset( $content['id'] ) ) {
                        self::$args[ $opt_name ]['help_tabs'][] = $content;
                    } else if ( is_array( end( $content ) ) ) {
                        foreach ( $content as $tab ) {
                            self::$args[ $opt_name ]['help_tabs'][] = $tab;
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

            public static function getPriority( $opt_name, $type ) {
                $priority = self::$priority[ $opt_name ][ $type ];
                self::$priority[ $opt_name ][ $type ] += 10;

                return $priority;
            }

            public static function check_opt_name( $opt_name = "" ) {
                if ( empty( $opt_name ) || is_array( $opt_name ) ) {
                    return;
                }
                if ( ! isset( self::$args[ $opt_name ] ) ) {
                    self::$args[ $opt_name ]             = array();
                    self::$priority[ $opt_name ]['args'] = 10;
                }
                if ( ! isset( self::$sections[ $opt_name ] ) ) {
                    self::$sections[ $opt_name ]             = array();
                    self::$priority[ $opt_name ]['sections'] = 10;
                }
                if ( ! isset( self::$fields[ $opt_name ] ) ) {
                    self::$fields[ $opt_name ]             = array();
                    self::$priority[ $opt_name ]['fields'] = 10;
                }
                if ( ! isset( self::$help[ $opt_name ] ) ) {
                    self::$help[ $opt_name ]             = array();
                    self::$priority[ $opt_name ]['help'] = 10;
                }
                if ( ! isset( self::$errors[ $opt_name ] ) ) {
                    self::$errors[ $opt_name ] = array();
                }
                if ( ! isset( self::$init[ $opt_name ] ) ) {
                    self::$init[ $opt_name ] = false;
                }
            }
        }

        ReduxAPI::load();
    }