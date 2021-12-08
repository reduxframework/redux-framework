<?php
/**
 * Bootstrap the application
 */

use Extendify\ExtendifySdk\Admin;
use Extendify\ExtendifySdk\Frontend;
use Extendify\ExtendifySdk\Shared;

if (!defined('ABSPATH')) {
    die('No direct access.');
}

if (!defined('EXTENDIFYSDK_PATH')) {
    define('EXTENDIFYSDK_PATH', \plugin_dir_path(__FILE__));
}

if (is_readable(EXTENDIFYSDK_PATH . 'vendor/autoload.php')) {
    require EXTENDIFYSDK_PATH . 'vendor/autoload.php';
}

$extendifysdkAdmin = new Admin();
$extendifysdkFrontend = new Frontend();
$extendifysdkShared = new Shared();

require EXTENDIFYSDK_PATH . 'routes/api.php';
require EXTENDIFYSDK_PATH . 'editorplus/EditorPlus.php';

\add_action(
    'init',
    function () {
        // Hard-coded to run only within Editor Plus for now.
        if (isset($GLOBALS['extendifySdkSourcePlugin']) && in_array($GLOBALS['extendifySdkSourcePlugin'], ['Editor Plus'], true)) {
            require EXTENDIFYSDK_PATH . 'support/notices.php';
        }

        \load_plugin_textdomain('extendify-sdk', false, EXTENDIFYSDK_PATH . 'languages');
    }
);
