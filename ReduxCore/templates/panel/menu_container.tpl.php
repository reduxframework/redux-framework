<?php
	/**
	 * The template for the menu container of the panel.
	 *
	 * Override this template by specifying the path where it is stored (templates_path) in your Redux config.
	 *
	 * @author 		Redux Framework
	 * @package 	ReduxFramework/Templates
	 * @version     3.4.3
	 */
?>
<div class="redux-sidebar">
	<ul class="redux-group-menu">
		<?php
			foreach ( $this->parent->sections as $k => $section ) {
				$title = isset( $section['title'] ) ? $section['title'] : '';

				$skip_sec = false;
				foreach ( $this->parent->hidden_perm_sections as $num => $section_title ) {
					if ( $section_title == $title ) {
						$skip_sec = true;
					}
				}

				if ( isset( $section['customizer_only'] ) && $section['customizer_only'] == true ) {
					continue;
				}

				if ( false == $skip_sec ) {
					echo $this->parent->section_menu( $k, $section );
					$skip_sec = false;
				}
			}

			/**
			 * action 'redux-page-after-sections-menu-{opt_name}'
			 *
			 * @param object $this ReduxFramework
			 */
			do_action( "redux-page-after-sections-menu-{$this->parent->args['opt_name']}", $this );

			/**
			 * action 'redux/page/{opt_name}/menu/after'
			 *
			 * @param object $this ReduxFramework
			 */
			do_action( "redux/page/{$this->parent->args['opt_name']}/menu/after", $this );

			// Import / Export tab
			if ( true == $this->parent->args['show_import_export'] && false == $this->parent->import_export->is_field ) {
				$this->parent->import_export->render_tab();
			}

			// Debug tab
			if ( $this->parent->args['dev_mode'] == true ) {
				$this->parent->debug->render_tab();
			}
		?>

		<?php if ( $this->parent->args['system_info'] === true ) : ?>
			<li id="system_info_default_section_group_li" class="redux-group-tab-link-li">
				<?php
					if ( ! empty( $this->parent->args['icon_type'] ) && $this->parent->args['icon_type'] == 'image' ) {
						$icon = ( ! isset( $this->parent->args['system_info_icon'] ) ) ? '' : '<img src="' . $this->parent->args['system_info_icon'] . '" /> ';
					} else {
						$icon_class = ( ! isset( $this->parent->args['system_info_icon_class'] ) ) ? '' : ' ' . $this->parent->args['system_info_icon_class'];
						$icon       = ( ! isset( $this->parent->args['system_info_icon'] ) ) ? '<i class="el-icon-info-sign' . $icon_class . '"></i>' : '<i class="icon-' . $this->parent->args['system_info_icon'] . $icon_class . '"></i> ';
					}
				?>
				<a href="javascript:void(0);" id="system_info_default_section_group_li_a"
				   class="redux-group-tab-link-a custom-tab" data-rel="system_info_default"><?php echo $icon; ?><span
						class="group_title"><?php _e( 'System Info', 'redux-framework' ); ?></span></a>
			</li>
		<?php endif; ?>
	</ul>
</div>