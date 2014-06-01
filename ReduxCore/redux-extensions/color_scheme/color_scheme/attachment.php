<?php

if (!class_exists('ReduxColorSchemeImport')) {
    class ReduxColorSchemeImport {
        
        // Private variables
        private $data       = '';
        private $err        = '';
        private $upload_dir = '';
        
        /*
         * Constructor
         */
        public function __construct() {
            
            // Set result to false
            $this->result = false;
            
            // String used more than once.
            $abort = '  Aborting import.';
            
            // Is request type set?
            if (isset($_REQUEST['type'])) {
                
                // Is request type import?
                if ($_REQUEST['type'] == "import") {
                    
                    // Get upload dir from cookie
                    if (true == $this->getUploadDir()) {
                        
                        // Check for field id
                        if (isset($_REQUEST['field_id'])) {
                            
                            // Get field id
                            $this->field_id = $_REQUEST['field_id'];
                            
                            // Process import file
                            if (true == $this->processFile()) {
                                $this->result   = true;
                                $this->data     = 'Import successful!  Click <strong>OK</strong> to refresh.';

                            // processFile failed, return error message
                            } else {
                                $this->data = $this->err . $abort;
                            }
                        } else {
                            $this->data = 'Invalid field ID.' . $abort;
                        }
                        
                    // Cookie read failed
                    } else {
                        $this->data = $this->err . $abort;
                    }
                    
                // No request type.  Somebody tryin' to doing something they shouldn't.
                } else {
                    $this->data = 'Invalid request type.' . $abort;
                }
                
            // No request type?  Just in case.
            } else {
                $this->data = 'No request type specified.' . $abort;
            }

            // data array to return
            $arr = array(
                'result'    => $this->result, 
                'data'      => $this->data
            );
            
            // json encode
            $arr = json_encode($arr);

            echo $arr;
        }

        /*
         * Get wp upload dir from cookie
         */
        private function getUploadDir() {
            
            // cookie name
            $val = 'redux_color_scheme_upload_dir';
            
            // Is the cookie there?
            if (isset($_COOKIE[$val])) {
                
                // Get value from cookie
                $upload_dir = $_COOKIE[$val];
                
                // Is it blank?
                if ('' == $upload_dir) {
                    $this->err = 'Required cookie is empty.';
                    
                // Nope, grab the data.
                } else {
                    $this->upload_dir = $upload_dir;
                    return true;
                }
                
            // No cookie for you!
            } else {
                $this->err = 'Unable to read required cookie.';
            }
            
            return false;
        }
        
        /*
         * Process import file
         */
        private function processFile() {
            
            // Undefined | Multiple Files | $_FILES Corruption Attack
            // If this request falls under any of them, treat it invalid.
            if (!isset($_FILES['file']['error']) || is_array($_FILES['file']['error'])) {
                $this->err = 'Invalid upload parameters.';
                return false;
            } else { 
                
                // Check $_FILES['file']['error'] value.
                switch ($_FILES['file']['error']) {
                    
                    // All is good.
                    case UPLOAD_ERR_OK:
                    break;
                
                    // Missing file.
                    case UPLOAD_ERR_NO_FILE:
                        $this->err = 'No file chosen.';
                        return false;
                    break;
                
                    // File too big.
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $this->err = 'Exceeds filesize limit.';
                        return false;
                    break;
                
                    // Unknown err.
                    default:
                        $this->err = 'Unknown error.';
                        return false;
                    break;                        
                }    

                // get file name
                $filename = trim($_FILES['file']['name']);

                // get temp file path
                $filepath = trim($_FILES['file']['tmp_name']);

                // remove illegal chars
                $filename = preg_replace('#[^0-9a-z ().,-_]#i', '', $filename);
                
                // Is accepted type?
                if (true == $this->isProperMIME($filepath)) {

                    // Check for JSON extension.
                    if ($this->isExtJSON($filename)){
                        
                        // Is actual scheme file?
                        if ($this->isSchemeFile($filepath)) {

                            // Try moving it from temp to wp upload.
                            if (!move_uploaded_file($filepath, $this->upload_dir . '/' . $this->field_id .  '.json')) {
                                $this->err = 'Cannot move Redux color scheme file to the upload folder.';
                            } else {
                                return true;
                            }
                        }
                    }
                }
            }
            
            return false;
        }

        /*
         * Check for proper MIME type.
         */
        private function isProperMIME($filepath) {
            // Get MIME type
            $finfo = new finfo(FILEINFO_MIME_TYPE);

            // Check type against accepted list.
            $ext = array_search(
                $finfo->file($filepath),
                array(
                    'json' => 'text/plain',
                ),
                true
            );

            // Bad type.
            if (false == $ext) {
                $this->err = 'Invalid file format.';
                return false;
            }
            
            return true;
        }
        
        /*
         * Is file a valid scheme file.
         */
        private function isSchemeFile($filepath) {
            
            //Check for valid color scheme backup tag.
            $content = $this->readTMP($filepath);
            
            // If empty, set err
            if ('' == $content) {
                $this->err = 'The selected file is empty.';
                
            // check for valid scheme entry
            } else {
                
                // scheme tag
                $tag = '"color_scheme_name"';
                
                // Locate its position in the string
                $pos = strpos($content, $tag);
                
                // There?  Return true
                if ($pos > 0) {
                    return true;
                    
                // Otherwise, set the err 
                } else {
                    $this->err = 'The selected file is not a valid color scheme file.';
                }
            }            
            return false;
        }
        
        /*
         * Check for valid extension
         */
        private function isExtJSON($filename) {
            // Get last period position.
            $pos = strrpos($filename, '.');
            
            // One found
            if ($pos > 0) {
                
                // Extract file extension
                $ext = substr($filename, $pos + 1);
                
                // If JSON, continue
                if ('json' == $ext) {
                    return true;
                    
                // If not, set err.
                } else {
                    $this->err = 'The selected file is a ' . strtoupper($ext) . ' file, not a JSON file.';
                }
                
            // No file ext found, set err.
            } else {
                $this->err = 'The selected file has no JSON extension.';
            }
            
            return false;
        }
        
        /*
         * Reads data from file.
         */
        private function readTMP($file) {
            if (file_exists($file)) {
                $fp = fopen($file, 'r');
                $data = fread($fp, filesize($file));
                fclose($fp);

                return $data;
            } else {
                return '';
            }
        }
    }

    new ReduxColorSchemeImport;
}