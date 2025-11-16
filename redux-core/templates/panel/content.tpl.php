<?php
/**
 * The template for the main content of the panel.
 * Override this template by specifying the path where it is stored (templates_path) in your Redux config.
 *
 * @author      Redux Framework
 * @package     ReduxFramework/Templates
 * @version:    4.0.0
 */

?>
<!-- Header Block -->
<?php $this->get_template( 'header.tpl.php' ); ?>

<!-- Intro Text -->
<?php if ( isset( $this->parent->args['intro_text'] ) ) { ?>
	<div id="redux-intro-text"><?php echo wp_kses_post( $this->parent->args['intro_text'] ); ?></div>
<?php } ?>

<?php $this->get_template( 'menu-container.tpl.php' ); ?>

<div class="redux-main">
	<!-- Stickybar -->
	<?php $this->get_template( 'header-stickybar.tpl.php' ); ?>
	<div id="redux_ajax_overlay">&nbsp;</div>
	<?php foreach ( $this->parent->sections as $redux_key => $redux_section ) { ?>
		<?php if ( isset( $redux_section['customizer_only'] ) && true === $redux_section['customizer_only'] ) { ?>
			<?php continue; ?>
		<?php } // phpcs:ignore Squiz.PHP.NonExecutableCode.Unreachable ?>

		<?php $redux_section['class'] = isset( $redux_section['class'] ) ? ' ' . $redux_section['class'] : ''; ?>

		<?php $redux_disabled = ''; ?>
		<?php if ( isset( $redux_section['disabled'] ) && $redux_section['disabled'] ) { ?>
			<?php $redux_disabled = 'disabled '; ?>
		<?php } ?>

		<div
			id="<?php echo esc_attr( $redux_key ); ?>_section_group"
			class="redux-group-tab <?php echo esc_attr( $redux_disabled ); ?><?php echo esc_attr( $redux_section['class'] ); ?>"
			data-rel="<?php echo esc_attr( $redux_key ); ?>">

			<?php $redux_display = true; ?>

			<?php if ( isset( $_GET['page'] ) && $this->parent->args['page_slug'] === $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification ?>
				<?php if ( isset( $redux_section['panel'] ) && false === $redux_section['panel'] ) { ?>
					<?php $redux_display = false; ?>
				<?php } ?>
			<?php } ?>

			<?php
			if ( $redux_display ) {
				/**
				 * Action 'redux/page/{opt_name}/section/before'
				 *
				 * @param object $this ReduxFramework
				 */

				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				do_action( "redux/page/{$this->parent->args['opt_name']}/section/before", $redux_section );

				$this->output_section( $redux_key );

				/**
				 * Action 'redux/page/{opt_name}/section/after'
				 *
				 * @param object $this ReduxFramework
				 */

				// phpcs:ignore WordPress.NamingConventions.ValidHookName
				do_action( "redux/page/{$this->parent->args['opt_name']}/section/after", $redux_section );
			}
			?>
		</div> <!-- section group -->
	<?php } ?>

	<?php
	/**
	 * Action 'redux/page/{opt_name}/sections/after'
	 *
	 * @param object $this ReduxFramework
	 */

	// phpcs:ignore WordPress.NamingConventions.ValidHookName
	do_action( "redux/page/{$this->parent->args['opt_name']}/sections/after", $this );
	?>
	<div class="clear"></div>
	<!-- Footer Block -->
	<?php $this->get_template( 'footer.tpl.php' ); ?>
	<div id="redux-sticky-padder" style="display: none;">&nbsp;</div>
	</div> <!-- redux main -->
<div class="clear"></div>
