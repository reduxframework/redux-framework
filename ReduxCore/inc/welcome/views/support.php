<div class="wrap about-wrap">
    <h1><?php _e( 'Redux Framework - Support', 'redux-framework' ); ?></h1>

    <div
        class="about-text"><?php printf( __( 'We are an open source project used by developers to make powerful control panels.', 'redux-framework' ), $this->display_version ); ?></div>
    <div
        class="redux-badge"><i
            class="el el-redux"></i><span><?php printf( __( 'Version %s', 'redux-framework' ), ReduxFramework::$_version ); ?></span>
    </div>

    <?php $this->actions(); ?>
    <?php $this->tabs(); ?>

    <div id="support_div" class="support">

        <?php
            $hash          = get_option( 'redux_support_hash' );
            $newHash       = 'testing';
            $generate_hash = true;
            if ( $newHash == $hash ) {
                unset( $generate_hash );
            }
            unset( $generate_hash );


        ?>

        <!-- multistep form -->
        <form id="msform">

            <ul id="progressbar" class=" breadcrumb">
                <li class="active">Create Support Hash</li>
                <li href="#">Choose Support Type</li>
                <li href="#">Finalize Support</li>
            </ul>

            <!-- fieldsets -->
            <fieldset>
                <h2 class="fs-title">Submit a Support Request</h2>

                <h3 class="fs-subtitle<?php echo isset( $generate_hash ) ? '' : ' hide'; ?>">To get started, we will
                    need to generate a support hash. This will provide to
                    your developer all the information they may need to remedy your issue. This action WILL send
                    information to a remote server. To learn what information is sent, you may inspect the <a
                        href="<?php echo admin_url( 'tools.php?page=redux-status' ); ?>">Status tab</a>.</h3>

                <h3 class="fs-subtitle hasAHash <?php echo ! isset( $generate_hash ) ? '' : ' hide'; ?>"
                    style="text-align: center;">You already have a valid support hash. Please proceed to the
                    next step.</h3>
                <input type="text" name="hash" placeholder="Support Hash" disabled="disabled"
                       class="hash<?php echo ! isset( $generate_hash ) ? '' : ' hide'; ?>"
                       value="http://support.redux.io/"/>

                <p<?php echo isset( $generate_hash ) ? '' : ' class="hide"'; ?>><a href="#"
                                                                                   class="docs button button-primary button-large">Generate
                        Support Hash</a></p>
                <input type="button" name="next" class="next action-button"
                       value="Next"<?php echo ! isset( $generate_hash ) ? '' : ' disabled="disabled"'; ?>/>
            </fieldset>
            <fieldset>
                <h2 class="fs-title">Support Type</h2>

                <h3 class="fs-subtitle" style="text-align: center;">
                    Please select all items that you believe to be causing your issue.
                </h3>
                <ul id="toDebug">
                    <?php
                        $active_plugins = (array) get_option( 'active_plugins', array() );

                        if ( is_multisite() ) {
                            $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
                        }


                        foreach ( $active_plugins as $plugin ) :
                            $plugin_data           = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
                            $dirname               = dirname( $plugin );
                            $plugin_data['folder'] = dirname( $plugin );

                            ?>
                            <li><input class="checkbox plugins" name="type[]" id="p<?php echo $dirname; ?>"
                                       type="checkbox"
                                       value="<?php echo urlencode( json_encode( $plugin_data ) ); ?>"><label
                                    for="p<?php echo $dirname; ?>"><strong><?php _e( 'Plugin', 'redux-framework' );?>
                                        : </strong> <?php echo $plugin_data['Name']; ?>
                                    <small>v<?php echo $plugin_data['Version']; ?></small>
                                </label>
                            </li>

                        <?php
                        endforeach;

                        $active_theme = wp_get_theme()
                    ?>
                    <li><input class="checkbox theme" name="type[]"
                               id="<?php echo sanitize_html_class( $active_theme->Name ); ?>"
                               type="checkbox" data='<?php echo json_encode( $active_theme ); ?>'
                               value="<?php echo urlencode( json_encode( $active_theme ) ); ?>"><label
                            for="<?php echo sanitize_html_class( $active_theme->Name ); ?>"><strong><?php _e( 'Theme', 'redux-framework' ); ?>
                                : </strong> <?php echo $active_theme->Name; ?>
                            <small>v<?php echo $active_theme->Version; ?></small>
                        </label>
                    </li>
                </ul>


                <p style="clear:both;">
                    <input class="checkbox" id="is_developer" type="checkbox" name="type[]"
                           value="developer"><label
                        for="is_developer" class="fs-subtitle">If you are a developer building a product using
                        Redux, click here.
                    </label>

                </p>

                <input type="button" name="previous" class="previous action-button" value="Previous"/>
                <input type="button" name="next" class="next action-button" value="Next" disabled="disabled"/>
            </fieldset>
            <fieldset>
                <h2 class="fs-title">Finalize Support</h2>

                <div class="is_developer">
                    <h3 class="fs-subtitle" style="text-align: center;">
                        Hello Redux Developer! Please head over to our issue tracker, and provide us with the following hash URL: <br />
                        <a href="https://github.com/reduxframework/redux-framework/issues">https://github.com/reduxframework/redux-framework/issues</a><br />
                        Provide us with details about your issue as well as the following support hash code:
                        <input type="text" name="hash" placeholder="Support Hash" disabled="disabled"
                               class="hash<?php echo ! isset( $generate_hash ) ? '' : ' hide'; ?>"
                               value="http://support.redux.io/sdfjDSKF"/>
                    </h3>

                </div>


                <input type="button" name="previous" class="previous action-button" value="Previous"/>
            </fieldset>
        </form>


        <div class="clear" style="clear:both;"></div>
    </div>

</div>

<!-- jQuery easing plugin -->
<script src="http://thecodeplayer.com/uploads/js/jquery.easing.min.js" type="text/javascript"></script>

<style>
    #support_div .hide {
        display: none;
    }

    #support_div .previous {
        opacity: .8;
    }

    #msform .action-button:disabled {
        opacity: .5;
    }

    /*form styles*/
    #msform {
        /*width: 500px;*/
        margin: 10px auto;
        text-align: center;
        position: relative;
    }

    #msform fieldset {
        background: white;
        border: 0 none;
        border-radius: 3px;
        box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.4);
        padding: 20px 30px;

        box-sizing: border-box;
        width: 86%;
        margin: 0 7%;
        /*stacking fieldsets above each other*/
        position: absolute;
    }

    /*Hide all except first fieldset*/
    #msform fieldset:not(:first-of-type) {
        display: none;
    }

    /*inputs*/
    #msform input, #msform textarea {
        padding: 15px;
        border: 1px solid #ccc;
        border-radius: 3px;
        margin-bottom: 10px;
        width: 100%;
        box-sizing: border-box;
        font-family: montserrat;
        color: #2C3E50;
        font-size: 13px;
    }

    #msform input.checkbox {
        width: initial;
        margin-top: 10px;
    }

    /*buttons*/
    #msform .action-button {
        width: 100px;
        background: #27AE60;
        font-weight: bold;
        color: white;
        border: 0 none;
        border-radius: 1px;
        cursor: pointer;
        padding: 10px 5px;
        margin: 10px 5px;
    }

    #msform .action-button:hover, #msform .action-button:focus {
        box-shadow: 0 0 0 2px white, 0 0 0 3px #27AE60;
    }

    /*headings*/
    .fs-title {
        font-size: 15px;
        text-transform: uppercase;
        color: #2C3E50;
        margin-bottom: 10px;
    }

    .fs-subtitle {
        font-weight: normal;
        font-size: 13px;
        text-align: left;
        color: #666;
        margin-bottom: 20px;
    }

    /*progressbar*/
    /*#progressbar {*/
        /*margin-bottom: 30px;*/
        /*overflow: hidden;*/
        /*CSS counters to number the steps*/
        /*counter-reset: step;*/
    /*}*/

    /*#progressbar li {*/
        /*list-style-type: none;*/
        /*color: white;*/
        /*text-transform: uppercase;*/
        /*font-size: 9px;*/
        /*width: 33.33%;*/
        /*float: left;*/
        /*position: relative;*/
    /*}*/

    /*#progressbar li:before {*/
        /*content: counter(step);*/
        /*counter-increment: step;*/
        /*width: 20px;*/
        /*line-height: 20px;*/
        /*display: block;*/
        /*font-size: 10px;*/
        /*color: #333;*/
        /*background: white;*/
        /*border-radius: 3px;*/
        /*margin: 0 auto 5px auto;*/
    /*}*/

    /*progressbar connectors*/
    /*#progressbar li:after {*/
        /*content: '';*/
        /*width: 100%;*/
        /*height: 2px;*/
        /*background: white;*/
        /*position: absolute;*/
        /*left: -50%;*/
        /*top: 9px;*/
        /*z-index: -1; *//*put it behind the numbers*/
    /*}*/

    /*#progressbar li:first-child:after {*/
        /*connector not needed before the first step*/
        /*content: none;*/
    /*}*/

    /*marking active/completed steps green*/
    /*The number of the step and the connector before it = green*/
    /*#progressbar li.active:before, #progressbar li.active:after {*/
        /*background: #27AE60;*/
        /*color: white;*/
    /*}*/

    #toDebug li {
        text-align: left;
        width: 45%;
        float: left;
    }
</style>
<script type="text/javascript">
    jQuery( document ).ready(
        function() {
            /*
             Orginal Page: http://thecodeplayer.com/walkthrough/jquery-multi-step-form-with-progress-bar

             */
            //jQuery time
            var current_fs, next_fs, previous_fs; //fieldsets
            var left, opacity, scale; //fieldset properties which we will animate
            var animating; //flag to prevent quick multi-click glitches

            jQuery('#support_div #is_developer').change(function() {
                if(this.checked || jQuery(this ).checked()) {
                    jQuery('#support_div input.plugins' ).removeAttr('checked');
                    jQuery('#support_div input.theme' ).removeAttr('checked');
                    jQuery(this ).parents('fieldset:first' ).find('.next' ).removeAttr('disabled');
                    jQuery(this ).parents('fieldset:first' ).find('.next' ).click();
                }
            });

            jQuery('#support_div input.checkbox').change(function() {
                if (jQuery("#support_div input.checkbox:checked").length > 0) {
                    jQuery(this ).parents('fieldset:first' ).find('.next' ).removeAttr('disabled');
                } else {
                    jQuery(this ).parents('fieldset:first' ).find('.next' ).attr('disabled', 'disabled');
                }
            });

            jQuery( "#support_div .next" ).click(
                function() {
                    if ( animating ) return false;
                    animating = true;

                    current_fs = jQuery( this ).parent();
                    next_fs = jQuery( this ).parent().next();

                    //activate next step on progressbar using the index of next_fs
                    jQuery( "#progressbar li" ).eq( jQuery( "fieldset" ).index( next_fs ) ).addClass( "active" );

                    //show the next fieldset
                    next_fs.show();
                    //hide the current fieldset with style
                    current_fs.animate(
                        {opacity: 0}, {
                            step: function( now, mx ) {
                                //as the opacity of current_fs reduces to 0 - stored in "now"
                                //1. scale current_fs down to 80%
                                scale = 1 - (1 - now) * 0.2;
                                //2. bring next_fs from the right(50%)
                                left = (now * 50) + "%";
                                //3. increase opacity of next_fs to 1 as it moves in
                                opacity = 1 - now;
                                current_fs.css( {'transform': 'scale(' + scale + ')'} );
                                next_fs.css( {'left': left, 'opacity': opacity} );
                            },
                            duration: 800,
                            complete: function() {
                                current_fs.hide();
                                animating = false;
                            },
                            //this comes from the custom easing plugin
                            easing: 'easeInOutBack'
                        }
                    );
                }
            );

            jQuery( "#support_div .previous" ).click(
                function() {
                    if ( animating ) return false;
                    animating = true;

                    current_fs = jQuery( this ).parent();
                    previous_fs = jQuery( this ).parent().prev();

                    //de-activate current step on progressbar
                    jQuery( "#progressbar li" ).eq( jQuery( "fieldset" ).index( current_fs ) ).removeClass( "active" );

                    //show the previous fieldset
                    previous_fs.show();
                    //hide the current fieldset with style
                    current_fs.animate(
                        {opacity: 0}, {
                            step: function( now, mx ) {
                                //as the opacity of current_fs reduces to 0 - stored in "now"
                                //1. scale previous_fs from 80% to 100%
                                scale = 0.8 + (1 - now) * 0.2;
                                //2. take current_fs to the right(50%) - from 0%
                                left = ((1 - now) * 50) + "%";
                                //3. increase opacity of previous_fs to 1 as it moves in
                                opacity = 1 - now;
                                current_fs.css( {'left': left} );
                                previous_fs.css( {'transform': 'scale(' + scale + ')', 'opacity': opacity} );
                            },
                            duration: 800,
                            complete: function() {
                                current_fs.hide();
                                animating = false;
                            },
                            //this comes from the custom easing plugin
                            easing: 'easeInOutBack'
                        }
                    );
                }
            );

            jQuery( ".submit" ).click(
                function() {
                    return false;
                }
            )
        }
    );
</script>




<!-- another version - flat style with animated hover effect -->

<style>



    .breadcrumb {
        /*centering*/
        display: inline-block;
        box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.35);
        overflow: hidden;
        border-radius: 5px;
        /*Lets add the numbers for each link using CSS counters. flag is the name of the counter. to be defined using counter-reset in the parent element of the links*/
        counter-reset: flag;
    }

    .breadcrumb li {
        text-decoration: none;
        outline: none;
        display: block;
        float: left;
        font-size: 12px;
        transition: all 0.5s;
        width: auto;
        margin:0;
        line-height: 36px;
        color: white;
        /*need more margin on the left of links to accomodate the numbers*/
        padding: 0 10px 0 60px;
        background: #666;
        background: linear-gradient(#666, #333);
        position: relative;
    }
    /*since the first link does not have a triangle before it we can reduce the left padding to make it look consistent with other links*/
    .breadcrumb li:first-child {
        padding-left: 46px;
        border-radius: 5px 0 0 5px; /*to match with the parent's radius*/
    }
    .breadcrumb li:first-child:before {
        left: 14px;
    }
    .breadcrumb li:last-child {
        border-radius: 0 5px 5px 0; /*this was to prevent glitches on hover*/
        padding-right: 20px;
    }

    /*hover/active styles*/
    .breadcrumb li.active, .breadcrumb li:hover{
        background: #333;
        background: linear-gradient(#333, #000);
        transition: all 0.5s;
    }
    .breadcrumb li.active:after, .breadcrumb li:hover:after {
        background: #333;
        background: linear-gradient(135deg, #333, #000);
        transition: all 0.5s;
    }

    /*adding the arrows for the breadcrumbs using rotated pseudo elements*/
    .breadcrumb li:after {
        content: '';
        position: absolute;
        top: 0;
        right: -18px; /*half of square's length*/
        /*same dimension as the line-height of .breadcrumb a */
        width: 36px;
        height: 36px;
        /*as you see the rotated square takes a larger height. which makes it tough to position it properly. So we are going to scale it down so that the diagonals become equal to the line-height of the link. We scale it to 70.7% because if square's:
        length = 1; diagonal = (1^2 + 1^2)^0.5 = 1.414 (pythagoras theorem)
        if diagonal required = 1; length = 1/1.414 = 0.707*/
        transform: scale(0.707) rotate(45deg);
        /*we need to prevent the arrows from getting buried under the next link*/
        z-index: 1;
        /*background same as links but the gradient will be rotated to compensate with the transform applied*/
        background: #666;
        background: linear-gradient(135deg, #666, #333);
        /*stylish arrow design using box shadow*/
        box-shadow:
        2px -2px 0 2px rgba(0, 0, 0, 0.4),
        3px -3px 0 2px rgba(255, 255, 255, 0.1);
        /*
            5px - for rounded arrows and
            50px - to prevent hover glitches on the border created using shadows*/
        border-radius: 0 5px 0 50px;
    }
    /*we dont need an arrow after the last link*/
    .breadcrumb li:last-child:after {
        content: none;
    }
    /*we will use the :before element to show numbers*/
    .breadcrumb li:before {
        content: counter(flag);
        counter-increment: flag;
        /*some styles now*/
        border-radius: 100%;
        width: 20px;
        height: 20px;
        line-height: 20px;
        margin: 8px 0;
        position: absolute;
        top: 0;
        left: 30px;
        background: #444;
        background: linear-gradient(#444, #222);
        font-weight: bold;
    }


    .flat li, .flat li:after {
        background: white;
        color: black;
        transition: all 0.5s;
    }
    .flat li:before {
        background: white;
        box-shadow: 0 0 0 1px #ccc;
    }
    .flat li:hover, .flat li.active,
    .flat li:hover:after, .flat li.active:after{
        background: #9EEB62;
    }
</style>