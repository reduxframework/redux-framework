<?php
/**
 * Redux Icon Select Extension Class
 *
 * @package Redux Extentions
 * @author  Dovy Paukstys <dovy@reduxframework.com> & Kevin Provance <kevin.provance@gmail.com>
 * @class   Redux_Extension_Icon_Select
 * @version 4.4.2
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Extension_Icon_Select' ) ) {


	/**
	 * Main ReduxFramework icon_select extension class
	 *
	 * @since       3.1.6
	 */
	class Redux_Extension_Icon_Select extends Redux_Extension_Abstract {

		/**
		 * Extension version.
		 *
		 * @var string
		 */
		public static $version = '4.4.2';

		/**
		 * Extension friendly name.
		 *
		 * @var string
		 */
		public $ext_name = 'Icon Select';

		/**
		 * ReduxFramework_Extension_Icon_Select constructor.
		 *
		 * @param ReduxFramework $redux ReduxFramework object.
		 */
		public function __construct( $redux ) {
			parent::__construct( $redux, __FILE__ );

			$this->add_field( 'icon_select' );

			add_action( 'wp_ajax_redux_get_icons', array( $this, 'get_icons' ) );
		}

		/**
		 * Add icons to modal.
		 *
		 * @return void
		 */
		public function get_icons() {
			$nonce       = ( ! empty( $_POST['nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
			$icon_set    = ( ! empty( $_POST['icon_set'] ) ) ? sanitize_text_field( wp_unslash( $_POST['icon_set'] ) ) : '';
			$select_text = ( ! empty( $_POST['select_text'] ) ) ? sanitize_text_field( wp_unslash( $_POST['select_text'] ) ) : '';

			if ( ! wp_verify_nonce( $nonce, 'redux_icon_nonce' ) ) {
				wp_send_json_error( array( 'error' => esc_html__( 'Error: Invalid nonce verification.', 'redux-framework' ) ) );
			}

			ob_start();

			$class = '';

			if ( 'font-awesome' === $icon_set ) {
				require_once Redux_Core::$dir . 'inc/lib/font-awesome-6-free.php';

				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				$icon_lists = apply_filters( 'redux/extensions/icon_select/fontawesome/icons', redux_icon_select_fa_6_free() );
			} elseif ( 'dashicons' === $icon_set ) {
				require_once Redux_Core::$dir . 'inc/lib/dashicons.php';

				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				$icon_lists = apply_filters( 'redux/extensions/icon_select/dashicons/icons', redux_get_dashicons() );
			} elseif ( 'elusive' === $icon_set ) {
				require_once Redux_Core::$dir . 'inc/lib/elusive-icons.php';

				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				$icon_lists = apply_filters( 'redux/extensions/icon_select/elusive/icons', redux_get_font_icons() );
			} else {
				$data  = ( ! empty( $_POST['data'] ) ) ? ( wp_unslash( $_POST['data'] ) ) : ''; // phpcs:ignore WordPress.Security
				$data  = json_decode( rawurldecode( $data ), true );
				$icons = '';

				foreach ( $data as $arr_data ) {
					if ( $arr_data['title'] === $select_text ) {
						$icons = $arr_data['icons'];

						$class = ( ! empty( $arr_data['class'] ) ? $arr_data['class'] . ' ' : '' );
					}
				}

				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				$icon_lists = apply_filters( 'redux/extensions/icon_select/custom/icons', $icons );
			}

			if ( ! empty( $icon_lists ) ) {
				foreach ( $icon_lists as $list ) {

					/**
					 * Icon output.
					 * Custom output for icon libraries that support different standards.
					 *
					 * @param string $default Original output.
					 * @param string $title   Title of the icon.
					 * @param string $class   Class of the icon.
					 * @param string $icon_Set Selected Icon set.
					 *
					 * @since 4.4.2
					 */
					echo apply_filters( 'redux/extension/icon_select/' . $this->parent->args['opt_name'] . '/output', '<i title="' . esc_attr( $list ) . '" class="' . esc_attr( $class . ' ' . $list ) . '" /></i>', $list, $class, $select_text ); // phpcs:ignore WordPress.NamingConventions.ValidHookName, WordPress.Security.EscapeOutput
				}
			} else {
				echo '<div class="redux-error-text">' . esc_html__( 'No data available.', 'redux-framework' ) . '</div>';
			}

			$content = ob_get_clean();

			wp_send_json_success( array( 'content' => $content ) );
		}
	}
}

class_alias( 'Redux_Extension_Icon_Select', 'ReduxFramework_extension_icon_select' );
