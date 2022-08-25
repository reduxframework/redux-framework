<?php
/**
 * ReduxTemplates - Full Width / Contained
 *
 * @since   4.0.0
 * @package redux-framework
 */

defined( 'ABSPATH' ) || exit;

get_header();

// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
echo '<style id="redux-template-overrides">' . ReduxTemplates\Template_Overrides::get_overrides() . '</style>';

while ( have_posts() ) :
	the_post();
	the_content();

	// If comments are open, or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;
endwhile; // End of the loop.

get_footer();
