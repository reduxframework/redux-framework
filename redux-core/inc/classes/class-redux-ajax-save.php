<?php
/**
 * Redux AJAX Save Class
 *
 * @class Redux_Core
 * @version 4.0.0
 * @package Redux Framework/Classes
 * @noinspection PhpConditionCheckedByNextConditionInspection
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_AJAX_Save', false ) ) {

	/**
	 * Class Redux_AJAX_Save
	 */
	class Redux_AJAX_Save extends Redux_Class {

		/**
		 * Redux_AJAX_Save constructor.
		 * array_merge_recursive_distinct
		 *
		 * @param object $redux ReduxFramework object.
		 */
		public function __construct( $redux ) {
			parent::__construct( $redux );

			add_action( 'wp_ajax_' . $this->args['opt_name'] . '_ajax_save', array( $this, 'save' ) );
		}

		/**
		 * AJAX callback to save the option panel values.
		 *
		 * @throws ReflectionException Exception.
		 */
		public function save() {
			$redux = null;

			$core = $this->core();

			if ( ! isset( $_REQUEST['nonce'] ) || ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_REQUEST['nonce'] ) ), 'redux_ajax_nonce' . $this->args['opt_name'] ) ) ) {
				echo wp_json_encode(
					array(
						'status' => esc_html__( 'Invalid security credential.  Please reload the page and try again.', 'redux-framework' ),
						'action' => '',
					)
				);
				die();
			}

			if ( ! Redux_Helpers::current_user_can( $core->args['page_permissions'] ) ) {
				echo wp_json_encode(
					array(
						'status' => esc_html__( 'Invalid user capability.  Please reload the page and try again.', 'redux-framework' ),
						'action' => '',
					)
				);
				die();
			}

			if ( isset( $_POST['opt_name'] ) && ! empty( $_POST['opt_name'] ) && isset( $_POST['data'] ) && ! empty( $_POST['data'] ) ) {
				$redux = Redux::instance( sanitize_text_field( wp_unslash( $_POST['opt_name'] ) ) );

				if ( ! empty( $redux->args['opt_name'] ) ) {

					$post_data = wp_unslash( $_POST['data'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

					// New method to avoid input_var nonsense.  Thanks, @harunbasic.
					$values = Redux_Functions_Ex::parse_str( $post_data );
					$values = $values[ $redux->args['opt_name'] ];

					if ( ! empty( $values ) ) {
						try {
							if ( isset( $redux->validation_ran ) ) {
								unset( $redux->validation_ran );
							}

							$redux->options_class->set( $redux->options_class->validate_options( $values ) );

							$do_reload = false;
							if ( isset( $core->required_class->reload_fields ) && ! empty( $core->required_class->reload_fields ) ) {
								if ( ! empty( $core->transients['changed_values'] ) ) {
									foreach ( $core->required_class->reload_fields as $val ) {
										if ( array_key_exists( $val, $core->transients['changed_values'] ) ) {
											$do_reload = true;
										}
									}
								}
							}

							if ( $do_reload || ( isset( $values['defaults'] ) && ! empty( $values['defaults'] ) ) || ( isset( $values['defaults-section'] ) && ! empty( $values['defaults-section'] ) ) || ( isset( $values['import_code'] ) && ! empty( $values['import_code'] ) ) || ( isset( $values['import_link'] ) && ! empty( $values['import_link'] ) ) ) {
								echo wp_json_encode(
									array(
										'status' => 'success',
										'action' => 'reload',
									)
								);
								die();
							}

							$redux->enqueue_class->get_warnings_and_errors_array();

							$return_array = array(
								'status'   => 'success',
								'options'  => $redux->options,
								'errors'   => $redux->enqueue_class->localize_data['errors'] ?? null,
								'warnings' => $redux->enqueue_class->localize_data['warnings'] ?? null,
								'sanitize' => $redux->enqueue_class->localize_data['sanitize'] ?? null,
							);
						} catch ( Exception $e ) {
							$return_array = array( 'status' => $e->getMessage() );
						}
					} else {
						echo wp_json_encode(
							array(
								'status' => esc_html__( 'Your panel has no fields. Nothing to save.', 'redux-framework' ),
							)
						);
						die();
					}
				}
			}

			if ( isset( $core->transients['run_compiler'] ) && $core->transients['run_compiler'] ) {
				$core->no_output = true;
				$temp            = $core->args['output_variables_prefix'];

				// Allow the override of variable's prefix for use by SCSS or LESS.
				if ( isset( $core->args['compiler_output_variables_prefix'] ) ) {
					$core->args['output_variables_prefix'] = $core->args['compiler_output_variables_prefix'];
				}

				$core->output_class->enqueue();
				$core->args['output_variables_prefix'] = $temp;

				try {

					// phpcs:ignore WordPress.NamingConventions.ValidVariableName
					$compiler_css = $core->compilerCSS;  // Backward compatibility variable.

					/**
					 * Action 'redux/options/{opt_name}/compiler'
					 *
					 * @param array  $options Global options.
					 * @param string $css CSS that get sent to the compiler hook.
					 * @param array  $changed_values Changed option values.
					 * @param array  $output_variables Output variables.
					 */

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					do_action( 'redux/options/' . $core->args['opt_name'] . '/compiler', $core->options, $compiler_css, $core->transients['changed_values'], $core->output_variables );

					/**
					 * Action 'redux/options/{opt_name}/compiler/advanced'
					 *
					 * @param object $redux ReduxFramework object.
					 */

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					do_action( 'redux/options/' . $core->args['opt_name'] . '/compiler/advanced', $core );
				} catch ( Exception $e ) {
					$return_array = array( 'status' => $e->getMessage() );
				}

				unset( $core->transients['run_compiler'] );
				$core->transient_class->set();
			}

			if ( isset( $return_array ) ) {
				if ( 'success' === $return_array['status'] ) {
					$panel = new Redux_Panel( $redux );
					ob_start();
					$panel->notification_bar();
					$notification_bar = ob_get_contents();
					ob_end_clean();
					$return_array['notification_bar'] = $notification_bar;
				}

				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				echo wp_json_encode( apply_filters( 'redux/options/' . $core->args['opt_name'] . '/ajax_save/response', $return_array ) );
			}

			die();
		}
	}
}
