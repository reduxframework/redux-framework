<?php
/**
 * Redux Pro Taxonomy Meta config.
 * For full documentation, please visit: http://devs.redux.io/
 *
 * @package Redux Pro
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Taxonomny' ) ) {
	return;
}

// Change the priority the Redux_Taxonomy boxes appear.
Redux_Taxonomy::set_args(
	$opt_name,
	array(
		'taxonomy_priority' => 50,
	)
);

Redux_Taxonomy::set_term(
	$opt_name,
	array(
		'id'             => 'demo-taxonomy',
		'title'          => __( 'Cool Options', 'your-textdomain-here' ),

		// Slug for every taxonomy you want.
		'taxonomy_types' => array( 'category', 'post_tag' ),

		'add_visibility' => true,

		// Can bet set on term, section, or field level. Denotes what fields to be displayed on the add {TERM} pages.
		'sections'       => array(
			array(
				'title'  => __( 'Home Settings', 'your-textdomain-here' ),
				'icon'   => 'el-icon-home',
				'fields' => array(
					array(
						'id'             => 'tax-text',
						'type'           => 'text',
						'add_visibility' => true,
						'title'          => __( 'Input 1', 'your-textdomain-here' ),
					),

					array(
						'id'             => 'tax-text-2',
						'type'           => 'text',
						'add_visibility' => true,
						'required'       => array( 'dovy1', '=', '222' ),
						'title'          => __( 'Input 2', 'your-textdomain-here' ),
					),
					array(
						'id'             => 'opt-button-set',
						'type'           => 'button_set',
						'title'          => __( 'Button Set Option', 'your-textdomain-here' ),
						'subtitle'       => __( 'No validation can be done on this field type', 'your-textdomain-here' ),
						'desc'           => __( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
						'add_visibility' => true,
						'options'        => array(
							'1' => 'Opt 1',
							'2' => 'Opt 2',
							'3' => 'Opt 3',
						),
						'default'        => '2',
					),
					array(
						'id'             => 'dovy3',
						'type'           => 'text',
						'add_visibility' => true,
						'title'          => __( 'Dovy Input3', 'your-textdomain-here' ),
					),
					array(
						'id'       => 'webFonts',
						'type'     => 'media',
						'title'    => __( 'Web Fonts', 'your-textdomain-here' ),
						'compiler' => 'true',
						'mode'     => false,
						// Can be set to false to allow any media type, or can also be set to any mime type.
						'desc'     => __( 'Basic media uploader with disabled URL input field.', 'your-textdomain-here' ),
						'subtitle' => __( 'Upload any media using the WordPress native uploader', 'your-textdomain-here' ),
					),
					array(
						'id'       => 'section-media-start',
						'type'     => 'section',
						'title'    => __( 'Media Options', 'your-textdomain-here' ),
						'subtitle' => __( 'With the "section" field you can create indent option sections.', 'your-textdomain-here' ),
						'indent'   => true,
					),
					array(
						'id'       => 'mediaurl',
						'type'     => 'media',
						'url'      => true,
						'title'    => __( 'Media w/ URL', 'your-textdomain-here' ),
						'compiler' => 'true',
						'desc'     => __( 'Basic media uploader with disabled URL input field.', 'your-textdomain-here' ),
						'subtitle' => __( 'Upload any media using the WordPress native uploader', 'your-textdomain-here' ),
						'default'  => array( 'url' => 'http://s.wordpress.org/style/images/codeispoetry.png' ),
					),
					array(
						'id'     => 'section-media-end',
						'type'   => 'section',
						'indent' => false,
					),
					array(
						'id'       => 'media-nourl',
						'type'     => 'media',
						'title'    => __( 'Media w/o URL', 'your-textdomain-here' ),
						'desc'     => __( 'This represents the minimalistic view. It does not have the preview box or the display URL in an input box. ', 'your-textdomain-here' ),
						'subtitle' => __( 'Upload any media using the WordPress native uploader', 'your-textdomain-here' ),
					),
					array(
						'id'       => 'media-nopreview',
						'type'     => 'media',
						'preview'  => false,
						'title'    => __( 'Media No Preview', 'your-textdomain-here' ),
						'desc'     => __( 'This represents the minimalistic view. It does not have the preview box or the display URL in an input box. ', 'your-textdomain-here' ),
						'subtitle' => __( 'Upload any media using the WordPress native uploader', 'your-textdomain-here' ),
					),
					array(
						'id'       => 'gallery',
						'type'     => 'gallery',
						'title'    => __( 'Add/Edit Gallery', 'your-textdomain-here' ),
						'subtitle' => __( 'Create a new Gallery by selecting existing or uploading new images using the WordPress native uploader', 'your-textdomain-here' ),
						'desc'     => __( 'This is the description field, again good for additional info.', 'your-textdomain-here' ),
					),
					array(
						'id'      => 'slider1bDOVY23',
						'type'    => 'slider',
						'title'   => __( 'JQuery UI Slider Example 1', 'your-textdomain-here' ),
						'desc'    => __( 'JQuery UI slider description. Min: 1, max: 500, step: 3, default value: 45', 'your-textdomain-here' ),
						'default' => '46',
						'min'     => '1',
						'step'    => '3',
						'max'     => '500',
					),
					array(
						'id'      => 'slider2bc',
						'type'    => 'slider',
						'title'   => __( 'JQuery UI Slider Example 2 w/ Steps (5)', 'your-textdomain-here' ),
						'desc'    => __( 'JQuery UI slider description. Min: 0, max: 300, step: 5, default value: 75', 'your-textdomain-here' ),
						'default' => '0',
						'min'     => '0',
						'step'    => '5',
						'max'     => '300',
					),
					array(
						'id'      => 'spinner1bcd',
						'type'    => 'spinner',
						'title'   => __( 'JQuery UI Spinner Example 1', 'your-textdomain-here' ),
						'desc'    => __( 'JQuery UI spinner description. Min:20, max: 100, step:20, default value: 40', 'your-textdomain-here' ),
						'default' => '40',
						'min'     => '20',
						'step'    => '20',
						'max'     => '100',
					),
					array(
						'id'       => 'switch-parent',
						'type'     => 'switch',
						'title'    => __( 'Switch - Nested Children, Enable to show', 'your-textdomain-here' ),
						'subtitle' => __( 'Look, it\'s on! Also hidden child elements!', 'your-textdomain-here' ),
						'default'  => 0,
						'on'       => 'Enabled',
						'off'      => 'Disabled',
					),
					array(
						'id'       => 'switch-child1',
						'type'     => 'switch',
						'required' => array( 'switch-parent', '=', '1' ),
						'title'    => __( 'Switch - This and the next switch required for patterns to show', 'your-textdomain-here' ),
						'subtitle' => __( 'Also called a "fold" parent.', 'your-textdomain-here' ),
						'desc'     => __( 'Items set with a fold to this ID will hide unless this is set to the appropriate value.', 'your-textdomain-here' ),
						'default'  => false,
					),
				),
			),

			array(
				'title'  => __( 'Home Layout', 'your-textdomain-here' ),
				'desc'   => __( 'Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'your-textdomain-here' ),
				'icon'   => 'el-icon-home',
				'fields' => array(
					array(
						'id'             => 'homepage_blocks',
						'type'           => 'sorter',
						'title'          => 'Homepage Layout Manager',
						'desc'           => 'Organize how you want the layout to appear on the homepage',
						'compiler'       => 'true',
						'add_visibility' => true,

						'options'        => array(
							'enabled'  => array(
								'placebo'    => 'placebo', // REQUIRED!
								'highlights' => 'Highlights',
								'slider'     => 'Slider',
								'staticpage' => 'Static Page',
								'services'   => 'Services',
							),
							'disabled' => array(
								'placebo' => 'placebo', // REQUIRED!
							),
						),
					),

					array(
						'id'       => 'presets',
						'type'     => 'image_select',
						'presets'  => true,
						'title'    => __( 'Preset', 'your-textdomain-here' ),
						'subtitle' => __( 'This allows you to set a json string or array to override multiple preferences in your theme.', 'your-textdomain-here' ),
						'default'  => 0,
						'desc'     => __( 'This allows you to set a json string or array to override multiple preferences in your theme.', 'your-textdomain-here' ),
						'options'  => array(
							'1' => array(
								'alt'     => 'Preset 1',
								'img'     => ReduxFramework::$_url . '../sample/presets/preset1.png',
								'presets' => array(
									'switch-on'     => 1,
									'switch-off'    => 1,
									'switch-custom' => 1,
								),
							),
							'2' => array(
								'alt'     => 'Preset 2',
								'img'     => ReduxFramework::$_url . '../sample/presets/preset2.png',
								'presets' => '{"slider1":"1", "slider2":"0", "switch-on":"0"}',
							),
						),
					),
				),
			),
		),
	)
);
