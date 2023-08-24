<?php
/**
 * Redux Widget Areas Extension Class
 *
 * @package Redux
 * @author  Dovy Paukstys (dovy)
 * @class   Redux_Extension_Widget_Areas
 * @version 4.3.20
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Extension_Widget_Areas' ) ) {

	/**
	 * Class Redux_Extension_Widget_Areas
	 */
	class Redux_Extension_Widget_Areas extends Redux_Extension_Abstract {

		/**
		 * Extension version.
		 *
		 * @var string
		 */
		public static $version = '4.3.20';

		/**
		 * Extension Friendly name.
		 *
		 * @var string
		 */
		public $extension_name = 'Widget Areas';


		/**
		 * Redux_Extension_Widget_Areas constructor.
		 *
		 * @param object $redux ReduxFramework object pointer.
		 */
		public function __construct( $redux ) {
			parent::__construct( $redux, __FILE__ );

			$this->add_field( 'widget_areas' );

			require_once __DIR__ . '/class-redux-widget-areas.php';
			$widget_areas = new Redux_Widget_Areas( $this->parent );

			// Allow users to extend if they want.
			// Phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( 'redux/widget_areas/' . $redux->args['opt_name'] . '/construct' );

			add_action( 'wp_ajax_redux_delete_widget_area', array( $widget_areas, 'redux_delete_widget_area_area' ) );
		}
	}
}

class_alias( 'Redux_Extension_Widget_Areas', 'ReduxFramework_extension_widget_areas' );
