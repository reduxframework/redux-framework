<?php
/**
 * Bootstrap the application
 */

use Extendify\ExtendifySdk\Admin;

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

require EXTENDIFYSDK_PATH . 'routes/api.php';
require EXTENDIFYSDK_PATH . 'editorplus/EditorPlus.php';


\add_action(
    'init',
    function () {
        \load_plugin_textdomain('extendify-sdk', false, EXTENDIFYSDK_PATH . 'languages');
    }
);
