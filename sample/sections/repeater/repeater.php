<?php
/**
 * Redux Repeater Sample config.
 *
 * For full documentation, please visit: http:https://devs.redux.io/
 *
 * @package Redux Pro
 */

defined( 'ABSPATH' ) || exit;

Redux::set_section(
	$opt_name,
	array(
		'title'      => __( 'Repeater', 'your-textdomain-here' ),
		'desc'       => esc_html__( 'For full documentation on this field, visit: ', 'your-textdomain-here' ) . '<a href="https://devs.redux.io/premium/repeater.html" target="_blank">https://devs.redux.io/premium/repeater.html</a>',
		'fields'     => array(
			array(
				'id'          => 'repeater-field-id2',
				'type'        => 'repeater',
				'title'       => esc_html__( 'Repeater Demo', 'your-textdomain-here' ),
				'full_width'  => true,
				'subtitle'    => esc_html__( 'Repeater', 'your-textdomain-here' ),
				'item_name'   => '',
				'sortable'    => true,
				'active'      => false,
				'collapsible' => false,
				'fields'      => array(
					array(
						'id'          => 'title_field',
						'type'        => 'text',
						'placeholder' => esc_html__( 'Title', 'your-textdomain-here' ),
					),
					array(
						'id'          => 'textarea_field',
						'type'        => 'textarea',
						'placeholder' => esc_html__( 'Text Field', 'your-textdomain-here' ),
						'default'     => 'Text Field here',
					),
					array(
						'id'          => 'select_field',
						'type'        => 'select',
						'multi'       => true,
						'title'       => esc_html__( 'Select Field', 'your-textdomain-here' ),
						'options'     => array(
							'1' => esc_html__( 'Option 1', 'your-textdomain-here' ),
							'2' => esc_html__( 'Option 2', 'your-textdomain-here' ),
							'3' => esc_html__( 'Option 3', 'your-textdomain-here' ),
						),
						'placeholder' => esc_html__( 'Listing Field', 'your-textdomain-here' ),
					),
					array(
						'id'          => 'switch_field',
						'type'        => 'switch',
						'placeholder' => esc_html__( 'Switch Field', 'your-textdomain-here' ),
						'default'     => true,
					),
					array(
						'id'          => 'text_field',
						'type'        => 'text',
						'placeholder' => esc_html__( 'Text Field', 'your-textdomain-here' ),
						'required'    => array( 'switch_field', '=', false ),
						'default'     => 'Text Field here',
					),
				),
			),
		),
	)
);
