<?php
/**
 * Bootstrap the application
 */

use Extendify\Library\Admin;
use Extendify\Library\Frontend;
use Extendify\Library\Shared;

if (!defined('ABSPATH')) {
    die('No direct access.');
}

if (!defined('EXTENDIFY_PATH')) {
    define('EXTENDIFY_PATH', \plugin_dir_path(__FILE__));
}

if (!defined('EXTENDIFY_URL')) {
    define('EXTENDIFY_URL', \plugin_dir_url(__FILE__));
}

if (!defined('EXTENDIFY_PLUGIN_BASENAME')) {
    define('EXTENDIFY_PLUGIN_BASENAME', \plugin_basename(__DIR__ . '/extendify.php'));
}

if (is_readable(EXTENDIFY_PATH . 'vendor/autoload.php')) {
    require EXTENDIFY_PATH . 'vendor/autoload.php';
}

$extendifyAdmin = new Admin();
$extendifyFrontend = new Frontend();
$extendifyShared = new Shared();

require EXTENDIFY_PATH . 'routes/api.php';
require EXTENDIFY_PATH . 'editorplus/EditorPlus.php';

\add_action(
    'init',
    function () {
        \load_plugin_textdomain('extendify', false, EXTENDIFY_PATH . 'languages');
    }
);

// To cover legacy conflicts.
// phpcs:ignore
class ExtendifySdk
{
}
