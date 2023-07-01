<?php
/**
 * The template for the main panel container.
 * Override this template by specifying the path where it is stored (templates_path) in your Redux config.
 *
 * @author      Redux Framework
 * @package     ReduxFramework/Templates
 * @version:    4.4.4
 */

$expanded = ( $this->parent->args['open_expanded'] ) ? ' fully-expanded' : ( ! empty( $this->parent->args['class'] ) ? ' ' . esc_attr( $this->parent->args['class'] ) : '' );
$nonce    = wp_create_nonce( 'redux_ajax_nonce' . $this->parent->args['opt_name'] );
$actionn  = ( 'network' === $this->parent->args['database'] && $this->parent->args['network_admin'] && is_network_admin() ? './edit.php?action=redux_' . $this->parent->args['opt_name'] : './options.php' );

// Last tab?
$this->parent->options['last_tab'] = ( isset( $_GET['tab'] ) && ! isset( $this->parent->transients['last_save_mode'] ) ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification

?>
	<div
		class="redux-container<?php echo esc_attr( $expanded ); ?>">

		<form
			method="post"
			action="<?php echo esc_attr( $actionn ); ?>"
			data-nonce="<?php echo esc_attr( $nonce ); ?>"
			enctype="multipart/form-data"
			class="redux-form-wrapper"
			id="redux-form-wrapper"
			data-opt-name="<?php echo esc_attr( $this->parent->args['opt_name'] ); ?>">
			<input
				type="hidden" id="redux-compiler-hook"
				name="<?php echo esc_attr( $this->parent->args['opt_name'] ); ?>[compiler]"
				value=""/>
			<input
				type="hidden" id="currentSection"
				name="<?php echo esc_attr( $this->parent->args['opt_name'] ); ?>[redux-section]"
				value=""/>
			<?php if ( ! empty( $this->parent->options_class->no_panel ) ) { ?>
				<input
					type="hidden"
					name="<?php echo esc_attr( $this->parent->args['opt_name'] ); ?>[redux-no_panel]"
					value="<?php echo esc_attr( implode( '|', $this->parent->options_class->no_panel ) ); ?>"/>
			<?php } ?>
			<?php $this->init_settings_fields(); // Must run or the page won't redirect properly. ?>
			<input
				type="hidden" id="last_tab"
				name="<?php echo esc_attr( $this->parent->args['opt_name'] ); ?>[last_tab]"
				value="<?php echo esc_attr( $this->parent->options['last_tab'] ); ?>"/>
			<?php $this->get_template( 'content.tpl.php' ); ?>
		</form>
	</div>

<?php if ( isset( $this->parent->args['footer_text'] ) ) { ?>
	<div id="redux-sub-footer"><?php echo wp_kses_post( $this->parent->args['footer_text'] ); ?></div>
<?php } ?>
