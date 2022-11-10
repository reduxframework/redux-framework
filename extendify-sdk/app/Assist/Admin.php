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

        // Show the Assist admin bar link when Launch is finished.
        if (Config::$showAssist && Config::$launchCompleted) {
            \add_action('admin_bar_menu', [$this, 'adminBarLink']);
            \add_action('wp_enqueue_scripts', [$this, 'adminBarStyles']);
            \add_action('admin_enqueue_scripts', [$this, 'adminBarStyles']);
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
            function () {
                if (!current_user_can(Config::$requiredCapability)) {
                    return;
                }

                if (!Config::$showAssist) {
                    return;
                }

                // Don't show on Launch pages.
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                if (isset($_GET['page']) && $_GET['page'] === 'extendify-launch') {
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

    /**
     * Adds link to assist to the admin bar.
     *
     * @param   object $adminBar - The admin bar.
     * @return  void
     */
    public function adminBarLink($adminBar)
    {
        $title = '<span class="ab-icon" aria-hidden="true"></span><span class="ab-label">' . __('Site Assistant', 'extendify') . '</span>';

        $args = [
            'id' => 'extendify-assist-link',
            'title' => $title,
            'href' => \admin_url() . 'admin.php?page=extendify-admin-page',
            'parent' => 'top-secondary',
        ];
        $adminBar->add_node($args);
    }

    /**
     * Adds custom styles only when admin bar CSS loaded.
     *
     * @return void
     */
    public function adminBarStyles()
    {
        $svg = 'url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0iI2ZmZiIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4gPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMS41MDA5IDJIMTQuOTg3M0MxNS45NzQ3IDIgMTYuMzMyMiAyLjEwMTYxIDE2LjY5MzQgMi4yOTEyN0MxNy4wNTQ2IDIuNDgxNjkgMTcuMzM3MiAyLjc2MDkyIDE3LjUzMDQgMy4xMTYxNkMxNy43MjM3IDMuNDcyMTYgMTcuODI2IDMuODI0NCAxNy44MjYgNC43OTc1NlY4LjIzMzM2QzE3LjgyNiA5LjIwNjUyIDE3LjcyMjkgOS41NTg3NiAxNy41MzA0IDkuOTE0NzVDMTcuMzM3MiAxMC4yNzA4IDE3LjA1MzkgMTAuNTQ5MiAxNi42OTM0IDEwLjczOTZDMTYuNTMxOSAxMC44MjQ4IDE2LjM3MDggMTAuODk0OCAxNi4xNTQgMTAuOTQ1VjEzLjY3OTRDMTYuMTU0IDE1LjE4MjQgMTUuOTk0NyAxNS43MjY0IDE1LjY5NzUgMTYuMjc2MkMxNS4zOTkxIDE2LjgyNiAxNC45NjE1IDE3LjI1NjEgMTQuNDA0OCAxNy41NTAyQzEzLjg0NjkgMTcuODQ0MiAxMy4yOTQ5IDE4IDExLjc2OTggMThINi4zODUzOEM0Ljg2MDI4IDE4IDQuMzA4MjggMTcuODQzMSAzLjc1MDM4IDE3LjU1MDJDMy4xOTI0NyAxNy4yNTYxIDIuNzU2MDYgMTYuODI0OCAyLjQ1NzY1IDE2LjI3NjJDMi4xNTkyMyAxNS43Mjc1IDIgMTUuMTgyNCAyIDEzLjY3OTRWOC4zNzQyNkMyIDYuODcxMjkgMi4xNTkyMyA2LjMyNzI5IDIuNDU2NDcgNS43Nzc0OEMyLjc1NDg4IDUuMjI3NjcgMy4xOTI0NyA0Ljc5NjQyIDMuNzUwMzggNC41MDIzNEM0LjMwNzEgNC4yMDk0MSA0Ljg2MDI4IDQuMDUyNDkgNi4zODUzOCA0LjA1MjQ5SDguNjkwODFDOC43MzQwNSAzLjYwNTk3IDguODI0MjYgMy4zNjIzNCA4Ljk1Njk0IDMuMTE2OTJDOS4xNTAxNiAyLjc2MDkyIDkuNDMzNSAyLjQ4MTY5IDkuNzk0NzQgMi4yOTEyN0MxMC4xNTUyIDIuMTAxNjEgMTAuNTEzNCAyIDExLjUwMDkgMlpNOS43MDkgNC4xODY5OEM5LjcwOSAzLjU0OTI5IDEwLjIzMzYgMy4wMzIzNCAxMC44ODA3IDMuMDMyMzRIMTUuNjA2NkMxNi4yNTM4IDMuMDMyMzQgMTYuNzc4NCAzLjU0OTI5IDE2Ljc3ODQgNC4xODY5OFY4Ljg0Mzk1QzE2Ljc3ODQgOS40ODE2NCAxNi4yNTM4IDkuOTk4NTkgMTUuNjA2NiA5Ljk5ODU5SDEwLjg4MDdDMTAuMjMzNiA5Ljk5ODU5IDkuNzA5IDkuNDgxNjQgOS43MDkgOC44NDM5NVY0LjE4Njk4WiIgZmlsbD0iI2ZmZiIgLz4gPC9zdmc+")';
        $css = '#wpadminbar #wp-admin-bar-extendify-assist-link .ab-icon { width: 20px; height: 20px; top: 1px;  background-color: currentColor; -webkit-mask-repeat: no-repeat; mask-repeat: no-repeat; -webkit-mask-position: center; mask-position: center; -webkit-mask-image: ' . $svg . ' !important; mask-image: ' . $svg . ' !important; }';
        wp_add_inline_style('admin-bar', $css);
    }
}
