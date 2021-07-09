<?php
/**
 * Raw Field.
 *
 * @package     ReduxFramework/Fields
 * @author      Dovy Paukstys & Kevin Provance (kprovance)
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Raw', false ) ) {

	/**
	 * Class Redux_Raw
	 */
	class Redux_Raw extends Redux_Field {

		/**
		 * Set field defaults.
		 */
		public function set_defaults() {
			$defaults = array(
				'full_width' => true,
				'markdown'   => false,
			);

			$this->field = wp_parse_args( $this->field, $defaults );
		}

		/**
		 * Field Render Function.
		 * Takes the vars and outputs the HTML for the field in the settings
		 *
		 * @since ReduxFramework 1.0.0
		 */
		public function render() {
			if ( ! empty( $this->field['include'] ) && file_exists( $this->field['include'] ) ) {
				require_once $this->field['include'];
			}

			if ( isset( $this->field['content_path'] ) && ! empty( $this->field['content_path'] ) && file_exists( $this->field['content_path'] ) ) {
				$this->field['content'] = $this->parent->filesystem->execute( 'get_contents', $this->field['content_path'] );
			}

			if ( ! empty( $this->field['content'] ) && isset( $this->field['content'] ) ) {
				if ( isset( $this->field['markdown'] ) && true === $this->field['markdown'] && ! empty( $this->field['content'] ) ) {
					require_once dirname( __FILE__ ) . '/parsedown.php';
					$parsedown = new Redux_Parsedown();

					echo( $parsedown->text( $this->field['content'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput
				} else {
					echo( $this->field['content'] ); // phpcs:ignore WordPress.Security.EscapeOutput
				}
			}

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( 'redux-field-raw-' . $this->parent->args['opt_name'] . '-' . $this->field['id'] );
		}
	}
}

class_alias( 'Redux_Raw', 'ReduxFramework_Raw' );
