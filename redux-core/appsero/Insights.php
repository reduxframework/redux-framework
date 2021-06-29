<?php
namespace ReduxAppsero;

/**
 * Appsero Insights
 *
 * This is a tracker class to track plugin usage based on if the customer has opted in.
 * No personal information is being tracked by this class, only general settings, active plugins, environment details
 * and admin email.
 */
class Insights {

	/**
	 * The notice text
	 *
	 * @var string
	 */
	public $notice;

	/**
	 * Wheather to the notice or not
	 *
	 * @var boolean
	 */
	protected $show_notice = true;

	/**
	 * If extra data needs to be sent
	 *
	 * @var array
	 */
	protected $extra_data = array();

	/**
	 * AppSero\Client
	 *
	 * @var object
	 */
	protected $client;

	/**
	 * Initialize the class
	 *
	 * @param AppSero\Client
	 */
	public function __construct( $client, $name = null, $file = null ) {

		if ( is_string( $client ) && ! empty( $name ) && ! empty( $file ) ) {
			$client = new Client( $client, $name, $file );
		}

		if ( is_object( $client ) && is_a( $client, 'ReduxAppsero\Client' ) ) {
			$this->client = $client;
		}
	}

	/**
	 * Don't show the notice
	 *
	 * @return \self
	 */
	public function hide_notice() {
		$this->show_notice = false;

		return $this;
	}

	/**
	 * Add extra data if needed
	 *
	 * @param array $data
	 *
	 * @return \self
	 */
	public function add_extra( $data = array() ) {
		$this->extra_data = $data;

		return $this;
	}

	/**
	 * Set custom notice text
	 *
	 * @param  string $text
	 *
	 * @return \self
	 */
	public function notice( $text ) {
		$this->notice = $text;

		return $this;
	}

	/**
	 * Initialize insights
	 *
	 * @return void
	 */
	public function init() {
		if ( $this->client->type == 'plugin' ) {
			$this->init_plugin();
		} elseif ( $this->client->type == 'theme' ) {
			$this->init_theme();
		}
	}

	/**
	 * Initialize theme hooks
	 *
	 * @return void
	 */
	public function init_theme() {
		$this->init_common();

		add_action( 'switch_theme', array( $this, 'deactivation_cleanup' ) );
		add_action( 'switch_theme', array( $this, 'theme_deactivated' ), 12, 3 );
	}

	/**
	 * Initialize plugin hooks
	 *
	 * @return void
	 */
	public function init_plugin() {
		// plugin deactivate popup
		if ( ! $this->is_local_server() ) {
			add_filter( 'plugin_action_links_' . $this->client->basename, array( $this, 'plugin_action_links' ) );
			add_action( 'admin_footer', array( $this, 'deactivate_scripts' ) );
		}

		$this->init_common();

		register_activation_hook( $this->client->file, array( $this, 'activate_plugin' ) );
		register_deactivation_hook( $this->client->file, array( $this, 'deactivation_cleanup' ) );
	}

	/**
	 * Initialize common hooks
	 *
	 * @return void
	 */
	protected function init_common() {

		if ( $this->show_notice ) {
			// tracking notice
			add_action( 'admin_notices', array( $this, 'admin_notice' ) );
		}

		add_action( 'admin_init', array( $this, 'handle_optin_optout' ) );

		// uninstall reason
		add_action( 'wp_ajax_' . $this->client->slug . '_submit-uninstall-reason', array( $this, 'uninstall_reason_submission' ) );

		// cron events
		add_filter( 'cron_schedules', array( $this, 'add_weekly_schedule' ) );
		add_action( $this->client->slug . '_tracker_send_event', array( $this, 'send_tracking_data' ) );
		// add_action( 'admin_init', array( $this, 'send_tracking_data' ) ); // test
	}

	/**
	 * Send tracking data to AppSero server
	 *
	 * @param  boolean $override
	 *
	 * @return void
	 */
	public function send_tracking_data( $override = false ) {
		// skip on AJAX Requests
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		if ( ! $this->tracking_allowed() && ! $override ) {
			return;
		}

		// Send a maximum of once per week
		$last_send = $this->get_last_send();

		if ( $last_send && $last_send > strtotime( '-1 week' ) ) {
			return;
		}

		$tracking_data = $this->get_tracking_data();

		$response = $this->client->send_request( $tracking_data, 'track' );

		update_option( $this->client->slug . '_tracking_last_send', time() );
	}

	/**
	 * Get the tracking data points
	 *
	 * @return array
	 */
	protected function get_tracking_data() {
		$all_plugins = $this->get_all_plugins();

		$users = get_users(
			array(
				'role'    => 'administrator',
				'orderby' => 'ID',
				'order'   => 'ASC',
				'number'  => 1,
				'paged'   => 1,
			)
		);

		$admin_user = ( is_array( $users ) && ! empty( $users ) ) ? $users[0] : false;
		$first_name = $last_name = '';

		if ( $admin_user ) {
			$first_name = $admin_user->first_name ? $admin_user->first_name : $admin_user->display_name;
			$last_name  = $admin_user->last_name;
		}

		$data = array(
			'version'          => $this->client->project_version,
			'url'              => esc_url( home_url() ),
			'site'             => $this->get_site_name(),
			'admin_email'      => get_option( 'admin_email' ),
			'first_name'       => $first_name,
			'last_name'        => $last_name,
			'hash'             => $this->client->hash,
			'server'           => $this->get_server_info(),
			'wp'               => $this->get_wp_info(),
			'users'            => $this->get_user_counts(),
			'active_plugins'   => count( $all_plugins['active_plugins'] ),
			'inactive_plugins' => count( $all_plugins['inactive_plugins'] ),
			'ip_address'       => $this->get_user_ip_address(),
			'theme'            => get_stylesheet(),
			'project_version'  => $this->client->project_version,
			'tracking_skipped' => false,
		);

		// Add metadata
		if ( $extra = $this->get_extra_data() ) {
			$data['extra'] = $extra;
		}

		// Check this has previously skipped tracking
		$skipped = get_option( $this->client->slug . '_tracking_skipped' );

		if ( $skipped === 'yes' ) {
			delete_option( $this->client->slug . '_tracking_skipped' );

			$data['tracking_skipped'] = true;
		}

		return apply_filters( $this->client->slug . '_tracker_data', $data );
	}

	/**
	 * If a child class wants to send extra data
	 *
	 * @return mixed
	 */
	protected function get_extra_data() {
		if ( is_callable( $this->extra_data ) ) {
			return call_user_func( $this->extra_data );
		}

		if ( is_array( $this->extra_data ) ) {
			return $this->extra_data;
		}

		return array();
	}

	/**
	 * Explain the user which data we collect
	 *
	 * @return string
	 */
	protected function data_we_collect() {
		$data = array(
			'Server environment details (php, mysql, server, WordPress versions)',
			'Number of users in your site',
			'Site language',
			'Number of active and inactive plugins',
			'Site name and url',
			'Your name and email address',
		);

		return $data;
	}

	/**
	 * Check if the user has opted into tracking
	 *
	 * @return bool
	 */
	public function tracking_allowed() {
		$allow_tracking = get_option( $this->client->slug . '_allow_tracking', 'no' );

		return $allow_tracking == 'yes';
	}

	/**
	 * Get the last time a tracking was sent
	 *
	 * @return false|string
	 */
	private function get_last_send() {
		return get_option( $this->client->slug . '_tracking_last_send', false );
	}

	/**
	 * Check if the notice has been dismissed or enabled
	 *
	 * @return boolean
	 */
	public function notice_dismissed() {
		$hide_notice = get_option( $this->client->slug . '_tracking_notice', null );

		if ( 'hide' == $hide_notice ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if the current server is localhost
	 *
	 * @return boolean
	 */
	private function is_local_server() {

		$is_local = false;

		$domains_to_check = array_unique(
			array(
				'siteurl' => wp_parse_url( get_site_url(), PHP_URL_HOST ),
				'homeurl' => wp_parse_url( get_home_url(), PHP_URL_HOST ),
			)
		);

		$forbidden_domains = array(
			'wordpress.com',
			'localhost',
			'localhost.localdomain',
			'127.0.0.1',
			'::1',
			'local.wordpress.test',         // VVV pattern.
			'local.wordpress-trunk.test',   // VVV pattern.
			'src.wordpress-develop.test',   // VVV pattern.
			'build.wordpress-develop.test', // VVV pattern.
		);

		foreach ( $domains_to_check as $domain ) {
			// If it's empty, just fail out.
			if ( ! $domain ) {
				$is_local = true;
				break;
			}

			// None of the explicit localhosts.
			if ( in_array( $domain, $forbidden_domains, true ) ) {
				$is_local = true;
				break;
			}

			// No .test or .local domains.
			if ( preg_match( '#\.(test|local)$#i', $domain ) ) {
				$is_local = true;
				break;
			}
		}

		return apply_filters( 'appsero_is_local', $is_local );
	}

	/**
	 * Schedule the event weekly
	 *
	 * @return void
	 */
	private function schedule_event() {
		$hook_name = $this->client->slug . '_tracker_send_event';

		if ( ! wp_next_scheduled( $hook_name ) ) {
			wp_schedule_event( time(), 'weekly', $hook_name );
		}
	}

	/**
	 * Clear any scheduled hook
	 *
	 * @return void
	 */
	private function clear_schedule_event() {
		wp_clear_scheduled_hook( $this->client->slug . '_tracker_send_event' );
	}

	/**
	 * Display the admin notice to users that have not opted-in or out
	 *
	 * @return void
	 */
	public function admin_notice() {

		if ( $this->notice_dismissed() ) {
			return;
		}

		if ( $this->tracking_allowed() ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// don't show tracking if a local server
		if ( $this->is_local_server() ) {
			return;
		}

		$optin_url  = add_query_arg( $this->client->slug . '_tracker_optin', 'true' );
		$optout_url = add_query_arg( $this->client->slug . '_tracker_optout', 'true' );

		if ( empty( $this->notice ) ) {
			$notice = sprintf( $this->client->__trans( 'Want to help make <strong>%1$s</strong> even more awesome? Allow %1$s to collect non-sensitive diagnostic data and usage information.' ), $this->client->name );
		} else {
			$notice = $this->notice;
		}

		$policy_url = 'https://' . 'appsero.com/privacy-policy/';

		$notice .= ' (<a class="' . $this->client->slug . '-insights-data-we-collect" href="#">' . $this->client->__trans( 'what we collect' ) . '</a>)';
		$notice .= '<p class="description" style="display:none;">' . implode( ', ', $this->data_we_collect() ) . '. No sensitive data is tracked. ';
		$notice .= 'We are using Appsero to collect your data. <a href="' . $policy_url . '">Learn more</a> about how Appsero collects and handle your data.</p>';

		echo '<div class="updated"><p>';
			echo $notice;
			echo '</p><p class="submit">';
			echo '&nbsp;<a href="' . esc_url( $optin_url ) . '" class="button-primary button-large">' . $this->client->__trans( 'Allow' ) . '</a>';
			echo '&nbsp;<a href="' . esc_url( $optout_url ) . '" class="button-secondary button-large">' . $this->client->__trans( 'No thanks' ) . '</a>';
		echo '</p></div>';

		echo "<script type='text/javascript'>jQuery('." . $this->client->slug . "-insights-data-we-collect').on('click', function(e) {
                e.preventDefault();
                jQuery(this).parents('.updated').find('p.description').slideToggle('fast');
            });
            </script>
        ";

	}

	/**
	 * handle the optin/optout
	 *
	 * @return void
	 */
	public function handle_optin_optout() {

		if ( isset( $_GET[ $this->client->slug . '_tracker_optin' ] ) && $_GET[ $this->client->slug . '_tracker_optin' ] == 'true' ) {
			$this->optin();

			wp_redirect( remove_query_arg( $this->client->slug . '_tracker_optin' ) );
			exit;
		}

		if ( isset( $_GET[ $this->client->slug . '_tracker_optout' ] ) && $_GET[ $this->client->slug . '_tracker_optout' ] == 'true' ) {
			$this->optout();

			wp_redirect( remove_query_arg( $this->client->slug . '_tracker_optout' ) );
			exit;
		}
	}

	/**
	 * Tracking optin
	 *
	 * @return void
	 */
	public function optin() {
		update_option( $this->client->slug . '_allow_tracking', 'yes' );
		update_option( $this->client->slug . '_tracking_notice', 'hide' );

		$this->clear_schedule_event();
		$this->schedule_event();
		$this->send_tracking_data();
	}

	/**
	 * Optout from tracking
	 *
	 * @return void
	 */
	public function optout() {
		update_option( $this->client->slug . '_allow_tracking', 'no' );
		update_option( $this->client->slug . '_tracking_notice', 'hide' );

		$this->send_tracking_skipped_request();

		$this->clear_schedule_event();
	}

	/**
	 * Get the number of post counts
	 *
	 * @param  string $post_type
	 *
	 * @return integer
	 */
	public function get_post_count( $post_type ) {
		global $wpdb;

		return (int) $wpdb->get_var( "SELECT count(ID) FROM $wpdb->posts WHERE post_type = '$post_type' and post_status = 'publish'" );
	}

	/**
	 * Get server related info.
	 *
	 * @return array
	 */
	private static function get_server_info() {
		global $wpdb;

		$server_data = array();

		if ( isset( $_SERVER['SERVER_SOFTWARE'] ) && ! empty( $_SERVER['SERVER_SOFTWARE'] ) ) {
			$server_data['software'] = $_SERVER['SERVER_SOFTWARE'];
		}

		if ( function_exists( 'phpversion' ) ) {
			$server_data['php_version'] = phpversion();
		}

		$server_data['mysql_version'] = $wpdb->db_version();

		$server_data['php_max_upload_size']  = size_format( wp_max_upload_size() );
		$server_data['php_default_timezone'] = date_default_timezone_get();
		$server_data['php_soap']             = class_exists( 'SoapClient' ) ? 'Yes' : 'No';
		$server_data['php_fsockopen']        = function_exists( 'fsockopen' ) ? 'Yes' : 'No';
		$server_data['php_curl']             = function_exists( 'curl_init' ) ? 'Yes' : 'No';

		return $server_data;
	}

	/**
	 * Get WordPress related data.
	 *
	 * @return array
	 */
	private function get_wp_info() {
		$wp_data = array();

		$wp_data['memory_limit'] = WP_MEMORY_LIMIT;
		$wp_data['debug_mode']   = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? 'Yes' : 'No';
		$wp_data['locale']       = get_locale();
		$wp_data['version']      = get_bloginfo( 'version' );
		$wp_data['multisite']    = is_multisite() ? 'Yes' : 'No';
		$wp_data['theme_slug']   = get_stylesheet();

		$theme = wp_get_theme( $wp_data['theme_slug'] );

		$wp_data['theme_name']    = $theme->get( 'Name' );
		$wp_data['theme_version'] = $theme->get( 'Version' );
		$wp_data['theme_uri']     = $theme->get( 'ThemeURI' );
		$wp_data['theme_author']  = $theme->get( 'Author' );

		return $wp_data;
	}

	/**
	 * Get the list of active and inactive plugins
	 *
	 * @return array
	 */
	private function get_all_plugins() {
		// Ensure get_plugins function is loaded
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}

		$plugins             = get_plugins();
		$active_plugins_keys = get_option( 'active_plugins', array() );
		$active_plugins      = array();

		foreach ( $plugins as $k => $v ) {
			// Take care of formatting the data how we want it.
			$formatted         = array();
			$formatted['name'] = strip_tags( $v['Name'] );

			if ( isset( $v['Version'] ) ) {
				$formatted['version'] = strip_tags( $v['Version'] );
			}

			if ( isset( $v['Author'] ) ) {
				$formatted['author'] = strip_tags( $v['Author'] );
			}

			if ( isset( $v['Network'] ) ) {
				$formatted['network'] = strip_tags( $v['Network'] );
			}

			if ( isset( $v['PluginURI'] ) ) {
				$formatted['plugin_uri'] = strip_tags( $v['PluginURI'] );
			}

			if ( in_array( $k, $active_plugins_keys ) ) {
				// Remove active plugins from list so we can show active and inactive separately
				unset( $plugins[ $k ] );
				$active_plugins[ $k ] = $formatted;
			} else {
				$plugins[ $k ] = $formatted;
			}
		}

		return array(
			'active_plugins'   => $active_plugins,
			'inactive_plugins' => $plugins,
		);
	}

	/**
	 * Get user totals based on user role.
	 *
	 * @return array
	 */
	public function get_user_counts() {
		$user_count          = array();
		$user_count_data     = count_users();
		$user_count['total'] = $user_count_data['total_users'];

		// Get user count based on user role
		foreach ( $user_count_data['avail_roles'] as $role => $count ) {
			if ( ! $count ) {
				continue;
			}
			$user_count[ $role ] = $count;
		}

		return $user_count;
	}

	/**
	 * Add weekly cron schedule
	 *
	 * @param array $schedules
	 *
	 * @return array
	 */
	public function add_weekly_schedule( $schedules ) {

		$schedules['weekly'] = array(
			'interval' => DAY_IN_SECONDS * 7,
			'display'  => 'Once Weekly',
		);

		return $schedules;
	}

	/**
	 * Plugin activation hook
	 *
	 * @return void
	 */
	public function activate_plugin() {
		$allowed = get_option( $this->client->slug . '_allow_tracking', 'no' );

		// if it wasn't allowed before, do nothing
		if ( 'yes' !== $allowed ) {
			return;
		}

		// re-schedule and delete the last sent time so we could force send again
		$hook_name = $this->client->slug . '_tracker_send_event';
		if ( ! wp_next_scheduled( $hook_name ) ) {
			wp_schedule_event( time(), 'weekly', $hook_name );
		}

		delete_option( $this->client->slug . '_tracking_last_send' );

		$this->send_tracking_data( true );
	}

	/**
	 * Clear our options upon deactivation
	 *
	 * @return void
	 */
	public function deactivation_cleanup() {
		$this->clear_schedule_event();

		if ( 'theme' == $this->client->type ) {
			delete_option( $this->client->slug . '_tracking_last_send' );
			delete_option( $this->client->slug . '_allow_tracking' );
		}

		delete_option( $this->client->slug . '_tracking_notice' );
	}

	/**
	 * Hook into action links and modify the deactivate link
	 *
	 * @param  array $links
	 *
	 * @return array
	 */
	public function plugin_action_links( $links ) {

		if ( array_key_exists( 'deactivate', $links ) ) {
			$links['deactivate'] = str_replace( '<a', '<a class="' . $this->client->slug . '-deactivate-link"', $links['deactivate'] );
		}

		return $links;
	}

	/**
	 * Plugin uninstall reasons
	 *
	 * @return array
	 */
	private function get_uninstall_reasons() {
		$reasons = array(
			array(
				'id'          => 'could-not-understand',
				'text'        => $this->client->__trans( 'I couldn\'t understand how to make it work' ),
				'type'        => 'textarea',
				'placeholder' => $this->client->__trans( 'Would you like us to assist you?' )
			),
			array(
				'id'          => 'found-better-plugin',
				'text'        => $this->client->__trans( 'I found a better plugin' ),
				'type'        => 'text',
				'placeholder' => $this->client->__trans( 'Which plugin?' )
			),
			array(
				'id'          => 'not-have-that-feature',
				'text'        => $this->client->__trans( 'The plugin is great, but I need specific feature that you don\'t support' ),
				'type'        => 'textarea',
				'placeholder' => $this->client->__trans( 'Could you tell us more about that feature?' )
			),
			array(
				'id'          => 'is-not-working',
				'text'        => $this->client->__trans( 'The plugin is not working' ),
				'type'        => 'textarea',
				'placeholder' => $this->client->__trans( 'Could you tell us a bit more whats not working?' )
			),
			array(
				'id'          => 'looking-for-other',
				'text'        => $this->client->__trans( 'It\'s not what I was looking for' ),
				'type'        => '',
				'placeholder' => ''
			),
			array(
				'id'          => 'did-not-work-as-expected',
				'text'        => $this->client->__trans( 'The plugin didn\'t work as expected' ),
				'type'        => 'textarea',
				'placeholder' => $this->client->__trans( 'What did you expect?' )
			),
			array(
				'id'          => 'other',
				'text'        => $this->client->__trans( 'Other' ),
				'type'        => 'textarea',
				'placeholder' => $this->client->__trans( 'Could you tell us a bit more?' )
			),
		);

		return $reasons;
	}

	/**
	 * Plugin deactivation uninstall reason submission
	 *
	 * @return void
	 */
	public function uninstall_reason_submission() {

		if ( ! isset( $_POST['reason_id'] ) ) {
			wp_send_json_error();
		}

		$data                = $this->get_tracking_data();
		$data['reason_id']   = sanitize_text_field( $_POST['reason_id'] );
		$data['reason_info'] = isset( $_REQUEST['reason_info'] ) ? trim( stripslashes( $_REQUEST['reason_info'] ) ) : '';

		$this->client->send_request( $data, 'deactivate' );

		wp_send_json_success();
	}

	/**
	 * Handle the plugin deactivation feedback
	 *
	 * @return void
	 */
	public function deactivate_scripts() {
		global $pagenow;

		if ( 'plugins.php' != $pagenow ) {
			return;
		}

		$reasons = $this->get_uninstall_reasons();
		?>

		<div class="wd-dr-modal" id="<?php echo $this->client->slug; ?>-wd-dr-modal">
			<div class="wd-dr-modal-wrap">
				<div class="wd-dr-modal-header">
					<h3><?php $this->client->_etrans( 'If you have a moment, please let us know why you are deactivating:' ); ?></h3>
				</div>

				<div class="wd-dr-modal-body">
					<ul class="reasons">
						<?php foreach ($reasons as $reason) { ?>
							<li data-type="<?php echo esc_attr( $reason['type'] ); ?>" data-placeholder="<?php echo esc_attr( $reason['placeholder'] ); ?>">
								<label><input type="radio" name="selected-reason" value="<?php echo $reason['id']; ?>"> <?php echo $reason['text']; ?></label>
							</li>
						<?php } ?>
					</ul>
					<p class="wd-dr-modal-reasons-bottom">
						<?php
						echo sprintf(
							$this->client->__trans( 'We use this information to troubleshoot problems and make product improvements. <a href="%1$s" target="_blank">Learn more</a> about how we handle you data.' ),
							esc_url( 'https://redux.io/privacy?utm_source=plugin&utm_medium=appsero&utm_campaign=deactivate' )
						);
						?>
					</p>
				</div>

				<div class="wd-dr-modal-footer">
					<a href="#" class="dont-bother-me"><?php $this->client->_etrans( "I rather wouldn't say" ); ?></a>
					<button class="button-secondary"><?php $this->client->_etrans( 'Submit & Deactivate' ); ?></button>
					<button class="button-primary"><?php $this->client->_etrans( 'Cancel' ); ?></button>
				</div>
			</div>
		</div>

		<style type="text/css">
			.wd-dr-modal {
				position: fixed;
				z-index: 99999;
				top: 0;
				right: 0;
				bottom: 0;
				left: 0;
				background: rgba(0,0,0,0.5);
				display: none;
			}

			.wd-dr-modal.modal-active {
				display: block;
			}

			.wd-dr-modal-wrap {
				width: 475px;
				position: relative;
				margin: 10% auto;
				background: #fff;
			}

			.wd-dr-modal-header {
				border-bottom: 1px solid #eee;
				padding: 8px 20px;
			}

			.wd-dr-modal-header h3 {
				line-height: 150%;
				margin: 0;
			}

			.wd-dr-modal-body {
				padding: 5px 20px 20px 20px;
			}

			.wd-dr-modal-body .reason-input {
				margin-top: 5px;
				margin-left: 20px;
			}
			.wd-dr-modal-footer {
				border-top: 1px solid #eee;
				padding: 12px 20px;
				text-align: right;
			}
			.wd-dr-modal-reasons-bottom {
				margin: 15px 0 0 0;
			}
		</style>

		<script type="text/javascript">
			(function($) {
				$(function() {
					var modal = $( '#<?php echo $this->client->slug; ?>-wd-dr-modal' );
					var deactivateLink = '';

					$( '#the-list' ).on('click', 'a.<?php echo $this->client->slug; ?>-deactivate-link', function(e) {
						e.preventDefault();

						modal.addClass('modal-active');
						deactivateLink = $(this).attr('href');
						modal.find('a.dont-bother-me').attr('href', deactivateLink).css('float', 'left');
					});

					modal.on('click', 'button.button-primary', function(e) {
						e.preventDefault();

						modal.removeClass('modal-active');
					});

					modal.on('click', 'input[type="radio"]', function () {
						var parent = $(this).parents('li:first');

						modal.find('.reason-input').remove();

						var inputType = parent.data('type'),
							inputPlaceholder = parent.data('placeholder'),
							reasonInputHtml = '<div class="reason-input">' + ( ( 'text' === inputType ) ? '<input type="text" size="40" />' : '<textarea rows="5" cols="45"></textarea>' ) + '</div>';

						if ( inputType !== '' ) {
							parent.append( $(reasonInputHtml) );
							parent.find('input, textarea').attr('placeholder', inputPlaceholder).focus();
						}
					});

					modal.on('click', 'button.button-secondary', function(e) {
						e.preventDefault();

						var button = $(this);

						if ( button.hasClass('disabled') ) {
							return;
						}

						var $radio = $( 'input[type="radio"]:checked', modal );

						var $selected_reason = $radio.parents('li:first'),
							$input = $selected_reason.find('textarea, input[type="text"]');

						$.ajax({
							url: ajaxurl,
							type: 'POST',
							data: {
								action: '<?php echo $this->client->slug; ?>_submit-uninstall-reason',
								reason_id: ( 0 === $radio.length ) ? 'none' : $radio.val(),
								reason_info: ( 0 !== $input.length ) ? $input.val().trim() : ''
							},
							beforeSend: function() {
								button.addClass('disabled');
								button.text('Processing...');
							},
							complete: function() {
								window.location.href = deactivateLink;
							}
						});
					});
				});
			}(jQuery));
		</script>

		<?php
	}

	/**
	 * Run after theme deactivated
	 *
	 * @param  string $new_name
	 * @param  object $new_theme
	 * @param  object $old_theme
	 * @return void
	 */
	public function theme_deactivated( $new_name, $new_theme, $old_theme ) {
		// Make sure this is appsero theme
		if ( $old_theme->get_template() == $this->client->slug ) {
			$this->client->send_request( $this->get_tracking_data(), 'deactivate' );
		}
	}

	/**
	 * Get user IP Address
	 */
	private function get_user_ip_address() {
		$response = wp_remote_get( 'https://icanhazip.com/' );

		if ( is_wp_error( $response ) ) {
			return '';
		}

		$ip = trim( wp_remote_retrieve_body( $response ) );

		if ( ! filter_var( $ip, FILTER_VALIDATE_IP ) ) {
			return '';
		}

		return $ip;
	}

	/**
	 * Get site name
	 */
	private function get_site_name() {
		$site_name = get_bloginfo( 'name' );

		if ( empty( $site_name ) ) {
			$site_name = get_bloginfo( 'description' );
			$site_name = wp_trim_words( $site_name, 3, '' );
		}

		if ( empty( $site_name ) ) {
			$site_name = esc_url( home_url() );
		}

		return $site_name;
	}

	/**
	 * Send request to appsero if user skip to send tracking data
	 */
	private function send_tracking_skipped_request() {
		$skipped = get_option( $this->client->slug . '_tracking_skipped' );

		$data = [
			'hash'               => $this->client->hash,
			'previously_skipped' => false,
		];

		if ( $skipped === 'yes' ) {
			$data['previously_skipped'] = true;
		} else {
			update_option( $this->client->slug . '_tracking_skipped', 'yes' );
		}

		$this->client->send_request( $data, 'tracking-skipped' );
	}
}
