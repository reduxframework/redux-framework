<?php // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
/**
 * Redux Framework User Meta API Class
 * Makes instantiating a Redux object an absolute piece of cake.
 *
 * @package     Redux_Framework
 * @author      Dovy Paukstys
 * @subpackage  Users
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Users' ) ) {

	/**
	 * Redux Users API Class
	 * Simple API for Redux Framework
	 *
	 * @since       1.0.0
	 */
	class Redux_Users {

		/**
		 * Profile array.
		 *
		 * @var array
		 */
		public static $profiles = array();

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
			add_action( 'init', array( 'Redux_Users', 'enqueue' ), 99 );
		}

		/**
		 * Enqueue support files and fields.
		 *
		 * @throws ReflectionException Exception.
		 */
		public static function enqueue() {
			global $pagenow;

			// Check and run instances of Redux where the opt_name hasn't been run.
			$pagenows = array( 'user-new.php', 'profile.php', 'user-edit.php' );

			if ( ! empty( self::$sections ) && in_array( $pagenow, $pagenows, true ) ) {
				$instances = ReduxFrameworkInstances::get_all_instances();

				foreach ( self::$fields as $opt_name => $fields ) {
					if ( ! isset( $instances[ $opt_name ] ) ) {
						Redux::set_args( $opt_name, array( 'menu_type' => 'hidden' ) );
						Redux::set_sections(
							$opt_name,
							array(
								array(
									'id'     => 'EXTENSION_USERS_FAKE_ID' . $opt_name,
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
		 * @param string $opt_name Panel Opt Name.
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
		 * Construct Profiles.
		 *
		 * @param string $opt_name Panel opt_name.
		 *
		 * @return array
		 */
		public static function construct_profiles( string $opt_name ): array {
			$profiles = array();

			if ( ! isset( self::$profiles[ $opt_name ] ) ) {
				return $profiles;
			}

			foreach ( self::$profiles[ $opt_name ] as $profile ) {
				$permissions         = $profile['permissions'] ?? false;
				$roles               = $profile['roles'] ?? false;
				$profile['sections'] = self::construct_sections( $opt_name, $profile['id'], $permissions, $roles );
				$profiles[]          = $profile;
			}

			ksort( $profiles );

			return $profiles;
		}

		/**
		 * Construct Sections.
		 *
		 * @param string     $opt_name    Panel opt_name.
		 * @param int|string $profile_id  Profile ID.
		 * @param bool       $permissions Permissions.
		 * @param bool       $roles       ROles.
		 *
		 * @return array
		 */
		public static function construct_sections( string $opt_name, $profile_id, bool $permissions = false, bool $roles = false ): array {
			$sections = array();

			if ( ! isset( self::$sections[ $opt_name ] ) ) {
				return $sections;
			}

			foreach ( self::$sections[ $opt_name ] as $section_id => $section ) {
				if ( $section['profile_id'] === $profile_id ) {

					self::$sections[ $opt_name ][ $section_id ]['roles'] = $section;

					$p = $section['priority'];

					while ( isset( $sections[ $p ] ) ) {
						echo esc_html( $p ++ );
					}

					$section['fields'] = self::construct_fields( $opt_name, $section_id );
					$sections[ $p ]    = $section;
				}
			}

			return $sections;
		}

		/**
		 * Construct Fields.
		 *
		 * @param string $opt_name    Panel opt_name.
		 * @param string $section_id  Section ID.
		 * @param bool   $permissions Permissions.
		 * @param bool   $roles       Roles.
		 *
		 * @return array
		 */
		public static function construct_fields( string $opt_name = '', string $section_id = '', bool $permissions = false, bool $roles = false ): array {
			$fields = array();

			if ( ! isset( self::$fields[ $opt_name ] ) ) {
				return $fields;
			}

			foreach ( self::$fields[ $opt_name ] as $key => $field ) {
				// Nested permissions.
				$field['permissions'] = $field['permissions'] ?? $permissions;

				self::$fields[ $opt_name ][ $key ]['permissions'] = $field['permissions'];

				// Nested roles permissions.
				$field['roles'] = $field['roles'] ?? $roles;

				self::$fields[ $opt_name ][ $key ]['roles'] = $field['roles'];

				if ( $field['section_id'] === $section_id ) {
					$p = $field['priority'];

					while ( isset( $fields[ $p ] ) ) {
						echo esc_html( $p ++ );
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
		 * Set Section.
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

						if ( isset( $section['permissions'] ) || isset( $section['roles'] ) ) {
							foreach ( $section['fields'] as $key => $field ) {
								if ( ! isset( $field['permissions'] ) && isset( $section['permissions'] ) ) {
									$section['fields'][ $key ]['permissions'] = $section['permissions'];
								}
								if ( ! isset( $field['roles'] ) && isset( $section['roles'] ) ) {
									$section['fields'][ $key ]['roles'] = $section['roles'];
								}
							}
						}

						self::process_fields_array( $opt_name, $section['id'], $section['fields'] );
					}
					unset( $section['fields'] );
				}

				self::$sections[ $opt_name ][ $section['id'] ] = $section;
			} else {
				self::$errors[ $opt_name ]['section']['empty'] = esc_html__( 'Unable to create a section due an empty section array or the section variable passed was not an array.', 'redux-framework' );
			}
		}

		/**
		 * Process Section Array.
		 *
		 * @param string $opt_name   Panel opt_name.
		 * @param string $profile_id Profile ID.
		 * @param array  $sections   Sections array.
		 */
		public static function process_sections_array( string $opt_name = '', string $profile_id = '', array $sections = array() ) {
			if ( ! empty( $opt_name ) && ! empty( $profile_id ) && is_array( $sections ) && ! empty( $sections ) ) {
				foreach ( $sections as $section ) {
					if ( ! is_array( $section ) ) {
						continue;
					}

					$section['profile_id'] = $profile_id;

					if ( ! isset( $section['fields'] ) || ! is_array( $section['fields'] ) ) {
						$section['fields'] = array();
					}

					self::set_section( $opt_name, $section );
				}
			}
		}

		/**
		 * Process Fields Array.
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
		 * @param string $id       Field ID.
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
		 * Set Profile.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param array  $profile  Profile array.
		 */
		public static function set_profile( string $opt_name = '', array $profile = array() ) {
			self::check_opt_name( $opt_name );

			if ( ! empty( $opt_name ) && is_array( $profile ) && ! empty( $profile ) ) {
				if ( ! isset( $profile['id'] ) ) {
					if ( isset( $profile['title'] ) ) {
						$profile['id'] = strtolower( sanitize_html_class( $profile['title'] ) );
					} else {
						$profile['id'] = 'profile';
					}

					if ( isset( self::$profiles[ $opt_name ][ $profile['id'] ] ) ) {
						$orig = $profile['id'];
						$i    = 0;
						while ( isset( self::$profiles[ $opt_name ][ $profile['id'] ] ) ) {
							$profile['id'] = $orig . '_' . $i;
						}
					}
				}

				if ( isset( $profile['sections'] ) ) {
					if ( ! empty( $profile['sections'] ) && is_array( $profile['sections'] ) ) {
						if ( isset( $profile['permissions'] ) || isset( $profile['roles'] ) ) {
							foreach ( $profile['sections'] as $key => $section ) {
								if ( ! isset( $section['permissions'] ) && isset( $profile['permissions'] ) ) {
									$profile['sections'][ $key ]['permissions'] = $profile['permissions'];
								}
								if ( ! isset( $section['roles'] ) && isset( $profile['roles'] ) ) {
									$profile['sections'][ $key ]['roles'] = $profile['roles'];
								}
							}
						}

						self::process_sections_array( $opt_name, $profile['id'], $profile['sections'] );
					}

					unset( $profile['sections'] );
				}

				self::$profiles[ $opt_name ][ $profile['id'] ] = $profile;
			} else {
				self::$errors[ $opt_name ]['profile']['empty'] = esc_html__( 'Unable to create a profile due an empty profile array or the profile variable passed was not an array.', 'redux-framework' );
			}
		}

		/**
		 * Set Profiles.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param array  $profiles Profiles array.
		 */
		public static function set_profiles( string $opt_name = '', array $profiles = array() ) {
			if ( ! empty( $profiles ) && is_array( $profiles ) ) {
				foreach ( $profiles as $profile ) {
					self::set_profile( $opt_name, $profile );
				}
			}
		}

		/**
		 * Get profiles.
		 *
		 * @param string $opt_name Panel opt_name.
		 *
		 * @return mixed
		 */
		public static function get_profiles( string $opt_name = '' ) {
			self::check_opt_name( $opt_name );

			if ( ! empty( $opt_name ) && ! empty( self::$profiles[ $opt_name ] ) ) {
				return self::$profiles[ $opt_name ];
			}

			return false;
		}

		/**
		 * Get Box.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $key      Key.
		 *
		 * @return mixed
		 */
		public static function get_box( string $opt_name = '', string $key = '' ) {
			self::check_opt_name( $opt_name );

			if ( ! empty( $opt_name ) && ! empty( $key ) && ! empty( self::$profiles[ $opt_name ] ) && isset( self::$profiles[ $opt_name ][ $key ] ) ) {
				return self::$profiles[ $opt_name ][ $key ];
			}

			return false;
		}

		/**
		 * Get Priority.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param mixed  $type     Type.
		 *
		 * @return mixed
		 */
		public static function get_priority( string $opt_name, $type ) {
			$priority                              = self::$priority[ $opt_name ][ $type ];
			self::$priority[ $opt_name ][ $type ] += 1;

			return $priority;
		}

		/**
		 * Check opt name.
		 *
		 * @param string $opt_name Panel opt_name.
		 */
		public static function check_opt_name( string $opt_name = '' ) {
			if ( empty( $opt_name ) || is_array( $opt_name ) ) {
				return;
			}

			if ( ! isset( self::$profiles[ $opt_name ] ) ) {
				self::$profiles[ $opt_name ] = array();
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
		 * Get user role.
		 *
		 * @param int $user_id User ID.
		 *
		 * @return false|string
		 */
		public static function get_user_role( int $user_id = 0 ) {
			$user = ( $user_id ) ? get_userdata( $user_id ) : wp_get_current_user();

			return current( $user->roles );
		}

		/**
		 * Get USer Meta.
		 *
		 * @param array $args Args array.
		 *
		 * @return mixed
		 */
		public static function get_user_meta( array $args = array() ) {
			$default = array(
				'key'      => '',
				'opt_name' => '',
				'user'     => '',
			);

			$args = wp_parse_args( $args, $default );

			if ( empty( $args['user'] ) ) {
				$args['user'] = get_current_user_id();
			}

			// phpcs:ignore WordPress.PHP.DontExtract
			extract( $args );

			$single = ! empty( $key );

			/** @var int $user User. */
			/** @var string $key Key. */
			$meta = get_user_meta( $user, $key, $single );

			if ( $single ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement
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
				//$defaults = self::get_field_defaults( $opt_name );

				//$meta = wp_parse_args( $meta, $defaults );
			}

			return $meta;
		}
	}

	Redux_Users::load();
}
