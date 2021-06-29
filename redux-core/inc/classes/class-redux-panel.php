<?php
/**
 * Redux Panel Class
 *
 * @class Redux_Panel
 * @version 3.0.0
 * @package Redux Framework/Classes
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Panel', false ) ) {

	/**
	 * Class Redux_Panel
	 */
	class Redux_Panel {

		/**
		 * ReduxFramwrok object pointer.
		 *
		 * @var object
		 */
		public $parent = null;

		/**
		 * Path to templates dir.
		 *
		 * @var null|string
		 */
		public $template_path = null;

		/**
		 * Original template path.
		 *
		 * @var null
		 */
		public $original_path = null;

		/**
		 * Sets the path from the arg or via filter. Also calls the panel template function.
		 *
		 * @param object $parent ReduxFramework pointer.
		 */
		public function __construct( $parent ) {
			$this->parent        = $parent;
			$this->template_path = Redux_Core::$dir . 'templates/panel/';
			$this->original_path = Redux_Core::$dir . 'templates/panel/';

			if ( ! empty( $this->parent->args['templates_path'] ) ) {
				$this->template_path = trailingslashit( $this->parent->args['templates_path'] );
			}

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$this->template_path = trailingslashit( apply_filters( "redux/{$this->parent->args['opt_name']}/panel/templates_path", $this->template_path ) );
		}

		/**
		 * Class init.
		 */
		public function init() {
			$this->panel_template();
		}

		/**
		 * Loads the panel templates where needed and provides the container for Redux
		 */
		private function panel_template() {
			if ( $this->parent->args['dev_mode'] ) {
				$this->template_file_check_notice();
			}

			/**
			 * Action 'redux/{opt_name}/panel/before'
			 */

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( "redux/{$this->parent->args['opt_name']}/panel/before" );

			echo '<div class="wrap"><h2></h2></div>'; // Stupid hack for WordPress alerts and warnings.

			echo '<div class="clear"></div>';
			echo '<div class="wrap redux-wrap-div" data-opt-name="' . esc_attr( $this->parent->args['opt_name'] ) . '">';

			// Do we support JS?
			echo '<noscript><div class="no-js">' . esc_html__( 'Warning- This options panel will not work properly without javascript!', 'redux-framework' ) . '</div></noscript>';

			// Security is vital!
			echo '<input type="hidden" class="redux-ajax-security" data-opt-name="' . esc_attr( $this->parent->args['opt_name'] ) . '" id="ajaxsecurity" name="security" value="' . esc_attr( wp_create_nonce( 'redux_ajax_nonce' . $this->parent->args['opt_name'] ) ) . '" />';

			/**
			 * Action 'redux/page/{opt_name}/form/before'
			 *
			 * @param object $this ReduxFramework
			 */

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( "redux/page/{$this->parent->args['opt_name']}/form/before", $this );

			if ( is_rtl() ) {
				$this->parent->args['class'] = ' redux-rtl';
			}

			$this->get_template( 'container.tpl.php' );

			/**
			 * Action 'redux/page/{opt_name}/form/after'
			 *
			 * @param object $this ReduxFramework
			 */

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( "redux/page/{$this->parent->args['opt_name']}/form/after", $this );

			echo '<div class="clear"></div>';
			echo '</div>';

			if ( true === $this->parent->args['dev_mode'] ) {
				echo '<br /><div class="redux-timer">' . esc_html( get_num_queries() ) . ' queries in ' . esc_html( timer_stop( 0 ) ) . ' seconds<br/>Redux is currently set to developer mode.</div>';
			}

			/**
			 * Action 'redux/{opt_name}/panel/after'
			 */

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( "redux/{$this->parent->args['opt_name']}/panel/after" );
		}

		/**
		 * Calls the various notification bars and sets the appropriate templates.
		 */
		public function notification_bar() {
			if ( isset( $this->parent->transients['last_save_mode'] ) ) {

				if ( 'import' === $this->parent->transients['last_save_mode'] ) {
					/**
					 * Action 'redux/options/{opt_name}/import'
					 *
					 * @param object $this ReduxFramework
					 */

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					do_action( "redux/options/{$this->parent->args['opt_name']}/import", $this, $this->parent->transients['changed_values'] );

					echo '<div class="admin-notice notice-blue saved_notice">';

					/**
					 * Filter 'redux-imported-text-{opt_name}'
					 *
					 * @param string  translated "settings imported" text
					 */

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					echo '<strong>' . esc_html( apply_filters( "redux-imported-text-{$this->parent->args['opt_name']}", esc_html__( 'Settings Imported!', 'redux-framework' ) ) ) . '</strong>';
					echo '</div>';
				} elseif ( 'defaults' === $this->parent->transients['last_save_mode'] ) {
					/**
					 * Action 'redux/options/{opt_name}/reset'
					 *
					 * @param object $this ReduxFramework
					 */

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					do_action( "redux/options/{$this->parent->args['opt_name']}/reset", $this );

					echo '<div class="saved_notice admin-notice notice-yellow">';

					/**
					 * Filter 'redux-defaults-text-{opt_name}'
					 *
					 * @param string  translated "settings imported" text
					 */

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					echo '<strong>' . esc_html( apply_filters( "redux-defaults-text-{$this->parent->args['opt_name']}", esc_html__( 'All Defaults Restored!', 'redux-framework' ) ) ) . '</strong>';
					echo '</div>';
				} elseif ( 'defaults_section' === $this->parent->transients['last_save_mode'] ) {
					/**
					 * Action 'redux/options/{opt_name}/section/reset'
					 *
					 * @param object $this ReduxFramework
					 */

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					do_action( "redux/options/{$this->parent->args['opt_name']}/section/reset", $this );

					echo '<div class="saved_notice admin-notice notice-yellow">';

					/**
					 * Filter 'redux-defaults-section-text-{opt_name}'
					 *
					 * @param string  translated "settings imported" text
					 */

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					echo '<strong>' . esc_html( apply_filters( "redux-defaults-section-text-{$this->parent->args['opt_name']}", esc_html__( 'Section Defaults Restored!', 'redux-framework' ) ) ) . '</strong>';
					echo '</div>';
				} elseif ( 'normal' === $this->parent->transients['last_save_mode'] ) {
					/**
					 * Action 'redux/options/{opt_name}/saved'
					 *
					 * @param mixed $value set/saved option value
					 */

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					do_action( "redux/options/{$this->parent->args['opt_name']}/saved", $this->parent->options, $this->parent->transients['changed_values'] );

					echo '<div class="saved_notice admin-notice notice-green">';

					/**
					 * Filter 'redux-saved-text-{opt_name}'
					 *
					 * @param string translated "settings saved" text
					 */

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					echo '<strong>' . esc_html( apply_filters( "redux-saved-text-{$this->parent->args['opt_name']}", esc_html__( 'Settings Saved!', 'redux-framework' ) ) ) . '</strong>';
					echo '</div>';
				}

				unset( $this->parent->transients['last_save_mode'] );

				$this->parent->transient_class->set();
			}

			/**
			 * Action 'redux/options/{opt_name}/settings/changes'
			 *
			 * @param mixed $value set/saved option value
			 */

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( "redux/options/{$this->parent->args['opt_name']}/settings/change", $this->parent->options, $this->parent->transients['changed_values'] );

			echo '<div class="redux-save-warn notice-yellow">';

			/**
			 * Filter 'redux-changed-text-{opt_name}'
			 *
			 * @param string translated "settings have changed" text
			 */

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			echo '<strong>' . esc_html( apply_filters( "redux-changed-text-{$this->parent->args['opt_name']}", esc_html__( 'Settings have changed, you should save them!', 'redux-framework' ) ) ) . '</strong>';
			echo '</div>';

			/**
			 * Action 'redux/options/{opt_name}/errors'
			 *
			 * @param array $this ->errors error information
			 */

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( "redux/options/{$this->parent->args['opt_name']}/errors", $this->parent->errors );

			echo '<div class="redux-field-errors notice-red">';
			echo '<strong>';
			echo '<span></span> ' . esc_html__( 'error(s) were found!', 'redux-framework' );
			echo '</strong>';
			echo '</div>';

			/**
			 * Action 'redux/options/{opt_name}/warnings'
			 *
			 * @param array $this ->warnings warning information
			 */

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( "redux/options/{$this->parent->args['opt_name']}/warnings", $this->parent->warnings );

			echo '<div class="redux-field-warnings notice-yellow">';
			echo '<strong>';
			echo '<span></span> ' . esc_html__( 'warning(s) were found!', 'redux-framework' );
			echo '</strong>';
			echo '</div>';
		}

		/**
		 * Used to intitialize the settings fields for this panel. Required for saving and redirect.
		 */
		private function init_settings_fields() {
			// Must run or the page won't redirect properly.
			settings_fields( "{$this->parent->args['opt_name']}_group" );
		}

		/**
		 * Enable file deprecate warning from core.  This is necessary because the function is considered private.
		 *
		 * @return bool
		 */
		public function tick_file_deprecate_warning() {
			return true;
		}

		/**
		 * Used to select the proper template. If it doesn't exist in the path, then the original template file is used.
		 *
		 * @param string $file Path to template file.
		 */
		public function get_template( $file ) {
			if ( empty( $file ) ) {
				return;
			}

			if ( file_exists( $this->template_path . $file ) ) {
				$path = $this->template_path . $file;
			} else {
				$path = $this->original_path . $file;
			}

			// Shim for v3 templates.
			if ( ! file_exists( $path ) ) {
				$old_file = $file;

				add_filter( 'deprecated_file_trigger_error', array( $this, 'tick_file_deprecate_warning' ) );

				$file = str_replace( '-', '_', $file );

				_deprecated_file( esc_html( $file ), '4.0', esc_html( $old_file ), 'Please replace this outdated template with the current one from the Redux core.' );

				if ( file_exists( $this->template_path . $file ) ) {
					$path = $this->template_path . $file;
				} else {
					$path = $this->original_path . $file;
				}
			}

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( "redux/{$this->parent->args['opt_name']}/panel/template/" . $file . '/before' );

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$path = apply_filters( "redux/{$this->parent->args['opt_name']}/panel/template/" . $file, $path );

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( "redux/{$this->parent->args['opt_name']}/panel/template/" . $file . '/after' );

			require $path;
		}

		/**
		 * Scan the template files.
		 *
		 * @param string $template_path Path to template file.
		 *
		 * @return array
		 */
		public function scan_template_files( $template_path ) {
			$files  = scandir( $template_path );
			$result = array();
			if ( $files ) {
				foreach ( $files as $key => $value ) {
					if ( ! in_array( $value, array( '.', '..' ), true ) ) {
						if ( is_dir( $template_path . DIRECTORY_SEPARATOR . $value ) ) {
							$sub_files = self::scan_template_files( $template_path . DIRECTORY_SEPARATOR . $value );
							foreach ( $sub_files as $sub_file ) {
								$result[] = $value . DIRECTORY_SEPARATOR . $sub_file;
							}
						} else {
							$result[] = $value;
						}
					}
				}
			}

			return $result;
		}

		/**
		 * Show a notice highlighting bad template files
		 */
		public function template_file_check_notice() {
			if ( $this->original_path === $this->template_path ) {
				return;
			}

			$core_templates = $this->scan_template_files( $this->original_path );

			foreach ( $core_templates as $file ) {
				$developer_theme_file = false;

				if ( file_exists( $this->template_path . $file ) ) {
					$developer_theme_file = $this->template_path . $file;
				}

				if ( $developer_theme_file ) {
					$core_version      = Redux_Helpers::get_template_version( $this->original_path . $file );
					$developer_version = Redux_Helpers::get_template_version( $developer_theme_file );

					if ( $core_version && $developer_version && version_compare( $developer_version, $core_version, '<' ) && isset( $this->parent->args['dev_mode'] ) && ! empty( $this->parent->args['dev_mode'] ) ) {
						?>
						<div id="message" class="error redux-message">
							<p>
								<strong><?php esc_html_e( 'Your panel has bundled copies of Redux Framework template files that are outdated!', 'redux-framework' ); ?></strong>&nbsp;&nbsp;<?php esc_html_e( 'Please update them now as functionality issues could arise.', 'redux-framework' ); ?></a></strong>
							</p>
						</div>
						<?php

						return;
					}
				}
			}
		}

		/**
		 * Outputs the HTML for a given section using the WordPress settings API.
		 *
		 * @param mixed $k Section number of settings panel to display.
		 */
		private function output_section( $k ) {
			do_settings_sections( $this->parent->args['opt_name'] . $k . '_section_group' );
		}
	}
}

if ( ! class_exists( 'reduxCorePanel' ) ) {
	class_alias( 'Redux_Panel', 'reduxCorePanel' );
}


