<?php
class Redux_Framework_border extends Redux_Framework{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since Redux_Framework 1.0.0
	*/
	function __construct($field = array(), $value ='', $parent){
		
		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;
		//$this->render();
		
	}//function
	
	
	
	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since Redux_Framework 1.0.0
	*/
	function render(){

		// No errors please
		$defaults = array(
			'color' => '',
			'style' => '',
			'size' => '',
			);
		$this->value = wp_parse_args( $this->value, $defaults );
		$this->field['std'] = wp_parse_args( $this->field['std'], $defaults );	

		if (empty($this->field['min'])) {
			$this->field['min'] = 0;
		}
		if (empty($this->field['max'])) {
			$this->field['max'] = 10;
		}		
		
		echo '<div class="redux-border-container">';

		$options = array(''=>'None', 'solid'=>'Solid', 'dashed'=>'Dashed', 'dotted'=>'Dotted');

		$class = (isset($this->field['class']))?' '.$this->field['class'].'" ':'';
		if (!empty($this->field['compiler']) && $this->field['compiler']) {
			$class .= " compiler";
		}
		echo '<div class="redux-border">';
		
			echo '<select original-title="'.__('Border size','redux').'" id="'.$this->field['id'].'" name="'.$this->args['opt_name'].'['.$this->field['id'].'][size]" class="tips redux-border-size mini'.$class.'" rows="6">';
				for ($k = $this->field['min']; $k <= $this->field['max']; $k++) {
					echo '<option value="'.$k.'"'.selected($this->value['size'], $k, false).'>'.$k.'</option>';
				}//foreach
			echo '</select>';	
			echo '<select original-title="'.__('Border style','redux').'" id="'.$this->field['id'].'" name="'.$this->args['opt_name'].'['.$this->field['id'].'][style]" class="tips redux-border-style'.$class.'" rows="6">';
				foreach($options as $k => $v){
					echo '<option value="'.$k.'"'.selected($this->value['style'], $k, false).'>'.$v.'</option>';
				}//foreach
			echo '</select>';	
			echo '<input name="'.$this->args['opt_name'].'['.$this->field['id'].'][color]" id="' . $this->field['id'] . '-color" class="redux-border-color redux-color ' . $class . '"  type="text" value="' . $this->value['color'] . '"  data-default-color="' . $this->field['std']['color'] . '" />';
			
			echo (isset($this->field['desc']) && !empty($this->field['desc']))?'<div class="description">'.$this->field['desc'].'</div>':'';
			
			echo '</div>';
		echo '</div>';

	}//function
	
	
	/**
	 * Enqueue Function.
	 *
	 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
	 *
	 * @since Redux_Framework 1.0.0
	*/
	function enqueue(){
		
		wp_enqueue_script(
			'redux-field-color-js', 
			REDUX_URL.'inc/fields/color/field_color.js', 
			array('jquery', 'wp-color-picker'),
			time(),
			true
		);

		wp_enqueue_style(
			'redux-field-color-css', 
			REDUX_URL.'inc/fields/color/field_color.css', 
			time(),
			true
		);		
		
		wp_enqueue_style(
			'redux-field-border-css', 
			REDUX_URL.'inc/fields/border/field_border.css', 
			time(),
			true
		);		

	}//function
	
}//class
?>