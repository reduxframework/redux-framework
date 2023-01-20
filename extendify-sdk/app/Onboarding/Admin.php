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
        $this->redirectOnce();
        $this->addMetaField();
    }

    /**
     * Adds a meta field so we can indicate a page was made with launch
     *
     * @return void
     */
    public function addMetaField()
    {
        \add_action(
            'init',
            function () {
                register_post_meta(
                    'page',
                    'made_with_extendify_launch',
                    [
                        'single'       => true,
                        'type'         => 'boolean',
                        'show_in_rest' => true,
                    ]
                );
            }
        );
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

                // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                if (!isset($_GET['page']) || $_GET['page'] !== 'extendify-launch') {
                    return;
                }

                $this->addScopedScriptsAndStyles();
            }
        );
    }


    /**
     * Redirect once to Launch, only once (at least once) when
     * the email matches the entry in WP Admin > Settings > General.
     *
     * @return void
     */
    public function redirectOnce()
    {
        \add_action('admin_init', function () {
            if (\get_option('extendify_launch_loaded', 0)
                // These are here for legacy reasons.
                || \get_option('extendify_onboarding_skipped', 0)
                || Config::$launchCompleted
            ) {
                return;
            }

            // Only redirect if we aren't already on the page.
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            if (isset($_GET['page']) && $_GET['page'] === 'extendify-launch') {
                return;
            }

            $user = \wp_get_current_user();
            if ($user
                // Check the main admin email, and they have an admin role.
                // phpcs:ignore Squiz.NamingConventions.ValidVariableName.MemberNotCamelCaps
                && \get_option('admin_email') === $user->user_email
                && in_array('administrator', $user->roles, true)
            ) {
                \wp_safe_redirect(\admin_url() . 'admin.php?page=extendify-launch');
            }
        });
    }

    /**
     * Check if partner data is available.
     *
     * @return array
     */
    public function checkPartnerDataSources()
    {
        $return = [];

        try {
            if (defined('EXTENDIFY_ONBOARDING_BG')) {
                $return['bgColor'] = constant('EXTENDIFY_ONBOARDING_BG');
                $return['fgColor'] = constant('EXTENDIFY_ONBOARDING_TXT');
                $return['logo'] = constant('EXTENDIFY_PARTNER_LOGO');
            }

            $data = get_option('extendify_partner_data');
            if ($data) {
                $return['bgColor'] = $data['backgroundColor'];
                $return['fgColor'] = $data['foregroundColor'];
                // Need this check to avoid errors if no partner logo is set in Airtable.
                $return['logo'] = $data['logo'] ? $data['logo'][0]['thumbnails']['large']['url'] : null;
            }
        } catch (\Exception $e) {
            // Do nothing here, set variables below. Coding Standards require something to be in the catch.
            $e;
        }//end try

        return $return;
    }

    /**
     * Adds various JS scripts
     *
     * @return void
     */
    public function addScopedScriptsAndStyles()
    {
        $partnerData = $this->checkPartnerDataSources();

        $bgColor = isset($partnerData['bgColor']) ? $partnerData['bgColor'] : '#2c39bd';
        $fgColor = isset($partnerData['fgColor']) ? $partnerData['fgColor'] : '#ffffff';
        $logo = isset($partnerData['logo']) ? $partnerData['logo'] : null;

        $version = Config::$environment === 'PRODUCTION' ? Config::$version : uniqid();
        $scriptAssetPath = EXTENDIFY_PATH . 'public/build/extendify-onboarding.asset.php';
        $fallback = [
            'dependencies' => [],
            'version' => $version,
        ];
        $scriptAsset = file_exists($scriptAssetPath) ? require $scriptAssetPath : $fallback;
        foreach ($scriptAsset['dependencies'] as $style) {
            wp_enqueue_style($style);
        }

        \wp_enqueue_script(
            Config::$slug . '-onboarding-scripts',
            EXTENDIFY_BASE_URL . 'public/build/extendify-onboarding.js',
            $scriptAsset['dependencies'],
            $scriptAsset['version'],
            true
        );
        \wp_add_inline_script(
            Config::$slug . '-onboarding-scripts',
            'window.extOnbData = ' . \wp_json_encode([
                'globalStylesPostID' => \WP_Theme_JSON_Resolver::get_user_global_styles_post_id(),
                'editorStyles' => \get_block_editor_settings([], null),
                'site' => \esc_url_raw(\get_site_url()),
                'adminUrl' => \esc_url_raw(\admin_url()),
                'pluginUrl' => \esc_url_raw(EXTENDIFY_BASE_URL),
                'home' => \esc_url_raw(\get_home_url()),
                'root' => \esc_url_raw(\rest_url(Config::$slug . '/' . Config::$apiVersion)),
                'config' => Config::$config,
                'wpRoot' => \esc_url_raw(\rest_url()),
                'nonce' => \wp_create_nonce('wp_rest'),
                'partnerLogo' => $logo,
                'partnerName' => \esc_attr(Config::$sdkPartner),
                'partnerSkipSteps' => defined('EXTENDIFY_SKIP_STEPS') ? constant('EXTENDIFY_SKIP_STEPS') : [],
                'devbuild' => \esc_attr(Config::$environment === 'DEVELOPMENT'),
                'version' => Config::$version,
                'insightsId' => \get_option('extendify_site_id', ''),
                // Only send insights if they have opted in explicitly.
                'insightsEnabled' => defined('EXTENDIFY_INSIGHTS_URL'),
                'activeTests' => \get_option('extendify_active_tests', []),
                'wpLanguage' => \get_locale(),
                'siteCreatedAt' => get_user_option('user_registered', 1),
            ]),
            'before'
        );

        \wp_set_script_translations(Config::$slug . '-onboarding-scripts', 'extendify');

        \wp_enqueue_style(
            Config::$slug . '-onboarding-styles',
            EXTENDIFY_BASE_URL . 'public/build/extendify-onboarding.css',
            [],
            $version
        );

        \wp_add_inline_style(Config::$slug . '-onboarding-styles', ":root {
            --ext-partner-theme-primary-bg: {$bgColor};
            --ext-partner-theme-primary-text: {$fgColor};
        }");
    }
}
