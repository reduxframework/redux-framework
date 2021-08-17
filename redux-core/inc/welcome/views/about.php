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

	<div class="feature-section one-col">
		<div class="col">
			<?php // translators: %s: HTML. ?>
			<h2><?php echo sprintf( esc_html__( 'Redux is Where the End User %s Interacts with WordPress', 'redux-framework' ), '<br />' ); ?></h2>
			<?php echo sprintf( '<h3 style="text-align: center;">%s</h3>', esc_html__( "Redux is a foundation on which to build any interface in WordPress. It's time saved, frustrations reduced, and a unified experience.", 'redux-framework' ) ); ?>

			<div class="hide" style="text-align: center;margin-top:25px;">
				<span class="button button-secondary"><?php echo esc_html__( 'Metaboxes', 'redux-framework' ); ?></span>
				<span class="button button-secondary hide"><?php echo esc_html__( 'Taxonomies', 'redux-framework' ); ?></span>
				<span class="button button-secondary hide"><?php echo esc_html__( 'User Meta', 'redux-framework' ); ?></span>
				<span class="button button-secondary"><?php echo esc_html__( 'Options Panel', 'redux-framework' ); ?></span>
				<span class="button button-secondary"><?php echo esc_html__( 'Customizer', 'redux-framework' ); ?></span>
				<span class="button button-secondary hide"><?php echo esc_html__( 'Gutenberg', 'redux-framework' ); ?></span>
			</div>
		</div>
	</div>

	<hr>
	<?php

	$sysinfo = Redux_Helpers::process_redux_callers();

	?>

	<div class="feature-section <?php echo empty( $sysinfo ) ? 'one-col' : 'two-col'; ?>">
		<div class="<?php echo ! empty( $sysinfo ) ? 'col' : ''; ?>">
			<h2 style="text-align:left;"><?php echo esc_html__( 'Did I install this?', 'redux-framework' ); ?></h2>
			<h3>
				<?php
				if ( ! empty( $sysinfo ) ) {
					esc_html_e( 'Maybe not! These items are using Redux. If you want to keep using them, Redux will need to remain installed and activated.', 'redux-framework' );
				} else {
					$nonce = wp_create_nonce( 'redux_framework_demo' );

					$query_args = array(
						'page'                   => 'redux-framework',
						'redux-framework-plugin' => 'demo',
						'nonce'                  => $nonce,
					);

					// translators: %1$s: URL, %2$s: close tag.
					echo sprintf( esc_html__( 'Maybe not! If you want to see what Redux is all about, click here to %1$sActivate Demo Mode%2$s.', 'redux-framework' ), '<a href="' . esc_url( admin_url( add_query_arg( $query_args, 'options-general.php' ) ) ) . '">', '</a>' );
				}
				?>


			</h3>
		</div>

		<div class="col">
			<?php
			if ( ! empty( $sysinfo ) && is_array( $sysinfo ) ) {
				$plugin_index = array();
				$plugin_data  = get_plugins();

				foreach ( $plugin_data as $key => $data ) {
					$key_slug                  = explode( '/', $key );
					$key_slug                  = $key_slug[0];
					$plugin_index[ $key_slug ] = $key;
				}

				foreach ( $sysinfo as $project_type => $products ) {
					if ( 'theme' === $project_type ) {
						$my_theme = wp_get_theme();
						?>
						<div class="redux-product">
							<h2 class="name"><?php echo esc_html( $my_theme->get( 'Name' ) ); ?>
								<?php if ( ! empty( $my_theme->get( 'Version' ) ) ) { ?>
									<span class="version"><?php echo esc_html__( 'Version:', 'redux-framework' ); ?>&nbsp;<?php echo esc_html( $my_theme->get( 'Version' ) ); ?></span>
								<?php } ?>
							</h2>
							<p class="author">
								<?php if ( ! empty( $my_theme->get( 'Author' ) ) ) { ?>
									<?php echo esc_html__( 'By', 'redux-framework' ); ?>
									<a href="<?php echo ! empty( $my_theme->get( 'AuthorURI' ) ) ? esc_attr( $my_theme->get( 'AuthorURI' ) ) : esc_attr( $my_theme->get( 'ThemeURI' ) ); ?>">
										<?php echo esc_html( $my_theme->get( 'Author' ) ); ?>

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
									foreach ( $products as $slug => $data ) {
										foreach ( $data as $opt_name => $callers ) {
											echo '<span><strong>opt_name</strong>: <code>' . esc_html( $opt_name ) . '</code></span><br />';

											foreach ( $callers as $caller ) {
												echo '<span>~/' . esc_html( $caller['basename'] ) . '</span><br />';
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
						foreach ( $products as $product => $data ) {
							if ( ! isset( $plugin_index[ $product ] ) ) {
								continue;
							}

							$plugin_path = Redux_Functions_Ex::wp_normalize_path( WP_PLUGIN_DIR . '/' . $plugin_index[ $product ] );
							$plugin_data = get_plugin_data( $plugin_path );

							?>
							<div class="redux-product">
								<h2 class="name">
									<?php echo esc_html( $plugin_data['Name'] ); ?>
									<?php if ( ! empty( $plugin_data['Version'] ) ) { ?>
										<span class="version"><?php echo esc_html__( 'Version', 'redux-framework' ); ?>&nbsp;<?php echo esc_html( $plugin_data['Version'] ); ?></span>
									<?php } ?>
								</h2>
								<p class="author">
									<?php
									if ( ! empty( $plugin_data['Author'] ) ) {
										$plugin_url = ! empty( $plugin_data['AuthorURI'] ) ? $plugin_data['AuthorURI'] : $plugin_data['PluginURI'];
										?>
										<?php echo esc_html__( 'By', 'redux-framework' ); ?>
										<a href="<?php echo esc_attr( $plugin_url ); ?>">
											<?php echo esc_html( trim( wp_strip_all_tags( $plugin_data['Author'] ) ) ); ?>
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
										foreach ( $data as $opt_name => $callers ) {
											echo '<span><strong>opt_name</strong>: <code>' . esc_html( $opt_name ) . '</code></span><br />';

											foreach ( $callers as $caller ) {
												echo '<span>~/' . esc_html( $caller['basename'] ) . '</span><br />';
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
