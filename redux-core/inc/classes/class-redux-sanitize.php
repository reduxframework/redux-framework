<?php
/**
 * Redux Sanitize Class
 *
 * @class Redux_Sanitize
 * @version 4.0.0
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Sanitize', false ) ) {

	/**
	 * Class Redux_Sanitize
	 */
	class Redux_Sanitize extends Redux_Class {

		/**
		 * Sanitize values from options form (used in settings api validate function)
		 *
		 * @since       4.0.0
		 * @access      public
		 *
		 * @param       array $plugin_options Plugin Options.
		 * @param       array $options Options.
		 * @param       array $sections Sections array.
		 *
		 * @return      array $plugin_options
		 */
		public function sanitize( array $plugin_options, array $options, array $sections ): array {
			$core = $this->core();

			foreach ( $sections as $k => $section ) {
				if ( isset( $section['fields'] ) ) {
					foreach ( $section['fields'] as $field ) {

						if ( is_array( $field ) ) {
							$field['section_id'] = $k;
						}

						if ( isset( $field['type'] ) && ( 'text' === $field['type'] || 'textarea' === $field['type'] || 'multi_text' === $field['type'] ) ) {

							// Make sure 'sanitize' field is set.
							if ( isset( $field['sanitize'] ) ) {

								// Can we make this an array of validations?
								$val_arr = array();

								if ( is_array( $field['sanitize'] ) ) {
									$val_arr = $field['sanitize'];
								} else {
									$val_arr[] = $field['sanitize'];
								}

								foreach ( $val_arr as $function ) {

									// Check for empty id value.
									if ( ! isset( $field['id'] ) || ! isset( $plugin_options[ $field['id'] ] ) || ( '' === $plugin_options[ $field['id'] ] ) ) {
										continue;
									}

									if ( function_exists( $function ) ) {
										if ( empty( $options[ $field['id'] ] ) ) {
											$options[ $field['id'] ] = '';
										}

										if ( is_array( $plugin_options[ $field['id'] ] ) && ! empty( $plugin_options[ $field['id'] ] ) ) {
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

												$value = call_user_func( $function, $before );

												if ( false !== $value ) {
													$plugin_options[ $field['id'] ][ $key ] = $value;
												} else {
													unset( $plugin_options[ $field['id'] ][ $key ] );
												}

												$field['current'] = $value;

												$core->sanitize[] = $field;
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

											$value = call_user_func( $function, $pofi );

											$plugin_options[ $field['id'] ] = $value;

											$field['current'] = $value;

											$core->sanitize[] = $field;
										}

										break;
									}
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
