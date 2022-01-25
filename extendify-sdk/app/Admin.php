<?php
/**
 * Admin.
 */

namespace Extendify\Library;

use Extendify\Library\App;
use Extendify\Library\User;
use Extendify\Library\SiteSettings;

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

        \add_filter('plugin_action_links_' . EXTENDIFY_PLUGIN_BASENAME, [ $this, 'pluginActionLinks' ]);
    }

    /**
     * Adds action links to the plugin list table
     *
     * @param array $links An array of plugin action links.
     * @return array An array of plugin action links.
     */
    public function pluginActionLinks($links)
    {
        $theme = get_option('template');
        $label = esc_html__('Upgrade', 'extendify');

        $links['upgrade'] = sprintf('<a href="%1$s" target="_blank"><b>%2$s</b></a>', "https://extendify.com/pricing?utm_source=extendify-plugin&utm_medium=wp-dash&utm_campaign=action-link&utm_content=$label&utm_term=$theme", $label);

        return $links;
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
            EXTENDIFY_BASE_URL . 'public/build/extendify.js',
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
            'extendifyData',
            [
                'root' => \esc_url_raw(rest_url(APP::$slug . '/' . APP::$apiVersion)),
                'nonce' => \wp_create_nonce('wp_rest'),
                'user' => json_decode(User::data('extendifysdk_user_data'), true),
                'sitesettings' => json_decode(SiteSettings::data()),
                'sdk_partner' => \esc_attr(APP::$sdkPartner),
                'asset_path' => \esc_url(EXTENDIFY_URL . 'public/assets'),
                'standalone' => \esc_attr(APP::$standalone),
            ]
        );
        \wp_enqueue_script(App::$slug . '-scripts');

        \wp_set_script_translations(App::$slug . '-scripts', 'extendify');

        // Inline the library styles to keep them out of the iframe live preview.
        // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
        $css = file_get_contents(EXTENDIFY_PATH . 'public/build/extendify.css');
        \wp_register_style(App::$slug, false, [], $version);
        \wp_enqueue_style(App::$slug);
        \wp_add_inline_style(App::$slug, $css);
    }

    /**
     * Check if current user is Admin
     *
     * @return Boolean
     */
    private function isAdmin()
    {
        if (\is_multisite()) {
            return \is_super_admin();
        }

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
