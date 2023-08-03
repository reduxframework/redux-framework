<?php
/**
 * Silence is golden.
 *
 * @package Redux Framework
 */

$theme = wp_get_theme();

// translators: %1$s: template path.
echo '<div class="error"><p>' . sprintf( esc_html__( 'The Redux 3 file ReduxCore/core/dashboard.php is still in use by %1$s. Please contact the author of this theme (NOT Redux support, we have no control over this issue). They need to update their project to use Redux 4 and discontinue use of this file. It will soon be removed from Redux.', 'redux-framework' ), '<code>' . esc_html( $theme->get( 'Name' ) ) . '</code>' ) . '</p></div>';

_deprecated_file( 'ReduxCore/core/dashboard.php', '4.3', '', 'This file has been discontinued and is no longer used in Redux 4.  Please remove any references to it as it will be removed in future versions of Redux.' );
