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
 * @subpackage  Body BackGround
 * @author      Sandro Bilbeisi (sandrobilbeisi)
 * @version     3.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_body_background' ) ) {

    /**
     * Main ReduxFramework_color class
     *
     * @since       1.0.0
     */
	class ReduxFramework_body_background extends ReduxFramework {
	
		/**
		 * Field Constructor.
		 *
		 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
		 *
	 	 * @since 		1.0.0
	 	 * @access		public
	 	 * @return		void
		 */
		function __construct( $field = array(), $value ='', $parent ) {
			
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

			$defaults = array(
				'bgcolor'		=> true,
				'id'			=> true,
				'url'			=> true,
				'width'			=> true,
				'height'		=> true,
				'thumbnail'		=> true,
				'preview'		=> true,
				'placeholder'	=> true,
				'repeat'		=> true,
				'position'		=> true,
				'attachment'	=> true,
				'origin'		=> true,
				'clip'			=> true,
				'size'			=> true,
			);

			$this->value = wp_parse_args( $this->value, $defaults );
			$fieldname = $this->args['opt_name'] . '[' . $this->field['id'] . ']';
/*
print_r($this->field);
echo "<hr />";
print_r($this->value);
*/

			// Picture
			if ( !isset( $this->field['mode'] ) ) {
				$this->field['mode'] = "image";
			}

			if( empty( $this->value['url'] ) && !empty( $this->field['default']['url'] ) ) { // If there are standard values and value is empty
				if( is_array( $this->field['default'] ) ) {
					if( !empty( $this->field['default']['id'] ) ) {
						$this->value['id'] = $this->field['default']['id'];
					}

					if( !empty( $this->field['default']['url'] ) ) {
						$this->value['url'] = $this->field['default']['url'];
					}
				} else {
					if( is_numeric( $this->field['default']['id'] ) ) { // Check if it's an attachment ID
						$this->value['id'] = $this->field['default']['id'];
					} else { // Must be a URL
						$this->value['url'] = $this->field['default']['url'];
					}
				}
			}


			if( empty( $this->value['url'] ) && !empty( $this->value['id'] ) ) {
				$img = wp_get_attachment_image_src( $this->value['id'], 'full' );
				$this->value['url'] = $img[0];
				$this->value['width'] = $img[1];
				$this->value['height'] = $img[2];
			}

			$hide = 'hide ';

			if( (isset( $this->field['preview'] ) && $this->field['preview'] === false) ) {
				$this->field['class'] .= " noPreview";
			}

			if( ( !empty( $this->field['url'] ) && $this->field['url'] === true ) || isset( $this->field['preview'] ) && $this->field['preview'] === false ) {
				$hide = '';
			}

			$placeholder = isset($this->field['placeholder']) ? $this->field['placeholder'] : __('No media selected','redux-framework');


			echo '<div class="redux-bgpreview-image" id="'.$this->field['id'].'[bgpreview]" style = " ';
			if(!empty($this->value['bgcolor']))
			{
				echo ' background-color:'.$this->value['bgcolor'].'; ';
			}
			if($hide=='')
			{
				echo ' background-image:url('.$this->value['url'].'); ';
			}
			if(!empty($this->value['repeat']))
			{
				echo ' background-repeat:'.$this->value['repeat'].'; ';
			}
			if(!empty($this->value['position']))
			{
				echo ' background-position:'.$this->value['position'].'; ';
			}
			if(!empty($this->value['attachment']))
			{
				echo ' background-attachment:'.$this->value['attachment'].'; ';
			}
			if(!empty($this->value['origin']))
			{
				echo ' background-origin:'.$this->value['origin'].'; ';
			}
			if(!empty($this->value['clip']))
			{
				echo ' background-clip:'.$this->value['clip'].'; ';
			}
			if(!empty($this->value['size']))
			{
				echo ' background-size:'.$this->value['size'].'; ';
			}
			echo '"> &nbsp; </div>'."\n";

			echo "<br />\n";
/*

*/
			echo "<strong>Background-Color</strong> &nbsp; " ;
			echo '<input data-id="'.$this->field['id']['bgcolor'].'" name="'.$this->args['opt_name'].'['.$this->field['id'].'][bgcolor]" id="' . $this->field['id'] . '[bgcolor]" class="redux-bgcolor redux-bgcolor-init ' . $this->field['class'] . '"  type="text" value="' . $this->value['bgcolor'] . '"  data-default-bgcolor="' . ( isset($this->field['default']['bgcolor']) ? $this->field['default']['bgcolor'] : "" ) . '" />';
			


			echo "<br />\n";
			echo "<strong>Background-Image</strong> &nbsp; " ;
			echo '<input placeholder="' . $placeholder .'" type="text" class="' . $hide . 'bgupload ' . $this->field['class'] . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][url]" id="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][url]" value="' . $this->value['url'] . '" readonly="readonly" />';

			echo '<input type="hidden" class="bgupload-id ' . $this->field['class'] . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][id]" id="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][id]" value="' . $this->value['id'] . '" />';
			
			echo '<input type="hidden" class="bgupload-height" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][height]" id="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][height]" value="' . $this->value['height'] . '" />';
			echo '<input type="hidden" class="bgupload-width" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][width]" id="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][width]" value="' . $this->value['width'] . '" />';
			echo '<input type="hidden" class="bgupload-thumbnail" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][thumbnail]" id="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][thumbnail]" value="' . $this->value['thumbnail'] . '" />';

			//Preview
			$hide = '';

			if( (isset( $this->field['preview'] ) && $this->field['preview'] === false) || empty( $this->value['url'] ) ) {
				$hide = 'hide ';
			}

			if ( empty( $this->value['thumbnail'] ) && !empty( $this->value['url'] ) ) { // Just in case
				if ( !empty( $this->value['id'] ) ) {
					$image = wp_get_attachment_image_src( $this->value['id'], array(150, 150) );
					$this->value['thumbnail'] = $image[0];
				} else {
					$this->value['thumbnail'] = $this->value['url'];
				}
			}

			echo '<div class="' . $hide . 'bgscreenshot">';
			echo '<a class="of-uploaded-bgimage" href="' . $this->value['url'] . '" target="_blank">';
			echo '<img class="redux-option-bgimage" id="bgimage_' . $this->field['id'] . '" src="' . $this->value['thumbnail'] . '" alt="" target="_blank" rel="external" />';
			echo '</a>';
			echo '</div>';

			//Upload controls DIV
			echo '<div class="bgupload_button_div">';

			//If the user has WP3.5+ show upload/remove button
			echo '<span class="button bgmedia_upload_button" id="' . $this->field['id'] . '-media">' . __( 'Upload', 'redux-framework' ) . '</span>';
			
			$hide = '';
			if( empty( $this->value['url'] ) || $this->value['url'] == '' )
				$hide =' hide';

			echo '<span class="button remove-bgimage' . $hide . '" id="reset_' . $this->field['id'] . '" rel="' . $this->field['id'] . '">' . __( 'Remove', 'redux-framework' ) . '</span>';

			echo '</div>';

// End of background-image


			echo '<table class="form-table form-table-bodybg no-border"><tbody>';
			$nameBrackets = "";

			if (!isset( $this->field['repeat'] )) $this->field['repeat']=true;
			if (  $this->field['repeat'] != false )
			{
				$bgRepeatOptions = array(
					'repeat'	=> 'Repeat All',
					'repeat-x'	=> 'Repeat Horizontally',
					'repeat-y'	=> 'Repeat Vertically',
					'no-repeat'	=> 'No repeat',
					'inherit'	=> 'Inherit'
				);
				echo '<tr>';
				echo '<td width="30%">';
				echo "<strong>Background-Repeat</strong>" ;
				echo '</td><td>';
				
				echo '<select  id="'.$this->field['id'].'[repeat]-select" data-placeholder="" name="'.$this->args['opt_name'].'['.$this->field['id'].'][repeat]'.'" class="redux-select-item redux-bgrepeat-input" rows="6">';
				echo '<option></option>';
				
				foreach( $bgRepeatOptions as $k => $v )
				{
					echo '<option value="' . $k . '"' . selected( $this->value['repeat'], $k, false ) . '>' . $v . '</option>';
				}
				echo '</select>';  
				echo '</td></tr>';
			}

			if (!isset( $this->field['position'] )) $this->field['position']=true;
			if (  $this->field['position'] != false )
			{
				$bgPositionOptions = array(
					'left top'		=> 'Left Top',
					'left center'	=> 'Left Center',
					'left bottom'	=> 'Left Bottom',
					'right top'		=> 'Right Top',
					'right center'	=> 'Right Center',
					'right bottom'	=> 'Right Bottom',
					'center top'	=> 'Center Top',
					'center center'	=> 'Center Center',
					'center bottom'	=> 'Center Bottom',
					'positions'		=> '(Positions) not yet working',
					'inherit'		=> 'Inherit'
				);
				echo '<tr>';
				echo '<td>';
				echo "<strong>Background-Position</strong>" ;
				echo '</td><td>';
				echo '<select  id="'.$this->field['id'].'[position]-select" data-placeholder="" name="'.$this->args['opt_name'].'['.$this->field['id'].'][position]'.'" class="redux-select-item redux-bgposition-input" rows="11">';
				echo '<option></option>';
				foreach( $bgPositionOptions as $k => $v )
				{
					echo '<option value="' . $k . '"' . selected( $this->value['position'], $k, false ) . '>' . $v . '</option>';
				}
				echo '</select>';
				echo '</td></tr>';
			}

			if (!isset( $this->field['attachment'] )) $this->field['attachment']=true;
			if (  $this->field['attachment'] != false )
			{
				$bgAttachmentOptions = array(
					'scroll'		=> 'Scroll',
					'fixed'	=> 'Fixed',
					'local'	=> 'Local',
					'inherit'		=> 'Inherit'
				);
				echo '<tr>';
				echo '<td>';
				echo "<strong>Background-Attachment</strong>" ;
				echo '</td><td>';
				echo '<select  id="'.$this->field['id'].'[attachment]-select" data-placeholder="" name="'.$this->args['opt_name'].'['.$this->field['id'].'][attachment]'.'" class="redux-select-item redux-bgattachment-input" rows="6">';
				echo '<option></option>';
				foreach( $bgAttachmentOptions as $k => $v )
				{
					echo '<option value="' . $k . '"' . selected( $this->value['attachment'], $k, false ) . '>' . $v . '</option>';
				}
				echo '</select>';
				echo '</td></tr>';
			}

			if (!isset( $this->field['origin'] )) $this->field['origin']=true;
			if (  $this->field['origin'] != false )
			{
				$bgOriginOptions = array(
					'border-box'	=> 'Border Box',
					'padding-box'	=> 'Padding Box',
					'content-box'	=> 'Content Box',
					'inherit'		=> 'Inherit'
				);
				echo '<tr>';
				echo '<td>';
				echo "<strong>Background-Origin</strong>" ;
				echo '</td><td>';
				echo '<select  id="'.$this->field['id'].'[origin]-select" data-placeholder="" name="'.$this->args['opt_name'].'['.$this->field['id'].'][origin]'.'" class="redux-select-item redux-bgorigin-input" rows="6">';
				echo '<option></option>';
				foreach( $bgOriginOptions as $k => $v )
				{
					echo '<option value="' . $k . '"' . selected( $this->value['origin'], $k, false ) . '>' . $v . '</option>';
				}
				echo '</select>';
				echo '</td></tr>';
			}

			if (!isset( $this->field['clip'] )) $this->field['clip']=true;
			if (  $this->field['clip'] != false )
			{
				$bgClipOptions = array(
					'border-box'	=> 'Border Box',
					'padding-box'	=> 'Padding Box',
					'content-box'	=> 'Content Box',
					'inherit'		=> 'Inherit'
				);
				echo '<tr>';
				echo '<td>';
				echo "<strong>Background-Clip</strong>" ;
				echo '</td><td>';
				echo '<select  id="'.$this->field['id'].'[clip]-select" data-placeholder="" name="'.$this->args['opt_name'].'['.$this->field['id'].'][clip]'.'" class="redux-select-item redux-bgclip-input" rows="6">';
				echo '<option></option>';
				foreach( $bgClipOptions as $k => $v )
				{
					echo '<option value="' . $k . '"' . selected( $this->value['clip'], $k, false ) . '>' . $v . '</option>';
				}
				echo '</select>';
				echo '</td></tr>';
			}

			if (!isset( $this->field['size'] )) $this->field['size']=true;
			if (  $this->field['size'] != false )
			{
				$bgSizeOptions = array(
					'cover'		=> 'Cover',
					'contain'	=> 'Contain',
					'auto'		=> 'Auto',
					'sizes'		=> '(Sizes) not yet working',
					'inherit'	=> 'Inherit'
				);
				echo '<tr>';
				echo '<td>';
				echo "<strong>Background-Size</strong>" ;
				echo '</td><td>';
				echo '<select  id="'.$this->field['id'].'[size]-select" data-placeholder="" name="'.$this->args['opt_name'].'['.$this->field['id'].'][size]'.'" class="redux-select-item redux-bgsize-input" rows="6">';
				echo '<option></option>';
				foreach( $bgSizeOptions as $k => $v )
				{
					echo '<option value="' . $k . '"' . selected( $this->value['size'], $k, false ) . '>' . $v . '</option>';
				}
				echo '</select>';
				echo '</td></tr>';
			}
			
			echo '</tbody></table>';
		}

		/**
		 * Enqueue Function.
		 *
		 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
		 *
		 * @since		1.0.0
		 * @access		public
		 * @return		void
		 */
		public function enqueue() {
			wp_enqueue_style( 'wp-color-picker' );
			
			wp_enqueue_script(
				'redux-field-bgcolor-js', 
				ReduxFramework::$_url . 'inc/fields/body_background/field_body_background.js', 
				array( 'jquery', 'wp-color-picker' ),
				time(),
				true
			);
			
			
			wp_enqueue_style(
				'redux-field-bgcolor-css', 
				ReduxFramework::$_url . 'inc/fields/body_background/field_body_background.css', 
				time(),
				true
			);
			
		}

		public function output() {
			/*
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
			*/
		}
	
	}
}