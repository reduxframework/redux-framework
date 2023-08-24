<?php
/**
 * Redux Date/Time Extension Class
 *
 * @package Redux Pro
 * @author  Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Extension_Datetime
 *
 * @version 4.3.15
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Extension_Datetime', false ) ) {

	/**
	 * Class Redux_Extension_Datetime
	 */
	class Redux_Extension_Datetime extends Redux_Extension_Abstract {

		/**
		 * Extension version.
		 *
		 * @var string
		 */
		public static $version = '4.3.15';

		/**
		 * Extension friendly name.
		 *
		 * @var string
		 */
		public $extension_name = 'Date/Time';

		/**
		 * Redux_Extension_Datetime constructor.
		 *
		 * @param object $redux ReduxFramework pointer.
		 */
		public function __construct( $redux ) {
			parent::__construct( $redux, __FILE__ );

			$this->add_field( 'datetime' );
		}
	}
}
