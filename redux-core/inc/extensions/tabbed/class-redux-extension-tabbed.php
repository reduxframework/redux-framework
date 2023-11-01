<?php
/**
 * Redux Tabbed Extension Class
 *
 * @package Redux\Extensions\Tabbed
 * @author  Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Extension_Tabbed
 *
 * @version 4.4.8
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Extension_Tabbed' ) ) {


	/**
	 * Class Redux_Extension_Tabbed
	 */
	class Redux_Extension_Tabbed extends Redux_Extension_Abstract {

		/**
		 * Extension version.
		 *
		 * @var string
		 */
		public static $version = '4.4.8';

		/**
		 * Extension friendly name.
		 *
		 * @var string
		 */
		public $extension_name = 'Tabbed';

		/**
		 * Class Constructor. Defines the args for the extensions class
		 *
		 * @since       1.0.0
		 * @access      public
		 *
		 * @param       object $redux Parent settings.
		 *
		 * @return      void
		 */
		public function __construct( $redux ) {
			parent::__construct( $redux, __FILE__ );

			$this->add_field( 'tabbed' );
		}
	}
}
