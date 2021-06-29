<?php // phpcs:ignore WordPress.Files.FileName

/**
 * Installer class which installs and/or activates block plugins.
 *
 * @since 4.0.0
 * @package Redux Framework
 */

namespace ReduxTemplates;

use ReduxTemplates;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/misc.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';
require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

/**
 * ReduxTemplates Installer.
 *
 * @since 4.0.0
 */
class Installer {

	/**
	 * Run command.
	 *
	 * @param string $slug Plugin Slug.
	 * @param string $download_link Install URL if from a custom URL.
	 *
	 * @return array
	 * @since 4.0.0
	 */
	public static function run( $slug, $download_link = '' ) {
		$plugin_dir = WP_PLUGIN_DIR . '/' . $slug;

		/*
		 * Don't try installing plugins that already exist (wastes time downloading files that
		 * won't be used
		 */

		$status = array();
		if ( ! is_dir( $plugin_dir ) ) {
			if ( empty( $download_link ) ) {
				$api = plugins_api(
					'plugin_information',
					array(
						'slug'   => $slug,
						'fields' => array(
							'short_description' => false,
							'sections'          => false,
							'requires'          => false,
							'rating'            => false,
							'ratings'           => false,
							'downloaded'        => false,
							'last_updated'      => false,
							'added'             => false,
							'tags'              => false,
							'compatibility'     => false,
							'homepage'          => false,
							'donate_link'       => false,
						),
					)
				);

				$download_link = $api->download_link;
			}

			if ( empty( $download_link ) ) {
				$status['error'] = 'Install url for ' . $slug . ' could not be located.';
				return $status;
			}

			ob_start();

			$skin     = new ReduxTemplates\Installer_Muter( array( 'api' => $api ) );
			$upgrader = new \Plugin_Upgrader( $skin );
			$install  = $upgrader->install( $download_link );

			ob_end_clean();

			if ( true !== $install ) {
				$status['error'] = 'Install process failed for ' . $slug . '.';

				if ( ! empty( $install ) ) {
					ob_start();
					// phpcs:ignore WordPress.PHP.DevelopmentFunctions
					\var_dump( $install );
					$result = ob_get_clean();

					$status['var_dump'] = $result;
				} else {
					$status['error'] .= ' ' . $upgrader->skin->options['api']->errors['plugins_api_failed'][0];
				}

				return $status;
			}

			// Stop UAGB redirect.
			if ( 'ultimate-addons-for-gutenberg' === $slug ) {
				update_option( '__uagb_do_redirect', false );
			}

			$status['install'] = 'success';
		}

		/*
		 * The install results don't indicate what the main plugin file is, so we just try to
		 * activate based on the slug. It may fail, in which case the plugin will have to be activated
		 * manually from the admin screen.
		 */
		$plugin_path  = false;
		$plugin_check = false;
		if ( file_exists( $plugin_dir . '/' . $slug . '.php' ) ) {
			$plugin_path  = $plugin_dir . '/' . $slug . '.php';
			$plugin_check = $slug . '/' . $slug . '.php';
		} elseif ( file_exists( $plugin_dir . '/plugin.php' ) ) {
			$plugin_path  = $plugin_dir . '/plugin.php';
			$plugin_check = $slug . '/plugin.php';
		} else {
			$split        = explode( '-', $slug );
			$new_filename = '';
			foreach ( $split as $s ) {
				if ( ! empty( $s ) ) {
					$new_filename .= $s[0];
				}
			}
			$plugin_path  = $plugin_dir . '/' . $new_filename . '.php';
			$plugin_check = $slug . '/' . $new_filename . '.php';

			if ( ! file_exists( $plugin_path ) ) {
				$plugin_path  = $plugin_dir . '/index.php';
				$plugin_check = $slug . '/index.php';
			}
		}

		if ( ! empty( $plugin_path ) && file_exists( $plugin_path ) ) {
			activate_plugin( $plugin_check );
			$status['activate'] = 'success';
		} else {
			$status['error'] = sprintf(
				'The block plugin `%s` could not be activated. Please try installing it manually.',
				$slug
			);
		}

		return $status;

	}
}
