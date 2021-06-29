<?php
/**
 * Redux Pro Multi-Media Sample config.
 *
 * For full documentation, please visit: http:https://devs.redux.io/
 *
 * @package Redux Pro
 */

defined( 'ABSPATH' ) || exit;

Redux::setSection(
	$opt_name,
	array(
		'title'      => esc_html__( 'Multi Media Selector', 'your-textdomain-here' ),
		'desc'       => esc_html__( 'For full documentation on this field, visit: ', 'your-textdomain-here' ) . '<a href="https://devs.redux.io/premium/multi-media.html" target="_blank">https://devs.redux.io/premium/multi-media.html</a>',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'              => 'opt-multi-media',
				'type'            => 'multi_media',
				'title'           => esc_html__( 'Multi Media Selector', 'your-textdomain-here' ),
				'subtitle'        => esc_html__( 'Alternative media field which allows for multi selections', 'your-textdomain-here' ),
				'desc'            => esc_html__( 'max_file_limit has been set to 5.', 'your-textdomain-here' ),
				'max_file_upload' => 5,
			),
		),
	)
);
