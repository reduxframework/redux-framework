<?php

/**
 * @package     Redux Framework
 * @subpackage  JS Button
 * @author      Kevin Provance (kprovance)
 * @version     1.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
    exit;
}

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_js_button' ) ) {

    /**
     * Main ReduxFramework_js_button class
     *
     * @since       1.0.0
     */
    class ReduxFramework_js_button {

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
            $dev_tag        = '';
            
            // Button text
            $button_text    = isset($this->field['button_text']) ? $this->field['button_text'] : '';
            
            // JS function entry point in passed script
            $func_name      = isset($this->field['script']['function']) ? $this->field['script']['function'] : '';// 'redux_js_button_click';

            // Set dev_mode data, if active.
            if (true == $dev_mode) {
                $dev_tag = ' data-dev-mode="'    . $this->parent->args['dev_mode'] . '"
                            data-version="'      . ReduxFramework_extension_js_button::$version . '"';
            }

            // primary container
            echo
            '<div
                class="redux-js-button-container' . $this->field['class'] . '"
                id="'               . $field_id . '"
                data-function="'    . $func_name . '"
                data-id="'          . $field_id . '"' .
                $dev_tag            . '
                style="width: 0px;"
            >';

            // Button render.
            echo
            '<input
                id="' . $field_id . '"
                class="hide-if-no-js button"
                type="button"
                value="' . $button_text . '"
            />';

            // Close container
            echo '</div>';
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
            $extension = ReduxFramework_extension_js_button::getInstance();

            // Make sure script data exists first
            if (isset($this->field['script']) && !empty($this->field['script'])){
                
                // URI location of script to enqueue
                $script_url     = isset($this->field['script']['url']) ? $this->field['script']['url'] : '';
                
                // Get deps, if any
                $script_dep     = isset($this->field['script']['dep']) ? $this->field['script']['dep'] : array();
                
                // Get ver, if any
                $script_ver     = isset($this->field['script']['ver']) ? $this->field['script']['ver'] : time();
                
                // Script location in HTML
                $script_footer  = isset($this->field['script']['in_footer']) ? $this->field['script']['in_footer'] : true;
                
                // If a script exists, enqueue it.
                if ($script_url != '') {
                    wp_enqueue_script(
                        'redux-js-button-' . $this->field['id'] . '-js',
                        $script_url,
                        $script_dep,
                        $script_ver,
                        $script_footer
                    );                    
                }
            }
            
            // Set up min files for dev_mode = false.
            $min = Redux_Functions::isMin();

            // Field dependent JS
            wp_enqueue_script(
                'redux-field-js-button-js',
                $this->extension_url . 'field_js_button' . $min . '.js',
                array('jquery'),
                time(),
                true
            );
        }
    }
}