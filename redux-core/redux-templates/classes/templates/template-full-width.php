<?php
/**
 * ReduxTemplates - Full Width / Stretched
 *
 * @since   4.0.0
 * @package redux-framework
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
get_header();
// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo '<style type="text/css" id="redux-template-overrides">' . ReduxTemplates\Template_Overrides::get_overrides() . '</style>';

while ( have_posts() ) :
	the_post();
	the_content();
	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;
endwhile; // End of the loop.

get_footer();
