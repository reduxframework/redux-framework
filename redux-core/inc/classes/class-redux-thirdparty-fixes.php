<?php
/**
 * Redux ThirdParty Fixes Class
 *
 * @class Redux_ThirdParty_Fixes
 * @version 3.0.0
 * @package Redux Framework/Classes
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_ThirdParty_Fixes', false ) ) {

	/**
	 * Class Redux_ThirdParty_Fixes
	 */
	class Redux_ThirdParty_Fixes extends Redux_Class {

		/**
		 * Redux_ThirdParty_Fixes constructor.
		 *
		 * @param object $parent ReduxFramework pointer.
		 */
		public function __construct( $parent ) {
			parent::__construct( $parent );

			$this->gt3_page_builder();
		}

		/**
		 * GT3 Page Builder fix.
		 */
		private function gt3_page_builder() {
			// Fix for the GT3 page builder: http://www.gt3themes.com/wordpress-gt3-page-builder-plugin/.
			if ( has_action( 'ecpt_field_options_' ) ) {
				global $pagenow;

				if ( 'admin.php' === $pagenow ) {
					remove_action( 'admin_init', 'pb_admin_init' );
				}
			}
		}
	}
}
