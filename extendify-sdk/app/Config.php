<?php
/**
 * The App details file
 */

namespace Extendify;

/**
 * Controller for handling various app data
 */
class Config
{

    /**
     * Plugin name
     *
     * @var string
     */
    public static $name = '';

    /**
     * Plugin slug
     *
     * @var string
     */
    public static $slug = '';

    /**
     * Plugin version
     *
     * @var string
     */
    public static $version = '';

    /**
     * Plugin API REST version
     *
     * @var string
     */
    public static $apiVersion = 'v1';

    /**
     * Whether this is the standalone plugin
     *
     * @var boolean
     */
    public static $standalone;

    /**
     * Whether to show load onboarding
     *
     * @var boolean
     */
    public static $showOnboarding = false;

    /**
     * Plugin environment
     *
     * @var string
     */
    public static $environment = '';

    /**
     * The partner plugin/theme
     *
     * @var string
     */
    public static $sdkPartner = 'standalone';

    /**
     * Host plugin
     *
     * @var string
     */
    public static $requiredCapability = 'manage_options';

    /**
     * Plugin config
     *
     * @var array
     */
    public static $config = [];

    /**
     * Process the readme file to get version and name
     *
     * @return void
     */
    public function __construct()
    {
        if (isset($GLOBALS['extendify_sdk_partner']) && $GLOBALS['extendify_sdk_partner']) {
            self::$sdkPartner = $GLOBALS['extendify_sdk_partner'];
        }

        // Always use the partner name if set as a constant.
        if (defined('EXTENDIFY_PARTNER_NAME')) {
            self::$sdkPartner = constant('EXTENDIFY_PARTNER_NAME');
        }

        // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
        $readme = file_get_contents(EXTENDIFY_PATH . 'readme.txt');

        preg_match('/=== (.+) ===/', $readme, $matches);
        self::$name = $matches[1];
        self::$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', self::$name), '-'));

        preg_match('/Stable tag: ([0-9.:]+)/', $readme, $matches);
        self::$version = $matches[1];

        // An easy way to check if we are in dev mode is to look for a dev specific file.
        $isDev = is_readable(EXTENDIFY_PATH . 'public/build/.devbuild');
        self::$environment = $isDev ? 'DEVELOPMENT' : 'PRODUCTION';

        self::$standalone = self::$sdkPartner === 'standalone';
        self::$showOnboarding = $this->showOnboarding();

        // Add the config.
        // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
        $config = file_get_contents(EXTENDIFY_PATH . 'config.json');
        self::$config = json_decode($config, true);
    }

    /**
     * Conditionally load Extendify Launch.
     *
     * @return boolean
     */
    private function showOnboarding()
    {
        // Always show it for dev mode.
        if (self::$environment === 'DEVELOPMENT') {
            return true;
        }

        // Currently we require a flag to be set.
        if (!defined('EXTENDIFY_SHOW_ONBOARDING')) {
            return false;
        }

        // Check if they disabled it and respect that.
        if (constant('EXTENDIFY_SHOW_ONBOARDING') === false) {
            return false;
        }

        // time() will be truthy and 0 falsy.
        if (get_option('extendify_onboarding_skipped', 0)) {
            return false;
        }

        // time() will be truthy and 0 falsy, so we reverse it.
        return !get_option('extendify_onboarding_completed', 0);
    }
}
