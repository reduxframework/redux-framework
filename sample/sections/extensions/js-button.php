<?php
/**
 * Redux Pro JS Button Sample config.
 * For full documentation, please visit: http:https://devs.redux.io/
 *
 * @package Redux Pro
 */

defined( 'ABSPATH' ) || exit;

Redux::set_section(
	$opt_name,
	array(
		'title'      => esc_html__( 'JS Button', 'your-textdomain-here' ),
		'desc'       => esc_html__( 'For full documentation on this field, visit: ', 'your-textdomain-here' ) . '<a href="https://devs.redux.io/core-extensions/js-button.html" target="_blank">https://devs.redux.io/core-extensions/js-button.html</a>',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'opt-js-button',
				'type'     => 'js_button',
				'title'    => esc_html__( 'JS Button', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'Run javascript in the options panel from button clicks.', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'Click the Add Date button to add the current date into the text field below.', 'your-textdomain-here' ),
				'script'   => array(
					'url'       => plugins_url( 'sample/sections/extensions/js-button.js', REDUX_PLUGIN_FILE ),
					'dir'       => dirname( __FILE__ ) . '/js-button.js',
					'dep'       => array( 'jquery' ),
					'ver'       => time(),
					'in_footer' => true,
				),
				'buttons'  => array(
					array(
						'text'     => esc_html__( 'Add Date', 'your-textdomain-here' ),
						'class'    => 'button-primary',
						'function' => 'redux_add_date',
					),
					array(
						'text'     => esc_html__( 'Alert', 'your-textdomain-here' ),
						'class'    => 'button-secondary',
						'function' => 'redux_show_alert',
					),

				),
			),
			array(
				'id'       => 'opt-blank-text',
				'type'     => 'text',
				'title'    => esc_html__( 'Date', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'Click the Add Date button above to fill out this field.', 'your-textdomain-here' ),
			),
		),
	)
);
