<?php
/**
 * Redux Shortcodes Extension Class
 *
 * @package Redux
 * @author  Dovy Paukstys (dovy) & Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Extension_Shortcodes
 * @version 4.3.5
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
		 * @param object $parent ReduxFramework Object pointer.
		 */
		public function __construct( $parent ) {
			parent::__construct( $parent, __FILE__ );

			$this->add_field( 'shortcodes' );

			if ( ! class_exists( 'Redux_Shortcodes' ) ) {
				require_once dirname( __FILE__ ) . '/class-redux-shortcodes.php';
				new Redux_Shortcodes();
			}

			// Allow users to extend if they want.
			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( 'redux/shortcodes/' . $parent->args['opt_name'] . '/construct' );
		}
	}
}
