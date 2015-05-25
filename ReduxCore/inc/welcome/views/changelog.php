<div class="wrap about-wrap">
    <h1><?php _e( 'Redux Framework - Changelog', 'redux-framework' ); ?></h1>

    <div
        class="about-text"><?php _e( 'Our core mantra at Redux is backwards compatibility. With hundreds of thousands of instances worldwide, you can be assured that we will take care of you and your clients.', 'redux-framework' ); ?></div>
    <div
        class="redux-badge"><i
            class="el el-redux"></i><span><?php printf( __( 'Version %s', 'redux-framework' ), ReduxFramework::$_version ); ?></span>
    </div>

    <?php $this->actions(); ?>
    <?php $this->tabs(); ?>

    <div class="changelog">
        <div class="feature-section">
            <?php echo $this->parse_readme(); ?>
        </div>
    </div>

</div>