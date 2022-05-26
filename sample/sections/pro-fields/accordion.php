<?php
/**
 * Redux Pro Box Accordion config.
 *
 * For full documentation, please visit: http:https://devs.redux.io/
 *
 * @package Redux Pro
 */

defined( 'ABSPATH' ) || exit;

Redux::set_section(
	$opt_name,
	array(
		'title'      => esc_html__( 'Accordion Field', 'your-textdomain-here' ),
		'desc'       => esc_html__( 'For full documentation on this field, visit: ', 'your-textdomain-here' ) . '<a href="https://devs.redux.io/premium/accordion.html" target="_blank">https://devs.redux.io/premium/accordion.html</a>',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'accordion-section-1',
				'type'     => 'accordion',
				'title'    => esc_html__( 'Accordion Section One', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'Section one with subtitle', 'your-textdomain-here' ),
				'position' => 'start',
			),
			array(
				'id'       => 'opt-blank-text-1',
				'type'     => 'text',
				'title'    => esc_html__( 'Textbox for some noble purpose.', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'Frailty, thy name is woman!', 'your-textdomain-here' ),
			),
			array(
				'id'       => 'opt-blank-text-2',
				'type'     => 'switch',
				'title'    => esc_html__( 'Switch, for some other important task!', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'Physician, heal thyself!', 'your-textdomain-here' ),
			),
			array(
				'id'       => 'accordion-section-end-1',
				'type'     => 'accordion',
				'position' => 'end',
			),
			array(
				'id'       => 'accordion-section-2',
				'type'     => 'accordion',
				'title'    => esc_html__( 'Accordion Section Two (no subtitle)', 'your-textdomain-here' ),
				'position' => 'start',
				'open'     => true,
			),
			array(
				'id'       => 'opt-blank-text-3',
				'type'     => 'text',
				'title'    => esc_html__( 'Look, another sample textbox.', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'The tartness of his face sours ripe grapes.', 'your-textdomain-here' ),
			),
			array(
				'id'       => 'opt-blank-text-4',
				'type'     => 'switch',
				'title'    => esc_html__( 'Yes, another switch, but you\'re free to use any field you like.', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'I scorn you, scurvy companion!', 'your-textdomain-here' ),
			),
			array(
				'id'       => 'accordion-section-end-2',
				'type'     => 'accordion',
				'position' => 'end',
			),
		),
	)
);
