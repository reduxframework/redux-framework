<?php
/**
 * Admin View: Page - About
 *
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="wrap about-wrap">
	<div class="error hide">
		<p>Redux.io is running from within one of your products. To keep your site safe, please install the Redux
			Framework
			plugin from WordPress.org.</p>
	</div>
	<h1><?php printf( esc_html__( 'Welcome to', 'redux-framework' ) . ' Redux Framework %s', esc_html( $this->display_version ) ); ?></h1>


	<div class="about-text">
		<?php esc_html_e( "Redux is the world's most powerful and widely used WordPress interface builder. We are trusted by millions of developers and end users world-wide.", 'redux-framework' ); ?>
	</div>
	<div class="redux-badge">
		<i class="el el-redux"></i>
		<span><?php printf( esc_html__( 'Version', 'redux-framework' ) . ' %s', esc_html( Redux_Core::$version ) ); ?></span>
	</div>

	<?php $this->actions(); ?>
	<?php $this->tabs(); ?>
	<hr>
	<?php

	$redux_sys_info = Redux_Helpers::process_redux_callers();

	?>

	<div class="feature-section <?php echo empty( $redux_sys_info ) ? 'one-col' : 'two-col'; ?>">
		<div class="<?php echo ! empty( $redux_sys_info ) ? 'col' : ''; ?>">
			<h2 style="text-align:left;"><?php echo esc_html__( 'Did I install this?', 'redux-framework' ); ?></h2>
			<h3>
				<?php
				if ( ! empty( $redux_sys_info ) ) {
					esc_html_e( 'Maybe not! These items are using Redux. If you want to keep using them, Redux will need to remain installed and activated.', 'redux-framework' );
				} else {
					$redux_nonce = wp_create_nonce( 'redux_framework_demo' );

					$redux_query_args = array(
						'page'                   => 'redux-framework',
						'redux-framework-plugin' => 'demo',
						'nonce'                  => $redux_nonce,
					);

					// translators: %1$s: URL, %2$s: close tag.
					printf( esc_html__( 'Maybe not! If you want to see what Redux is all about, click here to %1$sActivate Demo Mode%2$s.', 'redux-framework' ), '<a href="' . esc_url( admin_url( add_query_arg( $redux_query_args, 'options-general.php' ) ) ) . '">', '</a>' );
				}

				?>
			</h3>
		</div>
		<div class="col">
			<?php
			if ( ! empty( $redux_sys_info ) && is_array( $redux_sys_info ) ) {
				$redux_plugin_index = array();
				$redux_plugin_data  = get_plugins();

				foreach ( $redux_plugin_data as $redux_key => $redux_data ) {
					$redux_key_slug                        = explode( '/', $redux_key );
					$redux_key_slug                        = $redux_key_slug[0];
					$redux_plugin_index[ $redux_key_slug ] = $redux_key;
				}

				foreach ( $redux_sys_info as $redux_project_type => $redux_products ) {
					if ( 'theme' === $redux_project_type ) {
						$redux_my_theme = wp_get_theme();

						?>
						<div class="redux-product">
							<h2 class="name"><?php echo esc_html( $redux_my_theme->get( 'Name' ) ); ?>
								<?php if ( ! empty( $redux_my_theme->get( 'Version' ) ) ) { ?>
									<span class="version"><?php echo esc_html__( 'Version:', 'redux-framework' ); ?>&nbsp;<?php echo esc_html( $redux_my_theme->get( 'Version' ) ); ?></span>
								<?php } ?>
							</h2>
							<p class="author">
								<?php if ( ! empty( $redux_my_theme->get( 'Author' ) ) ) { ?>
									<?php echo esc_html__( 'By', 'redux-framework' ); ?>
									<a href="<?php echo ! empty( $redux_my_theme->get( 'AuthorURI' ) ) ? esc_attr( $redux_my_theme->get( 'AuthorURI' ) ) : esc_attr( $redux_my_theme->get( 'ThemeURI' ) ); ?>">
										<?php echo esc_html( $redux_my_theme->get( 'Author' ) ); ?>

									</a>
								<?php } ?>
								<span class="type theme">
												<?php echo esc_html__( 'Theme', 'redux-framework' ); ?>
										</span>
							</p>
							<hr style="margin: 0 0 15px 0;padding:0;">
							<p class="author">
								<small>
									<?php
									foreach ( $redux_products as $redux_slug => $redux_data ) {
										foreach ( $redux_data as $redux_opt_name => $redux_callers ) {
											echo '<span><strong>opt_name</strong>: <code>' . esc_html( $redux_opt_name ) . '</code></span><br />';

											foreach ( $redux_callers as $redux_caller ) {
												echo '<span>~/' . esc_html( $redux_caller['basename'] ) . '</span><br />';
											}

											echo '<br />';
										}
									}
									?>
								</small>
							</p>
						</div>
						<?php

					} else {
						foreach ( $redux_products as $redux_product => $redux_data ) {
							if ( ! isset( $redux_plugin_index[ $redux_product ] ) ) {
								continue;
							}

							$redux_plugin_path = Redux_Functions_Ex::wp_normalize_path( WP_PLUGIN_DIR . '/' . $redux_plugin_index[ $redux_product ] );
							$redux_plugin_data = get_plugin_data( $redux_plugin_path );

							?>
							<div class="redux-product">
								<h2 class="name">
									<?php echo esc_html( $redux_plugin_data['Name'] ); ?>
									<?php if ( ! empty( $redux_plugin_data['Version'] ) ) { ?>
										<span class="version"><?php echo esc_html__( 'Version', 'redux-framework' ); ?>&nbsp;<?php echo esc_html( $redux_plugin_data['Version'] ); ?></span>
									<?php } ?>
								</h2>
								<p class="author">
									<?php
									if ( ! empty( $redux_plugin_data['Author'] ) ) {
										$redux_plugin_url = ! empty( $redux_plugin_data['AuthorURI'] ) ? $redux_plugin_data['AuthorURI'] : $redux_plugin_data['PluginURI'];
										?>
										<?php echo esc_html__( 'By', 'redux-framework' ); ?>
										<a href="<?php echo esc_attr( $redux_plugin_url ); ?>">
											<?php echo esc_html( trim( wp_strip_all_tags( $redux_plugin_data['Author'] ) ) ); ?>
										</a>
									<?php } ?>
									<span class="type plugin">
									<?php echo esc_html__( 'Plugin', 'redux-framework' ); ?>
								</span>
								</p>
								<hr style="margin: 0 0 15px 0;padding:0;">
								<p class="author">
									<small>
										<?php
										foreach ( $redux_data as $redux_opt_name => $redux_callers ) {
											echo '<span><strong>opt_name</strong>: <code>' . esc_html( $redux_opt_name ) . '</code></span><br />';

											foreach ( $redux_callers as $redux_caller ) {
												echo '<span>~/' . esc_html( $redux_caller['basename'] ) . '</span><br />';
											}
										}
										?>
									</small>
								</p>
							</div>
							<?php
						}
					}
				}
			}
			?>
		</div>
	</div>
</div>
