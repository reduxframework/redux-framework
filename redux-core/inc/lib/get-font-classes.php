<?php
/**
 * Get Font Awesome font classes.
 *
 * Used with Gulp compiler to pull Font Awesome font class names
 * and compile them into a function to be used with icon_Select field.
 *
 * @package Redux
 * @author  Kevin Provance <kevin.provance@gmail.com>
 *
 * @version 4.4.2
 */

$redux_output  = '<?php' . "\r";
$redux_output .= '/**' . "\r";
$redux_output .= ' * Redux Icon Select Font Awesome 6 Free icon array.' . "\r";
$redux_output .= ' *' . "\r";
$redux_output .= ' * @package Redux' . "\r";
$redux_output .= ' * @author  Kevin Provance <kevin.provance@gmail.com>' . "\r";
$redux_output .= ' */' . "\r\r";
$redux_output .= "defined( 'ABSPATH' ) || exit;\r\r";
$redux_output .= '';
$redux_output .= "if ( ! function_exists( 'redux_icon_select_fa_6_free' ) ) {\r\r";
$redux_output .= "\t" . '/**' . "\r";
$redux_output .= "\t" . ' * Array of free Font Awesome 6 icons.' . "\r";
$redux_output .= "\t" . ' *' . "\r";
$redux_output .= "\t" . ' * @return array' . "\r";
$redux_output .= "\t" . ' */' . "\r";
$redux_output .= "\t" . 'function redux_icon_select_fa_6_free(): array {' . "\r";
$redux_output .= "\t\t" . 'return array( ' . redux_fa_icons() . ' );' . "\r";
$redux_output .= "\t" . '}' . "\r";
$redux_output .= '}' . "\r";

file_put_contents( dirname( __DIR__ ) . '/lib/font-awesome-6-free.php', $redux_output );

/**
 * Get Font Awesome metadata.
 *
 * @return false|string
 */
function redux_fa_icons() {
	$content = file_get_contents( 'https://raw.githubusercontent.com/FortAwesome/Font-Awesome/6.x/metadata/icons.json' );
	$json    = json_decode( $content );
	$icons   = '';

	foreach ( $json as $icon => $value ) {
		foreach ( $value->styles as $style ) {
			$icon = 'fa' . substr( $style, 0, 1 ) . ' fa-' . $icon;

			$icons .= "'" . $icon . "', ";
		}
	}

	return substr( $icons, 0, -2 );
}
