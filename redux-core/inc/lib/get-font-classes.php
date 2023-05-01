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

$output  = '<?php' . "\r";
$output .= '/**' . "\r";
$output .= ' * Redux Icon Select Font Awesome 6 Free icon array.' . "\r";
$output .= ' *' . "\r";
$output .= ' * @package Redux' . "\r";
$output .= ' * @author  Kevin Provance <kevin.provance@gmail.com>' . "\r";
$output .= ' */' . "\r\r";
$output .= "defined( 'ABSPATH' ) || exit;\r\r";
$output .= '';
$output .= "if ( ! function_exists( 'redux_icon_select_fa_6_free' ) ) {\r\r";
$output .= "\t" . '/**' . "\r";
$output .= "\t" . ' * Array of free Font Awesome 6 icons.' . "\r";
$output .= "\t" . ' *' . "\r";
$output .= "\t" . ' * @return array' . "\r";
$output .= "\t" . ' */' . "\r";
$output .= "\t" . 'function redux_icon_select_fa_6_free(): array {' . "\r";
$output .= "\t\t" . 'return array( ' . fa_icons() . ' );' . "\r";
$output .= "\t" . '}' . "\r";
$output .= '}' . "\r";

file_put_contents( dirname( __DIR__ ) . '/lib/font-awesome-6-free.php', $output );

// print_r ( fa_icons() );

/**
 * Get Font Awesome metadata.
 *
 * @return false|string
 */
function fa_icons() {
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
