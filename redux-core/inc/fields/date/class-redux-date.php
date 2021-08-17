<?php
/**
 * Date Field.
 *
 * @package     ReduxFramework/Fields
 * @author      Dovy Paukstys & Kevin Provance (kprovance)
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Date', false ) ) {

	/**
	 * Main Redux_date class
	 *
	 * @since       1.0.0
	 */
	class Redux_Date extends Redux_Field {

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since         1.0.0
		 * @access        public
		 * @return        void
		 */
		public function render() {
			$placeholder = ( isset( $this->field['placeholder'] ) ) ? ' placeholder="' . $this->field['placeholder'] . '" ' : '';

			$this->field['attributes']['data-id']     = $this->field['id'];
			$this->field['attributes']['id']          = $this->field['id'];
			$this->field['attributes']['name']        = esc_attr( $this->field['name'] . $this->field['name_suffix'] );
			$this->field['attributes']['value']       = $this->value;
			$this->field['attributes']['placeholder'] = ( isset( $this->field['placeholder'] ) && ! is_array( $this->field['placeholder'] ) ) ? esc_attr( $this->field['placeholder'] ) : '';
			$this->field['attributes']['class']       = 'redux-datepicker regular-text ' . esc_attr( $this->field['class'] );
			$attributes_string                        = $this->render_attributes( $this->field['attributes'] );
			echo '<input ' . $attributes_string . '>'; // phpcs:ignore WordPress.Security.EscapeOutput
		}

		/**
		 * Enqueue Function.
		 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
		 *
		 * @since         1.0.0
		 * @access        public
		 * @return        void
		 */
		public function enqueue() {
			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-field-date-css',
					Redux_Core::$url . 'inc/fields/date/redux-date.css',
					array(),
					$this->timestamp
				);
			}

			wp_enqueue_script(
				'redux-field-date-js',
				Redux_Core::$url . 'inc/fields/date/redux-date' . Redux_Functions::is_min() . '.js',
				array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker', 'redux-js' ),
				$this->timestamp,
				true
			);
		}
	}
}

class_alias( 'Redux_Date', 'ReduxFramework_Date' );
