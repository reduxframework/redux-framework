<?php
/**
 * Redux WordPress Data Class
 *
 * @class   Redux_WordPress_Data
 * @version 3.0.0
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_WordPress_Data', false ) ) {

	/**
	 * Class Redux_WordPress_Data
	 */
	class Redux_WordPress_Data extends Redux_Class {

		/**
		 * Holds WordPress data.
		 *
		 * @var null
		 */
		private $wp_data = null;

		/**
		 * Redux_WordPress_Data constructor.
		 *
		 * @param mixed $redux ReduxFramework pointer or opt_name.
		 */
		public function __construct( $redux = null ) {
			if ( is_string( $redux ) ) {
				$this->opt_name = $redux;
			} else {
				parent::__construct( $redux );
			}
		}

		/**
		 * Get the data.
		 *
		 * @param string|array $type          Type.
		 * @param array|string $args          Args.
		 * @param string       $opt_name      Opt name.
		 * @param string|int   $current_value Current value.
		 * @param bool         $ajax          Tells if this is an AJAX call.
		 *
		 * @return array|mixed|string
		 */
		public function get( $type, $args = array(), string $opt_name = '', $current_value = '', bool $ajax = false ) {
			$opt_name = $this->opt_name;

			// We don't want to run this, it's not a string value. Send it back!
			if ( is_array( $type ) ) {
				return $type;
			}

			/**
			 * Filter 'redux/options/{opt_name}/pre_data/{type}'
			 *
			 * @param string $data
			 */
			$pre_data = apply_filters( "redux/options/$opt_name/pre_data/$type", null ); // phpcs:ignore WordPress.NamingConventions.ValidHookName
			if ( null !== $pre_data || empty( $type ) ) {
				return $pre_data;
			}

			if ( isset( $args['data_sortby'] ) && in_array( $args['data_sortby'], array( 'value', 'key' ), true ) ) {
				$data_sort = $args['data_sortby'];
				unset( $args['data_sortby'] );
			} else {
				$data_sort = 'value';
			}
			if ( isset( $args['data_order'] ) && in_array( $args['data_order'], array( 'asc', 'desc' ), true ) ) {
				$data_order = $args['data_order'];
				unset( $args['data_order'] );
			} else {
				$data_order = 'asc';
			}

			$this->maybe_get_translation( $type, $current_value, $args );

			$current_data = array();
			if ( empty( $current_value ) && ! Redux_Helpers::is_integer( $current_value ) ) {
				$current_value = null;
			} else {
				// Get the args to grab the current data.
				$current_data_args = $this->get_current_data_args( $type, $args, $current_value );

				// Let's make a unique key for this arg array.
				$current_data_args_key = md5( maybe_serialize( $current_data_args ) );

				// Check to make sure we haven't already run this call before.
				$current_data = $this->wp_data[ $type . $current_data_args_key ] ?? $this->get_data( $type, $current_data_args, $current_value );
			}

			// If ajax is enabled AND $current_data is empty, set a dummy value for the init.
			if ( $ajax && ! wp_doing_ajax() ) {
				// Dummy is needed otherwise empty.
				if ( empty( $current_data ) ) {
					$current_data = array(
						'dummy' => '',
					);
				}

				return $current_data;
			}

			// phpcs:ignore Squiz.PHP.CommentedOutCode
			$args_key = md5( maybe_serialize( $args ) );

			// Data caching.
			if ( isset( $this->wp_data[ $type . $args_key ] ) ) {
				$data = $this->wp_data[ $type . $args_key ];
			} else {
				/**
				 * Use data from WordPress to populate options array.
				 * */
				$data = $this->get_data( $type, $args, $current_value );
			}

			if ( ! empty( $current_data ) ) {
				$data += $current_data;
			}

			if ( ! empty( $data ) ) {
				$data                               = $this->order_data( $data, $data_sort, $data_order );
				$this->wp_data[ $type . $args_key ] = $data;
			}

			/**
			 * Filter 'redux/options/{opt_name}/data/{type}'
			 *
			 * @param string $data
			 */

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			return apply_filters( "redux/options/$opt_name/data/$type", $data );
		}


		/**
		 * Process the results into a proper array, fetching the data elements needed for each data type.
		 *
		 * @param array|WP_Error $results       Results to process in the data array.
		 * @param string|bool    $id_key        Key on object/array that represents the ID.
		 * @param string|bool    $name_key      Key on object/array that represents the name/text.
		 * @param bool           $add_key       If true, the display key will appear in the text.
		 * @param string|bool    $secondary_key If a data type you'd rather display a different ID as the display key.
		 *
		 * @return array
		 */
		private function process_results( $results = array(), $id_key = '', $name_key = '', bool $add_key = true, $secondary_key = 'slug' ): array {
			$data = array();
			if ( ! empty( $results ) && ! is_a( $results, 'WP_Error' ) ) {
				foreach ( $results as $k => $v ) {
					if ( empty( $id_key ) ) {
						$key = $k;
					} else {
						if ( is_object( $v ) ) {
							$key = $v->$id_key;
						} elseif ( is_array( $v ) ) {
							$key = $v[ $id_key ];
						} else {
							$key = $k;
						}
					}

					if ( empty( $name_key ) ) {
						$value = $v;
					} else {
						if ( is_object( $v ) ) {
							$value = $v->$name_key;
						} elseif ( is_array( $v ) ) {
							$value = $v[ $name_key ];
						} else {
							$value = $v;
						}
					}
					$display_key = $key;
					if ( is_object( $v ) && isset( $v->$secondary_key ) ) {
						$display_key = $v->$secondary_key;
					} elseif ( ! is_object( $v ) && isset( $v[ $secondary_key ] ) ) {
						$display_key = $v[ $secondary_key ];
					}
					$data[ $key ] = $value;
					if ( $display_key !== $value && $add_key ) {
						$data[ $key ] = $data[ $key ] . ' [' . $display_key . ']';
					}
				}
			}

			return $data;
		}

		/**
		 * Order / Sort the data.
		 *
		 * @param array  $data  Data to sort.
		 * @param string $sort  Way to sort. Accepts: key|value.
		 * @param string $order Order of the sort. Accepts: asc|desc.
		 *
		 * @return array
		 */
		private function order_data( array $data = array(), string $sort = 'value', string $order = 'asc' ): array {
			if ( 'key' === $sort ) {
				if ( 'asc' === $order ) {
					ksort( $data );
				} else {
					krsort( $data );
				}
			} elseif ( 'value' === $sort ) {
				if ( 'asc' === $order ) {
					asort( $data );
				} else {
					arsort( $data );
				}
			}

			return $data;
		}

		/**
		 * Fetch the data for a given type.
		 *
		 * @param string       $type          The data type we're fetching.
		 * @param array|string $args          Arguments to pass.
		 * @param mixed|array  $current_value If a current value already set in the database.
		 *
		 * @return array|null|string
		 */
		private function get_data( string $type, $args, $current_value ) {
			$args = $this->get_arg_defaults( $type, $args );

			$opt_name = $this->opt_name;
			if ( empty( $args ) ) {
				$args = array();
			}

			$data = array();
			if ( isset( $args['args'] ) && empty( $args['args'] ) ) {
				unset( $args['args'] );
			}

			$display_keys = false;
			if ( isset( $args['display_keys'] ) ) {
				$display_keys = true;
				unset( $args['display_keys'] );
			}

			$secondary_key = 'slug';
			if ( isset( $args['secondary_key'] ) ) {
				$secondary_key = $args['secondary_key'];
				unset( $args['secondary_key'] );
			}

			switch ( $type ) {
				case 'categories':
				case 'category':
				case 'terms':
				case 'term':
					if ( isset( $args['taxonomies'] ) ) {
						$args['taxonomy'] = $args['taxonomies'];
						unset( $args['taxonomies'] );
					}
					$results = get_terms( $args );
					$data    = $this->process_results( $results, 'term_id', 'name', $display_keys, $secondary_key );
					break;

				case 'pages':
				case 'page':
					$results = get_pages( $args );
					$data    = $this->process_results( $results, 'ID', 'post_title', $display_keys, $secondary_key );
					break;

				case 'tags':
				case 'tag':
					$results = get_tags( $args );
					$data    = $this->process_results( $results, 'term_id', 'name', $display_keys, $secondary_key );
					break;

				case 'menus':
				case 'menu':
					$results = wp_get_nav_menus( $args );
					$data    = $this->process_results( $results, 'term_id', 'name', $display_keys, $secondary_key );
					break;

				case 'posts':
				case 'post':
					$results = get_posts( $args );
					$data    = $this->process_results( $results, 'ID', 'post_title', $display_keys, $secondary_key );
					break;

				case 'users':
				case 'user':
					$results = get_users( $args );
					$data    = $this->process_results( $results, 'ID', 'display_name', $display_keys, $secondary_key );
					break;

				case 'sites':
				case 'site':
					$sites = get_sites();

					if ( isset( $sites ) ) {
						$results = array();
						foreach ( $sites as $site ) {
							$site = (array) $site;
							$k    = $site['blog_id'];
							$v    = $site['domain'] . $site['path'];
							$name = get_blog_option( $k, 'blogname' );
							if ( ! empty( $name ) ) {
								$v .= ' - [' . $name . ']';
							}
							$results[ $k ] = $v;
						}
						$data = $this->process_results( $results, '', '', $display_keys, $secondary_key );
					}

					break;

				case 'taxonomies':
				case 'taxonomy':
				case 'tax':
					$results = get_taxonomies( $args );
					$data    = $this->process_results( $results, '', '', $display_keys, $secondary_key );
					break;

				case 'post_types':
				case 'post_type':
					global $wp_post_types;

					$output = $args['output'];
					unset( $args['output'] );
					$operator = $args['operator'];
					unset( $args['operator'] );

					$post_types = get_post_types( $args, $output, $operator );

					foreach ( $post_types as $name => $title ) {
						if ( isset( $wp_post_types[ $name ]->labels->menu_name ) ) {
							$data[ $name ] = $wp_post_types[ $name ]->labels->menu_name;
						} else {
							$data[ $name ] = ucfirst( $name );
						}
					}
					break;

				case 'menu_locations':
				case 'menu_location':
					global $_wp_registered_nav_menus;
					foreach ( $_wp_registered_nav_menus as $k => $v ) {
						$data[ $k ] = $v;
						if ( ! has_nav_menu( $k ) ) {
							$data[ $k ] .= ' ' . __( '[unassigned]', 'redux-framework' );
						}
					}
					break;

				case 'image_sizes':
				case 'image_size':
					global $_wp_additional_image_sizes;
					$results = array();
					foreach ( $_wp_additional_image_sizes as $size_name => $size_attrs ) {
						$results[ $size_name ] = $size_name . ' - ' . $size_attrs['width'] . ' x ' . $size_attrs['height'];
					}
					$data = $this->process_results( $results, '', '', $display_keys, $secondary_key );

					break;

				case 'elusive-icons':
				case 'elusive-icon':
				case 'elusive':
				case 'icons':
				case 'font-icon':
				case 'font-icons':
					$fs    = Redux_Filesystem::get_instance();
					$fonts = $fs->get_contents( Redux_Core::$dir . 'assets/css/vendor/elusive-icons.css' );
					if ( ! empty( $fonts ) ) {
						preg_match_all( '@\.el-(\w+)::before@', $fonts, $matches );
						foreach ( $matches[1] as $item ) {
							if ( 'before' === $item ) {
								continue;
							}
							$data[ 'el el-' . $item ] = $item;
						}
					}

					/**
					 * Filter 'redux/font-icons'
					 *
					 * @param array $font_icons array of elusive icon classes
					 *
					 * @deprecated
					 */

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					$font_icons = apply_filters_deprecated( 'redux/font-icons', array( $data ), '4.3', 'redux/$opt_name/field/font/icons' );

					/**
					 * Filter 'redux/{opt_name}/field/font/icons'
					 *
					 * @param array $font_icons array of elusive icon classes
					 */

					// phpcs:ignore WordPress.NamingConventions.ValidHookName
					$data = apply_filters( "redux/$opt_name/field/font/icons", $font_icons );

					break;

				case 'dashicons':
				case 'dashicon':
				case 'dash':
					$fs    = Redux_Filesystem::get_instance();
					$fonts = $fs->get_contents( ABSPATH . WPINC . '/css/dashicons.css' );
					if ( ! empty( $fonts ) ) {
						preg_match_all( '@\.dashicons-(\w+):before@', $fonts, $matches );
						foreach ( $matches[1] as $item ) {
							if ( 'before' === $item ) {
								continue;
							}
							$data[ 'dashicons dashicons-' . $item ] = $item;
						}
					}
					break;

				case 'roles':
				case 'role':
					global $wp_roles;
					$results = $wp_roles->get_names();
					$data    = $this->process_results( $results, '', '', $display_keys, $secondary_key );

					break;

				case 'sidebars':
				case 'sidebar':
					global $wp_registered_sidebars;
					$data = $this->process_results( $wp_registered_sidebars, '', 'name', $display_keys, $secondary_key );
					break;
				case 'capabilities':
				case 'capability':
					global $wp_roles;
					$results = array();
					foreach ( $wp_roles->roles as $role ) {
						foreach ( $role['capabilities'] as $key => $cap ) {
							$results[ $key ] = ucwords( str_replace( '_', ' ', $key ) );
						}
					}
					$data = $this->process_results( $results, '', '', $display_keys, $secondary_key );

					break;

				case 'capabilities_grouped':
				case 'capability_grouped':
				case 'capabilities_group':
				case 'capability_group':
					global $wp_roles;

					foreach ( $wp_roles->roles as $role ) {
						$caps = array();
						foreach ( $role['capabilities'] as $key => $cap ) {
							$caps[ $key ] = ucwords( str_replace( '_', ' ', $key ) );
						}
						asort( $caps );
						$data[ $role['name'] ] = $caps;
					}

					break;

				case 'callback':
					if ( ! empty( $args ) && is_string( $args ) && function_exists( $args ) ) {
						$data = call_user_func( $args, $current_value );
					}

					break;
			}

			return $data;
		}


		/**
		 * Router for translation based on the given post type.
		 *
		 * @param string       $type          Type of data request.
		 * @param mixed|array  $current_value Current value stored in DB.
		 * @param array|string $args          Arguments for the call.
		 */
		private function maybe_get_translation( string $type, &$current_value = '', $args = array() ) {
			switch ( $type ) {
				case 'categories':
				case 'category':
					$this->maybe_translate( $current_value, 'category' );
					break;

				case 'pages':
				case 'page':
					$this->maybe_translate( $current_value, 'page' );
					break;

				case 'terms':
				case 'term':
					$this->maybe_translate( $current_value, $args['taxonomy'] ?? '' );
					break;

				case 'tags':
				case 'tag':
					$this->maybe_translate( $current_value, 'post_tag' );
					break;

				case 'menus':
				case 'menu':
					$this->maybe_translate( $current_value, 'nav_menu' );
					break;

				case 'post':
				case 'posts':
					$this->maybe_translate( $current_value, 'post' );
					break;
				default:
					$this->maybe_translate( $current_value, '' );
			}
		}

		/**
		 * Maybe translate the values.
		 *
		 * @param mixed|array $value     Value.
		 * @param mixed|array $post_type Post type.
		 */
		private function maybe_translate( &$value, $post_type ) {

			// phpcs:ignore WordPress.NamingConventions.ValidHookName
			$value = apply_filters( "redux/options/$this->opt_name/wordpress_data/translate/post_type_value", $value, $post_type );

			// WPML Integration, see https://wpml.org/documentation/support/creating-multilingual-wordpress-themes/language-dependent-ids/.
			if ( function_exists( 'icl_object_id' ) ) {
				if ( has_filter( 'wpml_object_id' ) ) {
					if ( Redux_Helpers::is_integer( $value ) ) {
						$value = apply_filters( 'wpml_object_id', $value, $post_type, true );
					} elseif ( is_array( $value ) ) {
						$value = array_map(
							function ( $val ) use ( $post_type ) {
								return apply_filters( 'wpml_object_id', $val, $post_type, true );
							},
							$value
						);
					}
				}
			}
		}

		/**
		 * Set the default arguments for a current query (existing data).
		 *
		 * @param string       $type          Type of data request.
		 * @param array|string $args          Arguments for the call.
		 * @param mixed|array  $current_value Current value stored in DB.
		 *
		 * @return array
		 */
		private function get_current_data_args( string $type, $args, $current_value ): array {
			// In this section we set the default arguments for each data type.
			switch ( $type ) {
				case 'categories':
				case 'category':
				case 'pages':
				case 'page':
				case 'terms':
				case 'term':
				case 'users':
				case 'user':
					$args['include'] = $current_value;
					break;
				case 'tags':
				case 'tag':
					$args['get'] = 'all';
					break;
				case 'menus':
				case 'menu':
					$args['object_ids'] = $current_value;
					break;
				case 'post':
				case 'posts':
					if ( ! empty( $current_value ) ) {
						$args['post__in'] = is_array( $current_value ) ? $current_value : array( $current_value );
					}
					break;

				default:
					$args = array();
			}

			return $args;
		}


		/**
		 * Get default arguments for a given data type.
		 *
		 * @param string       $type Type of data request.
		 * @param array|string $args Arguments for the call.
		 *
		 * @return array|string
		 */
		private function get_arg_defaults( string $type, $args = array() ) {
			// In this section we set the default arguments for each data type.
			switch ( $type ) {
				case 'categories':
				case 'category':
					$args = wp_parse_args(
						$args,
						array(
							'taxonomy' => 'category',
						)
					);
					break;

				case 'pages':
				case 'page':
					$args = wp_parse_args(
						$args,
						array(
							'display_keys'   => true,
							'posts_per_page' => 20,
						)
					);
					break;

				case 'post_type':
				case 'post_types':
					$args = wp_parse_args(
						$args,
						array(
							'public'              => true,
							'exclude_from_search' => false,
							'output'              => 'names',
							'operator'            => 'and',
						)
					);

					break;

				case 'tag':
				case 'tags':
					$args = wp_parse_args(
						$args,
						array(
							'get'          => 'all',
							'display_keys' => true,
						)
					);
					break;

				case 'sidebars':
				case 'sidebar':
				case 'capabilities':
				case 'capability':
					$args = wp_parse_args(
						$args,
						array(
							'display_keys' => true,
						)
					);
					break;

				case 'capabilities_grouped':
				case 'capability_grouped':
				case 'capabilities_group':
				case 'capability_group':
					$args = wp_parse_args(
						$args,
						array(
							'data_sortby' => '',
						)
					);
					break;

			}

			return $args;
		}
	}
}
