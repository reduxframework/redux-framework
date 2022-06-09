<?php
/**
 * Admin.
 */

namespace Extendify\Onboarding;

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
        // Whether to load Extendify Launch or not.
        if (!Config::$showOnboarding) {
            return;
        }

        if (self::$instance) {
            return self::$instance;
        }

        self::$instance = $this;
        $this->loadScripts();
        $this->addAdminMenu();
        $this->redirectOnLogin();
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
                if (!current_user_can(Config::$requiredCapability)) {
                    return;
                }

                if (!$this->checkItsGutenbergPost($hook)) {
                    return;
                }

                $this->addScopedScriptsAndStyles();
            }
        );
    }

    /**
     * Adds settings menu
     *
     * @return void
     */
    public function addAdminMenu()
    {
        \add_action('admin_menu', function () {
            if (Config::$environment === 'DEVELOPMENT' || Config::$showOnboarding) {
                \add_submenu_page('extendify', \__('Welcome', 'extendify'), \__('Welcome', 'extendify'), Config::$requiredCapability, 'extendify', '', 400);
                \add_submenu_page('extendify', 'Extendify Launch', 'Extendify Launch', Config::$requiredCapability, 'post-new.php?extendify=onboarding', '', 500);
            }
        });
    }

    /**
     * Always redirect to Extendify Launch on login unless onboarding has been previously completed or skipped.
     *
     * Applies to the main admin account, whose email matches the entry in WP Admin > Settings > General.
     *
     * @return void
     */
    public function redirectOnLogin()
    {
        // phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter
        \add_filter('login_redirect', function ($location, $request, $user) {
            // Don't redirect if Launch previously completed, or user skipped to WP admin.
            if (get_option('extendify_onboarding_skipped', 0) || get_option('extendify_onboarding_completed', 0)) {
                return $location;
            }

            // Redirect to Extendify Launch if user is admin, and their email matches the entry in general settings.
            if (is_object($user) && isset($user->data->user_email)) {
                $loginEmail = $user->data->user_email;
                $adminEmail = \get_option('admin_email');
                if ($loginEmail === $adminEmail && (isset($user->roles) && is_array($user->roles))) {
                    return in_array('administrator', $user->roles, true) ? admin_url() . 'post-new.php?extendify=onboarding' : $location;
                }
            }

            // Redirect as normal.
            return $location;
        }, 10, 3);
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
        $version = Config::$environment === 'PRODUCTION' ? Config::$version : uniqid();

        \wp_enqueue_script(
            Config::$slug . '-onboarding-scripts',
            EXTENDIFY_BASE_URL . 'public/build/extendify-onboarding.js',
            [
                'wp-i18n',
                'wp-components',
                'wp-element',
                'wp-editor',
            ],
            $version,
            true
        );
        \wp_add_inline_script(
            Config::$slug . '-onboarding-scripts',
            'window.extOnbData = ' . wp_json_encode([
                'site' => \esc_url_raw(\get_site_url()),
                'adminUrl' => \esc_url_raw(\admin_url()),
                'pluginUrl' => \esc_url_raw(EXTENDIFY_BASE_URL),
                'home' => \esc_url_raw(\get_home_url()),
                'root' => \esc_url_raw(\rest_url(Config::$slug . '/' . Config::$apiVersion)),
                'wpRoot' => \esc_url_raw(\rest_url()),
                'nonce' => \wp_create_nonce('wp_rest'),
                'partnerLogo' => defined('EXTENDIFY_PARTNER_LOGO') ? constant('EXTENDIFY_PARTNER_LOGO') : null,
                'partnerName' => defined('EXTENDIFY_PARTNER_NAME') ? constant('EXTENDIFY_PARTNER_NAME') : null,
            ]),
            'before'
        );

        \wp_set_script_translations(Config::$slug . '-onboarding-scripts', 'extendify');

        \wp_enqueue_style(
            Config::$slug . '-onboarding-styles',
            EXTENDIFY_BASE_URL . 'public/build/extendify-onboarding.css',
            [],
            $version,
            'all'
        );
        $bg = defined('EXTENDIFY_ONBOARDING_BG') ? constant('EXTENDIFY_ONBOARDING_BG') : 'var(--wp-admin-theme-color, #007cba)';
        $txt = defined('EXTENDIFY_ONBOARDING_TXT') ? constant('EXTENDIFY_ONBOARDING_TXT') : '#ffffff';
        \wp_add_inline_style(Config::$slug . '-onboarding-styles', "body {
            --ext-partner-theme-primary-bg: {$bg};
            --ext-partner-theme-primary-text: {$txt};
        }");
    }
}
