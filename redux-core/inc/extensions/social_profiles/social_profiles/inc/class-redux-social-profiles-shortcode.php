<?php
/**
 * Social Profiles Shortcode Class.
 *
 * @package     Redux
 * @subpackage  Extensions
 * @author      Kevin Provance (kprovance)
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Social_Profiles_Shortcode' ) ) {

	/**
	 * Class Redux_Social_Profiles_Shortcode
	 */
	class Redux_Social_Profiles_Shortcode {

		/**
		 * ReduxFramework object pointer.
		 *
		 * @var null
		 */
		private $parent;

		/**
		 * Field ID.
		 *
		 * @var string
		 */
		private $field_id;

		/**
		 * Redux_Social_Profiles_Shortcode constructor.
		 *
		 * @param object $parent   ReduxFramework object.
		 * @param string $field_id Field ID.
		 */
		public function __construct( $parent, string $field_id ) {
			$this->parent   = $parent;
			$this->field_id = $field_id;

			add_shortcode( 'social_profiles', array( $this, 'redux_social_profiles' ) );
		}

		/**
		 * Render shortcode.
		 *
		 * @param array|string $atts    Shortcode attributes.
		 * @param null         $content Shortcode content.
		 *
		 * @return string
		 */
		public function redux_social_profiles( $atts, $content = null ): string {
			$redux_options = get_option( $this->parent->args['opt_name'] );
			$social_items  = $redux_options[ $this->field_id ];

			$html = '<ul class="redux-social-media-list clearfix">';

			if ( is_array( $social_items ) ) {
				foreach ( $social_items as $social_item ) {
					if ( $social_item['enabled'] ) {
						$icon       = $social_item['icon'];
						$color      = $social_item['color'];
						$background = $social_item['background'];
						$base_url   = $social_item['url'];
						$id         = $social_item['id'];

						// phpcs:ignore WordPress.NamingConventions.ValidHookName
						$url = apply_filters( 'redux/extensions/social_profiles/' . $this->parent->args['opt_name'] . '/icon_url', $id, $base_url );

						$html .= '<li style="list-style: none;">';
						$html .= "<a href='" . $url . "'>";
						$html .= Redux_Social_Profiles_Functions::render_icon( $icon, $color, $background, '', false );
						$html .= '</a>';
						$html .= '</li>';
					}
				}
			}
			$html .= '</ul>';

			return $html;
		}
	}
}
