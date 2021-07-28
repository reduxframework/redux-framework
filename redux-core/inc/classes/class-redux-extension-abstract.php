<?php
/**
 * Redux Extension Abstract
 *
 * @class   Redux_Extension_Abstract
 * @version 4.0.0
 * @package Redux Framework/Classes
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class Redux_Extension_Abstract
 * An abstract class to make the writing of redux extensions easier by allowing users to extend this class
 *
 * @see the samples directory to find a usage example
 */
abstract class Redux_Extension_Abstract {
	/**
	 * The version of the extension (This is a default value you may want to override it)
	 *
	 * @var string
	 */
	public static $version = '1.0.0';

	/**
	 * The extension URL.
	 *
	 * @var string
	 */
	protected $extension_url;

	/**
	 * The extension dir.
	 *
	 * @var string
	 */
	protected $extension_dir;

	/**
	 * The instance of the extension
	 *
	 * @var static
	 */
	protected static $instance;

	/**
	 * The extension's file
	 *
	 * @var string
	 */
	protected $file;

	/**
	 * The redux framework instance that spawned the extension.
	 *
	 * @var ReduxFramework
	 */
	public $parent;

	/**
	 * The ReflectionClass of the extension
	 *
	 * @var ReflectionClass
	 */
	protected $reflection_class;

	/**
	 * Redux_Extension_Abstract constructor.
	 *
	 * @param object $parent ReduxFramework pointer.
	 * @param string $file   Extension file.
	 */
	public function __construct( $parent, string $file = '' ) {
		$this->parent = $parent;

		// If the file is not given make sure we have one.
		if ( empty( $file ) ) {
			$file = $this->get_reflection()->getFileName();
		}

		$this->file = $file;

		$this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( $file ) ) );

		$plugin_info = Redux_Functions_Ex::is_inside_plugin( $this->file );

		if ( false !== $plugin_info ) {
			$this->extension_url = trailingslashit( dirname( $plugin_info['url'] ) );
		} else {
			$theme_info = Redux_Functions_Ex::is_inside_theme( $this->file );
			if ( false !== $theme_info ) {
				$this->extension_url = trailingslashit( dirname( $theme_info['url'] ) );
			}
		}

		static::$instance = $this;
	}

	/**
	 * Get the reflection class of the extension.
	 *
	 * @return ReflectionClass
	 */
	protected function get_reflection(): ReflectionClass {
		if ( ! isset( $this->reflection_class ) ) {
			try {
				$this->reflection_class = new ReflectionClass( $this );
			} catch ( ReflectionException $e ) { // phpcs:ignore
				error_log( $e->getMessage() ); // phpcs:ignore
			}
		}

		return $this->reflection_class;
	}

	/**
	 * Return extension version.
	 *
	 * @return string
	 */
	public static function get_version(): string {
		return static::$version;
	}

	/**
	 * Returns extension instance.
	 *
	 * @return Redux_Extension_Abstract
	 */
	public static function get_instance(): Redux_Extension_Abstract {
		return static::$instance;
	}

	/**
	 * Return extension dir.
	 *
	 * @return string
	 */
	public function get_dir(): string {
		return $this->extension_dir;
	}

	/**
	 * Returns extension URL
	 *
	 * @return string
	 */
	public function get_url(): string {
		return $this->extension_url;
	}

	/**
	 * Adds the local field. (The use of add_field is recommended).
	 *
	 * @param string $field_name Name of field.
	 */
	protected function add_overload_field_filter( string $field_name ) {
		// phpcs:ignore WordPress.NamingConventions.ValidHookName
		add_filter(
			'redux/' . $this->parent->args['opt_name'] . '/field/class/' . $field_name,
			array(
				&$this,
				'overload_field_path',
			),
			10,
			2
		);
	}

	/**
	 * Adds the local field to the extension and register it in the builder.
	 *
	 * @param string $field_name Name of field.
	 */
	protected function add_field( string $field_name ) {
		$class = $this->get_reflection()->getName();

		// phpcs:ignore WordPress.NamingConventions.ValidHookName
		add_filter(
			'redux/fields',
			function ( $classes ) use ( $field_name, $class ) {
				$classes[ $field_name ] = $class;
				return $classes;
			}
		);

		$this->add_overload_field_filter( $field_name );
	}

	/**
	 * Overload field path.
	 *
	 * @param string $file  Extension file.
	 * @param array  $field Field array.
	 *
	 * @return string
	 */
	public function overload_field_path( string $file, array $field ): string {
		$filename_fix = str_replace( '_', '-', $field['type'] );

		$files = array(
			trailingslashit( dirname( $this->file ) ) . $field['type'] . DIRECTORY_SEPARATOR . 'field_' . $field['type'] . '.php',
			trailingslashit( dirname( $this->file ) ) . $field['type'] . DIRECTORY_SEPARATOR . 'class-redux-' . $filename_fix . '.php',
		);

		return Redux_Functions::file_exists_ex( $files );
	}

	/**
	 * Sets the minimum version of Redux to use.  Displays a notice if requirments not met.
	 *
	 * @param string $min_version       Minimum version to evaluate.
	 * @param string $extension_version Extension version number.
	 * @param string $friendly_name     Friend extension name for notice display.
	 *
	 * @return bool
	 */
	public function is_minimum_version( string $min_version = '', string $extension_version = '', string $friendly_name = '' ): bool {
		$redux_ver = Redux_Core::$version;

		if ( '' !== $min_version ) {
			if ( version_compare( $redux_ver, $min_version ) < 0 ) {
				// translators: %1$s Extension friendly name. %2$s: minimum Redux version.
				$msg = '<strong>' . sprintf( esc_html__( 'The %1$s extension requires Redux Framework version %2$s or higher.', 'redux-framework' ), $friendly_name, $min_version ) . '</strong>&nbsp;&nbsp;' . esc_html__( 'You are currently running Redux Framework version ', 'redux-framework' ) . ' ' . $redux_ver . '.<br/><br/>' . esc_html__( 'This field will not render in your option panel, and featuress of this extension will not be available until the latest version of Redux Framework has been installed.', 'redux-framework' );

				$data = array(
					'parent'  => $this->parent,
					'type'    => 'error',
					'msg'     => $msg,
					'id'      => $this->ext_name . '_notice_' . $extension_version,
					'dismiss' => false,
				);

				if ( method_exists( 'Redux_Admin_Notices', 'set_notice' ) ) {
					Redux_Admin_Notices::set_notice( $data );
				} else {
					echo '<div class="error">';
					echo '<p>';
					echo $msg; // phpcs:ignore WordPress.Security.EscapeOutput
					echo '</p>';
					echo '</div>';
				}

				return false;
			}
		}

		return true;
	}
}

if ( ! class_exists( 'Redux_Abstract_Extension' ) ) {
	class_alias( 'Redux_Extension_Abstract', 'Redux_Abstract_Extension' );
}
