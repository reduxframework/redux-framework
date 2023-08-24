<?php
/**
 * Redux Social Profiles Helpers
 *
 * @package Redux
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'redux_social_profile_value_from_id' ) ) {
	/**
	 * Returns social profile value from passed profile ID.
	 *
	 * @param string $opt_name Redux Framework opt_name.
	 * @param string $id       Profile ID.
	 * @param string $value    Social profile value to return (icon, name, background, color, url, or order).
	 *
	 * @return      string Returns HTML string when $echo is set to false.  Otherwise, true.
	 * @since       1.0.0
	 * @access      public
	 */
	function redux_social_profile_value_from_id( string $opt_name, string $id, string $value ): string {
		if ( empty( $opt_name ) || empty( $id ) || empty( $value ) ) {
			return '';
		}

		$redux           = ReduxFrameworkInstances::get_instance( $opt_name );
		$social_profiles = $redux->extensions['social_profiles'];

		$redux_options = get_option( $social_profiles->opt_name );
		$settings      = $redux_options[ $social_profiles->field_id ];

		foreach ( $settings as $arr ) {
			if ( $id === $arr['id'] ) {
				if ( $arr['enabled'] ) {
					if ( isset( $arr[ $value ] ) ) {
						return $arr[ $value ];
					}
				} else {
					return '';
				}
			}
		}

		return '';
	}
}

if ( ! function_exists( 'redux_render_icon_from_id' ) ) {
	/**
	 * Renders social icon from passed profile ID.
	 *
	 * @param string  $opt_name Redux Framework opt_name.
	 * @param string  $id       Profile ID.
	 * @param boolean $output   Echos icon HTML when true.  Returns icon HTML when false.
	 * @param string  $a_class  Class name for a tag.
	 *
	 * @return      string Returns HTML string when $echo is set to false.  Otherwise, true.
	 * @since       1.0.0
	 * @access      public
	 */
	function redux_render_icon_from_id( string $opt_name, string $id, bool $output = true, string $a_class = '' ) {
		if ( empty( $opt_name ) || empty( $id ) ) {
			return '';
		}

		include_once 'social_profiles/inc/class-redux-social-profiles-functions.php';

		$redux           = ReduxFrameworkInstances::get_instance( $opt_name );
		$social_profiles = $redux->extensions['social_profiles'];

		$redux_options = get_option( $social_profiles->opt_name );
		$settings      = $redux_options[ $social_profiles->field_id ];

		foreach ( $settings as $arr ) {
			if ( $id === $arr['id'] ) {
				if ( $arr['enabled'] ) {

					if ( $output ) {
						echo '<a class="' . esc_attr( $a_class ) . '" href="' . esc_url( $arr['url'] ) . '">';
						Redux_Social_Profiles_Functions::render_icon( $arr['icon'], $arr['color'], $arr['background'], '' );
						echo '</a>';

						return true;
					} else {
						$html = '<a class="' . $a_class . '"href="' . $arr['url'] . '">';

						$html .= Redux_Social_Profiles_Functions::render_icon( $arr['icon'], $arr['color'], $arr['background'], '', false );
						$html .= '</a>';

						return $html;
					}
				}
			}
		}

		return '';
	}
}
