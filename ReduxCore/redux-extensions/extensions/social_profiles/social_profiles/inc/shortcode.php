<?php

if ( !class_exists ( 'reduxSocialProfilesShortcode' ) ) {

    class reduxSocialProfilesShortcode {

        private $parent     = null;
        private $field_id   = '';

        public function __construct ($parent, $field_id) {
            $this->parent   = $parent;
            $this->field_id = $field_id;

            add_shortcode ( 'social_profiles', array( $this, 'redux_social_profiles' ) );
        }

        public function redux_social_profiles ( $atts, $content = null ) {
//            extract ( shortcode_atts ( array(
//                "type" => "info"
//                            ), $atts ) );

            //return '<div class="alert ' . $type . '">' . do_shortcode ( $content ) . '</div>';
            $redux_options = get_option($this->parent->args['opt_name']);
            $social_items   = $redux_options[$this->field_id];

            $html = '';

            $html .= '<ul class="redux-social-media-list clearfix">';
            if ( is_array ( $social_items ) ) {
                foreach ( $social_items as $key => $social_item ) {
                    if ( $social_item[ 'enabled' ] ) {
                        $icon           = $social_item[ 'icon' ];
                        $color          = $social_item[ 'color' ];
                        $background     = $social_item[ 'background' ];
                        $base_url       = $social_item[ 'url' ];
                        $id             = $social_item['id'];

                        $url = apply_filters('redux/extensions/social_profiles/' . $this->parent->args['opt_name'] . '/icon_url', $id, $base_url);

                        $html .= '<li style="list-style: none;">';
                        $html .= "<a href='" . $url . "'>";
                        $html .= reduxSocialProfilesFunctions::render_icon ( $icon, $color, $background, '', false );
                        $html .= "</a>";
                        $html .= "</li>";
                    }
                }
            }
            $html .= '</ul>';

            return $html;
        }
    }
}
