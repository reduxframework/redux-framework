<?php
/**
 * The template for the menu container of the panel.
 * Override this template by specifying the path where it is stored (templates_path) in your Redux config.
 *
 * @author     Redux Framework
 * @package    ReduxFramework/Templates
 * @version:    4.0.0
 */

?>
<div class="redux-sidebar">
	<ul class="redux-group-menu">
		<?php
		foreach ( $this->parent->sections as $k => $section ) {
			$the_title = isset( $section['title'] ) ? $section['title'] : '';
			$skip_sec  = false;
			foreach ( $this->parent->options_class->hidden_perm_sections as $num => $section_title ) {
				if ( $section_title === $the_title ) {
					$skip_sec = true;
				}
			}

			if ( isset( $section['customizer_only'] ) && true === $section['customizer_only'] ) {
				continue;
			}

			if ( false === $skip_sec ) {
				echo( $this->parent->section_menu( $k, $section ) ); // phpcs:ignore WordPress.Security.EscapeOutput
				$skip_sec = false;
			}
		}

		/**
		 * Action 'redux/page/{opt_name}/menu/after'
		 *
		 * @param object $this ReduxFramework
		 */
		do_action( "redux/page/{$this->parent->args['opt_name']}/menu/after", $this ); // phpcs:ignore WordPress.NamingConventions.ValidHookName
		?>
	</ul>
</div>
