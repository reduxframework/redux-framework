<?php
class Redux_Framework_date extends Redux_Framework{	
	
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
		
		$class = (isset($this->field['class']))?' '.$this->field['class'].'" ':'';
		if (!empty($this->field['compiler']) && $this->field['compiler']) {
			$class .= " compiler";
		}
		
		echo '<input type="text" id="'.$this->field['id'].'" name="'.$this->args['opt_name'].'['.$this->field['id'].']" value="'.$this->value.'" class="redux-datepicker'.$class.'" />';
		
		echo (isset($this->field['desc']) && !empty($this->field['desc']))?'<div class="description">'.$this->field['desc'].'</div>':'';
		
	}//function
	
	
	
	/**
	 * Enqueue Function.
	 *
	 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
	 *
	 * @since Redux_Framework 1.0.0
	*/
	function enqueue(){
		wp_enqueue_style('jquery-ui-css');
		wp_enqueue_script(
			'redux-field-date-js', 
			REDUX_URL.'inc/fields/date/field_date.js', 
			array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'),
			time(),
			true
		);
		
	}//function
	
}//class
?>