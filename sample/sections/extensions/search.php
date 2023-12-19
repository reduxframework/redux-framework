<?php
/**
 * Redux Search Sample config.
 *
 * For full documentation, please visit: http:https://devs.redux.io/
 *
 * @package Redux
 */

defined( 'ABSPATH' ) || exit;

// --> Below this line not needed. This is just for demonstration purposes.
Redux::set_section(
	$opt_name,
	array(
		'title'      => esc_html__( 'Live Search', 'your-textdomain-here' ),
		'desc'       => esc_html__( 'For full documentation on this field, visit: ', 'your-textdomain-here' ) . '<a href="https://devs.redux.io/core-extensions/live-search.html" target="_blank">https://devs.redux.io/extensions/live-search.html</a>',
		'heading'    => esc_html__( 'Try the search box at the top right of every panel or metabox section. It will dynamically filter out the visible fields to match your search.', 'your-textdomain-here' ),
		'customizer' => false,
		'subsection' => true,
	)
);
