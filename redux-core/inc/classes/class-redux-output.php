<?php
/**
 * Redux Output Class
 *
 * @class   Redux_Output
 * @version 3.0.0
 * @package Redux Framework/Classes
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Output', false ) ) {

	/**
	 * Class Redux_Output
	 */
	class Redux_Output extends Redux_Class {

		/**
		 * Redux_Output constructor.
		 *
		 * @param object $parent ReduxFramework pointer.
		 */
		public function __construct( $parent ) {
			parent::__construct( $parent );

			// Output dynamic CSS.
			// Frontend: Maybe enqueue dynamic CSS and Google fonts.
			if ( empty( $this->args['output_location'] ) || in_array( 'frontend', $this->args['output_location'], true ) ) {
				add_action( 'wp_head', array( $this, 'output_css' ), 150 );
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), 150 );
			}

			// Login page: Maybe enqueue dynamic CSS and Google fonts.
			if ( in_array( 'login', $this->args['output_location'], true ) ) {
				add_action( 'login_head', array( $this, 'output_css' ), 150 );
				add_action( 'login_enqueue_scripts', array( $this, 'enqueue' ), 150 );
			}

			// Admin area: Maybe enqueue dynamic CSS and Google fonts.
			if ( in_array( 'admin', $this->args['output_location'], true ) ) {
				add_action( 'admin_head', array( $this, 'output_css' ), 150 );
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 150 );
			}

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( "redux/output/{$this->parent->args['opt_name']}/construct", $this );
			// Useful for adding different locations for CSS output.
		}

		/**
		 * Enqueue CSS and Google fonts for front end
		 *
		 * @return      void
		 * @since       1.0.0
		 * @access      public
		 */
		public function enqueue() {
			$core = $this->core();

			if ( false === $core->args['output'] && false === $core->args['compiler'] ) {
				return;
			}

			foreach ( $core->sections as $k => $section ) {
				if ( isset( $section['type'] ) && ( 'divide' === $section['type'] ) ) {
					continue;
				}

				if ( isset( $section['fields'] ) ) {
					foreach ( $section['fields'] as $fieldk => $field ) {
						if ( isset( $field['type'] ) && 'callback' !== $field['type'] ) {
							$field_classes = array( 'Redux_' . $field['type'], 'ReduxFramework_' . $field['type'] );

							$field_class = Redux_Functions::class_exists_ex( $field_classes );

							if ( false === $field_class ) {
								if ( ! isset( $field['compiler'] ) ) {
									$field['compiler'] = '';
								}

								/**
								 * Field class file
								 * filter 'redux/{opt_name}/field/class/{field.type}
								 *
								 * @param string        field class file
								 * @param array $field field config data
								 */
								$field_type = str_replace( '_', '-', $field['type'] );
								$core_path  = Redux_Core::$dir . "inc/fields/{$field['type']}/class-redux-$field_type.php";

								if ( ! file_exists( $core_path ) ) {
									$core_path = Redux_Core::$dir . "inc/fields/{$field['type']}/field_{$field['type']}.php";
								}

								if ( Redux_Core::$pro_loaded ) {
									$pro_path = '';

									if ( class_exists( 'Redux_Pro' ) ) {
										$pro_path = Redux_Pro::$dir . "core/inc/fields/{$field['type']}/class-redux-$field_type.php";
									}

									if ( file_exists( $pro_path ) ) {
										$filter_path = $pro_path;
									} else {
										$filter_path = $core_path;
									}
								} else {
									$filter_path = $core_path;
								}

								// phpcs:ignore WordPress.NamingConventions.ValidHookName
								$class_file = apply_filters( "redux/{$core->args['opt_name']}/field/class/{$field['type']}", $filter_path, $field );

								if ( $class_file && file_exists( $class_file ) && ( ! class_exists( $field_class ) ) ) {
									require_once $class_file;

									$field_class = Redux_Functions::class_exists_ex( $field_classes );
								}
							}

							$field['default'] = $field['default'] ?? '';
							$value            = $core->options[ $field['id'] ] ?? $field['default'];
							$style_data       = '';
							$data             = array(
								'field' => $field,
								'value' => $value,
								'core'  => $core,
								'mode'  => 'output',
							);

							Redux_Functions::load_pro_field( $data );

							if ( empty( $field_class ) ) {
								continue;
							}

							$field_object = new $field_class( $field, $value, $core );

							if ( ! empty( $core->options[ $field['id'] ] ) && class_exists( $field_class ) && method_exists( $field_class, 'output' ) && $this->can_output_css( $core, $field ) ) {

								// phpcs:ignore WordPress.NamingConventions.ValidHookName
								$field = apply_filters( "redux/field/{$core->args['opt_name']}/output_css", $field );

								if ( ! empty( $field['output'] ) && ! is_array( $field['output'] ) ) {
									$field['output'] = array( $field['output'] );
								}

								if ( ( ( isset( $field['output'] ) && ! empty( $field['output'] ) ) || ( isset( $field['compiler'] ) && ! empty( $field['compiler'] ) ) || isset( $field['media_query'] ) && ! empty( $field['media_query'] ) || 'typography' === $field['type'] || 'icon_select' === $field['type'] ) ) {
									if ( method_exists( $field_class, 'css_style' ) ) {
										$style_data = $field_object->css_style( $field_object->value );
									}
								}

								if ( null !== $style_data ) {
									if ( ( ( isset( $field['output'] ) && ! empty( $field['output'] ) ) || ( isset( $field['compiler'] ) && ! empty( $field['compiler'] ) ) || 'typography' === $field['type'] || 'icon_select' === $field['type'] ) ) {
										$field_object->output( $style_data );
									}

									if ( isset( $field['media_query'] ) && ! empty( $field['media_query'] ) ) {
										$field_object->media_query( $style_data );
									}
								}
							}

							// phpcs:ignore WordPress.NamingConventions.ValidHookName
							do_action( "redux/field/{$core->args['opt_name']}/output_loop", $core, $field, $value, $style_data );

							// phpcs:ignore WordPress.NamingConventions.ValidHookName
							do_action( "redux/field/{$core->args['opt_name']}/output_loop/{$field['type']}", $core, $field, $value, $style_data );

							if ( method_exists( $field_class, 'output_variables' ) && $this->can_output_css( $core, $field ) ) {
								$passed_style_data = $field_object->output_variables( $style_data );
								$this->output_variables( $core, $section, $field, $value, $passed_style_data );
							}
						}
					}

					if ( ! empty( $core->outputCSS ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
						// phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
						$core->outputCSS = html_entity_decode( $core->outputCSS, ENT_QUOTES, 'UTF-8' );
					}
				}
			}

			// For use like in the customizer. Stops the output, but passes the CSS in the variable for the compiler.
			if ( isset( $core->no_output ) ) {
				return;
			}

			if ( ! empty( $core->typography ) && filter_var( $core->args['output'], FILTER_VALIDATE_BOOLEAN ) ) {
				$version = ! empty( $core->transients['last_save'] ) ? $core->transients['last_save'] : '';
				if ( ! class_exists( 'Redux_Typography' ) ) {
					require_once Redux_Core::$dir . '/inc/fields/typography/class-redux-typography.php';
				}
				$typography = new Redux_Typography( null, null, $core );

				if ( ! $core->args['disable_google_fonts_link'] ) {
					$url = $typography->make_google_web_font_link( $core->typography );
					wp_enqueue_style( 'redux-google-fonts-' . $core->args['opt_name'], $url, array(), $version );
					add_filter( 'style_loader_tag', array( $this, 'add_style_attributes' ), 10, 4 );
					add_filter( 'wp_resource_hints', array( $this, 'google_fonts_preconnect' ), 10, 2 );
				}
			}
		}

		/**
		 * Add Google Fonts preconnect link.
		 *
		 * @param array  $urls              HTML to be added.
		 * @param string $relationship_type Handle name.
		 *
		 * @return      array
		 * @since       4.1.15
		 * @access      public
		 */
		public function google_fonts_preconnect( array $urls, string $relationship_type ): array {
			if ( 'preconnect' !== $relationship_type ) {
				return $urls;
			}
			$urls[] = array(
				'rel'  => 'preconnect',
				'href' => 'https://fonts.gstatic.com',
				'crossorigin',
			);
			return $urls;
		}

		/**
		 * Filter to enhance the google fonts enqueue.
		 *
		 * @param string $html   HTML to be added.
		 * @param string $handle Handle name.
		 * @param string $href   HREF URL of script.
		 * @param string $media  Media type.
		 *
		 * @return      string
		 * @since       4.1.15
		 * @access      public
		 */
		public function add_style_attributes( string $html = '', string $handle = '', string $href = '', string $media = '' ): string {
			if ( Redux_Functions_Ex::string_starts_with( $handle, 'redux-google-fonts-' ) ) {
				// Revamp thanks to Harry: https://csswizardry.com/2020/05/the-fastest-google-fonts/.
				$href      = str_replace( array( '|', ' ' ), array( '%7C', '%20' ), urldecode( $href ) );
				$new_html  = '<link rel="preload" as="style" href="' . esc_attr( $href ) . '" />';
				$new_html .= '<link rel="stylesheet" href="' . esc_attr( $href ) . '" media="print" onload="this.media=\'all\'">';  // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet
				$new_html .= '<noscript><link rel="stylesheet" href="' . esc_attr( $href ) . '" /></noscript>'; // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet
				$html      = $new_html;
			}

			return $html;
		}

		/**
		 * Function to output output_variables to the dynamic output.
		 *
		 * @param object       $core       ReduxFramework core pointer.
		 * @param array        $section    Section containing this field.
		 * @param array        $field      Field object.
		 * @param array|string $value      Current value of field.
		 * @param string|null  $style_data CSS output string to append to the root output variable.
		 *
		 * @return      void
		 * @since       4.0.3
		 * @access      public
		 */
		private function output_variables( $core, array $section = array(), array $field = array(), $value = array(), ?string $style_data = '' ) {
			// Let's allow section overrides please.
			if ( isset( $section['output_variables'] ) && ! isset( $field['output_variables'] ) ) {
				$field['output_variables'] = $section['output_variables'];
			}
			if ( isset( $section['output_variables_prefix'] ) && ! isset( $field['output_variables_prefix'] ) ) {
				$field['output_variables_prefix'] = $section['output_variables_prefix'];
			}
			if ( isset( $field['output_variables'] ) && $field['output_variables'] ) {
				$output_variables_prefix = $core->args['output_variables_prefix'];
				if ( isset( $field['output_variables_prefix'] ) && ! empty( $field['output_variables_prefix'] ) ) {
					$output_variables_prefix = $field['output_variables_prefix'];
				} elseif ( isset( $section['output_variables_prefix'] ) && ! empty( $section['output_variables_prefix'] ) ) {
					$output_variables_prefix = $section['output_variables_prefix'];
				}

				if ( is_array( $value ) ) {
					$val_pieces = array_filter( $value, 'strlen' );
					// We don't need to show the Google boolean.
					if ( 'typography' === $field['type'] && isset( $val_pieces['google'] ) ) {
						unset( $val_pieces['google'] );
					}

					foreach ( $val_pieces as $val_key => $val_val ) {
						$val_key                            = $output_variables_prefix . sanitize_title_with_dashes( $field['id'] ) . '-' . $val_key;
						$core->output_variables[ $val_key ] = $val_val;
						if ( ! empty( $style_data ) ) {
							$val_key                            = $output_variables_prefix . sanitize_title_with_dashes( $field['id'] );
							$core->output_variables[ $val_key ] = $style_data;
						}
					}
				} else {
					$val_key = $output_variables_prefix . sanitize_title_with_dashes( $field['id'] );

					if ( ! empty( $style_data ) ) {
						$core->output_variables[ $val_key ] = $style_data;
					} else {
						$core->output_variables[ $val_key ] = $value;
					}
				}
			}
		}

		/**
		 * Output dynamic CSS at bottom of HEAD
		 *
		 * @return      void
		 * @since       3.2.8
		 * @access      public
		 */
		public function output_css() {
			$core = $this->core();

			if ( false === $core->args['output'] && false === $core->args['compiler'] && empty( $core->output_variables ) ) {
				return;
			}

			if ( isset( $core->no_output ) ) {
				return;
			}

			if ( ! empty( $core->output_variables ) ) {
				$root_css = ':root{';
				foreach ( $core->output_variables as $key => $value ) {
					$root_css .= "$key:$value;";
				}
				$root_css .= '}';
				// phpcs:ignore WordPress.NamingConventions.ValidVariableName, WordPress.Security.EscapeOutput
				$core->outputCSS = $root_css . $core->outputCSS;
			}

			// phpcs:ignore WordPress.NamingConventions.ValidVariableName
			if ( ! empty( $core->outputCSS ) && ( true === $core->args['output_tag'] || ( isset( $_POST['customized'] ) && isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'preview-customize_' . wp_get_theme()->get_stylesheet() ) ) ) ) {
				// phpcs:ignore WordPress.NamingConventions.ValidVariableName, WordPress.Security.EscapeOutput
				echo '<style id="' . esc_attr( $core->args['opt_name'] ) . '-dynamic-css" title="dynamic-css" class="redux-options-output">' . $core->outputCSS . '</style>';
			}
		}

		/**
		 * Can Output CSS
		 * Check if a field meets its requirements before outputting to CSS
		 *
		 * @param object $core  ReduxFramework core pointer.
		 * @param array  $field Field array.
		 *
		 * @return bool
		 */
		private function can_output_css( $core, array $field ): ?bool {
			$return = true;

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$field = apply_filters( "redux/field/{$core->args['opt_name']}/_can_output_css", $field );

			if ( isset( $field['force_output'] ) && true === $field['force_output'] ) {
				return true;
			}

			if ( ! empty( $field['required'] ) ) {
				if ( isset( $field['required'][0] ) ) {
					if ( ! is_array( $field['required'][0] ) && 3 === count( $field['required'] ) ) {
						$parent_value = $GLOBALS[ $core->args['global_variable'] ][ $field['required'][0] ] ?? '';
						$check_value  = $field['required'][2];
						$operation    = $field['required'][1];
						$return       = $core->required_class->compare_value_dependencies( $parent_value, $check_value, $operation );
					} elseif ( is_array( $field['required'][0] ) ) {
						foreach ( $field['required'] as $required ) {
							if ( isset( $required[0] ) && ! is_array( $required[0] ) && 3 === count( $required ) ) {
								$parent_value = $GLOBALS[ $core->args['global_variable'] ][ $required[0] ] ?? '';
								$check_value  = $required[2];
								$operation    = $required[1];
								$return       = $core->required_class->compare_value_dependencies( $parent_value, $check_value, $operation );
							}
							if ( ! $return ) {
								return $return;
							}
						}
					}
				}
			}

			return $return;
		}

	}

}
