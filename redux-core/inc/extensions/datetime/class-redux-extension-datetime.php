<?php
/**
 * Redux Date/Time Extension Class
 *
 * @package Redux Pro
 * @author  Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Extension_Datetime
 *
 * @version 2.0.0
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
		public static $version = '2.0.0';

		/**
		 * Extension friendly name.
		 *
		 * @var string
		 */
		public $extension_name = 'Date/Time';

		/**
		 * Redux_Extension_Datetime constructor.
		 *
		 * @param object $parent ReduxFramework pointer.
		 */
		public function __construct( $parent ) {
			parent::__construct( $parent, __FILE__ );

			$this->add_field( 'datetime' );
		}
	}
}
