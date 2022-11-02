<?php
/**
 * Redux Pro Widget Areas Sample config.
 *
 * For full documentation, please visit: http:https://devs.redux.io/
 *
 * @package Redux Pro
 */

defined( 'ABSPATH' ) || exit;

// --> Below this line not needed. This is just for demonstration purposes.
Redux::set_section(
	$opt_name,
	array(
		'title'      => esc_html__( 'Widget Areas', 'your-textdomain-here' ),
		// phpcs:ignore
		// 'desc'       => esc_html__( 'For full documentation on this field, visit: ', 'your-textdomain-here' ) . '<a href="https://devs.redux.io/extensions/widget-areas.html" target="_blank">https://devs.redux.io/extensions/widget-areas.html</a>',
		'subsection' => true,
		'fields'     => array(
			array(
				'id'       => 'widget_areas',
				'type'     => 'info',
				'style'    => 'info',
				'notice'   => true,
				'title'    => esc_html__( 'Widget Areas is Already Running!', 'your-textdomain-here' ),

				// translators: %1$s: Widget Admin URL.
				'subtitle' => sprintf( esc_html__( 'To see it in action, head over to your %1$s', 'your-textdomain-here' ), '<a href="' . admin_url( 'widgets.php' ) . '">' . esc_html__( 'Widgets page', 'your-textdomain-here' ) . '</a> (Applicable for Classic Widgets only).' ),
			),
		),
	)
);
