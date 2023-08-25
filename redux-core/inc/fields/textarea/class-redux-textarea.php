<?php
/**
 * Textarea Field
 *
 * @package     Redux Framework/Fields
 * @author      Dovy Paukstys & Kevin Provance (kprovance)
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Textarea', false ) ) {

	/**
	 * Class Redux_Textarea
	 */
	class Redux_Textarea extends Redux_Field {

		/**
		 * Set field and value defaults.
		 */
		public function set_defaults() {
			$defaults = array(
				'placeholder'  => '',
				'rows'         => 6,
				'autocomplete' => false,
				'readonly'     => false,
				'class'        => '',
			);

			$this->field = wp_parse_args( $this->field, $defaults );
		}

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since ReduxFramework 1.0.0
		 * */
		public function render() {
			$readonly     = ( true === boolval( $this->field['readonly'] ) ) ? ' readonly' : '';
			$autocomplete = ( true === boolval( $this->field['autocomplete'] ) ) ? 'on' : 'off';

			?>
			<label for="<?php echo esc_attr( $this->field['id'] ); ?>-textarea"></label>
			<textarea <?php echo esc_html( $readonly ); ?>
					name="<?php echo esc_attr( $this->field['name'] . $this->field['name_suffix'] ); ?>"
					id="<?php echo esc_attr( $this->field['id'] ); ?>-textarea"
					placeholder="<?php echo esc_attr( $this->field['placeholder'] ); ?>"
					autocomplete="<?php echo esc_attr( $autocomplete ); ?>"
					class="large-text <?php echo esc_attr( $this->field['class'] ); ?>"
					rows="<?php echo esc_attr( $this->field['rows'] ); ?>"><?php echo esc_textarea( $this->value ); ?>
			</textarea>
			<?php
		}

		/**
		 * Sanitize value.
		 *
		 * @param array  $field Field array.
		 * @param string $value Values array.
		 *
		 * @return string
		 */
		public function sanitize( array $field, string $value ): string {
			if ( empty( $value ) ) {
				$value = '';
			} else {
				$value = esc_textarea( $value );
			}

			return $value;
		}
	}
}

class_alias( 'Redux_Textarea', 'ReduxFramework_Textarea' );
