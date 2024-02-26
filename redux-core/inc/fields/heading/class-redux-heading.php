<?php
/**
 * Heading Field.
 *
 * @package     ReduxFramework/Fields
 * @author      Kevin Provance (kprovance)
 * @version     4.4.14
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Heading', false ) ) {

	/**
	 * Main Redux_Heading class
	 *
	 * @since       1.0.0
	 */
	class Redux_Heading extends Redux_Field {

		/**
		 * Set field and value defaults.
		 */
		public function set_defaults() {
			$defaults = array(
				'content' => '',
				'icon'    => '',
				'class'   => '',
			);

			$this->field = wp_parse_args( $this->field, $defaults );
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
			echo '</td></tr></table>';
			echo '<h2
					id="heading-' . esc_attr( $this->field['id'] ) . '"
					class="redux-field redux-field-borders ' . ( isset( $this->field['icon'] ) && ! empty( $this->field['icon'] ) && true !== $this->field['icon'] ? 'hasIcon ' : '' ) . ' ' . esc_attr( $this->field['class'] ) . ' redux-field-' . esc_attr( $this->field['type'] ) . '"' . ( ! empty( $this->field['color'] ) ? ' style="' . esc_attr( $this->field['color'] ) . '"' : '' ) . '>';
			echo wp_kses_post( $this->field['content'] );
			echo '</h2>';
			echo '<table class="form-table no-border" style="margin-top: 0;">';
			echo '<tbody>';
			echo '<tr style="border-bottom:0; display:none;">';
			echo '<th style="padding-top:0;"></th>';
			echo '<td style="padding-top:0;">';
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
			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-field-heading',
					Redux_Core::$url . 'inc/fields/heading/redux-heading.css',
					array(),
					$this->timestamp
				);
			}
		}
	}
}
