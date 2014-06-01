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
 * @subpackage  Wordpress
 * @author      Kevin Provance (kprovance)
 * @version     1.0.1
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_extension_color_scheme' ) ) {


    /**
     * Main ReduxFramework color_scheme extension class
     *
     * @since       1.0.0
     */
    class ReduxFramework_extension_color_scheme {

        public static $version = '1.0.1';
        
        // Protected vars
        protected $parent;
        public $extension_url;
        public $extension_dir;
        public static $theInstance;
        public $field_id = '';
        private $class_css = '';

        /**
        * Class Constructor. Defines the args for the extions class
        *
        * @since       1.0.0
        * @access      public
        * @param       array $parent Parent settings.
        * @return      void
        */
        public function __construct( $parent ) {
           
            $redux_ver = ReduxFramework::$_version;
            
            //TODO on release
            
//            DO NOT REMOVE, COMMENT OUT OR EDIT THESE THREE LINES!!!!!
//            Doing so could cause errors, notices, and/or your computer to cry!            
//            Why?  Older version of Redux - pre 3.2.8.8 - do not have the necessary
//            helper functions to make this extension work.
            if (version_compare($redux_ver, '3.2.8.8') < 0) {
                wp_die('The Redux Color Scheme extension required Redux Framework version 3.2.8.8 or higher.<br/><br/>You are running Redux Framework version ' . $redux_ver );
            }
            
            // Set parent object
            $this->parent = $parent;
            
            // Set extension dir
            if ( empty( $this->extension_dir ) ) {
                $this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
            }
            
            // Set field name
            $this->field_name = 'color_scheme';

            // Set instance
            self::$theInstance = $this;
            
            $this->class_css = Redux_Helpers::cleanFilePath(get_stylesheet_directory()) . '/redux-color-schemes.css';
            
            // Adds the local field
            add_filter( 'redux/'.$this->parent->args['opt_name'].'/field/class/'. $this->field_name, array( &$this, 'overload_field_path' ) );

            // Ajax hooks
            add_action('wp_ajax_redux_color_schemes',         array($this, 'parse_ajax'));
            add_action('wp_ajax_nopriv_redux_color_schemes',  array($this, 'parse_ajax'));

            // Reset hooks
            add_action('redux/options/' . $this->parent->args['opt_name'] . '/reset', array($this, 'reset_all'));
            add_action('redux/options/' . $this->parent->args['opt_name'] . '/section/reset', array($this, 'reset_section'));
            
            // Save hook
            add_action('redux/options/' . $this->parent->args['opt_name'] . '/saved', array($this, 'save_me'), 10, 2);
            
            // Register hook - to get field id and prep helper
            add_action('redux/options/' . $this->parent->args['opt_name'] . '/field/' . $this->field_name . '/register', array($this, 'register_field'));
            
        }

        /**
        * Field Register. Sets the whole smash up.
        *
        * @since       1.0.0
        * @access      public
        * @param       array $data Field data.
        * @return      void
        */        
        public function register_field($data) {
            global $wp_filesystem;

            // Include color_scheme helper
            include_once($this->extension_dir . 'color_scheme/inc/class.color_scheme_functions.php');

            $this->field_id = $data['id'];
            ReduxColorSchemeFunctions::$_field_id = $data['id'];
            
            // Set helper parent object
            ReduxColorSchemeFunctions::$_parent = $this->parent;
            
            // Init wp_filesystem
            Redux_Functions::initWpFilesystem();
            
            ReduxColorSchemeFunctions::init();

            // Prep storage
            $upload_dir = ReduxColorSchemeFunctions::$upload_dir;

            // Create uploads/redux_scheme_colors/ folder
            if (!is_dir($upload_dir)) {
                $wp_filesystem->mkdir($upload_dir, FS_CHMOD_DIR);
            }

            // Set upload_dir cookie
            setcookie('redux_color_scheme_upload_dir', $upload_dir, 0, "/");
            
            // Scheme file exists
            $scheme_file_exists = file_exists($upload_dir . '/' . $data['id'] . '.json');
            
            // Default scheme exists
            //print_r(ReduxColorSchemeFunctions::getSchemeNames());
            $default_exists = in_array('default', array_map('strtolower', ReduxColorSchemeFunctions::getSchemeNames()));

            // If either are false, create default scheme
            if (!$scheme_file_exists || !$default_exists) {
                $this->reset_data();
            }
        }
        
        /**
        * Save Changes Hook. What to do when changes are saved
        *
        * @since       1.0.0
        * @access      public
        * @param       array $data Saved data.
        * @return      void
        */        
        public function save_me($data, $changed_values) {

            // Make sure the field is in use first
            if (true == Redux_Helpers::isFieldInUse($this->parent, $this->field_name)) {

                // Get current scheme name
                $scheme = ReduxColorSchemeFunctions::getCurrentSchemeID();
                                
                // Get the current field ID
                $raw_data = $data[$this->field_id];

                // Create new array
                $save_data = array();

                // Enum through saved data
                foreach($raw_data as $id => $val) {
                    if ($id !== 'color_scheme_name') {
                        
                        // Sanitize everything
                        $color  = isset($val['color']) ? $val['color'] : '';
                        $alpha  = isset($val['alpha']) ? $val['alpha'] : 1;
                        $mode   = isset($val['mode']) ? $val['mode'] : 'color';
                        $id     = isset($val['id']) ? $val['id'] : $id;
                        $impt   = isset($val['important']) ? $val['important'] : false;

                        // Create array of saved data
                        $save_data[] = array(
                            'id'        => $id,
                            'title'     => $val['title'],
                            'color'     => $color,
                            'alpha'     => $alpha,
                            'selector'  => $val['selector'],
                            'mode'      => $mode,
                            'important' => $impt,
                        );
                    }
                }

                // Save new data to JSON scheme file
                ReduxColorSchemeFunctions::setSchemeData($scheme, $save_data);
                
                // Set the default in the database
                $this->setDatabaseData($scheme);
            } else {
                
                // Get upload dir
                $upload = wp_upload_dir();
                
                // Cleanup file path
                $upload = ReduxFramework::$_upload_dir . '/color-schemes';
                
                // Delete scheme files/folder if they exist.
                if (is_dir($upload)){
                    self::deleteDir ($upload);
                }
                
                // Remove container-replacer.css
                if (file_exists($this->class_css)){
                    unlink($this->class_css);
                }
            }
        }

        /**
        * deleteDir. Removes color schemes dir when not in use.
        *
        * @since       1.0.0
        * @access      private
        * @return      void
        */        
        static private function deleteDir($dirPath) {
            if (! is_dir($dirPath)) {
                exit;
                //throw new InvalidArgumentException("$dirPath must be a directory");
            }
            
            if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
                $dirPath .= '/';
            }
            
            $files = glob($dirPath . '*', GLOB_MARK);
            foreach ($files as $file) {
                if (is_dir($file)) {
                    self::deleteDir($file);
                } else {
                    unlink($file);
                }
            }
            rmdir($dirPath);
        }
        
        /**
        * Reset data. Restores colour picker to default values
        *
        * @since       1.0.0
        * @access      private
        * @return      void
        */        
        private function reset_data(){

            // Get default data
            $data = $this->getDefaultData();

            //  Add to (and/or create) JSON scheme file
            ReduxColorSchemeFunctions::setSchemeData('Default', $data);

            // Set default scheme
            ReduxColorSchemeFunctions::setCurrentSchemeID('Default');

            // Set the database with default settings
            $this->setDatabaseData();
        }
        
        /**
        * Reset All Hook. Todo list when all data is reset
        *
        * @since       1.0.0
        * @access      public
        * @param       array $data All data from Framework.
        * @return      void
        */        
        public function reset_all($data) {
            if (true == Redux_Helpers::isFieldInUse($this->parent, $this->field_name)) {
                $this->reset_data();
            }
        }

        /**
        * Reset Section Hook. Todo list when section data is reset
        *
        * @since       1.0.0
        * @access      public
        * @param       array $data All data from Framework.
        * @return      void
        */        
        public function reset_section($data) {
            
            // Make sure field is in use
            if (true == Redux_Helpers::isFieldInUse($this->parent, $this->field_name)) {
                
                // Get current tab/section number
                $curTab = $_COOKIE['redux_current_tab'];
                
                // Get the tab/section number field is used on
                $tabNum = Redux_Helpers::tabFromField($this->parent, $this->field_id);

                // If they match...
                if ($curTab == $tabNum) {
                    
                    // Reset data
                    $this->reset_data();
                }
            }
        }
        
        /**
        * AJAX evaluator. Detemine course of action based on AJAX callback
        *
        * @since       1.0.0
        * @access      public
        * @return      void
        */        
        public function parse_ajax() {
            
            // Verify nonce
            if (!isset($_REQUEST['nonce']) || !wp_verify_nonce($_REQUEST['nonce'], "redux_{$this->parent->args['opt_name']}_color_schemes")) {
                die(0);
            }
            
            // Do action
            if (isset($_REQUEST['type'])) {
                
                // Save scheme
                if ($_REQUEST['type'] == "save") {
                    $this->save_scheme();
                
                // Delete scheme
                } elseif ($_REQUEST['type'] == "delete") {
                    $this->delete_scheme();
                
                // Scheme change
                } elseif ($_REQUEST['type'] == "update") {
                    $this->get_scheme_html();
                    
                // Export scheme file
                } elseif ($_REQUEST['type'] == "export") {
                    $this->download_schemes();
                }
            }
        }

        /**
        * Download Scheme File. 
        *
        * @since       1.0.0
        * @access      private
        * @return      void
        */        
        private function download_schemes() {
            
            // Read contents of scheme file
            $content = ReduxColorSchemeFunctions::readSchemeFile('', false);
            
            // Set header info
            header( 'Content-Description: File Transfer' );
            header( 'Content-type: application/txt' );
            header( 'Content-Disposition: attachment; filename="redux_schemes_' . $this->parent->args['opt_name'] . '_' . $this->field_id . '_' . date( 'm-d-Y' ) . '.json"' );
            header( 'Content-Transfer-Encoding: binary' );
            header( 'Expires: 0' );
            header( 'Cache-Control: must-revalidate' );
            header( 'Pragma: public' );
            
            // File download
            echo $content;
            
            // 2B ~! 2B
            die;
        }

        /**
        * Save Scheme. Saved individual scheme to JSON scheme file
        *
        * @since       1.0.0
        * @access      private
        * @return      void
        */        
        private function save_scheme() {
            
            // Get scheme name
            $scheme_name    = $_REQUEST['scheme_name'];
            
            // Get scheme data
            $scheme_data    = $_REQUEST['scheme_data'];
            
            // Get field ID
            $field_id       = $_REQUEST['field_id'];

            // Save scheme to file.  If successful...
            if (true == ReduxColorSchemeFunctions::setSchemeData($scheme_name, $scheme_data)) {
                
                // Update field ID
                ReduxColorSchemeFunctions::$_field_id = $field_id;
                
                // Update scheme selector
                echo ReduxColorSchemeFunctions::getSchemeSelectHTML($scheme_name);
            }
            
            die(); // a horrible death!
        }

        /**
        * Delete Scheme. Delete individual scheme from JSON scheme file
        *
        * @since       1.0.0
        * @access      private
        * @return      void
        */        
        private function delete_scheme() {
            
            // Get deleted scheme ID
            $scheme_id  = $_REQUEST['scheme_id'];
            
            // Get field ID
            $field_id   = $_REQUEST['field_id'];

            // If scheme ID was passed (and why wouldn't it be??  Hmmm??)
            if ($scheme_id) {
                
                // Get entire scheme file
                $schemes = ReduxColorSchemeFunctions::readSchemeFile();
                
                // If we got a good read...
                if (!false == $schemes){
                    
                    // If scheme name exists...
                    if (isset($schemes[$scheme_id])) {
                        
                        // Unset it.
                        unset($schemes[$scheme_id]);

                        // Save the scheme data, minus the deleted scheme.  Upon success...
                        if (true == ReduxColorSchemeFunctions::writeSchemeFile($schemes)) {
                            
                            // Set default scheme
                            ReduxColorSchemeFunctions::setCurrentSchemeID('Default');
                            
                            // Update field ID
                            ReduxColorSchemeFunctions::$_field_id = $field_id;
                            
                            // Meh TODO
                            $this->setDatabaseData();

                            echo "success";
                        } else {
                            echo "Failed to write JSON file to server.";
                        }
                    } else {
                        echo "Scheme name does not exist in JSON string.  Aborting.";
                    }
                } else {
                    echo "Failed to read JSON scheme file, or file is empty.";
                }
            } else {
                echo "No scheme ID passed.  Aborting.";
            }
            
            die(); // rolled a two.
        }

        /**
        * Gets the new scheme based on selection.
        *
        * @since       1.0.0
        * @access      private
        * @return      void
        */        
        private function get_scheme_html() {
            
            // Get the selected scheme name
            $scheme_id      = $_POST['scheme_id'];
            
            // Get the field ID
            $field_id       = $_POST['field_id'];
            
            // Get the field class
            $field_class    = isset($_POST['field_class']) ? $_POST['field_class'] :'';

            // Set the updated field ID
            ReduxColorSchemeFunctions::$_field_id       = $field_id;
            
            // Set the updated field class
            ReduxColorSchemeFunctions::$_field_class    = $field_class;
            
            // Get the colour picket layout HTML
            $html = ReduxColorSchemeFunctions::getCurrentColorSchemeHTML($scheme_id);
            
            // Print!
            echo $html;

            die(); //another day
        }

        /**
         * setDatabaseData Function.
         *
         * Sets current scheme to database.
         *
         * @since       1.0.0
         * @access      private
         * @param       string $scheme Current scheme name
         * @return      void
         */           
        private function setDatabaseData($scheme = 'Default'){
            $data = ReduxColorSchemeFunctions::getSchemeData($scheme);

            // Get opt name, for database
            $opt_name = $this->parent->args['opt_name'];

            // Get all options from database
            $redux_options = get_option($opt_name);

            // Append ID to variable that holds the current scheme ID data
            $redux_options[$this->field_id] = $data;

            // Save the modified settings
            update_option($opt_name, $redux_options);
        }

        /**
         * getDefaultData Function.
         *
         * Retrieves array of default data for colour picker.
         *
         * @since       1.0.0
         * @access      private
         * @return      array Default values from config.
         */            
        private function getDefaultData() {
            $defOpts = $this->parent->options_defaults[$this->field_id];

            $data = array();
            foreach($defOpts as $k => $v) {

                $title      = isset($v['title']) ? $v['title'] : $v['id'];
                $color      = isset($v['color']) ? $v['color'] : '';
                $alpha      = isset($v['alpha']) ? $v['alpha'] : 1;
                $selector   = isset($v['selector']) ? $v['selector'] : '';
                $mode       = isset($v['mode']) ? $v['mode'] : 'color';
                $impt       = isset($v['important']) ? $v['important'] : false;

                $data[] = array(
                    'id'        => $v['id'],
                    'title'     => $title,
                    'color'     => $color,
                    'alpha'     => $alpha,
                    'selector'  => $selector,
                    'mode'      => $mode,
                    'important' => $impt,
                );
            }

            return $data;
        }        
        
        static public function getInstance() {
            return self::$theInstance;
        }

        // Forces the use of the embeded field path vs what the core typically would use
        public function overload_field_path($field) {
            return dirname(__FILE__).'/'.$this->field_name.'/field_'.$this->field_name.'.php';
        }

    } // class
} // if
