<?php
/**
 * Redux Accordion Extension Class
 *
 * @package Redux
 * @author Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Extension_Accordion
 * @version 4.3.16
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Extension_Accordion' ) ) {

	/**
	 * Main ReduxFramework_Extension_Accordion extension class
	 *
	 * @since       1.0.0
	 */
	class Redux_Extension_Accordion extends Redux_Extension_Abstract {

		/**
		 * Extension version.
		 *
		 * @var string
		 */
		public static $version = '4.3.16';

		/**
		 * Extension friendly name.
		 *
		 * @var string
		 */
		public $extension_name = 'Accordion';

		/**
		 * Class Constructor. Defines the args for the extension class
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

			$this->add_field( 'accordion' );
		}
	}
}
