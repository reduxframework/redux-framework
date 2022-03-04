<?php
/**
 * Redux Filesystem Class
 *
 * @class Redux_Filesystem
 * @version 4.0.0
 * @package Redux Framework/Classes
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Filesystem', false ) ) {

	/**
	 * Class Redux_Filesystem
	 */
	class Redux_Filesystem {

		/**
		 * Instance of this class.
		 *
		 * @since    1.0.0
		 * @var      object
		 */
		protected static $instance = null;

		/**
		 * WP Filesystem object.
		 *
		 * @var object
		 */
		protected static $direct = null;

		/**
		 * File system credentials.
		 *
		 * @var array
		 */
		private $creds = array();

		/**
		 * ReduxFramework object pointer.
		 *
		 * @var object
		 */
		public $parent = null;

		/**
		 * Instance of WP_Filesystem
		 *
		 * @var WP_Filesystem|null
		 */
		private $wp_filesystem;

		/**
		 * If DBI_Filesystem should attempt to use the WP_Filesystem class.
		 *
		 * @var bool
		 */
		private $use_filesystem = false;

		/**
		 * Default chmod octal value for directories.
		 *
		 * @var int
		 */
		private $chmod_dir;

		/**
		 * Default chmod octal value for files.
		 *
		 * @var int
		 */
		private $chmod_file;

		/**
		 * Default cache folder.
		 *
		 * @var string
		 */
		public $cache_folder;

		/**
		 * Pass `true` when instantiating to skip using WP_Filesystem.
		 *
		 * @param bool $force_no_fs Force no use of the filesystem.
		 */
		public function __construct( bool $force_no_fs = false ) {

			// This little number fixes some issues with certain filesystem setups.

			if ( ! function_exists( 'request_filesystem_credentials' ) ) {
				require_once ABSPATH . '/wp-admin/includes/template.php';
				require_once ABSPATH . '/wp-includes/pluggable.php';
				require_once ABSPATH . '/wp-admin/includes/file.php';
			}

			if ( ! $force_no_fs && function_exists( 'request_filesystem_credentials' ) ) {
				if ( ( defined( 'WPMDB_WP_FILESYSTEM' ) && WPMDB_WP_FILESYSTEM ) || ! defined( 'WPMDB_WP_FILESYSTEM' ) ) {
					$this->maybe_init_wp_filesystem();
				}
			}

			$uploads_dir        = wp_upload_dir();
			$this->cache_folder = trailingslashit( $uploads_dir['basedir'] ) . 'redux/';
			if ( ! $this->file_exists( $this->cache_folder ) ) {
				$this->mkdir( $this->cache_folder );
			}
		}

		/**
		 * Return an instance of this class.
		 *
		 * @param object $parent ReduxFramework pointer.
		 *
		 * @since     1.0.0
		 * @return    object    A single instance of this class.
		 */
		public static function get_instance( $parent = null ) {

			// If the single instance hasn't been set, set it now.
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			if ( null !== $parent ) {
				self::$instance->parent = $parent;
			}

			return self::$instance;
		}

		/**
		 * Build FTP form.
		 */
		public function ftp_form() {
			if ( isset( $this->parent->ftp_form ) && ! empty( $this->parent->ftp_form ) ) {
				echo '<div class="wrap">';
				echo '<div class="error">';
				echo '<p>';
				// translators: %1$s: Upload URL.  %2$s: Codex URL.
				echo '<strong>' . esc_html__( 'File Permission Issues', 'redux-framework' ) . '</strong><br/>' . sprintf( esc_html__( 'We were unable to modify required files. Please ensure that %1$s has the proper read-write permissions, or modify your wp-config.php file to contain your FTP login credentials as %2$s.', 'redux-framework' ), '<code>' . esc_url( Redux_Functions_Ex::wp_normalize_path( trailingslashit( WP_CONTENT_DIR ) ) . '/uploads/' ) . '</code>', ' <a href="https://codex.wordpress.org/Editing_wp-config.php#WordPress_Upgrade_Constants" target="_blank">' . esc_html__( 'outlined here', 'redux-framework' ) . '</a>' );
				echo '</p>';
				echo '</div>';
				echo '<h2></h2>';
				echo '</div>';
			}
		}

		/**
		 * Attempt to initiate WP_Filesystem
		 * If this fails, $use_filesystem is set to false and all methods in this class should use native php fallbacks
		 * Thwarts `request_filesystem_credentials()` attempt to display a form for obtaining creds from users
		 * TODO: provide notice and input in wp-admin for users when this fails
		 */
		public function maybe_init_wp_filesystem() {
			// Set up the filesystem with creds.
			require_once ABSPATH . '/wp-admin/includes/template.php';
			require_once ABSPATH . '/wp-includes/pluggable.php';
			require_once ABSPATH . '/wp-admin/includes/file.php';
			ob_start();
			$credentials = request_filesystem_credentials( '', '', false, false );
			$ob_contents = ob_get_contents();
			ob_end_clean();
			if ( @wp_filesystem( $credentials ) ) { // phpcs:ignore WordPress.PHP.NoSilencedErrors
				global $wp_filesystem;
				$this->wp_filesystem  = $wp_filesystem;
				$this->use_filesystem = true;
				$this->generate_default_files();
			}
		}

		/**
		 * Init WO Filesystem.
		 *
		 * @param string $form_url Form URL.
		 * @param string $method   Connect method.
		 * @param bool   $context  Context.
		 * @param null   $fields   Fields.
		 *
		 * @return bool
		 */
		public function advanced_filesystem_init( string $form_url, string $method = '', bool $context = false, $fields = null ): bool {
			if ( ! empty( $this->wp_filesystem ) && $this->use_filesystem ) {
				return true;
			}

			if ( ! empty( $this->creds ) ) {
				return true;
			}

			ob_start();

			$this->creds = request_filesystem_credentials( $form_url, $method, false, $context );

			/* first attempt to get credentials */
			if ( false === $this->creds ) {
				$this->creds            = array();
				$this->parent->ftp_form = ob_get_contents();
				ob_end_clean();

				/**
				 * If we come here - we don't have credentials
				 * so the request for them is displaying
				 * no need for further processing
				 * */
				return false;
			}

			/* now we got some credentials - try to use them */
			if ( ! @wp_filesystem( $this->creds ) ) { // phpcs:ignore WordPress.PHP.NoSilencedErrors
				$this->creds = array();
				/* incorrect connection data - ask for credentials again, now with error message */
				request_filesystem_credentials( $form_url, '', true, $context );
				$this->parent->ftp_form = ob_get_contents();
				ob_end_clean();

				return false;
			}

			global $wp_filesystem;
			$this->wp_filesystem  = $wp_filesystem;
			$this->use_filesystem = true;
			$this->generate_default_files();

			return true;
		}

		/**
		 * Load WP filesystem directly.
		 */
		public static function load_direct() {
			if ( null === self::$direct ) {
				require_once ABSPATH . '/wp-admin/includes/class-wp-filesystem-base.php';
				require_once ABSPATH . '/wp-admin/includes/class-wp-filesystem-direct.php';

				self::$direct = new WP_Filesystem_Direct( array() );
			}
		}

		/**
		 * Execute filesystem request.
		 *
		 * @param string $action Action to perform.
		 * @param string $file File to perform upon.
		 * @param array  $params Argument for action.
		 *
		 * @return bool|void
		 */
		public function execute( string $action, string $file = '', array $params = array() ) {
			if ( empty( $this->parent->args ) ) {
				return;
			}

			if ( ! empty( $params ) ) {
				// phpcs:ignore WordPress.PHP.DontExtract
				extract( $params );
			}

			if ( empty( $this->wp_filesystem ) ) {
				if ( 'submenu' === $this->parent->args['menu_type'] ) {
					$page_parent = $this->parent->args['page_parent'];
					$base        = $page_parent . '?page=' . $this->parent->args['page_slug'];
				} else {
					$base = 'admin.php?page=' . $this->parent->args['page_slug'];
				}

				$url = wp_nonce_url( $base, 'redux-options' );
				$this->advanced_filesystem_init( $url, 'direct', dirname( $file ) );
			}

			return $this->do_action( $action, $file, $params );
		}


		/**
		 * Generates the default Redux cache folder.
		 *
		 * @return void
		 */
		private function generate_default_files() {

			// Set default permissions.
			if ( defined( 'FS_CHMOD_DIR' ) ) {
				$this->chmod_dir = FS_CHMOD_DIR;
			} else {
				$this->chmod_dir = ( fileperms( ABSPATH ) & 0777 | 0755 );
			}

			if ( defined( 'FS_CHMOD_FILE' ) ) {
				$this->chmod_file = FS_CHMOD_FILE;
			} else {
				$this->chmod_file = ( fileperms( ABSPATH . 'index.php' ) & 0777 | 0644 );
			}

			if ( ! $this->is_dir( Redux_Core::$upload_dir ) ) {
				$this->mkdir( Redux_Core::$upload_dir );
			}

			$hash_path = trailingslashit( Redux_Core::$upload_dir ) . 'hash';
			if ( ! $this->file_exists( $hash_path ) ) {
				$this->put_contents( $hash_path, Redux_Helpers::get_hash() );
			}

			$version_path = trailingslashit( Redux_Core::$upload_dir ) . 'version';
			if ( ! $this->file_exists( $version_path ) ) {
				$this->put_contents( $version_path, Redux_Core::$version );
			} else {
				$version_compare = $this->get_contents( $version_path );
				if ( (string) Redux_Core::$version !== $version_compare ) {
					$this->put_contents( $version_path, Redux_Core::$version );
				}
			}
		}

		/**
		 * Do request filesystem action.
		 *
		 * @param string $action Requested action.
		 * @param string $file File to perform action upon.
		 * @param array  $params Action arguments.
		 *
		 * @return bool|void
		 */
		public function do_action( string $action, string $file = '', array $params = array() ) {
			$destination = '';
			$overwrite   = '';
			$content     = '';

			if ( ! empty( $params ) ) {

				// phpcs:ignore WordPress.PHP.DontExtract
				extract( $params );
			}

			global $wp_filesystem;

			if ( defined( 'FS_CHMOD_FILE' ) ) {
				$chmod = FS_CHMOD_FILE;
			} else {
				$chmod = 0644;
			}

			if ( isset( $params['chmod'] ) && ! empty( $params['chmod'] ) ) {
				$chmod = $params['chmod'];
			}
			$res = false;
			if ( ! isset( $recursive ) ) {
				$recursive = false;
			}

			// Do unique stuff.
			if ( 'mkdir' === $action ) {
				$chmod = null;
				if ( isset( $params['chmod'] ) && ! empty( $params['chmod'] ) ) {
					$chmod = $params['chmod'];
				}
				$res = $this->mkdir( $file, $chmod );
			} elseif ( 'rmdir' === $action ) {
				$res = $this->rmdir( $file, $recursive );
			} elseif ( 'copy' === $action && ! isset( $this->wp_filesystem->killswitch ) ) {
				$res = $this->copy( $file, $destination, $overwrite, $chmod );
			} elseif ( 'move' === $action && ! isset( $this->wp_filesystem->killswitch ) ) {
				$res = $this->move( $file, $destination, $overwrite );
			} elseif ( 'delete' === $action ) {
				if ( $this->is_dir( $file ) ) {
					$res = $this->rmdir( $file, $recursive );
				} else {
					$res = $this->unlink( $file );
				}
			} elseif ( 'rmdir' === $action ) {
				$res = $this->rmdir( $file, $recursive );
			} elseif ( 'dirlist' === $action ) {
				if ( ! isset( $include_hidden ) ) {
					$include_hidden = true;
				}
				$res = $this->scandir( $file, $include_hidden, $recursive );
			} elseif ( 'put_contents' === $action && ! isset( $this->wp_filesystem->killswitch ) ) {
				// Write a string to a file.
				if ( isset( $this->parent->ftp_form ) && ! empty( $this->parent->ftp_form ) ) {
					self::load_direct();
					$res = self::$direct->put_contents( $file, $content, $chmod );
				} else {
					$res = $this->put_contents( $file, $content, $chmod );
				}
			} elseif ( 'chown' === $action ) {
				// Changes file owner.
				if ( isset( $owner ) && ! empty( $owner ) ) {
					$res = $wp_filesystem->chmod( $file, $chmod, $recursive );
				}
			} elseif ( 'owner' === $action ) {
				// Gets file owner.
				$res = $this->wp_filesystem->owner( $file );
			} elseif ( 'chmod' === $action ) {
				if ( ! isset( $params['chmod'] ) || ( isset( $params['chmod'] ) && empty( $params['chmod'] ) ) ) {
					$chmod = false;
				}

				$res = $this->chmod( $file, $chmod, $recursive );
			} elseif ( 'get_contents' === $action ) {
				// Reads entire file into a string.
				if ( isset( $this->parent->ftp_form ) && ! empty( $this->parent->ftp_form ) ) {
					self::load_direct();
					$res = self::$direct->get_contents( $file );
				} else {
					$res = $this->get_contents( $file );
				}
			} elseif ( 'get_contents_array' === $action ) {
				// Reads entire file into an array.
				$res = $this->wp_filesystem->get_contents_array( $file );
			} elseif ( 'object' === $action ) {
				$res = $this->wp_filesystem;
			} elseif ( 'unzip' === $action ) {
				$unzipfile = unzip_file( $file, $destination );
				if ( $unzipfile ) {
					$res = true;
				}
			}

			if ( ! $res ) {
				if ( 'dirlist' === $action ) {
					if ( empty( $res ) ) {
						return;
					}

					if ( ! is_array( $res ) ) {
						if ( count( glob( "$file*" ) ) === 0 ) {
							return;
						}
					}
				}

				$this->killswitch = true;

				// translators: %1$s: Upload URL.  %2$s: Codex URL.
				$msg = '<strong>' . esc_html__( 'File Permission Issues', 'redux-framework' ) . '</strong><br/>' . sprintf( esc_html__( 'We were unable to modify required files. Please ensure that %1$s has the proper read-write permissions, or modify your wp-config.php file to contain your FTP login credentials as %2$s.', 'redux-framework' ), '<code>' . esc_url( Redux_Functions_Ex::wp_normalize_path( trailingslashit( WP_CONTENT_DIR ) ) ) . '/uploads/</code>', '<a href="https://codex.wordpress.org/Editing_wp-config.php#WordPress_Upgrade_Constants" target="_blank">' . esc_html__( 'outlined here', 'redux-framework' ) . '</a>' );

				$data = array(
					'parent'  => self::$instance->parent,
					'type'    => 'error',
					'msg'     => $msg,
					'id'      => 'redux-wp-login',
					'dismiss' => false,
				);

				Redux_Admin_Notices::set_notice( $data );
			}

			return $res;
		}


		/**
		 * Getter for the instantiated WP_Filesystem. This should be used carefully since $wp_filesystem won't always have a value.
		 *
		 * @return WP_Filesystem|false
		 */
		public function get_wp_filesystem() {
			if ( $this->use_filesystem ) {
				return $this->wp_filesystem;
			} else {
				return false;
			}
		}

		/**
		 * Check if WP_Filesystem being used.
		 *
		 * @return bool
		 */
		public function using_wp_filesystem(): bool {
			return $this->use_filesystem;
		}

		/**
		 * Attempts to use the correct path for the FS method being used.
		 *
		 * @param string $abs_path Absolute path.
		 *
		 * @return string
		 */
		public function get_sanitized_path( string $abs_path ): string {
			if ( $this->using_wp_filesystem() ) {
				return str_replace( ABSPATH, $this->wp_filesystem->abspath(), $abs_path );
			}

			return $abs_path;
		}

		/**
		 * Create file if not exists then set mtime and atime on file
		 *
		 * @param string $abs_path Absolute path.
		 * @param int    $time Time.
		 * @param int    $atime Altered time.
		 *
		 * @return bool
		 */
		public function touch( string $abs_path, int $time = 0, int $atime = 0 ): bool {
			if ( 0 === $time ) {
				$time = time();
			}

			if ( 0 === $atime ) {
				$atime = time();
			}

			// phpcs:ignore WordPress.PHP.NoSilencedErrors
			$return = @touch( $abs_path, $time, $atime );

			if ( ! $return && $this->use_filesystem ) {
				$abs_path = $this->get_sanitized_path( $abs_path );
				$return   = $this->wp_filesystem->touch( $abs_path, $time, $atime );
			}

			return $return;
		}

		/**
		 * Calls file_put_contents with chmod.
		 *
		 * @param string      $abs_path Absolute path.
		 * @param string      $contents Content to write to the file.
		 * @param string|null $perms    Default permissions value.
		 *
		 * @return bool
		 */
		public function put_contents( string $abs_path, string $contents, string $perms = null ): bool {

			if ( ! $this->is_dir( dirname( $abs_path ) ) ) {
				$this->mkdir( dirname( $abs_path ) );
			}

			// phpcs:ignore WordPress.PHP.NoSilencedErrors
			// @codingStandardsIgnoreStart
			$return = @file_put_contents( $abs_path, $contents );
			// @codingStandardsIgnoreEnd
			$this->chmod( $abs_path );

			if ( null === $perms ) {
				$perms = $this->chmod_file;
			}

			if ( ! $return && $this->use_filesystem ) {
				$abs_path = $this->get_sanitized_path( $abs_path );
				$return   = $this->wp_filesystem->put_contents( $abs_path, $contents, $perms );
			}

			return (bool) $return;
		}

		/**
		 * Calls file_put_contents with chmod.
		 *
		 * @param string $path Get full cache path.
		 *
		 * @return string
		 */
		public function get_cache_path( string $path ): string {
			return $this->folder . $path;
		}

		/**
		 * Calls file_put_contents with chmod in cache directory.
		 *
		 * @param string $abs_path Absolute path.
		 * @param string $contents Contents to put in the cache.
		 *
		 * @return bool
		 */
		public function put_contents_cache( string $abs_path, string $contents ): bool {
			return $this->put_contents( $this->get_cache_path( $abs_path ), $contents );
		}

		/**
		 * Does the specified file or dir exist.
		 *
		 * @param string $abs_path Absolute path.
		 * @return bool
		 */
		public function file_exists( string $abs_path ): bool {
			$return = file_exists( $abs_path );

			if ( ! $return && $this->use_filesystem ) {
				$abs_path = $this->get_sanitized_path( $abs_path );
				$return   = $this->wp_filesystem->exists( $abs_path );
			}

			return (bool) $return;
		}

		/**
		 * Get a file's size.
		 *
		 * @param string $abs_path Absolute path.
		 *
		 * @return int
		 */
		public function filesize( string $abs_path ): int {
			$return = filesize( $abs_path );

			if ( ! $return && $this->use_filesystem ) {
				$abs_path = $this->get_sanitized_path( $abs_path );
				$return   = $this->wp_filesystem->size( $abs_path );
			}

			return $return;
		}

		/**
		 * Get the contents of a file as a string.
		 *
		 * @param string $abs_path Absolute path.
		 *
		 * @return string
		 */
		public function get_local_file_contents( string $abs_path ): string {

			try {
				ob_start();
				if ( $this->file_exists( $abs_path ) && is_file( $abs_path ) ) {
					require_once $abs_path;
				}
				$contents = ob_get_clean();
			} catch ( Exception $e ) {
				// This means that ob_start has been disabled on the system. Lets fallback to good old file_get_contents.
				$contents = file_get_contents( $abs_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			}

			return $contents;
		}

		/**
		 * Get the contents of a file as a string.
		 *
		 * @param string $abs_path Absolute path.
		 *
		 * @return string
		 */
		public function get_contents( string $abs_path ): string {
			$abs_path = $this->get_sanitized_path( $abs_path );
			$return   = '';
			if ( $this->use_filesystem ) {
				$return = $this->wp_filesystem->get_contents( $abs_path );
			}
			if ( empty( $return ) ) {
				$return = $this->get_local_file_contents( $abs_path );
			}

			return $return;
		}

		/**
		 * Delete a file.
		 *
		 * @param string $abs_path Absolute path.
		 *
		 * @return bool
		 */
		public function unlink( string $abs_path ): bool {
			// phpcs:ignore WordPress.PHP.NoSilencedErrors
			$return = @unlink( $abs_path );

			if ( ! $return && $this->use_filesystem ) {
				$abs_path = $this->get_sanitized_path( $abs_path );
				$return   = $this->wp_filesystem->delete( $abs_path, false, false );
			}

			return $return;
		}

		/**
		 * Chmod a file.
		 *
		 * @param string   $abs_path Absolute path.
		 * @param int|null $perms    Permission value, if not provided, defaults to WP standards.
		 *
		 * @return bool
		 */
		public function chmod( string $abs_path, int $perms = null ): bool {
			if ( ! $this->file_exists( $abs_path ) ) {
				return false;
			}
			if ( is_null( $perms ) ) {
				$perms = $this->is_file( $abs_path ) ? $this->chmod_file : $this->chmod_dir;
			}
			// phpcs:ignore WordPress.PHP.NoSilencedErrors
			$return = @chmod( $abs_path, $perms );

			if ( ! $return && $this->use_filesystem ) {
				$abs_path = $this->get_sanitized_path( $abs_path );
				$return   = $this->wp_filesystem->chmod( $abs_path, $perms, false );
			}

			return (bool) $return;
		}

		/**
		 * Check if this path is a directory.
		 *
		 * @param string $abs_path Absolute path.
		 *
		 * @return bool
		 */
		public function is_dir( string $abs_path ): bool {
			$return = is_dir( $abs_path );

			if ( ! $return && $this->use_filesystem ) {
				$abs_path = $this->get_sanitized_path( $abs_path );
				$return   = $this->wp_filesystem->is_dir( $abs_path );
			}

			return $return;
		}

		/**
		 * Check if the specified path is a file.
		 *
		 * @param string $abs_path Absolute path.
		 *
		 * @return bool
		 */
		public function is_file( string $abs_path ): bool {
			$return = is_file( $abs_path );

			if ( ! $return && $this->use_filesystem ) {
				$abs_path = $this->get_sanitized_path( $abs_path );
				$return   = $this->wp_filesystem->is_file( $abs_path );
			}

			return $return;
		}

		/**
		 * Is the specified path readable.
		 *
		 * @param string $abs_path Absolute path.
		 *
		 * @return bool
		 */
		public function is_readable( string $abs_path ): bool {
			$return = is_readable( $abs_path );

			if ( ! $return && $this->use_filesystem ) {
				$abs_path = $this->get_sanitized_path( $abs_path );
				$return   = $this->wp_filesystem->is_readable( $abs_path );
			}

			return $return;
		}

		/**
		 * Is the specified path writable.
		 *
		 * @param string $abs_path Absolute path.
		 *
		 * @return bool
		 */
		public function is_writable( string $abs_path ): bool {
			$return = is_writable( $abs_path );

			if ( ! $return && $this->use_filesystem ) {
				$abs_path = $this->get_sanitized_path( $abs_path );
				$return   = $this->wp_filesystem->is_writable( $abs_path );
			}

			return $return;
		}

		/**
		 * Create an index file at the given path.
		 *
		 * @param string $path Directory to add the index to.
		 */
		private function create_index( string $path ) {
			$index_path = trailingslashit( $path ) . 'index.php';
			if ( ! $this->file_exists( $index_path ) ) {
				$this->put_contents( $index_path, "<?php\n//Silence is golden" );
			}
		}

		/**
		 * Recursive mkdir.
		 *
		 * @param string   $abs_path Absolute path.
		 * @param int|null $perms    Permissions, if default not required.
		 *
		 * @return bool
		 */
		public function mkdir( string $abs_path, int $perms = null ): bool {
			if ( is_null( $perms ) ) {
				$perms = $this->chmod_dir;
			}

			if ( $this->is_dir( $abs_path ) ) {
				$this->chmod( $abs_path, $perms );
				$this->create_index( $abs_path );

				return true;
			}

			try {
				$mkdirp = wp_mkdir_p( $abs_path );
			} catch ( Exception $e ) {
				$mkdirp = false;
			}

			if ( $mkdirp ) {
				$this->chmod( $abs_path, $perms );
				$this->create_index( $abs_path );

				return true;
			}
			// phpcs:ignore WordPress.PHP.NoSilencedErrors
			$return = @mkdir( $abs_path, $perms, true );

			if ( ! $return && $this->use_filesystem ) {
				$abs_path = $this->get_sanitized_path( $abs_path );

				if ( $this->is_dir( $abs_path ) ) {
					$this->create_index( $abs_path );

					return true;
				}

				// WP_Filesystem doesn't offer a recursive mkdir().
				$abs_path = str_replace( '//', '/', $abs_path );
				$abs_path = rtrim( $abs_path, '/' );
				if ( empty( $abs_path ) ) {
					$abs_path = '/';
				}

				$dirs        = explode( '/', ltrim( $abs_path, '/' ) );
				$current_dir = '';

				foreach ( $dirs as $dir ) {
					$current_dir .= '/' . $dir;
					if ( ! $this->is_dir( $current_dir ) ) {
						$this->wp_filesystem->mkdir( $current_dir, $perms );
					}
				}

				$return = $this->is_dir( $abs_path );
			}

			return $return;
		}

		/**
		 * Delete a directory.
		 *
		 * @param string $abs_path  Absolute path.
		 * @param bool   $recursive Set to recursive create.
		 *
		 * @return bool
		 */
		public function rmdir( string $abs_path, bool $recursive = false ): bool {
			if ( ! $this->is_dir( $abs_path ) ) {
				return false;
			}

			// Taken from WP_Filesystem_Direct.
			if ( ! $recursive ) {
				// phpcs:ignore WordPress.PHP.NoSilencedErrors
				$return = @rmdir( $abs_path );
			} else {

				// At this point it's a folder, and we're in recursive mode.
				$abs_path = trailingslashit( $abs_path );
				$filelist = $this->scandir( $abs_path );

				$return = true;
				if ( is_array( $filelist ) ) {
					foreach ( $filelist as $filename => $fileinfo ) {

						if ( 'd' === $fileinfo['type'] ) {
							$return = $this->rmdir( $abs_path . $filename, $recursive );
						} else {
							$return = $this->unlink( $abs_path . $filename );
						}
					}
				}
				// phpcs:ignore WordPress.PHP.NoSilencedErrors
				if ( file_exists( $abs_path ) && ! @rmdir( $abs_path ) ) {
					$return = false;
				}
			}

			if ( ! $return && $this->use_filesystem ) {
				$abs_path = $this->get_sanitized_path( $abs_path );

				return $this->wp_filesystem->rmdir( $abs_path, $recursive );
			}

			return $return;

		}

		/**
		 * Get a list of files/folders under specified directory.
		 *
		 * @param string $abs_path       Absolute path.
		 * @param bool   $include_hidden Include hidden files, defaults to true.
		 * @param bool   $recursive      Recursive search, defaults to false.
		 *
		 * @return array|bool
		 */
		public function scandir( string $abs_path, bool $include_hidden = true, bool $recursive = false ) {
			// phpcs:ignore WordPress.PHP.NoSilencedErrors
			$dirlist = @scandir( $abs_path );
			if ( false === $dirlist ) {
				if ( $this->use_filesystem ) {
					$abs_path = $this->get_sanitized_path( $abs_path );

					return $this->wp_filesystem->dirlist( $abs_path, $include_hidden, $recursive );
				}

				return false;
			}

			$return = array();

			// Normalize return to look somewhat like the return value for WP_Filesystem::dirlist.
			foreach ( $dirlist as $entry ) {
				if ( '.' === $entry || '..' === $entry ) {
					continue;
				}
				$return[ $entry ] = array(
					'name' => $entry,
					'type' => $this->is_dir( $abs_path . '/' . $entry ) ? 'd' : 'f',
				);
			}

			return $return;

		}

		/**
		 * Light wrapper for move_uploaded_file with chmod.
		 *
		 * @param string   $file        Source file.
		 * @param string   $destination File destination.
		 * @param int|null $perms       Permission value.
		 *
		 * @return bool
		 */
		public function move_uploaded_file( string $file, string $destination, int $perms = null ): bool {
			// TODO: look into replicating more functionality from wp_handle_upload().
			// phpcs:ignore WordPress.PHP.NoSilencedErrors
			$return = @move_uploaded_file( $file, $destination );

			if ( $return ) {
				$this->chmod( $destination, $perms );
			}

			return $return;
		}

		/**
		 * Copy a file.
		 *
		 * @param string $source_abs_path Source path.
		 * @param string $destination_abs_path Destination path.
		 * @param bool   $overwrite Overwrite file.
		 * @param mixed  $perms Permission value.
		 * @return bool
		 * Taken from WP_Filesystem_Direct
		 */
		public function copy( string $source_abs_path, string $destination_abs_path, bool $overwrite = true, $perms = false ): bool {

			// Error if source file doesn't exist.
			if ( ! $this->file_exists( $source_abs_path ) ) {
				return false;
			}

			if ( ! $overwrite && $this->file_exists( $destination_abs_path ) ) {
				return false;
			}
			if ( ! $this->is_dir( dirname( $destination_abs_path ) ) ) {
				$this->mkdir( dirname( $destination_abs_path ) );
			}

			// phpcs:ignore WordPress.PHP.NoSilencedErrors
			$return = @copy( $source_abs_path, $destination_abs_path );
			if ( $perms && $return ) {
				$this->chmod( $destination_abs_path, $perms );
			}

			if ( ! $return && $this->use_filesystem ) {
				$source_abs_path      = $this->get_sanitized_path( $source_abs_path );
				$destination_abs_path = $this->get_sanitized_path( $destination_abs_path );
				$return               = $this->wp_filesystem->copy(
					$source_abs_path,
					$destination_abs_path,
					$overwrite,
					$perms
				);
			}

			return $return;
		}

		/**
		 * Move a file.
		 *
		 * @param string $source_abs_path Source absolute path.
		 * @param string $destination_abs_path Destination absolute path.
		 * @param bool   $overwrite Overwrite if file exists.
		 * @return bool
		 */
		public function move( string $source_abs_path, string $destination_abs_path, bool $overwrite = true ): bool {

			// Error if source file doesn't exist.
			if ( ! $this->file_exists( $source_abs_path ) ) {
				return false;
			}

			// Try using rename first. if that fails (for example, source is read only) try copy.
			// Taken in part from WP_Filesystem_Direct.
			if ( ! $overwrite && $this->file_exists( $destination_abs_path ) ) {
				return false;
			} elseif ( @rename( $source_abs_path, $destination_abs_path ) ) { // phpcs:ignore WordPress.PHP.NoSilencedErrors
				return true;
			} else {
				if ( $this->copy( $source_abs_path, $destination_abs_path, $overwrite ) && $this->file_exists(
					$destination_abs_path
				) ) {
					$this->unlink( $source_abs_path );

					return true;
				} else {
					$return = false;
				}
			}

			if ( $this->use_filesystem ) {
				$source_abs_path      = $this->get_sanitized_path( $source_abs_path );
				$destination_abs_path = $this->get_sanitized_path( $destination_abs_path );

				$return = $this->wp_filesystem->move( $source_abs_path, $destination_abs_path, $overwrite );
			}

			return $return;
		}

		/**
		 * Shim: get_template.
		 *
		 * @param string $file Template name.
		 *
		 * @return void Path to template file.
		 */
		public function get_template( string $file ) {
			$panel = new Redux_Panel( $this );
			$panel->get_template( $file );
		}
	}

	Redux_Filesystem::get_instance();
}
