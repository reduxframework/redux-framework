<?php

/**
 * @package     Redux Framework
 * @subpackage  Multi media selector
 * @author      Kevin Provance (kprovance)
 * @version     1.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
    exit;
}

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_multi_media' ) ) {

    /**
     * Main ReduxFramework_multi_media class
     *
     * @since       1.0.0
     */
    class ReduxFramework_multi_media {

      /**
       * Class Constructor. Defines the args for the extions class
       *
       * @since       1.0.0
       * @access      public
       * @param       array $field  Field sections.
       * @param       array $value  Values.
       * @param       array $parent Parent object.
       * @return      void
       */
        public function __construct( $field = array(), $value ='', $parent ) {

            // Set required variables
            $this->parent   = $parent;
            $this->field    = $field;
            $this->value    = $value;

            // Set extension dir & url
            if ( empty( $this->extension_dir ) ) {
                $this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
                $this->extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->extension_dir ) );
            }
        }

        /**
         * Field Render Function.
         *
         * Takes the vars and outputs the HTML for the field in the settings
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function render() {
            $field_id       = $this->field['id'];
            $dev_mode       = $this->parent->args['dev_mode'];
            $opt_name       = $this->parent->args['opt_name'];
            $dev_tag        = '';
            $button_text    = isset($this->field['labels']['button']) ? $this->field['labels']['button'] : __('Add or Upload File(s)', 'redux-framework');
            $max_file_count = isset($this->field['max_file_upload']) ? $this->field['max_file_upload'] : 0;

            // Set library filter data, if it's set.
            if (!isset($this->field['library_filter'])) {
                $libFilter = '';
            } else {
                if (!is_array($this->field['library_filter'])) {
                    $this->field['library_filter'] = array($this->field['library_filter']);
                }

                $mimeTypes = get_allowed_mime_types();

                $libArray = $this->field['library_filter'];

                $jsonArr = array();

                // Enum mime types
                foreach ($mimeTypes as $ext => $type) {
                    if (strpos($ext,'|')) {
                        $expArr = explode('|', $ext);

                        foreach($expArr as $ext){
                            if (in_array($ext, $libArray )) {
                                $jsonArr[$ext] = $type;
                            }
                        }
                    } elseif (in_array($ext, $libArray )) {
                        $jsonArr[$ext] = $type;
                    }

                }

                // Encode for transist to JS
                $libFilter = urlencode(json_encode($jsonArr));
            }

            // Set dev_mode data, if active.
            if (true == $dev_mode) {
                $dev_tag = ' data-dev-mode="'    . $this->parent->args['dev_mode'] . '"
                            data-version="'      . ReduxFramework_extension_multi_media::$version . '"';
            }

            // primary container
            echo
            '<div
                class="redux-multi-media-container' . $this->field['class'] . '"
                id="' . $field_id . '"
                data-max-file-upload="'             . $max_file_count . '"
                data-id="'                          . $field_id . '"' .
                $dev_tag . '
                data-dev-mode="'                    . $this->parent->args['dev_mode'] . '"
                data-version="'                     . ReduxFramework_extension_multi_media::$version . '"
            >';

            // Library filter
            echo '<input type="hidden" class="library-filter" data-lib-filter="' . $libFilter . '" />';

            // Hidden inout for file(s).
            echo
            '<input
                name="' . $opt_name . '[' . $field_id . ']"
                id="' . $field_id . '-multi-media"
                class="redux_upload_file redux_upload_list"
                type="hidden"
                value=""
                size="45"
            />';

            // Upload button
            echo
            '<input
                type="button"
                class="redux_upload_button button redux_upload_list"
                name=""
                id=""
                value="' . $button_text . '"
            />';

            // list container
            echo '<ul id="' . $opt_name . '_' . $field_id . '_status" class="redux_media_status attach_list">';

            $fileArr  = array();
            $imgArr   = array();
            $allArr   = array();
            
            // Check for file entries in array format
            if ($this->value && is_array($this->value)) {

                // Enum existing file exntries
                foreach($this->value as $id => $url){

                    // hidden ID input
                    $id_input =
                    '<input
                        type="hidden"
                        value="' . $url . '"
                        name="' . $opt_name . '[' . $field_id . '][' . $id . ']"
                        id="filelist-' . $id .'"
                        class=""
                    />';

                    // Check for valud image extension
                    if ( $this->is_valid_img_ext( $url ) ) {

                        // Add image to array
                        $imgArr[] = 
                        '<li class="img_status">' .
                            wp_get_attachment_image( $id, array(50, 50) ) .
                            '<p class="redux_remove_wrapper"><a href="#" class="redux_remove_file_button">'. __( 'Remove Image', 'redux-framework' ) .'</a></p>
                            '. $id_input .'
                        </li>';

                    // No image?  Output standard file info9
                    } else {

                        // Get parts of URL
                        $parts = explode( '/', $url );

                        // Get the filename.
                        for ( $i = 0; $i < count( $parts ); ++$i ) {
                            $title = $parts[$i];
                        }

                        // Add file to array
                        $fileArr[] = 
                        '<li>' .
                            __( 'File: ', 'redux-framework' ) . ' <strong>' . $title . '</strong>&nbsp;&nbsp;&nbsp; (<a href="' . $url . '" target="_blank" rel="external">' . __( 'Download', 'redux-framework' ) .'</a> / <a href="#" class="redux_remove_file_button">'. __( 'Remove', 'redux-framework' ) .'</a>)
                            '. $id_input .'
                        </li>';
                    }
                }
            }

            // Push images onto array stack
            if (!empty($imgArr)) {
                foreach($imgArr as $idx => $html) {
                    array_push($allArr, $html);
                }
            }

            // Push files onto array stack
            if (!empty($fileArr)) {
                foreach($fileArr as $idx => $html) {
                    array_push($allArr, $html);
                }
            }

            // Output array to page.
            if (!empty($allArr)) {
                foreach($allArr as $idx => $html) {
                    echo $html;
                }
            }
            
            // Close list
            echo '</ul>';

            // Close container
            echo '</div>';
        }

	/**
	 * Determine a file's extension
	 * @since  1.0.0
	 * @param  string           $file File url
	 * @return string|false     File extension or false
	 */
	private function get_file_ext( $file ) {
            $parsed = @parse_url( $file, PHP_URL_PATH );

            return $parsed ? strtolower( pathinfo( $parsed, PATHINFO_EXTENSION ) ) : false;
	}

	/**
	 * Determines if a file has a valid image extension
	 * @since  1.0.0
	 * @param  string $file File url
	 * @return bool         Whether file has a valid image extension
	 */
	private function is_valid_img_ext( $file ) {
            $file_ext = $this->get_file_ext( $file );

            $valid = empty( $valid ) ? (array) apply_filters( 'redux_valid_img_types', array( 'jpg', 'jpeg', 'png', 'gif', 'ico', 'icon' ) ) : $valid;

            return ( $file_ext && in_array( $file_ext, $valid ) );
	}

        /**
         * Enqueue Function.
         *
         * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function enqueue() {
            $extension = ReduxFramework_extension_multi_media::getInstance();

            // Get labels for localization
            $upload_file    = isset($this->field['labels']['upload_file'])  ? $this->field['labels']['upload_file']     : __('Select File(s)', 'redux-framework');
            $remove_image   = isset($this->field['labels']['remove_image']) ? $this->field['labels']['remove_image']    : __('Remove Image', 'redux-framework');
            $remove_file    = isset($this->field['labels']['remove_file'])  ? $this->field['labels']['remove_file']     : __('Remove', 'redux-framework');
            $file_label     = isset($this->field['labels']['file'])         ? $this->field['labels']['file']            : __('File: ', 'redux-framework');
            $download_label = isset($this->field['labels']['download'])     ? $this->field['labels']['download']        : __('Download', 'redux-framework');
            $media_title    = isset($this->field['labels']['title'])        ? $this->field['labels']['title']           : $this->field['title'];
            $dup_warn       = isset($this->field['labels']['duplicate'])    ? $this->field['labels']['duplicate']       : __('%s already exists in your file queue.','redux-framework');
            $max_warn       = isset($this->field['labels']['max_limit'])    ? $this->field['labels']['max_limit']       : __('Maximum upload limit of %s reached/exceeded.','redux-framework');

            // Set up min files for dev_mode = false.
            $min = Redux_Functions::isMin();

            // Field dependent JS
            wp_enqueue_script(
                'redux-field-multi-media-js',
                $this->extension_url . 'field_multi_media' . $min . '.js',
                array('jquery'),
                time(),
                true
            );

            // Field CSS
            wp_enqueue_style(
                'redux-field-multi-media-css',
                $this->extension_url . 'field_multi_media.css',
                time(),
                true
            );

            // Localization
            wp_localize_script( 'redux-field-multi-media-js', 'redux_multi_media_l10', apply_filters( 'redux_multi_media_localized_data', array(
                'upload_file'   => $upload_file,
                'remove_image'  => $remove_image,
                'remove_file'   => $remove_file,
                'file'          => $file_label,
                'download'      => $download_label,
                'title'         => $media_title,
                'dup_warn'      => $dup_warn,
                'max_warn'      => $max_warn
            ) ) );
        }
    }
}