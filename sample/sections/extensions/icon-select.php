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
				'options'          => redux_icon_select_fa_5_free(),

				// Disable auto-enqueue of stylesheet if present in the panel.
				'enqueue'          => true,

				// Disable auto-enqueue of stylesheet on the front-end.
				'enqueue_frontend' => false,
				'stylesheet'       => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css',

				// If needed to initialize the icon.
				'prefix'           => 'fa',
			),
			array(
				'id'               => 'icon_select_material',
				'type'             => 'icon_select',
				'title'            => esc_html__( 'Icon Material', 'your-textdomain-here' ),
				'subtitle'         => esc_html__( 'Select an icon.', 'your-textdomain-here' ),
				'default'          => 'fas fa-1',

				// Disable auto-enqueue of stylesheet if present in the panel.
				'enqueue'          => true,

				// Disable auto-enqueue of stylesheet on the front-end.
				'enqueue_frontend' => false,

				// Stylesheet data.
				'stylesheet'       => array(
					array(
						'url'    => 'https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.2.96/css/materialdesignicons.css',
						'title'  => 'Material Icons',
						'prefix' => 'mdi-set',
						'regex'  => array(),
					),
					array(
						'url'    => 'https://icons.getbootstrap.com/assets/font/bootstrap-icons.min.css',
						'title'  => 'Bootstrap',
						'prefix' => 'bi',
						'regex'  => array(),
					),
					array(
						'url'    => 'https://cdn.lineicons.com/4.0/lineicons.css',
						'title'  => 'Line Icons',
						'prefix' => 'lni',
						'regex'  => array(),
					),
				),
			),

		),
	)
);

