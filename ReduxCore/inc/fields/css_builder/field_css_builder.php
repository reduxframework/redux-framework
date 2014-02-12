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
* @subpackage  Field_css_builder
* @author      Daniel J Griffiths (Ghost1227)
* @author      Dovy Paukstys
*	@author	  Taha Paksu (tpaksu)
* @version     3.0.0
*/

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_css_builder' ) ) {

    /**
    * Main ReduxFramework_css_builder class
    *
    * @since       1.0.0
    */
    class ReduxFramework_css_builder extends ReduxFramework {

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

            //parent::__construct( $parent->sections, $parent->args );
            $this->parent = $parent;
            $this->field = $field;
            $this->value = $value;
            $this->properties = array();
            $json_decoded_properties = json_decode(file_get_contents(dirname(__FILE__)."/css_properties.list"),true);
            foreach($json_decoded_properties as $css_section => $css_values){
                foreach($css_values as $css_indx=>$css_value){
                    $indx = count($this->properties);
                    $this->properties[$indx] = array();
                    $this->properties[$indx]["property"] = $css_value["property"];
                    $this->properties[$indx]["group"] = $css_section;
                    $this->properties[$indx]["description"] = $css_value["description"];
                }
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

            $this->add_text = ( isset($this->field['add_text']) ) ? $this->field['add_text'] : __( 'Add More', 'redux-framework');

            $this->show_empty = ( isset($this->field['show_empty']) ) ? $this->field['show_empty'] : true;

            echo '<ul id="' . $this->field['id'] . '-ul" class="redux-css-builder">';

            if( isset( $this->value ) && is_array( $this->value ) ) {
                foreach( $this->value['properties'] as $k => $value ) {
                    if( $value != '' ) {
                        echo '<li><select class="redux-select-item redux-css-builder-select noUpdate" name="' . $this->field['name'] . '[properties]['.$k.']">'.$this->build_css_properties_options($this->value['properties'][$k]).'</select>&nbsp;<input type="text" id="' . $this->field['id'] . '-' . $k . '" name="' . $this->field['name'] . '[values]['.$k.']" value="' . esc_attr( $this->value['values'][$k] ) . '" class="regular-text ' . $this->field['class'] . '" /> <a href="javascript:void(0);" class="deletion redux-css-builder-remove">' . __( 'Remove', 'redux-framework' ) . '</a></li>';
                    }
                }
            } elseif($this->show_empty == true ) {
                echo '<li><select class="redux-select-item redux-css-builder-select noUpdate" name="' . $this->field['name'] . '[properties][]">'.$this->build_css_properties_options().'</select>&nbsp;<input type="text" id="' . $this->field['id'] . '-0" name="' . $this->field['name'] . '[values][]" value="" class="regular-text ' . $this->field['class'] . '" /> <a href="javascript:void(0);" class="deletion redux-css-builder-remove">' . __( 'Remove', 'redux-framework' ) . '</a></li>';
            }

            echo '<li style="display:none;"><select class="redux-css-dummy-select redux-css-builder-select noUpdate" name="">'.$this->build_css_properties_options().'</select>&nbsp;<input type="text" id="' . $this->field['id'] . '" name="" value="" class="regular-text" /> <a href="javascript:void(0);" class="deletion redux-css-builder-remove">' . __( 'Remove', 'redux-framework') . '</a></li>';

            echo '</ul>';
            $this->field['add_number'] = ( isset( $this->field['add_number'] ) && is_numeric( $this->field['add_number'] ) ) ? $this->field['add_number'] : 1;
            echo '<a href="javascript:void(0);" class="button button-primary redux-css-builder-add" data-add_number="'.$this->field['add_number'].'" data-id="' . $this->field['id'] . '-ul" data-name="' . $this->field['name'] . '">' . $this->add_text . '</a><br/>';

        }

        public function build_css_properties_options($selected_value = ""){
            $options = "";
            $last_option_group = "";
            $options_grouped = false;
            foreach($this->properties as $index => $keyword){
                if(isset($this->field["exclude_group"]) && in_array($keyword["group"],$this->field["exclude_group"])){
                    continue;
                }
                if($keyword["group"]!=$last_option_group){
                    if($index != 0){
                        $options .= "</optgroup>";
                    }
                    $options .= "<optgroup label='".$keyword["group"]."'>";
                    $options_grouped = true;
                    $last_option_group = $keyword["group"];
                }
                if($selected_value != "" && $keyword["property"] === $selected_value){
                    $options .= '<option data-description="'. addslashes($keyword["description"]) .'" value="'.$keyword["property"].'" selected>'.$keyword["property"].'</option>';
                }else{
                    $options .= '<option data-description="'. addslashes($keyword["description"]) .'" value="'.$keyword["property"].'">'.$keyword["property"].'</option>';
                }
            }
            if($options_grouped) $options.="</optgroup>";
            return $options;
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
            'redux-field-css-builder-js',
            ReduxFramework::$_url . 'inc/fields/css_builder/field_css_builder.js',
            array( 'jquery' ),
            time(),
            true
            );

            wp_enqueue_style(
            'redux-field-css-builder-css',
            ReduxFramework::$_url.'inc/fields/css_builder/field_css_builder.css',
            time(),
            true
            );

        }
    }
}
