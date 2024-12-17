<?php
/**
 * Placeholder file to fix WordPress's fuck up!
 * We removed this extension as it was no longer needed, but WordPress
 * didn't remove the old files with the 4.5.4 update and crashes enough
 * installs to make my life a living hell.
 * Now we have this empty construct so that stopped happening. Fucking stupid!
 *
 * @package Redux
 * @version Inactive Placeholder 4.5.5
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Extension_Search' ) ) {

	/**
	 * Class Redux_Extension_Search
	 */
	class Redux_Extension_Search extends Redux_Extension_Abstract {

		/**
		 * Extension Friendly Name.
		 *
		 * @var string
		 */
		public string $extension_name = 'Search (Inactive Placeholder)';

		/**
		 * Redux_Extension_Search constructor.
		 *
		 * @param object $redux ReduxFramework Object pointer.
		 */
		public function __construct( $redux ) {
			parent::__construct( $redux, __FILE__ );

			// Nothing here.
		}
	}
}
