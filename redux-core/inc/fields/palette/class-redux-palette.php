<?php
/**
 * Background Field.
 *
 * @package     ReduxFramework/Fields
 * @author      Kevin Provance (kprovance)
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Palette', false ) ) {

	/**
	 * Class Redux_Palette
	 */
	class Redux_Palette extends Redux_Field {

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settingss
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function render() {
			if ( ! isset( $this->field['palettes'] ) && empty( $this->field['palettes'] ) ) {
				echo 'No palettes have been set.';

				return;
			}

			echo '<div id="' . esc_attr( $this->field['id'] ) . '" class="buttonset">';

			foreach ( $this->field['palettes'] as $value => $color_set ) {
				$checked = checked( $this->value, $value, false );

				echo '<input
						type="radio"
						value="' . esc_attr( $value ) . '"
						name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '"
						class="redux-palette-set ' . esc_attr( $this->field['class'] ) . '"
						id="' . esc_attr( $this->field['id'] . '-' . $value ) . '"' . esc_html( $checked ) . '>';

				echo '<label for="' . esc_attr( $this->field['id'] . '-' . $value ) . '">';

				foreach ( $color_set as $color ) {
					echo '<span style=background:' . esc_attr( $color ) . '>' . esc_attr( $color ) . '</span>';
				}

				echo '</label>';
				echo '</input>';
			}

			echo '</div>';
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
			$min = Redux_Functions::is_min();

			wp_enqueue_script(
				'redux-field-palette-js',
				Redux_Core::$url . 'inc/fields/palette/redux-palette' . $min . '.js',
				array( 'jquery', 'redux-js', 'jquery-ui-button', 'jquery-ui-core' ),
				$this->timestamp,
				true
			);

			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-field-palette-css',
					Redux_Core::$url . 'inc/fields/palette/redux-palette.css',
					array(),
					$this->timestamp
				);
			}
		}

		/**
		 * Enable output_variables to be generated.
		 *
		 * @since       4.0.3
		 * @return void
		 */
		public function output_variables() {
			// No code needed, just defining the method is enough.
		}
	}
}

class_alias( 'Redux_Palette', 'ReduxFramework_Palette' );
