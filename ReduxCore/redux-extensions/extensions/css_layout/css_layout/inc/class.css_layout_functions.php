<?php

if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'ReduxCssLayoutFunctions' ) ) {
    class ReduxCssLayoutFunctions {
        public static $_field_id           = '';
        public static $units               = array();
        public static $output_shorthand    = '';
        
        public static function fixResult($value, $default) {
            if ($value != '') {
                $local_unit     = self::getUnit($value);
                $unit           = !empty($local_unit) ? $local_unit : $default;
                $value          = self::stripAlphas($value);
                
                return $value . $unit;
            }            
        }
        
        /*
         *  Check for existance of unit in value
         */
        public static function getUnit($val) {

            // Make the value lowercase
            $val = strtolower($val);

            // Get accepted unit value array
            $unit_arr = self::$units;

            // Enum though units
            foreach($unit_arr as $key => $unit) {

                // Check for existance of unit
                $pos = strpos($val, $unit);

                // If so, return it
                if ($pos > 0) {
                    return $unit;
                }
            }
        }
        
        /*
         *  Strip alpha characters from value
         */
        public static function stripAlphas($s) {

            // Regex is our friend.  THERE ARE FOUR LIGHTS!!
            return preg_replace('/[^\d.-]/', '', $s);
        }        
    }
}