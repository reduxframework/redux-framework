<?php
/**
 * Checkbox Field.
 *
 * @package     ReduxFramework/Fields
 * @author      Dovy Paukstys & Kevin Provance (kprovance)
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Checkbox', false ) ) {

	/**
	 * Main Redux_checkbox class
	 *
	 * @since       1.0.0
	 */
	class Redux_Checkbox extends Redux_Field {

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

				$this->field['options'] = $this->parent->wordpress_data->get( $this->field['data'], $this->field['args'], $this->parent->args['opt_name'], $this->value );
				if ( empty( $this->field['options'] ) ) {
					return;
				}
			}

			$this->field['data_class'] = ( isset( $this->field['multi_layout'] ) ) ? 'data-' . $this->field['multi_layout'] : 'data-full';

			if ( ! empty( $this->field['options'] ) && ( is_array( $this->field['options'] ) || is_array( $this->field['default'] ) ) ) {

				echo '<ul class="' . esc_attr( $this->field['data_class'] ) . '">';

				if ( ! isset( $this->value ) ) {
					$this->value = array();
				}

				if ( ! is_array( $this->value ) ) {
					$this->value = array();
				}

				if ( empty( $this->field['options'] ) && isset( $this->field['default'] ) && is_array( $this->field['default'] ) ) {
					$this->field['options'] = $this->field['default'];
				}

				foreach ( $this->field['options'] as $k => $v ) {

					if ( empty( $this->value[ $k ] ) ) {
						$this->value[ $k ] = '';
					}

					echo '<li>';

					$ident_1 = strtr(
						$this->parent->args['opt_name'] . '[' . $this->field['id'] . '][' . $k . ']',
						array(
							'[' => '_',
							']' => '',
						)
					);

					$ident_2 = array_search( $k, array_keys( $this->field['options'] ), true );
					$id      = $ident_1 . '_' . $ident_2;

					echo '<label for="' . esc_attr( $id ) . '">';
					echo '<input type="hidden" class="checkbox-check" data-val="1" name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] . '[' . $k . ']' ) . '" value="' . esc_attr( $this->value[ $k ] ) . '"/>';
					echo '<input type="checkbox" class="checkbox ' . esc_attr( $this->field['class'] ) . '" id="' . esc_attr( $id ) . '" value="1" ' . checked( $this->value[ $k ], '1', false ) . '/>';
					echo ' ' . esc_attr( $v ) . '</label>';
					echo '</li>';
				}

				echo '</ul>';
			} elseif ( empty( $this->field['data'] ) ) {
				echo '<ul class="data-full">';
				echo '<li>';

				if ( ! empty( $this->field['label'] ) ) {
					echo '<label>';
				}

				$ident_1 = strtr(
					$this->parent->args['opt_name'] . '[' . $this->field['id'] . ']',
					array(
						'[' => '_',
						']' => '',
					)
				);

				// Got the "Checked" status as "0" or "1" then insert it as the "value" option.
				echo '<input type="hidden" class="checkbox-check" data-val="1" name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '" value="' . esc_attr( $this->value ) . '"/>';
				echo '<input type="checkbox" id="' . esc_attr( $ident_1 ) . '" value="1" class="checkbox ' . esc_attr( $this->field['class'] ) . '" ' . checked( $this->value, '1', false ) . '/>';

				if ( ! empty( $this->field['label'] ) ) {
					echo ' ' . esc_html( $this->field['label'] );
					echo '</label>';
				}

				echo '</li>';
				echo '</ul>';
			}
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
					'redux-field-checkbox-css',
					Redux_Core::$url . 'inc/fields/checkbox/redux-checkbox.css',
					array(),
					$this->timestamp
				);
			}

			wp_enqueue_script(
				'redux-field-checkbox-js',
				Redux_Core::$url . 'inc/fields/checkbox/redux-checkbox' . Redux_Functions::is_min() . '.js',
				array( 'jquery', 'redux-js' ),
				$this->timestamp,
				true
			);
		}
	}
}

class_alias( 'Redux_Checkbox', 'ReduxFramework_Checkbox' );
