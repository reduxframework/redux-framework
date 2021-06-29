<?php // phpcs:ignore WordPress.Files.FileName

/**
 * Detect supported plugins with the given instance.
 *
 * @since   4.0.0
 * @package Redux Framework
 */

namespace ReduxTemplates;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Redux Templates Supported_Plugins Class
 *
 * @since 4.0.0
 */
class Supported_Plugins {

	/**
	 * List of all supported plugins from the library.
	 *
	 * @var array|null
	 */
	protected static $plugins = array();
	/**
	 * List of all supported plugins from the library.
	 *
	 * @var Supported_Plugins|null
	 */
	protected static $instance = null;

	/**
	 * Return or generate instance, singleton.
	 *
	 * @return object Instance
	 * @since 4.0.0
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Include the template
	 *
	 * @param array|null $plugins List of all possible plugins from the library.
	 *
	 * @return void
	 * @since 4.0.0
	 */
	public function init( $plugins = array() ) {
		self::$plugins = $plugins;
		self::detect_versions();
	}

	/**
	 * Detect all versions of plugins had in library and installed locally.
	 *
	 * @return void
	 * @since 4.0.0
	 */
	private static function detect_versions() {
		$all_plugins = get_plugins();

		$active_plugins = get_option( 'active_plugins' );

		$data = array();
		foreach ( $active_plugins as $plugin ) {
			$slug = explode( '/', $plugin )[0];
			if ( ! isset( $all_plugins[ $plugin ] ) ) {
				$all_plugins[ $plugin ] = array();
			}
			$data[ $slug ] = $all_plugins[ $plugin ];
		}

		foreach ( self::$plugins as $key => $plugin ) {
			$selector = false;
			if ( isset( $data[ $key ] ) ) {
				$selector = $key;
			} else {
				if ( isset( $plugin['slug'] ) && isset( $data[ $plugin['slug'] ] ) ) {
					$selector = $plugin['slug'];
				}
			}
			if ( isset( $plugin['detect'] ) ) {
				if ( isset( $plugin['detect']['freemius'] ) && $plugin['detect']['freemius'] ) {
					// Freemius Version Detection.
					if ( isset( $GLOBALS[ $plugin['detect']['freemius'] ] ) && ! empty( $GLOBALS[ $plugin['detect']['freemius'] ] ) ) {
						$freemius  = $GLOBALS[ $plugin['detect']['freemius'] ];
						$selector  = $freemius->get_plugin_basename();
						$true_slug = explode( '/', $selector )[0];
						if ( $true_slug !== $key ) {
							self::$plugins[ $key ]['true_slug'] = $true_slug;
						}
						if ( isset( self::$plugins[ $key ]['free_slug'] ) ) {
							continue;  // Let's only store the info on the free version.
						}
						$plugin_info = $freemius->get_plugin_data();
						if ( $selector && ! isset( $data[ $selector ]['Version'] ) ) {
							self::$plugins[ $key ]['version'] = $plugin_info['Version'];
						}
						if ( $freemius->can_use_premium_code() ) {
							self::$plugins[ $key ]['is_pro'] = true;
						}
						unset( self::$plugins[ $key ]['detect']['freemius'] );
					}
				}
				if ( isset( $plugin['detect']['defined'] ) ) {
					if ( ! empty( $plugin['detect']['defined'] ) ) {
						foreach ( $plugin['detect']['defined'] as $key_name => $defined_name ) {
							if ( defined( $defined_name ) && ! empty( constant( $defined_name ) ) ) {
								self::$plugins[ $key ][ $key_name ] = constant( $defined_name );
							}
						}
					}
					unset( self::$plugins[ $key ]['detect']['defined'] );
				}
				if ( empty( self::$plugins[ $key ]['detect'] ) ) {
					unset( self::$plugins[ $key ]['detect'] );
				}
			} else {
				if ( isset( $data[ $key ] ) ) {
					if ( isset( $data[ $key ]['Version'] ) ) {
						self::$plugins[ $key ]['version'] = $data[ $key ]['Version'];
					}
				}
			}
		}
		foreach ( self::$plugins as $key => $plugin ) {
			if ( ! isset( $plugin['url'] ) || ( isset( $plugin['url'] ) && empty( $plugin['url'] ) ) ) {
				if ( isset( $plugin['free_slug'] ) ) {
					if ( isset( $plugin['free_slug'] ) ) {
						$free_plugin = self::$plugins[ $plugin['free_slug'] ];
						if ( isset( $free_plugin['url'] ) ) {
							self::$plugins[ $key ]['url'] = $free_plugin['url'];
						} else {
							self::$plugins[ $key ]['url'] = "https://wordpress.org/plugins/{$plugin['free_slug']}/";
						}
					}
				} else {
					self::$plugins[ $key ]['url'] = "https://wordpress.org/plugins/{$key}/";
				}
			}
		}
		self::$plugins['redux-framework']['plugin'] = defined( 'REDUX_PLUGIN_FILE' );
		if ( isset( self::$plugins['redux-pro'] ) ) {
			self::$plugins['redux-pro']['redux_pro'] = true;
		}
	}

	/**
	 * Helper function to return all installed plugins.
	 *
	 * @return array Array of plugins and which are installed.
	 * @since 4.0.0
	 */
	public static function get_plugins() {
		$instance = self::instance();
		if ( empty( $instance::$plugins ) ) {
			$instance->init();
		}

		return $instance::$plugins;
	}

}
