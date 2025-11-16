<?php
/**
 * The template for the panel footer area.
 * Override this template by specifying the path where it is stored (templates_path) in your Redux config.
 *
 * @author        Redux Framework
 * @package       ReduxFramework/Templates
 * @version:      4.4.2
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>
<div id="redux-sticky-padder" style="display: none;">&nbsp;</div>
<div id="redux-footer-sticky">
	<div id="redux-footer">
		<?php
		if ( isset( $this->parent->args['share_icons'] ) ) {
			$redux_skip_icons = false;

			if ( ! $this->parent->args['dev_mode'] && $this->parent->args_class->omit_icons ) {
				$redux_skip_icons = true;
			}
			?>
			<div id="redux-share">
				<?php
				foreach ( $this->parent->args['share_icons'] as $redux_links ) {
					if ( $redux_skip_icons ) {
						continue;
					}
					// SHIM, use URL now.
					if ( isset( $redux_links['link'] ) && ! empty( $redux_links['link'] ) ) {
						$redux_links['url'] = $redux_links['link'];
						unset( $redux_links['link'] );
					}
					if ( isset( $redux_links['icon'] ) && ! empty( $redux_links['icon'] ) ) {
						if ( strpos( $redux_links['icon'], 'el-icon' ) !== false && strpos( $redux_links['icon'], 'el ' ) === false ) {
							$redux_links['icon'] = 'el ' . $redux_links['icon'];
						}
					}
					?>
					<a href="<?php echo esc_url( $redux_links['url'] ); ?>" title="<?php echo esc_attr( $redux_links['title'] ); ?>" target="_blank">
						<?php if ( isset( $redux_links['icon'] ) && ! empty( $redux_links['icon'] ) ) { ?>
							<i class="<?php echo esc_attr( $redux_links['icon'] ); ?>"></i>
						<?php } else { ?>
							<img alt="<?php echo esc_url( $redux_links['img'] ); ?>" src="<?php echo esc_url( $redux_links['img'] ); ?>"/>
						<?php } ?>
					</a>
				<?php } ?>
			</div>
		<?php } ?>

		<div class="redux-action_bar">
			<span class="spinner"></span>
			<?php
			if ( false === $this->parent->args['hide_save'] ) {
				submit_button( esc_html__( 'Save Changes', 'redux-framework' ), 'primary', 'redux_save', false, array( 'id' => 'redux_bottom_save' ) );
			}

			if ( false === $this->parent->args['hide_reset'] ) {
				submit_button( esc_html__( 'Reset Section', 'redux-framework' ), 'secondary', $this->parent->args['opt_name'] . '[defaults-section]', false, array( 'id' => 'redux-defaults-section-bottom' ) );
				submit_button( esc_html__( 'Reset All', 'redux-framework' ), 'secondary', $this->parent->args['opt_name'] . '[defaults]', false, array( 'id' => 'redux-defaults-bottom' ) );
			}
			?>
		</div>
		<div class="clear"></div>
	</div>
</div>
