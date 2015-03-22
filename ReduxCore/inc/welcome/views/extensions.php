<?php
    /*
    repeater =>
    social profiles =>
    js button =>
    multi media =>
    css layout =>
    color schemes => adjust-alt
    custom fonts => fontsize
    code mirror => view-mode
    live search => search
    support faq's => question
    date time picker =>
    premium support =>
    metaboxes =>
    widget areas =>
    shortcodes =>
    icon select => gallery
    tracking =>
    * */
    $iconMap = array(
        'repeater'        => 'asl',
        'social-profiles' => 'group',
        'js-button'       => 'hand-down',
        'multi-media'     => 'picture',
        'css-layout'      => 'fullscreen',
        'color-schemes'   => 'adjust-alt',
        'custom-fonts'    => 'fontsize',
        'codemirror'      => 'view-mode',
        'live-search'     => 'search',
        'support-faqs'    => 'question',
        'date-time'       => 'calendar',
        'premium-support' => 'fire',
        'metaboxes'       => 'magic',
        'widget-areas'    => 'inbox-box',
        'shortcodes'      => 'shortcode',
        'icon-select'     => 'gallery',
    );
    $colors  = array(
        '8CC63F',
        '8CC63F',
        '0A803B',
        '25AAE1',
        '0F75BC',
        'F7941E',
        'F1592A',
        'ED217C',
        'BF1E2D',
        '8569CF',
        '0D9FD8',
        '8AD749',
        'EECE00',
        'F8981F',
        'F80E27',
        'F640AE'
    );
    shuffle( $colors );
    echo '<style type="text/css">';
?>

<?php
    foreach ( $colors as $key => $color ) {
        echo '.theme-browser .theme.color' . $key . ' .theme-screenshot{background-color:' . Redux_Helpers::hex2rgba( $color, .45 ) . ';}';
        echo '.theme-browser .theme.color' . $key . ':hover .theme-screenshot{background-color:' . Redux_Helpers::hex2rgba( $color, .75 ) . ';}';

    }
    echo '</style>';
    $color = 1;


?>


<div class="wrap about-wrap">
    <h1><?php _e( 'Redux Framework - Extensions', 'redux-framework' ); ?></h1>

    <div
        class="about-text"><?php printf( __( 'Supercharge your Redux experience. Our extensions provide you with features that will take your products to the next level.', 'redux-framework' ), $this->display_version ); ?></div>
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

    <p class="about-description"><?php _e( "While some are built specificially for developers, extensions such as Custom Fonts are sure to make any user happy.", 'redux-framework' ); ?></p>

    <div class="extensions">
        <div class="feature-section theme-browser rendered" style="clear:both;">
            <?php

                $data = get_transient( 'redux-extensions-fetch' );

                if ( empty( $data ) ) {
                    $data = json_decode( wp_remote_retrieve_body( wp_remote_get( 'http://reduxframework.com/wp-admin/admin-ajax.php?action=get_redux_extensions' ) ), true );
                    if ( ! empty( $data ) ) {
                        set_transient( 'redux-extensions-fetch', $data, 24 * HOUR_IN_SECONDS );
                    }
                }
                function shuffle_assoc( $list ) {
                    if ( ! is_array( $list ) ) {
                        return $list;
                    }

                    $keys = array_keys( $list );
                    shuffle( $keys );
                    $random = array();
                    foreach ( $keys as $key ) {
                        $random[ $key ] = $list[ $key ];
                    }

                    return $random;
                }

                $data = shuffle_assoc( $data );

                foreach ( $data as $key => $extension ) :

                    ?>
                    <div class="theme color<?php echo $color;
                        $color ++;?>">
                        <div class="theme-screenshot">
                            <figure>
                                <i class="el <?php echo isset( $iconMap[ $key ] ) && ! empty( $iconMap[ $key ] ) ? 'el-' . $iconMap[ $key ] : 'el-redux'; ?>"></i>
                                <figcaption>
                                    <p><?php echo $extension['excerpt'];?></p>
                                    <a href="<?php echo $extension['url']; ?>" target="_blank">Learn more</a>
                                </figcaption>
                            </figure>
                        </div>
                        <h3 class="theme-name" id="classic"><?php echo $extension['title']; ?></h3>

                        <div class="theme-actions">
                            <a class="button button-primary button-install-demo"
                               data-demo-id="<?php echo $key; ?>"
                               href="<?php echo $extension['url']; ?>" target="_blank">Learn
                                More</a></div>
                    </div>

                <?php
                endforeach;
            ?>
        </div>
    </div>
</div>