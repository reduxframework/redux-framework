<?php
/**
 * Redux Build API Class
 *
 * @class Redux_Rest_Api_Builder
 * @version 4.0.0
 * @package Redux Framework
 * @author Tofandel & Dovy
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class Redux_Rest_Api_Builder
 */
class Redux_Rest_Api_Builder {

	const ENDPOINT = 'redux/descriptors';
	const VER      = 'v1';

	/**
	 * Get the namespace of the api.
	 *
	 * @return string
	 */
	public function get_namespace() {
		return self::ENDPOINT . '/' . self::VER;
	}

	/**
	 * Get the rest url for an api call.
	 *
	 * @param string $route Route router.
	 *
	 * @return string
	 */
	public function get_url( $route ) {
		return rest_url( trailingslashit( $this->get_namespace() ) . ltrim( '/', $route ) );
	}

	/**
	 * Redux_Rest_Api_Builder constructor.
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );
	}

	/**
	 * Init the rest api.
	 */
	public function rest_api_init() {
		register_rest_route(
			$this->get_namespace(),
			'/fields',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'list_fields' ),
				'permission_callback' => '__return_true',
			)
		);
		register_rest_route(
			$this->get_namespace(),
			'/field/(?P<type>[a-z0-9-_]+)',
			array(
				'args'                => array(
					'type' => array(
						'description' => __( 'The field type', 'redux-framework' ),
						'type'        => 'string',
					),
				),
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_field' ),
				'permission_callback' => '__return_true',
			)
		);
		register_rest_route(
			$this->get_namespace(),
			'/field/(?P<type>[a-z0-9-_]+)/render',
			array(
				'args'                => array(
					'name' => array(
						'description' => __( 'The field type', 'redux-framework' ),
						'type'        => 'string',
					),
				),
				'methods'             => WP_REST_Server::ALLMETHODS,
				'callback'            => array( $this, 'render_field' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Fetch the folders in the field directory
	 *
	 * @return RecursiveDirectoryIterator
	 */
	public function field_directories() {

		return $dirs;
	}

	/**
	 * Helper to get the Redux fields paths.
	 *
	 * @return array
	 */
	public function get_field_paths() {
		$fields     = array();
		$fields_dir = trailingslashit( Redux_Core::$dir ) . 'inc' . DIRECTORY_SEPARATOR . 'fields' . DIRECTORY_SEPARATOR;
		$dirs       = new RecursiveDirectoryIterator( $fields_dir );

		$data = array();
		foreach ( $dirs as $path ) {
			$folder = explode( '/', $path );
			$folder = end( $folder );
			if ( in_array( $folder, array( '.', '..' ), true ) ) {
				continue;
			}
			$files    = array(
				trailingslashit( $path ) . 'field_' . $folder . '.php',
				trailingslashit( $path ) . 'class-redux-' . $folder . '.php',
			);
			$filename = Redux_Functions::file_exists_ex( $files );

			if ( $filename ) {
				$data[ $folder ] = $filename;
			}
		}
		// phpcs:ignore WordPress.NamingConventions.ValidHookName
		$data = apply_filters( 'redux/fields', $data );

		return $data;
	}

	/**
	 * List the available fields.
	 *
	 * @return array
	 */
	public function list_fields() {
		$field_classes = $this->get_field_paths();
		$fields        = array();

		foreach ( $field_classes as $folder => $filename ) {
			$class = 'Redux_' . ucwords( str_replace( '-', '_', $folder ) );
			if ( ! class_exists( $class ) && file_exists( $filename ) ) {
				require_once $filename;
			}
			$field_class = Redux_Functions::class_exists_ex( $field_classes );
			// Load it here to save some resources in autoloading!
			if ( $field_class && is_subclass_of( $class, 'Redux_Field' ) ) {
				$descriptor      = call_user_func( array( $class, 'get_descriptor' ) );
				$descriptor_type = $descriptor->get_field_type();
				if ( ! empty( $descriptor_type ) ) {
					$field_data = $descriptor->to_array();
					if ( isset( $field_data['fields'] ) && ! empty( $field_data['fields'] ) ) {
						$field_data['fields'] = $this->prepare_fields_output( $field_data['fields'] );
					}
					$fields[ $descriptor_type ] = $field_data;
				}
			}
		}

		return $fields;
	}

	/**
	 * Get the information of a field.
	 *
	 * @param array $request Pointer to ReduxFramework object.
	 *
	 * @return array
	 */
	public function get_field( $request = array() ) {
		$type = $request['type'];

		$field_classes = $this->get_field_paths();
		if ( isset( $field_classes[ mb_strtolower( $type ) ] ) ) {
			$class = 'Redux_' . ucwords( str_replace( '-', '_', $type ) );
			if ( ! class_exists( $class ) ) {
				require_once $field_classes[ mb_strtolower( $type ) ];
			}
			$field_class = array( 'Redux_' . ucwords( $type ), 'ReduxFramework_' . ucwords( $type ) );
			$field_class = Redux_Functions::class_exists_ex( $field_class );

			if ( $field_class && is_subclass_of( $field_class, 'Redux_Field' ) ) {
				/**
				 * Test if the field exists
				 *
				 * @var Redux_Descriptor $descriptor
				 */
				$descriptor = call_user_func( array( $field_class, 'get_descriptor' ) );

				$data = $descriptor->to_array();
				if ( isset( $data['fields'] ) ) {
					$data['fields'] = $this->prepare_fields_output( $data['fields'] );
				}

				return $data;
			}
		}

		return array( 'success' => false );
	}

	/**
	 * Used to order the fields based on the order key.
	 *
	 * @param array $a First value.
	 * @param array $b Second value.
	 *
	 * @return array
	 */
	public function sort_by_order( $a, $b ) {
		return $a['order'] - $b['order'];
	}

	/**
	 * Prepares the fields value to have the proper order.
	 *
	 * @param array $fields Array of fields.
	 *
	 * @return array
	 */
	private function prepare_fields_output( $fields = array() ) {
		if ( empty( $fields ) ) {
			return $fields;
		}
		$new_output = array_values( $fields );
		usort( $new_output, array( $this, 'sort_by_order' ) );
		foreach ( $new_output as $key => $item ) {
			if ( isset( $item['order'] ) ) {
				unset( $new_output[ $key ]['order'] );
			}
		}
		return $new_output;
	}

	/**
	 * Render the html of a field and return it to the api.
	 *
	 * @param array $request Name of field.
	 *
	 * @return array
	 */
	public function render_field( $request = array() ) {
		$type          = $request['type'];
		$field_classes = $this->get_field_paths();
		if ( isset( $field_classes[ mb_strtolower( $type ) ] ) ) {
			$class = 'Redux_' . ucwords( str_replace( '-', '_', $type ) );
			if ( ! class_exists( $class ) ) {
				require_once $field_classes[ mb_strtolower( $type ) ];
			}
			$field_class = array( 'Redux_' . ucwords( $type ), 'ReduxFramework_' . ucwords( $type ) );
			$field_class = Redux_Functions::class_exists_ex( $field_class );

			if ( $field_class && is_subclass_of( $field_class, 'Redux_Field' ) ) {
				// TODO MODIFY the function to get the post data from the data object with a post method in the register route!
				try {
					$class = new ReflectionClass( 'ReduxFramework_' . $type );
				} catch ( ReflectionException $e ) {
					return array( 'success' => false );
				}

				/**
				 * Grab the field descriptor
				 *
				 * @var Redux_Descriptor $descriptor
				 */
				$descriptor = call_user_func( array( 'ReduxFramework_' . $type, 'get_descriptor' ) );
				$opt_name   = 'redux_builder_api';

				$redux_instance = new ReduxFramework( array(), array( 'opt_name' => $opt_name ) );
				$req            = $descriptor->parse_request( $request );

				$req = wp_parse_args(
					$req,
					array(
						'class'          => '',
						'example_values' => '',
						'name_suffix'    => '',
					)
				);

				$req['id']   = isset( $request['id'] ) ? $request['id'] : 'redux_field';
				$req['name'] = isset( $request['name'] ) ? $request['name'] : $req['id'];

				$field = $class->newInstance( $req, $request['example_values'], $redux_instance );
				ob_start();
				$field->render();

				return array(
					'success' => true,
					'render'  => ob_get_clean(),
				);
			}
		}

		return array( 'success' => false );
	}
}
