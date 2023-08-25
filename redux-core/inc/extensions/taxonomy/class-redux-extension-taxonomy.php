<?php
/**
 * Redux Taxonomy Meta Extension Class
 *
 * @package Redux Pro
 * @author  Dovy Paukstys
 * @class   Redux_Extension_Taxonomy
 * @version 4.4.6
 * @noinspection PhpIgnoredClassAliasDeclaration
 */

defined( 'ABSPATH' ) || exit;

// Don't duplicate me!
if ( ! class_exists( 'Redux_Extension_Taxonomy' ) ) {

	/**
	 * Main ReduxFramework customizer extension class
	 *
	 * @since       1.0.0
	 */
	class Redux_Extension_Taxonomy extends Redux_Extension_Abstract {

		/**
		 * Extension version.
		 *
		 * @var string
		 */
		public static $version = '4.4.6';

		/**
		 * Extension friendly name.
		 *
		 * @var string
		 */
		public $extension_name = 'Taxonomy';

		/**
		 * Terms array.
		 *
		 * @var array
		 */
		public $terms = array();

		/**
		 * Taxonomy types array.
		 *
		 * @var array
		 */
		public $taxonomy_types = array();

		/**
		 * Taxonomy type.
		 *
		 * @var string
		 */
		public $taxonomy_type = '';

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
		 * Parent options.
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
		 * Taxonomy field types array.
		 *
		 * @var array
		 */
		public $taxonomy_type_fields = array();

		/**
		 * WP Links array.
		 *
		 * @var array
		 */
		public $wp_links = array();

		/**
		 * Option defaults.
		 *
		 * @var array
		 */
		public $options_defaults = array();

		/**
		 * Localized data array.
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
		 * Tag ID.
		 *
		 * @var int
		 */
		public $tag_id = 0;

		/**
		 * Base URL.
		 *
		 * @var string
		 */
		public $base_url = '';

		/**
		 * Accepted WordPress screens.
		 *
		 * @var array
		 */
		private $pagenows;

		/**
		 * Notices array.
		 *
		 * @var array
		 */
		public $notices;

		/**
		 * Redux_Extension_Taxonomy constructor.
		 *
		 * @param object $redux ReduxFramework object.
		 */
		public function __construct( $redux ) {
			global $pagenow;

			parent::__construct( $redux, __FILE__ );

			$this->parent = $redux;

			$this->add_field( 'taxonomy' );
			$this->parent->extensions['taxonomy'] = $this;

			$this->pagenows = array( 'edit-tags.php', 'term.php' );

			include_once __DIR__ . '/redux-taxonomy-helpers.php';

			add_action( 'admin_notices', array( $this, 'meta_terms_show_errors' ), 0 );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 20 );

			if ( is_admin() && in_array( $pagenow, $this->pagenows, true ) ) {
				$this->init();

				// phpcs:ignore WordPress.Security.NonceVerification
				if ( isset( $_POST['taxonomy'] ) ) {

					// phpcs:ignore WordPress.Security.NonceVerification
					add_action( 'edit_' . sanitize_text_field( wp_unslash( $_POST['taxonomy'] ) ), array( $this, 'meta_terms_save' ), 10, 3 );
				}
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
			$classes[] = 'redux-taxonomy';
			$classes[] = 'redux-' . $this->parent->args['opt_name'];

			if ( '' !== $this->parent->args['class'] ) {
				$classes[] = $this->parent->args['class'];
			}

			return $classes;
		}

		/**
		 * Add term hide class.
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

			if ( isset( $_POST['action'] ) && 'redux_demo_customizer_save' === $_POST['action'] ) { // phpcs:ignore WordPress.Security.NonceVerification
				return;
			}

			$this->parent->transients['run_compiler'] = false;

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$this->terms = apply_filters( 'redux/taxonomy/' . $this->parent->args['opt_name'] . '/terms', $this->terms, $this->parent->args['opt_name'] );

			if ( empty( $this->terms ) && class_exists( 'Redux_Taxonomy' ) ) {
				$this->terms = Redux_Taxonomy::construct_terms( $this->parent->args['opt_name'] );
			}

			if ( empty( $this->terms ) || ! is_array( $this->terms ) ) {
				return;
			}

			$this->base_url = ( is_ssl() ? 'https://' : 'http://' ) . Redux_Core::$server['HTTP_HOST'] . Redux_Core::$server['REQUEST_URI'];

			// phpcs:disable WordPress.Security.NonceVerification
			if ( ! isset( $_GET['tag_ID'] ) ) {
				$_GET['tag_ID'] = 0;
			}
			$this->tag_id        = isset( $_GET['tag_ID'] ) ? sanitize_text_field( wp_unslash( $_GET['tag_ID'] ) ) : 0;
			$this->taxonomy_type = isset( $_GET['taxonomy'] ) ? sanitize_text_field( wp_unslash( $_GET['taxonomy'] ) ) : '';
			// phpcs:enable WordPress.Security.NonceVerification

			foreach ( $this->terms as $bk => $term ) {

				// If the tag_ids for this term are set, we're limiting to the current tag id.
				if ( ! empty( $term['tag_ids'] ) ) {
					if ( ! is_array( $term['tag_ids'] ) ) {
						$term['tag_ids'] = array( $term['tag_ids'] );
					}
					if ( ! in_array( $this->tag_id, $term['tag_ids'], true ) ) {
						continue;
					}
				}

				if ( ! isset( $term['taxonomy_types'] ) ) {
					return;
				}

				if ( ! empty( $term['sections'] ) ) {
					$this->sections         = $term['sections'];
					$this->parent->sections = array_merge( $this->parent->sections, $term['sections'] );

					$this->taxonomy_types = wp_parse_args( $this->taxonomy_types, $term['taxonomy_types'] );

					// Checking to override the parent variables.
					$add_field = false;

					foreach ( $term['taxonomy_types'] as $type ) {
						if ( $this->taxonomy_type === $type ) {
							$add_field = true;
						}
					}

					// Replacing all the fields.
					if ( $add_field || ( ( is_admin() && in_array( $pagenow, $this->pagenows, true ) ) || ( ! is_admin() ) ) ) {
						$run_hooks = true;

						$term_id = 'redux-' . $this->parent->args['opt_name'] . '-metaterm-' . $term['id'];

						if ( isset( $term['page_template'] ) && 'page' === $this->taxonomy_type ) {
							if ( ! is_array( $term['page_template'] ) ) {
								$term['page_template'] = array( $term['page_template'] );
							}

							$this->wp_links[ $term_id ]['page_template'] = isset( $this->wp_links[ $term_id ]['page_template'] ) ? wp_parse_args( $this->wp_links[ $term_id ]['page_template'], $term['page_template'] ) : $term['page_template'];
						}

						if ( isset( $term['post_format'] ) && ( in_array( $this->taxonomy_type, $this->taxonomy_types, true ) || '' === $this->taxonomy_type ) ) {
							if ( ! is_array( $term['post_format'] ) ) {
								$term['post_format'] = array( $term['post_format'] );
							}

							$this->wp_links[ $term_id ]['post_format'] = isset( $this->wp_links[ $term_id ]['post_format'] ) ? wp_parse_args( $this->wp_links[ $term_id ]['post_format'], $term['post_format'] ) : $term['post_format'];
						}

						$this->meta[ $this->tag_id ] = Redux_Taxonomy::get_term_meta( array( 'taxonomy' => $this->tag_id ) );
						$this->parent->options       = array_merge( $this->parent->options, $this->meta[ $this->tag_id ] );

						foreach ( $term['sections'] as $sk => $section ) {
							if ( ! empty( $section['fields'] ) ) {
								foreach ( $section['fields'] as $fk => $field ) {
									if ( ! isset( $field['class'] ) ) {
										$field['class'] = '';

										$this->terms[ $bk ]['sections'][ $sk ]['fields'][ $fk ] = $field;
									}

									$this->parent->required_class->check_dependencies( $field );

									/**  phpcs:ignore
									/ phpcs:ignore Generic.CodeAnalysis.EmptyStatement
									/ if ( stripos( $field['class'], 'redux-field-init' ) === 0 ) {
									/ $field['class'] = trim( $field['class'] . ' redux-field-init' );
									/}
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

										foreach ( $term['taxonomy_types'] as $type ) {
											$this->taxonomy_type_fields[ $type ][ $field['id'] ] = 1;
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

									if ( ! isset( $this->meta[ $this->tag_id ][ $field['id'] ] ) ) {
										$this->meta[ $this->tag_id ][ $field['id'] ] = $this->options_defaults[ $field['id'] ];
									}

									// Only override if it exists, and it's not the default.
									// phpcs:ignore Generic.CodeAnalysis.EmptyStatement
									/** If ( isset( $this->meta[ $this->tag_id ][ $field['id'] ] ) && isset( $field['default'] ) && $this->meta[ $this->tag_id ][ $field['id'] ] === $field['default'] ) {
									 *   // unset($this->meta[$this->tag_id][$field['id']]); .
									 * }
									 */
								}
							}
						}
					}
				}
			}

			if ( isset( $run_hooks ) && true === $run_hooks ) {
				$this->parent_options = '';

				if ( ! empty( $this->to_replace ) ) {
					foreach ( $this->to_replace as $id => $field ) {
						add_filter( "redux/options/{$this->parent->args['opt_name']}/field/$id/register", array( $this, 'replace_field' ) );
					}
				}

				add_filter( "redux/options/{$this->parent->args['opt_name']}/options", array( $this, 'override_options' ) );
				add_filter( "redux/field/{$this->parent->args['opt_name']}/_can_output_css", array( $this, 'override_can_output_css' ) );
				add_filter( "redux/field/{$this->parent->args['opt_name']}/output_css", array( $this, 'output_css' ) );

				// phpcs:disable WordPress.Security.NonceVerification
				if ( is_admin() && in_array( $pagenow, $this->pagenows, true ) && isset( $_GET['taxonomy'] ) ) {
					$priority = $this->parent->args['taxonomy_priority'] ?? 3;

					add_action( sanitize_text_field( wp_unslash( $_GET['taxonomy'] ) ) . '_edit_form', array( $this, 'add_meta_terms' ), $priority );
					add_action( sanitize_text_field( wp_unslash( $_GET['taxonomy'] ) ) . '_add_form_fields', array( $this, 'add_meta_terms' ), $priority );
				}
				// phpcs:enable WordPress.Security.NonceVerification
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
		 * CSS can output override.
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
		 * Output CSS>
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
		 * @param array $options Options array.
		 *
		 * @return array
		 */
		public function override_options( array $options ): array {
			$this->parent->options_class->default_values();
			$this->parent_defaults = $this->parent->options_defaults;

			$meta = $this->get_meta( $this->tag_id );
			$data = wp_parse_args( $meta, $this->options_defaults );

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
		 * Enqueue support files and fields.
		 */
		public function enqueue() {
			global $pagenow;

			$types    = array();
			$sections = array();

			// Enqueue css.
			foreach ( $this->terms as $key => $term ) {
				if ( empty( $term['sections'] ) ) {
					continue;
				}

				// phpcs:ignore WordPress.Security.NonceVerification
				if ( isset( $_GET['taxonomy'] ) && isset( $term['taxonomy_types'] ) && in_array( sanitize_text_field( wp_unslash( $_GET['taxonomy'] ) ), $term['taxonomy_types'], true ) ) {
					$sections[] = $term['sections'];
				}

				$types = isset( $term['taxonomy_types'] ) ? array_merge( $term['taxonomy_types'], $types ) : $types;
				if ( ! empty( $term['taxonomy_types'] ) ) {
					if ( ! is_array( $term['taxonomy_types'] ) ) {
						$term['taxonomy_types']                = array( $term['taxonomy_types'] );
						$this->terms[ $key ]['taxonomy_types'] = $term['taxonomy_types'];
					}
				}
			}

			// phpcs:ignore WordPress.Security.NonceVerification
			if ( in_array( $pagenow, $this->pagenows, true ) && isset( $_GET['taxonomy'] ) ) {
				if ( in_array( wp_unslash( $_GET['taxonomy'] ), $types, true ) ) { // phpcs:ignore WordPress.Security.NonceVerification
					$this->parent->transients       = get_transient( $this->parent->args['opt_name'] . '-transients-taxonomy' );
					$this->parent->transients_check = $this->parent->transients;

					if ( isset( $this->parent->transients['notices'] ) ) {
						$this->notices                              = $this->parent->transients['notices'];
						$this->parent->transients['last_save_mode'] = 'taxonomy';
					}

					delete_transient( $this->parent->args['opt_name'] . '-transients-taxonomy' );
					$this->parent->enqueue_class->init();

					$this->parent->sections = $sections;

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					do_action( "redux/taxonomy/{$this->parent->args['opt_name']}/enqueue" );

					/**
					 * Redux taxonomy CSS
					 * filter 'redux/page/{opt_name}/enqueue/redux-extension-taxonomy-css'
					 */
					if ( true === $this->parent->args['dev_mode'] ) {
						wp_enqueue_style(
							'redux-extension-taxonomy-css',
							// phpcs:ignore WordPress.NamingConventions.ValidHookName
							apply_filters( "redux/taxonomy/{$this->parent->args['opt_name']}/enqueue/redux-extension-taxonomy-css", $this->extension_url . 'redux-extension-taxonomy.css' ),
							array( 'redux-admin-css' ),
							self::$version
						);
					}

					/**
					 * Redux taxonomy JS
					 * filter 'redux/page/{opt_name}/enqueue/redux-extension-taxonomy-js
					 */
					wp_enqueue_script(
						'redux-extension-taxonomy-js',
						// phpcs:ignore WordPress.NamingConventions.ValidHookName
						apply_filters( "redux/taxonomy/{$this->parent->args['opt_name']}/enqueue/redux-extension-taxonomy-js", $this->extension_url . 'redux-extension-taxonomy' . Redux_Functions::is_min() . '.js' ),
						array( 'jquery', 'redux-js' ),
						self::$version,
						true
					);

					// Values used by the javascript.
					wp_localize_script( 'redux-extension-taxonomy-js', 'reduxTaxonomy', $this->wp_links );
				}
			}
		}

		/**
		 * DEPRECATED
		 */
		public function default_values() {
			if ( ! empty( $this->terms ) && empty( $this->options_defaults ) ) {
				foreach ( $this->terms as $key => $term ) {
					if ( empty( $term['sections'] ) ) {
						continue;
					}

					// fill the cache.
					foreach ( $term['sections'] as $sk => $section ) {
						if ( ! isset( $section['id'] ) ) {
							if ( ! is_numeric( $sk ) || ! isset( $section['title'] ) ) {
								$section['id'] = $sk;
							} else {
								$section['id'] = sanitize_title( $section['title'], $sk );
							}
							$this->terms[ $key ]['sections'][ $sk ] = $section;
						}
						if ( isset( $section['fields'] ) ) {
							foreach ( $section['fields'] as $k => $field ) {
								if ( empty( $field['id'] ) && empty( $field['type'] ) ) {
									continue;
								}

								if ( 'ace_editor' === $field['type'] && isset( $field['options'] ) ) {
									$this->terms[ $key ]['sections'][ $sk ]['fields'][ $k ]['args'] = $field['options'];
									unset( $this->terms[ $key ]['sections'][ $sk ]['fields'][ $k ]['options'] );
								}

								if ( 'section' === $field['type'] && isset( $field['indent'] ) && true === $field['indent'] ) {
									$field['class']  = $field['class'] ?? '';
									$field['class'] .= 'redux-section-indent-start';
									$this->terms[ $key ]['sections'][ $sk ]['fields'][ $k ] = $field;
								}

								$this->parent->options_defaults_class->field_default_values( $this->parent->args['opt_name'], $field );
							}
						}
					}
				}
			}

			if ( empty( $this->meta[ $this->tag_id ] ) ) {
				$this->meta[ $this->tag_id ] = $this->get_meta( $this->tag_id );
			}
		}

		/**
		 * Add Meta Terms.
		 */
		public function add_meta_terms() {
			if ( empty( $this->terms ) || ! is_array( $this->terms ) ) {
				return;
			}

			foreach ( $this->terms as $key => $term ) {
				if ( empty( $term['sections'] ) ) {
					continue;
				}

				$defaults = array(
					'id'         => "$key",
					'section_id' => $key,
					'terms'      => array(),
				);

				$term = wp_parse_args( $term, $defaults );
				if ( ! empty( $term['taxonomy_types'] ) && isset( $_GET['taxonomy'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
					foreach ( $term['taxonomy_types'] as $termtype ) {
						if ( $_GET['taxonomy'] !== $termtype ) { // phpcs:ignore WordPress.Security.NonceVerification
							continue;
						}

						// Override the parent args on a metaterm level.
						if ( empty( $this->orig_args ) ) {
							$this->orig_args = $this->parent->args;
						}

						if ( isset( $term['args'] ) ) {
							$this->parent->args = wp_parse_args( $term['args'], $this->orig_args );
						} elseif ( $this->parent->args !== $this->orig_args ) {
							$this->parent->args = $this->orig_args;
						}

						if ( ! isset( $term['class'] ) ) {
							$term['class'] = array();
						}

						if ( ! empty( $term['class'] ) ) {
							if ( ! is_array( $term['class'] ) ) {
								$term['class'] = array( $term['class'] );
							}
						}

						$term['class'] = $this->add_term_classes( $term['class'] );

						if ( isset( $term['post_format'] ) ) {
							$term['class'] = $this->add_term_hide_class( $term['class'] );
						}

						global $pagenow;
						if ( strpos( $pagenow, 'edit-' ) !== false ) {

							$term['style']   = 'wp';
							$term['class'][] = ' edit-page';
							$term['class'][] = ' redux-wp-style';
						}

						$this->generate_terms( array( 'args' => $term ) );
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
		private function field_default( array $field_id ) {
			if ( ! isset( $this->parent->options_defaults ) ) {
				$this->parent->options_defaults = $this->parent->default_values();
			}

			if ( ! isset( $this->parent->options ) || empty( $this->parent->options ) ) {
				$this->parent->options_class->get();
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
		 * Function to get and cache the post-meta.
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
				$o_data = apply_filters( "redux/taxonomy/{$this->parent->args['opt_name']}/get_meta", $o_data );

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
		 * @param object $the_post WP_Post.
		 * @param string $meta_key Meta key.
		 * @param string $def_val  Default value.
		 *
		 * @return mixed|string
		 */
		public function get_values( $the_post, string $meta_key = '', string $def_val = '' ) {

			// Override these values if they differ from the admin panel defaults.
			if ( isset( $the_post->taxonomy_type ) && in_array( $the_post->taxonomy_type, $this->taxonomy_types, true ) ) {
				if ( isset( $this->taxonomy_type_values[ $the_post->taxonomy_type ] ) ) {
					$meta = $this->taxonomy_type_fields[ $the_post->taxonomy_type ];
				} else {
					$defaults = array();
					if ( ! empty( $this->taxonomy_type_fields[ $the_post->taxonomy_type ] ) ) {
						foreach ( $this->taxonomy_type_fields[ $the_post->taxonomy_type ] as $key => $null ) {
							if ( isset( $this->options_defaults[ $key ] ) ) {
								$defaults[ $key ] = $this->options_defaults[ $key ];
							}
						}
					}

					$meta = wp_parse_args( $this->get_meta( $the_post->ID ), $defaults );

					$this->taxonomy_type_fields[ $the_post->taxonomy_type ] = $meta;
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
		 * Check edit visibility.
		 *
		 * @param array $params Array.
		 *
		 * @return bool
		 */
		private function check_edit_visibility( array $params = array() ): bool {
			global $pagenow;

			// Edit page visibility.
			if ( strpos( $pagenow, 'edit-' ) !== false ) {
				if ( isset( $params['fields'] ) ) {
					foreach ( $params['fields'] as $field ) {
						if ( in_array( $field['id'], $this->parent->fields_hidden, true ) ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement
							// Not visible.
						} elseif ( isset( $field['add_visibility'] ) && $field['add_visibility'] ) {
								return true;
						}
					}

					return false;
				}
				if ( isset( $params['add_visibility'] ) && $params['add_visibility'] ) {
					return true;
				}

				return false;
			}

			return true;
		}

		/**
		 * Generate Terms.
		 *
		 * @param array $metaterm Term.
		 */
		private function generate_terms( array $metaterm ) {
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
			echo '<div class="' . esc_attr( $class ) . '">';

			$sections = $metaterm['args']['sections'];

			wp_nonce_field( 'redux_taxonomy_meta_nonce', 'redux_taxonomy_meta_nonce' );

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
								echo $this->parent->render_class->section_menu( $s_key, $section, '_' . $metaterm['args']['id'], $sections );
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

								// phpcs:ignore WordPress.Security.EscapeOutput
								echo '<tr valign="top"' . $ex_style . '>';

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
											echo $th; // phpcs:ignore WordPress.Security.EscapeOutput
										}
										echo '</th>';
										echo '<td>';
									}
								} else {
									echo '<td>' . $th; // phpcs:ignore WordPress.Security.EscapeOutput
								}

								if ( 'section' === $field['type'] && true === $field['indent'] ) {
									$field['class']  = $field['class'] ?? '';
									$field['class'] .= 'redux-section-indent-start';
								}

								if ( ! isset( $this->meta[ $this->tag_id ][ $field['id'] ] ) ) {
									$this->meta[ $this->tag_id ][ $field['id'] ] = '';
								}

								$this->parent->render_class->field_input( $field, $this->meta[ $this->tag_id ][ $field['id'] ] );
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
			echo '</div>';
		}

		/**
		 * Save meta terms
		 * Runs when a post is saved and does an action which to write panel save scripts can hook into.
		 *
		 * @access public
		 *
		 * @param mixed $tag_id Yag ID.
		 *
		 * @return bool
		 */
		public function meta_terms_save( $tag_id ): bool {

			// Check if our nonce is set.
			if ( ! isset( $_POST['redux_taxonomy_meta_nonce'] ) || ! isset( $_POST[ $this->parent->args['opt_name'] ] ) ) {
				return false;
			}

			// Verify that the nonce is valid.
			// Validate fields (if needed).
			if ( ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['redux_taxonomy_meta_nonce'] ) ), 'redux_taxonomy_meta_nonce' ) ) {
				return $tag_id;
			}

			$meta = Redux_Taxonomy::get_term_meta( array( 'taxonomy' => $tag_id ) );

			$to_save    = array();
			$to_compare = array();
			$to_delete  = array();

			$field_args = Redux_Taxonomy::$fields[ $this->parent->args['opt_name'] ];

			foreach ( $_POST[ $this->parent->args['opt_name'] ] as $key => $value ) { // phpcs:ignore WordPress.Security
				$key = sanitize_text_field( wp_unslash( $key ) );

				// Do not save anything the user doesn't have permissions for.
				if ( ! empty( $field_args[ $key ]['permissions'] ) ) {
					foreach ( (array) $field_args[ $key ]['permissions'] as $pv ) {

						// Do not save anything the user doesn't have permissions for.
						if ( isset( $field_args[ $key ] ) ) {
							if ( user_can( get_current_user_id(), $pv ) ) {
								break;
							}
						}
					}
				}

				// Have to remove the escaping for array comparison.
				if ( is_array( $value ) ) {
					foreach ( $value as $k => $v ) {
						if ( ! is_array( $v ) ) {
							$value[ $k ] = stripslashes( $v );
						}
					}
				}

				// parent_options.
				if ( isset( $this->options_defaults[ $key ] ) && $value === $this->options_defaults[ $key ] ) {
					$to_delete[ $key ] = $value;
				} elseif ( isset( $this->options_defaults[ $key ] ) ) {
					$to_save[ $key ]    = $value;
					$to_compare[ $key ] = $meta[ $key ] ?? '';
				}
			}

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$to_save = apply_filters( 'redux/taxonomy/save/before_validate', $to_save, $to_compare, $this->sections );

			$validate = $this->parent->_validate_values( $to_save, $to_compare, $this->sections );

			// Validate fields (if needed).
			foreach ( $to_save as $key => $value ) {
				if ( isset( $validate[ $key ] ) && $validate[ $key ] !== $value ) {
					if ( isset( $meta[ $key ] ) && $validate[ $key ] === $meta[ $key ] ) {
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
					set_transient( $this->parent->args['opt_name'] . '-transients-taxonomy', $this->parent->transients );
				}
			}

			$post_tax = isset( $_POST['taxonomy'] ) ? sanitize_text_field( wp_unslash( $_POST['taxonomy'] ) ) : '';

			$check = $this->taxonomy_type_fields[ $post_tax ] ?? array();

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$to_save = apply_filters( 'redux/taxonomy/save', $to_save, $to_compare, $this->sections );

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
				$prev_value = $this->meta[ $tag_id ][ $key ] ?? '';
				if ( isset( $check[ $key ] ) ) {
					unset( $check[ $key ] );
				}
				update_term_meta( $tag_id, $key, $value, $prev_value );
			}

			foreach ( $to_delete as $key => $value ) {
				if ( isset( $check[ $key ] ) ) {
					unset( $check[ $key ] );
				}

				$prev_value = $this->meta[ $tag_id ][ $key ] ?? '';
				delete_term_meta( $tag_id, $key, $prev_value );
			}
			if ( ! empty( $check ) ) {
				foreach ( $check as $key => $value ) {
					delete_term_meta( $tag_id, $key );
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
		public function meta_terms_show_errors() {
			if ( isset( $this->notices['errors'] ) && ! empty( $this->notices['errors'] ) ) {
				echo '<div id="redux_taxonomy_errors" class="error fade">';
				echo '<p><strong><span></span> ' . count( $this->notices['errors'] ) . ' ' . esc_html__( 'error(s) were found!', 'redux-framework' ) . '</strong></p>';
				echo '</div>';
			}

			if ( isset( $this->notices['warnings'] ) && ! empty( $this->notices['warnings'] ) ) {
				echo '<div id="redux_taxonomy_warnings" class="error fade" style="border-left-color: #E8E20C;">';
				echo '<p><strong><span></span> ' . count( $this->notices['warnings'] ) . ' ' . esc_html__( 'warnings(s) were found!', 'redux-framework' ) . '</strong></p>';
				echo '</div>';
			}
		}
	}
}

class_alias( 'Redux_Extension_Taxonomy', 'ReduxFramework_extension_taxonomy' );
