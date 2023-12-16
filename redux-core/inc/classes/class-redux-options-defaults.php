<?php
/**
 * Redux Option Defaults Class
 *
 * @class Redux_Options_Defaults
 * @version 4.0.0
 * @package Redux Framework/Classes
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Options_Defaults', false ) ) {

	/**
	 * Class Redux_Options_Defaults
	 */
	class Redux_Options_Defaults {

		/**
		 * Default options.
		 *
		 * @var array
		 */
		public $options_defaults = array();

		/**
		 * Field array.
		 *
		 * @var array
		 */
		public $fields = array();

		/**
		 * Is repeater group flag.
		 *
		 * @var bool
		 */
		private $is_repeater_group = false;

		/**
		 * Repeater ID.
		 *
		 * @var bool
		 */
		private $repeater_id = '';

		/**
		 * Creates a default options array.
		 *
		 * @param string $opt_name      Panel opt_name.
		 * @param array  $sections      Panel sections array.
		 * @param null   $wp_data_class WordPress data class.
		 *
		 * @return array
		 */
		public function default_values( string $opt_name = '', array $sections = array(), $wp_data_class = null ): array {
			// We want it to be clean each time this is run.
			$this->options_defaults = array();

			// Check to make sure we're not in the select2 action, we don't want to fetch any there.
			if ( isset( $_REQUEST['action'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$action = sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

				if ( Redux_Functions_Ex::string_ends_with( $action, '_select2' ) && Redux_Functions_Ex::string_starts_with( $action, 'redux_' ) ) {
					return array();
				}
			}

			if ( ! empty( $sections ) ) {

				// Fill the cache.
				foreach ( $sections as $sk => $section ) {
					if ( ! isset( $section['id'] ) ) {
						if ( ! is_numeric( $sk ) || ! isset( $section['title'] ) ) {
							$section['id'] = $sk;
						} else {
							$section['id'] = sanitize_title( $section['title'], $sk );
						}

						$sections[ $sk ] = $section;
					}
					if ( isset( $section['fields'] ) ) {
						foreach ( $section['fields'] as $field ) {
							if ( empty( $field['id'] ) && empty( $field['type'] ) ) {
								continue;
							}

							$this->field_default_values( $opt_name, $field, $wp_data_class, false );

							if ( 'tabbed' === $field['type'] ) {
								if ( ! empty( $field['tabs'] ) ) {
									foreach ( $field['tabs'] as $val ) {
										if ( ! empty( $val['fields'] ) ) {
											foreach ( $val['fields'] as $f ) {
												$this->field_default_values( $opt_name, $f, $wp_data_class, false, true );
											}
										}
									}
								}
							}

							if ( 'repeater' === $field['type'] ) {
								if ( ! empty( $field['fields'] ) ) {
									foreach ( $field['fields'] as $f ) {
										$this->field_default_values( $opt_name, $f, $wp_data_class, true );
									}
								}
							}
						}
					}
				}
			}

			return $this->options_defaults;
		}

		/**
		 * Field default values.
		 *
		 * @param string $opt_name      Panel opt_name.
		 * @param array  $field         Field array.
		 * @param null   $wp_data_class WordPress data class.
		 * @param bool   $is_repeater   Is a repeater field.
		 * @param bool   $is_tabbed     Is a tabbed field.
		 */
		public function field_default_values( string $opt_name = '', array $field = array(), $wp_data_class = null, bool $is_repeater = false, $is_tabbed = false ) {
			if ( 'repeater' === $field['type'] ) {
				if ( isset( $field['group_values'] ) && true === $field['group_values'] ) {
					$this->is_repeater_group = true;
					$this->repeater_id       = $field['id'];
				}
			}

			if ( null === $wp_data_class && class_exists( 'Redux_WordPress_Data' ) && ! ( 'select' === $field['type'] && isset( $field['ajax'] ) && $field['ajax'] ) ) {
				$wp_data_class = new Redux_WordPress_Data( $opt_name );
			}

			// Detect what field types are being used.
			if ( ! isset( $this->fields[ $field['type'] ][ $field['id'] ] ) ) {
				$this->fields[ $field['type'] ][ $field['id'] ] = 1;
			} else {
				$this->fields[ $field['type'] ] = array( $field['id'] => 1 );
			}

			if ( isset( $field['default'] ) ) {
				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				$def = apply_filters( "redux/$opt_name/field/{$field['type']}/defaults", $field['default'], $field );

				if ( true === $is_repeater ) {
					if ( true === $this->is_repeater_group ) {
						$this->options_defaults[ $this->repeater_id ][ $field['id'] ] = array( $def );
					} else {
						$this->options_defaults[ $field['id'] ] = array( $def );
					}
				} elseif ( true === $is_tabbed ) {
					$this->options_defaults[ $field['id'] ] = $def;
				} else {
					$this->options_defaults[ $field['id'] ] = $def;
				}
			} elseif ( ( 'ace_editor' !== $field['type'] ) && ! ( 'select' === $field['type'] && ! empty( $field['ajax'] ) ) ) {
				if ( isset( $field['data'] ) ) {
					if ( ! isset( $field['args'] ) ) {
						$field['args'] = array();
					}
					if ( is_array( $field['data'] ) && ! empty( $field['data'] ) ) {
						foreach ( $field['data'] as $key => $data ) {
							if ( ! empty( $data ) ) {
								if ( ! isset( $field['args'][ $key ] ) ) {
									$field['args'][ $key ] = array();
								}
								if ( null !== $wp_data_class ) {
									$field['options'][ $key ] = $wp_data_class->get( $data, $field['args'][ $key ], $opt_name );
								}
							}
						}
					} elseif ( null !== $wp_data_class ) {
						$field['options'] = $wp_data_class->get( $field['data'], $field['args'], $opt_name );
					}

					if ( 'sorter' === $field['type'] && ! empty( $field['data'] ) && is_array( $field['data'] ) ) {
						if ( ! isset( $field['args'] ) ) {
							$field['args'] = array();
						}
						foreach ( $field['data'] as $key => $data ) {
							if ( ! isset( $field['args'][ $key ] ) ) {
								$field['args'][ $key ] = array();
							}
							if ( null !== $wp_data_class ) {
								$field['options'][ $key ] = $wp_data_class->get( $data, $field['args'][ $key ], $opt_name );
							}
						}
					}

					if ( isset( $field['options'] ) ) {
						if ( 'sortable' === $field['type'] ) {
							$this->options_defaults[ $field['id'] ] = ( true === $is_repeater ) ? array( array() ) : array();
						} elseif ( 'image_select' === $field['type'] ) {
							$this->options_defaults[ $field['id'] ] = ( true === $is_repeater ) ? array( '' ) : '';
						} elseif ( 'select' === $field['type'] ) {
							$this->options_defaults[ $field['id'] ] = ( true === $is_repeater ) ? array( '' ) : '';
						} else {
							$this->options_defaults[ $field['id'] ] = ( true === $is_repeater ) ? array( $field['options'] ) : $field['options'];
						}
					}
				}
			}
		}
	}
}
