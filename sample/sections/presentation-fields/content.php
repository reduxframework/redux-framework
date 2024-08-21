<?php
/**
 * Redux Framework content config.
 * For full documentation, please visit: https://devs.redux.io/
 *
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

Redux::set_section(
	$opt_name,
	array(
		'title'      => esc_html__( 'Content', 'your-textdomain-here' ),
		'id'         => 'presentation-content',
		'desc'       => esc_html__( 'For full documentation on this field, visit: ', 'your-textdomain-here' ) . '<a href="https://devs.redux.io/core-fields/content.html" target="_blank">https://devs.redux.io/core-fields/content.html</a>',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'      => 'opt-heading-1',
				'type'    => 'content',
				'mode'    => 'heading',
				'content' => 'This is a content field using the mode <strong>heading</strong>',
			),
			array(
				'id'      => 'opt-subheading-1',
				'type'    => 'content',
				'mode'    => 'subheading',
				'content' => 'This is a content field using the mode <strong>subheading</strong>',
			),
			array(
				'id'      => 'opt-content-1',
				'type'    => 'content',
				'mode'    => 'content',
				'content' => 'This is a content field using the mode <strong>content</strong>',
			),
			array(
				'id'      => 'opt-submessage-1',
				'type'    => 'content',
				'mode'    => 'submessage',
				'content' => 'This is a content field using the mode <strong>submessage</strong> with <strong>normal</strong> style.',
			),
			array(
				'id'      => 'opt-content-2',
				'type'    => 'content',
				'mode'    => 'content',
				'content' => 'This is a content field using the mode <strong>content</strong>',
			),
			array(
				'id'      => 'opt-submessage-2',
				'type'    => 'content',
				'mode'    => 'submessage',
				'content' => 'This is a content field using the mode <strong>submessage</strong> with <strong>success</strong> style.',
				'style'   => 'success',
			),
			array(
				'id'      => 'opt-submessage-3',
				'type'    => 'content',
				'mode'    => 'submessage',
				'content' => 'This is a content field using the mode <strong>submessage</strong> with <strong>info</strong> style.',
				'style'   => 'info',
			),
			array(
				'id'      => 'opt-submessage-4',
				'type'    => 'content',
				'mode'    => 'submessage',
				'content' => 'This is a content field using the mode <strong>submessage</strong> with <strong>warning</strong> style.',
				'style'   => 'warning',
			),
			array(
				'id'      => 'opt-submessage-5',
				'type'    => 'content',
				'mode'    => 'submessage',
				'content' => 'This is a content field using the mode <strong>submessage</strong> with <strong>critical</strong> style.',
				'style'   => 'critical',
			),
		),
	)
);
