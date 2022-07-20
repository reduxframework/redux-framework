<?php
/**
 * Redux JS Button Extension Class
 *
 * @package Redux Pro
 * @author  Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Extension_Js_Button
 * @version 2.0.0
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
		 * @param       ReduxFramework $parent Parent settings.
		 *
		 * @return      void
		 */
		public function __construct( $parent ) {
			parent::__construct( $parent, __FILE__ );

			$this->add_field( 'js_button' );
		}
	}
}
