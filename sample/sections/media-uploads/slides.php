<?php
/**
 * Redux Framework slides config.
 * For full documentation, please visit: http://devs.redux.io/
 *
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

Redux::set_section(
	$opt_name,
	array(
		'title'      => esc_html__( 'Slides', 'your-textdomain-here' ),
		'id'         => 'additional-slides',
		'desc'       => esc_html__( 'For full documentation on this field, visit: ', 'your-textdomain-here' ) . '<a href="https://devs.redux.io/core-fields/slides.html" target="_blank">https://devs.redux.io/core-fields/slides.html</a>',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'          => 'opt-slides',
				'type'        => 'slides',
				'title'       => esc_html__( 'Slides Options', 'your-textdomain-here' ),
				'subtitle'    => esc_html__( 'Unlimited slides with drag and drop sortings.', 'your-textdomain-here' ),
				'desc'        => esc_html__( 'This field will store all slides values into a multidimensional array to use into a foreach loop.', 'your-textdomain-here' ),
				'placeholder' => array(
					'title'       => esc_html__( 'This is a title', 'your-textdomain-here' ),
					'description' => esc_html__( 'Description Here', 'your-textdomain-here' ),
					'url'         => esc_html__( 'Give us a link!', 'your-textdomain-here' ),
				),
			),
		),
	)
);
