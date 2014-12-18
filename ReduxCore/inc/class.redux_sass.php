<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if (!class_exists('reduxSassCompiler')) {
    class reduxSassCompiler {
        public static $path    = array();
        public static $import  = array();

        public static function add_path ($path) {
            if (!in_array($path, self::$path)) {
                array_push(self::$path, $path);
            }
        }

        public static function add_import($import) {
            if (!in_array($import, self::$import)) {
                array_push (self::$import, $import);
            }
        }

        public static function compile_sass($parent) {
            
            if (!empty(self::$path)) {
                
                require( "scssphp/scss.inc.php" );

                $scss = new scssc();

                $scss->setImportPaths( self::$path );

                if (!$parent->args['dev_mode']) {
                    $scss->setFormatter ( "scss_formatter_compressed" );
                }

                $new_css = '';
                foreach (self::$import as $import) {
                    $new_css .= $scss->compile( $import );
                }

                if ($new_css != '') {
                    if ($parent->args['sass']['page_output']) {
                        echo '<style type="text/css" id="redux-' . $parent->args['opt_name'] . '">' . $new_css . '</style>';
                    } else {
                        Redux_Functions::initWpFilesystem();

                        global $wp_filesystem;

                        $css_file   = Redux_Helpers::cleanFilePath( ReduxFramework::$_upload_dir . $parent->args['opt_name'] .  '-redux.css');
                        $ret_val    = $wp_filesystem->put_contents($css_file, $new_css, FS_CHMOD_FILE);
                    }
                }
            }
        }
    }
}

if (!function_exists ( 'redux_enqueue_style')) {
    /**
     * Enqueues style for SASS comnpile or WP enqueue, depending on 'use_sass' arg.
     *
     * @since       3.3.9
     * @access      public
     * @param       string  $handle     Name of the stylesheet.
     * @param       string  $css_src    Path to the stylesheet from the root directory of WordPress. Example: '/css/mystyle.css'.
     * @param       string  $scss_dir   Directory path to SCSS file.
     * @param       array   $deps       An array of registered style handles this stylesheet depends on. Default empty array.
     * @param       string  $ver        String specifying the stylesheet version number, if it has one. This parameter is used to ensure that the correct version is sent to the client regardless of caching, and so should be included if a version number is available and makes sense for the stylesheet.
     * @param       string  $media      Optional. The media for which this stylesheet has been defined. Default 'all'. Accepts 'all', 'aural', 'braille', 'handheld', 'projection', 'print', 'screen', 'tty', or 'tv'.
     * @return      void
     */
    function redux_enqueue_style ($parent, $handle, $css_src, $scss_dir, $deps = array(), $ver = '', $media = false){
        if ($parent->args['sass']['enabled']) {
            $path_parts = pathinfo($css_src);

            $filename   = $path_parts['filename'];

            reduxSassCompiler::add_path($scss_dir);
            reduxSassCompiler::add_import('@import "' . $filename . '.scss"');
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