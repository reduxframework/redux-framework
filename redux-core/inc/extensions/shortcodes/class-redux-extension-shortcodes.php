<?php
/**
 * Redux Shortcodes Extension Class
 *
 * @package Redux
 * @author  Dovy Paukstys (dovy) & Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Extension_Shortcodes
 * @version 4.3.6
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Extension_Shortcodes' ) ) {

	/**
	 * Class Redux_Extension_Shortcodes
	 */
	class Redux_Extension_Shortcodes extends Redux_Extension_Abstract {

		/**
		 * Extension Version.
		 *
		 * @var string
		 */
		public static $version = '4.3.6';

		/**
		 * Extension Friendly Name.
		 *
		 * @var string
		 */
		public $extension_name = 'Shortcodes';

		/**
		 * Redux_Extension_Shortcodes constructor.
		 *
		 * @param object $redux ReduxFramework Object pointer.
		 */
		public function __construct( $redux ) {
			parent::__construct( $redux, __FILE__ );

			$this->add_field( 'shortcodes' );

			if ( ! class_exists( 'Redux_Shortcodes' ) ) {
				require_once __DIR__ . '/class-redux-shortcodes.php';
				new Redux_Shortcodes();
			}

			// Allow users to extend if they want.
			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( 'redux/shortcodes/' . $redux->args['opt_name'] . '/construct' );
		}
	}
}
