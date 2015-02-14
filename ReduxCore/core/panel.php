<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	if ( ! class_exists( 'reduxCorePanel' ) ) {
		class reduxCorePanel {
			public $parent = null;
			public $template_path = null;
			public $original_path = null;

			public function __construct( $parent ) {
				$this->parent             = $parent;
				Redux_Functions::$_parent = $parent;
				$this->template_path      = $this->original_path = ReduxFramework::$_dir . 'templates/panel/';
				$this->template_path      = trailingslashit( apply_filters( "redux/{$this->parent->args['opt_name']}/panel/templates_path", $this->template_path ) );
				$this->panel_template();

			}

			private function panel_template() {
				/**
				 * action 'redux/{opt_name}/panel/before'
				 */
				do_action( "redux/{$this->parent->args['opt_name']}/panel/before" );

				echo '<div class="wrap"><h2></h2></div>'; // Stupid hack for Wordpress alerts and warnings

				echo '<div class="clear"></div>';
				echo '<div class="wrap">';

				// Do we support JS?
				echo '<noscript><div class="no-js">' . __( 'Warning- This options panel will not work properly without javascript!', 'redux-framework' ) . '</div></noscript>';

				// Security is vital!
				echo '<input type="hidden" id="ajaxsecurity" name="security" value="' . wp_create_nonce( 'redux_ajax_nonce' ) . '" />';

				/**
				 * action 'redux-page-before-form-{opt_name}'
				 *
				 * @deprecated
				 */
				do_action( "redux-page-before-form-{$this->parent->args['opt_name']}" ); // Remove

				/**
				 * action 'redux/page/{opt_name}/form/before'
				 *
				 * @param object $this ReduxFramework
				 */
				do_action( "redux/page/{$this->parent->args['opt_name']}/form/before", $this );

				$this->get_template( 'layout.tpl.php' );

				/**
				 * action 'redux-page-after-form-{opt_name}'
				 *
				 * @deprecated
				 */
				do_action( "redux-page-after-form-{$this->parent->args['opt_name']}" ); // REMOVE

				/**
				 * action 'redux/page/{opt_name}/form/after'
				 *
				 * @param object $this ReduxFramework
				 */
				do_action( "redux/page/{$this->parent->args['opt_name']}/form/after", $this );

				echo '<div class="clear"></div>';
				echo '</div><!--wrap-->';

				if ( $this->parent->args['dev_mode'] == true ) {
					if ( current_user_can( 'administrator' ) ) {
						global $wpdb;
						echo "<br /><pre>";
						print_r( $wpdb->queries );
						echo "</pre>";
					}

					echo '<br /><div class="redux-timer">' . get_num_queries() . ' queries in ' . timer_stop( 0 ) . ' seconds<br/>Redux is currently set to developer mode.</div>';
				}

				/**
				 * action 'redux/{opt_name}/panel/after'
				 */
				do_action( "redux/{$this->parent->args['opt_name']}/panel/after" );

			}

			function get_template( $file ) {

				if ( empty( $file ) ) {
					return;
				}

				if ( file_exists( $this->template_path . $file ) ) {
					$path = $this->template_path . $file;
				} else {
					$path = $this->original_path . $file;
				}

				$path = apply_filters( "redux/{$this->parent->args['opt_name']}/panel/template/" . $file, $path );

				include( $path );

			}


		}
	}