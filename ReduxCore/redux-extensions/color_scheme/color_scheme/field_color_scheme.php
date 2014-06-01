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

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_color_scheme' ) ) {

    /**
     * Main ReduxFramework_color_scheme class
     *
     * @since       1.0.0
     */
    class ReduxFramework_color_scheme {
    
      /**
       * Class Constructor. Defines the args for the extions class
       *
       * @since       1.0.0
       * @access      public
       * @param       array $field Field sections.
       * @param       array $value Values.
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

            // Validate
            $this->field['options']['show_input']              = isset($this->field['options']['show_input']) ? $this->field['options']['show_input'] : true;
            $this->field['options']['show_initial']            = isset($this->field['options']['show_initial']) ? $this->field['options']['show_initial'] : true;
            $this->field['options']['show_alpha']              = isset($this->field['options']['show_alpha']) ? $this->field['options']['show_alpha'] : true;
            $this->field['options']['show_palette']            = isset($this->field['options']['show_palette']) ? $this->field['options']['show_palette'] : true;
            $this->field['options']['show_palette_only']       = isset($this->field['options']['show_palette_only']) ? $this->field['options']['show_palette_only'] : false;
            $this->field['options']['max_palette_size']        = isset($this->field['options']['max_palette_size']) ? $this->field['options']['max_palette_size'] : 10;
            $this->field['options']['show_selection_palette']  = isset($this->field['options']['show_selection_palette']) ? $this->field['options']['show_selection_palette'] : true;
            $this->field['options']['allow_empty']             = isset($this->field['options']['allow_empty']) ? $this->field['options']['allow_empty'] : true;
            $this->field['options']['clickout_fires_change']   = isset($this->field['options']['clickout_fires_change']) ? $this->field['options']['clickout_fires_change'] : false;
            $this->field['options']['choose_text']             = isset($this->field['options']['choose_text']) ? $this->field['options']['choose_text'] : 'Choose';
            $this->field['options']['cancel_text']             = isset($this->field['options']['cancel_text']) ? $this->field['options']['cancel_text'] : 'Cancel';
            $this->field['options']['show_buttons']            = isset($this->field['options']['show_buttons']) ? $this->field['options']['show_buttons'] : true;
            $this->field['options']['container_class']         = isset($this->field['options']['container_class']) ? $this->field['options']['container_class'] : 'redux-colorpicker-container';
            $this->field['options']['replacer_class']          = isset($this->field['options']['replacer_class']) ? $this->field['options']['replacer_class'] : 'redux-colorpicker-replacer';
            $this->field['options']['use_extended_classes']    = isset($this->field['options']['use_extended_classes']) ? $this->field['options']['use_extended_classes'] : false;
            $this->field['options']['palette']                 = isset($this->field['options']['palette']) ? $this->field['options']['palette'] : null;
            $this->field['simple']                             = isset($this->field['simple']) ? $this->field['simple'] : false;
            
            // Convert empty array to null, if there.
            $this->field['options']['palette'] = empty($this->field['options']['palette']) ? null : $this->field['options']['palette'];
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
            global $wp_filesystem;
            
            $field_id = $this->field['id'];
            
            // Nonce
            $nonce = wp_create_nonce("redux_{$this->parent->args['opt_name']}_color_schemes");

            // Modal message
            echo '<div id="redux-' . $field_id . '-scheme-message-notice" style="display:none; cursor: default">'; 
            echo '    <h2>message</h2>';
            echo '    <input type="button" id="redux-' . $field_id . '-scheme-ok" value="OK" />'; 
            echo '</div>'; 
            
            // Waiting message
            echo '<div id="redux-' . $field_id . '-scheme-wait-message" style="display:none;">';
            echo '   <h1><img src="' . $this->extension_url . 'img/busy.gif" /> Please wait...</h1>';
            echo '</div>';
            
            // Delete dialog
            echo '<div id="redux-' . $field_id . '-delete-scheme-question" style="display:none; cursor: default">'; 
            echo '    <h2>Are you sure you want to delete this scheme?</h2>';
            echo '    <input type="button" id="redux-' . $field_id . '-delete-scheme-yes" value="Yes" />'; 
            echo '    <input type="button" id="redux-' . $field_id . '-delete-scheme-no" value="No" />'; 
            echo '</div>';            
            
            $dev_mode = $this->parent->args['dev_mode'];
            
            $dev_tag = '';
            if (true == $dev_mode) {
                
                $dev_tag = ' data-dev-mode="'    . $this->parent->args['dev_mode'] . '"
                            data-version="'      . ReduxFramework_extension_color_scheme::$version . '"';
            }
            
            $classes_tag = '';
            $class_css    = Redux_Helpers::cleanFilePath(get_stylesheet_directory()) . '/redux-color-schemes.css';
            
            if (true == $this->field['options']['use_extended_classes']) {
                
                $container_class    = $this->field['options']['container_class'];
                $replacer_class     = $this->field['options']['replacer_class'];
                
                if (!file_exists($class_css)){
                    $css_data = '/* CSS file to set your Container and Replacer CSS
Please visit http://docs.reduxframework.com/premium-extensions/color-schemes 
for more information on how to use this file. 
Auto generated on ' . date('l jS \of F Y h:i:s A') . ' */
                            
.' . $container_class . ' {
    
}

.' . $replacer_class . ' {
    
}
';
                    $wp_filesystem->put_contents($class_css, $css_data, FS_CHMOD_FILE);
                }
                
                $classes_tag = ' data-container-class="'           . $container_class . '"
                                data-replacer-class="'             . $replacer_class . '"';
            } else {
                if (file_exists($class_css)){
                    unlink($class_css);
                }                
            }
            
            // Color picker container
            echo '<div 
                      class="redux-color-scheme-container" 
                      data-id="'                        . $field_id . '" 
                      data-nonce="'                     . $nonce . '"' . 
                      $dev_tag . '
                      data-dev-mode="'                  . $this->parent->args['dev_mode'] . '"
                      data-version="'                   . ReduxFramework_extension_color_scheme::$version . '"
                      data-show-input="'                . $this->field['options']['show_input'] . '"
                      data-show-initial="'              . $this->field['options']['show_initial'] . '"
                      data-show-alpha="'                . $this->field['options']['show_alpha'] . '"
                      data-show-palette="'              . $this->field['options']['show_palette'] . '"
                      data-show-palette-only="'         . $this->field['options']['show_palette_only'] . '"
                      data-show-selection-palette="'    . $this->field['options']['show_selection_palette'] . '"
                      data-max-palette-size="'          . $this->field['options']['max_palette_size'] . '"
                      data-allow-empty="'               . $this->field['options']['allow_empty'] . '"
                      data-clickout-fires-change="'     . $this->field['options']['clickout_fires_change'] . '"
                      data-choose-text="'               . $this->field['options']['choose_text'] . '"
                      data-cancel-text="'               . $this->field['options']['cancel_text'] . '"
                      data-show-buttons="'              . $this->field['options']['show_buttons'] . '"' .
                      $classes_tag . '
                      data-palette="'                   . urlencode(json_encode($this->field['options']['palette'])) . '"
                  >';
            
            // Hide scheme save stuff on simple mode
            if (false == $this->field['simple']) {
                // Select container
                echo '<div class="redux-scheme-select-container">';
                echo '    <label for="redux-scheme-select-' . $field_id . '" class="redux-select-scheme-label">Scheme:';

                // Set field ID, just in case
                ReduxColorSchemeFunctions::$_field_id = $field_id;

                // Output scheme selector
                echo ReduxColorSchemeFunctions::getSchemeSelectHTML('');

                echo '    </label>';
                echo '</div>';

                // Text input
                echo '<div class="redux-scheme-name">';
                echo '  <label for="redux-scheme-input-' . $field_id . '" class="redux-text-scheme-label">Name:';
                echo '      <input 
                                type="text" 
                                class="noUpdate redux-scheme-input-' . $field_id . '"
                                id="redux-scheme-input"
                            />';
                echo    '</label>';
                //echo '  &nbsp;&nbsp;';

                // Action buttons/links
                echo '  <div class="redux-action-links">';
                echo '      <label for="redux-' . $field_id . '-save-scheme-button" class="redux-action-scheme-label">Actions:';

                // Save button
                echo '          <a 
                                    href="javascript:void(0);" 
                                    id="redux-' . $field_id . '-save-scheme-button" 
                                    class="redux-save-scheme-button button-secondary">' . __( 'Add', 'redux-framework' ) . '
                                </a>';

                // Delete button
                echo '          <a 
                                    href="javascript:void(0);" 
                                    id="redux-' . $field_id . '-delete-scheme-button" 
                                    class="redux-delete-scheme-button button-secondary">' . __( 'Delete', 'redux-framework' ) . '
                                </a>';

                $link = admin_url('admin-ajax.php?action=redux_color_schemes&type=export&nonce=' . $nonce);

                // Export button
                echo '          <a 
                                    href="' . $link . '" 
                                    id="redux-' . $field_id . '-export-scheme-button" 
                                    data-opt-name="' . $this->parent->args['opt_name'] . '" 
                                    data-submit="' . $this->extension_url . '" 
                                    class="redux-export-scheme-button button-primary">' . __( 'Export', 'redux-framework' ) . '
                                </a>';

                // Import button
                echo '          <a 
                                    href="javascript:void(0);" 
                                    id="redux-' . $field_id . '-import-scheme-button" 
                                    data-submit="' . $this->extension_url . '" 
                                    class="noUpdate redux-import-scheme-button button-secondary">' . __( 'Import', 'redux-framework' ) . '
                                </a>';
                echo '      </label>';
                echo '  </div>';
                echo '</div>';
                echo '<hr/>';
            }
        
            // Set field class.  Gotta do it this way so custsom class makes
            // it through AJAX.
            ReduxColorSchemeFunctions::$_field_class = 'redux-color-scheme ' . $this->field['class'];
            
            // Colour picker layout
            echo ReduxColorSchemeFunctions::getCurrentColorSchemeHTML();
                    
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

            $extension = ReduxFramework_extension_color_scheme::getInstance();
            
            // Set up min files for dev_mode = false.
            $min = '';
            if (false == $this->parent->args['dev_mode']) {
                $min = '.min';
            }
            
            // One-Click Upload
            wp_enqueue_script(
                'redux-ocupload',
                $this->extension_url . 'vendor/jquery.ocupload' . $min . '.js',
                array( 'jquery' ),
                time(),
                true
            ); 
            
            // BlockUI
            wp_enqueue_script(
                'redux-blockUI',
                $this->extension_url . 'vendor/jquery.blockUI' . $min . '.js',
                array( 'jquery' ),
                time(),
                true
            );            
            
            // Spectrum colour picker
            wp_enqueue_script(
                'redux-spectrum-js', 
                $this->extension_url . 'vendor/spectrum' . $min . '.js', 
                array('jquery'), 
                time(), 
                true
            );

            // Spectrum CSS
            wp_enqueue_style(
                'redux-spectrum-css', 
                $this->extension_url . 'vendor/spectrum' . $min . '.css', 
                time(), 
                true
            );
            
            // Field dependent JS
            wp_enqueue_script(
                'redux-field-color_scheme-js', 
                $this->extension_url . 'field_color_scheme' . $min . '.js', 
                array('jquery', 'redux-spectrum-js'), 
                time(), 
                true
            );

            // Field CSS
            wp_enqueue_style(
                'redux-field-color_scheme-css', 
                $this->extension_url . 'field_color_scheme' . $min . '.css', 
                time(), 
                true
            );
            
            // Color picker class
            if (true == $this->field['options']['use_extended_classes']) {
                $css_file = Redux_Helpers::cleanFilePath(get_stylesheet_directory()) . '/redux-color-schemes.css';
                if (file_exists($css_file)) {
                    wp_enqueue_style(
                        'redux-color_scheme-class-css', 
                        Redux_Helpers::cleanFilePath(get_stylesheet_directory_uri()) . '/redux-color-schemes.css', 
                        time(), 
                        true
                    );                
                }
            }
            
            // AJAX
            wp_localize_script( 
                'redux-field-color_scheme-js', 
                'redux_ajax_script', 
                array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) 
            );
        }

        /**
         * getCSS.  Returns formatted CSS based on color picker table args.
         *
         * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
         *
         * @since       1.0.0
         * @access      private
         * @return      string
         */        
        private function getCSS(){
            
            // No notices
            $css = '';
            
            // Must be an array
            if (is_array($this->value)) {

                // Enum array to parse values
                foreach($this->value as $id => $val) {
                    
                    // Sanitize alpha
                    $alpha = isset($val['alpha']) ? $val['alpha'] : 1;
                    
                    // Sanitize color
                    $color = isset($val['color']) ? $val['color'] : '';

                    // Sanitize selector
                    $selector   = isset($val['selector']) ? $val['selector'] : '';
                    
                    // Santize mode, default to 'color'.
                    $mode       = isset($val['mode']) ? $val['mode'] : 'color';

                    $important  = isset($val['important']) ? $val['important'] : false;
                    if (true == $important) {
                        $important = ' !important';
                    } else {
                        $important = '';
                    }
                    
                    // Only build rgba output if alpha ia less than 1
                    if ( $alpha < 1 && $alpha <> '' ) {
                        $color = Redux_Helpers::hex2rgba($color, $alpha);
                    }
                    
                    // Only build value if selector is indicated
                    if ('' <> $selector && '' <> $color) {
                        $css .= $selector . '{' . $mode . ': ' . $color . $important . ';} ';
                    }
                }
            }
            
            return $css;
        }
        
        /**
         * Output Function.
         *
         * Used to enqueue to the front-end
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */        
        public function output() {
            if (!empty($this->value)) {

                if (!empty($this->field['output']) && (true == $this->field['output'])) {
                    $css = $this->getCSS();
                    $this->parent->outputCSS .= $css;
                }

                if (!empty($this->field['compiler']) && (true == $this->field['compiler'])) {
                    $css = $this->getCSS();
                    $this->parent->compilerCSS .= $css ;
                }
            }
        }
    }
}
