<?php

    if ( ! class_exists( 'Redux_Validation_css' ) ) {
        class Redux_Validation_css {

            /**
             * Field Constructor.
             * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
             *
             * @since ReduxFramework 1.0.0
             */
            function __construct( $parent, $field, $value, $current ) {

                $this->parent  = $parent;
                $this->field   = $field;
                $this->value   = $value;
                $this->current = $current;

                $this->validate();
            }

            //function

            /**
             * Field Render Function.
             * Takes the vars and validates them
             *
             * @since ReduxFramework 3.0.0
             */
            function validate() {

                require_once( dirname( __FILE__ ) . '/csstidy/class.csstidy.php' );

                $csstidy = new csstidy();

                $csstidy->set_cfg( 'remove_bslash', false );
                $csstidy->set_cfg( 'compress_colors', false );
                $csstidy->set_cfg( 'compress_font-weight', false );
                $csstidy->set_cfg( 'optimise_shorthands', 0 );
                $csstidy->set_cfg( 'remove_last_;', false );
                $csstidy->set_cfg( 'case_properties', false );
                $csstidy->set_cfg( 'discard_invalid_properties', true );
                $csstidy->set_cfg( 'css_level', 'CSS3.0' );
                $csstidy->set_cfg( 'preserve_css', true );
                $csstidy->set_cfg( 'template', dirname( __FILE__ ) . '/csstidy/wordpress-standard.tpl' );

                $css = $orig = $this->value;

                $css = preg_replace( '/\\\\([0-9a-fA-F]{4})/', '\\\\\\\\$1', $prev = $css );

                if ( $css != $prev ) {
                    $this->warning = true;
                }

                // Some people put weird stuff in their CSS, KSES tends to be greedy
                $css = str_replace( '<=', '&lt;=', $css );
                // Why KSES instead of strip_tags?  Who knows?
                $css = wp_kses_split( $prev = $css, array(), array() );
                $css = str_replace( '&gt;', '>', $css ); // kses replaces lone '>' with &gt;
                // Why both KSES and strip_tags?  Because we just added some '>'.
                $css = strip_tags( $css );

                if ( $css != $prev ) {
                    $this->warning = true;
                }

                $csstidy->parse( $css );
                $this->value = $csstidy->print->plain();

                if ( isset( $this->warning ) && $this->warning ) {
                    $this->warning = __( 'Unsafe strings were found in your CSS and have been filtered out.', 'redux-framework' );
                }

            } //function
        } //class
    }