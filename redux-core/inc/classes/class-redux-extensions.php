<?php
/**
 * Register Extensions for use
 *
 * @package Redux Framework/Classes
 * @since       3.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Extensions', false ) ) {

	/**
	 * Class Redux_Extensions
	 */
	class Redux_Extensions extends Redux_Class {

		/**
		 * Redux_Extensions constructor.
		 *
		 * @param object $parent ReduxFramework object pointer.
		 */
		public function __construct( $parent ) {
			parent::__construct( $parent );

			$this->load();
		}

		/**
		 * Class load functions.
		 *
		 * @throws ReflectionException For fallback.
		 */
		private function load() {
			$core = $this->core();

			$max = 1;

			if ( Redux_Core::$pro_loaded ) {
				$max = 2;
			}

			for ( $i = 1; $i <= $max; $i ++ ) {
				$path = Redux_Core::$dir . 'inc/extensions/';

				if ( 2 === $i ) {
					if ( class_exists( 'Redux_Pro' ) ) {
						$path = Redux_Pro::$dir . 'core/inc/extensions/';
					}
				}

				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				$path = apply_filters( 'redux/' . $core->args['opt_name'] . '/extensions/dir', $path );

				/**
				 * Action 'redux/extensions/before'
				 *
				 * @param object $this ReduxFramework
				 */
				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				do_action( 'redux/extensions/before', $core );

				/**
				 * Action 'redux/extensions/{opt_name}/before'
				 *
				 * @param object $this ReduxFramework
				 */
				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				do_action( "redux/extensions/{$core->args['opt_name']}/before", $core );

				if ( isset( $core->old_opt_name ) && null !== $core->old_opt_name ) {
					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					do_action( 'redux/extensions/' . $core->old_opt_name . '/before', $core );
				}

				require_once Redux_Core::$dir . 'inc/classes/class-redux-extension-abstract.php';

				$path = untrailingslashit( $path );

				// Backwards compatibility for extensions.
				$instance_extensions = Redux::get_extensions( $core->args['opt_name'] );
				if ( ! empty( $instance_extensions ) ) {
					foreach ( $instance_extensions as $name => $extension ) {
						if ( ! isset( $core->extensions[ $name ] ) ) {
							if ( class_exists( 'ReduxFramework_Extension_' . $name ) ) {
								$a = new ReflectionClass( 'ReduxFramework_Extension_' . $name );
								Redux::set_extensions( $core->args['opt_name'], dirname( $a->getFileName() ), true );
							}
						}
						if ( ! isset( $core->extensions[ $name ] ) ) {
							/* translators: %s is the name of an extension */
							$msg  = '<strong>' . sprintf( esc_html__( 'The `%s` extension was not located properly', 'redux-framework' ), $name ) . '</strong>';
							$data = array(
								'parent'  => $this->parent,
								'type'    => 'error',
								'msg'     => $msg,
								'id'      => $name . '_notice_',
								'dismiss' => false,
							);
							if ( method_exists( 'Redux_Admin_Notices', 'set_notice' ) ) {
								Redux_Admin_Notices::set_notice( $data );
							}
							continue;
						}
						if ( ! is_subclass_of( $core->extensions[ $name ], 'Redux_Extension_Abstract' ) ) {
							$ext_class                      = get_class( $core->extensions[ $name ] );
							$new_class_name                 = $ext_class . '_extended';
							Redux::$extension_compatibility = true;
							$core->extensions[ $name ]      = Redux_Functions_Ex::extension_compatibility( $core, $extension['path'], $ext_class, $new_class_name, $name );
						}
					}
				}

				Redux::set_extensions( $core->args['opt_name'], $path, true );

				/**
				 * Action 'redux/extensions/{opt_name}'
				 *
				 * @param object $this ReduxFramework
				 */
				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				do_action( "redux/extensions/{$core->args['opt_name']}", $core );

				if ( isset( $core->old_opt_name ) && null !== $core->old_opt_name ) {
					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					do_action( 'redux/extensions/' . $core->old_opt_name, $core );
				}
			}
		}
	}
}
