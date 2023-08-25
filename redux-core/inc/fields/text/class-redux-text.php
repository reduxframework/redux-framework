<?php
/**
 * Text Field
 *
 * @package     Redux Framework/Fields
 * @author      Dovy Paukstys & Kevin Provance (kprovance)
 * @version     4.0.0
 * @noinspection PhpIgnoredClassAliasDeclaration
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Text', false ) ) {

	/**
	 * Class Redux_Text
	 */
	class Redux_Text extends Redux_Field {

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

				$this->field['options'] = $this->parent->wordpress_data->get( $this->field['data'], $this->field['args'], $this->parent->args['opt_name'], $this->value );
				$this->field['class']  .= ' hasOptions ';
			}

			if ( empty( $this->value ) && ! empty( $this->field['data'] ) && ! empty( $this->field['options'] ) ) {
				$this->value = $this->field['options'];
			}

			$qtip_title = isset( $this->field['text_hint']['title'] ) ? 'qtip-title="' . $this->field['text_hint']['title'] . '" ' : '';
			$qtip_text  = isset( $this->field['text_hint']['content'] ) ? 'qtip-content="' . $this->field['text_hint']['content'] . '" ' : '';

			$readonly     = ( isset( $this->field['readonly'] ) && $this->field['readonly'] ) ? ' readonly="readonly"' : '';
			$autocomplete = ( isset( $this->field['autocomplete'] ) && false === $this->field['autocomplete'] ) ? ' autocomplete="off"' : '';

			if ( isset( $this->field['options'] ) && ! empty( $this->field['options'] ) ) {
				$placeholder = '';

				if ( isset( $this->field['placeholder'] ) ) {
					$placeholder = $this->field['placeholder'];
				}

				foreach ( $this->field['options'] as $k => $v ) {
					if ( ! empty( $placeholder ) ) {
						$placeholder = ( is_array( $this->field['placeholder'] ) && isset( $this->field['placeholder'][ $k ] ) ) ? ' placeholder="' . esc_attr( $this->field['placeholder'][ $k ] ) . '" ' : '';
					}

					echo '<div class="input_wrapper">';
					echo '<label for="' . esc_attr( $this->field['id'] . '-text-' . $k ) . '">' . esc_html( $v ) . '</label> ';

					$value = $this->value[ $k ] ?? '';
					$value = ! empty( $this->value[ $k ] ) ? $this->value[ $k ] : '';

					// phpcs:ignore WordPress.Security.EscapeOutput
					echo '<input type="text" id="' . esc_attr( $this->field['id'] . '-text-' . $k ) . '" ' . esc_attr( $qtip_title ) . esc_attr( $qtip_text ) . ' name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] . '[' . esc_attr( $k ) ) . ']" ' . $placeholder . ' value="' . esc_attr( $value ) . '" class="regular-text ' . esc_attr( $this->field['class'] ) . '" ' . esc_html( $readonly ) . esc_html( $autocomplete ) . '/><br />';
					echo '</div>';
				}
			} else {
				$placeholder = ( isset( $this->field['placeholder'] ) && ! is_array( $this->field['placeholder'] ) ) ? ' placeholder="' . esc_attr( $this->field['placeholder'] ) . '" ' : '';

				// phpcs:ignore WordPress.Security.EscapeOutput
				echo '<input ' . esc_attr( $qtip_title ) . esc_attr( $qtip_text ) . 'type="text" id="' . esc_attr( $this->field['id'] ) . '" name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '" ' . $placeholder . 'value="' . esc_attr( $this->value ) . '" class="regular-text ' . esc_attr( $this->field['class'] ) . '"' . esc_html( $readonly ) . esc_html( $autocomplete ) . ' />';
			}
		}

		/**
		 * Enqueue Function.
		 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
		 *
		 * @since ReduxFramework 3.0.0
		 */
		public function enqueue() {
			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-field-text',
					Redux_Core::$url . 'inc/fields/text/redux-text.css',
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

class_alias( 'Redux_Text', 'ReduxFramework_Text' );
