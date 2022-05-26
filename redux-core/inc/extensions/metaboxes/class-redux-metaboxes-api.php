<?php // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
/**
 * Redux Framework Metaboxes API Class
 * Makes instantiating a Redux Metabox object an absolute piece of cake.
 *
 * @package      Redux_Framework
 * @author       Dovy Paukstys
 * @subpackage   Core
 * @noinspection PhpUnused
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Metaboxes' ) ) {

	/**
	 * Redux Metaboxes API Class
	 * Simple API for Redux Framework
	 *
	 * @since       1.0.0
	 */
	class Redux_Metaboxes {

		/**
		 * Boxes array.
		 *
		 * @var array
		 */
		public static $boxes = array();

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
		 * Code has run flag.
		 *
		 * @var bool
		 */
		public static $has_run = false;

		/**
		 * Class load.
		 */
		public static function load() {
			if ( version_compare( PHP_VERSION, '5.5.0', '<' ) ) {
				include_once Redux_Core::$dir . 'inc/lib/array-column.php';
			}

			add_action( 'init', array( 'Redux_Metaboxes', 'enqueue' ), 99 );
		}

		/**
		 * Enqueue function.
		 *
		 * @throws ReflectionException Exception.
		 */
		public static function enqueue() {
			global $pagenow;

			// Check and run instances of Redux where the opt_name hasn't been run.
			$pagenows = array( 'post-new.php', 'post.php' );
			if ( ! empty( self::$sections ) && in_array( $pagenow, $pagenows, true ) ) {
				$instances = ReduxFrameworkInstances::get_all_instances();

				foreach ( self::$fields as $opt_name => $fields ) {
					if ( ! isset( $instances[ $opt_name ] ) ) {
						Redux::setArgs(
							$opt_name,
							array(
								'menu_type' => 'hidden',
							)
						);

						Redux::setSections(
							$opt_name,
							array(
								array(
									'id'     => 'EXTENSION_FAKE_ID' . $opt_name,
									'fields' => $fields,
									'title'  => 'N/A',
								),
							)
						);

						Redux::init( $opt_name );

						$instances = ReduxFrameworkInstances::get_all_instances();

						add_action( 'admin_enqueue_scripts', array( $instances[ $opt_name ], 'enqueue' ), 1 );
					}
				}
			}
		}

		/**
		 * Filter Metaboxes function.
		 *
		 * @deprecated 4.0.0 No more camelCase.
		 */
		public static function filterMetaboxes() { // phpcs:ignore: WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.3', __CLASS__ . '::filter_metaboxes()' );

			self::filter_metaboxes();
		}

		/**
		 * Filter Metaboxes function.
		 */
		public static function filter_metaboxes() {
			if ( true === self::$has_run ) {
				return;
			}

			if ( ! class_exists( 'ReduxFramework' ) ) {
				echo '<div id="message" class="error"><p>Redux Framework is <strong>not installed</strong>. Please install it.</p></div>';

				return;
			}

			foreach ( self::$boxes as $opt_name => $the_boxes ) {
				if ( ! self::$init[ $opt_name ] ) {

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					add_action( 'redux/metaboxes/' . $opt_name . '/boxes', array( 'Redux_Metaboxes', 'addMetaboxes' ), 2 );
				}
			}

			self::$has_run = true;
		}

		/**
		 * Construct global args array.
		 *
		 * @param string $opt_name Panel opt_name.
		 *
		 * @return mixed
		 * @deprecated 4.0.0 No more camelCase.
		 */
		public static function constructArgs( string $opt_name ) { // phpcs:ignore: WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.3', __CLASS__ . '::construct_args( $opt_name )' );

			return self::construct_args( $opt_name );
		}

		/**
		 * Construct global args array.
		 *
		 * @param string $opt_name Panel opt_name.
		 *
		 * @return mixed
		 */
		public static function construct_args( string $opt_name ) {
			Redux_Functions_Ex::record_caller( $opt_name );

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
		 * Construct metabox boxes array.
		 *
		 * @param string $opt_name Panel opt_name.
		 *
		 * @return array
		 *
		 * @deprecated 4.0.0 No more camelCase.
		 */
		public static function constructBoxes( string $opt_name ): array {  // phpcs:ignore: WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.3', __CLASS__ . '::construct_boxes( $opt_name )' );

			return self::construct_boxes( $opt_name );
		}


		/**
		 * Construct metabox boxes array.
		 *
		 * @param string $opt_name Panel opt_name.
		 *
		 * @return array
		 */
		public static function construct_boxes( string $opt_name ): array {
			Redux_Functions_Ex::record_caller( $opt_name );

			$boxes = array();
			if ( ! isset( self::$boxes[ $opt_name ] ) ) {
				return $boxes;
			}

			foreach ( self::$boxes[ $opt_name ] as $box ) {
				$box['sections'] = self::construct_sections( $opt_name, $box['id'] );
				$boxes[]         = $box;
			}

			ksort( $boxes );

			return $boxes;
		}

		/**
		 * Construct sections.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $box_id   Metabox ID.
		 *
		 * @return array
		 *
		 * @deprecated 4.0.0 No more camelCase.
		 */
		public static function constructSections( string $opt_name, string $box_id ): array {  // phpcs:ignore: WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.3', __CLASS__ . '::construct_sections( $opt_name, $box_id )' );

			return self::construct_sections( $opt_name, $box_id );
		}

		/**
		 * Construct sections.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $box_id   Metabox ID.
		 *
		 * @return array
		 */
		public static function construct_sections( string $opt_name, string $box_id ): array {
			Redux_Functions_Ex::record_caller( $opt_name );

			$sections = array();
			if ( ! isset( self::$sections[ $opt_name ] ) ) {
				return $sections;
			}

			foreach ( self::$sections[ $opt_name ] as $section_id => $section ) {
				if ( $box_id === $section['box_id'] ) {
					$p = $section['priority'];

					while ( isset( $sections[ $p ] ) ) {
						echo esc_html( $p ++ );
					}

					$section['fields'] = self::construct_fields( $opt_name, $section_id );
					$sections[ $p ]    = $section;
				}
			}

			ksort( $sections );

			return $sections;
		}

		/**
		 * Construct fields.
		 *
		 * @param string     $opt_name   Panel opt_name.
		 * @param string|int $section_id Section ID.
		 *
		 * @return array
		 * @deprecated 4.0.0 No moe camelCase.
		 */
		public static function constructFields( string $opt_name = '', $section_id = '' ): array {  // phpcs:ignore: WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.3', __CLASS__ . '::construct_fields( $opt_name, $section_id )' );

			return self::construct_fields( $opt_name, $section_id );
		}

		/**
		 * Construct fields.
		 *
		 * @param string     $opt_name Panel opt_name.
		 * @param string|int $section_id Section ID.
		 *
		 * @return array
		 */
		public static function construct_fields( string $opt_name = '', $section_id = '' ): array {
			Redux_Functions_Ex::record_caller( $opt_name );

			$fields = array();

			if ( ! isset( self::$fields[ $opt_name ] ) ) {
				return $fields;
			}

			foreach ( self::$fields[ $opt_name ] as $field ) {
				if ( $section_id === $field['section_id'] ) {
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
		 * Retrieve section array.
		 *
		 * @param string     $opt_name Panel opt_name.
		 * @param string|int $id Section ID.
		 *
		 * @deprecated 4.0.0 No more camelCase.
		 *
		 * @return bool
		 */
		public static function getSection( string $opt_name = '', $id = '' ): bool {  // phpcs:ignore: WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.3', __CLASS__ . '::get_section( $opt_name, $id )' );

			return self::get_section( $opt_name, $id );
		}

		/**
		 * Retrieve section ID.
		 *
		 * @param string     $opt_name Panel opt_name.
		 * @param string|int $id Section ID.
		 *
		 * @return bool
		 */
		public static function get_section( string $opt_name = '', $id = '' ): bool {
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
		 * Set section array.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param array  $section Section array.
		 *
		 * @deprecated 4.0.0 No more camelCase.
		 */
		public static function setSection( string $opt_name = '', array $section = array() ) {  // phpcs:ignore: WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.3', __CLASS__ . '::set_section( $opt_name, $section )' );

			self::set_section( $opt_name, $section );
		}

		/**
		 * Set section array.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param array  $section Section array.
		 */
		public static function set_section( string $opt_name = '', array $section = array() ) {
			Redux_Functions_Ex::record_caller( $opt_name );

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
							$i++;
						}
					}
				}

				if ( ! isset( $section['priority'] ) ) {
					$section['priority'] = self::get_priority( $opt_name, 'sections' );
				}

				if ( isset( $section['fields'] ) ) {
					if ( ! empty( $section['fields'] ) && is_array( $section['fields'] ) ) {
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
		 * Process section arrays.
		 *
		 * @param string     $opt_name Panel opt_name.
		 * @param string|int $box_id   Box ID.
		 * @param array      $sections Sections array.
		 */
		public static function process_sections_array( string $opt_name = '', $box_id = '', array $sections = array() ) {
			if ( ! empty( $opt_name ) && ! empty( $box_id ) && is_array( $sections ) && ! empty( $sections ) ) {
				foreach ( $sections as $section ) {
					if ( ! is_array( $section ) ) {
						continue;
					}

					$section['box_id'] = $box_id;

					if ( ! isset( $section['fields'] ) || ! is_array( $section['fields'] ) ) {
						$section['fields'] = array();
					}

					self::set_section( $opt_name, $section );
				}
			}
		}

		/**
		 * Process Field arrays.
		 *
		 * @param string     $opt_name   Panel opt_name.
		 * @param string|int $section_id Section ID.
		 * @param array      $fields     Field arrays.
		 *
		 * @deprecated 4.0.0 No more camelCase.
		 */
		public static function processFieldsArray( string $opt_name = '', $section_id = '', array $fields = array() ) {  // phpcs:ignore: WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.3', __CLASS__ . '::process_fields_array( $opt_name, $section_id, $fields )' );

			self::process_fields_array( $opt_name, $section_id, $fields );
		}

		/**
		 * Process Field arrays.
		 *
		 * @param string     $opt_name   Panel opt_name.
		 * @param string|int $section_id Section ID.
		 * @param array      $fields     Field arrays.
		 */
		public static function process_fields_array( string $opt_name = '', $section_id = '', array $fields = array() ) {
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
		 * Retrieves field array.
		 *
		 * @param string     $opt_name Panel opt_name.
		 * @param string|int $id Field ID.
		 *
		 * @deprecated 4.0.0 No more camelCase.
		 *
		 * @return bool
		 */
		public static function getField( string $opt_name = '', $id = '' ): bool {  // phpcs:ignore: WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.3', __CLASS__ . '::get_field( $opt_name, $id )' );

			return self::get_field( $opt_name, $id );
		}

		/**
		 * Retrieves field array.
		 *
		 * @param string     $opt_name Panel opt_name.
		 * @param string|int $id Field ID.
		 *
		 * @return bool
		 */
		public static function get_field( string $opt_name = '', $id = '' ): bool {
			self::check_opt_name( $opt_name );

			if ( ! empty( $opt_name ) && ! empty( $id ) ) {
				return self::$fields[ $opt_name ][ $id ] ?? false;
			}

			return false;
		}

		/**
		 * Set field array.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param array  $field Field array.
		 *
		 * @deprecated 4.0.0 No more camelCase.
		 */
		public static function setField( string $opt_name = '', array $field = array() ) {  // phpcs:ignore: WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.3', __CLASS__ . '::set_field( $opt_name, $field )' );

			self::set_field( $opt_name, $field );
		}

		/**
		 * Set field array.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param array  $field Field array.
		 */
		public static function set_field( string $opt_name = '', array $field = array() ) {
			Redux_Functions_Ex::record_caller( $opt_name );

			self::check_opt_name( $opt_name );

			if ( ! empty( $opt_name ) && is_array( $field ) && ! empty( $field ) ) {
				if ( ! isset( $field['priority'] ) ) {
					$field['priority'] = self::get_priority( $opt_name, 'fields' );
				}

				self::$fields[ $opt_name ][ $field['id'] ] = $field;
			}
		}

		/**
		 * Set metabox box.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param array  $box Box array.
		 *
		 * @deprecated 4.0.0 No more camelCase.
		 */
		public static function setBox( string $opt_name = '', array $box = array() ) {  // phpcs:ignore: WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.3', __CLASS__ . '::set_box( $opt_name, $box )' );

			self::set_box( $opt_name, $box );
		}

		/**
		 * Set metabox box.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param array  $box Box array.
		 */
		public static function set_box( string $opt_name = '', array $box = array() ) {
			Redux_Functions_Ex::record_caller( $opt_name );

			self::check_opt_name( $opt_name );

			if ( ! empty( $opt_name ) && is_array( $box ) && ! empty( $box ) ) {
				if ( ! isset( $box['id'] ) ) {
					if ( isset( $box['title'] ) ) {
						$box['id'] = strtolower( sanitize_html_class( $box['title'] ) );
					} else {
						$box['id'] = 'box';
					}

					if ( isset( self::$boxes[ $opt_name ][ $box['id'] ] ) ) {
						$orig = $box['id'];
						$i    = 0;

						while ( isset( self::$boxes[ $opt_name ][ $box['id'] ] ) ) {
							$box['id'] = $orig . '_' . $i;
							$i++;
						}
					}
				}

				if ( isset( $box['sections'] ) ) {
					if ( ! empty( $box['sections'] ) && is_array( $box['sections'] ) ) {
						self::process_sections_array( $opt_name, $box['id'], $box['sections'] );
					}

					unset( $box['sections'] );
				}

				self::$boxes[ $opt_name ][ $box['id'] ] = $box;
			} else {
				self::$errors[ $opt_name ]['box']['empty'] = esc_html__( 'Unable to create a box due an empty box array or the box variable passed was not an array.', 'redux-framework' );
			}
		}

		/**
		 * Set Metaboxes.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param array  $boxes Boxes array.
		 *
		 * @deprecated 4.0.0 No more camelCase.
		 */
		public static function setBoxes( string $opt_name = '', array $boxes = array() ) {  // phpcs:ignore: WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.3', __CLASS__ . '::set_boxes( $opt_name, $boxes )' );

			self::set_boxes( $opt_name, $boxes );
		}

		/**
		 * Set Metaboxes.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param array  $boxes Boxes array.
		 */
		public static function set_boxes( string $opt_name = '', array $boxes = array() ) {
			Redux_Functions_Ex::record_caller( $opt_name );

			if ( ! empty( $boxes ) && is_array( $boxes ) ) {
				foreach ( $boxes as $box ) {
					self::set_box( $opt_name, $box );
				}
			}
		}

		/**
		 * Retrieve Metabox arrays.
		 *
		 * @param string $opt_name Panel opt_name.
		 *
		 * @deprecated 4.0.0 No more camelCase.
		 *
		 * @return mixed
		 */
		public static function getBoxes( string $opt_name = '' ) {  // phpcs:ignore: WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.3', __CLASS__ . '::get_boxes( $opt_name )' );

			return self::get_boxes( $opt_name );
		}

		/**
		 * Retrieve Metabox arrays.
		 *
		 * @param string $opt_name Panel opt_name.
		 *
		 * @return mixed
		 */
		public static function get_boxes( string $opt_name = '' ) {
			self::check_opt_name( $opt_name );

			if ( ! empty( $opt_name ) && ! empty( self::$boxes[ $opt_name ] ) ) {
				return self::$boxes[ $opt_name ];
			}

			return null;
		}

		/**
		 * Get Metabox box.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $key Box key.
		 *
		 * @deprecated 4.0.0 No more camelCase.
		 *
		 * @return mixed
		 */
		public static function getBox( string $opt_name = '', string $key = '' ) {  // phpcs:ignore: WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.3', __CLASS__ . '::get_box( $opt_name, $key )' );

			return self::get_box( $opt_name, $key );
		}

		/**
		 * Get Metabox box.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $key Box key.
		 *
		 * @return mixed
		 */
		public static function get_box( string $opt_name = '', string $key = '' ) {
			self::check_opt_name( $opt_name );

			if ( ! empty( $opt_name ) && ! empty( $key ) && ! empty( self::$boxes[ $opt_name ] ) && isset( self::$boxes[ $opt_name ][ $key ] ) ) {
				return self::$boxes[ $opt_name ][ $key ];
			}

			return null;
		}

		/**
		 * Get priority.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $type     Type.
		 *
		 * @return mixed
		 *
		 * @deprecated 4.0.0 No more camelCase.
		 */
		public static function getPriority( string $opt_name, string $type ) {  // phpcs:ignore: WordPress.NamingConventions.ValidFunctionName
			_deprecated_function( __CLASS__ . '::' . __FUNCTION__, 'Redux 4.3', __CLASS__ . '::get_priority( $opt_name, $type )' );

			return self::get_priority( $opt_name, $type );
		}

		/**
		 * Get priority.
		 *
		 * @param string $opt_name Panel opt_name.
		 * @param string $type     Type.
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

			if ( ! isset( self::$boxes[ $opt_name ] ) ) {
				self::$boxes[ $opt_name ] = array();
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
	}

	Redux_Metaboxes::load();
}
