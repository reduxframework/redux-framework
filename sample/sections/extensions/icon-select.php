<?php
/**
 * Redux Pro Icon Select Sample config.
 * For full documentation, please visit: http:https://devs.redux.io/
 *
 * @package Redux Pro
 */

defined( 'ABSPATH' ) || exit;

require_once Redux_Core::$dir . 'inc/extensions/icon_select/font-awesome-5-free.php';

Redux::set_section(
	$opt_name,
	array(
		'title'      => esc_html__( 'Icon Select', 'your-textdomain-here' ),
		'desc'       => esc_html__( 'For full documentation on this field, visit: ', 'your-textdomain-here' ) . '<a href="https://devs.redux.io/premium/icon-select.html" target="_blank">https://devs.redux.io/premium/icon-select.html</a>',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'               => 'icon_select_field',
				'type'             => 'icon_select',
				'title'            => esc_html__( 'Icon Select', 'your-textdomain-here' ),
				'subtitle'         => esc_html__( 'Select an icon.', 'your-textdomain-here' ),
				'default'          => '',
				//'options'          => redux_icon_select_fa_5_free(),

				// Disable auto-enqueue of stylesheet if present in the panel.
				'enqueue'          => true,

				// Disable auto-enqueue of stylesheet on the front-end.
				'enqueue_frontend' => false,
				//'stylesheet'       => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.css',

				// If needed to initialize the icon.
				'prefix'           => 'fa',

				// How each icon begins for this given font.
				'selector'         => 'fa-',
			),
			array(
				'id'               => 'icon_select_field_2',
				'type'             => 'icon_select',
				'title'            => esc_html__( 'Icon Select 2', 'your-textdomain-here' ),
				'subtitle'         => esc_html__( 'Select an icon.', 'your-textdomain-here' ),
				'default'          => '',
				//'options'          => redux_icon_select_fa_5_free(),

				// Disable auto-enqueue of stylesheet if present in the panel.
				'enqueue'          => true,

				// Disable auto-enqueue of stylesheet on the front-end.
				'enqueue_frontend' => false,
				'stylesheet'       => array('stylesheet' => '', 'title' => 'title' ), //'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css',

				// If needed to initialize the icon.
				'prefix'           => 'fa',

				// How each icon begins for this given font.
				'selector'         => 'fa-',
			),
		),
	)
);
