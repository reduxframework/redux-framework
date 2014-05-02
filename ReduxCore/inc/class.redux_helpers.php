<?php

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
    exit;
}

// Don't duplicate me!
if( !class_exists( 'Redux_Helpers' ) ) {

    /**
     * Redux Helpers Class
     *
     * Class of useful functions that can/should be shared among all Redux files.
     *
     * @since       1.0.0
     */
    class Redux_Helpers {

        public static function curlRead($filename){
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $filename);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            
            $data = curl_exec($ch);
            
            curl_close($ch);
            
            if (empty($data)) {
                $data = false;
            }
            
            return $data;
        }
        
        public static function tabFromField($parent, $field) {
          foreach( $parent->sections as $k => $section ) {
                if ( !isset( $section['title'] ) ){
                    continue;
                }

                if ( isset( $section['fields'] ) && !empty( $section['fields'] ) ) {
                    if ( Redux_Helpers::recursive_array_search($field, $section['fields'])) {
                        return $k;
                        continue;
                    }
                }
            }
        }

        public static function isFieldInUse($parent, $field) {
          foreach( $parent->sections as $k => $section ) {
                if ( !isset( $section['title'] ) ){
                    continue;
                }

                if ( isset( $section['fields'] ) && !empty( $section['fields'] ) ) {
                    if ( Redux_Helpers::recursive_array_search($field, $section['fields'])) {
                        return true;
                        continue;
                    }
                }
            }
        }

        public static function isParentTheme($file) {
            if (strpos(self::cleanFilePath($file), self::cleanFilePath(get_template_directory())) !== false) {
                return true;
            }
            return false;
        }

        public static function isChildTheme($file) {
            if (strpos(self::cleanFilePath($file), self::cleanFilePath(get_stylesheet_directory())) !== false) {
                return true;
            }
            return false;
        }

        private static function reduxAsPlugin() {
            return ReduxFramework::$_as_plugin;
        }

        public static function isTheme ($file) {
            if (true == self::isChildTheme($file) || true == self::isParentTheme($file)) {
                return true;
            }
            return false;
        }

        public static function array_in_array($needle, $haystack) {
            //Make sure $needle is an array for foreach
            if (!is_array($needle)) {
                $needle = array($needle);
            }
            //For each value in $needle, return TRUE if in $haystack
            foreach ($needle as $pin)
            //echo 'needle' . $pin;
                if (in_array($pin, $haystack)) {
                    return true;
                }
            //Return FALSE if none of the values from $needle are found in $haystack
            return false;
        }

        public static function recursive_array_search($needle, $haystack) {
            foreach($haystack as $key => $value) {
                if($needle === $value || (is_array($value) && self::recursive_array_search($needle, $value) !== false)) {
                    return true;
                }
            }
            return false;
        }

        /**
         * Take a path and return it clean
         * @param string $path
         * @since    3.1.7
         */
        public static function cleanFilePath( $path ) {
            $path = str_replace('','', str_replace( array( "\\", "\\\\" ), '/', $path ) );
            if ($path[ strlen($path)-1 ] === '/') {
                $path = rtrim($path, '/');
            }
            return $path;
        }

        /**
         * Field Render Function.
         *
         * Takes the color hex value and converts to a rgba.
         *
         * @since ReduxFramework 3.0.4
         */
        public static function hex2rgba($hex, $alpha = '') {
            $hex = str_replace("#", "", $hex);
            if(strlen($hex) == 3) {
                $r = hexdec(substr($hex,0,1).substr($hex,0,1));
                $g = hexdec(substr($hex,1,1).substr($hex,1,1));
                $b = hexdec(substr($hex,2,1).substr($hex,2,1));
            } else {
                $r = hexdec(substr($hex,0,2));
                $g = hexdec(substr($hex,2,2));
                $b = hexdec(substr($hex,4,2));
            }
            $rgb = $r.','.$g.','.$b;

            if ('' == $alpha) {
                return $rgb;
            } else {
                $alpha = floatval($alpha);
                return 'rgba(' . $rgb . ',' . $alpha . ')';
            }
        }
    }
}
