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

        add_action('after_setup_theme', function () {
            // phpcs:ignore WordPress.Security.NonceVerification
            if (isset($_GET['extendify-disable-admin-bar'])) {
                show_admin_bar(false);
            }
        });
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

                if (!Config::$showAssist) {
                    return;
                }

                // Don't show on Launch pages.
                if ($hook === 'extendify_page_extendify-launch') {
                    return;
                }

                $version = Config::$environment === 'PRODUCTION' ? Config::$version : uniqid();
                $scriptAssetPath = EXTENDIFY_PATH . 'public/build/extendify-assist.asset.php';
                $fallback = [
                    'dependencies' => [],
                    'version' => $version,
                ];
                $scriptAsset = file_exists($scriptAssetPath) ? require $scriptAssetPath : $fallback;
                wp_enqueue_media();
                foreach ($scriptAsset['dependencies'] as $style) {
                    wp_enqueue_style($style);
                }

                \wp_enqueue_script(
                    Config::$slug . '-assist-scripts',
                    EXTENDIFY_BASE_URL . 'public/build/extendify-assist.js',
                    $scriptAsset['dependencies'],
                    $scriptAsset['version'],
                    true
                );

                $assistState = get_option('extendify_assist_globals');
                $dismissed = isset($assistState['state']['dismissedNotices']) ? $assistState['state']['dismissedNotices'] : [];
                \wp_add_inline_script(
                    Config::$slug . '-assist-scripts',
                    'window.extAssistData = ' . wp_json_encode([
                        'devbuild' => \esc_attr(Config::$environment === 'DEVELOPMENT'),
                        'insightsId' => \get_option('extendify_site_id', ''),
                        // Only send insights if they have opted in explicitly.
                        'insightsEnabled' => defined('EXTENDIFY_INSIGHTS_URL'),
                        'root' => \esc_url_raw(\rest_url(Config::$slug . '/' . Config::$apiVersion)),
                        'nonce' => \wp_create_nonce('wp_rest'),
                        'adminUrl' => \esc_url_raw(\admin_url()),
                        'home' => \esc_url_raw(\get_home_url()),
                        'asset_path' => \esc_url(EXTENDIFY_URL . 'public/assets'),
                        'launchCompleted' => Config::$launchCompleted,
                        'dismissedNotices' => $dismissed,
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
