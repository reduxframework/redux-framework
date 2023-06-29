<?php
/**
 * Redux Validation Class
 *
 * @class   Redux_Validation
 * @version 4.0.0
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Validation', false ) ) {

	/**
	 * Class Redux_Validation
	 */
	class Redux_Validation extends Redux_Class {

		/**
		 * Validate values from options form (used in settings api validate function)
		 * calls the custom validation class for the field so authors can override with custom classes
		 *
		 * @since       1.0.0
		 * @access      public
		 *
		 * @param       array $plugin_options Plugin Options.
		 * @param       array $options        Options.
		 * @param       array $sections       Sections array.
		 *
		 * @return      array $plugin_options
		 */
		public function validate( array $plugin_options, array $options, array $sections ): array {
			$core = $this->core();

			foreach ( $sections as $k => $section ) {
				if ( isset( $section['fields'] ) ) {
					foreach ( $section['fields'] as $fkey => $field ) {
						if ( is_array( $field ) ) {
							$field['section_id'] = $k;
						}

						if ( isset( $field['type'] ) && ( 'checkbox' === $field['type'] || 'checkbox_hide_below' === $field['type'] || 'checkbox_hide_all' === $field['type'] ) ) {
							if ( ! isset( $plugin_options[ $field['id'] ] ) ) {
								$plugin_options[ $field['id'] ] = 0;
							}
						}

						// Part of Dovy's serialize typography effort.  Preserved here in case it becomes a thing. - kp.
						/**
						 * If ( isset ( $field['type'] ) && $field['type'] == 'typography' ) {
						 *      if ( ! is_array( $plugin_options[ $field['id'] ] ) && ! empty( $plugin_options[ $field['id'] ] ) ) {
						 *          $plugin_options[ $field['id'] ] = json_decode( $plugin_options[ $field['id'] ], true );
						 *      }
						 * }
						 */

						if ( isset( $core->extensions[ $field['type'] ] ) && method_exists( $core->extensions[ $field['type'] ], '_validate_values' ) ) {
							$plugin_options = $core->extensions[ $field['type'] ]->_validate_values( $plugin_options, $field, $sections );
						}

						// Make sure 'validate' field is set.
						if ( isset( $field['validate'] ) ) {

							// Can we make this an array of validations?
							$val_arr = array();

							if ( is_array( $field['validate'] ) ) {
								$val_arr = $field['validate'];
							} else {
								$val_arr[] = $field['validate'];
							}

							foreach ( $val_arr as $idx => $val ) {
								// shim for old *_not_empty validations.
								if ( 'email_not_empty' === $val || 'numeric_not_empty' === $val ) {
									$val = 'not_empty';
								}

								// Make sure 'validate field' is set to 'not_empty'.
								$is_not_empty = false;

								if ( 'not_empty' === $val ) {
									// Set the flag.
									$is_not_empty = true;
								}

								// Check for empty id value.
								if ( ! isset( $field['id'] ) || ! isset( $plugin_options[ $field['id'] ] ) || ( '' === $plugin_options[ $field['id'] ] ) ) {

									// If we are looking for an empty value, in the case of 'not_empty'
									// then we need to keep processing.
									if ( ! $is_not_empty ) {

										// Empty id and not checking for 'not_empty'.  Bail out...
										if ( ! isset( $field['validate_callback'] ) ) {
											continue;
										}
									}
								}

								// Force validate of custom field types.
								if ( isset( $field['type'] ) && ! isset( $val ) && ! isset( $field['validate_callback'] ) ) {
									if ( 'color' === $field['type'] || 'color_gradient' === $field['type'] ) {
										$val = 'color';
									} elseif ( 'date' === $field['type'] ) {
										$val = 'date';
									}
								}

								// No need.  Spectrum self validates.
								if ( 'color_rgba' === $field['type'] ) {
									continue;
								}

								// Shim out old color rgba validators.
								if ( 'color_rgba' === $val || 'colorrgba' === $val ) {
									$val = 'color';
								}

								$validate = 'Redux_Validation_' . $val;

								if ( ! class_exists( $validate ) ) {
									$file = str_replace( '_', '-', $val );

									/**
									 * Filter 'redux/validate/{opt_name}/class/{field.validate}'
									 *
									 * @param string $validate   validation class file path
									 */

									// phpcs:ignore WordPress.NamingConventions.ValidHookName
									$class_file = apply_filters( "redux/validate/{$core->args['opt_name']}/class/$val", Redux_Core::$dir . "inc/validation/$val/class-redux-validation-$file.php", $validate );

									if ( $class_file ) {
										if ( file_exists( $class_file ) ) {
											require_once $class_file;
										}
									}
								}

								if ( class_exists( $validate ) ) {
									if ( empty( $options[ $field['id'] ] ) ) {
										$options[ $field['id'] ] = '';
									}

									if ( isset( $plugin_options[ $field['id'] ] ) && is_array( $plugin_options[ $field['id'] ] ) && ! empty( $plugin_options[ $field['id'] ] ) ) {
										foreach ( $plugin_options[ $field['id'] ] as $key => $value ) {
											$before = null;
											$after  = null;

											if ( isset( $plugin_options[ $field['id'] ][ $key ] ) && ( ! empty( $plugin_options[ $field['id'] ][ $key ] ) || '0' === $plugin_options[ $field['id'] ][ $key ] ) ) {
												if ( is_array( $plugin_options[ $field['id'] ][ $key ] ) ) {
													$before = $plugin_options[ $field['id'] ][ $key ];
												} else {
													$before = trim( $plugin_options[ $field['id'] ][ $key ] );
												}
											}

											if ( isset( $options[ $field['id'] ][ $key ] ) && ( ! empty( $plugin_options[ $field['id'] ][ $key ] ) || '0' === $plugin_options[ $field['id'] ][ $key ] ) ) {
												$after = $options[ $field['id'] ][ $key ];
											}

											$validation = new $validate( $core, $field, $before, $after );

											if ( ! empty( $validation->value ) || '0' === $validation->value ) {
												$plugin_options[ $field['id'] ][ $key ] = $validation->value;
											} else {
												unset( $plugin_options[ $field['id'] ][ $key ] );
											}

											if ( ! empty( $validation->error ) ) {
												$core->errors[] = $validation->error;
											}

											if ( ! empty( $validation->warning ) ) {
												$core->warnings[] = $validation->warning;
											}

											if ( ! empty( $validation->sanitize ) ) {
												$core->sanitize[] = $validation->sanitize;
											}
										}
									} else {
										if ( isset( $plugin_options[ $field['id'] ] ) ) {
											if ( is_array( $plugin_options[ $field['id'] ] ) ) {
												$pofi = $plugin_options[ $field['id'] ];
											} else {
												$pofi = trim( $plugin_options[ $field['id'] ] );
											}
										} else {
											$pofi = null;
										}

										$validation                     = new $validate( $core, $field, $pofi, $options[ $field['id'] ] );
										$plugin_options[ $field['id'] ] = $validation->value;

										if ( ! empty( $validation->error ) ) {
											$core->errors[] = $validation->error;
										}

										if ( ! empty( $validation->warning ) ) {
											$core->warnings[] = $validation->warning;
										}

										if ( ! empty( $validation->sanitize ) ) {
											$core->sanitize[] = $validation->sanitize;
										}
									}

									break;
								}
							}
						}

						if ( isset( $field['validate_callback'] ) && ( is_callable( $field['validate_callback'] ) || ( is_string( $field['validate_callback'] ) && function_exists( $field['validate_callback'] ) ) ) ) {
							$callback = $field['validate_callback'];
							unset( $field['validate_callback'] );

							$plugin_option = $plugin_options[ $field['id'] ] ?? null;
							$option        = $options[ $field['id'] ] ?? null;

							if ( null !== $plugin_option ) {
								$callbackvalues = call_user_func( $callback, $field, $plugin_option, $option );

								$plugin_options[ $field['id'] ] = $callbackvalues['value'];

								if ( isset( $callbackvalues['error'] ) ) {
									$core->errors[] = $callbackvalues['error'];
								}

								if ( isset( $callbackvalues['warning'] ) ) {
									$core->warnings[] = $callbackvalues['warning'];
								}

								if ( isset( $callbackvalues['sanitize'] ) ) {
									$core->sanitize[] = $callbackvalues['sanitize'];
								}
							}
						}
					}
				}
			}

			return $plugin_options;
		}
	}
}
