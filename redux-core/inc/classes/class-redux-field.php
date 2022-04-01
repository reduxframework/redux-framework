<?php
/**
 * Redux Field Class
 *
 * @class Redux_Field
 * @version 4.0.0
 * @package Redux Framework/Classes
 */

defined( 'ABSPATH' ) || exit;

use Redux_Descriptor_Types as RDT; // TODO Require instead!

if ( ! class_exists( 'Redux_Field', false ) ) {

	/**
	 * Class Redux_Field
	 */
	abstract class Redux_Field {

		/**
		 * Array of descriptors.
		 *
		 * @var Redux_Descriptor[]
		 */
		public static $descriptors = array();

		/**
		 * Make base descriptor.
		 *
		 * @return Redux_Descriptor
		 */
		public static function make_base_descriptor(): Redux_Descriptor {
			$d                                       = new Redux_Descriptor( get_called_class() );
			self::$descriptors[ get_called_class() ] = $d;

			$d->add_field( 'id', __( 'Field ID', 'redux-framework' ), RDT::TEXT )->set_order( 0 )->set_required();
			$d->add_field( 'title', __( 'Title', 'redux-framework' ), RDT::TEXT )->set_order( 1 );
			$d->add_field( 'subtitle', __( 'Subtitle', 'redux-framework' ), RDT::TEXT )->set_order( 2 );
			$d->add_field( 'desc', __( 'Description', 'redux-framework' ), RDT::TEXT )->set_order( 3 );
			$d->add_field( 'class', __( 'Class', 'redux-framework' ), RDT::TEXT )->set_order( 3 );
			$d->add_field( 'compiler', __( 'Compiler', 'redux-framework' ), RDT::BOOL, '', false )->set_order( 60 );
			$d->add_field( 'default', __( 'Default', 'redux-framework' ), RDT::OPTIONS, '', false )->set_order( 60 );
			$d->add_field( 'disabled', __( 'Disabled', 'redux-framework' ), RDT::BOOL, '', false )->set_order( 60 );
			$d->add_field( 'hint', __( 'Hint', 'redux-framework' ), RDT::OPTIONS, '', false )->set_order( 60 );
			$d->add_field( 'hint', __( 'Permissions', 'redux-framework' ), RDT::OPTIONS, '', false )->set_order( 60 );
			$d->add_field( 'required', __( 'Required', 'redux-framework' ), RDT::BOOL, '', false )->set_order( 60 );

			return $d;
		}

		/**
		 * Renders an attribute array into a html attributes string.
		 *
		 * @param array $attributes HTML attributes.
		 *
		 * @return string
		 */
		public static function render_attributes( array $attributes = array() ): string {
			$output = '';

			if ( empty( $attributes ) ) {
				return $output;
			}

			foreach ( $attributes as $key => $value ) {
				if ( false === $value || '' === $value ) {
					continue;
				}

				if ( is_array( $value ) ) {
					$value = wp_json_encode( $value );
				}

				$output .= sprintf( true === $value ? ' %1$s' : ' %1$s="%2$s"', $key, esc_attr( $value ) );
			}

			return $output;
		}

		/**
		 * Get descriptor.
		 *
		 * @return Redux_Descriptor
		 */
		public static function get_descriptor(): Redux_Descriptor {
			if ( ! isset( static::$descriptors[ get_called_class() ] ) ) {
				static::make_descriptor();
			}

			$d = self::$descriptors[ get_called_class() ];

			static::make_descriptor();

			// This part is out of opt name because it's non vendor dependant!
			return apply_filters( 'redux/field/' . $d->get_field_type() . '/get_descriptor', $d ); // phpcs:ignore WordPress.NamingConventions.ValidHookName
		}

		/**
		 * Build the field descriptor in this function.
		 */
		public static function make_descriptor() {
			static::make_base_descriptor();
		}


		/**
		 * CSS styling per field output/compiler.
		 *
		 * @var string
		 */
		public $style = null;

		/**
		 * Class dir.
		 *
		 * @var string
		 */
		public $dir = null;

		/**
		 * Class URL.
		 *
		 * @var string
		 */
		public $url = null;

		/**
		 * Timestamp for ver append in dev_mode
		 *
		 * @var string
		 */
		public $timestamp = null;

		/**
		 * ReduxFramework object pointer.
		 *
		 * @var ReduxFramework
		 */
		public $parent;

		/**
		 * Field values.
		 *
		 * @var string|array
		 */
		public $field;

		/**
		 * Value values.
		 *
		 * @var string|array
		 */
		public $value;

		/**
		 * Select2 options.
		 *
		 * @var array
		 */
		public $select2_config = array();

		/**
		 * Redux_Field constructor.
		 *
		 * @param array|string|null $field  Field array.
		 * @param string|array|null $value  Field values.
		 * @param null              $parent ReduxFramework object pointer.
		 *
		 * @throws ReflectionException Comment.
		 */
		public function __construct( $field = array(), $value = null, $parent = null ) {
			$this->parent = $parent;
			$this->field  = $field;
			$this->value  = $value;

			$this->select2_config = array(
				'width'      => 'resolve',
				'allowClear' => false,
				'theme'      => 'default',
			);

			$this->set_defaults();

			$class_name = get_class( $this );
			$reflector  = new ReflectionClass( $class_name );
			$path       = $reflector->getFilename();
			$path_info  = Redux_Helpers::path_info( $path );
			$this->dir  = trailingslashit( dirname( $path_info['real_path'] ) );
			$this->url  = trailingslashit( dirname( $path_info['url'] ) );

			$this->timestamp = Redux_Core::$version;
			if ( $parent->args['dev_mode'] ) {
				$this->timestamp .= '.' . time();
			}
		}

		/**
		 * Retrieve dirname.
		 *
		 * @return string
		 */
		protected function get_dir(): ?string {
			return $this->dir;
		}

		/**
		 * Media query compiler for Redux Pro,
		 *
		 * @param string $style_data CSS string.
		 */
		public function media_query( string $style_data = '' ) {
			$query_arr = $this->field['media_query'];
			$css       = '';

			if ( isset( $query_arr['queries'] ) ) {
				foreach ( $query_arr['queries'] as $query ) {
					$rule      = $query['rule'] ?? '';
					$selectors = $query['selectors'] ?? array();

					if ( ! is_array( $selectors ) && '' !== $selectors ) {
						$selectors = array( $selectors );
					}

					if ( '' !== $rule && ! empty( $selectors ) ) {
						$selectors = implode( ',', $selectors );

						$css .= '@media ' . $rule . '{';
						$css .= $selectors . '{' . $style_data . '}';
						$css .= '}';
					}
				}
			} else {
				return;
			}

			if ( isset( $query_arr['output'] ) && $query_arr['output'] ) {
				$this->parent->outputCSS .= $css;
			}

			if ( isset( $query_arr['compiler'] ) && $query_arr['compiler'] ) {
				$this->parent->compilerCSS .= $css;
			}
		}

		/**
		 * CSS for field output, if set (Remove the noinpection line and fix this function when we drop support for PHP 7.1).
		 *
		 * @param string $style CSS string.
		 *
		 * @noinspection PhpMissingParamTypeInspection
		 */
		public function output( $style = '' ) {
			if ( '' !== $style ) {

				// Force output value into an array.
				if ( isset( $this->field['output'] ) && ! is_array( $this->field['output'] ) ) {
					$this->field['output'] = array( $this->field['output'] );
				}

				if ( ! empty( $this->field['output'] ) && is_array( $this->field['output'] ) ) {
					if ( isset( $this->field['output']['important'] ) ) {
						if ( $this->field['output']['important'] ) {
							$style = str_replace( ';', ' !important;', $style );
						}
						unset( $this->field['output']['important'] );
					}

					$keys                     = implode( ',', $this->field['output'] );
					$this->parent->outputCSS .= $keys . '{' . $style . '}';
				}

				// Force compiler value into an array.
				if ( isset( $this->field['compiler'] ) && ! is_array( $this->field['compiler'] ) ) {
					$this->field['compiler'] = array( $this->field['compiler'] );
				}

				if ( isset( $this->field['compiler']['important'] ) ) {
					if ( $this->field['compiler']['important'] ) {
						$style = str_replace( ';', ' !important;', $style );
					}
					unset( $this->field['compiler']['important'] );
				}

				if ( ! empty( $this->field['compiler'] ) && is_array( $this->field['compiler'] ) ) {
					$keys                       = implode( ',', $this->field['compiler'] );
					$this->parent->compilerCSS .= $keys . '{' . $style . '}';
				}
			}
		}

		/**
		 * Unused for now.
		 *
		 * @param mixed $data CSS data.
		 */
		public function css_style( $data ) {

		}

		/**
		 * Unused for now.
		 */
		public function set_defaults() {

		}

		/**
		 * Unused for now.
		 */
		public function render() {

		}

		/**
		 * Unused for now.
		 */
		public function enqueue() {

		}

		/**
		 * Unused for now.
		 *
		 * @param array  $field Field array.
		 * @param string $value Value array.
		 */
		public function localize( array $field, string $value = '' ) {

		}
	}
}
