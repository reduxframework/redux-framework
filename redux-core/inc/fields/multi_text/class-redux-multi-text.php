<?php
/**
 * Multi Text Field.
 *
 * @package     ReduxFramework/Fields
 * @author      Dovy Paukstys & Kevin Provance (kprovance)
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Multi_Text', false ) ) {

	/**
	 * Main Redux_multi_text class
	 *
	 * @since       1.0.0
	 */
	class Redux_Multi_Text extends Redux_Field {

		/**
		 * Set field defaults.
		 */
		public function set_defaults() {
			$defaults = array(
				'show_empty' => true,
				'add_text'   => esc_html__( 'Add More', 'redux-framework' ),
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
			echo '<ul id="' . esc_attr( $this->field['id'] ) . '-ul" class="redux-multi-text ' . esc_attr( $this->field['class'] ) . '">';

			if ( isset( $this->value ) && is_array( $this->value ) ) {
				foreach ( $this->value as $k => $value ) {
					if ( '' !== $value || ( true === $this->field['show_empty'] ) ) {
						echo '<li>';
						echo '<input
								type="text"
								id="' . esc_attr( $this->field['id'] . '-' . $k ) . '"
								name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[]"
								value="' . esc_attr( $value ) . '"
								class="regular-text" /> ';

						echo '<a
								data-id="' . esc_attr( $this->field['id'] ) . '-ul"
								href="javascript:void(0);"
								class="deletion redux-multi-text-remove">' .
								esc_html__( 'Remove', 'redux-framework' ) . '</a>';
						echo '</li>';
					}
				}
			} elseif ( true === $this->field['show_empty'] ) {
				echo '<li>';
				echo '<input
						type="text"
						id="' . esc_attr( $this->field['id'] . '-0' ) . '"
						name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[]"
						value=""
						class="regular-text" /> ';

				echo '<a
						data-id="' . esc_attr( $this->field['id'] ) . '-ul"
						href="javascript:void(0);"
						class="deletion redux-multi-text-remove">' .
						esc_html__( 'Remove', 'redux-framework' ) . '</a>';

				echo '</li>';
			}

			$the_name = '';
			if ( isset( $this->value ) && empty( $this->value ) && false === $this->field['show_empty'] ) {
				$the_name = $this->field['name'] . $this->field['name_suffix'];
			}

			echo '<li style="display:none;">';
			echo '<input
					type="text"
					id="' . esc_attr( $this->field['id'] ) . '"
					name="' . esc_attr( $the_name ) . '"
					value=""
					class="regular-text" /> ';

			echo '<a
					data-id="' . esc_attr( $this->field['id'] ) . '-ul"
					href="javascript:void(0);"
					class="deletion redux-multi-text-remove">' .
					esc_html__( 'Remove', 'redux-framework' ) . '</a>';

			echo '</li>';
			echo '</ul>';

			echo '<span style="clear:both;display:block;height:0;"></span>';
			$this->field['add_number'] = ( isset( $this->field['add_number'] ) && is_numeric( $this->field['add_number'] ) ) ? $this->field['add_number'] : 1;
			echo '<a href="javascript:void(0);" class="button button-primary redux-multi-text-add" data-add_number="' . esc_attr( $this->field['add_number'] ) . '" data-id="' . esc_attr( $this->field['id'] ) . '-ul" data-name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '">' . esc_html( $this->field['add_text'] ) . '</a><br/>';
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
				'redux-field-multi-text-js',
				Redux_Core::$url . 'inc/fields/multi_text/redux-multi-text' . Redux_Functions::is_min() . '.js',
				array( 'jquery', 'redux-js' ),
				$this->timestamp,
				true
			);

			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-field-multi-text-css',
					Redux_Core::$url . 'inc/fields/multi_text/redux-multi-text.css',
					array(),
					$this->timestamp
				);
			}
		}
	}
}

class_alias( 'Redux_Multi_Text', 'ReduxFramework_Multi_Text' );
