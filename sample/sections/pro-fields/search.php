<?php
/**
 * Redux Pro Search Sample config.
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
		'title'      => esc_html__( 'Live Search', 'your-textdomain-here' ),
		// phpcs:ignore
		// 'desc'       => esc_html__( 'For full documentation on this field, visit: ', 'your-textdomain-here' ) . '<a href="https://devs.redux.io/extensions/live-search.html" target="_blank">https://devs.redux.io/extensions/live-search.html</a>',
		'heading'    => esc_html__( 'This extension is a drop-in utility. Try the search box to the top right of every panel or metabox section. It will dynamically filter out the visible fields to match your search.', 'your-textdomain-here' ),
		'subsection' => true,
		'customizer' => false,
	)
);
