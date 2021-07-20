<?php
/**
 * Redux Pro Color Palette Sample config.
 *
 * For full documentation, please visit: http:https://devs.redux.io/
 *
 * @package Redux Pro
 */

defined( 'ABSPATH' ) || exit;

Redux::set_section(
	$opt_name,
	array(
		'title'      => esc_html__( 'Color Palette', 'your-textdomain-here' ),
		'desc'       => esc_html__( 'For full documentation on this field, visit: ', 'your-textdomain-here' ) . '<a href="https://devs.redux.io/core-fields/palette-color.html" target="_blank">https://devs.redux.io/core-fields/palette-color.html</a>',
		'id'         => 'color-palette',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'opt-color-palette-grey',
				'type'     => 'color_palette',
				'title'    => esc_html__( 'Color Palette Control', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'User defined colors with round selectors.', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'Set the Widget Title color here.', 'your-textdomain-here' ),
				'default'  => '#888888',
				'options'  => array(
					'colors' => array(
						'#000000',
						'#222222',
						'#444444',
						'#666666',
						'#888888',
						'#aaaaaa',
						'#cccccc',
						'#eeeeee',
						'#ffffff',
					),
					'style'  => 'round',
				),
				'output'   => array(
					'color'     => '.widget-title',
					'important' => true,
				),
			),
			array(
				'id'       => 'opt-color-palette-mui-all',
				'type'     => 'color_palette',
				'title'    => esc_html__( 'Color Palette Control', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'All Material Dedign Colors.', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
				'default'  => '#F44336',
				'options'  => array(
					'colors' => Redux_Helpers::get_material_design_colors( 'all' ),
					'size'   => 17,
				),
			),
			array(
				'id'       => 'opt-color-palette-mui-primary',
				'type'     => 'color_palette',
				'title'    => esc_html__( 'Color Palette Control', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'Primary Material Dedign Colors.', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
				'default'  => '#000000',
				'options'  => array(
					'colors'     => Redux_Helpers::get_material_design_colors(),
					'size'       => 25,
					'box-shadow' => true,
					'margin'     => true,
				),
			),
			array(
				'id'       => 'opt-color-palette-mui-red',
				'type'     => 'color_palette',
				'title'    => esc_html__( 'Color Palette Control', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'Red Material Dedign Colors.', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
				'default'  => '#FF1744',
				'options'  => array(
					'colors' => Redux_Helpers::get_material_design_colors( 'red' ),
					'size'   => 25,
				),
			),
			array(
				'id'       => 'opt-color-palette-mui-a100',
				'type'     => 'color_palette',
				'title'    => esc_html__( 'Color Palette Control', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'A100 Material Dedign Colors.', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
				'default'  => '#FF80AB',
				'options'  => array(
					'colors' => Redux_Helpers::get_material_design_colors( 'A100' ),
					'size'   => 60,
					'style'  => 'round',
				),
			),
		),
	)
);
