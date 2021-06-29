<?php
/**
 * Redux Framework image select config.
 * For full documentation, please visit: http://devs.redux.io/
 *
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

Redux::set_section(
	$opt_name,
	array(
		'title'      => esc_html__( 'Image Select', 'your-textdomain-here' ),
		'id'         => 'select-image_select',
		'desc'       => esc_html__( 'For full documentation on this field, visit: ', 'your-textdomain-here' ) . '<a href="https://devs.redux.io/core-fields/image-select.html" target="_blank">https://devs.redux.io/core-fields/image-select.html</a>',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'opt-image-select-layout',
				'type'     => 'image_select',
				'title'    => esc_html__( 'Images Option for Layout', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This uses some of the built in images, you can use them for layout options.', 'your-textdomain-here' ),

				// Must provide key => value(array:title|img) pairs for radio options.
				'options'  => array(
					'1' => array(
						'alt' => '1 Column',
						'img' => Redux_Core::$url . 'assets/img/1col.png',
					),
					'2' => array(
						'alt' => '2 Column Left',
						'img' => Redux_Core::$url . 'assets/img/2cl.png',
					),
					'3' => array(
						'alt' => '2 Column Right',
						'img' => Redux_Core::$url . 'assets/img/2cr.png',
					),
					'4' => array(
						'alt' => '3 Column Middle',
						'img' => Redux_Core::$url . 'assets/img/3cm.png',
					),
					'5' => array(
						'alt' => '3 Column Left',
						'img' => Redux_Core::$url . 'assets/img/3cl.png',
					),
					'6' => array(
						'alt' => '3 Column Right',
						'img' => Redux_Core::$url . 'assets/img/3cr.png',
					),
				),
				'default'  => '2',
			),
			array(
				'id'       => 'opt-patterns',
				'type'     => 'image_select',
				'tiles'    => true,
				'title'    => esc_html__( 'Images Option (with tiles => true)', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'Select a background pattern.', 'your-textdomain-here' ),
				'default'  => 0,
				'options'  => $sample_patterns,
			),
			array(
				'id'       => 'opt-image-select',
				'type'     => 'image_select',
				'title'    => esc_html__( 'Images Option', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'No validation can be done on this field type', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),

				// Must provide key => value(array:title|img) pairs for radio options.
				'options'  => array(
					'1' => array(
						'title' => 'Opt 1',
						'img'   => admin_url() . 'images/align-none.png',
					),
					'2' => array(
						'title' => 'Opt 2',
						'img'   => admin_url() . 'images/align-left.png',
					),
					'3' => array(
						'title' => 'Opt 3',
						'img'   => admin_url() . 'images/align-center.png',
					),
					'4' => array(
						'title' => 'Opt 4',
						'img'   => admin_url() . 'images/align-right.png',
					),
				),
				'default'  => '2',
			),
			array(
				'id'         => 'opt-presets',
				'type'       => 'image_select',
				'presets'    => true,
				'full_width' => true,
				'title'      => esc_html__( 'Preset', 'your-textdomain-here' ),
				'subtitle'   => esc_html__( 'This allows you to set a json string or array to override multiple preferences in your theme.', 'your-textdomain-here' ),
				'default'    => 0,
				'desc'       => esc_html__( 'This allows you to set a json string or array to override multiple preferences in your theme.', 'your-textdomain-here' ),
				'options'    => array(
					'1' => array(
						'alt'     => 'Preset 1',
						'img'     => Redux_Core::$url . '../sample/presets/preset1.png',
						'presets' => array(
							'switch-on'     => 1,
							'switch-off'    => 1,
							'switch-parent' => 1,
						),
					),
					'2' => array(
						'alt'     => 'Preset 2',
						'img'     => Redux_Core::$url . '../sample/presets/preset2.png',
						'presets' => '{"opt-slider-label":"1", "opt-slider-text":"10"}',
					),
				),
			),
		),
	)
);
