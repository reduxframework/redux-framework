<?php // phpcs:ignore WordPress.Files.FileName

/**
 * Redux templates API class.
 *
 * @since   4.0.0
 * @package Redux Framework
 */

namespace ReduxTemplates;

use ReduxTemplates;
use WP_Patterns_Registry;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * ReduxTemplates API class.
 *
 * @since 4.0.0
 */
class Api {

	/**
	 * Seconds to cache the local files.
	 *
	 * @var int
	 */
	private $cache_time = 24 * 3600; // 24 hours
	/**
	 * Timeout for requests.
	 *
	 * @var int
	 */
	private $timeout = 145;
	/**
	 * API URL.
	 *
	 * @var string
	 */
	protected $api_base_url = 'https://api.redux.io/';
	/**
	 * License API URL.
	 *
	 * @var string
	 */
	protected $license_base_url = 'https://redux.io/';
	/**
	 * Default headers array.
	 *
	 * @var array
	 */
	protected $default_request_headers = array();
	/**
	 * Filesystem object instance.
	 *
	 * @var Filesystem
	 */
	protected $filesystem;
	/**
	 * Cache folder location.
	 *
	 * @var string
	 */
	protected $cache_folder;

	/**
	 * Constructor.
	 */
	public function __construct() {

		add_filter( 'redux_templates_api_headers', array( $this, 'request_verify' ) );
		$this->default_request_headers = apply_filters( 'redux_templates_api_headers', $this->default_request_headers );

		add_action( 'rest_api_init', array( $this, 'register_api_hooks' ), 0 );

	}

	/**
	 * Get the filesystem.
	 *
	 * @return Filesystem
	 */
	private function get_filesystem() {
		if ( empty( $this->filesystem ) ) {
			$this->filesystem = \Redux_Filesystem::get_instance();

		}

		return $this->filesystem;
	}

	/**
	 * Process the registered blocks from the library.
	 *
	 * @param array $parameters Array to be returned if no response, or to have data appended to.
	 *
	 * @return array Array of properly processed blocks and supported plugins.
	 */
	private function process_registered_blocks( $parameters ) {
		$data = $this->api_cache_fetch( array(), array(), 'library.json' );

		if ( empty( $data ) || ( ! empty( $data ) && ! isset( $data['plugins'] ) ) ) {
			return $parameters;
		}
		$supported = ReduxTemplates\Supported_Plugins::instance();
		$supported->init( $data['plugins'] );
		$plugins           = $supported::get_plugins();
		$installed_plugins = array();
		if ( ! isset( $parameters['registered_blocks'] ) ) {
			$parameters['registered_blocks'] = array();
		}

		foreach ( $plugins as $key => $value ) {
			if ( isset( $value['version'] ) ) {
				array_push( $installed_plugins, $key . '~' . $value['version'] );
				$found_already = array_search( $key, $parameters['registered_blocks'], true );
				if ( false !== $found_already ) {
					unset( $parameters['registered_blocks'][ $found_already ] );
				}
				if ( isset( $value['namespace'] ) && $key !== $value['namespace'] ) {
					$found = array_search( $value['namespace'], $parameters['registered_blocks'], true );
					if ( false !== $found ) {
						unset( $parameters['registered_blocks'][ $found ] );
					}
				}
			}
		}
		$parameters['registered_blocks'] = array_values( array_merge( $installed_plugins, $parameters['registered_blocks'] ) );

		return $parameters;
	}

	/**
	 * Process the dependencies.
	 *
	 * @param array  $data Data array.
	 * @param string $key  Key param.
	 *
	 * @return array Data array with dependencies outlined.
	 */
	private function process_dependencies( $data, $key ) {

		foreach ( $data[ $key ] as $kk => $pp ) {
			$debug = false;
			// Add debug if statement if key matches here.

			if ( isset( $pp['dependencies'] ) ) {
				foreach ( $pp['dependencies'] as $dep ) {
					if ( isset( $data['plugins'][ $dep ] ) ) {
						if ( isset( $data['plugins'][ $dep ]['no_plugin'] ) || 'core' === $dep ) {
							continue;
						}
						if ( isset( $data['plugins'][ $dep ]['free_slug'] ) ) {
							if ( isset( $data['plugins'][ $data['plugins'][ $dep ]['free_slug'] ] ) ) {
								$plugin = $data['plugins'][ $data['plugins'][ $dep ]['free_slug'] ];
								if ( ! isset( $plugin['is_pro'] ) ) {
									if ( ! isset( $data[ $key ][ $kk ]['proDependenciesMissing'] ) ) {
										$data[ $key ][ $kk ]['proDependenciesMissing'] = array();
									}
									$data[ $key ][ $kk ]['proDependenciesMissing'][] = $dep;
								}
								if ( ! isset( $data[ $key ][ $kk ]['proDependencies'] ) ) {
									$data[ $key ][ $kk ]['proDependencies'] = array();
								}
								$data[ $key ][ $kk ]['proDependencies'][] = $dep;
							}
						} else {
							if ( ! isset( $data['plugins'][ $dep ]['version'] ) ) {
								if ( ! isset( $data[ $key ][ $kk ]['installDependenciesMissing'] ) ) {
									$data[ $key ][ $kk ]['installDependenciesMissing'] = array();
								}
								$data[ $key ][ $kk ]['installDependenciesMissing'][] = $dep;
							}
							if ( ! isset( $data[ $key ][ $kk ]['installDependencies'] ) ) {
								$data[ $key ][ $kk ]['installDependencies'] = array();
							}
							$data[ $key ][ $kk ]['installDependencies'][] = $dep;
						}
					}
				}
			}

			// Print the debug here if the key exists. Use `print_r( $data[ $key ][ $kk ] );exit();`.

		}

		return $data;

	}

	/**
	 * Get the last cache time.
	 *
	 * @param string $abs_path Absolute path to a file.
	 *
	 * @return string|bool Last modified time.
	 */
	private function get_cache_time( $abs_path ) {
		$filesystem    = $this->get_filesystem();
		$last_modified = false;
		if ( $filesystem->file_exists( $abs_path ) ) {
			$last_modified = filemtime( $abs_path );
		}

		return $last_modified;
	}

	/**
	 * Fetch from the cache if had.
	 *
	 * @param array  $parameters Absolute path to a file.
	 * @param array  $config     Absolute path to a file.
	 * @param string $path       URL path perform a request to a file.
	 * @param bool   $cache_only Set to only fetch from the local cache.
	 *
	 * @return array Response and possibly the template if recovered.
	 */
	public function api_cache_fetch( $parameters, $config, $path, $cache_only = false ) {
		$filesystem = $this->get_filesystem();

		$this->cache_folder = trailingslashit( $filesystem->cache_folder ) . 'templates/';
		if ( ! $filesystem->file_exists( $this->cache_folder ) ) {
			$filesystem->mkdir( $this->cache_folder );
		}

		if ( strpos( $path, $this->cache_folder ) === false ) {
			$path = $this->cache_folder . $path;
		}
		if ( ! $filesystem->file_exists( dirname( $path ) ) ) {
			$filesystem->mkdir( dirname( $path ) );
		}

		$last_modified = file_exists( $path ) ? $this->get_cache_time( $path ) : false;

		$use_cache = true;
		if ( isset( $parameters['no_cache'] ) ) {
			$use_cache = false;
		}
		if ( ! empty( $last_modified ) ) {
			$config['headers']['Redux-Cache-Time'] = $last_modified;
			if ( time() > $last_modified + $this->cache_time ) {
				$use_cache = false;
			}
		}

		if ( $cache_only ) {
			$use_cache = true;
		}

		$data = array();
		if ( file_exists( $path ) && $use_cache ) {
			// phpcs:ignore WordPress.PHP.NoSilencedErrors
			$data = @json_decode( $filesystem->get_contents( $path ), true );
		}
		// If somehow we got an invalid response, let's not persist it.
		if ( isset( $data['message'] ) && false !== strpos( $data['message'], 'meta http-equiv="refresh" content="0;URL=' ) ) {
			$data = false;
		}

		if ( $cache_only ) {
			return $data;
		}

		if ( ! $use_cache && isset( $config['headers']['Redux-Cache-Time'] ) ) {
			unset( $config['headers']['Redux-Cache-Time'] );
		}

		if ( empty( $data ) ) {

			if ( isset( $parameters['registered_blocks'] ) ) {
				$config['headers']['Redux-Registered-Blocks'] = implode( ',', $parameters['registered_blocks'] );
			}

			$results = $this->api_request( $config );

			if ( ! empty( $results ) ) {
				// phpcs:ignore WordPress.PHP.NoSilencedErrors
				$data = @json_decode( $results, true );

				if ( isset( $data['use_cache'] ) ) {
					// phpcs:ignore WordPress.PHP.NoSilencedErrors
					$data          = @json_decode( $filesystem->get_contents( $path ), true );
					$data['cache'] = 'used';
				} else {
					if ( empty( $data ) ) {
						$data = array( 'message' => $results );
					}
					if ( isset( $data['status'] ) && 'error' === $data['status'] ) {
						wp_send_json_error( array( 'message' => $data['message'] ) );
					}
					$filesystem->put_contents( $path, wp_json_encode( $data ) );
				}
			} else {
				wp_send_json_error( array( 'message' => __( 'API fetch failure.', 'redux-framework' ) ) );
			}
		}

		if ( empty( $data ) ) {
			// phpcs:ignore WordPress.PHP.NoSilencedErrors
			$data = @json_decode( $filesystem->get_contents( $path ), true );
			if ( $data ) {
				$data['status']  = 'error';
				$data['message'] = __( 'Fetching failed, used a cached version.', 'redux-framework' );
			} else {
				$data = array(
					'message' => 'Error Fetching',
				);
			}
		} else {
			if ( ! $use_cache ) {
				$data['cache'] = 'cleared';
			}
		}

		return $data;
	}

	/**
	 * Get library index. Support for library, collections, pages, sections all in a single request.
	 *
	 * @param \WP_REST_Request $request WP Rest request.
	 *
	 * @since 4.0.0
	 */
	public function get_index( \WP_REST_Request $request ) {

		$parameters = $request->get_params();
		$attributes = $request->get_attributes();

		$type = $request->get_route();
		$type = explode( '/', $type );
		$type = end( $type );

		if ( empty( $type ) ) {
			wp_send_json_error( 'No type specified.' );
		}

		$config    = array(
			'path' => 'library/',
		);
		$test_path = dirname( __FILE__ ) . '/library.json';

		if ( file_exists( $test_path ) ) {
			$data = json_decode( ReduxTemplates\Init::get_local_file_contents( $test_path ), true );
		} else {
			$parameters['no_cache'] = 1;
			$data                   = $this->api_cache_fetch( $parameters, $config, 'library/' );
		}

		if ( isset( $data['plugins'] ) ) {
			$supported = ReduxTemplates\Supported_Plugins::instance();
			$supported->init( $data['plugins'] );
			$data['plugins']                               = $supported::get_plugins();
			$data['plugins']['redux-framework']['version'] = \Redux_Core::$version;
			if ( \Redux_Helpers::mokama() ) {
				if ( class_exists( 'Redux_Pro' ) ) {
					$data['plugins']['redux-framework']['is_pro'] = \Redux_Pro::$version;
				} else {
					$data['plugins']['redux-framework']['is_pro'] = \Redux_Core::$version;
				}
			}
			$data = $this->process_dependencies( $data, 'sections' );
			$data = $this->process_dependencies( $data, 'pages' );
		}

		if ( class_exists( 'WP_Patterns_Registry' ) ) {
			$patterns = \WP_Patterns_Registry::get_instance()->get_all_registered();
			foreach ( $patterns as $k => $p ) {
				$id                      = 'wp_block_pattern_' . $k;
				$data['sections'][ $id ] = array(
					'name'       => $p['title'],
					'categories' => array( 'WP Block Patterns' ),
					'source'     => 'wp_block_patterns',
					'id'         => $id,
				);
			}
		}

		wp_send_json_success( $data );
	}

	/**
	 * Filter an array recursively.
	 *
	 * @param array $input Array to filter.
	 *
	 * @return array Filtered array.
	 */
	private function array_filter_recursive( $input ) {
		foreach ( $input as &$value ) {
			if ( is_array( $value ) ) {
				$value = $this->array_filter_recursive( $value );
			}
		}

		return array_filter( $input );
	}

	/**
	 * Method for transmitting a template the user is sharing remotely.
	 *
	 * @param \WP_REST_Request $request WP Rest request.
	 *
	 * @since 4.0.0
	 */
	public function share_template( \WP_REST_Request $request ) {
		$parameters = $request->get_params();
		$attributes = $request->get_attributes();
		$parameters = $this->process_registered_blocks( $parameters );

		if ( empty( $parameters ) ) {
			wp_send_json_error( 'No template data found.' );
		}

		$config = array(
			'path'           => 'template_share/',
			'uid'            => get_current_user_id(),
			'editor_content' => isset( $parameters['editor_content'] ) ? (string) $parameters['editor_content'] : '',
			'editor_blocks'  => isset( $parameters['editor_blocks'] ) ? $parameters['editor_blocks'] : '',
			'postID'         => isset( $parameters['postID'] ) ? (string) sanitize_text_field( $parameters['postID'] ) : '',
			'title'          => isset( $parameters['title'] ) ? (string) sanitize_text_field( $parameters['title'] ) : 'The Title',
			'type'           => isset( $parameters['type'] ) ? (string) sanitize_text_field( $parameters['type'] ) : 'page',
			'categories'     => isset( $parameters['categories'] ) ? (string) sanitize_text_field( $parameters['categories'] ) : '',
			'description'    => isset( $parameters['description'] ) ? (string) sanitize_text_field( $parameters['description'] ) : '',
			'headers'        => array(
				'Redux-Registered-Blocks' => isset( $parameters['registered_blocks'] ) ? (string) sanitize_text_field( implode( ',', $parameters['registered_blocks'] ) ) : '',
			),
		);

		$config = $this->array_filter_recursive( $config );

		if ( ! isset( $config['title'] ) ) {
			wp_send_json_error( array( 'messages' => 'A title is required.' ) );
		}
		if ( ! isset( $config['type'] ) ) {
			wp_send_json_error( array( 'messages' => 'A type is required.' ) );
		}

		$response = $this->api_request( $config );

		// phpcs:ignore WordPress.PHP.NoSilencedErrors
		$data = @json_decode( $response, true );

		if ( 'success' === $data['status'] && isset( $data['url'] ) ) {
			wp_send_json_success( array( 'url' => $data['url'] ) );
		}

		wp_send_json_error( $data );

	}

	/**
	 * Run an API request.
	 *
	 * @param array $data Array related to an API request.
	 *
	 * @return string API response string.
	 */
	public function api_request( $data ) {

		$api_url = $this->api_base_url;

		if ( isset( $data['path'] ) ) {
			if ( 'library/' === $data['path'] ) {
				$api_url = 'https://files.redux.io/library.json';
				$request = wp_remote_get( $api_url, array( 'timeout' => $this->timeout ) );
				if ( is_wp_error( $request ) ) {
					wp_send_json_error(
						array(
							'success'       => 'false',
							'message'       => $request->get_error_messages(),
							'message_types' => 'error',
						)
					);
				}
				if ( 404 === wp_remote_retrieve_response_code( $request ) ) {
					wp_send_json_error(
						array(
							'success'       => 'false',
							'message'       => 'Error fetching library, URL not found. Please try again',
							'message_types' => 'error',
						)
					);
				}
				return $request['body'];
			}
			$api_url = $api_url . $data['path'];
		}

		if ( isset( $data['_locale'] ) ) {
			unset( $data['_locale'] );
		}
		$headers = array();
		if ( isset( $data['headers'] ) ) {
			$headers = $data['headers'];
			unset( $data['headers'] );
		}
		if ( isset( $data['p'] ) ) {
			$headers['Redux-P'] = $data['p'];
			unset( $data['p'] );
		}
		if ( isset( $data['path'] ) ) {
			$headers['Redux-Path'] = $data['path'];
			unset( $data['path'] );
		}

		$headers['Redux-Slug'] = \Redux_Helpers::get_hash();

		$headers = wp_parse_args( $headers, $this->default_request_headers );

		$headers['Content-Type'] = 'application/json; charset=utf-8';
		$headers                 = array_filter( $headers );

		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && ! empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$headers['Redux-User-Agent'] = (string) sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) );
		}

		$headers['Redux-SiteURL'] = get_site_url( get_current_blog_id() );

		$post_args = array(
			'timeout'     => $this->timeout,
			'body'        => wp_json_encode( $data ),
			'method'      => 'POST',
			'data_format' => 'body',
			'redirection' => 5,
			'headers'     => $headers,
		);

		$request = wp_remote_post( $api_url, $post_args );

		// Handle redirects.
		if ( ! is_wp_error( $request ) && isset( $request['http_response'] ) && $request['http_response'] instanceof \WP_HTTP_Requests_Response && method_exists( $request['http_response'], 'get_response_object' ) && strpos( $request['http_response']->get_response_object()->url, 'files.redux.io' ) !== false ) {
			if ( isset( $data['no_redirect'] ) ) {
				return $request['http_response']->get_response_object()->url;
			} else {
				$request = wp_remote_get( $request['http_response']->get_response_object()->url, array( 'timeout' => $this->timeout ) );
			}
		}

		if ( is_wp_error( $request ) ) {

			wp_send_json_error(
				array(
					'success'       => 'false',
					'message'       => $request->get_error_messages(),
					'message_types' => 'error',
				)
			);
		}

		if ( 404 === wp_remote_retrieve_response_code( $request ) ) {
			return false;
		}

		return $request['body'];
	}

	/**
	 * Fetch a single template.
	 *
	 * @param \WP_REST_Request $request WP Rest request.
	 *
	 * @since 4.0.0
	 */
	public function get_template( \WP_REST_Request $request ) {

		$parameters = $request->get_params();
		$attributes = $request->get_attributes();
		$parameters = $this->process_registered_blocks( $parameters );

		if ( in_array( $parameters['type'], array( 'sections', 'pages' ), true ) ) {
			$parameters['type'] = substr_replace( $parameters['type'], '', - 1 );
		}

		$config = array(
			'path'   => 'template/',
			'id'     => sanitize_text_field( $parameters['id'] ),
			'type'   => (string) sanitize_text_field( $parameters['type'] ),
			'source' => isset( $parameters['source'] ) ? $parameters['source'] : '',
		);

		$template_response = $this->check_template_response( $parameters );

		if ( 'wp_block_patterns' === $config['source'] && class_exists( 'WP_Patterns_Registry' ) ) {
			$patterns = \WP_Patterns_Registry::get_instance()->get_all_registered();
			$id       = explode( '_', $config['id'] );
			$id       = end( $id );

			if ( isset( $patterns[ $id ] ) ) {
				$response = array( 'template' => $patterns[ $id ]['content'] );
			}
		} else {
			$cache_path             = $config['type'] . DIRECTORY_SEPARATOR . $config['id'] . '.json';
			$parameters['no_cache'] = 1;
			$response               = $this->api_cache_fetch( $parameters, $config, $cache_path );
			if ( false === $response ) {
				wp_send_json_error(
					array(
						'success'       => 'false',
						'message'       => 'Error fetching template. Please try again',
						'message_types' => 'error',
					)
				);
			}
			$response = wp_parse_args( $response, $template_response );
		}

		if ( ! empty( $response ) && isset( $response['message'] ) ) {
			$response['template'] = $response['message'];
			unset( $response['message'] );
		}

		wp_send_json_success( $response );
	}

	/**
	 * Check template reponse.
	 *
	 * @param array $parameters Parameters array.
	 *
	 * @return array
	 * @since 4.0.0
	 */
	public function check_template_response( $parameters ) {
		$response = array();
		if ( ! \Redux_Helpers::mokama() ) {
			if ( \Redux_Functions_Ex::activated() ) {
				$response['left'] = 999;
			} else {
				$count = ReduxTemplates\Init::left( $parameters['uid'] );

				$count = intval( $count ) - 1;
				if ( 0 === $count ) {
					$response['left'] = $count;
					update_user_meta( $parameters['uid'], '_redux_templates_counts', -1 );
				} elseif ( $count < 0 ) {
					wp_send_json_error(
						array(
							'message' => 'Please activate Redux',
							'left'    => 0,
						)
					);
				} else {
					update_user_meta( $parameters['uid'], '_redux_templates_counts', $count );
				}
				$response['left'] = $count;
			}
		}

		return $response;
	}

	/**
	 * Check template reponse.
	 *
	 * @param array $parameters Parameters array.
	 *
	 * @since 4.0.0
	 */
	public function activate_redux( $parameters ) {
		if ( \Redux_Functions_Ex::activated() ) {
			$response['left'] = 999;
		} else {
			\Redux_Core::$insights->optin();
			if ( \Redux_Functions_Ex::activated() ) {
				$response['left'] = 999;
			} else {
				$count = get_user_meta( get_current_user_id(), '_redux_templates_counts', true );
				if ( false === $count ) {
					$count = Init::$default_left;
					update_user_meta( get_current_user_id(), '_redux_templates_counts', $count );
				}
				$response = array(
					'left' => $count,
				);
			}
		}
		if ( 999 === $response['left'] ) {
			wp_send_json_success( $response );
		}
		wp_send_json_error( $response );
	}

	/**
	 * Fetch a single template.
	 *
	 * @param array $data Data array.
	 *
	 * @return array
	 * @since 4.0.0
	 */
	public function request_verify( $data = array() ) {
		$config = array(
			'Redux-Version'   => REDUXTEMPLATES_VERSION,
			'Redux-Multisite' => is_multisite(),
			'Redux-Mokama'    => \Redux_Helpers::mokama(),
			'Redux-Insights'  => \Redux_Core::$insights->tracking_allowed(),
		);

		// TODO - Update this with the EDD key or developer key.
		$config['Redux-API-Key'] = '';

		if ( ! empty( \Redux_Core::$pro_loaded ) && \Redux_Core::$pro_loaded ) {
			$config['Redux-Pro'] = \Redux_Core::$pro_loaded;
		}
		$data = wp_parse_args( $data, $config );

		return $data;
	}


	/**
	 * Get all saved blocks (reusable blocks).
	 *
	 * @param \WP_REST_Request $request WP Rest request.
	 *
	 * @since 4.0.0
	 */
	public function get_saved_blocks( \WP_REST_Request $request ) {
		$args      = array(
			'post_type'   => 'wp_block',
			'post_status' => 'publish',
		);
		$r         = wp_parse_args( null, $args );
		$get_posts = new \WP_Query();
		$wp_blocks = $get_posts->query( $r );
		wp_send_json_success( $wp_blocks );
	}

	/**
	 * Delete a single saved (reusable) block
	 *
	 * @param \WP_REST_Request $request WP Rest request.
	 *
	 * @since 4.0.0
	 */
	public function delete_saved_block( \WP_REST_Request $request ) {
		$parameters = $request->get_params();
		$attributes = $request->get_attributes();
		if ( ! isset( $parameters['block_id'] ) ) {
			return wp_send_json_error(
				array(
					'status'  => 'error',
					'message' => 'Missing block_id.',
				)
			);
		}

		$block_id      = (int) sanitize_text_field( wp_unslash( $parameters['block_id'] ) );
		$deleted_block = wp_delete_post( $block_id );

		wp_send_json_success( $deleted_block );
	}

	/**
	 * Record that the user has used the welcome guide.
	 *
	 * @param \WP_REST_Request $request WP Rest request.
	 *
	 * @since 4.0.0
	 */
	public function welcome_guide( \WP_REST_Request $request ) {
		$parameters = $request->get_params();
		$attributes = $request->get_attributes();
		if ( ! isset( $parameters['uid'] ) ) {
			return wp_send_json_error(
				array(
					'status'  => 'error',
					'message' => 'User ID required.',
				)
			);
		}
		update_user_meta( $parameters['uid'], '_redux_welcome_guide', '1' );
		wp_send_json_success( array( 'status' => 'success' ) );
	}

	/**
	 * Method used to register all rest endpoint hooks.
	 *
	 * @since 4.0.0
	 */
	public function register_api_hooks() {

		$hooks = array(
			'library'            => array(
				'callback' => 'get_index',
			),
			'pages'              => array(
				'callback' => 'get_index',
			),
			'sections'           => array(
				'callback' => 'get_index',
			),
			'collections'        => array(
				'callback' => 'get_index',
			),
			'feedback'           => array(
				'callback' => 'send_feedback',
			),
			'suggestion'         => array(
				'callback' => 'send_suggestion',
			),
			'template'           => array(
				'callback' => 'get_template',
			),
			'share'              => array(
				'method'   => 'POST',
				'callback' => 'share_template',
			),
			'get_saved_blocks'   => array(
				'callback' => 'get_saved_blocks',
			),
			'delete_saved_block' => array(
				'method'   => 'POST',
				'callback' => 'delete_saved_block',
			),
			'activate'           => array(
				'method'   => 'GET',
				'callback' => 'activate_redux',
			),
			'plugin-install'     => array(
				'method'   => 'GET',
				'callback' => 'plugin_install',
			),
			'license'            => array(
				'method'   => 'GET',
				'callback' => 'license',
			),
			'license-validate'   => array(
				'method'   => 'GET',
				'callback' => 'validate_license',
			),
			'license-activate'   => array(
				'method'   => 'GET',
				'callback' => 'activate_license',
			),
			'license-deactivate' => array(
				'method'   => 'GET',
				'callback' => 'deactivate_license',
			),
			'get-pro-url'        => array(
				'method'   => 'GET',
				'callback' => 'get_pro_url',
			),
			'opt_out'            => array(
				'method'   => 'GET',
				'callback' => 'opt_out_account',
			),
			'welcome'            => array(
				'method'   => 'POST',
				'callback' => 'welcome_guide',
			),
			'nps'                => array(
				'method'   => 'POST',
				'callback' => 'send_nps',
			),
		);
		$fs    = \Redux_Filesystem::get_instance();

		foreach ( $hooks as $route => $data ) {
			$methods = array( 'GET', 'POST' );
			if ( isset( $data['method'] ) ) {
				$methods = explode( ',', $data['method'] );
			}

			foreach ( $methods as $method ) {
				$args = array(
					'methods'  => $method,
					'callback' => array( $this, $data['callback'] ),
					'args'     => array(),
				);
				if ( defined( 'REDUX_PLUGIN_FILE' ) && ! $fs->file_exists( trailingslashit( dirname( REDUX_PLUGIN_FILE ) ) . 'local_developer.txt' ) ) {
					$args['permission_callback'] = function () {
						return current_user_can( 'install_plugins' );
					};
				} else {
					$args['permission_callback'] = '__return_true';
				}

				register_rest_route(
					'redux/v1/templates',
					$route,
					$args
				);
			}
		}

	}

	/**
	 * Install plugin endpoint.
	 *
	 * @param \WP_REST_Request $request WP Rest request.
	 *
	 * @since 4.0.0
	 */
	public function plugin_install( \WP_REST_Request $request ) {

		$data = $request->get_params();

		if ( empty( $data['slug'] ) ) {
			wp_send_json_error(
				array(
					'error' => __( 'Slug not specified.', 'redux-framework' ),
				)
			);
		}

		$slug = (string) sanitize_text_field( $data['slug'] );
		if ( ! empty( $data['redux_pro'] ) ) {
			if ( \Redux_Helpers::mokama() || 'redux-pro' === $slug ) {
				$config                 = array(
					'path'        => 'installer/',
					'slug'        => $slug,
					'no_redirect' => true,
				);
				$parameters['no_cache'] = 1;

				if ( 'redux-pro' === $slug ) {

					$lic_status = get_option( 'redux_pro_license_status', false );

					if ( 'valid' !== $lic_status && 'active' !== $lic_status ) {
						$status = array(
							'error' => __( 'Redux Pro license not active, please activate.', 'redux-framework' ),
						);
					} else {
						$array   = array(
							'edd_action' => 'get_version',
						);
						$request = $this->do_license_request( $array );

						if ( isset( $request['download_link'] ) ) {
							$status = ReduxTemplates\Installer::run( $slug, $request['download_link'] );
						} else {
							$status = array(
								'error' => __( 'Invalid license key.', 'redux-framework' ),
							);
							delete_option( 'redux_pro_license_status' );
						}
					}
				} else {
					$response = $this->api_cache_fetch( $parameters, $config, false, false );
					if ( isset( $response['message'] ) && false !== strpos( $response['message'], 'redux.io' ) ) {
						$status = ReduxTemplates\Installer::run( $slug, $response['message'] );
					} else {
						if ( isset( $response['error'] ) && ! empty( $response['error'] ) ) {
							$status = array(
								'error' => $response['error'],
							);
						} else {
							$status = array(
								'error' => __( 'A valid Redux Pro subscription is required.', 'redux-framework' ),
							);
						}
					}
				}
			} else {
				$status = array(
					'error' => __( 'A valid Redux Pro subscription is required.', 'redux-framework' ),
				);
			}
		} else {
			$status = ReduxTemplates\Installer::run( $slug );
		}

		if ( isset( $status['error'] ) ) {
			wp_send_json_error( $status );
		}
		if ( 'otter-blocks' === $slug ) {
			update_option( 'themeisle_blocks_settings_default_block', false );
		}
		wp_send_json_success( $status );
	}

	/**
	 * Check the license key.
	 *
	 * @since 4.1.18
	 * @return bool|array
	 */
	protected function check_license_key() {
		$lic = get_option( 'redux_pro_license_key' );
		if ( empty( $lic ) ) {
			delete_option( 'redux_pro_license_status' );
			return array(
				'success'       => 'false',
				'message'       => 'No license found.',
				'message_types' => 'error',
			);
		}
		return true;
	}

	/**
	 * Run the license API calls.
	 *
	 * @param \WP_REST_Request $request WP Rest request.
	 * @since 4.1.18
	 */
	public function license( \WP_REST_Request $request ) {
		$data = $request->get_params();

		if ( ! isset( $data['key'] ) || ( isset( $data['key'] ) && empty( $data['key'] ) ) ) {
			wp_send_json_error(
				array(
					'success'       => 'false',
					'message'       => 'License key not provided or empty.',
					'message_types' => 'error',
				)
			);
		}

		$array    = array(
			'edd_action' => 'check_license',
			'license'    => $data['key'],
		);
		$response = $this->do_license_request( $array );

		if ( isset( $response['license'] ) && in_array( $response['license'], array( 'valid', 'site_inactive', 'inactive' ), true ) ) {
			if ( 'valid' === $response['license'] ) {
				wp_send_json_success( array( 'status' => 'success' ) );
			} else {
				if ( 0 === $response['data']['activations_left'] ) {
					delete_option( 'redux_pro_license_key' );
					wp_send_json_error(
						array(
							'status'  => 'error',
							'message' => __( 'You have reached your activation limits for Redux Pro', 'redux-framework' ),
						)
					);
				}
				update_option( 'redux_pro_license_key', $data['key'] );

				$array = array(
					'edd_action' => 'activate_license',
				);

				$response = $this->do_license_request( $array );
				if ( isset( $response['license'] ) && 'valid' === $response['license'] ) {
					\Redux_Functions_Ex::set_activated();
					wp_send_json_success( $response );
				}
			}
		}
		wp_send_json_error(
			array(
				'status'   => 'error',
				'msg'      => __( 'Invalid license key.', 'redux-framework' ),
				'response' => $response,
			)
		);
	}

	/**
	 * Validate a license key.
	 *
	 * @param \WP_REST_Request $request WP Rest request.
	 * @since 4.1.18
	 */
	public function validate_license( \WP_REST_Request $request ) {

		$data = $request->get_params();

		if ( ! isset( $data['key'] ) || ( isset( $data['key'] ) && empty( $data['key'] ) ) ) {
			wp_send_json_error(
				array(
					'success'       => 'false',
					'message'       => 'License key not provided or empty.',
					'message_types' => 'error',
				)
			);
		}

		$array    = array(
			'edd_action' => 'check_license',
			'license'    => $data['key'],
		);
		$response = $this->do_license_request( $array );

		if ( isset( $response['license'] ) && in_array( $response['key'], array( 'valid', 'site_inactive' ), true ) ) {
			update_option( 'redux_pro_license_status', $data['key'] );
			wp_send_json_success( $response );
		} else {
			wp_send_json_error(
				$response
			);
		}
	}

	/**
	 * Activate a license key.
	 *
	 * @param \WP_REST_Request $request WP Rest request.
	 * @since 4.1.18
	 */
	public function activate_license( \WP_REST_Request $request ) {
		$check = $this->check_license_key();
		if ( is_array( $check ) ) {
			wp_send_json_error( $check );
		}
		$lic = get_option( 'redux_pro_license_key' );
		if ( empty( $lic ) ) {
			delete_option( 'redux_pro_license_status' );
			wp_send_json_error(
				array(
					'success'       => 'false',
					'message'       => 'No license found.',
					'message_types' => 'error',
				)
			);
		}

		$array   = array(
			'edd_action' => 'activate_license',
		);
		$request = $this->do_license_request( $array );

		if ( isset( $request['license'] ) && 'valid' === $request['license'] ) {
			wp_send_json_success( $request );
		}
		wp_send_json_error(
			array(
				'success'       => 'false',
				'message'       => 'License is not valid.',
				'message_types' => 'error',
			)
		);
	}

	/**
	 * Deactivate a license key.
	 *
	 * @since 4.1.18
	 */
	public function deactivate_license() {
		$check = $this->check_license_key();
		if ( is_array( $check ) ) {
			wp_send_json_error( $check );
		}
		$lic = get_option( 'redux_pro_license_key' );
		if ( empty( $lic ) ) {
			wp_send_json_error(
				array(
					'success'       => 'false',
					'message'       => 'No license found.',
					'message_types' => 'error',
				)
			);
		}

		$array   = array(
			'edd_action' => 'deactivate_license',
		);
		$request = $this->do_license_request( $array );
		if ( isset( $request['license'] ) && 'deactivated' === $request['license'] ) {
			delete_option( 'redux_pro_license_key' );
			wp_send_json_success( $request );
		}
		wp_send_json_error(
			array(
				'success'       => 'false',
				'message'       => 'License is not valid.',
				'message_types' => 'error',
			)
		);
	}

	/**
	 * Get the Redux Pro download URL.
	 *
	 * @since 4.1.18
	 */
	public function get_pro_url() {
		$lic_status = get_option( 'redux_pro_license_status', 'inactive' );

		if ( 'valid' !== $lic_status && 'active' !== $lic_status ) {
			wp_send_json_error(
				array(
					'success'       => 'false',
					'message'       => 'License not active, please activate.',
					'message_types' => 'error',
				)
			);
		}

		$array   = array(
			'edd_action' => 'get_version',
		);
		$request = $this->do_license_request( $array );

		if ( isset( $request['download_link'] ) ) {
			wp_send_json_success( $request['download_link'] );
		}

		wp_send_json_error(
			array(
				'success'       => 'false',
				'message'       => 'Could not recover Pro download URL.',
				'message_types' => 'error',
			)
		);
	}

	/**
	 * Run the license API calls.
	 *
	 * @param array $args Array of args.
	 * @since 4.1.18
	 * @return mixed
	 */
	private function do_license_request( $args ) {

		$defaults = array(
			'item_name' => 'Redux Pro',
			'url'       => network_site_url(),
			'license'   => get_option( 'redux_pro_license_key' ),
		);
		$args     = wp_parse_args( $args, $defaults );

		if ( ! isset( $args['edd_action'] ) || ( isset( $args['edd_action'] ) && empty( $args['edd_action'] ) ) ) {
			wp_send_json_error(
				array(
					'success'       => 'false',
					'message'       => 'Missing edd_action.',
					'message_types' => 'error',
				)
			);
		}

		if ( 'check_license' !== $args['edd_action'] ) {
			$check = $this->check_license_key();
			if ( is_array( $check ) ) {
				wp_send_json_error( $check );
			}
		}

		$url = add_query_arg( $args, $this->license_base_url );

		$request = wp_remote_get( $url, array( 'timeout' => $this->timeout ) );
		if ( is_wp_error( $request ) ) {
			wp_send_json_error(
				array(
					'success'       => 'false',
					'message'       => $request->get_error_messages(),
					'message_types' => 'error',
				)
			);
		}
		if ( 404 === wp_remote_retrieve_response_code( $request ) ) {
			wp_send_json_error(
				array(
					'success'       => 'false',
					'message'       => 'Our API appears to be down... please try again later.',
					'message_types' => 'error',
				)
			);
		}
		$data = json_decode( $request['body'], true );
		if ( isset( $data['license'] ) ) {
			update_option( 'redux_pro_license_status', $data['license'] );
		}
		return $data;
	}

	/**
	 * Send the NPS value.
	 *
	 * @param \WP_REST_Request $request WP Rest request.
	 * @since 4.1.19
	 */
	public function send_nps( \WP_REST_Request $request ) {
		$data = $request->get_params();

		if ( empty( $data['nps'] ) ) {
			wp_send_json_error(
				array(
					'error' => __( 'NPS not specified.', 'redux-framework' ),
				)
			);
		}

		$nps         = (string) sanitize_text_field( $data['nps'] );
		$the_request = array(
			'path' => 'nps',
			'nps'  => $nps,
		);
		$response    = $this->api_request( $the_request );
		if ( false === $response ) {
			wp_send_json_error(
				array(
					'message' => __( 'Could not record score.', 'redux-framework' ),
				)
			);
		}
		wp_send_json_success( json_decode( $response ) );
	}

}
