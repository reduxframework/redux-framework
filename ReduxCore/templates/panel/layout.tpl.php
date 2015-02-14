<?php
	// Main container
	$expanded = ( $this->parent->args['open_expanded'] ) ? ' fully-expanded' : '';
?>
<div class="redux-container<?php echo ( $this->parent->args['open_expanded'] ) ? ' fully-expanded' : '' . ( ! empty( $this->parent->args['class'] ) ? ' ' . $this->parent->args['class'] : '' ); ?>">
	<form method="post"
	      action="<?php echo( $this->parent->args['database'] == "network" && $this->parent->args['network_admin'] && is_network_admin() ? './edit.php?action=redux_' . $this->parent->args['opt_name'] : './options.php' ) ?>"
	      enctype="multipart/form-data" id="redux-form-wrapper">
		<input type="hidden" id="redux-compiler-hook" name="<?php echo $this->parent->args['opt_name']; ?>[compiler]"
		       value=""/>
		<input type="hidden" id="currentSection" name="<?php echo $this->parent->args['opt_name']; ?>[redux-section]"
		       value=""/>
		<?php if ( ! empty( $this->parent->no_panel ) ) : ?>
			<input type="hidden" name="<?php echo $this->parent->args['opt_name']; ?>[redux-no_panel]"
			       value="<?php echo implode( '|', $this->parent->no_panel ); ?>"/>
		<?php endif; ?>

		<?php
			// Must run or the page won't redirect properly
			settings_fields( "{$this->parent->args['opt_name']}_group" );

			// Last tab?
			$this->parent->options['last_tab'] = ( isset( $_GET['tab'] ) && ! isset( $this->parent->transients['last_save_mode'] ) ) ? $_GET['tab'] : '';
		?>
		<input type="hidden" id="last_tab" name="<?php echo $this->parent->args['opt_name']; ?>[last_tab]"
		       value="<?php echo $this->parent->options['last_tab']; ?>"/>

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

		<!-- Intro Text -->
		<?php if ( isset( $this->parent->args['intro_text'] ) ) : ?>
			<div id="redux-intro-text"><?php echo $this->parent->args['intro_text']; ?></div>
		<?php endif; ?>

		<!-- Stickybar -->
		<div id="redux-sticky">
			<div id="info_bar">

				<a href="javascript:void(0);"
				   class="expand_options<?php echo ( $this->parent->args['open_expanded'] ) ? ' expanded' : ''; ?>"<?php echo $this->parent->args['hide_expand'] ? ' style="display: none;"' : '' ?>><?php _e( 'Expand', 'redux-framework' ); ?></a>
				<div class="redux-action_bar">
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

			<!-- Warning bar -->
			<?php if ( isset( $this->parent->transients['last_save_mode'] ) ) {

				if ( $this->parent->transients['last_save_mode'] == "import" ) {
					/**
					 * action 'redux/options/{opt_name}/import'
					 *
					 * @param object $this ReduxFramework
					 */
					do_action( "redux/options/{$this->parent->args['opt_name']}/import", $this, $this->parent->transients['changed_values'] );

					/**
					 * filter 'redux-imported-text-{opt_name}'
					 *
					 * @param string  translated "settings imported" text
					 */
					echo '<div class="admin-notice notice-blue saved_notice"><strong>' . apply_filters( "redux-imported-text-{$this->parent->args['opt_name']}", __( 'Settings Imported!', 'redux-framework' ) ) . '</strong></div>';
					//exit();
				} else if ( $this->parent->transients['last_save_mode'] == "defaults" ) {
					/**
					 * action 'redux/options/{opt_name}/reset'
					 *
					 * @param object $this ReduxFramework
					 */
					do_action( "redux/options/{$this->parent->args['opt_name']}/reset", $this );

					/**
					 * filter 'redux-defaults-text-{opt_name}'
					 *
					 * @param string  translated "settings imported" text
					 */
					echo '<div class="saved_notice admin-notice notice-yellow"><strong>' . apply_filters( "redux-defaults-text-{$this->parent->args['opt_name']}", __( 'All Defaults Restored!', 'redux-framework' ) ) . '</strong></div>';
				} else if ( $this->parent->transients['last_save_mode'] == "defaults_section" ) {
					/**
					 * action 'redux/options/{opt_name}/section/reset'
					 *
					 * @param object $this ReduxFramework
					 */
					do_action( "redux/options/{$this->parent->args['opt_name']}/section/reset", $this );

					/**
					 * filter 'redux-defaults-section-text-{opt_name}'
					 *
					 * @param string  translated "settings imported" text
					 */
					echo '<div class="saved_notice admin-notice notice-yellow"><strong>' . apply_filters( "redux-defaults-section-text-{$this->parent->args['opt_name']}", __( 'Section Defaults Restored!', 'redux-framework' ) ) . '</strong></div>';
				} else if ( $this->parent->transients['last_save_mode'] == "normal" ) {
					/**
					 * action 'redux/options/{opt_name}/saved'
					 *
					 * @param mixed $value set/saved option value
					 */
					do_action( "redux/options/{$this->parent->args['opt_name']}/saved", $this->parent->options, $this->parent->transients['changed_values'] );

					/**
					 * filter 'redux-saved-text-{opt_name}'
					 *
					 * @param string translated "settings saved" text
					 */
					echo '<div class="saved_notice admin-notice notice-green"><strong>' . apply_filters( "redux-saved-text-{$this->parent->args['opt_name']}", __( 'Settings Saved!', 'redux-framework' ) ) . '</strong></div>';
				}

				unset( $this->parent->transients['last_save_mode'] );
				//$this->parent->transients['last_save_mode'] = 'remove';
				$this->parent->set_transients();
			}

				/**
				 * action 'redux/options/{opt_name}/settings/changes'
				 *
				 * @param mixed $value set/saved option value
				 */
				do_action( "redux/options/{$this->parent->args['opt_name']}/settings/change", $this->parent->options, $this->parent->transients['changed_values'] );

				/**
				 * filter 'redux-changed-text-{opt_name}'
				 *
				 * @param string translated "settings have changed" text
				 */
				echo '<div class="redux-save-warn notice-yellow"><strong>' . apply_filters( "redux-changed-text-{$this->parent->args['opt_name']}", __( 'Settings have changed, you should save them!', 'redux-framework' ) ) . '</strong></div>';

				/**
				 * action 'redux/options/{opt_name}/errors'
				 *
				 * @param array $this ->errors error information
				 */
				do_action( "redux/options/{$this->parent->args['opt_name']}/errors", $this->parent->errors );
				echo '<div class="redux-field-errors notice-red"><strong><span></span> ' . __( 'error(s) were found!', 'redux-framework' ) . '</strong></div>';

				/**
				 * action 'redux/options/{opt_name}/warnings'
				 *
				 * @param array $this ->warnings warning information
				 */
				do_action( "redux/options/{$this->parent->args['opt_name']}/warnings", $this->parent->warnings );
			?>
			<div class="redux-field-warnings notice-yellow">
				<strong><span></span><?php _e( 'warning(s) were found!', 'redux-framework' ); ?></strong></div>

		</div>

		<div class="clear"></div>

		<?php $this->get_template( 'menu_container.tpl.php' ); ?>

		<div class="redux-main">
			<?php
				foreach ( $this->parent->sections as $k => $section ) {
					if ( isset( $section['customizer_only'] ) && $section['customizer_only'] == true ) {
						continue;
					}

					//$active = ( ( is_numeric($this->parent->current_tab) && $this->parent->current_tab == $k ) || ( !is_numeric($this->parent->current_tab) && $this->parent->current_tab === $k )  ) ? ' style="display: block;"' : '';
					$section['class'] = isset( $section['class'] ) ? ' ' . $section['class'] : '';
					echo '<div id="' . $k . '_section_group' . '" class="redux-group-tab' . $section['class'] . '" data-rel="' . $k . '">';
					//echo '<div id="' . $k . '_nav-bar' . '"';
					/*
				if ( !empty( $section['tab'] ) ) {

					echo '<div id="' . $k . '_section_tabs' . '" class="redux-section-tabs">';

					echo '<ul>';

					foreach ($section['tab'] as $subkey => $subsection) {
						//echo '-=' . $subkey . '=-';
						echo '<li style="display:inline;"><a href="#' . $k . '_section-tab-' . $subkey . '">' . $subsection['title'] . '</a></li>';
					}

					echo '</ul>';
					foreach ($section['tab'] as $subkey => $subsection) {
						echo '<div id="' . $k .'sub-'.$subkey. '_section_group' . '" class="redux-group-tab" style="display:block;">';
						echo '<div id="' . $k . '_section-tab-' . $subkey . '">';
						echo "hello ".$subkey;
						do_settings_sections( $this->parent->args['opt_name'] . $k . '_tab_' . $subkey . '_section_group' );
						echo "</div>";
						echo "</div>";
					}
					echo "</div>";
				} else {
					*/

					// Don't display in the
					$display = true;
					if ( isset( $_GET['page'] ) && $_GET['page'] == $this->parent->args['page_slug'] ) {
						if ( isset( $section['panel'] ) && $section['panel'] == "false" ) {
							$display = false;
						}
					}

					if ( $display ) {
						do_settings_sections( $this->parent->args['opt_name'] . $k . '_section_group' );
					}
					//}
					echo "</div>";
					//echo '</div>';
				}

				// Import / Export output
				if ( true == $this->parent->args['show_import_export'] && false == $this->parent->import_export->is_field ) {
					$this->parent->import_export->enqueue();
					echo '<fieldset id="' . $this->parent->args['opt_name'] . '-import_export_core" class="redux-field-container redux-field redux-field-init redux-container-import_export" data-id="import_export_core" data-type="import_export">';
					$this->parent->import_export->render();
					echo '</fieldset>';

				}

				// Debug object output
				if ( $this->parent->args['dev_mode'] == true ) {
					$this->parent->debug->render();
				}
			?>
			<?php if ( $this->parent->args['system_info'] === true ) :
				require_once 'inc/sysinfo.php';
				$system_info = new Simple_System_Info();
				?>
				<div id="system_info_default_section_group" class="redux-group-tab">
					<h3><?php _e( 'System Info', 'redux-framework' );?></h3>

					<div id="redux-system-info">
						<?php echo $system_info->get( true );?>
					</div>

				</div>
			<?php endif; ?>
			<?php
				/**
				 * action 'redux/page-after-sections-{opt_name}'
				 *
				 * @deprecated
				 *
				 * @param object $this ReduxFramework
				 */
				do_action( "redux/page-after-sections-{$this->parent->args['opt_name']}", $this ); // REMOVE LATER

				/**
				 * action 'redux/page/{opt_name}/sections/after'
				 *
				 * @param object $this ReduxFramework
				 */
				do_action( "redux/page/{$this->parent->args['opt_name']}/sections/after", $this );
			?>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>

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
									<img src="<?php $link['img'] ?>"/>
								<?php endif; ?>

							</a>
						<?php endforeach; ?>

					</div>
				<?php endif; ?>

				<div class="redux-action_bar">
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
	</form>
</div></div>
<?php if ( isset( $this->parent->args['footer_text'] ) ) : ?>
	<div id="redux-sub-footer"><?php $this->parent->args['footer_text']; ?></div>
<?php endif; ?>
