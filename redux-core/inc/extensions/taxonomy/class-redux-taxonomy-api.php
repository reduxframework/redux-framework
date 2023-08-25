<?php // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
/**
 * Redux Framework Taxonomy Meta API Class
 * Makes instantiating a Redux object an absolute piece of cake.
 *
 * @package     Redux Pro
 * @author      Dovy Paukstys
 * @subpackage  Taxonomy
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Taxonomy' ) ) {

	/**
	 * Redux Taxonomy API Class
	 * Simple API for Redux Framework
	 *
	 * @since       1.0.0
	 */
	class Redux_Taxonomy {

		/**
		 * Terms array.
		 *
		 * @var array
		 */
		public static $terms = array();

		/**
		 * Sections array.
		 *
		 * @var array
		 */
		public static $sections = array();

		/**
		 * Fields array.
		 *
		 * @var array
		 */
		public static $fields = array();

		/**
		 * Priority array.
		 *
		 * @var array
		 */
		public static $priority = array();

		/**
		 * Errors array.
		 *
		 * @var array
		 */
		public static $errors = array();

		/**
		 * Init array.
		 *
		 * @var array
		 */
		public static $init = array();

		/**
		 * Args array.
		 *
		 * @var array
		 */
		public static $args = array();

		/**
		 * Load.
		 */
		public static function load() {
			add_action( 'init', array( 'Redux_Taxonomy', 'enqueue' ), 99 );
		}

		/**
		 * Enqueue support files and fields.
		 *
		 * @throws ReflectionException Exception.
		 */
		public static function enqueue() {
			global $pagenow;

			// Check and run instances of Redux where the opt_name hasn't been run.
			$pagenows = array( 'edit-tags.php', 'term.php' );

			if ( ! empty( self::$sections ) && in_array( $pagenow, $pagenows, true ) ) {
				$instances = Redux::all_instances();

				foreach ( self::$fields as $opt_name => $fields ) {
					if ( ! isset( $instances[ $opt_name ] ) ) {
						Redux::set_args( $opt_name, array( 'menu_type' => 'hidden' ) );

						Redux::set_sections(
							$opt_name,
							array(
								array(
									'id'     => 'EXTENSION_TAXONOMY_FAKE_ID' . $opt_name,
									'fields' => $fields,
									'title'  => 'N/A',
								),
							)
						);

						Redux::init( $opt_name );

						$instances = ReduxFrameworkInstances::get_all_instances();
					}

					self::check_opt_name( $opt_name );

					Redux::set_args( $opt_name, self::$args[ $opt_name ] );
				}
			}
		}

		/**
		 * Construct Args.
		 *
		 * @param string $opt_name Panel opt_name.
		 *
		 * @return mixed
		 */
		public static function construct_args( string $opt_name ) {
			$args             = self::$args[ $opt_name ];
			$args['opt_name'] = $opt_name;

			if ( ! isset( $args['menu_title'] ) ) {
				$args['menu_title'] = ucfirst( $opt_name ) . ' Options';
			}

			if ( ! isset( $args['page_title'] ) ) {
				$args['page_title'] = ucfirst( $opt_name ) . ' Options';
			}

			if ( ! isset( $args['page_slug'] ) ) {
				$args['page_slug'] = $opt_name . '_options';
			}

			return $args;
		}

		/**
		 * Construct Terms
		 *
		 * @param string $opt_name Panel opt_name.
		 *
		 * @return array
		 */
		public static function construct_terms( string $opt_name ): array {
			$terms = array();

			if ( ! isset( self::$terms[ $opt_name ] ) ) {
				return $terms;
			}

			foreach ( self::$terms[ $opt_name ] as $term ) {
				$term['sections'] = self::construct_sections( $opt_name, $term['id'] );
				$terms[]          = $term;
			}

			ksort( $terms );

			return $terms;
		}

		/**
		 * Construct Sections.
		 *
		 * @param string $opt_name       Panel opt_name.
		 * @param string $term_id        Term ID.
		 *
		 * @return array
		 */
		public static function construct_sections( string $opt_name, string $term_id ): array {
			$sections = array();

			if ( ! isset( self::$sections[ $opt_name ] ) ) {
				return $sections;
			}

			foreach ( self::$sections[ $opt_name ] as $section_id => $section ) {
				if ( $section['term_id'] === $term_id ) {
					self::$sections[ $opt_name ][ $section_id ]['add_visibility'] = $section;

					$p = $section['priority'];

					while ( isset( $sections[ $p ] ) ) {
						echo esc_html( $p++ );
					}

					$section['fields'] = self::construct_fields( $opt_name, $section_id );
					$sections[ $p ]    = $section;
				}
			}

			ksort( $sections );

			return $sections;
		}

		/**
		 * Construct Fields.
		 *
		 * @param string $opt_name       Panel opt_name.
		 * @param string $section_id     Section ID.
		 * @param bool   $permissions    Permissions.
		 * @param bool   $add_visibility Add visibility.
		 *
		 * @return array
		 */
		public static function construct_fields( string $opt_name = '', string $section_id = '', bool $permissions = false, bool $add_visibility = false ): array {
			$fields = array();

			if ( ! isset( self::$fields[ $opt_name ] ) ) {
				return $fields;
			}

			foreach ( self::$fields[ $opt_name ] as $key => $field ) {
				// Nested permissions.
				$field['permissions'] = $field['permissions'] ?? $permissions;

				self::$fields[ $opt_name ][ $key ]['permissions'] = $field['permissions'];

				// Nested add_visibility permissions.
				$field['add_visibility'] = $field['add_visibility'] ?? $add_visibility;

				self::$fields[ $opt_name ][ $key ]['add_visibility'] = $field['add_visibility'];

				if ( $field['section_id'] === $section_id ) {
					$p = $field['priority'];

					while ( isset( $fields[ $p ] ) ) {
						echo esc_html( $p++ );
					}

					$fields[ $p ] = $field;
				}
			}

			ksort( $fields );

			return $fields;
		}

		/**
		 * Get Section.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $id       ID.
		 *
		 * @return bool
		 */
		public static function get_section( string $opt_name = '', string $id = '' ): bool {
			self::check_opt_name( $opt_name );

			if ( ! empty( $opt_name ) && ! empty( $id ) ) {
				if ( ! isset( self::$sections[ $opt_name ][ $id ] ) ) {
					$id = strtolower( sanitize_html_class( $id ) );
				}

				return self::$sections[ $opt_name ][ $id ] ?? false;
			}

			return false;
		}

		/**
		 * Set_section.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param array  $section  Section array.
		 */
		public static function set_section( string $opt_name = '', array $section = array() ) {
			self::check_opt_name( $opt_name );

			if ( ! empty( $opt_name ) && is_array( $section ) && ! empty( $section ) ) {
				if ( ! isset( $section['id'] ) ) {
					if ( isset( $section['title'] ) ) {
						$section['id'] = strtolower( sanitize_html_class( $section['title'] ) );
					} else {
						$section['id'] = 'section';
					}

					if ( isset( self::$sections[ $opt_name ][ $section['id'] ] ) ) {
						$orig = $section['id'];
						$i    = 0;

						while ( isset( self::$sections[ $opt_name ][ $section['id'] ] ) ) {
							$section['id'] = $orig . '_' . $i;
						}
					}
				}

				if ( ! isset( $section['priority'] ) ) {
					$section['priority'] = self::get_priority( $opt_name, 'sections' );
				}

				if ( isset( $section['fields'] ) ) {
					if ( ! empty( $section['fields'] ) && is_array( $section['fields'] ) ) {
						if ( isset( $section['permissions'] ) || isset( $section['add_visibility'] ) ) {
							foreach ( $section['fields'] as $key => $field ) {
								if ( ! isset( $field['permissions'] ) && isset( $section['permissions'] ) ) {
									$section['fields'][ $key ]['permissions'] = $section['permissions'];
								}

								if ( ! isset( $field['add_visibility'] ) && isset( $section['add_visibility'] ) ) {
									$section['fields'][ $key ]['add_visibility'] = $section['add_visibility'];
								}
							}
						}

						self::process_fields_array( $opt_name, $section['id'], $section['fields'] );
					}

					unset( $section['fields'] );
				}

				self::$sections[ $opt_name ][ $section['id'] ] = $section;
			} else {
				self::$errors[ $opt_name ]['section']['empty'] = esc_html__( 'Unable to create a section due an empty section array or the section variable passed was not an array.', 'redux-pro' );
			}
		}

		/**
		 * Process Sections Array.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $term_id  Term ID.
		 * @param array  $sections Sections array.
		 */
		public static function process_sections_array( string $opt_name = '', string $term_id = '', array $sections = array() ) {
			if ( ! empty( $opt_name ) && ! empty( $term_id ) && is_array( $sections ) && ! empty( $sections ) ) {
				foreach ( $sections as $section ) {
					if ( ! is_array( $section ) ) {
						continue;
					}

					$section['term_id'] = $term_id;

					if ( ! isset( $section['fields'] ) || ! is_array( $section['fields'] ) ) {
						$section['fields'] = array();
					}

					self::set_section( $opt_name, $section );
				}
			}
		}

		/**
		 * Process field array.
		 *
		 * @param string $opt_name   Panel opt_name.
		 * @param string $section_id Section ID.
		 * @param array  $fields     Fields array.
		 */
		public static function process_fields_array( string $opt_name = '', string $section_id = '', array $fields = array() ) {
			if ( ! empty( $opt_name ) && ! empty( $section_id ) && is_array( $fields ) && ! empty( $fields ) ) {
				foreach ( $fields as $field ) {
					if ( ! is_array( $field ) ) {
						continue;
					}

					$field['section_id'] = $section_id;

					self::set_field( $opt_name, $field );
				}
			}
		}

		/**
		 * Get field.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $id       ID.
		 *
		 * @return bool
		 */
		public static function get_field( string $opt_name = '', string $id = '' ): bool {
			self::check_opt_name( $opt_name );

			if ( ! empty( $opt_name ) && ! empty( $id ) ) {
				return self::$fields[ $opt_name ][ $id ] ?? false;
			}

			return false;
		}

		/**
		 * Set field.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param array  $field    Field array.
		 */
		public static function set_field( string $opt_name = '', array $field = array() ) {
			self::check_opt_name( $opt_name );

			if ( ! empty( $opt_name ) && is_array( $field ) && ! empty( $field ) ) {
				if ( ! isset( $field['priority'] ) ) {
					$field['priority'] = self::get_priority( $opt_name, 'fields' );
				}

				self::$fields[ $opt_name ][ $field['id'] ] = $field;
			}
		}

		/**
		 * Set args.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param array  $args     Args array.
		 */
		public static function set_args( string $opt_name = '', array $args = array() ) {
			self::check_opt_name( $opt_name );

			if ( ! empty( $opt_name ) && is_array( $args ) && ! empty( $args ) ) {
				self::$args[ $opt_name ] = self::$args[ $opt_name ] ?? array();
				self::$args[ $opt_name ] = wp_parse_args( $args, self::$args[ $opt_name ] );
			}
		}

		/**
		 * Set term.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param array  $term     Term array.
		 */
		public static function set_term( string $opt_name = '', array $term = array() ) {
			self::check_opt_name( $opt_name );

			if ( ! empty( $opt_name ) && is_array( $term ) && ! empty( $term ) ) {
				if ( ! isset( $term['id'] ) ) {
					if ( isset( $term['title'] ) ) {
						$term['id'] = strtolower( sanitize_html_class( $term['title'] ) );
					} else {
						$term['id'] = 'term';
					}

					if ( isset( self::$terms[ $opt_name ][ $term['id'] ] ) ) {
						$orig = $term['id'];
						$i    = 0;

						while ( isset( self::$terms[ $opt_name ][ $term['id'] ] ) ) {
							$term['id'] = $orig . '_' . $i;
						}
					}
				}

				if ( isset( $term['sections'] ) ) {
					if ( ! empty( $term['sections'] ) && is_array( $term['sections'] ) ) {
						if ( isset( $term['permissions'] ) || isset( $term['add_visibility'] ) ) {
							foreach ( $term['sections'] as $key => $section ) {
								if ( ! isset( $section['permissions'] ) && isset( $term['permissions'] ) ) {
									$term['sections'][ $key ]['permissions'] = $term['permissions'];
								}

								if ( ! isset( $section['add_visibility'] ) && isset( $term['add_visibility'] ) ) {
									$term['sections'][ $key ]['add_visibility'] = $term['add_visibility'];
								}
							}
						}

						self::process_sections_array( $opt_name, $term['id'], $term['sections'] );
					}

					unset( $term['sections'] );
				}

				self::$terms[ $opt_name ][ $term['id'] ] = $term;
			} else {
				self::$errors[ $opt_name ]['term']['empty'] = esc_html__( 'Unable to create a term due an empty term array or the term variable passed was not an array.', 'redux-pro' );
			}
		}

		/**
		 * Get terms.
		 *
		 * @param string $opt_name Panel opt_name.
		 *
		 * @return mixed
		 */
		public static function get_terms( string $opt_name = '' ) {
			self::check_opt_name( $opt_name );

			if ( ! empty( $opt_name ) && ! empty( self::$terms[ $opt_name ] ) ) {
				return self::$terms[ $opt_name ];
			}

			return false;
		}

		/**
		 * Get priority.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $type     Field type.
		 *
		 * @return mixed
		 */
		public static function get_priority( string $opt_name, string $type ) {
			$priority                              = self::$priority[ $opt_name ][ $type ];
			self::$priority[ $opt_name ][ $type ] += 1;

			return $priority;
		}

		/**
		 * Check opt_name.
		 *
		 * @param string $opt_name Panel opt_name.
		 */
		public static function check_opt_name( string $opt_name = '' ) {
			if ( empty( $opt_name ) || is_array( $opt_name ) ) {
				return;
			}

			if ( ! isset( self::$terms[ $opt_name ] ) ) {
				self::$terms[ $opt_name ] = array();
			}

			if ( ! isset( self::$priority[ $opt_name ] ) ) {
				self::$priority[ $opt_name ]['args'] = 1;
			}

			if ( ! isset( self::$sections[ $opt_name ] ) ) {
				self::$sections[ $opt_name ]             = array();
				self::$priority[ $opt_name ]['sections'] = 1;
			}

			if ( ! isset( self::$fields[ $opt_name ] ) ) {
				self::$fields[ $opt_name ]             = array();
				self::$priority[ $opt_name ]['fields'] = 1;
			}

			if ( ! isset( self::$errors[ $opt_name ] ) ) {
				self::$errors[ $opt_name ] = array();
			}

			if ( ! isset( self::$init[ $opt_name ] ) ) {
				self::$init[ $opt_name ] = false;
			}

			if ( ! isset( self::$args[ $opt_name ] ) ) {
				self::$args[ $opt_name ] = false;
			}
		}

		/**
		 * Get field defaults.
		 *
		 * @param string $opt_name Panel opt_name.
		 *
		 * @return array|void
		 */
		public static function get_field_defaults( string $opt_name ) {
			if ( empty( $opt_name ) ) {
				return;
			}

			if ( ! isset( self::$fields[ $opt_name ] ) ) {
				return array();
			}

			$defaults = array();
			foreach ( self::$fields[ $opt_name ] as $key => $field ) {
				$defaults[ $key ] = $field['default'] ?? '';
			}

			return $defaults;
		}

		/**
		 * Get term meta.
		 *
		 * @param array $args Args array.
		 *
		 * @return array|mixed|string
		 */
		public static function get_term_meta( array $args = array() ) {
			$default = array(
				'key'      => '',
				'opt_name' => '',
				'taxonomy' => '',
			);

			$args = wp_parse_args( $args, $default );

			// phpcs:ignore WordPress.PHP.DontExtract
			extract( $args );

			if ( empty( $taxonomy ) ) {
				return array();
			}

			$single = ! empty( $key );

			$meta = get_term_meta( $taxonomy, $key, $single );

			// phpcs:ignore Generic.CodeAnalysis.EmptyStatement
			if ( $single ) {
				// Do nothing.
			} elseif ( ! empty( $meta ) ) {
				foreach ( $meta as $key => $value ) {
					if ( is_array( $value ) ) {
						$value = $value[0];
					}

					$meta[ $key ] = maybe_unserialize( $value );
				}
			}

			if ( ! empty( $opt_name ) ) {
				$defaults = self::get_field_defaults( $opt_name );

				if ( $single ) {
					$default_value = '';

					if ( isset( $defaults[ $key ] ) ) {
						$default_value = $defaults[ $key ];
					}

					if ( is_array( $meta ) ) {
						if ( is_array( $default_value ) ) {
							$meta = wp_parse_args( $meta, $default_value );
						}
					} elseif ( '' === $meta && '' !== $default_value ) {
							$meta = $default_value;
					}
				} else {
					$meta = wp_parse_args( $meta, $defaults );
				}
			}

			return $meta;
		}
	}

	Redux_Taxonomy::load();
}
