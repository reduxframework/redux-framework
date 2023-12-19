<?php
/**
 * Redux Icon Select Sample config.
 * For full documentation, please visit: http:https://devs.redux.io/
 *
 * @package Redux
 */

defined( 'ABSPATH' ) || exit;

/**
 * This file is for backward compatibility. Please do not use.
 * FontAwesome 6+ is preinstalled with Redux.
 */
require_once Redux_Core::$dir . 'inc/extensions/icon_select/font-awesome-5-free.php';

Redux::set_section(
	$opt_name,
	array(
		'title'      => esc_html__( 'Icon Select', 'your-textdomain-here' ),
		'desc'       => esc_html__( 'For full documentation on this field, visit: ', 'your-textdomain-here' ) . '<a href="https://devs.redux.io/core-extensions/icon-select.html" target="_blank">https://devs.redux.io/core-extensions/icon-select.html</a>',
		'subsection' => true,
		'fields'     => array(

			/**
			 * This field was left in the sample config to display that every effort to maintain backward compatibility with older
			 * versions of Icon Select has been implemented.
			 * Please do NOT use argument in this field in your projects.
			 * They are considered deprecated.
			 */
			array(
				'id'               => 'icon-select-legacy',
				'type'             => 'icon_select',
				'title'            => esc_html__( 'Legacy Icon Select', 'your-textdomain-here' ),
				'subtitle'         => esc_html__( 'Original Icon Select field that maintains backward compatibility with the original extension.', 'your-textdomain-here' ),
				'default'          => '',
				'options'          => redux_icon_select_fa_5_free(),

				// Disable auto-enqueue of stylesheet if present in the panel.
				'enqueue'          => true,

				// Disable auto-enqueue of stylesheet on the front-end.
				'enqueue_frontend' => true,

				// Stylesheet URL.
				'stylesheet'       => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css',

				// (Optional) Specify a class prefix if one is needed to initialize the icon.
				'prefix'           => 'fa',
			),

			/**
			 * When creating fields for Icon Select, use this as a template instead.
			 * For detailed documentation, see: https://devs.redux.io/core-extensions/icon-select.html
			 */
			array(
				'id'               => 'icon-select',
				'type'             => 'icon_select',
				'title'            => esc_html__( 'Icon Select', 'your-textdomain-here' ),
				'subtitle'         => esc_html__( 'Select an icon.', 'your-textdomain-here' ),
				'default'          => 'fas fa-1',

				// Disable auto-enqueue of stylesheet if present in the panel.
				'enqueue'          => true,

				// Disable auto-enqueue of stylesheet on the front-end.
				'enqueue_frontend' => true,

				// Stylesheet data.
				'stylesheet'       => array(
					array(
						'url'    => 'https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.2.96/css/materialdesignicons.css',
						'title'  => 'Material Icons',
						'prefix' => 'mdi-set',
					),
					array(
						'url'    => 'https://icons.getbootstrap.com/assets/font/bootstrap-icons.min.css',
						'title'  => 'Bootstrap',
						'prefix' => 'bi',
					),
					array(
						'url'    => 'https://cdn.lineicons.com/4.0/lineicons.css',
						'title'  => 'Line Icons',
						'prefix' => 'lni',
					),
					array(
						'url'    => 'https://cdn.jsdelivr.net/gh/devicons/devicon@v2.15.1/devicon.min.css',
						'title'  => 'Dev Icons',
						'prefix' => '',
					),
				),
			),
		),
	)
);
