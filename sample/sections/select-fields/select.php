<?php
/**
 * Redux Framework select config.
 * For full documentation, please visit: http://devs.redux.io/
 *
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

Redux::set_section(
	$opt_name,
	array(
		'title'      => esc_html__( 'Select', 'your-textdomain-here' ),
		'id'         => 'select-select',
		'desc'       => esc_html__( 'For full documentation on this field, visit: ', 'your-textdomain-here' ) . '<a href="https://devs.redux.io/core-fields/select.html" target="_blank">https://devs.redux.io/core-fields/select.html</a>',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'opt-select',
				'type'     => 'select',
				'title'    => esc_html__( 'Select Option', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),

				// Must provide key => value pairs for select options.
				'options'  => array(
					'1' => 'Opt 1',
					'2' => 'Opt 2',
					'3' => 'Opt 3',
				),
				'default'  => '2',
			),
			array(
				'id'       => 'opt-select-stylesheet',
				'type'     => 'select',
				'title'    => esc_html__( 'Theme Stylesheet', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'Select your themes alternative color scheme.', 'your-textdomain-here' ),
				'options'  => array(
					'default.css' => 'default.css',
					'color1.css'  => 'color1.css',
				),
				'default'  => 'default.css',
			),
			array(
				'id'       => 'opt-select-optgroup',
				'type'     => 'select',
				'title'    => esc_html__( 'Select Option with optgroup', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),

				// Must provide key => value pairs for select options.
				'options'  => array(
					'Group 1' => array(
						'1' => 'Opt 1',
						'2' => 'Opt 2',
						'3' => 'Opt 3',
					),
					'Group 2' => array(
						'4' => 'Opt 4',
						'5' => 'Opt 5',
						'6' => 'Opt 6',
					),
					'7'       => 'Opt 7',
					'8'       => 'Opt 8',
					'9'       => 'Opt 9',
				),
				'default'  => '2',
			),
			array(
				'id'       => 'opt-multi-select',
				'type'     => 'select',
				'multi'    => true,
				'title'    => esc_html__( 'Multi Select Option', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),

				// Must provide key => value pairs for radio options.
				'options'  => array(
					'1' => 'Opt 1',
					'2' => 'Opt 2',
					'3' => 'Opt 3',
				),
				'default'  => array( '2', '3' ),
			),
			array(
				'id'   => 'opt-info',
				'type' => 'info',
				'desc' => esc_html__( 'You can easily add a variety of data from WordPress.', 'your-textdomain-here' ),
			),
			array(
				'id'       => 'opt-select-categories',
				'type'     => 'select',
				'data'     => 'roles',
				'title'    => esc_html__( 'Categories Select Option', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
			),
			array(
				'id'       => 'opt-select-categories-multi',
				'type'     => 'select',
				'data'     => 'categories',
				'multi'    => true,
				'title'    => esc_html__( 'Categories Multi Select Option', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
			),
			array(
				'id'       => 'opt-select-pages',
				'type'     => 'select',
				'data'     => 'pages',
				'title'    => esc_html__( 'Pages Select Option', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
			),
			array(
				'id'       => 'opt-multi-select-pages',
				'type'     => 'select',
				'data'     => 'pages',
				'multi'    => true,
				'title'    => esc_html__( 'Pages Multi Select Option', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
			),
			array(
				'id'       => 'opt-select-tags',
				'type'     => 'select',
				'data'     => 'tags',
				'title'    => esc_html__( 'Tags Select Option', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
			),
			array(
				'id'       => 'opt-multi-select-tags',
				'type'     => 'select',
				'data'     => 'terms',
				'multi'    => true,
				'title'    => esc_html__( 'Tags Multi Select Option', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
			),
			array(
				'id'       => 'opt-select-terms',
				'type'     => 'select',
				'data'     => 'terms',
				'title'    => esc_html__( 'Terms Select Option', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
			),
			array(
				'id'       => 'opt-multi-select-terms',
				'type'     => 'select',
				'data'     => 'terms',
				'multi'    => true,
				'title'    => esc_html__( 'Terms Multi Select Option', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
			),
			array(
				'id'       => 'opt-select-menus',
				'type'     => 'select',
				'data'     => 'menus',
				'title'    => esc_html__( 'Menus Select Option', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
			),
			array(
				'id'       => 'opt-multi-select-menus',
				'type'     => 'select',
				'data'     => 'menu',
				'multi'    => true,
				'title'    => esc_html__( 'Menus Multi Select Option', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
			),
			array(
				'id'       => 'opt-select-post-type',
				'type'     => 'select',
				'data'     => 'post_type',
				'title'    => esc_html__( 'Post Type Select Option', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
			),
			array(
				'id'       => 'opt-multi-select-post-type',
				'type'     => 'select',
				'data'     => 'post_type',
				'multi'    => true,
				'title'    => esc_html__( 'Post Type Multi Select Option', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
			),
			array(
				'id'       => 'opt-multi-select-sortable',
				'type'     => 'select',
				'data'     => 'post_type',
				'multi'    => true,
				'sortable' => true,
				'title'    => esc_html__( 'Post Type Multi Select Option + Sortable', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'This field also has sortable enabled!', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
			),
			array(
				'id'       => 'opt-select-posts',
				'type'     => 'select',
				'data'     => 'post',
				'title'    => esc_html__( 'Posts Select Option2', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
			),
			array(
				'id'       => 'opt-multi-select-posts',
				'type'     => 'select',
				'data'     => 'post',
				'multi'    => true,
				'title'    => esc_html__( 'Posts Multi Select Option', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
			),
			array(
				'id'       => 'opt-select-roles',
				'type'     => 'select',
				'data'     => 'roles',
				'title'    => esc_html__( 'User Role Select Option', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
			),
			array(
				'id'               => 'opt-select-capabilities',
				'type'             => 'select',
				'data'             => 'capabilities',
				'multi'            => false,
				'ajax'             => true,
				'min_input_length' => 3,
				'title'            => esc_html__( 'Capabilities Select Option w/ AJAX Loading', 'your-textdomain-here' ),
				'subtitle'         => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'             => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
			),
			array(
				'id'       => 'opt-select-elusive',
				'type'     => 'select',
				'data'     => 'elusive-icons',
				'title'    => esc_html__( 'Elusive Icons Select Option', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'Here\'s a list of all the elusive icons by name and icon.', 'your-textdomain-here' ),
			),
			array(
				'id'               => 'opt-select-users',
				'type'             => 'select',
				'data'             => 'users',
				'ajax'             => true,
				'min_input_length' => 3,
				'title'            => esc_html__( 'Users Select Option', 'your-textdomain-here' ),
				'subtitle'         => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'             => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
			),
			array(
				'id'       => 'opt-select-callback',
				'type'     => 'select',
				'data'     => 'callback',
				'args'     => 'redux_select_callback',
				'title'    => esc_html__( 'Select Option using a Callback', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'The items in this selcect were added via a callback function.', 'your-textdomain-here' ),
			),
		),
	)
);

/**
 * Select callback function.
 *
 * @return array
 */
function redux_select_callback(): array {
	$options = array();

	$options[0] = esc_html__( 'Zero', 'your-textdomain-here' );
	$options[1] = esc_html__( 'One', 'your-textdomain-here' );
	$options[2] = esc_html__( 'Two', 'your-textdomain-here' );
	$options[3] = esc_html__( 'Three', 'your-textdomain-here' );

	return $options;
}
