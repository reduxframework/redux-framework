<?php // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
/**
 * Redux Framework API Class
 * Makes instantiating a Redux object an absolute piece of cake.
 *
 * @package     Redux_Framework
 * @author      Dovy Paukstys
 * @subpackage  Core
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux', false ) ) {

	/**
	 * Redux API Class
	 * Simple API for Redux Framework
	 *
	 * @since       3.0.0
	 */
	class Redux {


		/**
		 *  Option fields.
		 *
		 * @var array
		 */
		public static $fields = array();

		/**
		 * Option sections.
		 *
		 * @var array
		 */
		public static $sections = array();

		/**
		 * Option defaults.
		 *
		 * @var array
		 */
		public static $options_defaults = array();

		/**
		 * Option help array.
		 *
		 * @var array
		 */
		public static $help = array();

		/**
		 * Option global args.
		 *
		 * @var array
		 */
		public static $args = array();

		/**
		 * Option section priorities.
		 *
		 * @var array
		 */
		public static $priority = array();

		/**
		 * Panel validations errors.
		 *
		 * @var array
		 */
		public static $errors = array();

		/**
		 * Init.
		 *
		 * @var array
		 */
		public static $init = array();

		/**
		 * Delay Init opt_names
		 *
		 * @var array
		 */
		public static $delay_init = array();

		/**
		 * Extension list.
		 *
		 * @var array
		 */
		public static $extensions = array();

		/**
		 * Extensions in use.
		 *
		 * @var array
		 */
		public static $uses_extensions = array();

		/**
		 * Extension paths.
		 *
		 * @var array
		 */
		public static $extension_paths = array();

		/**
		 * Extension capability flag.
		 *
		 * @var boolean
		 */
		public static $extension_compatibility = false;

		/**
		 * Code to run at creation in instance.
		 */
		public static function load() {
			add_action( 'after_setup_theme', array( 'Redux', 'create_redux' ) );
			add_action( 'init', array( 'Redux', 'create_redux' ) );
			add_action( 'switch_theme', array( 'Redux', 'create_redux' ) );

			require_once Redux_Core::$dir . 'inc/extensions/metaboxes/class-redux-metaboxes-api.php';
		}

		/**
		 * Delay init action function
		 * Delays all Redux objects from loaded before `plugins_loaded` runs.
		 */
		public static function delay_init() {
			if ( ! empty( self::$delay_init ) ) {

				foreach ( self::$delay_init as $opt_name ) {
					self::init( $opt_name );
					$parent = Redux_Instances::get_instance( $opt_name );
					// translators: This is only shown to developers, should not impact users.
					$msg = sprintf(
						'<strong>%s</strong><br /><code>%s</code> %s',
						esc_html__( 'Warning, Premature Initialization', 'redux-framework' ),
						'self::init("' . esc_html( $opt_name ) . '")',
						// translators: This is only shown to developers, should not impact users.
						sprintf( esc_html__( 'was run before the %s hook and was delayed to avoid errors.', 'redux-framework' ), '<code>plugins_loaded</code>' )
					);

					if ( isset( $parent->args ) ) {
						$data = array(
							'parent'  => $parent,
							'type'    => 'error',
							'msg'     => $msg,
							'id'      => 'redux_init',
							'dismiss' => true,
						);

						Redux_Admin_Notices::set_notice( $data );
					}
				}
			}
		}

		/**
		 * Init Redux object
		 *
		 * @param string $opt_name Panel opt_name.
		 */
		public static function init( string $opt_name = '' ) {
			if ( ! empty( $opt_name ) ) {
				if ( ! did_action( 'plugins_loaded' ) ) {

					// We don't want to load before plugins_loaded EVER.
					self::$delay_init[] = $opt_name;
					add_action( 'plugins_loaded', array( 'Redux', 'delay_init' ) );
				} else {

					// The hook `plugins_loaded` has run, let's get going!
					self::load_redux( $opt_name );

					remove_action( 'setup_theme', array( 'Redux', 'create_redux' ) );
				}
			}
		}

		/**
		 * Retrive ReduxFramework object.
		 *
		 * @param string $opt_name Panel opt_name.
		 *
		 * @return object|ReduxFramework
		 */
		public static function instance( string $opt_name ) {
			return Redux_Instances::get_instance( $opt_name );
		}

		/**
		 * Retrieve all ReduxFramework Instances.
		 *
		 * @return null|array|ReduxFramework[]
		 */
		public static function all_instances(): ?array {
			return Redux_Instances::get_all_instances();
		}

		/**
		 * Load external extensions.
		 *
		 * @param object $redux_framework ReduxFramework object.
		 *
		 * @deprecated No longer using camcelCase naming convensions.
		 */
		public static function loadExtensions( $redux_framework ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			self::load_extensions( $redux_framework );
		}

		/**
		 * Load external extensions.
		 *
		 * @param object $redux_framework ReduxFramework object.
		 */
		public static function load_extensions( $redux_framework ) {
			$instance_extensions = self::get_extensions( $redux_framework->args['opt_name'] );

			if ( $instance_extensions ) {
				foreach ( $instance_extensions as $name => $extension ) {
					$old_class = str_replace( 'Redux_', 'ReduxFramework_', $extension['class'] );

					if ( ! class_exists( $extension['class'] ) && ! class_exists( $old_class ) ) {
						// In case you wanted override your override, hah.
						// phpcs:ignore WordPress.NamingConventions.ValidHookName
						$extension['path'] = apply_filters( 'redux/extension/' . $redux_framework->args['opt_name'] . '/' . $name, $extension['path'] );
						if ( file_exists( $extension['path'] ) ) {
							require_once $extension['path'];
						}
					}
					if ( isset( $extension['field'] ) ) {
						require_once $extension['field'];
					}

					if ( ! isset( $redux_framework->extensions[ $name ] ) ) {
						$field_classes = array( $extension['class'], $old_class );
						$ext_class     = Redux_Functions::class_exists_ex( $field_classes );
						if ( false !== $ext_class ) {
							$redux_framework->extensions[ $name ] = new $ext_class( $redux_framework );
							// Backwards compatibility for extensions.
							if ( ! is_subclass_of( $redux_framework->extensions[ $name ], 'Redux_Extension_Abstract' ) ) {
								$new_class_name                       = $ext_class . '_extended';
								self::$extension_compatibility        = true;
								$redux_framework->extensions[ $name ] = Redux_Functions_Ex::extension_compatibility( $redux_framework, $extension['path'], $ext_class, $new_class_name, $name );
							}
						} elseif ( is_admin() && true === $redux_framework->args['dev_mode'] ) {
							echo '<div id="message" class="error"><p>No class named <strong>' . esc_html( $extension['class'] ) . '</strong> exists. Please verify your extension path.</p></div>';
						}
					}
				}
			}
		}

		/**
		 * Deprecated function to set extension path.
		 *
		 * @param string $extension Path.
		 * @param bool   $folder    Set if path is a folder.
		 *
		 * @return bool|mixed
		 * @deprecated No longer using cameCase naming convensions.
		 */
		public static function extensionPath( string $extension, bool $folder = true ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			return self::extension_path( $extension, $folder = true );
		}

		/**
		 * Sets a path to an extension.
		 *
		 * @param string $extension Path to extension.
		 * @param bool   $folder    Set if path is a folder.
		 *
		 * @return bool|mixed
		 */
		public static function extension_path( string $extension, bool $folder = true ) {
			if ( ! isset( self::$extensions[ $extension ] ) ) {
				return false;
			}

			$path = end( self::$extensions[ $extension ] );

			if ( ! $folder ) {
				return $path;
			}

			return dirname( $path );
		}

		/**
		 * Deprecated function of Load Redux Framework.
		 *
		 * @param string $opt_name Panrl opt_name.
		 *
		 * @deprecated No longer using camelCase naming convensions.
		 */
		public static function loadRedux( string $opt_name = '' ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			self::load_redux( $opt_name );
		}

		/**
		 * Load defaults values for a given opt_name.
		 *
		 * @param string $opt_name Panel opt_name.
		 */
		public static function set_defaults( string $opt_name = '' ) {
			// Try to load the class if in the same directory, so the user only have to include the Redux API.
			if ( ! class_exists( 'Redux_Options_Defaults' ) ) {
				$file_check = trailingslashit( dirname( __FILE__ ) ) . 'class-redux-options-defaults.php';

				if ( file_exists( dirname( $file_check ) ) ) {
					include_once $file_check;
					$file_check = trailingslashit( dirname( __FILE__ ) ) . 'class-redux-wordpress-data.php';
					if ( file_exists( dirname( $file_check ) ) ) {
						include_once $file_check;
					}
				}
			}

			if ( class_exists( 'Redux_Options_Defaults' ) && ! isset( self::$options_defaults[ $opt_name ] ) ) {
				$sections                            = self::construct_sections( $opt_name );
				$wordpress_data                      = ( ! class_exists( 'Redux_WordPress_Data' ) ) ? null : new Redux_WordPress_Data( $opt_name );
				$options_defaults_class              = new Redux_Options_Defaults();
				self::$options_defaults[ $opt_name ] = $options_defaults_class->default_values( $opt_name, $sections, $wordpress_data );
				if ( ! isset( self::$args[ $opt_name ]['global_variable'] ) || ( '' === self::$args[ $opt_name ]['global_variable'] && false !== self::$args[ $opt_name ]['global_variable'] ) ) {
					self::$args[ $opt_name ]['global_variable'] = str_replace( '-', '_', $opt_name );
				}
				if ( isset( self::$args[ $opt_name ]['global_variable'] ) && self::$args[ $opt_name ]['global_variable'] ) {
					$option_global = self::$args[ $opt_name ]['global_variable'];

					/**
					 * Filter 'redux/options/{opt_name}/global_variable'
					 *
					 * @param array $value option value to set global_variable with
					 */ global $$option_global;

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					$$option_global = apply_filters( 'redux/options/' . $opt_name . '/global_variable', self::$options_defaults[ $opt_name ] );
				}
			}
		}

		/**
		 * Load Redux Framework.
		 *
		 * @param string $opt_name Panel opt_name.
		 */
		public static function load_redux( string $opt_name = '' ) {
			if ( empty( $opt_name ) ) {
				return;
			}

			if ( class_exists( 'ReduxFramework' ) ) {
				if ( isset( self::$init[ $opt_name ] ) && ! empty( self::$init[ $opt_name ] ) ) {
					return;
				}
			} else {
				echo '<div id="message" class="error"><p>' . esc_html__( 'Redux Framework is not installed. Please install it.', 'redux-framework' ) . '</p></div>';

				return;
			}

			$check = self::instance( $opt_name );

			Redux_Functions_Ex::record_caller( $opt_name );

			if ( isset( self::$init[ $opt_name ] ) && 1 === self::$init[ $opt_name ] ) {
				return;
			}

			self::set_defaults( $opt_name );

			$args     = self::construct_args( $opt_name );
			$sections = self::construct_sections( $opt_name );

			if ( isset( self::$uses_extensions[ $opt_name ] ) && ! empty( self::$uses_extensions[ $opt_name ] ) ) {
				add_action( "redux/extensions/$opt_name/before", array( 'Redux', 'loadExtensions' ), 0 );
			}

			$redux                   = new ReduxFramework( $sections, $args );
			self::$init[ $opt_name ] = 1;

			if ( isset( $redux->args['opt_name'] ) && $redux->args['opt_name'] !== $opt_name ) {
				self::$init[ $redux->args['opt_name'] ] = 1;
			}
		}

		/**
		 * Deprecated Create Redux instance.
		 *
		 * @deprecated No longer using camelCase naming convention.
		 */
		public static function createRedux() {       // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			self::create_redux();
		}

		/**
		 * Create Redux instance.
		 */
		public static function create_redux() {
			foreach ( self::$sections as $opt_name => $the_sections ) {
				if ( ! empty( $the_sections ) ) {
					if ( ! self::$init[ $opt_name ] ) {
						self::loadRedux( $opt_name );
					}
				}
			}
		}

		/**
		 * Construct global arguments.
		 *
		 * @param string $opt_name Panel opt_name.
		 *
		 * @return array|mixed
		 */
		public static function construct_args( string $opt_name ) {
			$args             = self::$args[ $opt_name ] ?? array();
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
		 * Construct option panel sections.
		 *
		 * @param string $opt_name Panel opt_name.
		 *
		 * @return array
		 */
		public static function construct_sections( string $opt_name ): array {
			$sections = array();

			if ( ! isset( self::$sections[ $opt_name ] ) ) {
				return $sections;
			}

			foreach ( self::$sections[ $opt_name ] as $section_id => $section ) {
				$section['fields'] = self::construct_fields( $opt_name, $section_id );
				$p                 = $section['priority'];

				while ( isset( $sections[ $p ] ) ) {
					$p++;
				}

				$sections[ $p ] = $section;
			}

			ksort( $sections );

			return $sections;
		}

		/**
		 * Construct option panel fields.
		 *
		 * @param string $opt_name   Panel opt_name.
		 * @param string $section_id ID of section.
		 *
		 * @return array
		 */
		public static function construct_fields( string $opt_name = '', string $section_id = '' ): array {
			$fields = array();

			if ( ! empty( self::$fields[ $opt_name ] ) ) {
				foreach ( self::$fields[ $opt_name ] as $key => $field ) {
					if ( $field['section_id'] === $section_id ) {
						$p = esc_html( $field['priority'] );

						while ( isset( $fields[ $p ] ) ) {
							echo intval( $p++ );
						}

						$fields[ $p ] = $field;
					}
				}
			}

			ksort( $fields );

			return $fields;
		}

		/**
		 * Deprecated Retrieve panel section.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $id       Section ID.
		 *
		 * @return bool
		 * @deprecated No longer using camelCase naming convention.
		 */
		public static function getSection( string $opt_name = '', string $id = '' ): bool { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			return self::get_section( $opt_name, $id );
		}

		/**
		 * Retrieve panel section.
		 *
		 * @param string     $opt_name Panel opt_name.
		 * @param string|int $id       Section ID.
		 *
		 * @return bool|string|int
		 */
		public static function get_section( string $opt_name = '', $id = '' ) {
			self::check_opt_name( $opt_name );

			if ( ! empty( $opt_name ) && ! empty( $id ) ) {
				if ( ! isset( self::$sections[ $opt_name ][ $id ] ) ) {
					$id = Redux_Core::strtolower( sanitize_html_class( $id ) );
				}

				return self::$sections[ $opt_name ][ $id ] ?? false;
			}

			return false;
		}

		/**
		 * Deprecated Create a section of the option panel.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param array  $sections Section ID.
		 *
		 * @deprecated No longer using camelCase naming convention.
		 */
		public static function setSections( string $opt_name = '', array $sections = array() ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			if ( '' !== $opt_name ) {
				Redux_Functions_Ex::record_caller( $opt_name );
			}

			self::set_sections( $opt_name, $sections );
		}

		/**
		 * Create multiple sections of the option panel.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param array  $sections Section ID.
		 */
		public static function set_sections( string $opt_name = '', array $sections = array() ) {
			if ( empty( $sections ) || '' === $opt_name ) {
				return;
			}

			self::check_opt_name( $opt_name );

			Redux_Functions_Ex::record_caller( $opt_name );

			foreach ( $sections as $section ) {
				self::set_section( $opt_name, $section );
			}
		}

		/**
		 * Deprecated Retrieve all sections from the option panel.
		 *
		 * @param string $opt_name Panel opt_name.
		 *
		 * @return array|mixed
		 * @deprecated No longer using camelCase naming convention.
		 */
		public static function getSections( string $opt_name = '' ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			return self::get_sections( $opt_name );
		}

		/**
		 * Retrieve all sections from the option panel.
		 *
		 * @param string $opt_name Panel opt_name.
		 *
		 * @return array|mixed
		 */
		public static function get_sections( string $opt_name = '' ) {
			self::check_opt_name( $opt_name );

			if ( ! empty( self::$sections[ $opt_name ] ) ) {
				return self::$sections[ $opt_name ];
			}

			return array();
		}

		/**
		 * Deprecated Remove option panel by ID.
		 *
		 * @param string     $opt_name Panel opt_name.
		 * @param string|int $id       Sectio ID.
		 * @param bool       $fields   Remove fields.
		 *
		 * @deprecated No longer using camelCase naming convention.
		 */
		public static function removeSection( string $opt_name = '', $id = '', bool $fields = false ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			if ( '' !== $opt_name ) {
				Redux_Functions_Ex::record_caller( $opt_name );
			}

			self::remove_section( $opt_name, $id, $fields );
		}

		/**
		 * Remove option panel by ID.
		 *
		 * @param string     $opt_name Panel opt_name.
		 * @param string|int $id       Sectio ID.
		 * @param bool       $fields   Remove fields.
		 */
		public static function remove_section( string $opt_name = '', $id = '', bool $fields = false ) {
			if ( '' !== $opt_name && '' !== $id ) {
				Redux_Functions_Ex::record_caller( $opt_name );

				if ( isset( self::$sections[ $opt_name ][ $id ] ) ) {
					$priority = '';

					foreach ( self::$sections[ $opt_name ] as $key => $section ) {
						if ( $key === $id ) {
							$priority = $section['priority'];
							self::$priority[ $opt_name ]['sections']--;
							unset( self::$sections[ $opt_name ][ $id ] );
							continue;
						}
						if ( '' !== $priority ) {
							$new_priority                        = $section['priority'];
							$section['priority']                 = $priority;
							self::$sections[ $opt_name ][ $key ] = $section;
							$priority                            = $new_priority;
						}
					}

					if ( isset( self::$fields[ $opt_name ] ) && ! empty( self::$fields[ $opt_name ] ) && true === $fields ) {
						foreach ( self::$fields[ $opt_name ] as $key => $field ) {
							if ( $field['section_id'] === $id ) {
								unset( self::$fields[ $opt_name ][ $key ] );
							}
						}
					}
				}
			}
		}

		/**
		 * Deprecated Sets a single option panel section.
		 *
		 * @param string     $opt_name Panel opt_name.
		 * @param array|null $section  Section data.
		 *
		 * @deprecated No longer using camelCase naming convention.
		 */
		public static function setSection( string $opt_name = '', ?array $section = array() ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			if ( '' !== $opt_name ) {
				Redux_Functions_Ex::record_caller( $opt_name );
			}

			self::set_section( $opt_name, $section );
		}

		/**
		 * Sets a single option panel section.
		 *
		 * @param string     $opt_name Panel opt_name.
		 * @param array|null $section  Section data.
		 * @param bool       $replace  Replaces section instead of creating a new one.
		 */
		public static function set_section( string $opt_name = '', ?array $section = array(), bool $replace = false ) {
			if ( empty( $section ) || '' === $opt_name ) {
				return;
			}

			self::check_opt_name( $opt_name );

			Redux_Functions_Ex::record_caller( $opt_name );

			if ( ! isset( $section['id'] ) ) {
				if ( isset( $section['type'] ) && 'divide' === $section['type'] ) {
					$section['id'] = time();
				} else {
					if ( isset( $section['title'] ) ) {
						$section['id'] = Redux_Core::strtolower( sanitize_title( $section['title'] ) );
					} else {
						$section['id'] = time();
					}
				}

				if ( isset( self::$sections[ $opt_name ][ $section['id'] ] ) && ! $replace ) {
					$orig = $section['id'];
					$i    = 0;

					while ( isset( self::$sections[ $opt_name ][ $section['id'] ] ) ) {
						$section['id'] = $orig . '_' . $i;
						$i++;
					}
				} elseif ( isset( self::$sections[ $opt_name ][ $section['id'] ] ) && $replace ) {
					// If replace is set, let's update the default values with these!
					$fields = false;
					if ( isset( self::$sections[ $opt_name ][ $section['id'] ]['fields'] ) && ! empty( self::$sections[ $opt_name ][ $section['id'] ]['fields'] ) ) {
						$fields = self::$sections[ $opt_name ][ $section['id'] ]['fields'];
					}
					self::$sections[ $opt_name ][ $section['id'] ] = wp_parse_args( $section, self::$sections[ $opt_name ][ $section['id'] ] );
					if ( ! empty( $fields ) ) {
						if ( ! isset( self::$sections[ $opt_name ][ $section['id'] ]['fields'] ) || ( isset( self::$sections[ $opt_name ][ $section['id'] ]['fields'] ) && empty( self::$sections[ $opt_name ][ $section['id'] ]['fields'] ) ) ) {
							self::$sections[ $opt_name ][ $section['id'] ]['fields'] = $fields;
						}
					}
				}
			}

			if ( ! empty( $opt_name ) && is_array( $section ) && ! empty( $section ) ) {
				if ( ! isset( $section['id'] ) && ! isset( $section['title'] ) ) {
					self::$errors[ $opt_name ]['section']['missing_title'] = esc_html__( 'Unable to create a section due to missing id and title.', 'redux-framework' );

					return;
				}

				if ( ! isset( $section['priority'] ) ) {
					$section['priority'] = self::get_priority( $opt_name, 'sections' );
				}

				if ( isset( $section['fields'] ) ) {
					if ( ! empty( $section['fields'] ) && is_array( $section['fields'] ) ) {
						self::process_field_array( $opt_name, $section['id'], $section['fields'] );
					}
					unset( $section['fields'] );
				}

				self::$sections[ $opt_name ][ $section['id'] ] = $section;
			} else {
				self::$errors[ $opt_name ]['section']['empty'] = esc_html__( 'Unable to create a section due an empty section array or the section variable passed was not an array.', 'redux-framework' );
			}
		}

		/**
		 * Deprecated Hides an option panel section.
		 *
		 * @param string     $opt_name Panel opt_name.
		 * @param string|int $id       Section ID.
		 * @param bool       $hide     Flag to hide/show.
		 *
		 * @deprecated No longer using camelCase naming convention.
		 */
		public static function hideSection( string $opt_name = '', $id = '', bool $hide = true ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			if ( '' !== $opt_name ) {
				Redux_Functions_Ex::record_caller( $opt_name );
			}

			self::hide_section( $opt_name, $id, $hide );
		}

		/**
		 * Hides an option panel section.
		 *
		 * @param string     $opt_name Panel opt_name.
		 * @param string|int $id       Section ID.
		 * @param bool       $hide     Flag to hide/show.
		 */
		public static function hide_section( string $opt_name = '', $id = '', bool $hide = true ) {
			self::check_opt_name( $opt_name );

			if ( '' !== $opt_name && '' !== $id ) {
				Redux_Functions_Ex::record_caller( $opt_name );

				if ( isset( self::$sections[ $opt_name ][ $id ] ) ) {
					self::$sections[ $opt_name ][ $id ]['hidden'] = $hide;
				}
			}
		}

		/**
		 * Compiles field array data.
		 *
		 * @param string     $opt_name   Panel opt_name.
		 * @param string|int $section_id Section ID.
		 * @param array      $fields     Field data.
		 */
		private static function process_field_array( string $opt_name = '', $section_id = '', array $fields = array() ) {
			if ( ! empty( $opt_name ) && ! empty( $section_id ) && is_array( $fields ) && ! empty( $fields ) ) {
				foreach ( $fields as $field ) {
					if ( ! is_array( $field ) ) {
						continue;
					}
					self::set_field( $opt_name, $section_id, $field );
				}
			}
		}

		/**
		 * Deprecated Retrieves an option panel field.
		 *
		 * @param string     $opt_name Panel opt_name.
		 * @param string|int $id       Field ID.
		 *
		 * @return int|bool
		 * @deprecated No longer using camelCase naming convention.
		 */
		public static function getField( string $opt_name = '', $id = '' ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			return self::get_field( $opt_name, $id );
		}

		/**
		 * Retrieves an option panel field.
		 *
		 * @param string     $opt_name Panel opt_name.
		 * @param string|int $id       Field ID.
		 *
		 * @return int|bool
		 */
		public static function get_field( string $opt_name = '', $id = '' ) {
			self::check_opt_name( $opt_name );

			if ( ! empty( $opt_name ) && ! empty( $id ) ) {
				return self::$fields[ $opt_name ][ $id ] ?? false;
			}

			return false;
		}

		/**
		 * Deprecated Hides an optio panel field.
		 *
		 * @param string     $opt_name Panel opt_name.
		 * @param string|int $id       Field ID.
		 * @param bool       $hide     Set hide/show.
		 *
		 * @deprecated No longer using camelCase naming convention.
		 */
		public static function hideField( string $opt_name = '', $id = '', bool $hide = true ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			if ( '' !== $opt_name ) {
				Redux_Functions_Ex::record_caller( $opt_name );
			}

			self::hide_field( $opt_name, $id, $hide );
		}

		/**
		 * Hides an option panel field.
		 *
		 * @param string     $opt_name Panel opt_name.
		 * @param string|int $id       Field ID.
		 * @param bool       $hide     Set hide/show.
		 */
		public static function hide_field( string $opt_name = '', $id = '', bool $hide = true ) {
			self::check_opt_name( $opt_name );

			if ( '' !== $opt_name && '' !== $id ) {
				if ( isset( self::$fields[ $opt_name ][ $id ] ) ) {
					Redux_Functions_Ex::record_caller( $opt_name );

					if ( ! $hide ) {
						self::$fields[ $opt_name ][ $id ]['class'] = str_replace( 'hidden', '', self::$fields[ $opt_name ][ $id ]['class'] );
					} else {
						self::$fields[ $opt_name ][ $id ]['class'] .= 'hidden';
					}
				}
			}
		}

		/**
		 * Deprecated Creates an option panel field.
		 *
		 * @param string     $opt_name   Panel opt_name.
		 * @param string|int $section_id Section ID this field belongs to.
		 * @param array      $field      Field data.
		 *
		 * @deprecated No longer using camelCase naming convention.
		 */
		public static function setField( string $opt_name = '', $section_id = '', array $field = array() ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			if ( '' !== $opt_name ) {
				Redux_Functions_Ex::record_caller( $opt_name );
			}

			self::set_field( $opt_name, $section_id, $field );
		}

		/**
		 * Creates an option panel field and adds to a section.
		 *
		 * @param string     $opt_name   Panel opt_name.
		 * @param string|int $section_id Section ID this field belongs to.
		 * @param array      $field      Field data.
		 */
		public static function set_field( string $opt_name = '', $section_id = '', array $field = array() ) {

			if ( ! is_array( $field ) || empty( $field ) || '' === $opt_name || '' === $section_id ) {
				return;
			}

			self::check_opt_name( $opt_name );

			Redux_Functions_Ex::record_caller( $opt_name );

			// Shim for the old method!
			if ( is_array( $section_id ) ) {
				$section_id_holder = $field;
				$field             = $section_id;
				if ( isset( $field['section_id'] ) ) {
					$section_id = $field['section_id'];
				}
			}

			$field['section_id'] = $section_id;

			if ( ! isset( $field['priority'] ) ) {
				$field['priority'] = self::get_priority( $opt_name, 'fields' );
			}
			$field['id'] = $field['id'] ?? "{$opt_name}_{$section_id}_{$field['type']}_" . wp_rand( 1, 9999 );

			self::$fields[ $opt_name ][ $field['id'] ] = $field;
		}

		/**
		 * Create multiple fields of the option panel and apply to a section.
		 *
		 * @param string     $opt_name   Panel opt_name.
		 * @param int|string $section_id Section ID this field belongs to.
		 * @param array      $fields     Array of field arrays.
		 */
		public static function set_fields( string $opt_name = '', $section_id = '', array $fields = array() ) {
			if ( ! is_array( $fields ) || empty( $fields ) || '' === $opt_name || '' === $section_id ) {
				return;
			}

			self::check_opt_name( $opt_name );

			// phpcs:ignore WordPress.PHP.DevelopmentFunctions
			Redux_Functions_Ex::record_caller( $opt_name );

			foreach ( $fields as $field ) {
				if ( is_array( $field ) ) {
					self::set_field( $opt_name, $section_id, $field );
				}
			}
		}

		/**
		 * Deprecated Removes an option panel field.
		 *
		 * @param string     $opt_name Panel opt_name.
		 * @param string|int $id       Field ID.
		 *
		 * @return bool
		 * @deprecated No longer using camelCase naming convention.
		 */
		public static function removeField( string $opt_name = '', $id = '' ): bool { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			if ( '' !== $opt_name ) {
				Redux_Functions_Ex::record_caller( $opt_name );
			}

			return self::remove_field( $opt_name, $id );
		}

		/**
		 * Removes an option panel field.
		 *
		 * @param string     $opt_name Panel opt_name.
		 * @param string|int $id       Field ID.
		 *
		 * @return bool
		 */
		public static function remove_field( string $opt_name = '', $id = '' ): bool {
			if ( '' !== $opt_name && '' !== $id ) {
				self::check_opt_name( $opt_name );

				Redux_Functions_Ex::record_caller( $opt_name );

				if ( isset( self::$fields[ $opt_name ][ $id ] ) ) {
					foreach ( self::$fields[ $opt_name ] as $key => $field ) {
						if ( $key === $id ) {
							$priority = $field['priority'];
							self::$priority[ $opt_name ]['fields']--;
							unset( self::$fields[ $opt_name ][ $id ] );
							continue;
						}

						if ( isset( $priority ) && '' !== $priority ) {
							$new_priority                      = $field['priority'];
							$field['priority']                 = $priority;
							self::$fields[ $opt_name ][ $key ] = $field;
							$priority                          = $new_priority;
						}
					}
				}
			}

			return false;
		}

		/**
		 * Deprecated Sets help tabs on option panel admin page.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param array  $tab      Tab data.
		 *
		 * @deprecated No longer using camelCase naming convention.
		 */
		public static function setHelpTab( string $opt_name = '', array $tab = array() ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			self::set_help_tab( $opt_name, $tab );
		}

		/**
		 * Sets help tabs on option panel admin page.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param array  $tab      Tab data.
		 */
		public static function set_help_tab( string $opt_name = '', array $tab = array() ) {
			if ( ! is_array( $tab ) && empty( $tab ) ) {
				return;
			}

			self::check_opt_name( $opt_name );

			if ( '' !== $opt_name ) {
				if ( ! isset( self::$args[ $opt_name ]['help_tabs'] ) ) {
					self::$args[ $opt_name ]['help_tabs'] = array();
				}

				if ( isset( $tab['id'] ) ) {
					self::$args[ $opt_name ]['help_tabs'][] = $tab;
				} elseif ( is_array( end( $tab ) ) ) {
					foreach ( $tab as $tab_item ) {
						self::$args[ $opt_name ]['help_tabs'][] = $tab_item;
					}
				}
			}
		}

		/**
		 * Deprecated Sets the help sidebar content.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $content  Content.
		 *
		 * @deprecated No longer using camelCase naming convention.
		 */
		public static function setHelpSidebar( string $opt_name = '', string $content = '' ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			self::set_help_sidebar( $opt_name, $content );
		}

		/**
		 * Sets the help sidebar content.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $content  Content.
		 */
		public static function set_help_sidebar( string $opt_name = '', string $content = '' ) {
			if ( '' === $content || '' === $opt_name ) {
				return;
			}
			self::check_opt_name( $opt_name );

			self::$args[ $opt_name ]['help_sidebar'] = $content;
		}

		/**
		 * Deprecated Sets option panel global arguments.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param array  $args     Argument data.
		 *
		 * @deprecated No longer using camelCase naming convention.
		 */
		public static function setArgs( string $opt_name = '', array $args = array() ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			if ( '' !== $opt_name ) {
				Redux_Functions_Ex::record_caller( $opt_name );
			}

			self::set_args( $opt_name, $args );
		}

		/**
		 * Sets option panel global arguments.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param array  $args     Argument data.
		 */
		public static function set_args( string $opt_name = '', array $args = array() ) {
			if ( empty( $args ) || '' === $opt_name ) {
				return;
			}

			self::check_opt_name( $opt_name );

			Redux_Functions_Ex::record_caller( $opt_name );

			if ( is_array( $args ) ) {
				if ( isset( self::$args[ $opt_name ] ) && isset( self::$args[ $opt_name ]['clearArgs'] ) ) {
					self::$args[ $opt_name ] = array();
				}
				self::$args[ $opt_name ] = wp_parse_args( $args, self::$args[ $opt_name ] );
			}
		}

		/**
		 * Set's developer key for premium services.
		 *
		 * @param string       $opt_name Panel opt_name.
		 * @param string|array $arg      Args data.
		 */
		public static function set_developer( string $opt_name = '', $arg = '' ) {
			if ( empty( $arg ) || '' === $opt_name ) {
				return;
			}

			self::check_opt_name( $opt_name );

			Redux_Functions_Ex::record_caller( $opt_name );

			self::$args[ $opt_name ]['developer'] = $arg;
		}

		/**
		 * Deprecated Retrives option panel global arguemnt array.
		 *
		 * @param string $opt_name Panel opt_name.
		 *
		 * @return mixed
		 * @deprecated No longer camelCase naming convention.
		 */
		public static function getArgs( string $opt_name = '' ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			return self::get_args( $opt_name );
		}

		/**
		 * Retrives option panel global arguemnt array.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $key      Argument key name to be returned.
		 *
		 * @return mixed|null|array
		 */
		public static function get_args( string $opt_name = '', string $key = '' ) {
			self::check_opt_name( $opt_name );

			if ( ! empty( $opt_name ) && ! empty( $key ) ) {
				if ( ! empty( self::$args[ $opt_name ] ) ) {
					return self::$args[ $opt_name ][ $key ];
				} else {
					return null;
				}
			} elseif ( ! empty( $opt_name ) && ! empty( self::$args[ $opt_name ] ) ) {
				return self::$args[ $opt_name ];
			}
		}

		/**
		 * Deprecated Retrieves a single global argument.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $key      Argument name.
		 *
		 * @return mixed
		 * @deprecated No longer using camelCase naming convention and using singular function self::get_args() now.
		 */
		public static function getArg( string $opt_name = '', string $key = '' ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			return self::get_args( $opt_name, $key );
		}

		/**
		 * Retrieves a single global argument.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $key      Argument name.
		 *
		 * @return mixed|array|null
		 */
		public static function get_arg( string $opt_name = '', string $key = '' ) {
			self::check_opt_name( $opt_name );

			if ( ! empty( $opt_name ) && ! empty( $key ) && ! empty( self::$args[ $opt_name ] ) ) {
				return self::$args[ $opt_name ][ $key ];
			} else {
				return null;
			}
		}

		/**
		 * Deprecated Retrieves single option from the database.
		 *
		 * @param string       $opt_name Panel opt_name.
		 * @param string       $key      Option key.
		 * @param string|array $default  Default value.
		 *
		 * @return mixed
		 * @deprecated No longer using camelCase naming convention.
		 */
		public static function getOption( string $opt_name = '', string $key = '', $default = '' ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			return self::get_option( $opt_name, $key );
		}

		/**
		 * Retrieves meta for a given post page, IE WordPress meta values
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param mixed  $the_post Post object to denote the current post, or custom.
		 * @param string $key      Option key.
		 * @param mixed  $default  Default value.
		 *
		 * @return mixed
		 */
		public static function get_post_meta( string $opt_name = '', $the_post = array(), string $key = '', $default = null ) {
			self::check_opt_name( $opt_name );

			if ( empty( $opt_name ) ) {
				return;
			}

			global $post;

			$redux = ReduxFrameworkInstances::get_instance( $opt_name );

			// We don't ever need to specify advanced_metaboxes here as all function for metaboxes are core,
			// and thus, metabox_lite.  The extension handles its own functions and is handled by this condition. - kp.
			$metaboxes = $redux->extensions['metaboxes'];

			if ( null === $default || '' === $default ) {
				$default = self::get_option( $opt_name, $key );
			}

			if ( isset( $the_post ) && is_array( $the_post ) ) {
				$the_post = $post;
			} elseif ( ! isset( $the_post ) || 0 === $the_post ) {
				return $default;
			} elseif ( is_numeric( $the_post ) ) {
				$the_post = get_post( $the_post );
			} elseif ( ! is_object( $the_post ) ) {
				$the_post = $post;
			}

			$default = self::get_option( $opt_name, $key );

			return $metaboxes->get_values( $the_post, $key, $default );
		}

		/**
		 * Retrieves single option from the database.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $key      Option key.
		 * @param mixed  $default  Default value.
		 *
		 * @return mixed
		 */
		public static function get_option( string $opt_name = '', string $key = '', $default = null ) {
			self::check_opt_name( $opt_name );

			// TODO - Add metaboxes magic here!
			if ( ! empty( $opt_name ) && ! empty( $key ) ) {
				global $$opt_name;

				if ( empty( $$opt_name ) ) {
					$values    = get_option( $opt_name );
					$$opt_name = $values;
				} else {
					$values = $$opt_name;
				}

				if ( ! isset( $values[ $key ] ) ) {
					if ( null === $default ) {
						$field = self::get_field( $opt_name, $key );

						if ( false !== $field ) {
							$defaults_class = new Redux_Options_Defaults();
							$sections       = self::construct_sections( $opt_name );
							$defaults       = $defaults_class->default_values( $opt_name, $sections );

							if ( isset( $defaults[ $key ] ) ) {
								$default = $defaults[ $key ];
							}
						}
					}
				}

				if ( ! empty( $subkeys ) && is_array( $subkeys ) ) {
					$value = $default;

					if ( isset( $values[ $key ] ) ) {
						$count = count( $subkeys );

						if ( 1 === $count ) {
							$value = $values[ $key ][ $subkeys[1] ] ?? $default;
						} elseif ( 2 === $count ) {
							if ( isset( $values[ $key ][ $subkeys[1] ] ) ) {
								$value = $values[ $key ][ $subkeys[1] ][ $subkeys[2] ] ?? $default;
							}
						} elseif ( 3 === $count ) {
							if ( isset( $values[ $key ][ $subkeys[1] ] ) ) {
								if ( isset( $values[ $key ][ $subkeys[1] ][ $subkeys[2] ] ) ) {
									$value = $values[ $key ][ $subkeys[1] ][ $subkeys[2] ][ $subkeys[3] ] ?? $default;
								}
							}
						}
					}
				} else {
					$value = $values[ $key ] ?? $default;
				}

				return $value;
			} else {
				return false;
			}
		}

		/**
		 * Deprecated Sets an option into the database.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $key      Option key.
		 * @param mixed  $option   Option value.
		 *
		 * @return bool
		 * @deprecated No longer using camelCase naming convention.
		 */
		public static function setOption( string $opt_name = '', string $key = '', $option = '' ): bool { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			if ( '' !== $opt_name ) {
				Redux_Functions_Ex::record_caller( $opt_name );
			}

			return self::set_option( $opt_name, $key, $option );
		}

		/**
		 * Sets an option into the database.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $key      Option key.
		 * @param mixed  $option   Option value.
		 *
		 * @return bool
		 */
		public static function set_option( string $opt_name = '', string $key = '', $option = '' ): bool {
			if ( '' === $key ) {
				return false;
			}

			self::check_opt_name( $opt_name );

			Redux_Functions_Ex::record_caller( $opt_name );

			if ( '' !== $opt_name ) {
				$redux         = get_option( $opt_name );
				$redux[ $key ] = $option;

				return update_option( $opt_name, $redux );
			} else {
				return false;
			}
		}

		/**
		 * Get next availablt priority for field/section.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $type     Field or section.
		 *
		 * @return mixed
		 */
		public static function get_priority( string $opt_name, string $type ) {
			$priority                              = self::$priority[ $opt_name ][ $type ];
			self::$priority[ $opt_name ][ $type ] += 1;

			return $priority;
		}

		/**
		 * Check opt_name integrity.
		 *
		 * @param string $opt_name Panel opt_name.
		 */
		public static function check_opt_name( string $opt_name = '' ) {
			if ( empty( $opt_name ) || is_array( $opt_name ) ) {
				return;
			}

			if ( ! isset( self::$sections[ $opt_name ] ) ) {
				self::$sections[ $opt_name ]             = array();
				self::$priority[ $opt_name ]['sections'] = 1;
			}

			if ( ! isset( self::$args[ $opt_name ] ) ) {
				self::$args[ $opt_name ]             = array();
				self::$priority[ $opt_name ]['args'] = 1;
			}

			if ( ! isset( self::$fields[ $opt_name ] ) ) {
				self::$fields[ $opt_name ]             = array();
				self::$priority[ $opt_name ]['fields'] = 1;
			}

			if ( ! isset( self::$help[ $opt_name ] ) ) {
				self::$help[ $opt_name ]             = array();
				self::$priority[ $opt_name ]['help'] = 1;
			}

			if ( ! isset( self::$errors[ $opt_name ] ) ) {
				self::$errors[ $opt_name ] = array();
			}

			if ( ! isset( self::$init[ $opt_name ] ) ) {
				self::$init[ $opt_name ] = false;
			}
		}

		/**
		 * Retrieve metadata from a file. Based on WP Core's get_file_data function
		 *
		 * @param string $file Path to the file.
		 *
		 * @return string
		 * @since 2.1.1
		 */
		public static function get_file_version( string $file ): string {
			$data = get_file_data( $file, array( 'version' ), 'plugin' );

			return $data[0];
		}

		/**
		 * Verify extension class name.
		 *
		 * @param string $opt_name   Panel opt_name.
		 * @param string $name       extension name.
		 * @param string $class_file Extension class file.
		 */
		private static function check_extension_class_file( string $opt_name, string $name = '', string $class_file = '' ) {
			$instance = null;

			if ( file_exists( $class_file ) ) {
				self::$uses_extensions[ $opt_name ] = self::$uses_extensions[ $opt_name ] ?? array();

				if ( ! in_array( $name, self::$uses_extensions[ $opt_name ], true ) ) {
					self::$uses_extensions[ $opt_name ][] = $name;
				}

				self::$extensions[ $name ] = self::$extensions[ $name ] ?? array();

				$version = Redux_Helpers::get_template_version( $class_file );

				if ( empty( $version ) && ! empty( $instance ) ) {
					if ( isset( $instance->version ) ) {
						$version = $instance->version;
					}
				}
				self::$extensions[ $name ][ $version ] = self::$extensions[ $name ][ $version ] ?? $class_file;

				$new_name  = str_replace( '_', '-', $name );
				$api_check = str_replace(
					array(
						'extension_' . $name,
						'class-redux-extension-' . $new_name,
					),
					array(
						$name . '_api',
						'class-redux-' . $new_name . '-api',
					),
					$class_file
				);

				if ( file_exists( $api_check ) && ! class_exists( 'Redux_' . ucfirst( $name ) ) ) {
					include_once $api_check;
				}
			}
		}

		/**
		 * Deprecated Sets all extensions in path.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $path     Path to extension folder.
		 *
		 * @deprecated No longer using camelCase naming convention.
		 */
		public static function setExtensions( string $opt_name, string $path ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			if ( '' !== $opt_name ) {
				Redux_Functions_Ex::record_caller( $opt_name );
			}

			self::set_extensions( $opt_name, $path );
		}

		/**
		 * Sets all extensions in path.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $path     Path to extension folder.
		 * @param bool   $force    Make extension reload.
		 */
		public static function set_extensions( string $opt_name, string $path, bool $force = false ) {
			if ( '' === $path || '' === $opt_name ) {
				return;
			}

			if ( version_compare( PHP_VERSION, '5.5.0', '<' ) ) {
				include_once Redux_Core::$dir . 'inc/lib/array-column.php';
			}

			self::check_opt_name( $opt_name );

			Redux_Functions_Ex::record_caller( $opt_name );

			if ( is_dir( $path ) ) {
				$path   = trailingslashit( $path );
				$folder = str_replace( '.php', '', basename( $path ) );

				$folder_fix = str_replace( '_', '-', $folder );

				$files = array(
					$path . 'extension_' . $folder . '.php',
					$path . 'class-redux-extension-' . $folder_fix . '.php',
				);

				$ext_file = Redux_Functions::file_exists_ex( $files );

				if ( $ext_file ) {
					self::check_extension_class_file( $opt_name, $folder, $ext_file );
				} else {
					$folders = scandir( $path, 1 );

					foreach ( $folders as $folder ) {
						if ( '.' === $folder || '..' === $folder ) {
							continue;
						}

						if ( is_dir( $path . $folder ) ) {
							self::set_extensions( $opt_name, $path . $folder );
						}
					}
				}
			} elseif ( file_exists( $path ) ) {
				$name = explode( 'extension_', basename( $path ) );
				if ( isset( $name[1] ) && ! empty( $name[1] ) ) {
					$name = str_replace( '.php', '', $name[1] );
					self::check_extension_class_file( $opt_name, $name, $path );
				}
			}

			self::$extension_paths[ $opt_name ] = $path;

			if ( true === $force ) {
				if ( isset( self::$uses_extensions[ $opt_name ] ) && ! empty( self::$uses_extensions[ $opt_name ] ) ) {
					$redux = self::instance( $opt_name );

					if ( isset( $redux ) ) {
						self::load_extensions( $redux );
					}
				}
			}
		}

		/**
		 * Retrieves all loaded extensions.
		 */
		private static function get_all_extension() {
			$redux = self::all_instances();

			foreach ( $redux as $instance ) {
				if ( ! empty( self::$uses_extensions[ $instance['args']['opt_name'] ] ) ) {
					continue;
				}
				if ( ! empty( $instance['extensions'] ) ) {
					self::get_instance_extension( $instance['args']['opt_name'], $instance );
				}
			}
		}

		/**
		 * Gets all loaded extension for the passed ReduxFramework instance.
		 *
		 * @param string      $opt_name Panel opt_name.
		 * @param object|null $instance ReduxFramework instance.
		 */
		public static function get_instance_extension( string $opt_name, $instance ) {
			if ( ! empty( self::$uses_extensions[ $opt_name ] ) || empty( $opt_name ) ) {
				return;
			}

			if ( empty( $instance ) ) {
				$instance = self::instance( $opt_name );
			}

			if ( empty( $instance ) || empty( $instance->extensions ) ) {
				return;
			}

			foreach ( $instance->extensions as $name => $extension ) {
				if ( 'widget_areas' === $name ) {
					$new = new Redux_Widget_Areas( $instance );
				}

				if ( isset( self::$uses_extensions[ $opt_name ][ $name ] ) ) {
					continue;
				}

				if ( isset( $extension->extension_dir ) ) {
					self::set_extensions( $opt_name, str_replace( $name, '', $extension->extension_dir ) );
				} elseif ( isset( $extension->extension_dir ) ) {
					self::set_extensions( $opt_name, str_replace( $name, '', $extension->extension_dir ) );
				}
			}
		}

		/**
		 * Deprecated Gets loaded extensions.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $key      Extension name.
		 *
		 * @return array|bool|mixed
		 * @deprecated No longer using camelCase naming convention.
		 */
		public static function getExtensions( string $opt_name = '', string $key = '' ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.0.0', 'self::get_extensions( $opt_name, $key )' );

			return self::get_extensions( $opt_name, $key );
		}

		/**
		 * Gets loaded extensions.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $key      Extension name.
		 *
		 * @return array|bool|mixed
		 */
		public static function get_extensions( string $opt_name = '', string $key = '' ) {
			if ( empty( $opt_name ) ) {
				self::get_all_extension();

				if ( empty( $key ) ) {
					return self::$extension_paths;
				} else {
					if ( isset( self::$extension_paths[ $key ] ) ) {
						return self::$extension_paths[ $key ];
					}
				}
			} else {
				if ( empty( self::$uses_extensions[ $opt_name ] ) ) {
					self::get_instance_extension( $opt_name, null );
				}

				if ( empty( self::$uses_extensions[ $opt_name ] ) ) {
					return false;
				}

				$instance_extensions = array();

				foreach ( self::$uses_extensions[ $opt_name ] as $extension ) {
					$class_file = end( self::$extensions[ $extension ] );
					$directory  = explode( DIRECTORY_SEPARATOR, $class_file );
					array_pop( $directory );
					$directory       = trailingslashit( join( DIRECTORY_SEPARATOR, $directory ) );
					$name            = str_replace( '.php', '', basename( $extension ) );
					$extension_class = 'Redux_Extension_' . $name;
					$the_data        = array(
						'path'    => $class_file,
						'dir'     => $directory,
						'class'   => $extension_class,
						'version' => Redux_Helpers::get_template_version( $class_file ),
					);

					if ( is_dir( $the_data['dir'] . $extension ) ) {
						$test_path = trailingslashit( $the_data['dir'] . $extension );
						if ( file_exists( $test_path . 'field_' . str_replace( '-', '', $extension ) . '.php' ) ) {
							$the_data['field'] = $test_path . 'field_' . str_replace( '-', '', $extension ) . '.php';
						}
						// Old extensions!
						if ( file_exists( $test_path . str_replace( '-', '', $extension ) . '.php' ) ) {
							$the_data['field'] = $test_path . str_replace( '-', '', $extension ) . '.php';
						}
					}
					$instance_extensions[ $extension ] = $the_data;
				}

				return $instance_extensions;
			}

			return false;
		}

		/**
		 * Method to disables Redux demo mode popup.
		 */
		public static function disable_demo() {
			add_action( 'ReduxFrameworkPlugin_admin_notice', 'Redux::remove_demo' );
			add_action( 'redux_framework_plugin_admin_notice', 'Redux::remove_demo' );
		}

		/**
		 * Callback used by self::disable_demo() to remove the demo mode notice from Redux.
		 */
		public static function remove_demo() {
			update_option( 'ReduxFrameworkPlugin_ACTIVATED_NOTICES', '' );
		}

		/**
		 * Function which forces a panel/page to render.
		 *
		 * @param string|object $redux Panel opt_name or Redux object.
		 */
		public static function render( $redux = '' ) {
			if ( is_string( $redux ) ) {
				$redux = Redux_Instances::get_instance( $redux );
				if ( empty( $redux ) ) {
					return;
				}
			}
			$enqueue = new Redux_Enqueue( $redux );
			$enqueue->init();
			$panel = new \Redux_Panel( $redux );
			$panel->init();
		}
	}

	Redux::load();
}
