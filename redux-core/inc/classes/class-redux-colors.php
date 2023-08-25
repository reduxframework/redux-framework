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
		 * Sanitizes a HEX value.
		 * The way this works is by splitting the string in 6 substrings.
		 * Each sub-string is individually sanitized, and the result is then returned.
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

			// If the string is six characters long, then use it in pairs.
			if ( 3 === strlen( $color ) ) {
				$color = substr( $color, 0, 1 ) . substr( $color, 0, 1 ) . substr( $color, 1, 1 ) . substr( $color, 1, 1 ) . substr( $color, 2, 1 ) . substr( $color, 2, 1 );
			}

			$substr = array();
			for ( $i = 0; $i <= 5; $i++ ) {
				$default      = ( 0 === $i ) ? 'F' : ( $substr[ $i - 1 ] );
				$substr[ $i ] = substr( $color, $i, 1 );
				$substr[ $i ] = ( false === $substr[ $i ] || ! ctype_xdigit( $substr[ $i ] ) ) ? $default : $substr[ $i ];
			}

			$hex = implode( '', $substr );

			return ( ! $hash ) ? $hex : '#' . $hex;
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
			 * Make sure that opacity is properly formatted:
			 * Set the opacity to 100 if a larger value has been entered by mistake.
			 * If a negative value is used, then set to 0.
			 * If an opacity value is entered in a decimal form (for example, 0.25), then multiply by 100.
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
	}
}
