<?php
	/**
	 * The template for the panel footer area.
	 *
	 * Override this template by specifying the path where it is stored (templates_path) in your Redux config.
	 *
	 * @author 		Redux Framework
	 * @package 	ReduxFramework/Templates
	 * @version     3.4.3
	 */
?>
<div id="redux-sticky-padder" style="display: none;">&nbsp;</div>
<div id="redux-footer-sticky">
	<div id="redux-footer">

		<?php if ( isset( $this->parent->args['share_icons'] ) ) : ?>
			<div id="redux-share">
				<?php foreach ( $this->parent->args['share_icons'] as $link ) : ?>
					<?php
					// SHIM, use URL now
					if ( isset( $link['link'] ) && ! empty( $link['link'] ) ) {
						$link['url'] = $link['link'];
						unset( $link['link'] );
					}
					?>

					<a href="<?php echo $link['url'] ?>" title="<?php echo $link['title']; ?>" target="_blank">

						<?php if ( isset( $link['icon'] ) && ! empty( $link['icon'] ) ) : ?>
							<i class="<?php echo $link['icon'] ?>"></i>
						<?php else : ?>
							<img src="<?php echo $link['img'] ?>"/>
						<?php endif; ?>

					</a>
				<?php endforeach; ?>

			</div>
		<?php endif; ?>

		<div class="redux-action_bar">
			<span class="spinner"></span>
			<?php submit_button( __( 'Save Changes', 'redux-framework' ), 'primary', 'redux_save', false ); ?>

			<?php if ( false === $this->parent->args['hide_reset'] ) : ?>
				<?php submit_button( __( 'Reset Section', 'redux-framework' ), 'secondary', $this->parent->args['opt_name'] . '[defaults-section]', false ); ?>
				<?php submit_button( __( 'Reset All', 'redux-framework' ), 'secondary', $this->parent->args['opt_name'] . '[defaults]', false ); ?>
			<?php endif; ?>

		</div>

		<div class="redux-ajax-loading" alt="<?php _e( 'Working...', 'redux-framework' ) ?>">&nbsp;</div>
		<div class="clear"></div>

	</div>
</div>
