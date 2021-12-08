<?php
/**
 * Admin.
 */

namespace Extendify\ExtendifySdk;

use Extendify\ExtendifySdk\App;
use Extendify\ExtendifySdk\User;
use Extendify\ExtendifySdk\SiteSettings;

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
            function ($hook) {
                if (!current_user_can(App::$requiredCapability)) {
                    return;
                }

                if (!$this->checkItsGutenbergPost($hook)) {
                    return;
                }

                if (!$this->isLibraryEnabled()) {
                    return;
                }

                $this->addScopedScriptsAndStyles();
            }
        );
    }

    /**
     * Makes sure we are on the correct page
     *
     * @param string $hook - An optional hook provided by WP to identify the page.
     * @return boolean
     */
    public function checkItsGutenbergPost($hook = '')
    {
        if (isset($GLOBALS['typenow']) && \use_block_editor_for_post_type($GLOBALS['typenow'])) {
            return $hook && in_array($hook, ['post.php', 'post-new.php'], true);
        }

        return false;
    }

    /**
     * Adds various JS scripts
     *
     * @return void
     */
    public function addScopedScriptsAndStyles()
    {
        $version = App::$environment === 'PRODUCTION' ? App::$version : uniqid();

        \wp_register_script(
            App::$slug . '-scripts',
            EXTENDIFYSDK_BASE_URL . 'public/build/extendify-sdk.js',
            [
                'wp-i18n',
                'wp-components',
                'wp-element',
                'wp-editor',
            ],
            $version,
            true
        );
        \wp_localize_script(
            App::$slug . '-scripts',
            'extendifySdkData',
            [
                'root' => \esc_url_raw(rest_url(APP::$slug . '/' . APP::$apiVersion)),
                'nonce' => \wp_create_nonce('wp_rest'),
                'user' => json_decode(User::data('extendifysdk_user_data'), true),
                'sitesettings' => json_decode(SiteSettings::data()),
                'sdk_partner' => \esc_attr(APP::$sdkPartner),
            ]
        );
        \wp_enqueue_script(App::$slug . '-scripts');

        \wp_set_script_translations(App::$slug . '-scripts', App::$textDomain);

        \wp_enqueue_style(
            App::$slug . '-theme',
            EXTENDIFYSDK_BASE_URL . 'public/build/extendify-sdk.css',
            [],
            $version,
            'all'
        );

        \wp_enqueue_style(
            App::$slug . '-utility-classes',
            EXTENDIFYSDK_BASE_URL . 'public/build/extendify-utilities.css',
            [],
            $version,
            'all'
        );
    }

    /**
     * Check if current user is Admin
     *
     * @return Boolean
     */
    private function isAdmin()
    {
        return in_array('administrator', \wp_get_current_user()->roles, true);
    }

    /**
     * Check if scripts should add
     *
     * @return Boolean
     */
    public function isLibraryEnabled()
    {
        $settings = json_decode(SiteSettings::data());

        // If it's disabled, only show it for admins.
        if (isset($settings->state) && (isset($settings->state->enabled)) && !$settings->state->enabled) {
            return $this->isAdmin();
        }

        return true;
    }
}
