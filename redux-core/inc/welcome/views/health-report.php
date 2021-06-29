<?php
/**
 * Admin View: Page - Status Report
 *
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

global $wpdb;

/**
 * Santize variables.
 *
 * @param string $var Variable to sanitize.
 *
 * @return string
 */
function redux_clean( $var ) {
	return sanitize_text_field( $var );
}

/**
 * Output help tooltips for status fields.
 *
 * @param string $tip Tooltip text.
 *
 * @return string
 */
function redux_help_tip( $tip ) {
	return '<span class="redux-hint-qtip" qtip-content="' . $tip . '"></span>';
}

$sysinfo = Redux_Helpers::compile_system_status( false, true );

?>
<div class="wrap about-wrap redux-status" style="max-width:initial;margin:10px 20px 0 2px;">
	<h1>
		<?php esc_html_e( 'Redux - Health Report', 'redux-framework' ); ?>
	</h1>

	<div class="about-text">
		<?php esc_html_e( 'Our core mantra at Redux is backward compatibility. With millions instances worldwide, all we care about is keeping can be assured that we will take care of you.', 'redux-framework' ); ?></div>
	<div class="redux-badge">
		<i class="el el-redux"></i>
		<span>
			<?php printf( esc_html__( 'Version', 'redux-framework' ) . ' %s', esc_html( Redux_Core::$version ) ); ?>
		</span>
	</div>

	<?php $this->actions(); ?>
	<?php $this->tabs(); ?>

	<div class="updated redux-message hidden">
		<p>
			<?php esc_html_e( 'Please copy and paste this information in your ticket when contacting support:', 'redux-framework' ); ?>
		</p>

		<p class="submit">
			<a href="#" class="button-primary debug-report">
				<?php esc_html_e( 'Get System Report', 'redux-framework' ); ?>
			</a>
			<a
					class="skip button-primary"
					href="https://devs.redux.io/core/support/understanding-the-redux-framework-system-status-report/"
					target="_blank">
				<?php esc_html_e( 'Understanding the Status Report', 'redux-framework' ); ?>
			</a>
		</p>
		<div id="debug-report">
			<textarea readonly="readonly"></textarea>
			<p class="submit">
				<button
						id="copy-for-support"
						class="button-primary redux-hint-qtip"
						href="#"
						qtip-content="<?php esc_html_e( 'Copied!', 'redux-framework' ); ?>">
					<?php esc_html_e( 'Copy for Support', 'redux-framework' ); ?>
				</button>
			</p>
		</div>
	</div>
	<br/>
	<table class="redux_status_table widefat" cellspacing="0" id="status">
		<thead>
		<tr>
			<th colspan="3" data-export-label="WordPress Environment">
				<?php esc_html_e( 'WordPress Environment', 'redux-framework' ); ?>
			</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td data-export-label="Home URL">
				<?php esc_html_e( 'Home URL', 'redux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'The URL of your site\'s homepage.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td><?php echo esc_url( $sysinfo['home_url'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Site URL">
				<?php esc_html_e( 'Site URL', 'redux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'The root URL of your site.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td>
				<?php echo esc_url( $sysinfo['site_url'] ); ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Redux Version">
				<?php esc_html_e( 'Redux Version', 'redux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'The version of Redux Framework installed on your site.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td>
				<?php echo esc_html( $sysinfo['redux_ver'] ); ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Redux Data Directory Writable">
				<?php esc_html_e( 'Redux Data Directory Writable', 'redux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'Redux and its extensions write data to the <code>uploads</code> directory. This directory must be writable.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td>
			<?php
			if ( true === $sysinfo['redux_data_writeable'] ) {
				echo '<mark class="yes">';
				echo '<span class="dashicons dashicons-yes"></span>';
				echo '<code>' . esc_html( $sysinfo['redux_data_dir'] ) . '</code>';
				echo '</mark>';
			} else {
				echo '<mark class="error">';
				echo '<span class="dashicons dashicons-warning"></span>';
				printf( esc_html__( 'To allow data saving, make', 'redux-framework' ) . ' <code>%s</code> ' . esc_html__( 'writable.', 'redux-framework' ), esc_html( $sysinfo['redux_data_dir'] ) );
				echo '</mark>';
			}
			?>
			</td>
		</tr>
		<tr>
			<td data-export-label="WP Content URL">
				<?php esc_html_e( 'WP Content URL', 'redux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'Redux and its extensions write data to the <code>uploads</code> directory. This directory must be writable.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td>
				<?php echo '<code>' . esc_url( $sysinfo['wp_content_url'] ) . '</code> '; ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="WP Version">
				<?php esc_html_e( 'WP Version', 'redux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'The version of WordPress installed on your site.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td>
				<?php bloginfo( 'version' ); ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="WP Multisite">
				<?php esc_html_e( 'WP Multisite', 'redux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'Whether or not you have WordPress Multisite enabled.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td>
			<?php
			if ( true === $sysinfo['wp_multisite'] ) {
				echo '<span class="dashicons dashicons-yes"></span>';
			} else {
				echo '&ndash;';
			}
			?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Permalink Structure">
				<?php esc_html_e( 'Permalink Structure', 'redux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'The current permalink structure as defined in WordPress Settings->Permalinks.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td>
				<?php echo esc_html( $sysinfo['permalink_structure'] ); ?>
			</td>
		</tr>
		<?php $sof = $sysinfo['front_page_display']; ?>
		<tr>
			<td data-export-label="Front Page Display">
				<?php esc_html_e( 'Front Page Display', 'redux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'The current Reading mode of WordPress.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td><?php echo esc_html( $sof ); ?></td>
		</tr>
		<?php
		if ( 'page' === $sof ) {
			?>
			<tr>
				<td data-export-label="Front Page">
					<?php esc_html_e( 'Front Page', 'redux-framework' ); ?>:
				</td>
				<td class="help">
					<?php echo redux_help_tip( esc_attr__( 'The currently selected page which acts as the site\'s Front Page.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</td>
				<td>
					<?php echo esc_html( $sysinfo['front_page'] ); ?>
				</td>
			</tr>
			<tr>
				<td data-export-label="Posts Page">
					<?php esc_html_e( 'Posts Page', 'redux-framework' ); ?>:
				</td>
				<td class="help">
					<?php echo redux_help_tip( esc_attr__( 'The currently selected page in where blog posts are displayed.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</td>
				<td>
					<?php echo esc_html( $sysinfo['posts_page'] ); ?>
				</td>
			</tr>
			<?php
		}
		?>
		<tr>
			<td data-export-label="WP Memory Limit">
				<?php esc_html_e( 'WP Memory Limit', 'redux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'The maximum amount of memory (RAM) that your site can use at one time.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td>
				<?php
				$memory = $sysinfo['wp_mem_limit']['raw'];

				if ( $memory < 40000000 ) {
					echo '<mark class="error">' . sprintf( '%s - ' . esc_html__( 'We recommend setting memory to at least 40MB. See:', 'redux-framework' ) . ' <a href="%s" target="_blank">' . esc_html__( 'Increasing memory allocated to PHP', 'redux-framework' ) . '</a>', esc_html( $sysinfo['wp_mem_limit']['size'] ), '//codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP' ) . '</mark>';
				} else {
					echo '<mark class="yes">' . esc_html( $sysinfo['wp_mem_limit']['size'] ) . '</mark>';
				}
				?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Database Table Prefix">
				<?php esc_html_e( 'Database Table Prefix', 'redux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'The prefix structure of the current WordPress database.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td>
				<?php echo esc_html( $sysinfo['db_table_prefix'] ); ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="WP Debug Mode">
				<?php esc_html_e( 'WP Debug Mode', 'redux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'Displays whether or not WordPress is in Debug Mode.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td>
				<?php
				if ( 'true' === $sysinfo['wp_debug'] ) {
					echo '<span class="dashicons dashicons-yes"></span>';
				} else {
					echo '<mark class="no">&ndash;</mark>';
				}
				?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Language">
				<?php esc_html_e( 'Language', 'redux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'The current language used by WordPress. Default = English', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td>
				<?php echo esc_html( $sysinfo['wp_lang'] ); ?>
			</td>
		</tr>
		</tbody>
	</table>
	<table class="redux_status_table widefat" cellspacing="0" id="status">
		<thead>
		<tr>
			<th colspan="3" data-export-label="Browser">
				<?php esc_html_e( 'Browser', 'redux-framework' ); ?>
			</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td data-export-label="Browser Info">
				<?php esc_html_e( 'Browser Info', 'redux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'Information about the current web browser.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td>
				<?php
				foreach ( $sysinfo['browser'] as $key => $value ) {
					echo '<strong>' . esc_html( ucfirst( $key ) ) . '</strong>: ' . esc_html( $value ) . '<br/>';
				}
				?>
			</td>
		</tr>
		</tbody>
	</table>

	<table class="redux_status_table widefat" cellspacing="0" id="status">
		<thead>
		<tr>
			<th colspan="3" data-export-label="Server Environment">
				<?php esc_html_e( 'Server Environment', 'redux-framework' ); ?>
			</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td data-export-label="Server Info">
				<?php esc_html_e( 'Server Info', 'redux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'Information about the web server that is currently hosting your site.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td>
				<?php echo esc_html( $sysinfo['server_info'] ); ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Localhost Environment">
				<?php esc_html_e( 'Localhost Environment', 'redux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'Is the server running in a localhost environment.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td>
				<?php
				if ( 'true' === $sysinfo['localhost'] ) {
					echo '<mark class="yes">';
					echo '<span class="dashicons dashicons-yes"></span>';
					echo '</mark>';
				} else {
					echo '&ndash;>';
				}
				?>
			</td>
		</tr>
		<tr>
			<td data-export-label="PHP Version">
				<?php esc_html_e( 'PHP Version', 'redux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'The version of PHP installed on your hosting server.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td>
				<?php echo esc_html( $sysinfo['php_ver'] ); ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="ABSPATH">
				<?php esc_html_e( 'ABSPATH', 'redux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'The ABSPATH variable on the server.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td>
				<?php echo '<code>' . esc_html( $sysinfo['abspath'] ) . '</code>'; ?>
			</td>
		</tr>

		<?php if ( function_exists( 'ini_get' ) ) { ?>
			<tr>
				<td data-export-label="PHP Memory Limit"><?php esc_html_e( 'PHP Memory Limit', 'redux-framework' ); ?>
					:
				</td>
				<td class="help">
					<?php echo redux_help_tip( esc_attr__( 'The largest filesize that can be contained in one post.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</td>
				<td><?php echo esc_html( $sysinfo['php_mem_limit'] ); ?></td>
			</tr>
			<tr>
				<td data-export-label="PHP Post Max Size"><?php esc_html_e( 'PHP Post Max Size', 'redux-framework' ); ?>
					:
				</td>
				<td class="help">
					<?php echo redux_help_tip( esc_attr__( 'The largest filesize that can be contained in one post.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</td>
				<td><?php echo esc_html( $sysinfo['php_post_max_size'] ); ?></td>
			</tr>
			<tr>
				<td data-export-label="PHP Time Limit"><?php esc_html_e( 'PHP Time Limit', 'redux-framework' ); ?>:</td>
				<td class="help">
					<?php echo redux_help_tip( esc_attr__( 'The amount of time (in seconds) that your site will spend on a single operation before timing out (to avoid server lockups).', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</td>
				<td><?php echo esc_html( $sysinfo['php_time_limit'] ); ?></td>
			</tr>
			<tr>
				<td data-export-label="PHP Max Input Vars"><?php esc_html_e( 'PHP Max Input Vars', 'redux-framework' ); ?>
					:
				</td>
				<td class="help">
					<?php echo redux_help_tip( esc_attr__( 'The maximum number of variables your server can use for a single function to avoid overloads.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</td>
				<td><?php echo esc_html( $sysinfo['php_max_input_var'] ); ?></td>
			</tr>
			<tr>
				<td data-export-label="PHP Display Errors"><?php esc_html_e( 'PHP Display Errors', 'redux-framework' ); ?>
					:
				</td>
				<td class="help">
					<?php echo redux_help_tip( esc_attr__( 'Determines if PHP will display errors within the browser.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</td>
				<td>
				<?php
				if ( true === $sysinfo['php_display_errors'] ) {
					echo '<mark class="yes">';
					echo '<span class="dashicons dashicons-yes"></span>';
					echo '</mark>';
				} else {
					echo '&ndash;';
				}
				?>
				</td>
			</tr>
		<?php } ?>
		<tr>
			<td data-export-label="SUHOSIN Installed"><?php esc_html_e( 'SUHOSIN Installed', 'redux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'Suhosin is an advanced protection system for PHP installations. It was designed to protect your servers on the one hand against a number of well known problems in PHP applications and on the other hand against potential unknown vulnerabilities within these applications or the PHP core itself.  If enabled on your server, Suhosin may need to be configured to increase its data submission limits.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td>
			<?php
			if ( true === $sysinfo['suhosin_installed'] ) {
				echo '<mark class="yes">';
				echo '<span class="dashicons dashicons-yes"></span>';
				echo '</mark>';
			} else {
				echo '<mark class="no">';
				echo '&ndash;';
				echo '</mark>';
			}
			?>
			</td>
		</tr>

		<tr>
			<td data-export-label="MySQL Version"><?php esc_html_e( 'MySQL Version', 'redux-framework' ); ?>:</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'The version of MySQL installed on your hosting server.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td><?php echo esc_html( $sysinfo['mysql_ver'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Max Upload Size"><?php esc_html_e( 'Max Upload Size', 'redux-framework' ); ?>:</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'The largest filesize that can be uploaded to your WordPress installation.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td><?php echo esc_html( $sysinfo['max_upload_size'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Default Timezone is UTC">
				<?php esc_html_e( 'Default Timezone is UTC', 'redux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'The default timezone for your server.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td>
				<?php
				if ( false === $sysinfo['def_tz_is_utc'] ) {
					echo '<mark class="error">';
					echo '<span class="dashicons dashicons-no-alt"></span>';
					// translators: %s default time zone.
					printf( esc_html__( 'Default timezone is', 'redux-framework' ) . ' %s - ' . esc_html__( 'it should be UTC', 'redux-framework' ), esc_html( date_default_timezone_get() ) );
					echo '</mark>';
				} else {
					echo '<mark class="yes">';
					echo '<span class="dashicons dashicons-yes"></span>';
					echo '</mark>';
				}
				?>
			</td>
		</tr>
		<?php
		$posting = array();

		// fsockopen/cURL.
		$posting['fsockopen_curl']['name'] = 'fsockopen/cURL';
		$posting['fsockopen_curl']['help'] = redux_help_tip( esc_attr__( 'Used when communicating with remote services with PHP.', 'redux-framework' ) );

		if ( true === $sysinfo['fsockopen_curl'] ) {
			$posting['fsockopen_curl']['success'] = true;
		} else {
			$posting['fsockopen_curl']['success'] = false;
			$posting['fsockopen_curl']['note']    = esc_html__( 'Your server does not have fsockopen or cURL enabled - cURL is used to communicate with other servers. Please contact your hosting provider.', 'redux-framework' ) . '</mark>';
		}

		// WP Remote Post Check.
		$posting['wp_remote_post']['name'] = esc_html__( 'Remote Post', 'redux-framework' );
		$posting['wp_remote_post']['help'] = redux_help_tip( esc_attr__( 'Used to send data to remote servers.', 'redux-framework' ) );

		if ( true === $sysinfo['wp_remote_post'] ) {
			$posting['wp_remote_post']['success'] = true;
		} else {
			$posting['wp_remote_post']['note'] = esc_html__( 'wp_remote_post() failed. Many advanced features may not function. Contact your hosting provider.', 'redux-framework' );

			if ( $sysinfo['wp_remote_post_error'] ) {
				$posting['wp_remote_post']['note'] .= ' ' . sprintf( esc_html__( 'Error:', 'redux-framework' ) . ' %s', redux_clean( $sysinfo['wp_remote_post_error'] ) );
			}

			$posting['wp_remote_post']['success'] = false;
		}

		// WP Remote Get Check.
		$posting['wp_remote_get']['name'] = esc_html__( 'Remote Get', 'redux-framework' );
		$posting['wp_remote_get']['help'] = redux_help_tip( esc_attr__( 'Used to grab information from remote servers for updates updates.', 'redux-framework' ) );

		if ( true === $sysinfo['wp_remote_get'] ) {
			$posting['wp_remote_get']['success'] = true;
		} else {
			$posting['wp_remote_get']['note'] = esc_html__( 'wp_remote_get() failed. This is needed to get information from remote servers. Contact your hosting provider.', 'redux-framework' );
			if ( $sysinfo['wp_remote_get_error'] ) {
				$posting['wp_remote_get']['note'] .= ' ' . sprintf( esc_html__( 'Error:', 'redux-framework' ) . ' %s', redux_clean( $sysinfo['wp_remote_get_error'] ) );
			}

			$posting['wp_remote_get']['success'] = false;
		}

		$posting = apply_filters( 'redux_debug_posting', $posting );

		foreach ( $posting as $single_post ) {
			$mark = ! empty( $single_post['success'] ) ? 'yes' : 'error';
			?>
			<tr>
				<td data-export-label="<?php echo esc_attr( $single_post['name'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>">
					<?php echo esc_html( $single_post['name'] ); ?>:
				</td>
				<td>
					<?php echo isset( $single_post['help'] ) ? $single_post['help'] : ''; // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</td>
				<td class="help">
					<mark class="<?php echo esc_attr( $mark ); ?>">
						<?php echo ! empty( $single_post['success'] ) ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-alt-no"></span>'; ?>
						<?php echo ! empty( $single_post['note'] ) ? wp_kses_data( $single_post['note'] ) : ''; ?>
					</mark>
				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
	<table class="redux_status_table widefat" cellspacing="0" id="status">
		<thead>
		<tr>
			<th
				colspan="3"
				data-export-label="Active Plugins (<?php echo esc_html( count( (array) get_option( 'active_plugins' ) ) ); ?>)">
				<?php esc_html_e( 'Active Plugins', 'redux-framework' ); ?>
				(<?php echo esc_html( count( (array) get_option( 'active_plugins' ) ) ); ?>)
			</th>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $sysinfo['plugins'] as $name => $plugin_data ) {
			if ( ! empty( $plugin_data['Name'] ) ) {
				// link the plugin name to the plugin url if available.
				$plugin_name = $plugin_data['Name'];

				if ( ! empty( $plugin_data['PluginURI'] ) ) {
					$plugin_name = '<a href="' . esc_url( $plugin_data['PluginURI'] ) . '" title="' . esc_attr__( 'Visit plugin homepage', 'redux-framework' ) . '">' . esc_html( $plugin_name ) . '</a>';
				}
				?>
				<tr>
					<td><?php echo wp_kses_post( $plugin_name ); ?></td>
					<td class="help">&nbsp;</td>
					<td>
						<?php // translators: %s Author name and URL. ?>
						<?php echo sprintf( esc_html_x( 'by %s', 'by author', 'redux-framework' ), $plugin_data['Author'] ) . ' &ndash; ' . esc_html( $plugin_data['Version'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</td>
				</tr>
				<?php
			}
		}
		?>
		</tbody>
	</table>
	<?php
	if ( ! empty( $sysinfo['redux_instances'] ) && is_array( $sysinfo['redux_instances'] ) ) {
		foreach ( $sysinfo['redux_instances'] as $inst => $data ) {
			$inst_name = ucwords( str_replace( array( '_', '-' ), ' ', $inst ) );
			$args      = $data['args'];
			?>
			<table class="redux_status_table widefat" cellspacing="0" id="status">
				<thead>
				<tr>
					<th colspan="3" data-export-label="Redux Instance: <?php echo esc_html( $inst_name ); ?>">
						<?php
						esc_html_e( 'Redux Instance: ', 'redux-framework' );
						echo esc_html( $inst_name );
						?>
					</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td data-export-label="opt_name">opt_name:</td>
					<td class="help">
						<?php echo redux_help_tip( esc_attr__( 'The opt_name argument for this instance of Redux.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</td>
					<td><?php echo esc_html( $args['opt_name'] ); ?></td>
				</tr>
				<?php
				if ( isset( $args['global_variable'] ) && '' !== $args['global_variable'] ) {
					?>
					<tr>
						<td data-export-label="global_variable">global_variable:</td>
						<td class="help">
							<?php echo redux_help_tip( esc_attr__( 'The global_variable argument for this instance of Redux.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</td>
						<td><?php echo esc_html( $args['global_variable'] ); ?></td>
					</tr>
					<?php
				}
				?>
				<tr>
					<td data-export-label="dev_mode">dev_mode:</td>
					<td class="help">
						<?php echo redux_help_tip( esc_attr__( 'Indicates if developer mode is enabled for this instance of Redux.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</td>
					<td>
					<?php
					if ( true === $args['dev_mode'] ) {
						echo '<mark class="yes">';
						echo '<span class="dashicons dashicons-yes"></span>';
						echo '</mark>';
					} else {
						echo '<mark class="error">';
						echo '<span class="dashicons dashicons-no-alt"></span>';
						echo '</mark>';
					}
					?>
					</td>
				</tr>
				<tr>
					<td data-export-label="ajax_save">ajax_save:</td>
					<td class="help">
						<?php echo redux_help_tip( esc_attr__( 'Indicates if ajax based saving is enabled for this instance of Redux.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</td>
					<td>
					<?php
					if ( true === $args['ajax_save'] ) {
						echo '<mark class="yes">';
						echo '<span class="dashicons dashicons-yes"></span>';
						echo '</mark>';
					} else {
						echo '<mark class="error">';
						echo '<span class="dashicons dashicons-no-alt"></span>';
						echo '</mark>';
					}
					?>
					</td>
				</tr>
				<tr>
					<td data-export-label="page_slug">page_slug:</td>
					<td class="help">
						<?php echo redux_help_tip( esc_attr__( 'The page slug denotes the string used for the options panel page for this instance of Redux.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</td>
					<td><?php echo esc_html( $args['page_slug'] ); ?></td>
				</tr>
				<tr>
					<td data-export-label="page_permissions">page_permissions:</td>
					<td class="help">
						<?php echo redux_help_tip( esc_attr__( 'The page permissions variable sets the permission level required to access the options panel for this instance of Redux.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</td>
					<td><?php echo esc_html( $args['page_permissions'] ); ?></td>
				</tr>
				<tr>
					<td data-export-label="menu_type">menu_type:</td>
					<td class="help">
						<?php echo redux_help_tip( esc_attr__( 'This variable set whether or not the menu is displayed as an admin menu item for this instance of Redux.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</td>
					<td><?php echo esc_html( $args['menu_type'] ); ?></td>
				</tr>
				<tr>
					<td data-export-label="page_parent">page_parent:</td>
					<td class="help">
						<?php echo redux_help_tip( esc_attr__( 'The page parent variable sets where the options menu will be placed on the WordPress admin sidebar for this instance of Redux.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</td>
					<td><?php echo esc_html( $args['page_parent'] ); ?></td>
				</tr>

				<tr>
					<td data-export-label="compiler">compiler:</td>
					<td class="help">
						<?php echo redux_help_tip( esc_attr__( 'Indicates if the compiler flag is enabled for this instance of Redux.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</td>
					<td>
					<?php
					if ( true === $args['compiler'] ) {
						echo '<mark class="yes">';
						echo '<span class="dashicons dashicons-yes"></span>';
						echo '</mark>';
					} else {
						echo '<mark class="error">';
						echo '<span class="dashicons dashicons-no-alt"></span>';
						echo '</mark>';
					}
					?>
					</td>
				</tr>
				<tr>
					<td data-export-label="output">output:</td>
					<td class="help">
						<?php echo redux_help_tip( esc_attr__( 'Indicates if output flag for globally shutting off all CSS output is enabled for this instance of Redux.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</td>
					<td>
					<?php
					if ( true === $args['output'] ) {
						echo '<mark class="yes">';
						echo '<span class="dashicons dashicons-yes"></span>';
						echo '</mark>';
					} else {
						echo '<mark class="error">';
						echo '<span class="dashicons dashicons-no-alt"></span>';
						echo '</mark>';
					}
					?>
					</td>
				</tr>
				<tr>
					<td data-export-label="output_tag">output_tag:</td>
					<td class="help">
						<?php echo redux_help_tip( esc_attr__( 'The output_tag variable sets whether or not dynamic CSS will be generated for the customizer and Google fonts for this instance of Redux.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</td>
					<td>
					<?php
					if ( true === $args['output_tag'] ) {
						echo '<mark class="yes">';
						echo '<span class="dashicons dashicons-yes"></span>';
						echo '</mark>';
					} else {
						echo '<mark class="error">';
						echo '<span class="dashicons dashicons-no-alt"></span>';
						echo '</mark>';
					}
					?>
					</td>
				</tr>
				<?php if ( ! empty( Redux_Core::$callers[ $inst ] ) ) { ?>
					<tr>
						<td data-export-label="output_tag">Calling Files</td>
						<td class="help">
							<?php echo redux_help_tip( esc_attr__( 'These files are calling this opt_name. If you want to alter the config of this opt_name, modify these files.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</td>
						<td>
							<?php
							$last_slug = '';
							foreach ( Redux_Core::$callers[ $inst ] as $caller ) {
								$plugin_info = Redux_Helpers::is_inside_plugin( $caller );
								$theme_info  = Redux_Helpers::is_inside_theme( $caller );

								if ( $plugin_info ) {
									if ( $last_slug !== $plugin_info['slug'] ) {
										echo 'Plugin: <strong>' . esc_html( $plugin_info['slug'] ) . '</strong><br />';
									}
									$last_slug = $plugin_info['slug'];
									echo '&nbsp;&nbsp;&nbsp;&nbsp;~/' . esc_html( $plugin_info['basename'] ) . '<br />';
								} elseif ( $theme_info ) {
									if ( $last_slug !== $theme_info['slug'] ) {
										echo 'Theme: <strong>' . esc_html( $theme_info['slug'] ) . '</strong><br />';
									}
									$last_slug = $theme_info['slug'];
									echo '&nbsp;&nbsp;&nbsp;&nbsp;~/' . esc_html( $theme_info['basename'] ) . '<br />';
								}
							}
							?>
						</td>
					</tr>
				<?php } ?>

				<?php if ( isset( $args['templates_path'] ) && '' !== $args['templates_path'] ) { ?>
					<tr>
						<td data-export-label="template_path">template_path:</td>
						<td class="help">
							<?php echo redux_help_tip( esc_attr__( 'The specified template path containing custom template files for this instance of Redux.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</td>
						<td><?php echo '<code>' . esc_html( $args['templates_path'] ) . '</code>'; ?></td>
					</tr>
					<tr>
						<td id="panel-templates" data-export-label="Templates">Templates:</td>
						<td class="help">
							<?php echo redux_help_tip( esc_attr__( 'List of template files overriding the default Redux template files.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</td>
						<?php

						$found_files = $data['templates'];
						if ( $found_files ) {
							foreach ( $found_files as $plugin_name => $found_plugin_files ) {
								?>
								<td>
									<?php echo implode( ', <br/>', $found_plugin_files ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								</td>
								<?php
							}
						} else {
							?>
							<td>&ndash;</td>
							<?php
						}
						?>
					</tr>
				<?php } ?>
				<?php
				$ext = $data['extensions'];
				if ( ! empty( $ext ) && is_array( $ext ) ) {
					?>
					<tr>
						<td data-export-label="Extensions">Extensions</td>
						<td class="help">
							<?php echo redux_help_tip( esc_attr__( 'Indicates the installed Redux extensions and their version numbers.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</td>
						<td>
							<?php
							ksort( $ext );
							foreach ( $ext as $name => $arr ) {
								$ver = $arr['version'];

								echo '<a href="//redux.io/extensions/' . esc_html( str_replace( array( '_' ), '-', $name ) ) . '" target="blank">' . esc_html( ucwords( str_replace( array( '_', '-' ), ' ', $name ) ) ) . '</a> - ' . esc_html( $ver );
								?>
								<br/>
								<?php
							}
							?>
						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
			<?php
		}
	}
	?>
	<table class="redux_status_table widefat" cellspacing="0" id="status">
		<thead>
		<tr>
			<th colspan="3" data-export-label="Theme"><?php esc_html_e( 'Theme', 'redux-framework' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td data-export-label="Name"><?php esc_html_e( 'Name', 'redux-framework' ); ?>:</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'The name of the current active theme.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td><?php echo esc_html( $sysinfo['theme']['name'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Version"><?php esc_html_e( 'Version', 'redux-framework' ); ?>:</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'The installed version of the current active theme.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td>
				<?php
				echo esc_html( $sysinfo['theme']['version'] );

				// phpcs:ignored WordPress.NamingConventions.ValidVariableName
				if ( ! empty( $theme_version_data['version'] ) && version_compare( $theme_version_data['version'], $active_theme->Version, '!=' ) ) {
					echo ' &ndash; <strong style="color:red;">' . esc_html( $theme_version_data['version'] ) . ' ' . esc_html__( 'is available', 'redux-framework' ) . '</strong>';
				}
				?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Author URL"><?php esc_html_e( 'Author URL', 'redux-framework' ); ?>:</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'The theme developers URL.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td><?php echo esc_url( $sysinfo['theme']['author_uri'] ); ?></td>
		</tr>
		<tr>
			<td data-export-label="Child Theme"><?php esc_html_e( 'Child Theme', 'redux-framework' ); ?>:</td>
			<td class="help">
				<?php echo redux_help_tip( esc_attr__( 'Displays whether or not the current theme is a child theme.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</td>
			<td>
				<?php
				if ( is_child_theme() ) {
					echo '<mark class="yes"><span class="dashicons dashicons-yes"></span></mark>';
				} else {
					/* Translators: %s docs link. */
					echo '<span class="dashicons dashicons-no-alt"></span> &ndash; ' . wp_kses_post( sprintf( __( 'If you are modifying Redux Framework on a parent theme that you did not build personally we recommend using a child theme. See: <a href="%s" target="_blank">How to create a child theme</a>', 'redux-framework' ), '//codex.wordpress.org/Child_Themes' ) );
				}
				?>
			</td>
		</tr>
		<?php
		if ( is_child_theme() ) {
			?>
			<tr>
				<td data-export-label="Parent Theme Name"><?php esc_html_e( 'Parent Theme Name', 'redux-framework' ); ?>
					:
				</td>
				<td class="help">
					<?php echo redux_help_tip( esc_attr__( 'The name of the parent theme.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</td>
				<td><?php echo esc_html( $sysinfo['theme']['parent_name'] ); ?></td>
			</tr>
			<tr>
				<td data-export-label="Parent Theme Version">
					<?php esc_html_e( 'Parent Theme Version', 'redux-framework' ); ?>:
				</td>
				<td class="help">
					<?php echo redux_help_tip( esc_attr__( 'The installed version of the parent theme.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</td>
				<td><?php echo esc_html( $sysinfo['theme']['parent_version'] ); ?></td>
			</tr>
			<tr>
				<td data-export-label="Parent Theme Author URL">
					<?php esc_html_e( 'Parent Theme Author URL', 'redux-framework' ); ?>:
				</td>
				<td class="help">
					<?php echo redux_help_tip( esc_attr__( 'The parent theme developers URL.', 'redux-framework' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</td>
				<td><?php echo esc_url( $sysinfo['theme']['parent_author_uri'] ); ?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<script type="text/javascript">
		jQuery( 'a.redux-hint-qtip' ).click(
			function() {
				return false;
			}
		);

		jQuery( 'a.debug-report' ).click(
			function() {
				var report = '';

				jQuery( '#status thead, #status tbody' ).each(
					function() {
						if ( jQuery( this ).is( 'thead' ) ) {
							var label = jQuery( this ).find( 'th:eq(0)' ).data( 'export-label' ) || jQuery(
								this ).text();
							report = report + "\n### " + jQuery.trim( label ) + " ###\n\n";
						} else {
							jQuery( 'tr', jQuery( this ) ).each(
								function() {
									var label = jQuery( this ).find( 'td:eq(0)' ).data( 'export-label' ) || jQuery(
										this ).find( 'td:eq(0)' ).text();
									var the_name = jQuery.trim( label ).replace( /(<([^>]+)>)/ig, '' ); // Remove HTML
									var the_value = jQuery.trim( jQuery( this ).find( 'td:eq(2)' ).text() );
									var value_array = the_value.split( ', ' );

									if ( value_array.length > 1 ) {
										// If value have a list of plugins ','
										// Split to add new line
										var output = '';
										var temp_line = '';
										jQuery.each(
											value_array, function( key, line ) {
												temp_line = temp_line + line + '\n';
											}
										);

										the_value = temp_line;
									}

									report = report + '' + the_name + ': ' + the_value + "\n";
								}
							);
						}
					}
				);

				try {
					jQuery( "#debug-report" ).slideDown();
					jQuery( "#debug-report textarea" ).val( report ).focus().select();
					jQuery( this ).fadeOut();

					return false;
				} catch ( e ) {
					console.log( e );
				}

				return false;
			}
		);

		jQuery( document ).ready(
			function( $ ) {
				$( 'body' ).on(
					'copy', '#copy-for-support', function( e ) {
						e.clipboardData.clearData();
						e.clipboardData.setData( 'text/plain', $( '#debug-report textarea' ).val() );
						e.preventDefault();
					}
				);
			}
		);
	</script>
</div>
