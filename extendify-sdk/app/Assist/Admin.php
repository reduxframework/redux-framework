<?php
/**
 * Admin.
 */

namespace Extendify\Assist;

use Extendify\Config;

/**
 * This class handles any file loading for the admin area.
 */
class Admin
{

    /**
     * The instance
     *
     * @var $instance
     */
    public static $instance = null;

    /**
     * Adds various actions to set up the page
     *
     * @return self|void
     */
    public function __construct()
    {
        if (self::$instance) {
            return self::$instance;
        }

        self::$instance = $this;
        $this->loadScripts();
    }

    /**
     * Adds scripts to the admin
     *
     * @return void
     */
    public function loadScripts()
    {
        \add_action(
            'admin_enqueue_scripts',
            function () {
                if (!current_user_can(Config::$requiredCapability)) {
                    return;
                }

                if (!Config::$showAssist) {
                    return;
                }

                $version = Config::$environment === 'PRODUCTION' ? Config::$version : uniqid();

                \wp_enqueue_script(
                    Config::$slug . '-assist-scripts',
                    EXTENDIFY_BASE_URL . 'public/build/extendify-assist.js',
                    [
                        'wp-i18n',
                        'wp-components',
                        'wp-element',
                    ],
                    $version,
                    true
                );
                \wp_add_inline_script(
                    Config::$slug . '-assist-scripts',
                    'window.extAssistData = ' . wp_json_encode([
                        'devbuild' => \esc_attr(Config::$environment === 'DEVELOPMENT'),
                    ]),
                    'before'
                );

                \wp_set_script_translations(Config::$slug . '-assist-scripts', 'extendify');

                \wp_enqueue_style(
                    Config::$slug . '-assist-styles',
                    EXTENDIFY_BASE_URL . 'public/build/extendify-assist.css',
                    [],
                    $version,
                    'all'
                );
            }
        );
    }

}
