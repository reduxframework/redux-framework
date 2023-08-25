<?php
/**
 * Redux Field Class
 *
 * @class Redux_Field
 * @version 4.0.0
 * @package Redux Framework/Classes
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Field', false ) ) {

	/**
	 * Class Redux_Field
	 */
	abstract class Redux_Field {

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
		 * @param null              $redux ReduxFramework object pointer.
		 *
		 * @throws ReflectionException Comment.
		 */
		public function __construct( $field = array(), $value = null, $redux = null ) {
			$this->parent = $redux;
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
			if ( $redux->args['dev_mode'] ) {
				$this->timestamp .= '.' . time();
			}
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
		public function css_style( $data ) {}

		/**
		 * Unused for now.
		 */
		public function set_defaults() {}

		/**
		 * Unused for now.
		 */
		public function render() {}

		/**
		 * Unused for now.
		 */
		public function enqueue() {}

		/**
		 * Unused for now.
		 */
		public function always_enqueue() {}

		/**
		 * Unused for now.
		 *
		 * @param array  $field Field array.
		 * @param string $value Value array.
		 */
		public function localize( array $field, string $value = '' ) {}
	}
}
