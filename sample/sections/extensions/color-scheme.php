<?php
/**
 * Redux Color Scheme Sample config.
 *
 * For full documentation, please visit: http:https://devs.redux.io/
 *
 * @package Redux
 */

defined( 'ABSPATH' ) || exit;

Redux::set_section(
	$opt_name,
	array(
		'title'      => esc_html__( 'Color Schemes', 'your-textdomain-here' ),
		'desc'       => esc_html__( 'For full documentation on this field, visit: ', 'your-textdomain-here' ) . '<a href="https://devs.redux.io/premium/color-schemes.html" target="_blank">https://devs.redux.io/premium/color-schemes.html</a>',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'opt-color-scheme',
				'type'     => 'color_scheme',
				'title'    => esc_html__( 'Color Schemes', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'Save and load color schemes', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'If you\'re using the theme 2023, you will be able to see many changes on the current site.', 'your-textdomain-here' ),
				'output'   => true,
				'compiler' => true,
				'simple'   => false,
				'options'  => array(
					'show_input'             => true,
					'show_initial'           => true,
					'show_alpha'             => true,
					'show_palette'           => true,
					'show_palette_only'      => false,
					'show_selection_palette' => true,
					'max_palette_size'       => 10,
					'allow_empty'            => true,
					'clickout_fires_change'  => false,
					'choose_text'            => 'Choose',
					'cancel_text'            => 'Cancel',
					'show_buttons'           => true,
					'use_extended_classes'   => true,
					'palette'                => null,  // show default.
				),
				'groups'   => array(
					esc_html__( 'Header', 'your-textdomain-here' ) => array(
						'desc'           => esc_html__( 'Set header and nav colors here. (Group open by default)', 'your-textdomain-here' ),
						'hidden'         => false,
						'accordion_open' => true,
					),
					esc_html__( 'Body', 'your-textdomain-here' ) => esc_html__( 'Set body and content colors here.', 'your-textdomain-here' ),
					esc_html__( 'Widget', 'your-textdomain-here' ) => '',
					'' => esc_html__( 'These colors are not assigned to any group.', 'your-textdomain-here' ),
				),
				'default'  => array(
					array(
						'id'        => 'site-header',
						'title'     => 'site header',
						'color'     => '#980000',
						'alpha'     => 1,
						'selector'  => array(
							'background' => '.site-header-main,header',
							'color'      => '.tester',
						),
						'mode'      => 'background-color',
						'important' => true,
						'group'     => esc_html__( 'Header', 'your-textdomain-here' ),
					),
					array(
						'id'        => 'site-header-border',
						'title'     => 'site header border',
						'color'     => '#ff0000',
						'alpha'     => 1,
						'selector'  => '.site-header,header',
						'mode'      => 'border-color',
						'important' => true,
						'group'     => esc_html__( 'Header', 'your-textdomain-here' ),
					),
					array(
						'id'        => 'home-link',     // ID.
						'title'     => 'home link',     // Display text.
						'color'     => '#fdfdfd',       // Default colour.
						'alpha'     => 1,               // Default alpha.
						'selector'  => '.home-link,.wp-block-site-title a',    // CSS selector.
						'mode'      => 'color',         // CSS mode.
						'important' => true,            // CSS important.
						'group'     => esc_html__( 'Header', 'your-textdomain-here' ),
					),
					array(
						'id'        => 'site-description',
						'title'     => 'site description',
						'color'     => '#ededed',
						'alpha'     => 1,
						'selector'  => 'h2.site-description,.wp-block-site-tagline',
						'mode'      => 'color',
						'important' => true,
						'group'     => esc_html__( 'Header', 'your-textdomain-here' ),
					),
					array(
						'id'       => 'navbar',
						'title'    => 'navbar',
						'color'    => '#e06666',
						'alpha'    => 1,
						'selector' => '.navbar,.wp-block-navigation',
						'mode'     => 'background-color',
						'group'    => esc_html__( 'Header', 'your-textdomain-here' ),

					),
					array(
						'id'       => 'body-text',
						'title'    => 'body text',
						'color'    => '#000000',
						'alpha'    => 1,
						'selector' => 'body p',
						'mode'     => 'color',
						'group'    => esc_html__( 'Body', 'your-textdomain-here' ),
					),
					array(
						'id'       => 'site-content',
						'title'    => 'site content',
						'color'    => '#a4c2f4',
						'alpha'    => 1,
						'selector' => '.site-content',
						'mode'     => 'background-color',
						'group'    => esc_html__( 'Body', 'your-textdomain-here' ),
					),
					array(
						'id'       => 'entry-content',
						'title'    => 'entry content',
						'color'    => '#93c47d',
						'alpha'    => 1,
						'selector' => '.entry-content',
						'mode'     => 'background-color',
						'group'    => esc_html__( 'Body', 'your-textdomain-here' ),
					),
					array(
						'id'       => 'entry-title',
						'title'    => 'entry title',
						'color'    => '#000000',
						'alpha'    => 1,
						'selector' => '.entry-title a',
						'mode'     => 'color',
						'group'    => esc_html__( 'Body', 'your-textdomain-here' ),
					),
					array(
						'id'       => 'entry-title-hover',
						'title'    => 'entry title hover',
						'color'    => '#ffffff',
						'alpha'    => 1,
						'selector' => '.entry-title a:hover',
						'mode'     => 'color',
						'group'    => esc_html__( 'Body', 'your-textdomain-here' ),
					),
					array(
						'id'       => 'entry-meta',
						'title'    => 'entry meta',
						'color'    => '#0b5394',
						'alpha'    => 1,
						'selector' => '.entry-meta a',
						'mode'     => 'color',
						'group'    => esc_html__( 'Body', 'your-textdomain-here' ),
					),
					array(
						'id'       => 'widget-container',
						'title'    => 'widget container',
						'color'    => '#f1c232',
						'alpha'    => .5,
						'selector' => '.widget',
						'mode'     => 'background-color',
						'group'    => esc_html__( 'Widget', 'your-textdomain-here' ),
					),
					array(
						'id'        => 'widget-title',
						'title'     => 'widget title',
						'color'     => '#741b47',
						'alpha'     => 1,
						'selector'  => '.widget-title',
						'mode'      => 'color',
						'important' => true,
						'group'     => esc_html__( 'Widget', 'your-textdomain-here' ),
					),
					array(
						'id'        => 'widget-text',
						'title'     => 'widget text',
						'color'     => '#fdfdfd',
						'alpha'     => 1,
						'selector'  => '.widget a',
						'mode'      => 'color',
						'important' => true,
						'group'     => esc_html__( 'Widget', 'your-textdomain-here' ),
					),
					array(
						'id'        => 'sidebar-container',
						'title'     => 'sidebar container',
						'color'     => '#d5a6bd',
						'alpha'     => 1,
						'selector'  => '.sidebar-container',
						'mode'      => 'background-color',
						'important' => true,
						'group'     => '',
					),
					array(
						'id'       => 'site-footer',
						'title'    => 'site footer',
						'color'    => '#ededed',
						'alpha'    => 1,
						'selector' => '.site-footer,footer',
						'mode'     => 'background-color',
						'group'    => '',
					),
					array(
						'id'       => 'site-footer-text',
						'title'    => 'site footer text',
						'color'    => '#000000',
						'alpha'    => 1,
						'selector' => '.site-footer a, footer a',
						'group'    => '',
					),
				),
			),
		),
	)
);
