<?php
/**
 * Button Set Field.
 *
 * @package     ReduxFramework/Fields
 * @author      Dovy Paukstys & Kevin Provance (kprovance)
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Button_Set', false ) ) {

	/**
	 * Main Redux_button_set class
	 *
	 * @since       1.0.0
	 */
	class Redux_Button_Set extends Redux_Field {

		/**
		 * Set field defaults.
		 */
		public function set_defaults() {
			$defaults = array(
				'options' => array(),
				'multi'   => false,
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
			if ( ! empty( $this->field['data'] ) && empty( $this->field['options'] ) ) {
				if ( empty( $this->field['args'] ) ) {
					$this->field['args'] = array();
				}

				$this->field['options'] = $this->parent->get_wordpress_data( $this->field['data'], $this->field['args'], $this->value );

				if ( empty( $this->field['options'] ) ) {
					return;
				}
			}

			$is_multi = isset( $this->field['multi'] ) && true === (bool) $this->field['multi'];

			$name = $this->field['name'] . $this->field['name_suffix'];

			// multi => true renders the field multi-selectable (checkbox vs radio).
			echo '<div class="buttonset ui-buttonset">';

			if ( $is_multi ) {
				$s = '';

				if ( empty( $this->value ) ) {
					$s = $name;
				}

				echo '<input type="hidden" data-name="' . esc_attr( $name ) . '" class="buttonset-empty" name="' . esc_attr( $s ) . '" value=""/>';

				$name = $name . '[]';
			}

			foreach ( $this->field['options'] as $k => $v ) {
				$selected = '';

				if ( $is_multi ) {
					$post_value = '';
					$type       = 'checkbox';

					if ( ! empty( $this->value ) && ! is_array( $this->value ) ) {
						$this->value = array( $this->value );
					}

					if ( is_array( $this->value ) && in_array( (string) $k, $this->value, true ) ) {
						$selected   = 'checked="checked"';
						$post_value = $k;
					}
				} else {
					$type = 'radio';

					if ( is_scalar( $this->value ) ) {
						$selected = checked( $this->value, $k, false );
					}
				}

				$the_val     = $k;
				$the_name    = $name;
				$data_val    = '';
				$multi_class = '';

				if ( $is_multi ) {
					$the_val     = '';
					$the_name    = '';
					$data_val    = ' data-val=' . $k;
					$hidden_name = $name;
					$multi_class = 'multi ';

					if ( '' === $post_value ) {
						$hidden_name = '';
					}

					echo '<input type="hidden" class="buttonset-check" id="' . esc_attr( $this->field['id'] ) . '-buttonset' . esc_attr( $k ) . '-hidden" name="' . esc_attr( $hidden_name ) . '" value="' . esc_attr( $post_value ) . '"/>';
				}

				echo '<input' . esc_attr( $data_val ) . ' data-id="' . esc_attr( $this->field['id'] ) . '" type="' . esc_attr( $type ) . '" id="' . esc_attr( $this->field['id'] ) . '-buttonset' . esc_attr( $k ) . '" name="' . esc_attr( $the_name ) . '" class="buttonset-item ' . esc_attr( $multi_class ) . esc_attr( $this->field['class'] ) . '" value="' . esc_attr( $the_val ) . '" ' . esc_html( $selected ) . '/>';
				echo '<label for="' . esc_attr( $this->field['id'] ) . '-buttonset' . esc_attr( $k ) . '">' . esc_html( $v ) . '</label>';
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
			wp_enqueue_script(
				'redux-field-button-set',
				Redux_Core::$url . 'inc/fields/button_set/redux-button-set' . Redux_Functions::is_min() . '.js',
				array( 'jquery', 'jquery-ui-core', 'redux-js' ),
				$this->timestamp,
				true
			);
		}
	}
}

class_alias( 'Redux_Button_Set', 'ReduxFramework_Button_Set' );
