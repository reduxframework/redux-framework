<?php
class Redux_Framework_color extends Redux_Framework{	
	
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
		
		echo '<div class="redux-color-container">';

		$class = (isset($this->field['class']))?' '.$this->field['class'].'" ':'';
		if (!empty($this->field['compiler']) && $this->field['compiler']) {
			$class .= " compiler";
		}

		echo '<input name="'.$this->args['opt_name'].'['.$this->field['id'].']" id="' . $this->field['id'] . '" class="redux-color redux-color-init ' . $class . '"  type="text" value="' . $this->value . '"  data-default-color="' . $this->field['std'] . '" />';
		
		echo (isset($this->field['desc']) && !empty($this->field['desc']))?'<div class="description">'.$this->field['desc'].'</div>':'';
		
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
		
	}//function
	
}//class
?>