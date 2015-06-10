<?php

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    if ( ! class_exists( 'reduxSassCompiler' ) ) {
        class reduxSassCompiler {
            public static $path = array();
            public static $import = array();
            public static $_do_compile = false;

            private static $matrix_file = '';
            private static $matrix_key = '';

            const SASS_NO_COMPILE = 0;
            const SASS_FILE_COMPILE = 1;
            const SASS_PAGE_OUTPUT = 2;

            private static function is_sass_dir( $dir ) {
                if ( ! is_dir( $dir ) ) {
                    wp_mkdir_p( $dir );

                    if ( is_dir( $dir ) ) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return true;
                }
            }

            public static function get_current_id_matrix( $parent ) {
                if ( $parent->args['sass']['enabled'] && ! $parent->args['sass']['page_output'] ) {
                    $ids = '';

                    foreach ( $parent->options as $id => $opts ) {
                        $ids .= $id . '|';
                    }

                    $ids = rtrim( $ids, '|' );

                    return $ids;
                }
            }

            public static function get_id_matrix() {
                if ( ! file_exists( self::$matrix_file ) ) {
                    $ids = get_option( self::$matrix_key );
                } else {
                    $ids = file_get_contents( self::$matrix_file );
                }

                return $ids;
            }

            public static function set_id_matrix( $ids ) {
                $ret = @file_put_contents( self::$matrix_file, $ids );

                if ( $ret == false ) {
                    return update_option( self::$matrix_key, $ids );
                }
            }

            public static function add_path( $path ) {
                if ( ! in_array( $path, self::$path ) ) {
                    array_push( self::$path, $path );
                }
            }

            public static function add_import( $import ) {
                if ( ! in_array( $import, self::$import ) ) {
                    array_push( self::$import, $import );
                }
            }

            public static function is_scss_newer( $dir, $filename ) {
                $css_time  = filemtime( $dir . '/' . $filename . '.css' );
                $scss_time = filemtime( $dir . '/' . $filename . '.scss' );

                if ( $scss_time > $css_time ) {
                    echo 'css: ' . $css_time . '<br>';
                    echo 'scss: ' . $scss_time . '<br>';

                    return true;
                }

                return false;
            }

            public static function compile_sass( $parent ) {
                if ( ! empty( self::$path ) ) {

                    $do_compile = false;
                    $as_output  = false;

                    if ( ! self::is_sass_dir( ReduxFramework::$_upload_dir . 'sass' ) ) {
                        $as_output = true;
                    }

                    if ( $parent->args['sass']['page_output'] ) {
                        $as_output = true;
                    }

                    $mb = $parent->extensions['metaboxes'];
                    if ( ! empty( $mb->boxes ) ) {
                        $as_output = true;
                    }

                    $opt_name = $parent->args['opt_name'];

                    self::$matrix_file = ReduxFramework::$_upload_dir . 'sass/' . $opt_name . '-id-matrix';
                    self::$matrix_key  = 'redux-sass-' . $opt_name . '-id-matrix';

                    if ( ! $as_output ) {
                        $current_ids = self::get_current_id_matrix( $parent );
                        $saved_ids   = self::get_id_matrix();

                        if ( $saved_ids == '' || empty( $saved_ids ) ) {
                            $ret        = self::set_id_matrix( $current_ids );
                            $do_compile = true;
                        } else {
                            if ( $current_ids != $saved_ids ) {
                                logconsole( 'not the same' );
                                self::set_id_matrix( $current_ids );
                                $do_compile = true;
                            } else {
                                logconsole( 'the same' );
                            }
                        }
                    } else {
                        $do_compile = true;
                    }

                    if ( $do_compile || self::$_do_compile ) {
                        logconsole( 'compiler run' );
                        if ( ! class_exists( 'scssc' ) && ! isset( $GLOBALS['redux_scss_compiler'] ) ) {
                            $GLOBALS['redux_scss_compiler'] = true;
                            require( "scssphp/scss.inc.php" );
                        }

                        $scss = new scssc();

                        $scss->setImportPaths( self::$path );

                        if ( ! $parent->args['dev_mode'] ) {
                            $scss->setFormatter( "scss_formatter_compressed" );
                        }

                        $new_css = '';

                        foreach ( self::$import as $import ) {
                            $new_css .= $scss->compile( $import );
                        }

                        unset ( $scss );

                        if ( $new_css != '' ) {
                            if ( $as_output ) {
                                self::css_to_page( $opt_name, $new_css );

                                return self::SASS_PAGE_OUTPUT;
                            } else {
                                $css_file = Redux_Helpers::cleanFilePath( ReduxFramework::$_upload_dir . $parent->args['opt_name'] . '-redux.css' );

                                $ret = @file_put_contents( $css_file, $new_css );

                                if ( $ret == false ) {
                                    self::css_to_page( $opt_name, $new_css );

                                    return self::SASS_PAGE_OUTPUT;
                                }

                                return self::SASS_FILE_COMPILE;
                            }
                        }
                    } // do_compile
                }

                return self::SASS_NO_COMPILE;
            }

            private static function css_to_page( $opt_name, $css ) {
                echo '<style type="text/css" id="redux-' . esc_attr( $opt_name ) . '">' . wp_kses( $css, array(), array() ) . '</style>';
            }

            public static function compile_single_field( $parent, $scss_path, $filename ) {
                echo 'single field compile: ' . $scss_path . ' ' . $filename;

                if ( ! class_exists( 'scssc' ) && ! isset( $GLOBALS['redux_scss_compiler'] ) ) {
                    $GLOBALS['redux_scss_compiler'] = true;
                    require( "scssphp/scss.inc.php" );
                }

                $scss = new scssc();

                $scss->setImportPaths( $scss_path );

                if ( ! $parent->args['dev_mode'] ) {
                    $scss->setFormatter( "scss_formatter_compressed" );
                }

                $new_css = $scss->compile( '@import "' . $filename . '.scss"' );

                unset ( $scss );

                $ret = @file_put_contents( $scss_path . '/' . $filename . '.css', $new_css );
            }
        }
    }

    if ( ! function_exists( 'redux_enqueue_style' ) ) {
        /**
         * Enqueues style for SASS comnpile or WP enqueue, depending on 'use_sass' arg.
         *
         * @since       3.3.9
         * @access      public
         *
         * @param       string $handle   Name of the stylesheet.
         * @param       string $css_src  Path to the stylesheet from the root directory of WordPress. Example: '/css/mystyle.css'.
         * @param       string $scss_dir Directory path to SCSS file.
         * @param       array  $deps     An array of registered style handles this stylesheet depends on. Default empty array.
         * @param       string $ver      String specifying the stylesheet version number, if it has one. This parameter is used to ensure that the correct version is sent to the client regardless of caching, and so should be included if a version number is available and makes sense for the stylesheet.
         * @param       string $media    Optional. The media for which this stylesheet has been defined. Default 'all'. Accepts 'all', 'aural', 'braille', 'handheld', 'projection', 'print', 'screen', 'tty', or 'tv'.
         *
         * @return      void
         */
        function redux_enqueue_style( $parent, $handle, $css_src, $scss_dir, $deps = array(), $ver = '', $media = false ) {
            if ( $parent->args['sass']['enabled'] ) {
                //if ($parent->args['dev_mode'] || $parent->args['sass']['page_output']) {
                $path_parts = pathinfo( $css_src );

                $filename = $path_parts['filename'];
//echo $filename . '<br>';
                $scss_dir = Redux_Helpers::cleanFilePath( $scss_dir );
                $scss_dir = untrailingslashit( $scss_dir );

                $is_diff = reduxSassCompiler::is_scss_newer( $scss_dir, $filename );

                if ( $is_diff ) {
                    reduxSassCompiler::compile_single_field( $parent, $scss_dir, $filename );
                    reduxSassCompiler::$_do_compile = true;
                }

                reduxSassCompiler::add_path( $scss_dir );
                reduxSassCompiler::add_import( '@import "' . $filename . '.scss"' );
                //}
            } else {
                wp_enqueue_style(
                    $handle,
                    $css_src,
                    $deps,
                    $ver,
                    $media
                );
            }
        }
    }