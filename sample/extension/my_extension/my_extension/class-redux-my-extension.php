<?php
/**
 * Redux My Extension Field Class
 * Short description.
 *
 * @package Redux Extentions
 * @class   Redux_Extension_My_Extension
 * @version 1.0.0
 *
 * There is no free support for extension development.
 * This example is 'as is'.
 *
 * Please be sure to replace ALL instances of "My Extension" and "My_Extension" with the name of your actual
 * extension.
 *
 * Please also change the file name, so the 'my-extension' portion is also the name of your extension.
 * Please use dashes and not underscores in the filename.  Please use underscores instead of dashes in the classname.
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_My_Extension', false ) ) {

	/**
	 * Main ReduxFramework_options_object class
	 *
	 * @since       1.0.0
	 */
	class Redux_My_Extension extends Redux_Field {
		/**
		 * Field Constructor.
		 * Required - must call the parent constructor, then assign field and value to vars
		 *
		 * @param array  $field  Field array.
		 * @param mixed  $value  Field values.
		 * @param object $parent ReduxFramework pointer.
		 *
		 * @throws ReflectionException Construct Exception.
		 */
		public function __construct( array $field, $value, $parent ) {
			parent::__construct( $field, $value, $parent );

			// Set default args for this field to avoid bad indexes. Change this to anything you use.
			$defaults = array(
				'options'          => array(),
				'stylesheet'       => '',
				'output'           => true,
				'enqueue'          => true,
				'enqueue_frontend' => true,
			);

			$this->field = wp_parse_args( $this->field, $defaults );
		}

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @return      void
		 */
		public function render() {
			// Render the field.
		}

		/**
		 * Enqueue Function.
		 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
		 *
		 * @return      void
		 */
		public function enqueue() {
			wp_enqueue_script(
				'redux-my-field',
				$this->url . 'redux-my-extension.js',
				array( 'jquery', 'redux-js' ),
				Redux_Extension_My_Extension::$version,
				true
			);

			wp_enqueue_style(
				'redux-my-field',
				$this->url . 'redux-my-extension.css',
				array(),
				Redux_Extension_my_extension::$version
			);
		}
	}
}
