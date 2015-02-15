<div id="redux-sticky">
	<div id="info_bar">

		<a href="javascript:void(0);"
		   class="expand_options<?php echo ( $this->parent->args['open_expanded'] ) ? ' expanded' : ''; ?>"<?php echo $this->parent->args['hide_expand'] ? ' style="display: none;"' : '' ?>><?php _e( 'Expand', 'redux-framework' ); ?></a>

		<div class="redux-action_bar">
			<span class="spinner"></span>
			<?php submit_button( __( 'Save Changes', 'redux-framework' ), 'primary', 'redux_save', false ); ?>
			<?php if ( false === $this->parent->args['hide_reset'] ) : ?>
				&nbsp;
				<?php submit_button( __( 'Reset Section', 'redux-framework' ), 'secondary', $this->parent->args['opt_name'] . '[defaults-section]', false ); ?>
				&nbsp;
				<?php submit_button( __( 'Reset All', 'redux-framework' ), 'secondary', $this->parent->args['opt_name'] . '[defaults]', false ); ?>
			<?php endif; ?>
		</div>
		<div class="redux-ajax-loading" alt="<?php _e( 'Working...', 'redux-framework' ) ?>">&nbsp;</div>
		<div class="clear"></div>
	</div>

	<!-- Notification bar -->
	<div id="redux_notification_bar">
		<?php $this->notification_bar(); ?>
	</div>


</div>