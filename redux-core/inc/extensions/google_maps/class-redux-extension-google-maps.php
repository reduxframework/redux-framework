<?php
/**
 * Redux Google Maps Extension Class
 *
 * @package Redux
 * @author  Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Extension_Google_Maps
 * @version 4.4.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Extension_Google_Maps' ) ) {

	/**
	 * Class ReduxFramework_extension_google_maps
	 */
	class Redux_Extension_Google_Maps extends Redux_Extension_Abstract {

		/**
		 * Extension version.
		 *
		 * @var string
		 */
		public static $version = '4.4.0';

		/**
		 * Extension friendly name.
		 *
		 * @var string
		 */
		public $extension_name = 'Google Maps';

		/**
		 * ReduxFramework_extension_google_maps constructor.
		 *
		 * @param ReduxFramework $redux ReduxFramework object.
		 */
		public function __construct( $redux ) {
			parent::__construct( $redux, __FILE__ );

			$this->add_field( 'google_maps' );
		}
	}
}
