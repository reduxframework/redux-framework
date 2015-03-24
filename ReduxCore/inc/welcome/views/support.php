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
        <form id="supportform">

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
            <fieldset id="final_support">
                <h2 class="fs-title">Finalize Support</h2>

                <div class="is_developer">
                    <h3 class="fs-subtitle" style="text-align: center;">
                        Hello Redux Developer! Please head over to our issue tracker, and provide us with the following
                        hash URL: <br/>
                        <a href="https://github.com/reduxframework/redux-framework/issues">https://github.com/reduxframework/redux-framework/issues</a><br/>
                        Provide us with details about your issue as well as the following support hash code:
                        <input type="text" name="hash" placeholder="Support Hash" disabled="disabled"
                               class="hash<?php echo ! isset( $generate_hash ) ? '' : ' hide'; ?>"
                               value="http://support.redux.io/sdfjDSKF"/>
                    </h3>

                </div>
                <div class="is_user">
                    <h3 class="fs-subtitle" style="text-align: center;">
                        Hello WordPress User! <br/>
                        <a href="https://github.com/reduxframework/redux-framework/issues">https://github.com/reduxframework/redux-framework/issues</a><br/>
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