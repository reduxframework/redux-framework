<?php
/**
 * Redux Pro Box Shadow Sample config.
 * For full documentation, please visit: http:https://devs.redux.io/
 *
 * @package Redux Pro
 */

defined( 'ABSPATH' ) || exit;

Redux::set_section(
	$opt_name,
	array(
		'title'      => esc_html__( 'Box Shadow', 'your-textdomain-here' ),
		'id'         => 'design-box-shadow',
		// phpcs:ignore
		// 'desc'       => esc_html__( 'For full documentation on this field, visit: ', 'your-textdomain-here' ) . '<a href="https://devs.redux.io/core-fields/box-shadow.html" target="_blank">https://devs.redux.io/core-fields/box_shadow.html</a>',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'          => 'opt-box_shadow',
				'type'        => 'box_shadow',
				'output'      => array( '.site-header' ),
				'color_alpha' => array(
					'inset-shadow' => true,
				),
				'media_query' => array(
					'output'   => true,
					'compiler' => true,
					'queries'  => array(
						array(
							'rule'      => 'screen and (max-width: 360px)',
							'selectors' => array( '.box-shadow' ),
						),
						array(
							'rule'      => 'screen and (max-width: 1120px)',
							'selectors' => array( '.box-shadow-wide' ),
						),
					),
				),
				'title'       => esc_html__( 'Box Shadow', 'your-textdomain-here' ),
				'subtitle'    => esc_html__( 'Site Header Box Shadow with inset and drop shadows.', 'your-textdomain-here' ),
				'desc'        => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
			),
		),
	)
);
