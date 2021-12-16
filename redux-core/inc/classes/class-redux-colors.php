<?php
/**
 * Redux Color Manipulator Class
 *
 * @class Redux_Core
 * @version 4.0.0
 * @package Redux Framework
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux_Colors', false ) ) {

	/**
	 * Class Redux_Colors
	 */
	class Redux_Colors extends Redux_Class {

		/**
		 * Sanitises a HEX value.
		 * The way this works is by splitting the string in 6 substrings.
		 * Each sub-string is individually sanitised, and the result is then returned.
		 *
		 * @param string  $color The hex value of a color.
		 * @param boolean $hash  Whether we want to include a hash (#) at the beginning or not.
		 *
		 * @return  string      The sanitized hex color.
		 */
		public static function sanitize_hex( string $color = '#FFFFFF', bool $hash = true ): string {
			$word_colors = array(
				'aliceblue'            => 'F0F8FF',
				'antiquewhite'         => 'FAEBD7',
				'aqua'                 => '00FFFF',
				'aquamarine'           => '7FFFD4',
				'azure'                => 'F0FFFF',
				'beige'                => 'F5F5DC',
				'bisque'               => 'FFE4C4',
				'black'                => '000000',
				'blanchedalmond'       => 'FFEBCD',
				'blue'                 => '0000FF',
				'blueviolet'           => '8A2BE2',
				'brown'                => 'A52A2A',
				'burlywood'            => 'DEB887',
				'cadetblue'            => '5F9EA0',
				'chartreuse'           => '7FFF00',
				'chocolate'            => 'D2691E',
				'coral'                => 'FF7F50',
				'cornflowerblue'       => '6495ED',
				'cornsilk'             => 'FFF8DC',
				'crimson'              => 'DC143C',
				'cyan'                 => '00FFFF',
				'darkblue'             => '00008B',
				'darkcyan'             => '008B8B',
				'darkgoldenrod'        => 'B8860B',
				'darkgray'             => 'A9A9A9',
				'darkgreen'            => '006400',
				'darkgrey'             => 'A9A9A9',
				'darkkhaki'            => 'BDB76B',
				'darkmagenta'          => '8B008B',
				'darkolivegreen'       => '556B2F',
				'darkorange'           => 'FF8C00',
				'darkorchid'           => '9932CC',
				'darkred'              => '8B0000',
				'darksalmon'           => 'E9967A',
				'darkseagreen'         => '8FBC8F',
				'darkslateblue'        => '483D8B',
				'darkslategray'        => '2F4F4F',
				'darkslategrey'        => '2F4F4F',
				'darkturquoise'        => '00CED1',
				'darkviolet'           => '9400D3',
				'deeppink'             => 'FF1493',
				'deepskyblue'          => '00BFFF',
				'dimgray'              => '696969',
				'dimgrey'              => '696969',
				'dodgerblue'           => '1E90FF',
				'firebrick'            => 'B22222',
				'floralwhite'          => 'FFFAF0',
				'forestgreen'          => '228B22',
				'fuchsia'              => 'FF00FF',
				'gainsboro'            => 'DCDCDC',
				'ghostwhite'           => 'F8F8FF',
				'gold'                 => 'FFD700',
				'goldenrod'            => 'DAA520',
				'gray'                 => '808080',
				'green'                => '008000',
				'greenyellow'          => 'ADFF2F',
				'grey'                 => '808080',
				'honeydew'             => 'F0FFF0',
				'hotpink'              => 'FF69B4',
				'indianred'            => 'CD5C5C',
				'indigo'               => '4B0082',
				'ivory'                => 'FFFFF0',
				'khaki'                => 'F0E68C',
				'lavender'             => 'E6E6FA',
				'lavenderblush'        => 'FFF0F5',
				'lawngreen'            => '7CFC00',
				'lemonchiffon'         => 'FFFACD',
				'lightblue'            => 'ADD8E6',
				'lightcoral'           => 'F08080',
				'lightcyan'            => 'E0FFFF',
				'lightgoldenrodyellow' => 'FAFAD2',
				'lightgray'            => 'D3D3D3',
				'lightgreen'           => '90EE90',
				'lightgrey'            => 'D3D3D3',
				'lightpink'            => 'FFB6C1',
				'lightsalmon'          => 'FFA07A',
				'lightseagreen'        => '20B2AA',
				'lightskyblue'         => '87CEFA',
				'lightslategray'       => '778899',
				'lightslategrey'       => '778899',
				'lightsteelblue'       => 'B0C4DE',
				'lightyellow'          => 'FFFFE0',
				'lime'                 => '00FF00',
				'limegreen'            => '32CD32',
				'linen'                => 'FAF0E6',
				'magenta'              => 'FF00FF',
				'maroon'               => '800000',
				'mediumaquamarine'     => '66CDAA',
				'mediumblue'           => '0000CD',
				'mediumorchid'         => 'BA55D3',
				'mediumpurple'         => '9370D0',
				'mediumseagreen'       => '3CB371',
				'mediumslateblue'      => '7B68EE',
				'mediumspringgreen'    => '00FA9A',
				'mediumturquoise'      => '48D1CC',
				'mediumvioletred'      => 'C71585',
				'midnightblue'         => '191970',
				'mintcream'            => 'F5FFFA',
				'mistyrose'            => 'FFE4E1',
				'moccasin'             => 'FFE4B5',
				'navajowhite'          => 'FFDEAD',
				'navy'                 => '000080',
				'oldlace'              => 'FDF5E6',
				'olive'                => '808000',
				'olivedrab'            => '6B8E23',
				'orange'               => 'FFA500',
				'orangered'            => 'FF4500',
				'orchid'               => 'DA70D6',
				'palegoldenrod'        => 'EEE8AA',
				'palegreen'            => '98FB98',
				'paleturquoise'        => 'AFEEEE',
				'palevioletred'        => 'DB7093',
				'papayawhip'           => 'FFEFD5',
				'peachpuff'            => 'FFDAB9',
				'peru'                 => 'CD853F',
				'pink'                 => 'FFC0CB',
				'plum'                 => 'DDA0DD',
				'powderblue'           => 'B0E0E6',
				'purple'               => '800080',
				'red'                  => 'FF0000',
				'rosybrown'            => 'BC8F8F',
				'royalblue'            => '4169E1',
				'saddlebrown'          => '8B4513',
				'salmon'               => 'FA8072',
				'sandybrown'           => 'F4A460',
				'seagreen'             => '2E8B57',
				'seashell'             => 'FFF5EE',
				'sienna'               => 'A0522D',
				'silver'               => 'C0C0C0',
				'skyblue'              => '87CEEB',
				'slateblue'            => '6A5ACD',
				'slategray'            => '708090',
				'slategrey'            => '708090',
				'snow'                 => 'FFFAFA',
				'springgreen'          => '00FF7F',
				'steelblue'            => '4682B4',
				'tan'                  => 'D2B48C',
				'teal'                 => '008080',
				'thistle'              => 'D8BFD8',
				'tomato'               => 'FF6347',
				'turquoise'            => '40E0D0',
				'violet'               => 'EE82EE',
				'wheat'                => 'F5DEB3',
				'white'                => 'FFFFFF',
				'whitesmoke'           => 'F5F5F5',
				'yellow'               => 'FFFF00',
				'yellowgreen'          => '9ACD32',
			);

			if ( is_array( $color ) ) {
				$color = $color[0];
			}

			// Remove any spaces and special characters before and after the string.
			$color = trim( $color );

			// Check if the color is a standard word-color.
			// If it is, then convert to hex.
			if ( array_key_exists( $color, $word_colors ) ) {
				$color = $word_colors[ $color ];
			}

			// Remove any trailing '#' symbols from the color value.
			$color = str_replace( '#', '', $color );

			// If the string is 6 characters long then use it in pairs.
			if ( 3 === strlen( $color ) ) {
				$color = substr( $color, 0, 1 ) . substr( $color, 0, 1 ) . substr( $color, 1, 1 ) . substr( $color, 1, 1 ) . substr( $color, 2, 1 ) . substr( $color, 2, 1 );
			}

			$substr = array();
			for ( $i = 0; $i <= 5; $i ++ ) {
				$default      = ( 0 === $i ) ? 'F' : ( $substr[ $i - 1 ] );
				$substr[ $i ] = substr( $color, $i, 1 );
				$substr[ $i ] = ( false === $substr[ $i ] || ! ctype_xdigit( $substr[ $i ] ) ) ? $default : $substr[ $i ];
			}

			$hex = implode( '', $substr );

			return ( ! $hash ) ? $hex : '#' . $hex;
		}

		/**
		 * Checks if string is a hex.
		 *
		 * @param string $hex_code Hex string.
		 *
		 * @return bool
		 */
		public static function is_hex( string $hex_code = '' ): bool {
			// phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
			return @preg_match( '/^[a-f0-9]{2,}$/i', strtolower( $hex_code ) ) && ! ( strlen( $hex_code ) & 1 );
		}

		/**
		 * Sanitizes RGBA color.
		 *
		 * @param string $value RGBA value.
		 *
		 * @return string
		 */
		public static function sanitize_rgba( string $value ): string {
			// If empty or an array return transparent.
			if ( empty( $value ) ) {
				return 'rgba(0,0,0,0)';
			}

			// If string does not start with 'rgba', then treat as hex
			// sanitize the hex color and finally convert hex to rgba.
			if ( false === strpos( $value, 'rgba' ) ) {
				return self::get_rgba( self::sanitize_hex( $value ) );
			}

			// By now we know the string is formatted as a rgba color, so we can just return it.
			$value = str_replace( array( ' ', 'rgba', '(', ')' ), '', $value );
			$value = explode( ',', $value );
			$red   = ( isset( $value[0] ) ) ? intval( $value[0] ) : 255;
			$green = ( isset( $value[1] ) ) ? intval( $value[1] ) : 255;
			$blue  = ( isset( $value[2] ) ) ? intval( $value[2] ) : 255;
			$alpha = ( isset( $value[3] ) ) ? filter_var( $value[3], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) : 1;

			return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
		}

		/**
		 * Sanitize colors.
		 * Determine if the current value is a hex or a rgba color and call the appropriate method.
		 *
		 * @param  string|array $value  hex or rgba color.
		 *
		 * @return string
		 * @since 0.8.5
		 */
		public static function sanitize_color( $value ): string {
			if ( is_array( $value ) ) {
				if ( isset( $value['rgba'] ) ) {
					$value = $value['rgba'];
				} elseif ( isset( $value['color'] ) ) {
					$opacity = ( isset( $value['opacity'] ) ) ? $value['opacity'] : null;
					$opacity = ( ! is_null( $opacity ) && isset( $value['alpha'] ) ) ? $value['alpha'] : null;
					$opacity = ( is_null( $opacity ) ) ? 1 : floatval( $opacity );

					$value = self::get_rgba( $value['color'], $opacity );
				} else {
					return '';
				}
			}

			if ( 'transparent' === $value ) {
				return 'transparent';
			}

			// Is this a rgba color or a hex?
			$mode = ( false === strpos( $value, 'rgba' ) ) ? 'rgba' : 'hex';

			if ( 'rgba' === $mode ) {
				return self::sanitize_hex( $value );
			} else {
				return self::sanitize_rgba( $value );
			}
		}

		/**
		 * Gets the rgb value of the $hex color.
		 *
		 * @param string  $hex     The hex value of a color.
		 * @param boolean $implode Whether we want to implode the values or not.
		 *
		 * @return  array|string       array|string
		 */
		public static function get_rgb( string $hex, bool $implode = false ) {
			// Remove any trailing '#' symbols from the color value.
			$hex = self::sanitize_hex( $hex, false );

			// rgb is an array.
			$rgb = array(
				hexdec( substr( $hex, 0, 2 ) ),
				hexdec( substr( $hex, 2, 2 ) ),
				hexdec( substr( $hex, 4, 2 ) ),
			);

			return ( $implode ) ? implode( ',', $rgb ) : $rgb;
		}

		/**
		 * Converts a rgba color to hex
		 * This is an approximation and not completely accurate.
		 *
		 * @param string $color         RGBA color.
		 * @param bool   $apply_opacity Opacity value.
		 *
		 * @return  string  The hex value of the color.
		 */
		public static function rgba2hex( string $color, bool $apply_opacity = false ): string {
			// Remove parts of the string.
			$color = str_replace( array( 'rgba', '(', ')', ' ' ), '', $color );

			if ( is_array( $color ) ) {
				return ( isset( $color['color'] ) ) ? $color['color'] : '#ffffff';
			}

			// if not rgba, sanitize as HEX.
			if ( false !== strpos( $color, '#' ) ) {
				return self::sanitize_hex( $color );
			}

			// Convert to array.
			$color = explode( ',', $color );

			// This is not a valid rgba definition, so return white.
			if ( 4 !== count( $color ) ) {
				return '#ffffff';
			}

			// Convert dec. to hex.
			$red   = dechex( (int) $color[0] );
			$green = dechex( (int) $color[1] );
			$blue  = dechex( (int) $color[2] );
			$alpha = $color[3];

			// Make sure all colors are 2 digits.
			$red   = ( 1 === strlen( $red ) ) ? '0' . $red : $red;
			$green = ( 1 === strlen( $green ) ) ? '0' . $green : $green;
			$blue  = ( 1 === strlen( $blue ) ) ? '0' . $blue : $blue;

			// Combine hex parts.
			$hex = $red . $green . $blue;
			if ( $apply_opacity ) {
				// Get the opacity value on a 0-100 basis instead of 0-1.
				$mix_level = intval( $alpha * 100 );

				// Apply opacity - mix with white.
				$hex = self::mix_colors( $hex, '#ffffff', $mix_level );
			}

			return '#' . str_replace( '#', '', $hex );
		}

		/**
		 * Get the alpha channel from a rgba color
		 *
		 * @param string|array $color The rgba color formatted like rgba(r,g,b,a).
		 *
		 * @return  string  The alpha value of the color.
		 */
		public static function get_alpha_from_rgba( $color ) {
			if ( is_array( $color ) ) {
				if ( isset( $color['opacity'] ) ) {
					return $color['opacity'];
				} elseif ( isset( $color['alpha'] ) ) {
					return $color['alpha'];
				} else {
					return 1;
				}
			}

			if ( false === strpos( $color, 'rgba' ) ) {
				return '1';
			}

			// Remove parts of the string.
			$color = str_replace( array( 'rgba', '(', ')', ' ' ), '', $color );

			// Convert to array.
			$color = explode( ',', $color );

			if ( isset( $color[3] ) ) {
				return (string) $color[3];
			} else {
				return '1';
			}
		}

		/**
		 * Gets the rgb value of the $hex color.
		 *
		 * @param string $hex     The hex value of a color.
		 * @param int    $opacity Opacity level (1-100).
		 *
		 * @return  string
		 */
		public static function get_rgba( string $hex = '#fff', int $opacity = 100 ): string {
			$hex = self::sanitize_hex( $hex, false );

			/**
			 * Make sure that opacity is properly formatted :
			 * Set the opacity to 100 if a larger value has been entered by mistake.
			 * If a negative value is used, then set to 0.
			 * If an opacity value is entered in a decimal form (for example 0.25), then multiply by 100.
			 */
			if ( $opacity >= 100 ) {
				$opacity = 100;
			} elseif ( $opacity < 0 ) {
				$opacity = 0;
			} elseif ( $opacity <= 1 && 0 !== $opacity ) {
				$opacity = ( $opacity * 100 );
			}

			// Divide the opacity by 100 to end-up with a CSS value for the opacity.
			$opacity = ( $opacity / 100 );

			return 'rgba(' . self::get_rgb( $hex, true ) . ', ' . $opacity . ')';
		}

		/**
		 * Strips the alpha value from an RGBA color string.
		 *
		 * @param string $rgba The RGBA color string.
		 *
		 * @return  string            The corresponding RGB string.
		 */
		public static function rgba_to_rgb( string $rgba ): string {
			$rgba          = str_replace( ' ', '', $rgba );
			$rgba_array    = explode( ',', $rgba );
			$rgba_array[0] = str_replace( 'rgba(', '', $rgba_array[0] );

			if ( isset( $rgba_array[3] ) ) {
				unset( $rgba_array[3] );
			}

			return sprintf( 'rgb(%s)', implode( ',', $rgba_array ) );
		}

		/**
		 * Gets the brightness of the $hex color.
		 *
		 * @param string $hex The hex value of a color.
		 *
		 * @return  int         value between 0 and 255.
		 */
		public static function get_brightness( string $hex ): int {
			$hex = self::sanitize_hex( $hex, false );

			// returns brightness value from 0 to 255.
			return intval( ( ( hexdec( substr( $hex, 0, 2 ) ) * 299 ) + ( hexdec( substr( $hex, 2, 2 ) ) * 587 ) + ( hexdec( substr( $hex, 4, 2 ) ) * 114 ) ) / 1000 );
		}

		/**
		 * Adjusts brightness of the $hex color.
		 *
		 * @param string $hex   The hex value of a color.
		 * @param int    $steps A value between -255 (darken) and 255 (lighten).
		 *
		 * @return  string      returns hex color.
		 */
		public static function adjust_brightness( string $hex, int $steps ): string {
			$hex = self::sanitize_hex( $hex, false );

			// Steps should be between -255 and 255. Negative = darker, positive = lighter.
			$steps = max( - 255, min( 255, $steps ) );

			// Adjust number of steps and keep it inside 0 to 255.
			$red   = max( 0, min( 255, hexdec( substr( $hex, 0, 2 ) ) + $steps ) );
			$green = max( 0, min( 255, hexdec( substr( $hex, 2, 2 ) ) + $steps ) );
			$blue  = max( 0, min( 255, hexdec( substr( $hex, 4, 2 ) ) + $steps ) );

			$red_hex   = str_pad( dechex( $red ), 2, '0', STR_PAD_LEFT );
			$green_hex = str_pad( dechex( $green ), 2, '0', STR_PAD_LEFT );
			$blue_hex  = str_pad( dechex( $blue ), 2, '0', STR_PAD_LEFT );

			return self::sanitize_hex( $red_hex . $green_hex . $blue_hex );
		}

		/**
		 * Mixes 2 hex colors.
		 * the "percentage" variable is the percent of the first color
		 * to be used it the mix. default is 50 (equal mix).
		 *
		 * @param string $hex1       The hex value of color 1.
		 * @param string $hex2       The hex value of color 2.
		 * @param int    $percentage A value between 0 and 100.
		 *
		 * @return  string      returns hex color.
		 */
		public static function mix_colors( string $hex1, string $hex2, int $percentage ): string {
			$hex1 = self::sanitize_hex( $hex1, false );
			$hex2 = self::sanitize_hex( $hex2, false );

			$red   = ( $percentage * hexdec( substr( $hex1, 0, 2 ) ) + ( 100 - $percentage ) * hexdec( substr( $hex2, 0, 2 ) ) ) / 100;
			$green = ( $percentage * hexdec( substr( $hex1, 2, 2 ) ) + ( 100 - $percentage ) * hexdec( substr( $hex2, 2, 2 ) ) ) / 100;
			$blue  = ( $percentage * hexdec( substr( $hex1, 4, 2 ) ) + ( 100 - $percentage ) * hexdec( substr( $hex2, 4, 2 ) ) ) / 100;

			$red_hex   = str_pad( dechex( $red ), 2, '0', STR_PAD_LEFT );
			$green_hex = str_pad( dechex( $green ), 2, '0', STR_PAD_LEFT );
			$blue_hex  = str_pad( dechex( $blue ), 2, '0', STR_PAD_LEFT );

			return self::sanitize_hex( $red_hex . $green_hex . $blue_hex );
		}

		/**
		 * Convert hex color to hsv
		 *
		 * @param string $hex The hex value of color 1.
		 *
		 * @return  array       returns array( 'h', 's', 'v' ).
		 */
		public static function hex_to_hsv( string $hex ): array {
			$rgb = (array) self::get_rgb( self::sanitize_hex( $hex, false ) );

			return self::rgb_to_hsv( $rgb );
		}

		/**
		 * Convert hex color to hsv
		 *
		 * @param array $color The rgb color to convert array( 'r', 'g', 'b' ).
		 *
		 * @return  array       returns array( 'h', 's', 'v' ).
		 */
		public static function rgb_to_hsv( array $color = array() ): array {
			$var_r = ( $color[0] / 255 );
			$var_g = ( $color[1] / 255 );
			$var_b = ( $color[2] / 255 );

			$var_min = min( $var_r, $var_g, $var_b );
			$var_max = max( $var_r, $var_g, $var_b );
			$del_max = $var_max - $var_min;

			$h = 0;
			$s = 0;
			$v = $var_max;

			if ( 0 !== $del_max ) {
				$s = $del_max / $var_max;

				$del_r = ( ( ( $var_max - $var_r ) / 6 ) + ( $del_max / 2 ) ) / $del_max;
				$del_g = ( ( ( $var_max - $var_g ) / 6 ) + ( $del_max / 2 ) ) / $del_max;
				$del_b = ( ( ( $var_max - $var_b ) / 6 ) + ( $del_max / 2 ) ) / $del_max;

				if ( $var_r === $var_max ) {
					$h = $del_b - $del_g;
				} elseif ( $var_g === $var_max ) {
					$h = ( 1 / 3 ) + $del_r - $del_b;
				} elseif ( $var_b === $var_max ) {
					$h = ( 2 / 3 ) + $del_g - $del_r;
				}

				if ( $h < 0 ) {
					$h ++;
				}

				if ( $h > 1 ) {
					$h --;
				}
			}

			return array(
				'h' => round( $h, 2 ),
				's' => round( $s, 2 ),
				'v' => round( $v, 2 ),
			);
		}

		/**
		 * This is a very simple algorithm that works by summing up the differences between the three color components red, green and blue.
		 * A value higher than 500 is recommended for good readability.
		 *
		 * @param string $color_1 Base color.
		 * @param string $color_2 Color to compare.
		 *
		 * @return mixed
		 */
		public static function color_difference( string $color_1 = '#ffffff', string $color_2 = '#000000' ) {
			$color_1 = self::sanitize_hex( $color_1, false );
			$color_2 = self::sanitize_hex( $color_2, false );

			$color_1_rgb = self::get_rgb( $color_1 );
			$color_2_rgb = self::get_rgb( $color_2 );

			$r_diff = max( $color_1_rgb[0], $color_2_rgb[0] ) - min( $color_1_rgb[0], $color_2_rgb[0] );
			$g_diff = max( $color_1_rgb[1], $color_2_rgb[1] ) - min( $color_1_rgb[1], $color_2_rgb[1] );
			$b_diff = max( $color_1_rgb[2], $color_2_rgb[2] ) - min( $color_1_rgb[2], $color_2_rgb[2] );

			return $r_diff + $g_diff + $b_diff;
		}

		/**
		 * This function tries to compare the brightness of the colors.
		 * A return value of more than 125 is recommended.
		 * Combining it with the color_difference function above might make sense.
		 *
		 * @param string $color_1 Base color.
		 * @param string $color_2 Color to compare.
		 *
		 * @return int
		 */
		public static function brightness_difference( string $color_1 = '#ffffff', string $color_2 = '#000000' ): int {
			$color_1 = self::sanitize_hex( $color_1, false );
			$color_2 = self::sanitize_hex( $color_2, false );

			$color_1_rgb = self::get_rgb( $color_1 );
			$color_2_rgb = self::get_rgb( $color_2 );

			$br_1 = ( 299 * $color_1_rgb[0] + 587 * $color_1_rgb[1] + 114 * $color_1_rgb[2] ) / 1000;
			$br_2 = ( 299 * $color_2_rgb[0] + 587 * $color_2_rgb[1] + 114 * $color_2_rgb[2] ) / 1000;

			return intval( abs( $br_1 - $br_2 ) );
		}

		/**
		 * Uses the luminosity to calculate the difference between the given colors.
		 * The returned value should be bigger than 5 for best readability.
		 *
		 * @param string $color_1 Base color.
		 * @param string $color_2 Color to compare.
		 *
		 * @return float
		 */
		public static function lumosity_difference( string $color_1 = '#ffffff', string $color_2 = '#000000' ): float {
			$color_1 = self::sanitize_hex( $color_1, false );
			$color_2 = self::sanitize_hex( $color_2, false );

			$color_1_rgb = self::get_rgb( $color_1 );
			$color_2_rgb = self::get_rgb( $color_2 );

			$l1 = 0.2126 * pow( $color_1_rgb[0] / 255, 2.2 ) + 0.7152 * pow( $color_1_rgb[1] / 255, 2.2 ) + 0.0722 * pow( $color_1_rgb[2] / 255, 2.2 );
			$l2 = 0.2126 * pow( $color_2_rgb[0] / 255, 2.2 ) + 0.7152 * pow( $color_2_rgb[1] / 255, 2.2 ) + 0.0722 * pow( $color_2_rgb[2] / 255, 2.2 );

			$lum_diff = ( $l1 > $l2 ) ? ( $l1 + 0.05 ) / ( $l2 + 0.05 ) : ( $l2 + 0.05 ) / ( $l1 + 0.05 );

			return round( $lum_diff, 2 );
		}
	}
}
