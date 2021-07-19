<?php
/**
 * Select Field.
 *
 * @package     ReduxFramework/Fields
 * @author      Dovy Paukstys & Kevin Provance (kprovance)
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Select', false ) ) {

	/**
	 * Class Redux_Select
	 */
	class Redux_Select extends Redux_Field {

		/**
		 * Set field defaults.
		 */
		public function set_defaults() {
			$defaults = array(
				'options'          => array(),
				'width'            => '40%',
				'multi'            => false,
				'sortable'         => false,
				'ajax'             => false,
				'min-input-length' => 1,
				'placeholder'      => '',
			);

			$this->field = wp_parse_args( $this->field, $defaults );
		}

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since ReduxFramework 1.0.0
		 */
		public function render() {
			$sortable = ( isset( $this->field['sortable'] ) && true === (bool) $this->field['sortable'] ) ? ' select2-sortable' : '';

			if ( ! empty( $sortable ) ) { // Dummy proofing  :P.
				$this->field['multi'] = true;
			}

			if ( ! empty( $this->field['data'] ) && empty( $this->field['options'] ) ) {
				if ( empty( $this->field['args'] ) ) {
					$this->field['args'] = array();
				}

				if ( 'elusive-icons' === $this->field['data'] || 'elusive-icon' === $this->field['data'] || 'elusive' === $this->field['data'] ) {
					$icons_file = Redux_Core::$dir . 'inc/fields/select/elusive-icons.php';

					/**
					 * Filter 'redux-font-icons-file}'
					 *
					 * @param  array $icon_file The File for the icons
					 */

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					$icons_file = apply_filters( 'redux-font-icons-file', $icons_file );

					/**
					 * Filter 'redux/{opt_name}/field/font/icons/file'
					 *
					 * @param  array $icon_file The file for the icons
					 */

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					$icons_file = apply_filters( "redux/{$this->parent->args['opt_name']}/field/font/icons/file", $icons_file );

					if ( file_exists( $icons_file ) ) {
						include_once $icons_file;
					}
				}

				// First one get with AJAX.
				$ajax = false;
				if ( isset( $this->field['ajax'] ) && $this->field['ajax'] ) {
					$ajax = true;
				}
				$this->field['options'] = $this->parent->wordpress_data->get( $this->field['data'], $this->field['args'], $this->parent->args['opt_name'], $this->value, $ajax );
			}

			if ( ! empty( $this->field['data'] ) && in_array( $this->field['data'], array( 'elusive-icons', 'elusive-icon', 'elusive', 'dashicons', 'dashicon', 'dash' ), true ) ) {
				$this->field['class'] .= ' font-icons';
			}

			if ( ! empty( $this->field['options'] ) ) {
				$multi = ( isset( $this->field['multi'] ) && $this->field['multi'] ) ? ' multiple="multiple"' : '';

				if ( ! empty( $this->field['width'] ) ) {
					$width = ' style="width:' . esc_attr( $this->field['width'] ) . '"';
				} else {
					$width = ' style="width:40%;"';
				}

				$name_brackets = '';
				if ( ! empty( $multi ) ) {
					$name_brackets = '[]';
				}

				$placeholder = ( isset( $this->field['placeholder'] ) ) ? esc_attr( $this->field['placeholder'] ) : esc_html__( 'Select an item', 'redux-framework' );

				$select2_width = 'resolve';
				if ( '' !== $multi ) {
					$select2_width = '100%';
				}
				$this->select2_config['width']      = $select2_width;
				$this->select2_config['allowClear'] = true;

				if ( isset( $this->field['ajax'] ) && $this->field['ajax'] && isset( $this->field['data'] ) && '' !== $this->field['data'] ) {
					$this->select2_config['ajax']             = true;
					$this->select2_config['min-input-length'] = $this->field['min_input_length'] ?? 1;
					$this->select2_config['action']           = "redux_{$this->parent->args['opt_name']}_select2";
					if ( isset( $this->field['args'] ) ) {
						$this->select2_config['args'] = wp_json_encode( $this->field['args'] );
					}
					$this->select2_config['nonce']   = wp_create_nonce( "redux_{$this->parent->args['opt_name']}_select2" );
					$this->select2_config['wp-data'] = $this->field['data'];
				}

				if ( isset( $this->field['select2'] ) ) {
					$this->field['select2'] = wp_parse_args( $this->field['select2'], $this->select2_config );
				} else {
					$this->field['select2'] = $this->select2_config;
				}

				$this->field['select2'] = Redux_Functions::sanitize_camel_case_array_keys( $this->field['select2'] );

				$select2_data = Redux_Functions::create_data_string( $this->field['select2'] );

				if ( isset( $this->field['multi'] ) && $this->field['multi'] && isset( $this->field['sortable'] ) && $this->field['sortable'] && ! empty( $this->value ) && is_array( $this->value ) ) {
					$orig_option            = $this->field['options'];
					$this->field['options'] = array();

					foreach ( $this->value as $value ) {
						$this->field['options'][ $value ] = $orig_option[ $value ];
					}

					if ( count( $this->field['options'] ) < count( $orig_option ) ) {
						foreach ( $orig_option as $key => $value ) {
							if ( ! in_array( $key, $this->field['options'], true ) ) {
								$this->field['options'][ $key ] = $value;
							}
						}
					}
				}

				$sortable = ( isset( $this->field['sortable'] ) && $this->field['sortable'] ) ? ' select2-sortable' : '';

				echo '<select ' .
					esc_html( $multi ) . '
			        id="' . esc_attr( $this->field['id'] ) . '-select"
			        data-placeholder="' . esc_attr( $placeholder ) . '"
			        name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . esc_attr( $name_brackets ) . '"
			        class="redux-select-item ' . esc_attr( $this->field['class'] ) . esc_attr( $sortable ) . '"' .
					$width . ' rows="6"' . esc_attr( $select2_data ) . '>'; // phpcs:ignore WordPress.Security.EscapeOutput

				echo '<option></option>';

				foreach ( $this->field['options'] as $k => $v ) {
					if ( is_array( $v ) ) {
						echo '<optgroup label="' . esc_attr( $k ) . '">';

						foreach ( $v as $opt => $val ) {
							$this->make_option( (string) $opt, $val, $k );
						}

						echo '</optgroup>';

						continue;
					}

					$this->make_option( (string) $k, $v );
				}

				echo '</select>';
			} else {
				echo '<strong>' . esc_html__( 'No items of this type were found.', 'redux-framework' ) . '</strong>';
			}
		}

		/**
		 * Compile option HTML.
		 *
		 * @param string $id         HTML ID.
		 * @param mixed  $value      Value array.
		 * @param string $group_name Group name.
		 */
		private function make_option( string $id, $value, string $group_name = '' ) {
			if ( is_array( $this->value ) ) {
				$selected = ( in_array( $id, $this->value, true ) ) ? ' selected="selected"' : '';
			} else {
				$selected = selected( $this->value, $id, false );
			}

			echo '<option value="' . esc_attr( $id ) . '" ' . esc_html( $selected ) . '>' . esc_attr( $value ) . '</option>';
		}

		/**
		 * Enqueue Function.
		 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
		 *
		 * @since ReduxFramework 1.0.0
		 */
		public function enqueue() {
			wp_enqueue_style( 'select2-css' );

			if ( isset( $this->field['sortable'] ) && $this->field['sortable'] ) {
				wp_enqueue_script( 'jquery-ui-sortable' );
			}

			wp_enqueue_script(
				'redux-field-select-js',
				Redux_Core::$url . 'inc/fields/select/redux-select' . Redux_Functions::is_min() . '.js',
				array( 'jquery', 'select2-js', 'redux-js' ),
				$this->timestamp,
				true
			);

			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-field-select-css',
					Redux_Core::$url . 'inc/fields/select/redux-select.css',
					array(),
					$this->timestamp
				);
			}
		}
	}
}

class_alias( 'Redux_Select', 'ReduxFramework_Select' );
