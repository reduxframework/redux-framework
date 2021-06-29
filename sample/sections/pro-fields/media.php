<?php
/**
 * Redux Pro Media Sample config.
 *
 * For full documentation, please visit: http:https://devs.redux.io/
 *
 * @package Redux Pro
 */

defined( 'ABSPATH' ) || exit;

Redux::set_section(
	$opt_name,
	array(
		'title'      => esc_html__( 'Media', 'your-textdomain-here' ),
		'id'         => 'pro-media-media',
		'desc'       => esc_html__( 'For full documentation on this field, visit: ', 'your-textdomain-here' ) . '<a href="https://devs.redux.io/core-fields/media.html" target="_blank">https://devs.redux.io/core-fields/media.html</a>',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'           => 'pro-opt-media',
				'type'         => 'media',
				'url'          => true,
				'title'        => esc_html__( 'Media w/ URL', 'your-textdomain-here' ),
				'compiler'     => 'true',
				'desc'         => esc_html__( 'Basic media uploader with disabled URL input field.', 'your-textdomain-here' ),
				'subtitle'     => esc_html__( 'Upload any media using the WordPress native uploader', 'your-textdomain-here' ),
				'default'      => array(
					'url'    => 'https://s.wordpress.org/style/images/codeispoetry.png',
					'filter' => array(
						'grayscale' => array(
							'checked' => true,
							'value'   => 50,
						),
					),
				),
				'preview_size' => 'full',
				'filter'       => array(
					'grayscale'  => true,
					'blur'       => true,
					'sepia'      => true,
					'saturate'   => true,
					'opacity'    => true,
					'brightness' => true,
					'contrast'   => true,
					'hue-rotate' => true,
					'invert'     => true,
				),
				'output'       => array( '.header-image img' ),
			),
			array(
				'id'       => 'pro-media-no-url',
				'type'     => 'media',
				'title'    => esc_html__( 'Media w/o URL', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This represents the minimalistic view. It does not have the preview box or the display URL in an input box. ', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'Upload any media using the WordPress native uploader', 'your-textdomain-here' ),
				'url'      => false,
				'filter'   => array(
					'grayscale' => true,
					'blur'      => true,
				),
				'preview'  => true,
			),
			array(
				'id'       => 'pro-media-no-preview',
				'type'     => 'media',
				'preview'  => false,
				'title'    => esc_html__( 'Media No Preview', 'your-textdomain-here' ),
				'desc'     => esc_html__( 'This represents the minimalistic view. It does not have the preview box or the display URL in an input box. ', 'your-textdomain-here' ),
				'subtitle' => esc_html__( 'Upload any media using the WordPress native uploader', 'your-textdomain-here' ),
				'hint'     => array(
					'title'   => esc_html__( 'Test', 'your-textdomain-here' ),
					'content' => 'This is a <b>hint</b> tool-tip for the webFonts field.<br/><br/>Add any HTML based text you like here.',
				),
			),
			array(
				'id'         => 'pro-opt-random-upload',
				'type'       => 'media',
				'title'      => esc_html__( 'Upload Anything - Disabled Mode', 'your-textdomain-here' ),
				'full_width' => true,
				'mode'       => false,
				// Can be set to false to allow any media type, or can also be set to any mime type.
				'desc'       => esc_html__( 'Basic media uploader with disabled URL input field.', 'your-textdomain-here' ),
				'subtitle'   => esc_html__( 'Upload any media using the WordPress native uploader', 'your-textdomain-here' ),
			),
		),
	)
);
