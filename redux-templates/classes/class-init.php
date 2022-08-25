<?php
/**
 * Initialize the Redux Template Library.
 *
 * @since 4.0.0
 * @package Redux Framework
 */

namespace ReduxTemplates;

use ReduxTemplates;

defined( 'ABSPATH' ) || exit;

/**
 * Redux Templates Init Class
 *
 * @since 4.0.0
 */
class Init {

	/**
	 * Init constructor.
	 *
	 * @access public
	 */
	public function __construct() {
		global $pagenow;

		if ( 'widgets.php' === $pagenow ) {
			return;
		}

		add_action( 'init', array( $this, 'load' ) );

		if ( did_action( 'init' ) ) { // In case the devs load it at the wrong place.
			$this->load();
		}
	}

	/**
	 * Load everything up after init.
	 *
	 * @access public
	 * @since 4.0.0
	 */
	public static function load() {
		new ReduxTemplates\Templates();
	}
}

new Init();
