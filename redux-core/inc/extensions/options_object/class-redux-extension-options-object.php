<?php
/**
 * Redux Options Object Extension Class
 *
 * @class Redux_Core
 * @version 4.0.0
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Extension_Options_Object', false ) ) {


	/**
	 * Main ReduxFramework options_object extension class
	 *
	 * @since       3.1.6
	 */
	class Redux_Extension_Options_Object extends Redux_Extension_Abstract {

		/**
		 * Ext version.
		 *
		 * @var string
		 */
		public static $version = '4.0.0';

		/**
		 * Set the name of the field.  Ideally, this will also be your extension's name.
		 * Please use underscores and NOT dashes.
		 *
		 * @var string
		 */
		private $field_name = 'options_object';

		/**
		 * Is field bit.
		 *
		 * @var bool
		 */
		public $is_field = false;

		/**
		 * Class Constructor. Defines the args for the extensions class
		 *
		 * @since       1.0.0
		 * @access      public
		 *
		 * @param       object $redux Redux object.
		 *
		 * @return      void
		 */
		public function __construct( $redux ) {
			parent::__construct( $redux, __FILE__ );

			$this->add_field( $this->field_name );
			$this->is_field = Redux_Helpers::is_field_in_use( $redux, $this->field_name );

			if ( ! $this->is_field && $this->parent->args['dev_mode'] && $this->parent->args['show_options_object'] ) {
				$this->add_section();
			}
		}

		/**
		 * Add section to panel.
		 */
		public function add_section() {
			$this->parent->sections[] = array(
				'id'         => 'options-object',
				'title'      => esc_html__( 'Options Object', 'redux-framework' ),
				'heading'    => '',
				'icon'       => 'el el-info-circle',
				'customizer' => false,
				'fields'     => array(
					array(
						'id'    => 'redux_options_object',
						'type'  => 'options_object',
						'title' => '',
					),
				),
			);
		}
	}
}

if ( ! class_exists( 'ReduxFramework_Extension_options_object' ) ) {
	class_alias( 'Redux_Extension_Options_Object', 'ReduxFramework_Extension_options_object' );
}
