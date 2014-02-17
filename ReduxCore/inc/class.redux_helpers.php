<?php

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework' ) ) {

    /**
     * Redux Helpers Class
     *
     * Class of useful functions that can/should be shared among all Redux files.
     *
     * @since       1.0.0
     */
    class Redux_Helpers {

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
         * @author @mekshq, http://mekshq.com/how-to-convert-hexadecimal-color-code-to-rgb-or-rgba-using-php/
        */
        public static function hex2rgba($color, $opacity = false) {

            $default = 'rgb(0,0,0)';

            //Return default if no color provided
            if(empty($color))
                  return $default; 

            //Sanitize $color if "#" is provided 
                if ($color[0] == '#' ) {
                    $color = substr( $color, 1 );
                }

                //Check if color has 6 or 3 characters and get values
                if (strlen($color) == 6) {
                        $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
                } elseif ( strlen( $color ) == 3 ) {
                        $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
                } else {
                        return $default;
                }

                //Convert hexadec to rgb
                $rgb =  array_map('hexdec', $hex);

                //Check if opacity is set(rgba or rgb)
                if($opacity){
                    if(abs($opacity) > 1)
                        $opacity = 1.0;
                    $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
                } else {
                    $output = 'rgb('.implode(",",$rgb).')';
                }

                //Return rgb(a) color string
                return $output;
        }
    }
}
