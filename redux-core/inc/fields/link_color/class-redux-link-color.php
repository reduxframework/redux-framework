<?php
/**
 * Link Color Field.
 *
 * @package     ReduxFramework/Fields
 * @author      Dovy Paukstys & Kevin Provance (kprovance)
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Link_Color', false ) ) {

	/**
	 * Main Redux_link_color class
	 *
	 * @since       1.0.0
	 */
	class Redux_Link_Color extends Redux_Field {

		/**
		 * Set field and value defaults.
		 */
		public function set_defaults() {
			$defaults = array(
				'regular' => true,
				'hover'   => true,
				'visited' => false,
				'active'  => true,
				'focus'   => false,
			);

			$this->field = wp_parse_args( $this->field, $defaults );

			$defaults = array(
				'regular' => '',
				'hover'   => '',
				'visited' => '',
				'active'  => '',
				'focus'   => '',
			);

			$this->value = wp_parse_args( $this->value, $defaults );

			// In case user passes no default values.
			if ( isset( $this->field['default'] ) ) {
				$this->field['default'] = wp_parse_args( $this->field['default'], $defaults );
			} else {
				$this->field['default'] = $defaults;
			}
		}

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings.
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function render() {
			if ( true === $this->field['regular'] && false !== $this->field['default']['regular'] ) {
				echo '<span class="linkColor">';
				echo '<strong>' . esc_html__( 'Regular', 'redux-framework' ) . '</strong>&nbsp;';
				echo '<input ';
				echo 'id="' . esc_attr( $this->field['id'] ) . '-regular" ';
				echo 'name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[regular]"';
				echo 'value="' . esc_attr( $this->value['regular'] ) . '"';
				echo 'class="color-picker redux-color redux-color-regular redux-color-init ' . esc_attr( $this->field['class'] ) . '"';
				echo 'type="text"';
				echo 'data-default-color="' . esc_attr( $this->field['default']['regular'] ) . '"';

				$data = array(
					'field' => $this->field,
					'index' => 'regular',
				);

				echo Redux_Functions_Ex::output_alpha_data( $data ); // phpcs:ignore WordPress.Security.EscapeOutput

				echo '>';
				echo '</span>';
			}

			if ( true === $this->field['hover'] && false !== $this->field['default']['hover'] ) {
				echo '<span class="linkColor">';
				echo '<strong>' . esc_html__( 'Hover', 'redux-framework' ) . '</strong>&nbsp;';
				echo '<input ';
				echo 'id="' . esc_attr( $this->field['id'] ) . '-hover"';
				echo 'name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[hover]"';
				echo 'value="' . esc_attr( $this->value['hover'] ) . '"';
				echo 'class="color-picker redux-color redux-color-hover redux-color-init ' . esc_attr( $this->field['class'] ) . '"';
				echo 'type="text"';
				echo 'data-default-color="' . esc_attr( $this->field['default']['hover'] ) . '"';

				$data = array(
					'field' => $this->field,
					'index' => 'hover',
				);

				echo Redux_Functions_Ex::output_alpha_data( $data ); // phpcs:ignore WordPress.Security.EscapeOutput

				echo '>';
				echo '</span>';
			}

			if ( true === $this->field['visited'] && false !== $this->field['default']['visited'] ) {
				echo '<span class="linkColor">';
				echo '<strong>' . esc_html__( 'Visited', 'redux-framework' ) . '</strong>&nbsp;';
				echo '<input ';
				echo 'id="' . esc_attr( $this->field['id'] ) . '-visited"';
				echo 'name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[visited]"';
				echo 'value="' . esc_attr( $this->value['visited'] ) . '"';
				echo 'class="color-picker redux-color redux-color-visited redux-color-init ' . esc_attr( $this->field['class'] ) . '"';
				echo 'type="text"';
				echo 'data-default-color="' . esc_attr( $this->field['default']['visited'] ) . '"';

				$data = array(
					'field' => $this->field,
					'index' => 'visited',
				);

				echo Redux_Functions_Ex::output_alpha_data( $data ); // phpcs:ignore WordPress.Security.EscapeOutput

				echo '>';
				echo '</span>';
			}

			if ( true === $this->field['active'] && false !== $this->field['default']['active'] ) {
				echo '<span class="linkColor">';
				echo '<strong>' . esc_html__( 'Active', 'redux-framework' ) . '</strong>&nbsp;';
				echo '<input ';
				echo 'id="' . esc_attr( $this->field['id'] ) . '-active"';
				echo 'name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[active]"';
				echo 'value="' . esc_attr( $this->value['active'] ) . '"';
				echo 'class="color-picker redux-color redux-color-active redux-color-init ' . esc_attr( $this->field['class'] ) . '"';
				echo 'type="text"';
				echo 'data-default-color="' . esc_attr( $this->field['default']['active'] ) . '"';

				$data = array(
					'field' => $this->field,
					'index' => 'active',
				);

				echo Redux_Functions_Ex::output_alpha_data( $data ); // phpcs:ignore WordPress.Security.EscapeOutput

				echo '>';
				echo '</span>';
			}

			if ( true === $this->field['focus'] && false !== $this->field['default']['focus'] ) {
				echo '<span class="linkColor">';
				echo '<strong>' . esc_html__( 'Focus', 'redux-framework' ) . '</strong>&nbsp;';
				echo '<input ';
				echo 'id="' . esc_attr( $this->field['id'] ) . '-focus"';
				echo 'name="' . esc_attr( $this->field['name'] . $this->field['name_suffix'] ) . '[focus]"';
				echo 'value="' . esc_attr( $this->value['focus'] ) . '"';
				echo 'class="color-picker redux-color redux-color-focus redux-color-init ' . esc_attr( $this->field['class'] ) . '"';
				echo 'type="text"';
				echo 'data-default-color="' . esc_attr( $this->field['default']['focus'] ) . '"';

				$data = array(
					'field' => $this->field,
					'index' => 'focus',
				);

				echo Redux_Functions_Ex::output_alpha_data( $data ); // phpcs:ignore WordPress.Security.EscapeOutput

				echo '>';
				echo '</span>';
			}
		}

		/**
		 * Enqueue Function.
		 * If this field requires any scripts, or CSS define this function and register/enqueue the scripts/css
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function enqueue() {
			wp_enqueue_style( 'wp-color-picker' );

			$dep_array = array( 'jquery', 'wp-color-picker', 'redux-js' );

			wp_enqueue_script(
				'redux-field-link-color-js',
				Redux_Core::$url . 'inc/fields/link_color/redux-link-color' . Redux_Functions::is_min() . '.js',
				$dep_array,
				$this->timestamp,
				true
			);

			if ( isset( $this->field['color_alpha'] ) && $this->field['color_alpha'] ) {
				if ( ! wp_script_is( 'redux-wp-color-picker-alpha-js' ) ) {
					wp_enqueue_script( 'redux-wp-color-picker-alpha-js' );
				}
			}

			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style( 'redux-color-picker-css' );

				wp_enqueue_style(
					'redux-field-link_color-css',
					Redux_Core::$url . 'inc/fields/link_color/redux-link-color.css',
					array(),
					$this->timestamp
				);
			}
		}

		/**
		 * Compile CSS data for output.
		 *
		 * @param string $data CSS data.
		 *
		 * @return array
		 */
		public function css_style( $data ): array {
			$style = array();

			if ( ! empty( $this->value['regular'] ) && true === $this->field['regular'] && false !== $this->field['default']['regular'] ) {
				$style[] = 'color:' . $this->value['regular'] . ';';
			}

			if ( ! empty( $this->value['visited'] ) && true === $this->field['visited'] && false !== $this->field['default']['visited'] ) {
				$style['visited'] = 'color:' . $this->value['visited'] . ';';
			}

			if ( ! empty( $this->value['hover'] ) && true === $this->field['hover'] && false !== $this->field['default']['hover'] ) {
				$style['hover'] = 'color:' . $this->value['hover'] . ';';
			}

			if ( ! empty( $this->value['active'] ) && true === $this->field['active'] && false !== $this->field['default']['active'] ) {
				$style['active'] = 'color:' . $this->value['active'] . ';';
			}

			if ( ! empty( $this->value['focus'] ) && true === $this->field['focus'] && false !== $this->field['default']['focus'] ) {
				$style['focus'] = 'color:' . $this->value['focus'] . ';';
			}

			return $style;
		}

		/**
		 * Output CSS/compiler.
		 *
		 * @param string|null|array $style Style to output.
		 */
		public function output( $style = '' ) {
			if ( ! empty( $style ) ) {
				if ( isset( $this->field['output'] ) && ! is_array( $this->field['output'] ) ) {
					$this->field['output'] = array( $this->field['output'] );
				}

				if ( ! empty( $this->field['output'] ) && is_array( $this->field['output'] ) ) {
					$style_string = '';

					if ( isset( $this->field['output']['important'] ) ) {
						if ( $this->field['output']['important'] ) {
							$style = str_replace( ';', ' !important;', $style );
						}
						unset( $this->field['output']['important'] );
					}

					foreach ( $style as $key => $value ) {
						if ( is_numeric( $key ) ) {
							$style_string .= implode( ',', $this->field['output'] ) . '{' . $value . '}';
						} else {
							if ( 1 === count( $this->field['output'] ) ) {
								$elem = '';

								foreach ( $this->field['output'] as $elem ) {
									break;
								}

								if ( false !== strpos( $elem, ',' ) ) {
									$selector_arr = explode( ',', $elem );
									$sel_list     = '';

									foreach ( $selector_arr as $selector ) {
										$sel_list .= $selector . ':' . $key . ',';
									}

									$sel_list      = rtrim( $sel_list, ',' );
									$style_string .= $sel_list . '{' . $value . '}';
								} else {
									$style_string .= $elem . ':' . $key . '{' . $value . '}';
								}
							} else {
								$blah = '';
								foreach ( $this->field['output'] as $sel ) {
									$blah .= $sel . ':' . $key . ',';
								}

								$blah          = substr( $blah, 0, strlen( $blah ) - 1 );
								$style_string .= $blah . '{' . $value . '}';
							}
						}
					}

					$this->parent->outputCSS .= $style_string;
				}

				if ( isset( $this->field['compiler'] ) && ! is_array( $this->field['compiler'] ) ) {
					$this->field['compiler'] = array( $this->field['compiler'] );
				}

				if ( ! empty( $this->field['compiler'] ) && is_array( $this->field['compiler'] ) ) {
					$style_string = '';

					if ( isset( $this->field['compiler']['important'] ) ) {
						if ( $this->field['compiler']['important'] ) {
							$style = str_replace( ';', ' !important;', $style );
						}
						unset( $this->field['compiler']['important'] );
					}

					foreach ( $style as $key => $value ) {
						if ( is_numeric( $key ) ) {
							$style_string .= implode( ',', $this->field['compiler'] ) . '{' . $value . '}';
						} else {
							if ( 1 === count( $this->field['compiler'] ) ) {
								$style_string .= $this->field['compiler'][0] . ':' . $key . '{' . $value . '}';
							} else {
								$blah = '';
								foreach ( $this->field['compiler'] as $sel ) {
									$blah .= $sel . ':' . $key . ',';
								}

								$blah          = substr( $blah, 0, strlen( $blah ) - 1 );
								$style_string .= $blah . '{' . $value . '}';
							}
						}
					}
					$this->parent->compilerCSS .= $style_string;
				}
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

class_alias( 'Redux_Link_Color', 'ReduxFramework_Link_Color' );
