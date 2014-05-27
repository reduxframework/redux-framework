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
 * @package     ReduxFramework
 * @author      Dovy Paukstys (dovy)
 * @version     3.0.0
 */
/**
 * Class and Function List:
 * Function list:
 * - __construct()
 * - custom_upload_mimes()
 * - getFonts()
 * - addCustomFonts()
 * - ajax()
 * - getValidFiles()
 * - processWebfont()
 * - getMissingFiles()
 * - checkFontFileName()
 * - generateCSS()
 * - generateFontCSS()
 * - get_instance()
 * - overload_field_path()
 * - dynamic_section()
 * Classes list:
 * - ReduxFramework_extension_custom_fonts
 */
// Exit if accessed directly
if (!defined('ABSPATH')) exit;
// Don't duplicate me!
if (!class_exists('ReduxFramework_extension_custom_fonts')) {
    /**
     * Main ReduxFramework custom_fonts extension class
     *
     * @since       3.1.6
     */
    class ReduxFramework_extension_custom_fonts {
        
        static $version = "1.0.0";
        // Protected vars
        protected $parent;
        
        public $extension_url;
        
        public $extension_dir;
        
        public static $theInstance;
        
        public $custom_fonts = array();
        /**
         * Class Constructor. Defines the args for the extions class
         *
         * @since       1.0.0
         * @access      public
         * @param       array $sections Panel sections.
         * @param       array $args Class constructor arguments.
         * @param       array $extra_tabs Extra panel tabs.
         * @return      void
         */
        public function __construct($parent) {
            
            global $wp_filesystem;
            // Initialize the Wordpress filesystem, no more using file_put_contents function
            if (empty($wp_filesystem)) {
                require_once (ABSPATH . '/wp-admin/includes/file.php');
                
                WP_Filesystem();
            }
            
            $upload = wp_upload_dir();
            
            $this->upload_dir = ReduxFramework::$_upload_dir . 'custom-fonts/'; 
            //$upload['basedir'] . '/redux_custom_fonts/';
            
            $this->upload_url = ReduxFramework::$_upload_url . 'custom-fonts/';
            //$upload['baseurl'] . '/redux_custom_fonts/';
            
            $this->getFonts();
            if (file_exists($this->upload_dir.'/fonts.css')) {
                if (filemtime($this->upload_dir) > (filemtime($this->upload_dir.'/fonts.css')+20) ) {
                    //echo "regen existing file";
                    $this->generateCSS();
                }
            } else {
                //echo "create non existing file";
                $this->generateCSS();
            }
            
            $this->parent = $parent;
            
            if (empty($this->extension_dir)) {
                $this->extension_dir = trailingslashit(str_replace('\\', '/', dirname(__FILE__)));
            }
            
            $this->field_name = 'custom_fonts';
            
            self::$theInstance = $this;
            // Adds the local field
            add_filter('redux/' . $this->parent->args['opt_name'] . '/field/class/' . $this->field_name, array(&$this,
                'overload_field_path'
            ));
            
            add_action('wp_ajax_redux_custom_fonts', array(
                $this,
                'ajax'
            ));
            
            add_filter("redux/{$this->parent->args['opt_name']}/field/typography/custom_fonts", array(
                $this,
                'addCustomFonts'
            ));
            
            $this->dynamic_section();

            //require_once 'System.php'; // Wordpress core file
            //if ( class_exists('System') === true ) {
                add_filter('upload_mimes', array(
                    $this,
                    'custom_upload_mimes'
                ));
            //}

            add_action( 'wp_head', array( $this, '_enqueue_output' ), 150 );

            add_filter('tiny_mce_before_init', array( $this, 'extend_tinymce_dropdown' ) );


        }

        /**
         * Adds FontMeister fonts to the TinyMCE drop-down. Typekit fonts don't render properly in the drop-down and in the editor,
         * because Typekit needs JS and TinyMCE doesn't support that.
         *
         * @param $opt
         * @return array
         */
        function extend_tinymce_dropdown($opt) {
            if (!is_admin()) {
                return $opt;
            }

            //print_r($this->custom_fonts);
            //return $opts;

            $theme_advanced_fonts = "Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats";
            $mce_fonts = array();
            $google_font_counter = 0;
            $content_css = array();
            $fontdeck_included = false;
            
            foreach ($this->custom_fonts as $font=>$pieces) {
                $mce_fonts[] = $font;
            }
            $mce_fonts = implode(',', $mce_fonts);
            $content_css = implode(',', $content_css);
            if (trim($mce_fonts) != '') {
                $theme_advanced_fonts .= $mce_fonts;
            }
            $opt['theme_advanced_fonts'] = $theme_advanced_fonts;
            if (isset($opt['content_css'])) {
                $opt['content_css'] .= $content_css;
            }
            else {
                $opt['content_css'] = $content_css;
            }
            return $opt;

        }

        function _enqueue_output() {
            if ( file_exists( $this->upload_dir . 'fonts.css' ) ) {
                wp_register_style(
                    'redux-custom-fonts-css',
                    $this->upload_dir . 'fonts.css',
                    '',
                    filemtime( $this->upload_dir . 'fonts.css' ),
                    'all'
                );                
            }
        }
        
        function custom_upload_mimes($existing_mimes = array()) {
            $existing_mimes['ttf'] = 'font/ttf';
            $existing_mimes['otf'] = 'font/otf';
            $existing_mimes['woff'] = 'application/font-woff';

            return $existing_mimes;
        }
        
        public function getFonts() {
            global $wp_filesystem;
            
            if (!empty($this->custom_fonts)) {
                return $this->custom_fonts;
            }

            $fonts = $wp_filesystem->dirlist($this->upload_dir, false, true);
            /*
            if (!empty($fonts)) {
                foreach ($fonts as $font) {
                    if ($font['type'] == "d") {
                        if (!empty($font['name'])) {
                            $kinds = array();
                            foreach($font['files'] as $f) {
                                $valid = $this->checkFontFileName($f);
                                if ($valid) {
                                    array_push($kinds, $valid);
                                }
                            }
                            $this->custom_fonts[$font['name']] = $kinds;
                            //array_push($this->custom_fonts, $font['name']);
                        }
                    }
                }
            }
            */
            if (!empty($fonts)) {
                foreach ($fonts as $section) {
                    if ($section['type'] == "d" && !empty($section['name'])) {
                        if ($section['name'] == "custom") {
                            $section['name'] = __('Custom Fonts', 'redux-framework');
                        } else if ($section['name'] == "fontsquirrel") {
                            $section['name'] = __('Fonts Squirrel', 'redux-framework');
                        }
                        if (!isset($section['files']) || empty($section['files'])) {
                            continue;
                        }
                        $this->custom_fonts[$section['name']] = isset( $this->custom_fonts[$section['name']] ) ? $this->custom_fonts[$section['name']] : array();
  
                        $kinds = array();
                        foreach($section['files'] as $font) {
                            if (!empty($font['name'])) {
                                if (!isset($font['files']) || empty($font['files'])) {
                                    continue;
                                }
                                $kinds = array();
                                foreach($font['files'] as $f) {
                                    $valid = $this->checkFontFileName($f);
                                    if ($valid) {
                                        array_push($kinds, $valid);
                                    }
                                }
                                $this->custom_fonts[$section['name']][$font['name']] = $kinds;
                            }
                        
                        }
                    }
                }
            }
        }
        
        public function addCustomFonts($custom_fonts) {
            if (!is_array($custom_fonts) || empty($custom_fonts)) {
                $custom_fonts = array();
            }
            $custom_fonts = wp_parse_args($custom_fonts, $this->custom_fonts);

            return $custom_fonts;
        }
        
        public function ajax() {
            
            if (!isset($_REQUEST['nonce']) || !wp_verify_nonce($_REQUEST['nonce'], "redux_{$this->parent->args['opt_name']}_custom_fonts")) {
                //exit("Not a valid nonce");
                die(0);
            }
            if (isset($_REQUEST['type']) && $_REQUEST['type'] == "delete") {

                global $wp_filesystem;

                if ($_REQUEST['section'] == __('Custom Fonts', 'redux-framework')) {
                    $_REQUEST['section'] = "custom";
                }
                if ( $_REQUEST['section'] == __('Fonts Squirrel', 'redux-framework' ) ) {
                    $_REQUEST['section'] = "fontssquirrel";
                }                
                
                try {
                    $wp_filesystem->delete($this->upload_dir . $_REQUEST['section'].'/'.$_REQUEST['name'].'/', true, 'd');
                    $result = array(
                        'type' => "success"
                    );
                    echo json_encode($result);

                } catch (Exception $e) {
                    echo json_encode(array('type' => 'error', 'msg' => 'Unable to delete font file(s).'));
                }                  
                
                die();
            }



            if (!isset($_REQUEST['title'])) {
                $_REQUEST['title'] = "";
            }
            if (isset($_REQUEST['attachment_id']) && !empty($_REQUEST['attachment_id'])) {
                $this->processWebfont($_REQUEST['attachment_id'], $_REQUEST['title'], $_REQUEST['mime']);
                
                $result = array(
                    'type' => "success"
                );
                
                echo json_encode($result);
            }
            die();
        }
        
        function getValidFiles($path) {
            global $wp_filesystem;
            $output = array();
            $path = trailingslashit($path);
            $files = $wp_filesystem->dirlist($path, false, true);
            
            foreach ($files as $file) {
                if ($file['type'] == "d") {
                    $output = array_merge($output, $this->getValidFiles($path . $file['name']));
                } elseif ($file['type'] == "f") {
                    $valid = $this->checkFontFileName($file);
                    if ($valid) {
                        $output[$valid] = trailingslashit($path) . $file['name'];
                    }
                }
            }
            return $output;
        }
        
        function processWebfont($attachment_id, $name, $mime_type, $subfolder = 'custom/') {
            
            global $wp_filesystem;
            
            $missing = array();
            
            $complete = array(
                'ttf',
                'woff',
                'eot',
                'svg'
            );
            
            $subtype = explode('/', $mime_type);
            $subtype = trim(max($subtype));
            
            if (!is_dir($this->upload_dir)) {
                $wp_filesystem->mkdir($this->upload_dir, FS_CHMOD_DIR);
            }
            if (!is_dir($this->upload_dir . $subfolder )) {
                $wp_filesystem->mkdir($this->upload_dir . $subfolder, FS_CHMOD_DIR);
            }            
            $temp = $this->upload_dir . 'temp';
            
            $path = get_attached_file($attachment_id, false);

            if (empty($path)) {
                echo json_encode(array('type' => 'error', 'msg' => 'Attachment does not exist.'));
                die();
            }
            $filename = explode('/', $path);
            
            $filename = $filename[(count($filename) - 1) ];
            
            $fontname = ucfirst(str_replace(array(
                '.zip',
                '.ttf',
                '.woff',
                '.eot',
                '.svg',
                '.otf'
            ) , '', strtolower($filename)));
            
            if (!isset($name) || empty($name)) {
                $name = $fontname;
            }
            if ($subtype == "zip") {
                if (!is_dir($temp)) {
                    $wp_filesystem->mkdir($temp, FS_CHMOD_DIR);
                }
                $unzipfile = unzip_file($path, $temp);
                $output = $this->getValidFiles($temp);
                
                if (!empty($output)) {
                    foreach ($complete as $test) {
                        if (!isset($output[$test])) {
                            $missing[] = $test;
                        }
                    }
                    
                    if (!is_dir($this->upload_dir . $subfolder . $name . '/')) {
                        $wp_filesystem->mkdir($this->upload_dir . $subfolder . $name . '/', FS_CHMOD_DIR);
                    }
                    foreach ($output as $key => $value) {
                        $wp_filesystem->copy($value, $this->upload_dir . $subfolder . $name . '/' . $fontname . '.' . $key, FS_CHMOD_DIR);
                    }
                    $this->getMissingFiles($name, $fontname, $missing, $output);
                }
                $wp_filesystem->delete($temp, true, 'd');
                $this->generateCSS();
                wp_delete_attachment($attachment_id, true);
            } else if ($subtype == "ttf" || $subtype == "otf" || $subtype == "font-woff") {
                foreach ($complete as $test) {
                    if (!isset($output[$test])) {
                        $missing[] = $test;
                    }
                }
                
                if (!is_dir($this->upload_dir . $subfolder . $name . '/')) {
                    $wp_filesystem->mkdir($this->upload_dir . $subfolder . $name . '/', FS_CHMOD_DIR);
                }
                $wp_filesystem->copy($path, $this->upload_dir . $subfolder . '/' . $name . '/' . $fontname . '.' . $subtype, FS_CHMOD_DIR);
                $output = array(
                    $subtype => $path
                );
                $this->getMissingFiles($name, $fontname, $missing, $output, $subfolder);
                $this->generateCSS();
                wp_delete_attachment($attachment_id, true);
            } else {
                echo json_encode(array('type' => 'error', 'msg' => 'File type not recognized.'));
                die();                
            }
        }
        
        private function getMissingFiles($name, $fontname, $missing, $output, $subfolder) {
            global $wp_filesystem;
            if (!isset($name) || empty($name) || !isset($missing) || empty($missing) || !is_array($missing)) {
                return;
            }
            // Find a file to convert from
            foreach ($output as $key => $value) {
                if ($key == "eot") {
                    continue;
                } else {
                    $main = $key;
                    break;
                }
            }
            include (dirname(__FILE__) . '/lib/Unirest.php');
            include (dirname(__FILE__) . '/lib/Tar.php');
            if (!is_dir($this->upload_dir . 'missing')) {
                $wp_filesystem->mkdir($this->upload_dir . 'missing', FS_CHMOD_DIR);
            }
            
            foreach ($missing as $item) {
                
                $response = Unirest::post("https://ofc.p.mashape.com/directConvert/", array(
                    "X-Mashape-Authorization" => "B6tUPzlD13s1mEJbPOZgCbPfIxUQYBjO"
                ) , array(
                    "file" => "@" . $this->upload_dir . $subfolder . $name . '/' . $fontname . '.' . $main,
                    "format" => $item
                ));
                $wp_filesystem->put_contents($this->upload_dir . 'missing/' . $name . '.' . $item . ".tar.gz", $response->body, FS_CHMOD_FILE);

                try {
                    $phar = new PharData( $this->upload_dir . 'missing/' . $name . '.' . $item . ".tar.gz" );
                    $phar->extractTo( $this->upload_dir . 'missing/', null, true ); // extract all files, and overwrite
                    $uncompressed = true;
                } catch (Exception $e) {
                    try {
                        $tar = new Archive_Tar($this->upload_dir . 'missing/' . $name . '.' . $item . ".tar.gz", true);
                        $result = $tar->extract($this->upload_dir . 'missing/');
                    } catch (Exception $e) {
                        $wp_filesystem->delete($this->upload_dir . 'missing/', true, 'd');
                        echo json_encode(array('type' => 'error', 'msg' => 'Unable to decompress compiled font file(s).'));
                        die();
                    }
                }                
            }
            $output = $this->getValidFiles($this->upload_dir . 'missing');
            
            if (!empty($output)) {
                foreach ($output as $key => $value) {
                    $wp_filesystem->copy($value, $this->upload_dir . $subfolder . $name . '/' . $fontname . '.' . $key, FS_CHMOD_DIR);
                }
            }
            
            $wp_filesystem->delete($this->upload_dir . 'missing/', true, 'd');
        }
        
        private function checkFontFileName($file) {
            
            if (strtolower(substr($file['name'], -5)) == ".woff") {
                return "woff";
            }
            $sub = strtolower(substr($file['name'], -4));
            
            if ($sub == ".ttf") {
                return "ttf";
            }
            if ($sub == ".eot") {
                return "eot";
            }
            if ($sub == ".svg") {
                return "svg";
            }
            if ($sub == ".otf") {
                return "otf";
            }
            return false;
        }
        
        private function generateCSS() {
            global $wp_filesystem;
            
            $fonts = $wp_filesystem->dirlist($this->upload_dir, false, true);
            
            if (empty($fonts)) {
                return;
            }
            $css = "";
            
            foreach ($fonts as $font) {
                if ($font['type'] == "d") {
                    $css.= $this->generateFontCSS($font['name'], $this->upload_dir);
                }
            }
            $wp_filesystem->put_contents($this->upload_dir . 'fonts.css', $css, FS_CHMOD_FILE);
        }
        
        private function generateFontCSS($name, $dir) {
            global $wp_filesystem;
            
            $path = $dir . $name;
            
            $files = $wp_filesystem->dirlist($path, false, true);
            
            if (empty($files)) {
                return;
            }
            $output = array();
            
            foreach ($files as $file) {
                $output[$this->checkFontFileName($file) ] = $file['name'];
            }
            
            $css = "@font-face {";
            
            $css.= "font-family:'{$name}';";
            
            $src = array();
            
            if (isset($output['eot'])) {
                $src[] = "url('{$name}/{$output['eot']}?#iefix') format('embedded-opentype')";
            }
            if (isset($output['woff'])) {
                $src[] = "url('{$name}/{$output['woff']}') format('woff')";
            }
            if (isset($output['ttf'])) {
                $src[] = "url('{$name}/{$output['ttf']}') format('truetype')";
            }
            if (isset($output['svg'])) {
                $src[] = "url('{$name}/{$output['svg']}#svg{$name}') format('svg')";
            }
            if (!empty($src)) {
                $css.= "src:" . implode(", ", $src) . ";";
            }
            // Replace font weight and style with sub-sets
            $css.= "font-weight: normal;";
            
            $css.= "font-style: normal;";
            
            $css.= "}";
            
            return $css;
        }
        
        public static function get_instance() {
            return self::$theInstance;
        }
        // Forces the use of the embeded field path vs what the core typically would use
        public function overload_field_path($field) {
            return dirname(__FILE__) . '/' . $this->field_name . '/field_' . $this->field_name . '.php';
        }
        /**
         Custom function for filtering the sections array. Good for child themes to override or add to the sections.
         Simply include this function in the child themes functions.php file.
         NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
         so you must use get_template_directory_uri() if you want to use any of the built in icons
         *
         */
        function dynamic_section() {
            
            if (!isset($this->parent->fontControl)) {
                $this->parent->sections[] = array(
                    'title' => __('Font Control', 'redux-framework-demo') ,
                    'desc' => __('<p class="description"></p>', 'redux-framework-demo') ,
                    'icon' => 'el-icon-font',
                    // Leave this as a blank section, no options just some intro text set above.
                    'fields' => array()
                );
                
                for ($i = count($this->parent->sections);$i >= 1;$i--) {
                    
                    if (isset($this->parent->sections[$i]) && isset($this->parent->sections[$i]['title']) && $this->parent->sections[$i]['title'] == __('Font Control', 'redux-framework-demo')) {
                        $this->parent->fontControl = $i;
                        
                        break;
                    }
                }
                $this->parent->sections[$this->parent->fontControl]['fields'][] = array(
                    'id' => 'redux_custom_fonts',
                    'type' => 'custom_fonts'
                );                
            }
            
            
        }
    } // class
    
    
} // if
