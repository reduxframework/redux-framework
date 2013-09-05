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
 * @subpackage  Field_Images
 * @author      Daniel J Griffiths (Ghost1227)
 * @author      Dovy Paukstys
 * @version     3.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_images' ) ) {

    /**
     * Main ReduxFramework_images class
     *
     * @since       1.0.0
     */
    class ReduxFramework_images extends ReduxFramework {
    
        /**
         * Field Constructor.
         *
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function __construct( $field = array(), $value = '', $parent = '' ) {
        
            parent::__construct( $parent->sections, $parent->args, $parent->extra_tabs );

            $this->field = $field;
            $this->value = $value;
        
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
        
            $class = ( isset( $this->field['class'] ) ) ? ' ' . $this->field['class'] . '" ' : '';

            if( !empty( $this->field['compiler'] ) && $this->field['compiler'] )
                $class .= ' compiler';
        
            echo '<fieldset>';
            
            if( !empty( $this->field['options'] ) ) {

                echo '<ul class="redux-images">';
            
                foreach( $this->field['options'] as $k => $v ) {

                    if( !is_array( $v ) )
                        $v = array( 'img' => $v );

                    if( !isset( $v['title'] ) )
                        $v['title'] = '';

                    if( !isset( $v['alt'] ) )
                        $v['alt'] = $v['title'];

                    $style = '';

                    if( !empty( $this->field['width'] ) ) {
                        $style .= 'width: ' . $this->field['width'];
                    
                        if( is_numeric( $this->field['width'] ) )
                            $style .= 'px';
                    
                        $style .= ';';
                    }

                    if( !empty( $this->field['height'] ) ) {
                        $style .= 'height: ' . $this->field['height'];

                        if( is_numeric( $this->field['height'] ) )
                            $style .= 'px';

                        $style .= ';';
                    }

                    $theValue = $k;
                
                    if( !empty( $this->field['tiles'] ) && $this->field['tiles'] == true )
                        $theValue = $v['img'];

                    $selected = ( checked( $this->value, $theValue, false ) != '' ) ? ' redux-images-selected' : '';

                    $presets = '';
                
                    if( !empty( $this->field['presets'] ) && $this->field['presets'] && !empty( $v['presets'] ) ) {
                    
                        if( !is_array( $v['presets'] ) )
                            $v['presets'] = json_decode( $v['presets'], true );

                        $v['presets']['redux-backup'] = 1;

                        $presets = ' data-presets="' . htmlspecialchars( json_encode( $v['presets'] ), ENT_QUOTES, 'UTF-8' ) . '"';
                        $selected = '';
                        $class .= ' redux-presets';
                    }               

                    echo '<li class="redux-images">';
                    echo '<label class="' . $selected . ' redux-images-' . $this->field['id'] . '" for="' . $this->field['id'] . '_' . array_search( $k, array_keys( $this->field['options'] ) ) . '">';

                    echo '<input type="radio" class="noUpdate' . $class . '" id="' . $this->field['id'] . '_' . array_search( $k, array_keys( $this->field['options'] ) ) . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . ']" value="' . $theValue . '" ' . checked( $this->value, $theValue, false ) . $presets . '/>';

                    if( !empty( $this->field['tiles'] ) && $this->field['tiles'] == true ) {
                        echo '<span class="tiles" style="background-image: url(' . $v['img'] . ');">&nbsp;</span>';
                    } else {
                        echo '<img src="' . $v['img'] . '" alt="' . $v['alt'] . '" style="' . $style . '"' . $presets . ' />';
                    }
                
                    if ( $v['title'] != '' )
                        echo '<br /><span>' . $v['title'] . '</span>';  

                    echo '</label>';
                    echo '</li>';
                }
                
                echo '</ul>';       
            
                if( !empty( $this->field['presets'] ) && $this->field['presets'] )
                    echo '<div class="redux-presets-bar"><input type="button" class="redux-save-preset button-primary" value="' . __( 'Load Preset', 'redux-framework' ) . '"></div>';

            }

            echo '</fieldset>';

            echo ( isset( $this->field['description'] ) && !empty( $this->field['description'] ) ) ? '<div class="description">' . $this->field['description'] . '</div>' : '';
        
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
                'redux-field-images-js', 
                REDUX_URL . 'inc/fields/images/field_images.min.js', 
                array( 'jquery' ),
                time(),
                true
            );

            wp_enqueue_style(
                'redux-field-images-css', 
                REDUX_URL . 'inc/fields/images/field_images.css',
                time(),
                true
            );
        
        }
    }
}
?>