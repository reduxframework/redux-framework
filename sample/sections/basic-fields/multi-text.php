<?php
/**
 * Redux Framework multi text config.
 * For full documentation, please visit: http://devs.redux.io/
 *
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

Redux::set_section(
	$opt_name,
	array(
		'title'      => esc_html__( 'Multi Text', 'your-textdomain-here' ),
		'id'         => 'basic-multi-text',
		'desc'       => esc_html__( 'For full documentation on this field, visit: ', 'your-textdomain-here' ) . '<a href="https://devs.redux.io/core-fields/multi-text.html" target="_blank">https://devs.redux.io/core-fields/multi-text.html</a>',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'opt-multitext',
				'type'     => 'multi_text',
				'title'    => esc_html__( 'Multi Text Option', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'Field subtitle', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'Field Decription', 'your-textdomain-here' ),
			),
		),
	)
);
