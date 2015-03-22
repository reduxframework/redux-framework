<div class="wrap about-wrap">
    <h1><?php printf( __( 'Welcome to Redux Framework %s', 'redux-framework' ), $this->display_version ); ?></h1>

    <div
        class="about-text"><?php printf( __( 'Thank you for updating to the latest version! Redux Framework %s is a huge step forward in Redux Development. Look at all that\'s new.', 'redux-framework' ), $this->display_version ); ?></div>
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

    <div id="redux-message" class="updated">
        <h4>What is Redux Framework?</h4>

        <p>Redux Framework is the core of many products on the web. It is an option framework which developers use to
            enhance their products..</p>

        <p class="submit">
            <a class="button-primary" href="http://reduxframework.com" target="_blank">Learn More</a>
        </p>
    </div>

    <div class="changelog">

        <h2>New in this Release</h2>

        <div class="changelog about-integrations">
            <div class="wc-feature feature-section col three-col">
                <div>
                    <h4>Ajax Saving & More Speed!</h4>

                    <p>This version the fastest Redux ever released. We've integrated ajax_saving as well as many other
                        speed improvments to make Redux even surpass the load time of <a
                            href="https://github.com/syamilmj/Options-Framework" target="_blank">SMOF</a> even with
                        large panels.</p>
                </div>
                <div>
                    <h4>The New Redux API</h4>

                    <p>We've gone back to the drawing boards and made Redux the <strong>simplist</strong> framework to
                        use. Introducing the Redux API. Easily add fields, extensions, templates, and more without every
                        having to define a class! <a href="" target="_blank">Learn More</a></p>
                </div>
                <div class="last-feature">
                    <h4>Security Improvments</h4>

                    <p>Thanks to the help of <a href="http://www.pritect.net/" target="_blank">James Golovich
                            (Pritect)</a>, we have patched varying security flaws in Redux. This is the most secure
                        version of Redux yet!</p>
                </div>
            </div>
        </div>
        <div class="changelog">
            <div class="feature-section col three-col">
                <div>
                    <h4>Panel Templates</h4>

                    <p>Now developers can easily customize the Redux panel by declaring a templates location path. We've
                        also made use of template versioning so if we change anything, you will know.</p>
                </div>
                <div>
                    <h4>Full Width for ANY Field</h4>

                    <p>Any field can now be set to full width! Just set the <code>full_width</code> argument and your
                        field will expand to the full width of your panel or metabox.</p>
                </div>
                <div class="last-feature">
                    <h4>Elusive Icons Update</h4>

                    <p>Redux is now taking over development of Elusive Icons. As a result, we've refreshed our copy of
                        Elusive to the newest version.</p>
                </div>
            </div>
        </div>
    </div>
</div>