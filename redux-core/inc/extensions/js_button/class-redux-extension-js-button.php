<?php
/**
 * Redux JS Button Extension Class
 *
 * @package Redux
 * @author  Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Extension_Js_Button
 * @version 4.3.16
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Extension_Js_Button' ) ) {

	/**
	 * Main Redux_Extension_Js_Button extension class
	 *
	 * @since       1.0.0
	 */
	class Redux_Extension_Js_Button extends Redux_Extension_Abstract {

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
		public $extension_name = 'JS Button';

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

			$this->add_field( 'js_button' );
		}
	}
}
