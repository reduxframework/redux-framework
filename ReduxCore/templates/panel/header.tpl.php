<div id="redux-header">
	<?php if ( ! empty( $this->parent->args['display_name'] ) ) : ?>
		<div class="display_header">

			<?php if ( isset( $this->parent->args['dev_mode'] ) && $this->parent->args['dev_mode'] ) : ?>
				<span
					class="redux-dev-mode-notice"><?php _e( 'Developer Mode Enabled', 'redux-framework' ); ?></span>
			<?php endif; ?>

			<h2><?php echo $this->parent->args['display_name']; ?></h2>

			<?php if ( ! empty( $this->parent->args['display_version'] ) ) : ?>
				<span><?php echo $this->parent->args['display_version']; ?></span>
			<?php endif; ?>

		</div>
	<?php endif; ?>

	<div class="clear"></div>
</div>