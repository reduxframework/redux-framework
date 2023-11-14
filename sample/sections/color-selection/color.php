<?php
/**
 * Redux Framework color config.
 * For full documentation, please visit: http://devs.redux.io/
 *
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

Redux::set_section(
	$opt_name,
	array(
		'title'      => esc_html__( 'Color', 'your-textdomain-here' ),
		'id'         => 'opt-color',
		'desc'       => esc_html__( 'For full documentation on this field, visit: ', 'your-textdomain-here' ) . '<a href="https://devs.redux.io/core-fields/color.html" target="_blank">https://devs.redux.io/core-fields/color.html</a>',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'          => 'opt-color-title',
				'type'        => 'color',
				'output'      => array(
					'color'     => '.site-title, .wp-block-site-title a',
					'important' => true,
				),
				'title'       => esc_html__( 'Site Title Color', 'your-textdomain-here' ),
				'subtitle'    => esc_html__( 'Pick a title color for the theme (default: #000).', 'your-textdomain-here' ),
				'default'     => '#000000',
				// 'color_alpha' => true,
				'transparent' => false,
				'validate'    => 'color',
			),
			array(
				'id'          => 'opt-color-footer',
				'type'        => 'color',
				'title'       => esc_html__( 'Footer Background Color', 'your-textdomain-here' ),
				'subtitle'    => esc_html__( 'Pick a background color for the footer (default: #dd9933).', 'your-textdomain-here' ),
				'default'     => '#dd9933',
				'transparent' => false,
				'validate'    => 'color',
				'output'      => array(
					'background-color' => '.footer, #site-footer, .site-footer, footer',
				),
			),
		),
	)
);
