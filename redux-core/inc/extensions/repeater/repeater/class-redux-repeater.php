<?php
/**
 * Redux Repeater Field Class
 *
 * @package Redux
 * @author  Dovy Paukstys & Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Repeater
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Repeater' ) ) {

	/**
	 * Class Redux_Repeater
	 */
	class Redux_Repeater extends Redux_Field {

		/**
		 * Repeater values.
		 *
		 * @var string
		 */
		private $repeater_values;

		/**
		 * Set defaults.
		 */
		public function set_defaults() {
			$this->repeater_values = '';

			if ( ! isset( $this->field['bind_title'] ) && ! empty( $this->field['fields'] ) ) {
				$this->field['bind_title'] = $this->field['fields'][0]['id'];
			}

			$default = array(
				'group_values'  => false,
				'item_name'     => '',
				'bind_title'    => true,
				'sortable'      => true,
				'limit'         => 10,
				'init_empty'    => false,
				'panels_closed' => false,
			);

			$this->field = wp_parse_args( $this->field, $default );
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
			if ( isset( $this->field['group_values'] ) && $this->field['group_values'] ) {
				$this->repeater_values = '[' . $this->field['id'] . ']';
			}

			if ( false === $this->field['init_empty'] ) {
				$title = '';

				if ( empty( $this->value ) || ! is_array( $this->value ) ) {
					$this->value = array(
						'redux_repeater_data' => array(
							array(
								'title' => $title,
							),
						),
					);
				}
			}

			if ( isset( $this->field['subfields'] ) && empty( $this->field['fields'] ) ) {
				$this->field['fields'] = $this->field['subfields'];
				unset( $this->field['subfields'] );
			}

			echo '<div class="redux-repeater-accordion" data-id="' . esc_attr( $this->field['id'] ) . '" data-panels-closed="' . esc_attr( $this->field['panels_closed'] ) . '">';

			$x = 0;

			if ( isset( $this->value['redux_repeater_data'] ) && is_array( $this->value['redux_repeater_data'] ) && ! empty( $this->value['redux_repeater_data'] ) ) {
				$repeaters = $this->value['redux_repeater_data'];

				if ( '' === $this->field['bind_title'] ) {
					$keys                      = array_keys( min( $repeaters ) );
					$this->field['bind_title'] = $keys[0];
				}
				foreach ( $repeaters as $repeater ) {
					if ( empty( $repeater ) ) {
						continue;
					}

					echo '<div class="redux-repeater-accordion-repeater" data-sortid="' . esc_attr( $x ) . '">';
					echo '<table style="margin-top: 0;" class="redux-repeater-accordion redux-repeater form-table no-border">';
					echo '<fieldset class="redux-field " data-id="' . esc_attr( $this->field['id'] ) . '">';

					echo '<input type="hidden" name="' . esc_attr( $this->parent->args['opt_name'] ) . '[' . esc_attr( $this->field['id'] ) . '][redux_repeater_data][' . esc_attr( $x ) . '][title]" value="' . esc_attr( $repeater['title'] ) . '" class="regular-text slide-title" data-key="' . esc_attr( $x ) . '" />';

					if ( isset( $this->field['bind_title'] ) ) {
						foreach ( $this->field['fields'] as $field ) {
							if ( $field['id'] === $this->field['bind_title'] ) {
								if ( isset( $field['options'] ) ) {

									// Sorter data filter.
									if ( 'sorter' === $field['type'] && isset( $field['data'] ) && ! empty( $field['data'] ) && is_array( $field['data'] ) ) {
										if ( ! isset( $field['args'] ) ) {
											$field['args'] = array();
										}

										foreach ( $field['data'] as $key => $data ) {
											if ( ! isset( $field['args'][ $key ] ) ) {
												$field['args'][ $key ] = array();
											}

											$field['options'][ $key ] = $this->parent->get_wordpress_data( $data, $field['args'][ $key ] );
										}
									}
								}

								$default = $field['default'] ?? '';

								if ( ! empty( $this->repeater_values ) ) {
									$repeater['title'] = ! isset( $this->parent->options[ $this->field['id'] ][ $field['id'] ][ $x ] ) ? $default : $this->parent->options[ $this->field['id'] ][ $field['id'] ][ $x ];
								} else {
									$repeater['title'] = ! isset( $this->parent->options[ $field['id'] ][ $x ] ) ? $default : $this->parent->options[ $field['id'] ][ $x ];
								}

								if ( isset( $field['options'] ) && is_array( $field['options'] ) && ! empty( $field['options'] ) ) {
									if ( isset( $field['options'][ $repeater['title'] ] ) ) {
										$repeater['title'] = $field['options'][ $repeater['title'] ];
									}
								}
							}
						}
					}

					if ( is_array( $repeater['title'] ) ) {
						$repeater['title'] = esc_html__( 'Title', 'redux-framework' );
					}

					echo '<h3><span class="redux-repeater-header">' . esc_html( $repeater['title'] ) . ' </span></h3>';

					echo '<div>';

					foreach ( $this->field['fields'] as $field ) {
						if ( ! isset( $field['class'] ) ) {
							$field['class'] = '';
						}

						if ( isset( $this->field['bind_title'] ) && $field['id'] === $this->field['bind_title'] ) {
							$field['class'] .= ' bind_title';
						}

						if ( 'repeater' === $field['type'] || 'social_profiles' === $field['type'] || 'color_scheme' === $field['type'] ) {
							echo esc_html__( 'The', 'redux-framework' ) . ' <code>' . esc_html( $field['type'] ) . '</code> ' . esc_html__( 'field is not supported within the Repeater field.', 'redux-framework' );
						} else {
							$this->output_field( $field, $x );
						}
					}

					if ( ! isset( $this->field['static'] ) && empty( $this->field['static'] ) ) {
						echo '<a href="javascript:void(0);" class="button deletion redux-repeaters-remove">' . esc_html__( 'Delete', 'redux-framework' ) . ' ' . esc_html( $this->field['item_name'] ) . '</a>';
					}

					echo '</div>';
					echo '</fieldset>';
					echo '</table>';
					echo '</div>';

					$x ++;
				}
			}

			if ( 0 === $x || ( isset( $this->field['static'] ) && ( $x - 1 ) < $this->field['static'] ) ) {
				if ( isset( $this->field['static'] ) && $x < $this->field['static'] ) {
					$loop = $this->field['static'] - $x;
				} else {
					$loop = 1;
				}

				$class = '';

				if ( 0 === $x && true === $this->field['init_empty'] ) {
					$class = 'close-me';
				}

				while ( $loop > 0 ) {
					echo '<div class="redux-repeater-accordion-repeater ' . esc_attr( $class ) . '">';
					echo '<table style="margin-top: 0;" class="redux-repeater-accordion redux-repeater form-table no-border">';
					echo '<fieldset class="redux-field" data-id="' . esc_attr( $this->field['id'] ) . '">';

					if ( ! isset( $this->value['redux_repeater_data'][ $x ]['title'] ) && is_array( $this->value ) && isset( $this->value['redux_repeater_data'] ) && is_array( $this->value['redux_repeater_data'] ) ) {
						$this->value['redux_repeater_data'][ $x ]['title'] = null;
					}

					echo '<input type="hidden" name="' . esc_attr( $this->parent->args['opt_name'] ) . '[' . esc_attr( $this->field['id'] ) . '][redux_repeater_data][' . intval( $x ) . '][title]" value="" class="regular-text slide-title" />';

					echo '<h3><span class="redux-repeater-header"> </span></h3>';
					echo '<div>';

					foreach ( $this->field['fields'] as $field ) {
						if ( isset( $this->field['bind_title'] ) && $field['id'] === $this->field['bind_title'] ) {
							if ( ! isset( $field['class'] ) || ( isset( $field['title'] ) && empty( $field['title'] ) ) ) {
								$field['class'] = 'bind_title';
							} else {
								$field['class'] .= ' bind_title';
							}
						}

						$this->output_field( $field, $x );
					}

					if ( ! isset( $this->field['static'] ) && empty( $this->field['static'] ) ) {
						echo '<a href="javascript:void(0);" class="button deletion redux-repeaters-remove">' . esc_html__( 'Delete', 'redux-framework' ) . ' ' . esc_html( $this->field['item_name'] ) . '</a>';
					}

					echo '</div>';
					echo '</fieldset>';
					echo '</table>';
					echo '</div>';

					$x ++;
					$loop --;
				}
			}

			echo '</div>';
			if ( ! isset( $this->field['static'] ) && empty( $this->field['static'] ) ) {
				$disabled = '';

				if ( isset( $this->field['limit'] ) && is_integer( $this->field['limit'] ) ) {
					if ( $x >= $this->field['limit'] ) {
						$disabled = ' button-disabled';
					}
				}

				echo '<a href="javascript:void(0);" class="button redux-repeaters-add button-primary' . esc_attr( $disabled ) . '" data-name="' . esc_attr( $this->parent->args['opt_name'] . $this->repeater_values ) . '[title][]">' . esc_html__( 'Add', 'redux-framework' ) . ' ' . esc_html( $this->field['item_name'] ) . '</a><br/>';
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
			wp_print_styles( 'editor-buttons' );

			// Set up min files for dev_mode = false.
			$min = Redux_Functions::is_min();

			wp_enqueue_script(
				'redux-field-repeater-js',
				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				$this->url . 'redux-repeater' . $min . '.js',
				array( 'jquery', 'jquery-ui-core', 'jquery-ui-accordion', 'jquery-ui-sortable', 'wp-color-picker', 'redux-js' ),
				Redux_Extension_Repeater::$version,
				true
			);

			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-field-repeater-css',
					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					$this->url . 'redux-repeater.css',
					array(),
					time()
				);
			}
		}

		/**
		 * Output field.
		 *
		 * @param array $field Field array.
		 * @param int   $x     Index.
		 */
		public function output_field( array $field, int $x ) {
			$this->enqueue_dependencies( $field );

			if ( ! isset( $field['class'] ) ) {
				$field['class'] = '';
			}

			$field['class'] .= ' repeater';

			if ( ! empty( $field['title'] ) ) {
				echo '<h4>' . wp_kses_post( $field['title'] ) . '</h4>';
			}

			if ( ! empty( $field['subtitle'] ) ) {
				echo '<span class="description">' . wp_kses_post( $field['subtitle'] ) . '</span>';
			}

			$orig_field_id = $field['id'];

			$field['id']          = $field['id'] . '-' . $x;
			$field['name']        = $this->parent->args['opt_name'] . $this->repeater_values . '[' . $orig_field_id . ']';
			$field['name_suffix'] = '[' . $x . ']';
			$field['class']      .= ' in-repeater';
			$field['repeater_id'] = $orig_field_id;

			if ( isset( $field['options'] ) ) {

				// Sorter data filter.
				if ( 'sorter' === $field['type'] && isset( $field['data'] ) && ! empty( $field['data'] ) && is_array( $field['data'] ) ) {
					if ( ! isset( $field['args'] ) ) {
						$field['args'] = array();
					}

					foreach ( $field['data'] as $key => $data ) {
						if ( ! isset( $field['args'][ $key ] ) ) {
							$field['args'][ $key ] = array();
						}
						$field['options'][ $key ] = $this->parent->get_wordpress_data( $data, $field['args'][ $key ] );
					}
				}
			}

			$default = $field['default'] ?? '';

			if ( ! empty( $this->repeater_values ) ) {
				$value = empty( $this->parent->options[ $this->field['id'] ][ $orig_field_id ][ $x ] ) ? $default : $this->parent->options[ $this->field['id'] ][ $orig_field_id ][ $x ];
			} else {
				$value = empty( $this->parent->options[ $orig_field_id ][ $x ] ) ? $default : $this->parent->options[ $orig_field_id ][ $x ];
			}

			ob_start();

			$this->parent->render_class->field_input( $field, $value );

			$content = ob_get_contents();

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$_field = apply_filters( 'redux-support-repeater', $content, $field, 0 );

			ob_end_clean();

			echo $_field; // phpcs:ignore WordPress.Security.EscapeOutput
		}


		/**
		 * Localize.
		 *
		 * @param array  $field Field array.
		 * @param string $value Value.
		 *
		 * @return array|string
		 */
		public function localize( array $field, string $value = '' ) {
			if ( isset( $field['subfields'] ) && empty( $field['fields'] ) ) {
				$field['fields'] = $field['subfields'];
				unset( $field['subfields'] );
			}

			if ( isset( $field['group_values'] ) && $field['group_values'] ) {
				$this->repeater_values = '[' . $field['id'] . ']';
			}

			$var = '';

			if ( '' === $value ) {
				$value = array();
			}

			if ( isset( $field['fields'] ) && ! empty( $field['fields'] ) ) {
				ob_start();

				foreach ( $field['fields'] as $f ) {
					if ( isset( $this->field['bind_title'] ) && $f['id'] === $this->field['bind_title'] ) {
						if ( ! isset( $f['class'] ) || ( isset( $f['title'] ) && empty( $f['title'] ) ) ) {
							$f['class'] = 'bind_title';
						} else {
							$f['class'] .= ' bind_title';
						}
					}

					$this->output_field( $f, 99999 );
				}

				$var = ob_get_contents();

				$var = array(
					'html'     => $var . '<a href="javascript:void(0);" class="button deletion redux-repeaters-remove">' . esc_html__( 'Delete', 'redux-framework' ) . '</a>',
					'count'    => count( $value ),
					'sortable' => true,
					'limit'    => '',
					'name'     => $this->parent->args['opt_name'] . '[' . $field['id'] . '][0]',
				);

				if ( isset( $field['sortable'] ) && is_bool( $this->field['sortable'] ) ) {
					$var['sortable'] = $field['sortable'];
				}
				if ( isset( $field['limit'] ) && is_integer( $field['limit'] ) ) {
					$var['limit'] = $field['limit'];
				}

				ob_end_clean();
			}

			return $var;
		}

		/**
		 * Enqueue Deps.
		 *
		 * @param array $field Field.
		 */
		private function enqueue_dependencies( array $field ) {
			$field_type = $field['type'];

			$field_class = 'Redux_' . $field_type;

			if ( ! class_exists( $field_class ) ) {
				$field_type = str_replace( '_', '-', $field_type );

				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				$class_file = apply_filters( 'redux-typeclass-load', ReduxFramework::$_dir . 'inc/fields/' . $field_type . '/class-redux-' . $field_type . '.php', $field_class );

				if ( file_exists( $class_file ) ) {
					require_once $class_file;
				}
			}

			if ( class_exists( $field_class ) && method_exists( $field_class, 'enqueue' ) ) {
				$enqueue = new $field_class( '', '', $this->parent );
				$enqueue->enqueue();
			}

			if ( class_exists( $field_class ) && method_exists( $field_class, 'localize' ) ) {

				$enqueue = new $field_class( '', '', $this->parent );

				$data = $enqueue->localize( $field );

				$this->parent->enqueue_class->localize_data[ $field['type'] ][ $field['id'] ] = $data;
			}
		}
	}
}
