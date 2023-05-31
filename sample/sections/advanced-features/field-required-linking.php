<?php
/**
 * Redux Framework required/linking config.
 * For full documentation, please visit: http://devs.redux.io/
 *
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

Redux::set_section(
	$opt_name,
	array(
		'title'      => esc_html__( 'Field Required / Linking', 'your-textdomain-here' ),
		'id'         => 'required',
		'desc'       => esc_html__( 'For full documentation on validation, visit: ', 'your-textdomain-here' ) . '<a href="https://devs.redux.io/configuration/fields/required.html" target="_blank">https://devs.redux.io/configuration/fields/required.html</a>',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'opt-required-basic',
				'type'     => 'switch',
				'title'    => esc_html__( 'Basic Required Example', 'your-textdomain-here' ),
				'subtitle' => wp_kses_post( __( 'Click <code>On</code> to see the text field appear.', 'your-textdomain-here' ) ),
				'default'  => false,
			),
			array(
				'id'       => 'opt-required-basic-text',
				'type'     => 'text',
				'title'    => esc_html__( 'Basic Text Field', 'your-textdomain-here' ),
				'subtitle' => wp_kses_post( __( 'This text field is only show when the above switch is set to <code>On</code>, using the <code>required</code> argument.', 'your-textdomain-here' ) ),
				'required' => array( 'opt-required-basic', '=', true ),
			),
			array(
				'id'   => 'opt-required-divide-1',
				'type' => 'divide',
			),
			array(
				'id'       => 'opt-required-nested',
				'type'     => 'switch',
				'title'    => esc_html__( 'Nested Required Example', 'your-textdomain-here' ),
				'subtitle' => wp_kses_post( __( 'Click <code>On</code> to see another set of options appear.', 'your-textdomain-here' ) ),
				'default'  => false,
			),
			array(
				'id'       => 'opt-required-nested-buttonset',
				'type'     => 'button_set',
				'title'    => esc_html__( 'Multiple Nested Required Examples', 'your-textdomain-here' ),
				'subtitle' => wp_kses_post( __( 'Click any button to show different fields based on their <code>required</code> statements.', 'your-textdomain-here' ) ),
				'options'  => array(
					'button-text'     => esc_html__( 'Show Text Field', 'your-textdomain-here' ),
					'button-textarea' => esc_html__( 'Show Textarea Field', 'your-textdomain-here' ),
					'button-editor'   => esc_html__( 'Show WP Editor', 'your-textdomain-here' ),
					'button-ace'      => esc_html__( 'Show ACE Editor', 'your-textdomain-here' ),
				),
				'required' => array( 'opt-required-nested', '=', true ),
				'default'  => 'button-text',
			),
			array(
				'id'       => 'opt-required-nested-text',
				'type'     => 'text',
				'title'    => esc_html__( 'Nested Text Field', 'your-textdomain-here' ),
				'required' => array( 'opt-required-nested-buttonset', '=', 'button-text' ),
			),
			array(
				'id'       => 'opt-required-nested-textarea',
				'type'     => 'textarea',
				'title'    => esc_html__( 'Nested Textarea Field', 'your-textdomain-here' ),
				'required' => array( 'opt-required-nested-buttonset', '=', 'button-textarea' ),
			),
			array(
				'id'       => 'opt-required-nested-editor',
				'type'     => 'editor',
				'title'    => esc_html__( 'Nested Editor Field', 'your-textdomain-here' ),
				'required' => array( 'opt-required-nested-buttonset', '=', 'button-editor' ),
			),
			array(
				'id'       => 'opt-required-nested-ace',
				'type'     => 'ace_editor',
				'title'    => esc_html__( 'Nested ACE Editor Field', 'your-textdomain-here' ),
				'required' => array( 'opt-required-nested-buttonset', '=', 'button-ace' ),
			),
			array(
				'id'   => 'opt-required-divide-2',
				'type' => 'divide',
			),
			array(
				'id'       => 'opt-required-select',
				'type'     => 'select',
				'title'    => esc_html__( 'Select Required Example', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'Select a different option to display its value.  Required may be used to display multiple & reusable fields', 'your-textdomain-here' ),
				'options'  => array(
					'no-sidebar'    => esc_html__( 'No Sidebars', 'your-textdomain-here' ),
					'left-sidebar'  => esc_html__( 'Left Sidebar', 'your-textdomain-here' ),
					'right-sidebar' => esc_html__( 'Right Sidebar', 'your-textdomain-here' ),
					'both-sidebars' => esc_html__( 'Both Sidebars', 'your-textdomain-here' ),
				),
				'default'  => 'no-sidebar',
				'select2'  => array( 'allowClear' => false ),
			),
			array(
				'id'       => 'opt-required-select-left-sidebar',
				'type'     => 'select',
				'title'    => esc_html__( 'Select Left Sidebar', 'your-textdomain-here' ),
				'data'     => 'sidebars',
				'default'  => '',
				'required' => array( 'opt-required-select', '=', array( 'left-sidebar', 'both-sidebars' ) ),
			),
			array(
				'id'       => 'opt-required-select-right-sidebar',
				'type'     => 'select',
				'title'    => esc_html__( 'Select Right Sidebar', 'your-textdomain-here' ),
				'data'     => 'sidebars',
				'default'  => '',
				'required' => array( 'opt-required-select', '=', array( 'right-sidebar', 'both-sidebars' ) ),
			),
		),
	)
);
