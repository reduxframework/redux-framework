<?php
/**
 * Redux Page Render Class
 *
 * @class Redux_Page_Render
 * @version 3.0.0
 * @package Redux Framework/Classes
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Page_Render', false ) ) {

	/**
	 * Class Redux_Page_Render
	 */
	class Redux_Page_Render extends Redux_Class {

		/**
		 * Flag to show or hide hints in panel.
		 *
		 * @var bool
		 * @access private
		 */
		private $show_hints = false;

		/**
		 * Creates page's hook suffix.
		 *
		 * @var false|string
		 * @access private
		 */
		private $page = '';

		/**
		 * Redux_Page_Render constructor.
		 *
		 * @param object $parent ReduxFramework pointer.
		 */
		public function __construct( $parent ) {
			parent::__construct( $parent );

			// phpcs:ignore Generic.Strings.UnnecessaryStringConcat
			add_action( 'admin' . '_bar' . '_menu', array( $this, 'add_menu' ), $parent->args['admin_bar_priority'] );

			// Options page.
			add_action( 'admin_menu', array( $this, 'options_page' ) );

			// Add a network menu.
			if ( 'network' === $parent->args['database'] && $parent->args['network_admin'] ) {
				add_action( 'network_admin_menu', array( $this, 'options_page' ) );
			}
		}

		/**
		 * Class Options Page Function, creates main options page.
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return void
		 */
		public function options_page() {
			$core = $this->core();
			// phpcs:ignore Generic.CodeAnalysis.EmptyStatement
			if ( 'hidden' === $core->args['menu_type'] ) {
				// No menu to add!
			} elseif ( 'submenu' === $core->args['menu_type'] ) {
				$this->submenu( $core );
			} else {
				// Theme-Check notice is displayed for WP.org theme devs, informing them to NOT use this.
				$this->page = call_user_func(
					'add_menu_page',
					$core->args['page_title'],
					$core->args['menu_title'],
					$core->args['page_permissions'],
					$core->args['page_slug'],
					array(
						$this,
						'generate_panel',
					),
					$core->args['menu_icon'],
					$core->args['page_priority']
				);

				if ( true === $core->args['allow_sub_menu'] ) {
					foreach ( $core->sections as $k => $section ) {
						$can_be_subsection = $k > 0 && ( ! isset( $core->sections[ ( $k ) ]['type'] ) || 'divide' !== $core->sections[ ( $k ) ]['type'] );

						if ( ! isset( $section['title'] ) || ( $can_be_subsection && ( isset( $section['subsection'] ) && true === $section['subsection'] ) ) ) {
							continue;
						}

						if ( isset( $section['submenu'] ) && false === $section['submenu'] ) {
							continue;
						}

						if ( isset( $section['customizer_only'] ) && true === $section['customizer_only'] ) {
							continue;
						}

						if ( isset( $section['hidden'] ) && true === $section['hidden'] ) {
							continue;
						}

						if ( isset( $section['permissions'] ) && ! Redux_Helpers::current_user_can( $section['permissions'] ) ) {
							continue;
						}

						// ONLY for non-wp.org themes OR plugins. Theme-Check alert shown if used and IS theme.
						call_user_func(
							'add_submenu_page',
							$core->args['page_slug'],
							$section['title'],
							$section['title'],
							$core->args['page_permissions'],
							$core->args['page_slug'] . '&tab=' . $k,
							'__return_null'
						);
					}

					// Remove parent submenu item instead of adding null item.
					remove_submenu_page( $core->args['page_slug'], $core->args['page_slug'] );
				}
			}

			add_action( "load-$this->page", array( $this, 'load_page' ) );
		}

		/**
		 * Show page help
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function load_page() {
			$core = $this->core();

			// Do admin head action for this page.
			add_action( 'admin_head', array( $this, 'admin_head' ) );

			// Do admin footer text hook.
			add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ) );

			$screen = get_current_screen();

			if ( is_array( $core->args['help_tabs'] ) ) {
				foreach ( $core->args['help_tabs'] as $tab ) {
					$screen->add_help_tab( $tab );
				}
			}

			// If hint argument is set, display hint tab.
			if ( true === $this->show_hints ) {
				global $current_user;

				$cur_page = '';

				// Users enable/disable hint choice.
				$hint_status = get_user_meta( $current_user->ID, 'ignore_hints' ) ? get_user_meta( $current_user->ID, 'ignore_hints', true ) : 'true';

				// current page parameters.
				if ( isset( $_GET['page'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
					$cur_page = sanitize_text_field( wp_unslash( $_GET['page'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
				}

				$cur_tab = '0';
				if ( isset( $_GET['tab'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
					$cur_tab = sanitize_text_field( wp_unslash( $_GET['tab'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
				}

				// Default url values for enabling hints.
				$dismiss = 'true';
				$s       = esc_html__( 'Enable', 'redux-framework' );

				// Values for disabling hints.
				if ( 'true' === $hint_status ) {
					$dismiss = 'false';
					$s       = esc_html__( 'Disable', 'redux-framework' );
				}

				// Make URL.
				$nonce = wp_create_nonce( 'redux_hint_toggle' );
				$url   = '<a class="redux_hint_status" href="?nonce=' . $nonce . '&amp;dismiss=' . $dismiss . '&amp;id=hints&amp;page=' . esc_attr( $cur_page ) . '&amp;tab=' . esc_attr( $cur_tab ) . '">' . $s . ' hints</a>';

				$event = esc_html__( 'moving the mouse over', 'redux-framework' );
				if ( 'click' === $core->args['hints']['tip_effect']['show']['event'] ) {
					$event = esc_html__( 'clicking', 'redux-framework' );
				}

				// Construct message.
				// translators: %1$s: Mouse action.  %2$s: Hint status.
				$msg = sprintf( esc_html__( 'Hints are tooltips that popup when %1$s the hint icon, offering addition information about the field in which they appear.  They can be %2$s by using the link below.', 'redux-framework' ), $event, Redux_Core::strtolower( $s ) ) . '<br/><br/>' . $url;

				// Construct hint tab.
				$tab = array(
					'id'      => 'redux-hint-tab',
					'title'   => esc_html__( 'Hints', 'redux-framework' ),
					'content' => '<p>' . $msg . '</p>',
				);

				$screen->add_help_tab( $tab );
			}

			// Sidebar text.
			if ( '' !== $core->args['help_sidebar'] ) {

				// Specify users text from arguments.
				$screen->set_help_sidebar( $core->args['help_sidebar'] );
			} else {
				// If sidebar text is empty and hints are active, display text
				// about hints.
				if ( true === $this->show_hints ) {
					$screen->set_help_sidebar( '<p><strong>Redux Framework</strong><br/><br/>' . esc_html__( 'Hint Tooltip Preferences', 'redux-framework' ) . '</p>' );
				}
			}

			/**
			 * Action 'redux/page/{opt_name}/load'
			 *
			 * @param object $screen WP_Screen
			 */

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( "redux/page/{$core->args['opt_name']}/load", $screen );
		}

		/**
		 * Class Add Sub Menu Function, creates options submenu in WordPress admin area.
		 *
		 * @param       object $core ReduxFramework core pointer.
		 *
		 * @since       3.1.9
		 * @access      private
		 * @return      void
		 */
		private function submenu( $core ) {
			global $submenu;

			$page_parent      = $core->args['page_parent'];
			$page_title       = $core->args['page_title'];
			$menu_title       = $core->args['menu_title'];
			$page_permissions = $core->args['page_permissions'];
			$page_slug        = $core->args['page_slug'];

			// Just in case. One never knows.
			$page_parent = Redux_Core::strtolower( $page_parent );

			$test = array(
				'index.php'               => 'dashboard',
				'edit.php'                => 'posts',
				'upload.php'              => 'media',
				'link-manager.php'        => 'links',
				'edit.php?post_type=page' => 'pages',
				'edit-comments.php'       => 'comments',
				'themes.php'              => 'theme',
				'plugins.php'             => 'plugins',
				'users.php'               => 'users',
				'tools.php'               => 'management',
				'options-general.php'     => 'options',
			);

			if ( isset( $test[ $page_parent ] ) ) {
				$function   = 'add_' . $test[ $page_parent ] . '_page';
				$this->page = $function(
					$page_title,
					$menu_title,
					$page_permissions,
					$page_slug,
					array( $this, 'generate_panel' )
				);
			} else {
				// Network settings and Post type menus. These do not have
				// wrappers and need to be appended to using add_submenu_page.
				// Okay, since we've left the post type menu appending
				// as default, we need to validate it, so anything that
				// isn't post_type=<post_type> doesn't get through and mess
				// things up.
				$add_menu = false;
				if ( 'settings.php' !== $page_parent ) {
					// Establish the needle.
					$needle = '?post_type=';

					// Check if it exists in the page_parent (how I miss instr).
					$needle_pos = strrpos( $page_parent, $needle );

					// It's there, so...
					if ( $needle_pos > 0 ) {

						// Get the post type.
						$post_type = substr( $page_parent, $needle_pos + strlen( $needle ) );

						// Ensure it exists.
						if ( post_type_exists( $post_type ) ) {
							// Set flag to add the menu page.
							$add_menu = true;
						}
						// custom menu.
					} elseif ( isset( $submenu[ $core->args['page_parent'] ] ) ) {
						$add_menu = true;
					} else {
						global $menu;

						foreach ( $menu as $menuitem ) {
							$needle_menu_slug = isset( $menuitem ) ? $menuitem[2] : false;
							if ( false !== $needle_menu_slug ) {

								// check if the current needle menu equals page_parent.
								if ( 0 === strcasecmp( $needle_menu_slug, $page_parent ) ) {

									// found an empty parent menu.
									$add_menu = true;
								}
							}
						}
					}
				} else {
					// The page_parent was settings.php, so set menu add
					// flag to true.
					$add_menu = true;
				}
				// Add the submenu if it's permitted.
				if ( true === $add_menu ) {
					// ONLY for non-wp.org themes OR plugins. Theme-Check alert shown if used and IS theme.
					$this->page = call_user_func(
						'add_submenu_page',
						$page_parent,
						$page_title,
						$menu_title,
						$page_permissions,
						$page_slug,
						array(
							$this,
							'generate_panel',
						)
					);
				}
			}
		}

		/**
		 * Output the option panel.
		 */
		public function generate_panel() {
			$core = $this->core();

			$panel = new Redux_Panel( $core );
			$panel->init();
			$core->transient_class->set();
		}

		/**
		 * Section HTML OUTPUT.
		 *
		 * @param       array $section Sections array.
		 *
		 * @return      void
		 * @since       1.0.0
		 * @access      public
		 */
		public function section_desc( array $section ) {
			$core = $this->core();

			$id = rtrim( $section['id'], '_section' );
			$id = str_replace( $core->args['opt_name'], '', $id );

			if ( isset( $core->sections[ $id ]['desc'] ) && ! empty( $core->sections[ $id ]['desc'] ) ) {
				echo '<div class="redux-section-desc">' . wp_kses_post( $core->sections[ $id ]['desc'] ) . '</div>';
			}
		}

		/**
		 * Field HTML OUTPUT.
		 * Gets option from options array, then calls the specific field type class - allows extending by other devs
		 *
		 * @param array             $field   Field array.
		 * @param string|array|null $v       Values.
		 *
		 * @return      void
		 * @since       1.0.0
		 */
		public function field_input( array $field, $v = null ) {
			$core = $this->core();

			if ( isset( $field['callback'] ) && ( is_callable( $field['callback'] ) || ( is_string( $field['callback'] ) && function_exists( $field['callback'] ) ) ) ) {

				$value = ( isset( $core->options[ $field['id'] ] ) ) ? $core->options[ $field['id'] ] : '';

				/**
				 * Action 'redux/field/{opt_name}/{field.type}/callback/before'
				 *
				 * @param array  $field field data
				 * @param string $value field.id
				 */
				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				do_action_ref_array(
				// phpcs:ignore WordPress.NamingConventions.ValidHookName
					"redux/field/{$core->args['opt_name']}/{$field['type']}/callback/before",
					array(
						&$field,
						&$value,
					)
				);

				/**
				 * Action 'redux/field/{opt_name}/callback/before'
				 *
				 * @param array  $field field data
				 * @param string $value field.id
				 */
				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				do_action_ref_array(
				// phpcs:ignore WordPress.NamingConventions.ValidHookName
					"redux/field/{$core->args['opt_name']}/callback/before",
					array(
						&$field,
						&$value,
					)
				);

				call_user_func( $field['callback'], $field, $value );

				/**
				 * Action 'redux/field/{opt_name}/{field.type}/callback/after'
				 *
				 * @param array  $field field data
				 * @param string $value field.id
				 */
				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				do_action_ref_array(
				// phpcs:ignore WordPress.NamingConventions.ValidHookName
					"redux/field/{$core->args['opt_name']}/{$field['type']}/callback/after",
					array(
						&$field,
						&$value,
					)
				);

				/**
				 * Action 'redux/field/{opt_name}/callback/after'
				 *
				 * @param array  $field field data
				 * @param string $value field.id
				 */
				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				do_action_ref_array(
				// phpcs:ignore WordPress.NamingConventions.ValidHookName
					"redux/field/{$core->args['opt_name']}/callback/after",
					array(
						&$field,
						&$value,
					)
				);

				return;
			}

			if ( isset( $field['type'] ) ) {
				// If the field is set not to display in the panel.
				$display = true;

				if ( isset( $_GET['page'] ) && $core->args['page_slug'] === $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification
					if ( isset( $field['panel'] ) && false === $field['panel'] ) {
						$display = false;
					}
				}

				if ( ! $display ) {
					return;
				}

				/**
				 * Filter 'redux/{opt_name}/field/class/{field.type}'
				 *
				 * @param       string        field class file path
				 * @param array $field field data
				 */
				$field_type = str_replace( '_', '-', $field['type'] );
				$core_path  = Redux_Core::$dir . "inc/fields/{$field['type']}/class-redux-$field_type.php";

				// Shim for v3 extension class names.
				if ( ! file_exists( $core_path ) ) {
					$core_path = Redux_Core::$dir . "inc/fields/{$field['type']}/field_{$field['type']}.php";
				}
				if ( Redux_Core::$pro_loaded ) {
					$pro_path = '';

					if ( class_exists( 'Redux_Pro' ) ) {
						$pro_path = Redux_Pro::$dir . "core/inc/fields/{$field['type']}/class-redux-pro-$field_type.php";
					}

					if ( file_exists( $pro_path ) ) {
						$filter_path = $pro_path;
					} else {
						$filter_path = $core_path;
					}
				} else {
					$filter_path = $core_path;
				}

				$field_class = '';

				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				$class_file = apply_filters( "redux/{$core->args['opt_name']}/field/class/{$field['type']}", $filter_path, $field );

				if ( $class_file ) {
					$field_classes = array( 'Redux_' . $field['type'], 'ReduxFramework_' . $field['type'] );

					$field_class = Redux_Functions::class_exists_ex( $field_classes );

					if ( ! class_exists( $field_class ) ) {
						if ( file_exists( $class_file ) ) {
							require_once $class_file;
							$field_class = Redux_Functions::class_exists_ex( $field_classes );
						} else {
							// translators: %1$s is the field ID, %2$s is the field type.
							echo sprintf( esc_html__( 'Field %1$s could not be displayed. Field type %2$s was not found.', 'redux-framework' ), '<code>' . esc_attr( $field['id'] ) . '</code>', '<code>' . esc_attr( $field['type'] ) . '</code>' );
						}
					}
				}

				if ( class_exists( $field_class ) ) {
					$value = $core->options[ $field['id'] ] ?? '';

					if ( null !== $v ) {
						$value = $v;
					}

					/**
					 * Action 'redux/field/{opt_name}/{field.type}/render/before'
					 *
					 * @param array  $field field data
					 * @param string $value field id
					 */
					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					do_action_ref_array(
					// phpcs:ignore WordPress.NamingConventions.ValidHookName
						"redux/field/{$core->args['opt_name']}/{$field['type']}/render/before",
						array(
							&$field,
							&$value,
						)
					);

					/**
					 * Action 'redux/field/{$this->args['opt_name']}/render/before'
					 *
					 * @param array  $field field data
					 * @param string $value field id
					 */
					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					do_action_ref_array(
					// phpcs:ignore WordPress.NamingConventions.ValidHookName
						"redux/field/{$core->args['opt_name']}/render/before",
						array(
							&$field,
							&$value,
						)
					);

					if ( ! isset( $field['name_suffix'] ) ) {
						$field['name_suffix'] = '';
					}

					$data = array(
						'field' => $field,
						'value' => $value,
						'core'  => $core,
						'mode'  => 'render',
					);

					$pro_field_loaded = Redux_Functions::load_pro_field( $data );

					$render = new $field_class( $field, $value, $core );

					ob_start();
					try {
						$render->render();
					} catch ( Error $e ) {
						echo 'Field failed to render: ',  esc_html( $e->getMessage() ), "\n";
					}

					/**
					 * Filter 'redux/field/{opt_name}'
					 *
					 * @param       string        rendered field markup
					 * @param array $field field data
					 */

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					$_render = apply_filters( "redux/field/{$core->args['opt_name']}", ob_get_contents(), $field );

					/**
					 * Filter 'redux/field/{opt_name}/{field.type}/render/after'
					 *
					 * @param       string        rendered field markup
					 * @param array $field field data
					 */

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					$_render = apply_filters( "redux/field/{$core->args['opt_name']}/{$field['type']}/render/after", $_render, $field );

					/**
					 * Filter 'redux/field/{opt_name}/render/after'
					 *
					 * @param       string        rendered field markup
					 * @param array $field field data
					 */

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					$_render = apply_filters( "redux/field/{$core->args['opt_name']}/render/after", $_render, $field );

					ob_end_clean();

					// create default data und class string and checks the dependencies of an object.
					$class_string = '';

					$core->required_class->check_dependencies( $field );

					/**
					 * Action 'redux/field/{opt_name}/{field.type}/fieldset/before/{opt_name}'
					 *
					 * @param array  $field field data
					 * @param string $value field id
					 */
					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					do_action_ref_array(
					// phpcs:ignore WordPress.NamingConventions.ValidHookName
						"redux/field/{$core->args['opt_name']}/{$field['type']}/fieldset/before/{$core->args['opt_name']}",
						array(
							&$field,
							&$value,
						)
					);

					/**
					 * Action 'redux/field/{opt_name}/fieldset/before/{opt_name}'
					 *
					 * @param array  $field field data
					 * @param string $value field id
					 */
					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					do_action_ref_array(
					// phpcs:ignore WordPress.NamingConventions.ValidHookName
						"redux/field/{$core->args['opt_name']}/fieldset/before/{$core->args['opt_name']}",
						array(
							&$field,
							&$value,
						)
					);

					$hidden = '';
					if ( isset( $field['hidden'] ) && $field['hidden'] ) {
						$hidden = 'hidden ';
					}

					$disabled = '';
					if ( isset( $field['disabled'] ) && $field['disabled'] ) {
						$disabled = 'disabled ';
					}

					if ( isset( $field['full_width'] ) && true === $field['full_width'] ) {
						$class_string .= 'redux_remove_th';
					}

					if ( isset( $field['fieldset_class'] ) && ! empty( $field['fieldset_class'] ) ) {
						$class_string .= ' ' . $field['fieldset_class'];
					}

					if ( Redux_Core::$pro_loaded ) {
						if ( $pro_field_loaded ) {
							$class_string .= ' redux-pro-field-init';
						}
					}

					echo '<fieldset id="' . esc_attr( $core->args['opt_name'] . '-' . $field['id'] ) . '" class="' . esc_attr( $hidden . esc_attr( $disabled ) . 'redux-field-container redux-field redux-field-init redux-container-' . $field['type'] . ' ' . $class_string ) . '" data-id="' . esc_attr( $field['id'] ) . '" data-type="' . esc_attr( $field['type'] ) . '">';
					echo $_render; // phpcs:ignore WordPress.Security.EscapeOutput

					if ( ! empty( $field['desc'] ) ) {
						$field['description'] = $field['desc'];
					}

					echo ( isset( $field['description'] ) && 'info' !== $field['type'] && 'section' !== $field['type'] && ! empty( $field['description'] ) ) ? '<div class="description field-desc">' . wp_kses_post( $field['description'] ) . '</div>' : '';
					echo '</fieldset>';

					/**
					 * Action 'redux/field/{opt_name}/{field.type}/fieldset/after/{opt_name}'
					 *
					 * @param array  $field field data
					 * @param string $value field id
					 */
					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					do_action_ref_array(
					// phpcs:ignore WordPress.NamingConventions.ValidHookName
						"redux/field/{$core->args['opt_name']}/{$field['type']}/fieldset/after/{$core->args['opt_name']}",
						array(
							&$field,
							&$value,
						)
					);

					/**
					 * Action 'redux/field/{opt_name}/fieldset/after/{opt_name}'
					 *
					 * @param array  $field field data
					 * @param string $value field id
					 */
					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					do_action_ref_array(
					// phpcs:ignore WordPress.NamingConventions.ValidHookName
						"redux/field/{$core->args['opt_name']}/fieldset/after/{$core->args['opt_name']}",
						array(
							&$field,
							&$value,
						)
					);
				}
			}
		}

		/**
		 * Add admin bar menu.
		 *
		 * @since       3.1.5.16
		 * @access      public
		 * @global      $menu , $submenu, $wp_admin_bar
		 * @return      void
		 */
		public function add_menu() {
			global $menu, $submenu, $wp_admin_bar;

			$core = $this->core();

			if ( ! is_super_admin() || ! is_admin_bar_showing() || ! $core->args['admin_bar'] || 'hidden' === $core->args['menu_type'] ) {
				return;
			}

			if ( $menu ) {
				foreach ( $menu as $menu_item ) {
					if ( isset( $menu_item[2] ) && $menu_item[2] === $core->args['page_slug'] ) {

						// Fetch the title.
						$title = empty( $core->args['admin_bar_icon'] ) ? $menu_item[0] : '<span class="ab-icon ' . esc_attr( $core->args['admin_bar_icon'] ) . '"></span>' . esc_html( $menu_item[0] );

						$nodeargs = array(
							'id'    => $menu_item[2],
							'title' => $title,
							'href'  => admin_url( 'admin.php?page=' . $menu_item[2] ),
							'meta'  => array(),
						);

						$wp_admin_bar->add_node( $nodeargs );

						break;
					}
				}

				if ( isset( $submenu[ $core->args['page_slug'] ] ) && is_array( $submenu[ $core->args['page_slug'] ] ) ) {
					foreach ( $submenu[ $core->args['page_slug'] ] as $index => $redux_options_submenu ) {
						$subnodeargs = array(
							'id'     => esc_html( $core->args['page_slug'] . '_' . $index ),
							'title'  => esc_html( $redux_options_submenu[0] ),
							'parent' => esc_html( $core->args['page_slug'] ),
							'href'   => esc_url( admin_url( 'admin.php?page=' . $redux_options_submenu[2] ) ),
						);

						$wp_admin_bar->add_node( $subnodeargs );
					}
				}

				// Let's deal with external links.
				if ( isset( $core->args['admin_bar_links'] ) ) {
					if ( ! $core->args['dev_mode'] && $core->args_class->omit_items ) {
						return;
					}

					// Group for Main Root Menu (External Group).
					$wp_admin_bar->add_node(
						array(
							'id'     => esc_html( $core->args['page_slug'] . '-external' ),
							'parent' => esc_html( $core->args['page_slug'] ),
							'group'  => true,
							'meta'   => array( 'class' => 'ab-sub-secondary' ),
						)
					);

					// Add Child Menus to External Group Menu.
					foreach ( $core->args['admin_bar_links'] as $link ) {
						if ( ! isset( $link['id'] ) ) {
							$link['id'] = $core->args['page_slug'] . '-sub-' . sanitize_html_class( $link['title'] );
						}

						$externalnodeargs = array(
							'id'     => esc_html( $link['id'] ),
							'title'  => esc_html( $link['title'] ),
							'parent' => esc_html( $core->args['page_slug'] . '-external' ),
							'href'   => esc_url( $link['href'] ),
							'meta'   => array( 'target' => '_blank' ),
						);

						$wp_admin_bar->add_node( $externalnodeargs );
					}
				}
			} else {
				// Fetch the title.
				$title = empty( $core->args['admin_bar_icon'] ) ? $core->args['menu_title'] : '<span class="ab-icon ' . esc_attr( $core->args['admin_bar_icon'] ) . '"></span>' . esc_html( $core->args['menu_title'] );

				$nodeargs = array(
					'id'    => esc_html( $core->args['page_slug'] ),
					'title' => $title,
					'href'  => esc_url( admin_url( 'admin.php?page=' . $core->args['page_slug'] ) ),
					'meta'  => array(),
				);

				$wp_admin_bar->add_node( $nodeargs );
			}
		}

		/**
		 * Do action redux-admin-head for options page
		 *
		 * @since       1.0.0
		 * @access      public
		 * @return      void
		 */
		public function admin_head() {
			$core = $this->core();

			/**
			 * Action 'redux/page/{opt_name}/header'
			 *
			 * @param  object $this ReduxFramework
			 */

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			do_action( "redux/page/{$core->args['opt_name']}/header", $core );
		}

		/**
		 * Return footer text
		 *
		 * @since       2.0.0
		 * @access      public
		 * @return      string $this->args['footer_credit']
		 */
		public function admin_footer_text(): string {
			$core = $this->core();

			return $core->args['footer_credit'];
		}

		/**
		 * Generate field header HTML
		 *
		 * @param array $field Field array.
		 *
		 * @return string
		 */
		public function get_header_html( array $field ): string {
			global $current_user;

			$core = $this->core();

			// Set to empty string to avoid warnings.
			$hint = '';
			$th   = '';

			if ( isset( $field['title'] ) && isset( $field['type'] ) && 'info' !== $field['type'] && 'section' !== $field['type'] ) {
				$default_mark = ( ! empty( $field['default'] ) && isset( $core->options[ $field['id'] ] ) && $field['default'] === $core->options[ $field['id'] ] && ! empty( $core->args['default_mark'] ) ) ? $core->args['default_mark'] : '';

				// If a hint is specified in the field, process it.
				if ( isset( $field['hint'] ) && ! empty( $field['hint'] ) ) {

					// Set show_hints flag to true, so help tab will be displayed.
					$this->show_hints = true;

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					$hint = apply_filters( 'redux/hints/html', $hint, $field, $core->args );

					// Get user pref for displaying hints.
					$meta_val = get_user_meta( $current_user->ID, 'ignore_hints', true );

					if ( 'true' === $meta_val || empty( $meta_val ) && empty( $hint ) ) {

						// Set hand cursor for clickable hints.
						$pointer = '';
						if ( isset( $core->args['hints']['tip_effect']['show']['event'] ) && 'click' === $core->args['hints']['tip_effect']['show']['event'] ) {
							$pointer = 'pointer';
						}

						$size = '16px';
						if ( 'large' === $core->args['hints']['icon_size'] ) {
							$size = '18px';
						}

						// In case docs are ignored.
						$title_param   = $field['hint']['title'] ?? '';
						$content_param = $field['hint']['content'] ?? '';

						$hint_color = $core->args['hints']['icon_color'] ?? '#d3d3d3';

						// Set hint html with appropriate position css.
						$hint = '<div class="redux-hint-qtip" style="float:' . esc_attr( $core->args['hints']['icon_position'] ) . '; font-size: ' . esc_attr( $size ) . '; color:' . esc_attr( $hint_color ) . '; cursor: ' . $pointer . ';" qtip-title="' . esc_attr( $title_param ) . '" qtip-content="' . wp_kses_post( $content_param ) . '">&nbsp;<i class="' . ( isset( $core->args['hints']['icon'] ) ? esc_attr( $core->args['hints']['icon'] ) : '' ) . '"></i></div>';
					}
				}

				if ( ! empty( $field['title'] ) ) {
					if ( 'left' === $core->args['hints']['icon_position'] ) {
						$th = $hint . wp_kses_post( $field['title'] ) . $default_mark . ' ';
					} else {
						$th = wp_kses_post( $field['title'] ) . $default_mark . ' ' . $hint;
					}
				}

				if ( isset( $field['subtitle'] ) ) {
					$th .= '<span class="description">' . wp_kses_post( $field['subtitle'] ) . '</span>';
				}
			}

			if ( ! empty( $th ) ) {
				$th = '<div class="redux_field_th">' . $th . '</div>';
			}

			$filter_arr = array(
				'editor',
				'ace_editor',
				'info',
				'section',
				'repeater',
				'color_scheme',
				'social_profiles',
				'css_layout',
			);

			if ( true === $core->args['default_show'] && isset( $field['default'] ) && isset( $core->options[ $field['id'] ] ) && $field['default'] !== $core->options[ $field['id'] ] && ! in_array( $field['type'], $filter_arr, true ) ) {
				$th .= $this->get_default_output_string( $field );
			}

			return $th;
		}

		/**
		 * Return default output string for use in panel
		 *
		 * @param array $field Field array.
		 *
		 * @return      string default_output
		 * @since       3.1.5
		 * @access      public
		 */
		private function get_default_output_string( array $field ): string {
			$default_output = '';

			if ( ! isset( $field['default'] ) ) {
				$field['default'] = '';
			}

			if ( ! is_array( $field['default'] ) ) {
				if ( ! empty( $field['options'][ $field['default'] ] ) ) {
					if ( ! empty( $field['options'][ $field['default'] ]['alt'] ) ) {
						$default_output .= $field['options'][ $field['default'] ]['alt'] . ', ';
					} else {
						if ( ! is_array( $field['options'][ $field['default'] ] ) ) {
							$default_output .= $field['options'][ $field['default'] ] . ', ';
						} else {
							$default_output .= maybe_serialize( $field['options'][ $field['default'] ] ) . ', ';
						}
					}
				} elseif ( ! empty( $field['options'][ $field['default'] ] ) ) {
					$default_output .= $field['options'][ $field['default'] ] . ', ';
				} elseif ( ! empty( $field['default'] ) ) {
					if ( 'switch' === $field['type'] && isset( $field['on'] ) && isset( $field['off'] ) ) {
						$default_output .= ( 1 === $field['default'] ? $field['on'] : $field['off'] ) . ', ';
					} else {
						$default_output .= $field['default'] . ', ';
					}
				}
			} else {
				foreach ( $field['default'] as $defaultk => $defaultv ) {
					if ( ! empty( $field['options'][ $defaultv ]['alt'] ) ) {
						$default_output .= $field['options'][ $defaultv ]['alt'] . ', ';
					} elseif ( ! empty( $field['options'][ $defaultv ] ) ) {
						$default_output .= $field['options'][ $defaultv ] . ', ';
					} elseif ( ! empty( $field['options'][ $defaultk ] ) ) {
						$default_output .= $field['options'][ $defaultk ] . ', ';
					} elseif ( ! empty( $defaultv ) ) {
						$default_output .= $defaultv . ', ';
					}
				}
			}

			if ( ! empty( $default_output ) ) {
				$default_output = esc_html__( 'Default', 'redux-framework' ) . ': ' . substr( $default_output, 0, - 2 );
			}

			if ( ! empty( $default_output ) ) {
				$default_output = '<span class="showDefaults">' . esc_html( $default_output ) . '</span><br class="default_br" />';
			}

			return $default_output;
		}

		/**
		 * Return Section Menu HTML.
		 *
		 * @param int|string $k        Section index.
		 * @param array      $section  Section array.
		 * @param string     $suffix   Optional suffix.
		 * @param array      $sections Sections array.
		 *
		 * @return      string
		 * @since       3.1.5
		 * @access      public
		 */
		public function section_menu( $k, array $section, string $suffix = '', array $sections = array() ): string {
			$function_count = 0;

			$core = $this->core();

			$display = true;

			$section['class'] = isset( $section['class'] ) ? ' ' . $section['class'] : '';

			if ( isset( $_GET['page'] ) && $core->args['page_slug'] === $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification
				if ( isset( $section['panel'] ) && false === $section['panel'] ) {
					$display = false;
				}
			}

			if ( ! $display ) {
				return '';
			}

			if ( empty( $sections ) ) {
				$sections       = $core->sections;
				$function_count = $k;
			}

			$string = '';
			if ( ( ( isset( $core->args['icon_type'] ) && 'image' === $core->args['icon_type'] ) || ( isset( $section['icon_type'] ) && 'image' === $section['icon_type'] ) ) || ( isset( $section['icon'] ) && false !== strpos( $section['icon'], '/' ) ) ) {
				$icon = ( ! isset( $section['icon'] ) ) ? '' : '<img class="image_icon_type" src="' . esc_url( $section['icon'] ) . '" /> ';
			} else {
				if ( ! empty( $section['icon_class'] ) ) {
					$icon_class = ' ' . $section['icon_class'];
				} elseif ( ! empty( $core->args['default_icon_class'] ) ) {
					$icon_class = ' ' . $core->args['default_icon_class'];
				} else {
					$icon_class = '';
				}
				$icon = ( ! isset( $section['icon'] ) ) ? '<i class="el el-cog' . esc_attr( $icon_class ) . '"></i> ' : '<i class="' . esc_attr( $section['icon'] ) . esc_attr( $icon_class ) . '"></i> ';
			}
			if ( strpos( $icon, 'el-icon-' ) !== false ) {
				$icon = str_replace( 'el-icon-', 'el el-', $icon );
			}

			$hide_section = '';
			if ( isset( $section['hidden'] ) ) {
				$hide_section = ( true === $section['hidden'] ) ? ' hidden ' : '';
			}

			$can_be_subsection = $k > 0 && ( ! isset( $sections[ ( $k ) ]['type'] ) || 'divide' !== $sections[ ( $k ) ]['type'] );

			if ( ! $can_be_subsection && isset( $section['subsection'] ) && true === $section['subsection'] ) {
				unset( $section['subsection'] );
			}

			if ( isset( $section['type'] ) && 'divide' === $section['type'] ) {
				$string .= '<li class="divide' . esc_attr( $section['class'] ) . '">&nbsp;</li>';
			} elseif ( ! isset( $section['subsection'] ) || true !== $section['subsection'] ) {
				if ( ! isset( $core->args['pro']['flyout_submenus'] ) ) {
					$core->args['pro']['flyout_submenus'] = false;
				}

				$subsections        = isset( $sections[ ( $k + 1 ) ] ) && isset( $sections[ ( $k + 1 ) ]['subsection'] ) && true === $sections[ ( $k + 1 ) ]['subsection'];
				$subsections_class  = $subsections ? ' hasSubSections' : '';
				$subsections_class .= ( empty( $section['fields'] ) ) ? ' empty_section' : '';
				$rotate             = true === $core->args['pro']['flyout_submenus'] ? ' el-rotate' : '';
				$extra_icon         = $subsections ? '<span class="extraIconSubsections"><i class="el el-chevron-down' . $rotate . '">&nbsp;</i></span>' : '';
				$string            .= '<li id="' . esc_attr( $k . $suffix ) . '_section_group_li" class="redux-group-tab-link-li' . esc_attr( $hide_section ) . esc_attr( $section['class'] ) . esc_attr( $subsections_class ) . '">';
				$string            .= '<a href="javascript:void(0);" id="' . esc_attr( $k . $suffix ) . '_section_group_li_a" class="redux-group-tab-link-a" data-key="' . esc_attr( $k ) . '" data-rel="' . esc_attr( $k . $suffix ) . '">' . $extra_icon . $icon . '<span class="group_title">' . wp_kses_post( $section['title'] ) . '</span></a>';

				$next_k = $k;

				// Make sure you can make this a subsection.
				if ( $subsections ) {
					$string .= '<ul id="' . esc_attr( $next_k . $suffix ) . '_section_group_li_subsections" class="subsection">';

					$do_loop = true;

					while ( $do_loop ) {
						$next_k ++;
						$function_count++;

						$display = true;

						if ( isset( $_GET['page'] ) && $core->args['page_slug'] === $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification
							if ( isset( $sections[ $next_k ]['panel'] ) && false === $sections[ $next_k ]['panel'] ) {
								$display = false;
							}
						}

						if ( count( $sections ) < $function_count || ! isset( $sections[ $next_k ] ) || ! isset( $sections[ $next_k ]['subsection'] ) || true !== $sections[ $next_k ]['subsection'] ) {
							$do_loop = false;
						} else {
							if ( ! $display ) {
								continue;
							}

							$hide_sub = '';
							if ( isset( $sections[ $next_k ]['hidden'] ) ) {
								$hide_sub = ( true === $sections[ $next_k ]['hidden'] ) ? ' hidden ' : '';
							}

							if ( ( isset( $core->args['icon_type'] ) && 'image' === $core->args['icon_type'] ) || ( isset( $sections[ $next_k ]['icon_type'] ) && 'image' === $sections[ $next_k ]['icon_type'] ) ) {
								$icon = ( ! isset( $sections[ $next_k ]['icon'] ) ) ? '' : '<img class="image_icon_type" src="' . esc_url( $sections[ $next_k ]['icon'] ) . '" /> ';
							} else {
								if ( ! empty( $sections[ $next_k ]['icon_class'] ) ) {
									$icon_class = ' ' . $sections[ $next_k ]['icon_class'];
								} elseif ( ! empty( $core->args['default_icon_class'] ) ) {
									$icon_class = ' ' . $core->args['default_icon_class'];
								} else {
									$icon_class = '';
								}
								$icon = ( ! isset( $sections[ $next_k ]['icon'] ) ) ? '' : '<i class="' . esc_attr( $sections[ $next_k ]['icon'] ) . esc_attr( $icon_class ) . '"></i> ';
							}
							if ( strpos( $icon, 'el-icon-' ) !== false ) {
								$icon = str_replace( 'el-icon-', 'el el-', $icon );
							}

							$sections[ $next_k ]['class'] = $sections[ $next_k ]['class'] ?? '';
							$section[ $next_k ]['class']  = $section[ $next_k ]['class'] ?? $sections[ $next_k ]['class'];
							$string                      .= '<li id="' . esc_attr( $next_k . $suffix ) . '_section_group_li" class="redux-group-tab-link-li ' . esc_attr( $hide_sub ) . esc_attr( $section[ $next_k ]['class'] ) . ( $icon ? ' hasIcon' : '' ) . '">';
							$string                      .= '<a href="javascript:void(0);" id="' . esc_attr( $next_k . $suffix ) . '_section_group_li_a" class="redux-group-tab-link-a" data-key="' . esc_attr( $next_k ) . '" data-rel="' . esc_attr( $next_k . $suffix ) . '">' . $icon . '<span class="group_title">' . wp_kses_post( $sections[ $next_k ]['title'] ) . '</span></a>';
							$string                      .= '</li>';
						}
					}

					$string .= '</ul>';
				}

				$string .= '</li>';
			}

			return $string;
		}
	}
}
