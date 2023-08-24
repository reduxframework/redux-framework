<?php
/**
 * Redux Search Extension Class
 *
 * @package Redux
 * @author  Dovy Paukstys (dovy)
 * @class   Redux_Extension_Search
 * @version 3.4.5
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Extension_Search' ) ) {

	/**
	 * Class Redux_Extension_Search
	 */
	class Redux_Extension_Search extends Redux_Extension_Abstract {

		/**
		 * Extension version.
		 *
		 * @var string
		 */
		public static $version = '3.4.5';

		/**
		 * Extension friendly name.
		 *
		 * @var string
		 */
		public $extension_name = 'Search';

		/**
		 * Redux_Extension_Search constructor.
		 *
		 * @param object $redux ReduxFramework object pointer.
		 */
		public function __construct( $redux ) {
			if ( false === $redux->args['search'] ) {
				return;
			}

			parent::__construct( $redux, __FILE__ );

			$this->add_field( 'search' );

			// Allow users to extend if they want.
			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( 'redux/search/' . $redux->args['opt_name'] . '/construct' );

			if ( isset( $_GET['page'] ) && sanitize_text_field( wp_unslash( $_GET['page'] === $this->parent->args['page_slug'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 0 );
			}

			add_action( "redux/metaboxes/{$this->parent->args[ 'opt_name' ]}/enqueue", array( $this, 'enqueue' ), 10 );
		}

		/**
		 * Support file enqueue.
		 */
		public function enqueue() {
			$min = Redux_Functions::is_min();

			/**
			 * Redux search CSS
			 * filter 'redux/page/{opt_name}/enqueue/redux-extension-search-css'
			 */
			if ( $this->parent->args['dev_mode'] ) {
				wp_enqueue_style(
					'redux-extension-search',
					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					apply_filters( "redux/search/{$this->parent->args[ 'opt_name' ]}/enqueue/redux-extension-search-css", $this->extension_url . 'redux-extension-search.css' ),
					array(),
					self::$version
				);
			}

			/**
			 * Redux search JS
			 * filter 'redux/page/{opt_name}/enqueue/redux-extension-search-js
			 */
			wp_enqueue_script(
				'redux-extension-search',
				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				apply_filters( "redux/search/{$this->parent->args[ 'opt_name' ]}/enqueue/redux-extension-search-js", $this->extension_url . 'redux-extension-search' . $min . '.js' ),
				'',
				self::$version,
				true
			);

			// Values used by the javascript.
			wp_localize_script(
				'redux-extension-search',
				'reduxSearch',
				array(
					'search' => esc_html__( 'Search for field(s)', 'redux-framework' ),
				)
			);
		}
	}
}
