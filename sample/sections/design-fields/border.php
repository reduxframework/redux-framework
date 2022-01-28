<?php
/**
 * Redux Framework border config.
 * For full documentation, please visit: http://devs.redux.io/
 *
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

Redux::set_section(
	$opt_name,
	array(
		'title'      => esc_html__( 'Border', 'your-textdomain-here' ),
		'id'         => 'design-border',
		'desc'       => esc_html__( 'For full documentation on this field, visit: ', 'your-textdomain-here' ) . '<a href="https://devs.redux.io/core-fields/border.html" target="_blank">https://devs.redux.io/core-fields/border.html</a>',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'opt-header-border',
				'type'     => 'border',
				'title'    => esc_html__( 'Header Border Option', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'Only color validation can be done on this field type', 'your-textdomain-here' ),
				'output'   => array( '.site-header, header' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
				'default'  => array(
					'border-color'  => '#1e73be',
					'border-style'  => 'solid',
					'border-top'    => '3px',
					'border-right'  => '3px',
					'border-bottom' => '3px',
					'border-left'   => '3px',
				),
			),
			array(
				'id'       => 'opt-header-border-expanded',
				'type'     => 'border',
				'title'    => esc_html__( 'Body Border Option', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'Only color validation can be done on this field type', 'your-textdomain-here' ),
				'output'   => array( 'body' ),
				'all'      => false,
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
				'default'  => array(
					'border-color'  => '#1e73be',
					'border-style'  => 'solid',
					'border-top'    => '3px',
					'border-right'  => '3px',
					'border-bottom' => '3px',
					'border-left'   => '3px',
				),
			),
		),
	)
);
