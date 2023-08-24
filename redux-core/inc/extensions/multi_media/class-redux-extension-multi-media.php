<?php
/**
 * Redux Multi Media Extension Class
 *
 * @package Redux
 * @author Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Extension_Multi_Media
 *
 * @version 4.4.1
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Extension_Multi_Media' ) ) {

	/**
	 * Main Redux_Extension_multi_media extension class
	 *
	 * @since       1.0.0
	 */
	class Redux_Extension_Multi_Media extends Redux_Extension_Abstract {

		/**
		 * Extension version.
		 *
		 * @var string
		 */
		public static $version = '4.4.1';

		/**
		 * Extension name.
		 *
		 * @var string
		 */
		public $extension_name = 'Multi Media';

		/**
		 * Class Constructor. Defines the args for the extensions class
		 *
		 * @since       1.0.0
		 * @access      public
		 *
		 * @param       ReduxFramework $redux Parent settings.
		 *
		 * @return      void
		 */
		public function __construct( $redux ) {
			parent::__construct( $redux, __FILE__ );

			$this->add_field( 'multi_media' );
		}

		/**
		 * Get extended image data.
		 *
		 * @param int|string $id image ID.
		 *
		 * @return array
		 * @depreacted Remove camelCase function name.
		 */
		public static function getExtendedData( $id ) {
			_deprecated_function( 'getExtendedData', '4.3.15', 'Redux_Extension_Multi_Media::get_extended_data( $id )' );

			return self::get_extended_data( $id );
		}

		/**
		 * Get extended image data.
		 *
		 * @param int|string $id image ID.
		 *
		 * @return array|void
		 */
		public static function get_extended_data( $id ) {
			if ( '' !== $id && is_numeric( $id ) ) {
				return wp_prepare_attachment_for_js( $id );
			}
		}
	}
}
