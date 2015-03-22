<div class="wrap about-wrap">
    <h1><?php _e( 'Redux Framework - Changelog', 'redux-framework' ); ?></h1>

    <div
        class="about-text"><?php _e( 'Our core mantra at Redux is backwards compatibility. With hundreds of thousands of instances worldwide, you can be assured that we will take care of you and your clients.', 'redux-framework' ); ?></div>
    <div
        class="redux-badge"><i
            class="el el-redux"></i><span><?php printf( __( 'Version %s', 'redux-framework' ), ReduxFramework::$_version ); ?></span>
    </div>

    <p class="redux-actions">
        <a href="http://docs.reduxframework.com/" class="docs button button-primary">Docs</a>
        <a href="https://wordpress.org/plugins/redux-framework/" class="review-us button button-primary"
           target="_blank">Review Us</a>
        <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=MMFMHWUPKHKPW"
           class="review-us button button-primary" target="_blank">Donate</a>
        <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://reduxframework.com"
           data-text="Reduce your dev time! Redux is the most powerful option framework for WordPress on the web"
           data-via="ReduxFramework" data-size="large" data-hashtags="Redux">Tweet</a>
        <script>!function( d, s, id ) {
                var js, fjs = d.getElementsByTagName( s )[0], p = /^http:/.test( d.location ) ? 'http' : 'https';
                if ( !d.getElementById( id ) ) {
                    js = d.createElement( s );
                    js.id = id;
                    js.src = p + '://platform.twitter.com/widgets.js';
                    fjs.parentNode.insertBefore( js, fjs );
                }
            }( document, 'script', 'twitter-wjs' );</script>
    </p>

    <?php $this->tabs(); ?>

    <div class="changelog">
        <div class="feature-section">
            <?php echo $this->parse_readme(); ?>
        </div>
    </div>

</div>