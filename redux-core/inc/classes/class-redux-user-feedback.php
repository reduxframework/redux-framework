<?php
/**
 * Plugin review class.
 * Prompts users to give a review of the plugin on WordPress.org after a period of usage.
 *
 * Heavily based on code by CoBlocks
 * https://github.com/coblocks/coblocks/blob/master/includes/admin/class-coblocks-feedback.php
 *
 * @package   Redux
 * @author    Jeffrey Carandang
 * @link      https://redux.io
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Feedback Notice Class
 */
class Redux_User_Feedback {

	/**
	 * Slug.
	 *
	 * @var string $slug
	 */
	private $slug;

	/**
	 * Name.
	 *
	 * @var string $name
	 */
	private $name;

	/**
	 * Time limit.
	 *
	 * @var string $time_limit
	 */
	private $time_limit;

	/**
	 * No Bug Option.
	 *
	 * @var string $nobug_option
	 */
	public $nobug_option;

	/**
	 * Activation Date Option.
	 *
	 * @var string $date_option
	 */
	public $date_option;

	/**
	 * Class constructor.
	 *
	 * @param array $args Arguments.
	 */
	public function __construct( array $args ) {

		$this->slug = $args['slug'];
		$this->name = $args['name'];

		$this->date_option  = $this->slug . '_activation_date';
		$this->nobug_option = $this->slug . '_no_bug';

		if ( isset( $args['time_limit'] ) ) {
			$this->time_limit = $args['time_limit'];
		} else {
			$this->time_limit = WEEK_IN_SECONDS;
		}

		if ( ! class_exists( 'Redux_Framework_Plugin' ) || ( class_exists( 'Redux_Framework_Plugin' ) && false === Redux_Framework_Plugin::$crash ) ) {
			// Add actions.
			add_action( 'admin_init', array( $this, 'check_installation_date' ) );
			add_action( 'admin_init', array( $this, 'set_no_bug' ), 5 );
		}
	}

	/**
	 * Seconds to words.
	 *
	 * @param string $seconds Seconds in time.
	 *
	 * @return string|null
	 */
	public function seconds_to_words( string $seconds ): ?string {

		// Get the years.
		$years = ( intval( $seconds ) / YEAR_IN_SECONDS ) % 100;
		if ( $years > 1 ) {
			/* translators: Number of years */
			return sprintf( __( '%s years', 'redux-framework' ), $years );
		} elseif ( $years > 0 ) {
			return __( 'a year', 'redux-framework' );
		}

		// Get the weeks.
		$weeks = ( intval( $seconds ) / WEEK_IN_SECONDS ) % 52;
		if ( $weeks > 1 ) {
			/* translators: Number of weeks */
			return sprintf( __( '%s weeks', 'redux-framework' ), $weeks );
		} elseif ( $weeks > 0 ) {
			return __( 'a week', 'redux-framework' );
		}

		// Get the days.
		$days = ( intval( $seconds ) / DAY_IN_SECONDS ) % 7;
		if ( $days > 1 ) {
			/* translators: Number of days */
			return sprintf( __( '%s days', 'redux-framework' ), $days );
		} elseif ( $days > 0 ) {
			return __( 'a day', 'redux-framework' );
		}

		// Get the hours.
		$hours = ( intval( $seconds ) / HOUR_IN_SECONDS ) % 24;
		if ( $hours > 1 ) {
			/* translators: Number of hours */
			return sprintf( __( '%s hours', 'redux-framework' ), $hours );
		} elseif ( $hours > 0 ) {
			return __( 'an hour', 'redux-framework' );
		}

		// Get the minutes.
		$minutes = ( intval( $seconds ) / MINUTE_IN_SECONDS ) % 60;
		if ( $minutes > 1 ) {
			/* translators: Number of minutes */
			return sprintf( __( '%s minutes', 'redux-framework' ), $minutes );
		} elseif ( $minutes > 0 ) {
			return __( 'a minute', 'redux-framework' );
		}

		// Get the seconds.
		$seconds = intval( $seconds ) % 60;
		if ( $seconds > 1 ) {
			/* translators: Number of seconds */
			return sprintf( __( '%s seconds', 'redux-framework' ), $seconds );
		} elseif ( $seconds > 0 ) {
			return __( 'a second', 'redux-framework' );
		}

		return null;
	}

	/**
	 * Check date on admin initiation and add to admin notice if it was more than the time limit.
	 */
	public function check_installation_date() {

		if ( ! get_site_option( $this->nobug_option ) || false === get_site_option( $this->nobug_option ) ) {

			add_site_option( $this->date_option, time() );

			// Retrieve the activation date.
			$install_date = get_site_option( $this->date_option );

			// If difference between install date and now is greater than time limit, then display notice.
			if ( ( time() - $install_date ) > $this->time_limit ) {
				add_action( 'admin_notices', array( $this, 'display_admin_notice' ) );
			}
		}
	}

	/**
	 * Display the admin notice.
	 */
	public function display_admin_notice() {

		$screen = get_current_screen();

		if ( isset( $screen->base ) && 'plugins' === $screen->base ) {
			$no_bug_url = wp_nonce_url( admin_url( 'plugins.php?' . $this->nobug_option . '=true' ), 'redux-feedback-nounce' );
			$time       = $this->seconds_to_words( time() - get_site_option( $this->date_option ) );
			?>
			<style>
				.notice.redux-notice {
					border-left-color: #24b0a6 !important;
					padding: 20px;
				}
				.rtl .notice.redux-notice {
					border-right-color: #19837c !important;
				}
				.notice.notice.redux-notice .redux-notice-inner {
					display: table;
					width: 100%;
				}
				.notice.redux-notice .redux-notice-inner .redux-notice-icon,
				.notice.redux-notice .redux-notice-inner .redux-notice-content,
				.notice.redux-notice .redux-notice-inner .redux-install-now {
					display: table-cell;
					vertical-align: middle;
				}
				.notice.redux-notice .redux-notice-icon {
					color: #509ed2;
					font-size: 13px;
					width: 60px;
				}
				.notice.redux-notice .redux-notice-icon img {
					width: 64px;
				}
				.notice.redux-notice .redux-notice-content {
					padding: 0 40px 0 20px;
				}
				.notice.redux-notice p {
					padding: 0;
					margin: 0;
				}
				.notice.redux-notice h3 {
					margin: 0 0 5px;
				}
				.notice.redux-notice .redux-install-now {
					text-align: center;
				}
				.notice.redux-notice .redux-install-now .redux-install-button {
					padding: 6px 50px;
					height: auto;
					line-height: 20px;
					background: #24b0a6;
					border-color: transparent;
					font-weight: bold;
				}
				.notice.redux-notice .redux-install-now .redux-install-button:hover {
					background: #19837c;
				}
				.notice.redux-notice a.no-thanks {
					display: block;
					margin-top: 10px;
					color: #72777c;
					text-decoration: none;
				}

				.notice.redux-notice a.no-thanks:hover {
					color: #444;
				}

				@media (max-width: 767px) {

					.notice.notice.redux-notice .redux-notice-inner {
						display: block;
					}
					.notice.redux-notice {
						padding: 20px !important;
					}
					.notice.redux-notice .redux-notice-inner {
						display: block;
					}
					.notice.redux-notice .redux-notice-inner .redux-notice-content {
						display: block;
						padding: 0;
					}
					.notice.redux-notice .redux-notice-inner .redux-notice-icon {
						display: none;
					}

					.notice.redux-notice .redux-notice-inner .redux-install-now {
						margin-top: 20px;
						display: block;
						text-align: left;
					}

					.notice.redux-notice .redux-notice-inner .no-thanks {
						display: inline-block;
						margin-left: 15px;
					}
				}
			</style>
			<div class="notice updated redux-notice">
				<div class="redux-notice-inner">
					<div class="redux-notice-icon">
						<?php /* translators: 1. Name */ ?>
						<img src="<?php echo esc_url( Redux_Core::$url . '/assets/img/icon--color.svg' ); ?>" alt="<?php printf( esc_attr__( '%s WordPress Plugin', 'redux-framework' ), esc_attr( $this->name ) ); ?>" />
					</div>
					<div class="redux-notice-content">
						<?php /* translators: 1. Name */ ?>
						<h3><?php printf( esc_html__( 'Are you enjoying %s?', 'redux-framework' ), esc_html( $this->name ) ); ?></h3>
						<p>
							<?php /* translators: 1. Name, 2. Time */ ?>
							<?php printf( esc_html__( 'You have been using %1$s for %2$s now. Would you mind leaving a review to let us know know what you think? We\'d really appreciate it!', 'redux-framework' ), esc_html( $this->name ), esc_html( $time ) ); ?>
						</p>
					</div>
					<div class="redux-install-now">
						<?php printf( '<a href="%1$s" class="button button-primary redux-install-button" target="_blank">%2$s</a>', esc_url( 'https://wordpress.org/support/plugin/redux-framework/reviews/?filter=5#new-post' ), esc_html__( 'Leave a Review', 'redux-framework' ) ); ?>
						<a href="<?php echo esc_url( $no_bug_url ); ?>" class="no-thanks"><?php echo esc_html__( 'No thanks / I already have', 'redux-framework' ); ?></a>
					</div>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Set the plugin to no longer bug users if user asks not to be.
	 */
	public function set_no_bug() {

		// Bail out if not on correct page.
		// phpcs:ignore
		if ( ! isset( $_GET['_wpnonce'] ) || ( ! wp_verify_nonce( $_GET['_wpnonce'], 'redux-feedback-nounce' ) || ! is_admin() || ! isset( $_GET[ $this->nobug_option ] ) || ! current_user_can( 'manage_options' ) ) ) {
			return;
		}

		add_site_option( $this->nobug_option, true );
	}
}

/*
 * Instantiate the Redux_User_Feedback class.
 */
new Redux_User_Feedback(
	array(
		'slug'       => 'Redux_plugin_feedback',
		'name'       => __( 'Redux', 'redux-framework' ),
		'time_limit' => WEEK_IN_SECONDS,
	)
);
