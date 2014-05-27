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
 * @subpackage  Field_Custom_Fonts
 * @author      Dovy Paukstys (dovy)
 * @version     1.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_custom_fonts' ) ) {

    /**
     * Main ReduxFramework_custom_fonts class
     *
     * @since       1.0.0
     */
    class ReduxFramework_custom_fonts {
    
        /**
         * Field Constructor.
         *
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        function __construct( $field = array(), $value ='', $parent ) {
        
            $this->parent = $parent;
            $this->parent->fontControl = true;
            $this->field = $field;
            $this->value = $value;
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

            echo '</fieldset></td></tr><tr><td colspan="2"><fieldset class="redux-field redux-container-custom_font">';

            $nonce = wp_create_nonce("redux_{$this->parent->args['opt_name']}_custom_fonts");

            // No errors please
            $defaults = array(
                'id'        => '',
                'url'       => '',
                'width'     => '',
                'height'    => '',
                'thumbnail' => '',
            );

            $this->value = wp_parse_args( $this->value, $defaults );



            $this->field['custom_fonts'] = apply_filters("redux/{$this->parent->args['opt_name']}/field/typography/custom_fonts", array());


            
            if (!empty($this->field['custom_fonts'])) {
                
                foreach($this->field['custom_fonts'] as $section => $fonts) {
                        if (empty($fonts)) {
                            continue;
                        }

                        echo '<h3>'.$section.'</h3>';
                        echo '<table class="wp-list-table widefat plugins" cellspacing="0"><tbody>';    

                        foreach($fonts as $font => $pieces) {

                            echo '<tr class="active">';
                            echo '<td class="plugin-title" style="min-width: 40%"><strong>'.$font.'</strong></td>';
                            echo '<td class="column-description desc"><div class="plugin-description">';
                            if (!empty($pieces)) {
                                foreach ( $pieces as $piece ) {
                                    echo "<span class=\"button button-primary button-small font-pieces\">{$piece}</span> ";
                                }
                            }
                            echo '</div></td><td style="width: 140px;"><div class="action-row visible"><span style="display:none;"><a href="#" class="rename">Rename</a> | </span><a href="#" class="fontDelete delete" data-section="'.$section.'" data-name="'.$font.'" data-type="delete">Delete</a><span class="spinner" style="display: none;"></span></div></td></tr>';
                        }
                        echo '</tbody></table>';
                        echo '<div class="upload_button_div"><span class="button media_add_font" data-nonce="' . $nonce . '" id="' . $this->field['id'] . '-custom_fonts">' . __( 'Add Font', 'redux-framework' ) . '</span></div><br />';

                    
                }
                
            } else {
                echo "<h3>".__('No Custom Fonts Found', 'redux-framework')."</h3>";
                echo '<div class="upload_button_div"><span class="button media_add_font" data-nonce="' . $nonce . '" id="' . $this->field['id'] . '-custom_fonts">' . __( 'Add Font', 'redux-framework' ) . '</span></div>';
            }

            echo '</fieldset></td></tr>';
            
        }

            /**
             * 
             * Functions to pass data from the PHP to the JS at render time.
             * 
             * @return array Params to be saved as a javascript object accessable to the UI.
             * 
             * @since  Redux_Framework 3.1.1
             * 
             */
            function localize($field, $value = "") {
                
                $params = array();

                if ( !isset( $field['mode'] ) ) {
                    $field['mode'] = "image";
                }          
                $params['mode'] = $field['mode'];

                if ( empty( $value ) && isset( $this->value ) ) {
                    $value = $this->value;
                }   
                $params['val'] = $value;

                return $params;
          
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

            wp_enqueue_script(
                'redux-blockUI',
                $this->extension_url . '/jquery.blockUI.js',
                array( 'jquery' ),
                time(),
                true
            );  

            wp_enqueue_script(
                'redux-field-custom_fonts-js',
                $this->extension_url . '/field_custom_fonts.js',
                array( 'jquery', 'redux-blockUI' ),
                time(),
                true
            );

          

            wp_enqueue_style(
                'redux-field-custom_fonts-css',
                $this->extension_url . 'field_custom_fonts.css',
                time(),
                true
            );

            $class = ReduxFramework_extension_custom_fonts::get_instance();
            if ( !empty( $class->custom_fonts ) ) {
                wp_enqueue_style(
                    'redux-custom_fonts-css',
                    $class->upload_url . "fonts.css",
                    time(),
                    true
                );                
            }   

        }
    }
}
