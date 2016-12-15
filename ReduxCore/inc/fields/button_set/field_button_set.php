<?php

    /**
     * Redux Framework is free software: you can redistribute it and/or modify
     * it under the terms of the GNU General Public License as published by
     * the Free Software Foundation, either version 2 of the License, or
     * any later version.
     * Redux Framework is distributed in the hope that it will be useful,
     * but WITHOUT ANY WARRANTY; without even the implied warranty of
     * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
     * GNU General Public License for more details.
     * You should have received a copy of the GNU General Public License
     * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
     *
     * @package     Redux_Field
     * @subpackage  Button_Set
     * @author      Daniel J Griffiths (Ghost1227)
     * @author      Dovy Paukstys
     * @author      Kevin Provance (kprovance)
     * @version     3.0.0
     */

// Exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

// Don't duplicate me!
    if ( ! class_exists( 'ReduxFramework_button_set' ) ) {

        /**
         * Main ReduxFramework_button_set class
         *
         * @since       1.0.0
         */
        class ReduxFramework_button_set {

            /**
             * Holds configuration settings for each field in a model.
             * Defining the field options
             * @param array $arr (See above)
             *
             * @return Object A new editor object.
             * */
            static $_properties = array(
                'id' => 'Identifier',
            );

            /**
             * Field Constructor.
             * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
             *
             * @since       1.0.0
             * @access      public
             * @return      void
             */
            function __construct( $field = array(), $value = '', $parent ) {

                $this->parent = $parent;
                $this->field  = $field;
                $this->value  = $value;
            }

            /**
             * Field Render Function.
             * Takes the vars and outputs the HTML for the field in the settings
             *
             * @since       1.0.0
             * @access      public
             * @return      void
             */
            public function render() {
                if ( !empty( $this->field['data'] ) && empty( $this->field['options'] ) ) {
                    if ( empty( $this->field['args'] ) ) {
                        $this->field['args'] = array();
                    }

                    $this->field['options'] = $this->parent->get_wordpress_data( $this->field['data'], $this->field['args'] );

                    if ( empty( $this->field['options'] ) ) {
                        return;
                    }
                }

                $is_multi = (isset( $this->field['multi'] ) && $this->field['multi'] == true) ? true: false;
                        
                $name = $this->field['name'] . $this->field['name_suffix'];
                
                // multi => true renders the field multi-selectable (checkbox vs radio)
                echo '<div class="buttonset ui-buttonset">';
                
                if ($is_multi) {
                    $s      = '';
                    
                    if (empty($this->value)) {
                        $s = $name;
                    }

                    echo '<input type="hidden" data-name="' . $name . '" class="buttonset-empty" name="' . $s . '" value=""/>';
                    
                    $name   = $name . '[]';
                }
                
                foreach ( $this->field['options'] as $k => $v ) {
                    $selected = '';

                    if ( $is_multi ) {
                        $post_value     = '';
                        $type           = "checkbox";

                        if ( ! empty( $this->value ) && ! is_array( $this->value ) ) {
                            $this->value = array( $this->value );
                        } 

                        if ( is_array( $this->value ) && in_array( $k, $this->value ) ) {
                            $selected = 'checked="checked"';
                            $post_value = $k;
                        }
                    } else {
                        $type         = "radio";

                        if ( is_scalar( $this->value ) ) {
                            $selected = checked( $this->value, $k, false );
                        }
                    }

                    $the_val    = $k;
                    $the_name   = $name;
                    $data_val   = '';
                    $multi_class = '';
                    
                    if ($is_multi) {
                        $the_val    = '';
                        $the_name   = '';
                        $data_val   = ' data-val="' . $k . '"';
                        $hidden_name = $name;
                        $multi_class = 'multi ';
                        
                        if ($post_value == '') {
                            $hidden_name = '';
                        }
                        
                        echo '<input type="hidden" class="buttonset-check" id="' . $this->field['id'] . '-buttonset' . $k . '-hidden" name="' .$hidden_name . '" value="' . $post_value . '"/>';
                    }
                    
                    echo '<input' . $data_val . ' data-id="' . $this->field['id'] . '" type="' . $type . '" id="' . $this->field['id'] . '-buttonset' . $k . '" name="' . $the_name . '" class="buttonset-item ' . $multi_class . $this->field['class'] . '" value="' . $the_val . '" ' . $selected . '/>';
                    echo '<label for="' . $this->field['id'] . '-buttonset' . $k . '">' . $v . '</label>';
                }

                echo '</div>';
            }

            /**
             * Enqueue Function.
             * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
             *
             * @since       1.0.0
             * @access      public
             * @return      void
             */
            public function enqueue() {

                if (!wp_script_is ( 'redux-field-button-set-js' )) {
                    wp_enqueue_script(
                        'redux-field-button-set-js',
                        ReduxFramework::$_url . 'inc/fields/button_set/field_button_set' . Redux_Functions::isMin() . '.js',
                        array( 'jquery', 'jquery-ui-core', 'redux-js' ),
                        time(),
                        true
                    );
                }
            }
        }
    }