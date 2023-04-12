<?php
/**
 * Redux User Meta Extension Class
 *
 * @package Redux Pro
 * @author  Dovy Paukstys
 * @class   Redux_Extension_Users
 * @version 4.4.1
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Extension_Users' ) ) {

	/**
	 * Main Redux_Extension_Users class
	 *
	 * @since       1.0.0
	 */
	class Redux_Extension_Users extends Redux_Extension_Abstract {

		/**
		 * Extension version.
		 *
		 * @var string
		 */
		public static $version = '4.4.1';

		/**
		 * Extension friendly name.
		 *
		 * @var string
		 */
		public $extension_name = 'Users';


		/**
		 * Profiles array.
		 *
		 * @var array
		 */
		public $profiles = array();

		/**
		 * User roles array.
		 *
		 * @var array
		 */
		public $users_roles = array();

		/**
		 * User role array.
		 *
		 * @var array
		 */
		public $users_role = array();

		/**
		 * Sections array.
		 *
		 * @var array
		 */
		public $sections = array();

		/**
		 * Original args array.
		 *
		 * @var array
		 */
		public $orig_args = array();

		/**
		 * Output array.
		 *
		 * @var array
		 */
		public $output = array();

		/**
		 * Options array.
		 *
		 * @var array
		 */
		public $options = array();

		/**
		 * Parent options array.
		 *
		 * @var array
		 */
		public $parent_options = array();

		/**
		 * Parent defaults.
		 *
		 * @var array
		 */
		public $parent_defaults = array();

		/**
		 * Profile fields array.
		 *
		 * @var array
		 */
		public $profile_fields = array();

		/**
		 * WP Links array.
		 *
		 * @var array
		 */
		public $wp_links = array();

		/**
		 * Options defaults.
		 *
		 * @var array
		 */
		public $options_defaults = array();

		/**
		 * Localize data array.
		 *
		 * @var array
		 */
		public $localize_data = array();

		/**
		 * To replace array.
		 *
		 * @var array
		 */
		public $to_replace = array();

		/**
		 * Meta array.
		 *
		 * @var array
		 */
		public $meta = array();

		/**
		 * Base URL.
		 *
		 * @var string
		 */
		public $base_url;

		/**
		 * Array of page names.
		 *
		 * @var array
		 */
		public $pagenows;

		/**
		 * Notices array.
		 *
		 * @var array
		 */
		public $notices;

		/**
		 * Redux_Extension_Users constructor.
		 *
		 * @param object $parent ReduxFramework pointer.
		 */
		public function __construct( $parent ) {
			global $pagenow;

			parent::__construct( $parent, __FILE__ );

			$this->parent = $parent;

			$this->add_field( 'users' );
			$this->parent->extensions['users'] = $this;

			$this->pagenows = array( 'user-new.php', 'profile.php', 'user-edit.php' );

			add_action( 'admin_notices', array( $this, 'meta_profiles_show_errors' ), 0 );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 20 );

			if ( is_admin() && in_array( $pagenow, $this->pagenows, true ) ) {
				$this->init();

				add_action( 'personal_options_update', array( $this, 'user_meta_save' ) );
				add_action( 'edit_user_profile_update', array( $this, 'user_meta_save' ) );

			}
		}

		/**
		 * Add term classes.
		 *
		 * @param array $classes Classes.
		 *
		 * @return array
		 */
		public function add_term_classes( array $classes ): array {
			$classes[] = 'redux-users';
			$classes[] = 'redux-' . $this->parent->args['opt_name'];

			if ( '' !== $this->parent->args['class'] ) {
				$classes[] = $this->parent->args['class'];
			}

			return $classes;
		}

		/**
		 * Add hide term class.
		 *
		 * @param array $classes Classes.
		 *
		 * @return array
		 */
		public function add_term_hide_class( array $classes ): array {
			$classes[] = 'hide';

			return $classes;
		}

		/**
		 * Init.
		 */
		public function init() {
			global $pagenow;

			// phpcs:ignore WordPress.Security.NonceVerification
			$user       = isset( $_GET['user_id'] ) ? sanitize_text_field( wp_unslash( $_GET['user_id'] ) ) : get_current_user_id();
			$this->meta = Redux_Users::get_user_meta( array( 'user' => $user ) );

			$this->parent->options = $this->meta;

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$this->profiles = apply_filters( 'redux/users/' . $this->parent->args['opt_name'] . '/profiles', $this->profiles, $this->parent->args['opt_name'] );

			if ( empty( $this->profiles ) && class_exists( 'Redux_Users' ) ) {
				$this->profiles = Redux_Users::construct_profiles( $this->parent->args['opt_name'] );
			}

			if ( empty( $this->profiles ) || ! is_array( $this->profiles ) ) {
				return;
			}

			$this->base_url = ( is_ssl() ? 'https://' : 'http://' ) . Redux_Core::$server['HTTP_HOST'] . Redux_Core::$server['REQUEST_URI'];

			foreach ( $this->profiles as $bk => $profile ) {
				$profile['roles'] = isset( $profile['roles'] ) ? (array) $profile['roles'] : array( 'read' );

				if ( ! empty( $profile['sections'] ) ) {
					$this->sections = $profile['sections'];

					$this->users_roles = wp_parse_args( $this->users_roles, $profile['roles'] );

					// Checking to override the parent variables.
					$add_field = false;

					foreach ( $profile['roles'] as $role ) {
						if ( $this->users_role === $role ) {
							$add_field = true;
						}
					}

					// Replacing all the fields.
					if ( $add_field || ( ( is_admin() && in_array( $pagenow, $this->pagenows, true ) ) || ( ! is_admin() ) ) ) {
						$profile_id = 'redux-' . $this->parent->args['opt_name'] . '-metaterm-' . $profile['id'];

						if ( isset( $profile['page_template'] ) && 'page' === $this->users_role ) {
							if ( ! is_array( $profile['page_template'] ) ) {
								$profile['page_template'] = array( $profile['page_template'] );
							}

							$this->wp_links[ $profile_id ]['page_template'] = isset( $this->wp_links[ $profile_id ]['page_template'] ) ? wp_parse_args( $this->wp_links[ $profile_id ]['page_template'], $profile['page_template'] ) : $profile['page_template'];
						}

						if ( isset( $profile['post_format'] ) && ( in_array( $this->users_role, $this->users_roles, true ) || '' === $this->users_role ) ) {
							if ( ! is_array( $profile['post_format'] ) ) {
								$profile['post_format'] = array( $profile['post_format'] );
							}

							$this->wp_links[ $profile_id ]['post_format'] = isset( $this->wp_links[ $profile_id ]['post_format'] ) ? wp_parse_args( $this->wp_links[ $profile_id ]['post_format'], $profile['post_format'] ) : $profile['post_format'];
						}

						foreach ( $profile['sections'] as $sk => $section ) {
							if ( ! empty( $section['fields'] ) ) {
								foreach ( $section['fields'] as $fk => $field ) {
									if ( ! isset( $field['class'] ) ) {
										$field['class'] = '';

										$this->profiles[ $bk ]['sections'][ $sk ]['fields'][ $fk ] = $field;
									}

									$this->parent->required_class->check_dependencies( $field );

									/** phpcs:ignore
									 * if ( stripos( $field['class'], 'redux-field-init' ) === 0 ) {
									 * $field['class'] = trim( $field['class'] . ' redux-field-init' );
									 * }
									 */

									if ( $add_field || ( ( is_admin() && in_array( $pagenow, $this->pagenows, true ) ) || ( ! is_admin() ) ) ) {
										if ( empty( $field['id'] ) ) {
											continue;
										}

										if ( isset( $field['default'] ) ) {
											$this->options_defaults[ $field['id'] ] = $field['default'];
										} else {
											$this->options_defaults[ $field['id'] ] = $this->field_default( $field );
										}

										foreach ( $profile['roles'] as $type ) {
											$this->profile_fields[ $type ][ $field['id'] ] = 1;
										}

										if ( ! empty( $field['output'] ) ) {
											$this->output[ $field['id'] ] = isset( $this->output[ $field['id'] ] ) ? array_merge( $field['output'], $this->output[ $field['id'] ] ) : $field['output'];
										}

										// Detect what field types are being used.
										if ( ! isset( $this->parent->fields[ $field['type'] ][ $field['id'] ] ) ) {
											$this->parent->fields[ $field['type'] ][ $field['id'] ] = 1;
										} else {
											$this->parent->fields[ $field['type'] ] = array( $field['id'] => 1 );
										}

										if ( isset( $this->options_defaults[ $field['id'] ] ) ) {
											$this->to_replace[ $field['id'] ] = $field;
										}
									}

									if ( ! isset( $this->parent->options[ $field['id'] ] ) ) {
										$this->parent->sections[ ( count( $this->parent->sections ) - 1 ) ]['fields'][] = $field;
									}

									if ( ! isset( $this->meta[ $field['id'] ] ) ) {
										$this->meta[ $field['id'] ] = $this->options_defaults[ $field['id'] ];
									}

									// Only override if it exists, and it's not the default.
									/** If ( isset( $this->meta[ $field['id'] ] ) && isset( $field['default'] ) && $this->meta[ $field['id'] ] === $field['default'] ) {
									 *    // unset($this->meta[$this->tag_id][$field['id']]);
									 *  }
									 */
								}
							}
						}
					}
				}
			}

			$this->parent_options = '';

			if ( ! empty( $this->to_replace ) ) {
				foreach ( $this->to_replace as $id => $field ) {
					add_filter( "redux/options/{$this->parent->args['opt_name']}/field/$id/register", array( $this, 'replace_field' ) );
				}
			}

			add_filter( "redux/options/{$this->parent->args['opt_name']}/options", array( $this, 'override_options' ) );

			if ( is_admin() && in_array( $pagenow, $this->pagenows, true ) ) {
				$priority = $this->parent->args['user_priority'] ?? 3;

				add_action( 'show_user_profile', array( $this, 'add_profiles' ), $priority );
				add_action( 'edit_user_profile', array( $this, 'add_profiles' ), $priority );
				add_action( 'user_new_form', array( $this, 'add_profiles' ), $priority );
			}
		}

		/**
		 * Replace field.
		 *
		 * @param array $field Field array.
		 *
		 * @return mixed
		 */
		public function replace_field( array $field ) {
			if ( isset( $this->to_replace[ $field['id'] ] ) ) {
				$field = $this->to_replace[ $field['id'] ];
			}

			return $field;
		}

		/**
		 * Can CSS Output override.
		 *
		 * @param array $field Field array.
		 *
		 * @return array
		 */
		public function override_can_output_css( array $field ): array {
			if ( isset( $this->output[ $field['id'] ] ) ) {
				$field['force_output'] = true;
			}

			return $field;
		}

		/**
		 * Output CSS.
		 *
		 * @param array $field Field array.
		 *
		 * @return array
		 */
		public function output_css( array $field ): array {
			if ( isset( $this->output[ $field['id'] ] ) ) {
				$field['output'] = $this->output[ $field['id'] ];
			}

			return $field;
		}

		/**
		 * Make sure the defaults are the defaults
		 *
		 * @param array $options Option array.
		 *
		 * @return array
		 */
		public function override_options( array $options ): array {
			$this->parent->options_class->default_values();
			$this->parent_defaults = $this->parent->options_defaults;

			if ( empty( $this->meta ) ) {
				// phpcs:ignore WordPress.Security.NonceVerification
				$user       = isset( $_GET['user_id'] ) ? sanitize_text_field( wp_unslash( $_GET['user_id'] ) ) : get_current_user_id();
				$this->meta = Redux_Users::get_user_meta( array( 'user' => $user ) );
			}

			$data = wp_parse_args( $this->meta, $this->options_defaults );

			foreach ( $data as $key => $value ) {
				if ( isset( $meta[ $key ] ) && '' !== $meta[ $key ] ) {
					$data[ $key ] = $meta[ $key ];
					continue;
				}

				if ( isset( $options[ $key ] ) ) {
					$data[ $key ] = $options[ $key ];
				}
			}

			$this->parent->options_defaults = wp_parse_args( $this->options_defaults, $this->parent->options_defaults );

			return wp_parse_args( $data, $options );
		}

		/**
		 * Sanitize Query.
		 *
		 * @param array $queries Queries.
		 *
		 * @return array
		 */
		public function sanitize_query( array $queries ): array {
			$clean_queries = array();

			foreach ( $queries as $key => $query ) {
				if ( 'relation' === $key ) {
					$relation = $query;

				} elseif ( ! is_array( $query ) ) {
					$clean_queries[ $key ]['values'] = array( $query );

					// First-order clause.
				} elseif ( $this->is_first_order_clause( $query ) ) {
					print( 'h3' );
					if ( isset( $query['value'] ) && array() === $query['value'] ) {
						unset( $query['value'] );
					}

					$clean_queries[ $key ] = $query;

					// Otherwise, it's a nested query, so we recurse.
				} else {
					print( 'h4' );
					$cleaned_query = $this->sanitize_query( $query );

					if ( ! empty( $cleaned_query ) ) {
						$clean_queries[ $key ] = $cleaned_query;
					}
				}
			}

			if ( empty( $clean_queries ) ) {
				return $clean_queries;
			}

			// Sanitize the 'relation' key provided in the query.
			if ( isset( $relation ) && 'OR' === strtoupper( $relation ) ) {
				$clean_queries['relation'] = 'OR';

				/*
				 * If there is only a single clause, call the relation 'OR'.
				 * This value will not actually be used to join clauses, but it
				 * simplifies the logic around combining key-only queries.
				 */
			} elseif ( 1 === count( $clean_queries ) ) {
				$clean_queries['relation'] = 'OR';

				// Default to AND.
			} else {
				$clean_queries['relation'] = 'AND';
			}

			return $clean_queries;
		}

		/**
		 * Is first order clause.
		 *
		 * @param array $query Query array.
		 *
		 * @return bool
		 */
		protected function is_first_order_clause( array $query ): bool {
			return isset( $query['key'] ) || isset( $query['value'] );
		}

		/**
		 * Support file and field enqueue.
		 */
		public function enqueue() {
			global $pagenow;

			if ( in_array( $pagenow, $this->pagenows, true ) ) {

				if ( 'user-new.php' === $pagenow ) {
					$this->parent->args['disable_save_warn'] = true;
				}

				$this->parent->transients       = get_transient( $this->parent->args['opt_name'] . '-transients-users' );
				$this->parent->transients_check = $this->parent->transients;

				if ( isset( $this->parent->transients['notices'] ) ) {
					$this->notices                              = $this->parent->transients['notices'];
					$this->parent->transients['last_save_mode'] = 'users';
				}

				delete_transient( $this->parent->args['opt_name'] . '-transients-users' );
				$this->parent->enqueue_class->init();

				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				do_action( "redux/users/{$this->parent->args['opt_name']}/enqueue" );

				/**
				 * Redux users CSS
				 * filter 'redux/page/{opt_name}/enqueue/redux-extension-users-css'
				 */
				if ( true === $this->parent->args['dev_mode'] ) {
					wp_enqueue_style(
						'redux-extension-users-css',
						// phpcs:ignore WordPress.NamingConventions.ValidHookName
						apply_filters( "redux/users/{$this->parent->args['opt_name']}/enqueue/redux-extension-users-css", $this->extension_url . 'redux-extension-users.css' ),
						array( 'redux-admin-css' ),
						self::$version
					);
				}

				/**
				 * Redux users JS
				 * filter 'redux/page/{opt_name}/enqueue/redux-extension-users-js
				 */
				wp_enqueue_script(
					'redux-extension-users-js',
					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					apply_filters( "redux/users/{$this->parent->args['opt_name']}/enqueue/redux-extension-users-js", $this->extension_url . 'redux-extension-users' . Redux_Functions::is_min() . '.js' ),
					array( 'jquery', 'redux-js' ),
					self::$version,
					true
				);

				// Values used by the javascript.
				wp_localize_script( 'redux-extension-users-js', 'reduxUsers', $this->users_roles );

			}
		}


		/**
		 * DEPRECATED
		 */
		public function default_values() {
			if ( ! empty( $this->profiles ) && empty( $this->options_defaults ) ) {
				foreach ( $this->profiles as $key => $profile ) {
					if ( empty( $profile['sections'] ) ) {
						continue;
					}

					// fill the cache.
					foreach ( $profile['sections'] as $sk => $section ) {
						if ( ! isset( $section['id'] ) ) {
							if ( ! is_numeric( $sk ) || ! isset( $section['title'] ) ) {
								$section['id'] = $sk;
							} else {
								$section['id'] = sanitize_title( $section['title'], $sk );
							}
							$this->profiles[ $key ]['sections'][ $sk ] = $section;
						}
						if ( isset( $section['fields'] ) ) {
							foreach ( $section['fields'] as $k => $field ) {
								if ( empty( $field['id'] ) && empty( $field['type'] ) ) {
									continue;
								}

								if ( $field['type'] === 'ace_editor' && isset( $field['options'] ) ) {
									$this->profiles[ $key ]['sections'][ $sk ]['fields'][ $k ]['args'] = $field['options'];
									unset( $this->profiles[ $key ]['sections'][ $sk ]['fields'][ $k ]['options'] );
								}

								if ( 'section' === $field['type'] && isset( $field['indent'] ) && true === $field['indent'] ) {
									$field['class']  = $field['class'] ?? '';
									$field['class'] .= 'redux-section-indent-start';

									$this->profiles[ $key ]['sections'][ $sk ]['fields'][ $k ] = $field;
								}

								$this->parent->options_defaults_class->field_default_values( $this->parent->args['opt_name'], $field );
							}
						}
					}
				}
			}

			if ( empty( $this->meta ) ) {
				// phpcs:ignore WordPress.Security.NonceVerification
				$user       = isset( $_GET['user_id'] ) ? sanitize_text_field( wp_unslash( $_GET['user_id'] ) ) : get_current_user_id();
				$this->meta = Redux_Users::get_user_meta( array( 'user' => $user ) );
			}
		}

		/**
		 * Add Profiles.
		 */
		public function add_profiles() {
			if ( empty( $this->profiles ) || ! is_array( $this->profiles ) ) {
				return;
			}

			foreach ( $this->profiles as $key => $profile ) {
				if ( empty( $profile['sections'] ) ) {
					continue;
				}

				$defaults = array(
					'id'         => "$key",
					'section_id' => $key,
					'profiles'   => array(),
				);

				$profile = wp_parse_args( $profile, $defaults );

				if ( isset( $profile['title'] ) ) {
					$title = $profile['title'];
				} else {
					if ( isset( $profile['sections'] ) && 1 === count( $profile['sections'] ) && isset( $profile['sections'][0]['fields'] ) && 1 === count( $profile['sections'][0]['fields'] ) && isset( $profile['sections'][0]['fields'][0]['title'] ) ) {

						// If only one field in this term.
						$title = $profile['sections'][0]['fields'][0]['title'];
					} else {
						$title = __( 'Options', 'redux-framework' );
					}
				}

				// Override the parent args on a meta-term level.
				if ( empty( $this->orig_args ) ) {
					$this->orig_args = $this->parent->args;
				}

				if ( isset( $profile['args'] ) ) {
					$this->parent->args = wp_parse_args( $profile['args'], $this->orig_args );
				} elseif ( $this->parent->args !== $this->orig_args ) {
					$this->parent->args = $this->orig_args;
				}

				if ( ! isset( $profile['class'] ) ) {
					$profile['class'] = array();
				}

				if ( ! empty( $profile['class'] ) ) {
					if ( ! is_array( $profile['class'] ) ) {
						$profile['class'] = array( $profile['class'] );
					}
				}

				$profile['class'] = $this->add_term_classes( $profile['class'] );

				if ( isset( $profile['post_format'] ) ) {
					$profile['class'] = $this->add_term_hide_class( $profile['class'] );
				}

				global $pagenow;
				if ( strpos( $pagenow, 'edit-' ) !== false ) {

					$profile['style']   = 'wp';
					$profile['class'][] = ' edit-page';
					$profile['class'][] = ' redux-wp-style';
				}

				$this->generate_profiles( array( 'args' => $profile ) );

				if ( ! empty( $profile['roles'] ) ) {
					foreach ( $profile['roles'] as $profiletype ) {
						if ( sanitize_text_field( wp_unslash( $_GET['users'] ) ) !== $profiletype ) { // phpcs:ignore WordPress.Security.NonceVerification
							continue;
						}
					}
				}
			}
		}

		/**
		 * Field Default.
		 *
		 * @param array $field_id ID.
		 *
		 * @return mixed|string
		 */
		public function field_default( array $field_id ) {
			if ( ! isset( $this->parent->options_defaults ) ) {
				$this->parent->options_defaults = $this->parent->default_values();
			}

			if ( ! isset( $this->parent->options ) || empty( $this->parent->options ) ) {
				$this->parent->get_options();
			}

			$this->options = $this->parent->options;

			if ( isset( $this->parent->options[ $field_id['id'] ] ) && isset( $this->parent->options_defaults[ $field_id['id'] ] ) && $this->parent->options[ $field_id['id'] ] !== $this->parent->options_defaults[ $field_id['id'] ] ) {
				return $this->parent->options[ $field_id['id'] ];
			} else {
				if ( empty( $this->options_defaults ) ) {
					$this->default_values(); // fill cache.
				}

				$data = '';
				if ( ! empty( $this->options_defaults ) ) {
					$data = $this->options_defaults[ $field_id['id'] ] ?? '';
				}

				if ( empty( $data ) && isset( $this->parent->options_defaults[ $field_id['id'] ] ) ) {
					$data = $this->parent->options_defaults[ $field_id['id'] ] ?? '';
				}

				return $data;
			}
		}

		/**
		 * Function to get and cache the post meta.
		 *
		 * @param string $id ID.
		 *
		 * @return array
		 */
		private function get_meta( string $id ): array {
			if ( ! isset( $this->meta[ $id ] ) ) {
				$this->meta[ $id ] = array();
				$o_data            = get_post_meta( $id );

				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				$o_data = apply_filters( "redux/users/{$this->parent->args['opt_name']}/get_meta", $o_data );

				if ( ! empty( $o_data ) ) {
					foreach ( $o_data as $key => $value ) {
						if ( count( $value ) === 1 ) {
							$this->meta[ $id ][ $key ] = maybe_unserialize( $value[0] );
						} else {
							$new_value = array_map( 'maybe_unserialize', $value );

							$this->meta[ $id ][ $key ] = $new_value[0];
						}
					}
				}

				if ( isset( $this->meta[ $id ][ $this->parent->args['opt_name'] ] ) ) {
					$data = maybe_unserialize( $this->meta[ $id ][ $this->parent->args['opt_name'] ] );

					foreach ( $data as $key => $value ) {
						$this->meta[ $id ][ $key ] = $value;
						update_post_meta( $id, $key, $value );
					}

					unset( $this->meta[ $id ][ $this->parent->args['opt_name'] ] );

					delete_post_meta( $id, $this->parent->args['opt_name'] );
				}
			}

			return $this->meta[ $id ];
		}

		/**
		 * Get values.
		 *
		 * @param mixed  $the_post Post array.
		 * @param string $meta_key Meta key.
		 * @param string $def_val  Default value.
		 *
		 * @return mixed|string
		 */
		public function get_values( $the_post, string $meta_key = '', string $def_val = '' ) {

			// Override these values if they differ from the admin panel defaults.  ;).
			if ( isset( $the_post->users_role ) && in_array( $the_post->users_role, $this->users_roles, true ) ) {
				if ( isset( $this->users_role_values[ $the_post->users_role ] ) ) {
					$meta = $this->profile_fields[ $the_post->users_role ];
				} else {
					$defaults = array();
					if ( ! empty( $this->profile_fields[ $the_post->users_role ] ) ) {
						foreach ( $this->profile_fields[ $the_post->users_role ] as $key => $null ) {
							if ( isset( $this->options_defaults[ $key ] ) ) {
								$defaults[ $key ] = $this->options_defaults[ $key ];
							}
						}
					}

					$meta = wp_parse_args( $this->get_meta( $the_post->ID ), $defaults );

					$this->profile_fields[ $the_post->users_role ] = $meta;
				}

				if ( ! empty( $meta_key ) ) {
					if ( ! isset( $meta[ $meta_key ] ) ) {
						$meta[ $meta_key ] = $def_val;
					}

					return $meta[ $meta_key ];
				} else {
					return $meta;
				}
			}

			return $def_val;
		}

		/**
		 * Check Edit Visibility.
		 *
		 * @param array $array Array.
		 *
		 * @return bool
		 */
		private function check_edit_visibility( array $array = array() ): bool {
			global $pagenow;

			// Edit page visibility.
			if ( strpos( $pagenow, 'edit-' ) !== false ) {
				if ( isset( $array['fields'] ) ) {
					foreach ( $array['fields'] as $field ) {
						if ( in_array( $field['id'], $this->parent->fields_hidden, true ) ) {
							// Not visible.
						} else {
							if ( isset( $field['add_visibility'] ) && $field['add_visibility'] ) {
								return true;
							}
						}
					}

					return false;
				}
				if ( isset( $array['add_visibility'] ) && $array['add_visibility'] ) {
					return true;
				}

				return false;
			}

			return true;
		}

		/**
		 * Generate Profiles.
		 *
		 * @param array $metaterm Meta term.
		 */
		private function generate_profiles( array $metaterm ) {
			if ( ! empty( $metaterm['args']['permissions'] ) && ! Redux_Helpers::current_user_can( $metaterm['args']['permissions'] ) ) {
				return;
			}

			if ( isset( $metaterm['args']['style'] ) && in_array( $metaterm['args']['style'], array( 'wp', 'wordpress' ), true ) ) {
				$container_class             = 'redux-wp-style';
				$metaterm['args']['sidebar'] = false;
			} elseif ( isset( $metaterm['args']['sidebar'] ) && ! $metaterm['args']['sidebar'] ) {
				$container_class = 'redux-no-sections';
			} else {
				$container_class = 'redux-has-sections';
			}

			$class = implode( ' ', $metaterm['args']['class'] );
			echo '<div class=' . esc_attr( $class ) . '>';

			$sections = $metaterm['args']['sections'];

			wp_nonce_field( 'redux_users_meta_nonce', 'redux_users_meta_nonce' );

			wp_dequeue_script( 'json-view-js' );

			$sidebar = true;
			if ( count( $sections ) === 1 || ( isset( $metaterm['args']['sidebar'] ) && false === $metaterm['args']['sidebar'] ) ) {
				$sidebar = false; // Show the section dividers or not.
			}
			?>
			<div data-opt-name="<?php echo esc_attr( $this->parent->args['opt_name'] ); ?>"
				class="redux-container <?php echo( esc_attr( $container_class ) ); ?> redux-term redux-box-normal redux-term-normal">
				<div class="redux-notices">
					<?php if ( $sidebar ) { ?>
						<div class="saved_notice admin-notice notice-blue" style="display:none;">
							<?php // phpcs:ignore WordPress.NamingConventions.ValidHookName ?>
							<strong><?php echo esc_html( apply_filters( "redux-imported-text-{$this->parent->args['opt_name']}", esc_html__( 'Settings Imported!', 'redux-framework' ) ) ); ?></strong>
						</div>
						<div class="redux-save-warn notice-yellow">
							<?php // phpcs:ignore WordPress.NamingConventions.ValidHookName ?>
							<strong><?php echo esc_html( apply_filters( "redux-changed-text-{$this->parent->args['opt_name']}", esc_html__( 'Settings have changed, you should save them!', 'redux-framework' ) ) ); ?></strong>
						</div>
					<?php } ?>
					<div class="redux-field-errors notice-red">
						<strong> <span></span> <?php echo esc_html__( 'error(s) were found!', 'redux-framework' ); ?>
						</strong>
					</div>
					<div class="redux-field-warnings notice-yellow">
						<strong> <span></span> <?php echo esc_html__( 'warning(s) were found!', 'redux-framework' ); ?>
						</strong>
					</div>
				</div>
				<?php
				echo '<a href="javascript:void(0);" class="expand_options hide" style="display:none;">' . esc_html__( 'Expand', 'redux-framework' ) . '</a>';
				if ( $sidebar ) {
					?>
					<div class="redux-sidebar">
						<ul class="redux-group-menu">
							<?php
							foreach ( $sections as $s_key => $section ) {
								if ( ! empty( $section['permissions'] ) && ! Redux_Helpers::current_user_can( $section['permissions'] ) ) {
									continue;
								}

								// phpcs:ignore WordPress.Security.EscapeOutput
								echo $this->parent->section_menu( $s_key, $section, '_' . $metaterm['args']['id'], $sections );
							}
							?>
						</ul>
					</div>
				<?php } ?>
				<div class="redux-main">
					<?php

					foreach ( $sections as $s_key => $section ) {
						if ( ! $this->check_edit_visibility( $section ) ) {
							continue;
						}

						if ( ! empty( $section['permissions'] ) && ! Redux_Helpers::current_user_can( $section['permissions'] ) ) {
							continue;
						}

						if ( ! empty( $section['fields'] ) ) {
							if ( isset( $section['args'] ) ) {
								$this->parent->args = wp_parse_args( $section['args'], $this->orig_args );
							} elseif ( $this->parent->args !== $this->orig_args ) {
								$this->parent->args = $this->orig_args;
							}

							$hide             = $sidebar ? '' : ' display-group';
							$section['class'] = isset( $section['class'] ) ? " {$section['class']}" : '';

							// phpcs:ignore WordPress.Security.EscapeOutput
							echo "<div id='{$s_key}_{$metaterm['args']['id']}_section_group' class='redux-group-tab{$section['class']} redux_metaterm_panel$hide'>";

							if ( ! empty( $section['title'] ) ) {
								echo '<h3 class="redux-section-title">' . wp_kses_post( $section['title'] ) . '</h3>';
							}

							if ( ! empty( $section['desc'] ) ) {
								echo '<div class="redux-section-desc">' . wp_kses_post( $section['desc'] ) . '</div>';
							}

							echo '<table class="form-table"><tbody>';

							foreach ( $section['fields'] as $field ) {
								if ( ! $this->check_edit_visibility( $field ) ) {
									continue;
								}

								if ( ! empty( $field['permissions'] ) && ! Redux_Helpers::current_user_can( $field['permissions'] ) ) {
									continue;
								}

								$field['name'] = $this->parent->args['opt_name'] . '[' . $field['id'] . ']';

								$is_hidden = false;
								$ex_style  = '';
								if ( isset( $field['hidden'] ) && $field['hidden'] ) {
									$is_hidden = true;
									$ex_style  = ' style="border-bottom: none;"';
								}

								echo '<tr valign="top"' . $ex_style . '>'; // phpcs:ignore WordPress.Security.EscapeOutput

								$th = $this->parent->render_class->get_header_html( $field );

								if ( $is_hidden ) {
									$str_pos = strpos( $th, 'redux_field_th' );

									if ( $str_pos > - 1 ) {
										$th = str_replace( 'redux_field_th', 'redux_field_th hide', $th );
									}
								}

								if ( $sidebar ) {
									if ( ! ( isset( $metaterm['args']['sections'] ) && count( $metaterm['args']['sections'] ) === 1 && isset( $metaterm['args']['sections'][0]['fields'] ) && count( $metaterm['args']['sections'][0]['fields'] ) === 1 ) && isset( $field['title'] ) ) {
										echo '<th scope="row">';
										if ( ! empty( $th ) ) {
											echo $th; //phpcs:ignore WordPress.Security.EscapeOutput
										}
										echo '</th>';
										echo '<td>';
									}
								} else {
									echo '<td>' . $th; //phpcs:ignore WordPress.Security.EscapeOutput
								}

								if ( 'section' === $field['type'] && true === $field['indent'] ) {
									$field['class']  = $field['class'] ?? '';
									$field['class'] .= 'redux-section-indent-start';
								}

								if ( ! isset( $this->meta[ $field['id'] ] ) ) {
									$this->meta[ $field['id'] ] = '';
								}

								$this->parent->render_class->field_input( $field, $this->meta[ $field['id'] ] );
								echo '</td></tr>';
							}
							echo '</tbody></table>';
							echo '</div>';
						}
					}
					?>
				</div>
				<div class="clear"></div>
			</div>

			<?php
		}

		/**
		 * Save meta profiles
		 * Runs when a post is saved and does an action which to write panel save scripts can hook into.
		 *
		 * @access public
		 *
		 * @param mixed $user_id User ID.
		 *
		 * @return boolean
		 */
		public function user_meta_save( $user_id = 0 ): bool {
			if ( ! current_user_can( 'edit_user', $user_id ) ) {
				return false;
			}

			// Check if our nonce is set.
			if ( ! isset( $_POST['redux_users_meta_nonce'] ) || ! isset( $_POST[ $this->parent->args['opt_name'] ] ) ) {
				return false;
			}

			// Verify that the nonce is valid.
			if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['redux_users_meta_nonce'] ) ), 'redux_users_meta_nonce' ) ) {
				return false;
			}

			$check_user_id = sanitize_text_field( wp_unslash( $_POST['checkuser_id'] ?? 1 ) );

			$user       = sanitize_text_field( wp_unslash( $_GET['user_id'] ?? get_current_user_id() ) );
			$this->meta = Redux_Users::get_user_meta( array( 'user' => $user ) );

			$to_save    = array();
			$to_compare = array();
			$to_delete  = array();

			$field_args = Redux_Users::$fields[ $this->parent->args['opt_name'] ];

			foreach ( $_POST[ $this->parent->args['opt_name'] ] as $key => $value ) { // phpcs:ignore WordPress.Security
				$key = sanitize_text_field( wp_unslash( $key ) );

				if ( ! empty( $field_args[ $key ]['permissions'] ) ) {
					foreach ( (array) $field_args[ $key ]['permissions'] as $pv ) {

						// Do not save anything the user doesn't have permissions for.
						if ( isset( $field_args[ $key ] ) ) {
							if ( user_can( $user_id, $pv ) && user_can( $check_user_id, $pv ) ) {
								break;
							}
						}
					}
				}

				// Have to remove the escaping for array comparison.
				if ( is_array( $value ) ) {
					foreach ( $value as $k => $v ) {
						if ( ! is_array( $v ) ) {
							$value[ $k ] = sanitize_text_field( wp_unslash( $v ) );
						}
					}
				}

				// parent_options.
				if ( isset( $this->options_defaults[ $key ] ) && $value === $this->options_defaults[ $key ] ) {
					$to_delete[ $key ] = $value;
				} elseif ( isset( $this->options_defaults[ $key ] ) ) {
					$to_save[ $key ]    = $value;
					$to_compare[ $key ] = $meta[ $key ] ?? '';
				} else {
					break;
				}
			}

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$to_save = apply_filters( "redux/{$this->parent->args['opt_name']}/users/save/before_validate", $to_save, $to_compare, $this->sections );

			$validate = $this->parent->validate_class->validate( $to_save, $to_compare, $this->sections );

			// Validate fields (if needed).
			foreach ( $to_save as $key => $value ) {
				if ( isset( $validate[ $key ] ) && $validate[ $key ] !== $value ) {
					if ( isset( $this->meta[ $key ] ) && $validate[ $key ] === $this->meta[ $key ] ) {
						unset( $to_save[ $key ] );
					} else {
						$to_save[ $key ] = $validate[ $key ];
					}
				}
			}

			if ( ! empty( $this->parent->errors ) || ! empty( $this->parent->warnings ) ) {
				$this->parent->transients['notices'] = ( isset( $this->parent->transients['notices'] ) && is_array( $this->parent->transients['notices'] ) ) ? $this->parent->transients['notices'] : array();

				if ( ! isset( $this->parent->transients['notices']['errors'] ) || $this->parent->transients['notices']['errors'] !== $this->parent->errors ) {
					$this->parent->transients['notices']['errors'] = $this->parent->errors;
					$update_transients                             = true;
				}

				if ( ! isset( $this->parent->transients['notices']['warnings'] ) || $this->parent->transients['notices']['warnings'] !== $this->parent->warnings ) {
					$this->parent->transients['notices']['warnings'] = $this->parent->warnings;
					$update_transients                               = true;
				}

				if ( isset( $update_transients ) ) {
					$this->parent->transients['notices']['override'] = 1;
					set_transient( $this->parent->args['opt_name'] . '-transients-users', $this->parent->transients );
				}
			}

			$check = $this->profile_fields;

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$to_save = apply_filters( 'redux/users/save', $to_save, $to_compare, $this->sections );

			foreach ( $to_save as $key => $value ) {
				if ( is_array( $value ) ) {
					$still_update = false;

					foreach ( $value as $vv ) {
						if ( ! empty( $vv ) ) {
							$still_update = true;
						}
					}

					if ( ! $still_update ) {
						continue;
					}
				}

				$prev_value = $this->meta[ $key ] ?? '';

				if ( isset( $check[ $key ] ) ) {
					unset( $check[ $key ] );
				}

				update_user_meta( $user_id, $key, $value, $prev_value );
			}

			foreach ( $to_delete as $key => $value ) {
				if ( isset( $check[ $key ] ) ) {
					unset( $check[ $key ] );
				}

				$prev_value = $this->meta[ $key ] ?? '';
				delete_user_meta( $user_id, $key, $prev_value );
			}
			if ( ! empty( $check ) ) {
				foreach ( $check as $key => $value ) {
					delete_user_meta( $user_id, $key );
				}
			}

			return true;
		}

		/**
		 * Show any stored error messages.
		 *
		 * @access public
		 * @return void
		 */
		public function meta_profiles_show_errors() {
			if ( isset( $this->notices['errors'] ) && ! empty( $this->notices['errors'] ) ) {
				echo '<div id="redux_users_errors" class="error fade">';
				echo '<p><strong><span></span> ' . count( $this->notices['errors'] ) . ' ' . esc_html__( 'error(s) were found!', 'redux-framework' ) . '</strong></p>';
				echo '</div>';
			}

			if ( isset( $this->notices['warnings'] ) && ! empty( $this->notices['warnings'] ) ) {
				echo '<div id="redux_users_warnings" class="error fade" style="border-left-color: #E8E20C;">';
				echo '<p><strong><span></span> ' . count( $this->notices['warnings'] ) . ' ' . esc_html__( 'warnings(s) were found!', 'redux-framework' ) . '</strong></p>';
				echo '</div>';
			}
		}
	}
}

// Helper function to bypass WordPress hook priorities.  ;).
if ( ! function_exists( 'create_term_redux_users' ) ) {

	/**
	 * Create_term_redux_users.
	 *
	 * @param string $profile_id Profile ID.
	 * @param int    $tt_id      ID.
	 * @param string $users      Users.
	 */
	function create_term_redux_users( string $profile_id, int $tt_id = 0, string $users = '' ) {
		$instances = Redux::all_instances();

		foreach ( $_POST as $key => $value ) {
			if ( is_array( $value ) && isset( $instances[ $key ] ) ) {
				$instances[ $key ]->extensions['users']->user_meta_save( $profile_id );
			}
		}
	}
}

add_action( 'create_term', 'create_term_redux_users', 4 );
