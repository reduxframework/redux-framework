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
		<p>Redux.io is running from within one of your products. To keep your site safe, please install the Redux Framework plugin from WordPress.org.</p>
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

	<?php $value = Redux_Core::$redux_templates_enabled; ?>

	<div class="feature-section one-col">
		<div class="col">
			<?php // translators: %s: HTML. ?>
			<h2><?php echo sprintf( esc_html__( ' Template Library', 'redux-framework' ), '<br />' ); ?></h2>
		</div>
	</div>
	<div class='wrap'>
		<form method="post" action="options.php">
			<?php settings_fields( 'redux_templates' ); ?>
			<table class="form-table">
				<tbody>
				<tr valign="top">
					<th scope="row" valign="top">
						<?php esc_html_e( 'Redux Template Library', 'redux-framework' ); ?>
					</th>
					<td>
						<input id="redux-pro_license_key" name="use_redux_templates" type="checkbox" class="regular-text" value="1" <?php checked( $value, '1', true ); ?>/>
						<label class="description" for="use_redux_templates"><?php esc_html_e( 'Load legacy Redux template library', 'redux-framework' ); ?></label>
					</td>
				</tr>
				</tbody>
			</table>
			<?php submit_button(); ?>
		</form>
		<hr>
</div>
