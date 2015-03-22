<div class="wrap about-wrap">
    <h1><?php _e( 'Redux Framework - Support', 'redux-framework' ); ?></h1>

    <div
        class="about-text"><?php printf( __( 'We are an open source project used by developers to make powerful control panels.', 'redux-framework' ), $this->display_version ); ?></div>
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

    <p class="about-description"><?php _e( 'To get the proper support, we need to send you to the correct place. Please choose the type of user you are.', 'redux-framework' ); ?></p>

    <div class="support">
        <ul>
            <li class="userType">
                <a href=""><i class="el el-child"></i></a>

                <h2>User</h2>
            </li>
            <li class="userType">
                <a href=""><i class="el el-idea"></i></a>

                <h2>Developer</h2>
            </li>
        </ul>


        <h3><?php _e( 'Hello there WordPress User!', 'redux-framework' ); ?></h3>

        <div class="feature-section">


        </div>
    </div>

</div>