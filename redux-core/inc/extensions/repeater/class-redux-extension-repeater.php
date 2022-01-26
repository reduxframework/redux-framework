<?php
/**
 * Redux Repeater Extension Class
 *
 * @package Redux
 * @author  Dovy Paukstys & Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Extension_Repeater
 *
 * @version 4.3.7
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Extension_Repeater' ) ) {


	/**
	 * Class Redux_Extension_Repeater
	 */
	class Redux_Extension_Repeater extends Redux_Extension_Abstract {

		/**
		 * Extension version.
		 *
		 * @var string
		 */
		public static $version = '4.3.9';

		/**
		 * Extension friendly name.
		 *
		 * @var string
		 */
		public $extension_name = 'Repeater';

		/**
		 * Class Constructor. Defines the args for the extensions class
		 *
		 * @since       1.0.0
		 * @access      public
		 *
		 * @param       object $parent Parent settings.
		 *
		 * @return      void
		 */
		public function __construct( $parent ) {
			parent::__construct( $parent, __FILE__ );

			$this->add_field( 'repeater' );
		}
	}
}
