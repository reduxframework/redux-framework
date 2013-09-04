<?php
class Redux_Options_multi_checkbox {

    /**
     * Field Constructor.
     *
     * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
     *
     * @since Redux_Options 1.0.0
    */
    function __construct($field = array(), $value ='', $parent) {
        $this->field = $field;
		$this->value = $value;
		$this->args = $parent->args;
    }

    /**
     * Field Render Function.
     *
     * Takes the vars and outputs the HTML for the field in the settings
     *
     * @since Redux_Options 1.0.0
    */
    function render() {
        $class = (isset($this->field['class'])) ? $this->field['class'] : 'regular-text';
        echo '<fieldset>';
        foreach($this->field['options'] as $k => $v) {
            $this->value[$k] = (isset($this->value[$k])) ? $this->value[$k] : '';
            echo '<label for="' . $this->field['id'] . '_' . array_search($k,array_keys($this->field['options'])) . '">';
            echo '<input type="checkbox" id="' . $this->field['id'] . '_' . array_search($k,array_keys($this->field['options'])) . '" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $k . ']" ' . $class . ' value="1" ' . checked($this->value[$k], '1', false) . '/>';
            echo ' ' . $v . '</label><br/>';
        }
        echo (isset($this->field['desc']) && !empty($this->field['desc'])) ? '<span class="description">' . $this->field['desc'] . '</span>' : '';
        echo '</fieldset>';
    }
}
