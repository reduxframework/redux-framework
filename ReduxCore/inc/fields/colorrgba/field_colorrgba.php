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
 * @subpackage  Field_Color RGBA
 * @author      Sandro Bilbeisi
 * @version     3.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_colorrgba' ) ) {

    /**
     * Main ReduxFramework_color class
     *
     * @since       1.0.0
     */
	class ReduxFramework_colorrgba extends ReduxFramework {
	
		/**
		 * Field Constructor.
		 *
		 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
		 *
	 	 * @since 		1.0.0
	 	 * @access		public
	 	 * @return		void
		 */
        function __construct( $field = array(), $value =array(), $parent ) {
        
			parent::__construct( $parent->sections, $parent->args );
			$this->parent = $parent;
			$this->field = $field;
			$this->value = $value;
        
        }
	
		/**
		 * Field Render Function.
		 *
		 * Takes the vars and outputs the HTML for the field in the settings
	 	 *
	 	 * @since 		1.0.0
	 	 * @access		public
	 	 * @return		void
		 */
		public function render() {
		/*
			print_r($this->value);
			echo "<hr />";
			print_r($this->field);
			echo "<hr />";
		*/
			echo '<input data-id="'.$this->field['id'].'" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][hex]" id="' . $this->field['id'] . '-color" class="redux-colorrgba redux-colorrgba-init ' . $this->field['class'] . '"  type="text" value="' . $this->value['hex'] . '"  data-default-color="' . $this->field['default']['hex'] . '" data-defaultvalue="' . $this->field['default']['hex'] . '" data-opacity="' . $this->value['alpha'] .'" />';
			
			echo '<input data-id="'.$this->field['id'] . '-alpha" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][alpha]" id="' . $this->field['id'] . '-alpha" type="hidden" value="'.$this->value['alpha'].'" />';
			
			if ( !isset( $this->field['transparent'] ) || $this->field['transparent'] !== false ) {
				$tChecked = "";
				if ( $this->value == "transparent" ) {
					$tChecked = ' checked="checked"';
				}
				echo '<label for="' . $this->field['id'] . '-transparency" class="colorrgba-transparency-check"><input type="checkbox" class="checkbox color-transparency ' . $this->field['class'] . '" id="' . $this->field['id'] . '-transparency" data-id="'.$this->field['id'] . '-color" value="1"'.$tChecked.'> '.__('Transparent', 'redux-framework').'</label>';				
			}

		}
	

		public function output() {

			if (isset($this->field['output']) && !empty($this->field['output'])) {

				$keys = implode(",", $this->field['output']);
		        $style = '';
		        if ( !empty( $this->value ) ) {

		        	$style .= $keys."{";
		        	$style .= 'color:'.$this->value.';';
		        	$style .= '}';
		        	$this->parent->outputCSS .= $style;  
		        }
			}
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
				'redux-field-colorrgba-minicolors-js', 
				ReduxFramework::$_url.'assets/js/vendor/minicolors/jquery.minicolors.js',
				array( 'jquery' ),
				time(),
				true
			);
			wp_enqueue_script(
				'redux-field-colorrgba-js', 
				ReduxFramework::$_url . 'inc/fields/colorrgba/field_colorrgba.js', 
				array( 'jquery' ),
				time(),
				true
			);
		
			wp_enqueue_style(
				'redux-field-colorrgba-minicolors-css', 
				ReduxFramework::$_url.'assets/js/vendor/minicolors/jquery.minicolors.css',
				time(),
				true
			);
			
			wp_enqueue_style(
				'redux-field-colorrgba-css', 
				ReduxFramework::$_url . 'inc/fields/colorrgba/field_colorrgba.css', 
				time(),
				true
			);
		}
	}
}