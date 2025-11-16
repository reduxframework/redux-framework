<?php
/**
 * The template for the menu container of the panel.
 * Override this template by specifying the path where it is stored (templates_path) in your Redux config.
 *
 * @author     Redux Framework
 * @package    ReduxFramework/Templates
 * @version:    4.3.11
 */

?>
<div class="redux-sidebar">
	<ul class="redux-group-menu">
		<?php
		foreach ( $this->parent->sections as $redux_key => $redux_section ) {
			$redux_the_title = $redux_section['title'] ?? '';
			$redux_skip_sec  = false;
			foreach ( $this->parent->options_class->hidden_perm_sections as $redux_num => $redux_section_title ) {
				if ( $redux_section_title === $redux_the_title ) {
					$redux_skip_sec = true;
				}
			}

			if ( isset( $redux_section['customizer_only'] ) && true === $redux_section['customizer_only'] ) {
				continue;
			}

			if ( false === $redux_skip_sec ) {
				echo( $this->parent->render_class->section_menu( $redux_key, $redux_section ) ); // phpcs:ignore WordPress.Security.EscapeOutput
				$redux_skip_sec = false;
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
