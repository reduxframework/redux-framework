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
        if (Config::$environment === 'PRODUCTION') {
            $this->hideSubmenus();
        }
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
                        'wp-components',
                        'wp-element',
                        'wp-data',
                        'wp-core-data',
                        'wp-html-entities',
                        'wp-i18n',
                        'wp-polyfill',
                    ],
                    $version,
                    true
                );
                \wp_add_inline_script(
                    Config::$slug . '-assist-scripts',
                    'window.extAssistData = ' . wp_json_encode([
                        'devbuild' => \esc_attr(Config::$environment === 'DEVELOPMENT'),
                        'root' => \esc_url_raw(\rest_url(Config::$slug . '/' . Config::$apiVersion)),
                        'nonce' => \wp_create_nonce('wp_rest'),
                        'adminUrl' => \esc_url_raw(\admin_url()),
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

    /**
     * Hide Extendify 'Welcome' and 'Assist' submenus on all admin pages.
     *
     * @return void
     */
    public function hideSubmenus()
    {
        add_action('admin_head', function () {
            echo '<style>
            #toplevel_page_extendify-assist .wp-submenu,
            #toplevel_page_extendify-welcome .wp-submenu {
                display:none!important;
            }
            #toplevel_page_extendify-assist::after,
            #toplevel_page_extendify-welcome::after {
                content:none!important;
            }
            </style>';
        });
    }

}
