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
 * @package     Redux Framework
 * @subpackage  Redux Color Schemes
 * @author      Kevin Provance (kprovance)
 * @version     1.0.1
 */

if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'ReduxColorSchemeFunctions' ) ) {
    class ReduxColorSchemeFunctions {

        // public variables
        static public $_parent;
        static public $_field_id;
        static public $_field_class;
        static public $upload_dir;
        static public $upload_url;
        static public $scheme_path;

        /**
         * wpFilesystemInit Function.
         *
         * Init WP filesystem, just in case.
         *
         * @since       1.0.0
         * @access      static public
         * @return      void
         */         
        static public function init(){
            Redux_Functions::initWpFilesystem();

            //$upload = ReduxFramework::$_upload_dir;
            // WP upload dir
            //$upload             = wp_upload_dir();

            // Make sanitized upload dir DIR
            self::$upload_dir   = ReduxFramework::$_upload_dir . 'color-schemes/';

            // Make sanitized upload dir URL
            self::$upload_url   = ReduxFramework::$_upload_url . 'color-schemes/';

            self::$scheme_path  = self::$upload_dir . '/' . self::$_field_id . '.json';
        }

        /**
         * getSchemeSelectHTML Function.
         *
         * Output scheme dropdown selector.
         *
         * @since       1.0.0
         * @access      static public
         * @param       string $selected Selected scheme name
         * @return      string HTML of dropdown selector.
         */         
        static public function getSchemeSelectHTML($selected) {
            $html  = '<select name="' . self::$_parent->args['opt_name'] . '[redux-scheme-select]" id="redux-scheme-select-' . self::$_field_id . '" class="redux-scheme-select">';
            $html .= ReduxColorSchemeFunctions::getSchemeListHTML($selected);
            $html .= '</select>';

            return $html;
        }

        /**
         * setCurrentSchemeID Function.
         *
         * Set current scheme ID, if one isn't specified.
         *
         * @since       1.0.0
         * @access      static public
         * @param       string $id Scheme name to set.
         * @return      void
         */          
        static public function setCurrentSchemeID($id){

            // Get opt name, for database
            $opt_name = self::$_parent->args['opt_name'];

            // Get all options from database
            $redux_options = get_option($opt_name);

            // Append ID to variable that holds the current scheme ID data
            $redux_options['redux-scheme-select'] = $id;

            // Save the modified settings
            update_option($opt_name,$redux_options);
        }

        /**
         * getCurrentSchemeID Function.
         *
         * Gets the current schem ID from the database.
         *
         * @since       1.0.0
         * @access      static public
         * @param       string $id Scheme name to set.
         * @return      string Current scheme ID.
         */           
        static public function getCurrentSchemeID() {

            // Retrieve the opt_name, needed for databasae
            $opt_name = self::$_parent->args['opt_name'];

            // Get the entire options array
            $redux_options = get_option($opt_name);

            // If the current scheme key exists...
            if (isset($redux_options['redux-scheme-select'])) {

                // yank it out and return it.
                return $redux_options['redux-scheme-select'];
            } else {

                // Otherwise, return 0/false.
                return 'Default';
            }
        }

        /**
         * getSchemeListHTML Function.
         *
         * Get the list of schemes for the selector.
         *
         * @since       1.0.0
         * @access      static private
         * @param       string $sel Scheme name to select.
         * @return      string HTML option values.
         */         
        static private function getSchemeListHTML($sel = '') {
            // no errors, please.
            $html = '';

            // Retrieves the list of saved schemes into an array variable
            $dropdown_values = self::getSchemeNames();

            // If the dropdown array has items...
            if (!empty($dropdown_values)) {

                // Sort them alphbetically.
                asort($dropdown_values);
            }

            // trim the selected item
            trim($sel);

            // If it's empty
            if ('' == $sel) {

                // Make the current scheme id the selected value
                $selected = self::getCurrentSchemeID();
            } else {

                // Otherwise, set it to the value passed to this function.
                $selected = $sel;
            }

            // Enum through the dropdown array and append the necessary HTML for the selector.
            foreach($dropdown_values as $k ) {
                $html .= '<option value="' . $k . '"' . selected($k, $selected, false ) . '>' . $k . '</option>';
            }

            // Send it all packin'.
            return $html;
        }

        /**
         * getCurrentColorSchemeHTML Function.
         *
         * Returns colour pickers HTML table.
         *
         * @since       1.0.0
         * @access      static public
         * @param       string $scheme_id Scheme name of HTML to return.
         * @return      string HTML of colour picker table.
         */          
        static public function getCurrentColorSchemeHTML($scheme_id = false){

            // If scheme_id is false
            if (!$scheme_id) {

                // Attempt to get the current scheme
                $scheme_id = ReduxColorSchemeFunctions::getCurrentSchemeID();

                // dummy check, because this shit happens!
                $arrSchemes = self::getSchemeNames();
                if (!in_array($scheme_id, $arrSchemes)) {
                    $scheme_id = 'Default';
                    self::setCurrentSchemeID('Default');
                }
            }

            // Set oft used variables.
            $opt_name       = self::$_parent->args['opt_name'];
            $field_id       = self::$_field_id;
            $field_class    = self::$_field_class;

            // open the list
            $html = '<ul class="redux-scheme-layout">';

            // get the default options
            $defOpts = self::$_parent->options_defaults[$field_id];

            // Create array of element ids from default options
            if (!empty($defOpts)) {
                $idArr = array();

                foreach($defOpts as $k => $v){
                    $idArr[] = $v['id'];
                }
            }

            $scheme = self::getSchemeData($scheme_id);

            // If it's not empty then...
            if (!empty($scheme)) {

                // Enum through each element/id
                foreach($scheme as $k => $v) {

                    // Ignore the color_scheme_name entry
                    if ('color_scheme_name' != $k) {

                        // Compare stored scheme array to default values
                        // This way we can weed out unused or old data
                        // from an imported scheme file, should the
                        // theme author made changes.
                        if(in_array($v['id'], $idArr)){

                            // If no title, use ID.
                            $v['title']     = isset($v['title']) ? $v['title'] : $v['id'];

                            // If no alpha, use 1 (solid)
                            $v['alpha']     = isset($v['alpha']) ? $v['alpha'] : 1;

                            // If no mode, default to 'color'
                            $v['mode']      = isset($v['mode']) ? $v['mode'] : 'color';

                            // Fuck forbid no colour, set to white
                            $v['color']     = isset($v['color']) ? $v['color'] : '';

                            // Begin the layout
                            $html .= '<li class="redux-scheme-layout">';
                            $html .= '<div class="redux-scheme-layout-container" data-id="' . $field_id . '-' . $v['id'] . '">';

                            
                            if ('' == $v['color']) {
                                $color = '';
                            } else {
                                $color = 'rgba(' . Redux_Helpers::hex2rgba($v['color']) . ',' . $v['alpha'] . ')';
                            }
                            
                            // colour picker dropdown
                            $html .= '<input
                                        name="' . $opt_name . '[' . $field_id . ']' . '[' . $v['id'] . '][color]"
                                        id="' . $field_id . '-' . $v['id'] . '-color"
                                        class="' . $field_class . '"
                                        type="text"
                                        value="' . $v['color'] . '"
                                        data-color="' . $color . '"
                                        data-title="' . $v['title'] . '"
                                        data-id="' . $v['id'] . '"
                                        data-current-color="' . $v['color'] . '"
                                        data-block-id="' . $field_id . '-' . $v['id'] . '"
                                      />';

                            // Hidden input for current picker name
                            $html .= '<input
                                        type="hidden"
                                        class="redux-hidden-title"
                                        name="' . $opt_name . '[' . $field_id . ']' . '[' . $v['id'] . '][title]"
                                        id="' . $field_id . '-' . $v['id'] . '-title"
                                        value="' . $v['title'] . '"
                                      />';

                            // Hidden input for current picker CSS selector
                            $html .= '<input
                                        type="hidden"
                                        class="redux-hidden-selector"
                                        name="' . $opt_name . '[' . $field_id . ']' . '[' . $v['id'] . '][selector]"
                                        id="' . $field_id . '-' . $v['id'] . '-selector"
                                        value="' . $v['selector'] . '"
                                      />';

                            // Hidden input for current CSS mode
                            $html .= '<input
                                        type="hidden"
                                        class="redux-hidden-mode"
                                        name="' . $opt_name . '[' . $field_id . ']' . '[' . $v['id'] . '][mode]"
                                        id="' . $field_id . '-' . $v['id'] . '-mode"
                                        value="' . $v['mode'] . '"
                                      />';

                            // Hidden input for current CSS important flag
                            $html .= '<input
                                        type="hidden"
                                        class="redux-hidden-important"
                                        name="' . $opt_name . '[' . $field_id . ']' . '[' . $v['id'] . '][important]"
                                        id="' . $field_id . '-' . $v['id'] . '-important"
                                        value="' . $v['important'] . '"
                                      />';

                            // Hidden input for current color
                            $html .= '<input
                                        type="hidden"
                                        class="redux-hidden-color"
                                        data-id="' . $field_id . '-' . $v['id'] . '-color"
                                        id="' . $field_id . '-' . $v['id'] . '-color"
                                        value="' . $v['color'] . '"
                                      />';

                            // Hidden input for alpha channel
                            $html .= '<input
                                        type="hidden"
                                        class="redux-hidden-alpha"
                                        data-id="' . $field_id . '-' . $v['id'] . '-alpha"
                                        name="' . $opt_name . '[' . $field_id . ']' . '[' . $v['id'] . ']' . '[alpha]' .  '"
                                        id="' . $field_id . '-' . $v['id'] . '-alpha"
                                        value="' . $v['alpha'] . '"
                                      />';

                            // closing html tags
                            $html .= '</div>';
                            $html .= '<label class="redux-layout-label">' . $v['title'] . '</label>';
                            $html .= '</li>';
                        }
                    }
                }
            }

            // Close list
            $html .= "</ul>";

            // html var not empty, return it.
            if (!empty($html)) {
                return $html;
            }
        }

        /**
         * readSchemeFile Function.
         *
         * Returns scheme file contents.
         *
         * @since       1.0.0
         * @access      static public
         * @param       string $file Optional file name
         * @param       bool $decode Flag to return JSON decoded data.
         * @return      array Array of scheme data.
         */          
        static public function readSchemeFile($file = '', $decode = true) {
            global $wp_filesystem;

            // If the file passed is empty, use the scheme path, otherwise use
            // the passed file.
            $file = ('' === $file) ? self::$scheme_path : self::$upload_dir . $file;

            if (file_exists($file)){
                // Get the contents of the file and stuff it in a variable
                $data = $wp_filesystem->get_contents($file);

                //  Error or null, set the result to false
                if (false == $data || null == $data){
                    $arrData = false;

                // Otherwise decode the json object and return it.
                } else {
                    if (true == $decode) {
                        $arr = json_decode($data, true);
                        $arrData = $arr;
                    } else {
                        $arrData = $data;
                    }
                }
            } else {
                $arrData = false;
            }

            return $arrData;
        }

        /**
         * writeSchemeFile Function.
         *
         * Sets scheme file contents.
         *
         * @since       1.0.0
         * @access      static public
         * @param       array $arrData PHP array of data to encode.
         * @param       string $file Optional file name to override default.
         * @return      bool Result of write function.
         */          
        static public function writeSchemeFile($arrData, $file = '') {
            global $wp_filesystem;

            $file = ('' === $file) ? self::$scheme_path : self::$upload_dir . $file;

            // Encode the array data
            $data = json_encode($arrData);

            // Write to its file on the server, return the return value
            // True on success, false on error.
            return $wp_filesystem->put_contents($file, $data, FS_CHMOD_FILE);
        }

        /**
         * getSchemeData Function.
         *
         * Gets individual scheme data from scheme JSON file.
         *
         * @since       1.0.0
         * @access      static public
         * @param       string $scheme_name Name of scheme.
         * @return      array PHP array of scheme data.
         */          
        static public function getSchemeData($scheme_name) {
            $data = self::readSchemeFile();

            if (false == $data) {
                return false;
            }

            $data = $data[$scheme_name];

            return $data;
        }

        /**
         * setSchemeData Function.
         *
         * Sets individual scheme data to scheme JSON file.
         *
         * @since       1.0.0
         * @access      static public
         * @param       string $name Name of scheme to save.
         * @param       array $array Scheme data to encode
         * @return      bool Result of file write.
         */                  
        static public function setSchemeData($name, $array){

            // Create blank array
            $new_scheme = array();

            // If name is present
            if ($name) {

                // then add the name at the new array's key
                $new_scheme['color_scheme_name'] = $name;

                // Enum through values and assign them to new array
                foreach($array as $item => $val){
                    $new_scheme[$val['id']] = $val;
                }

                // read the contents of the current scheme file
                $schemes = self::readSchemeFile();

                // If returned false (not there) then create a new array
                if (false == $schemes) {
                    $schemes = array();
                }

                // Add new scheme to array that will be saved.
                $schemes[$name] = $new_scheme;

                // Write the data to the JSON file.
                return self::writeSchemeFile($schemes);
            }

            // !success
            return false;
        }

        /**
         * getSchemeNames Function.
         *
         * Enumerate the scheme names from the JSON store file.
         *
         * @since       1.0.0
         * @access      static public
         * @return      array Array of stored scheme names..
         */         
        static public function getSchemeNames() {

            // Read the JSON file, which returns a PHP array
            $schemes = self::readSchemeFile();

            // Create a new array
            $output = array();

            if (false != $schemes) {

                // If the schemes array IS an array (versus false), then...
                if (is_array($schemes)) {

                    // Enum them
                    foreach($schemes as $scheme) {

                        // If the color_scheme_name key is set...
                        if (isset($scheme['color_scheme_name'])) {

                            // Push it onto the array stack.
                            $output[] = $scheme['color_scheme_name'];
                        }
                    }
                }
            }

            // Kick the full array out the door.
            return $output;
        }
    }
}
