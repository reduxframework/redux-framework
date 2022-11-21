<?php
/**
 * Radio Button Field.
 *
 * @package     ReduxFramework/Fields
 * @author      Dovy Paukstys & Kevin Provance (kprovance)
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Radio', false ) ) {

	/**
	 * Class Redux_Radio
	 */
	class Redux_Radio extends Redux_Field {

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since ReduxFramework 1.0.0
		 */
		public function render() {
			if ( ! empty( $this->field['data'] ) && empty( $this->field['options'] ) ) {
				if ( empty( $this->field['args'] ) ) {
					$this->field['args'] = array();
				}

				if ( is_array( $this->field['data'] ) ) {
					$this->field['options'] = $this->field['data'];
				} else {
					$this->field['options'] = $this->parent->wordpress_data->get( $this->field['data'], $this->field['args'], $this->parent->args['opt_name'], $this->value );
				}
			}

			$this->field['data_class'] = ( isset( $this->field['multi_layout'] ) ) ? 'data-' . $this->field['multi_layout'] : 'data-full';

			if ( isset( $this->field['options'] ) && ! empty( $this->field['options'] ) ) {
				echo '<ul class="' . esc_attr( $this->field['data_class'] ) . '">';

				foreach ( $this->field['options'] as $k => $v ) {
					echo '<li>';
					echo '<label for="' . esc_attr( $this->field['id'] . '_' . array_search( $k, array_keys( $this->field['options'] ), true ) ) . '">';
					echo '<input
							type="radio"
							class="radio ' . esc_attr( $this->field['class'] ) . '"
							id="' . esc_attr( $this->field['id'] . '_' . array_search( $k, array_keys( $this->field['options'] ), true ) ) . '"
							name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '"
							value="' . esc_attr( $k ) . '" ' . checked( $this->value, $k, false ) . '/>';

					echo ' <span>' . wp_kses_post( $v ) . '</span>';
					echo '</label>';
					echo '</li>';
				}

				echo '</ul>';
			}
		}
	}
}

class_alias( 'Redux_Radio', 'ReduxFramework_Radio' );
