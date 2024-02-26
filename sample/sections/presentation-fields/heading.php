<?php
/**
 * Redux Framework info config.
 * For full documentation, please visit: http://devs.redux.io/
 *
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

Redux::set_section(
	$opt_name,
	array(
		'title'      => esc_html__( 'Heading', 'your-textdomain-here' ),
		'id'         => 'presentation-heading',
		'desc'       => esc_html__( 'For full documentation on this field, visit: ', 'your-textdomain-here' ) . '<a href="https://devs.redux.io/core-fields/heading.html" target="_blank">https://devs.redux.io/core-fields/heading.html</a>',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'      => 'opt-heading-1',
				'type'    => 'heading',
				'content' => 'My Custom Redux Heading',
			),
			array(
				'id'      => 'opt-heading-2',
				'type'    => 'heading',
				'content' => 'My Custom Redux Heading',
			),
		),
	)
);
