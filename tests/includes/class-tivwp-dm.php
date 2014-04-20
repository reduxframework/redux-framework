<?php

/**
 * Class TIVWP_DM
 * @package TIVWP_DM
 * @author  tivnet
 */
class TIVWP_DM {

    const MIN_CAPABILITY = 'activate_plugins';
    const MENU_SLUG_ACTIVATE = 'tivwp-dm-activate';
    const MENU_SLUG_DEACTIVATE = 'tivwp-dm-deactivate';

    /**
     * @important Do not change these constants. They are used to form WP function names.
     */
    const ACTION_ACTIVATE = 'activate';
    const ACTION_DEACTIVATE = 'deactivate';

    /**
     * @var array $development_plugins Initially empty. Loaded from $_default_development_plugins
     * @see load_plugin_list
     */
    private static $development_plugins = array();

    /**
     * Accessor method used in unit tests
     * @return array
     */
    public static function get_development_plugins() {
        return self::$development_plugins;
    }

    /**
     * @var array $_default_development_plugins Default list of plugins. Use 'tivwp_dm_plugin_list' to modify.
     */
    private static $_default_development_plugins = array(
        array(
            'name' => 'Debug Bar',
            'slug' => 'debug-bar',
            'required' => false,
        ),
        array(
            'name' => 'Debug Bar Console',
            'slug' => 'debug-bar-console',
            'required' => false,
        ),
        array(
            'name' => 'Kint Debugger',
            'slug' => 'kint-debugger',
            'required' => false,
        ),
        array(
            'name' => 'Query Monitor',
            'slug' => 'query-monitor',
            'required' => false,
        ),
    );

    /**
     * Load the default plugin list into the working one, with filter.
     */
    public static function load_plugin_list() {
        self::$development_plugins = apply_filters('tivwp_dm_plugin_list', self::$_default_development_plugins);
    }

    /**
     * Hooked methods
     */

    /**
     * Load translations
     */
    public static function action_init_load_plugin_textdomain() {
        /**
         * We are in the "includes" sub-folder. Therefore, need this "funny" construction to go up.
         * @todo Save plugin folder somewhere else and use it here
         */
        $folder_i18n = dirname(dirname(plugin_basename(__FILE__))) . '/languages';
        load_plugin_textdomain('tivwp-dm', false, $folder_i18n);
    }

    /**
     * Prompt to install required and recommended plugins
     * @wp-hook tgmpa_register
     */
    public static function action_tgmpa_register() {
        tgmpa(self::$development_plugins);
    }

    /**
     * Turn on output buffering for wp_redirect to work
     * @wp-hook admin_init
     */
    public static function turn_output_buffering() {
        if (isset($GLOBALS['plugin_page']) && in_array($GLOBALS['plugin_page'], array(
                    self::MENU_SLUG_ACTIVATE,
                    self::MENU_SLUG_DEACTIVATE,
                ))
        ) {
            ob_start();
        }
    }

    /**
     * Setup administrator menu options
     * @wp-hook admin_menu
     */
    public static function action_admin_menu() {

        add_plugins_page(
                '', __('Activate Development Plugins', 'tivwp-dm'), self::MIN_CAPABILITY, self::MENU_SLUG_ACTIVATE, array(
            __CLASS__,
            'menu_callback_activate'
                )
        );

        add_plugins_page(
                '', __('Deactivate Development Plugins', 'tivwp-dm'), self::MIN_CAPABILITY, self::MENU_SLUG_DEACTIVATE, array(
            __CLASS__,
            'menu_callback_deactivate'
                )
        );
    }

    /**
     * Service method: protect from non-authorized users
     */
    private static function _die_if_not_authorized() {
        if (!current_user_can(self::MIN_CAPABILITY)) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'tivwp-dm'));
        }
    }

    /**
     * Activate all development plugins and redirect to the list of active plugins
     * activate-multi=true is used to display the "Selected plugins activated" message
     */
    public static function menu_callback_activate() {
        self::_die_if_not_authorized();
        self::activate_all_development_plugins();
        wp_redirect(self_admin_url('plugins.php?plugin_status=active&activate-multi=true'));
        exit;
    }

    /**
     * Deactivate all development plugins and redirect to the list of inactive plugins
     * deactivate-multi=true is used to display the "Selected plugins deactivated" message
     */
    public static function menu_callback_deactivate() {
        self::_die_if_not_authorized();
        self::deactivate_all_development_plugins();
        wp_redirect(self_admin_url('plugins.php?plugin_status=inactive&deactivate-multi=true'));
        exit;
    }

    /**
     * Public interface to the _switch_all_development_plugins method
     */
    public static function activate_all_development_plugins() {
        self::_switch_all_development_plugins(self::ACTION_ACTIVATE);
    }

    /**
     * Public interface to the _switch_all_development_plugins method
     */
    public static function deactivate_all_development_plugins() {
        self::_switch_all_development_plugins(self::ACTION_DEACTIVATE);
    }

    /**
     * The $development_plugins array has only plugin slugs, and not the path to the plugin file,
     * needed by activation/deactivation methods.
     * Therefore, we build a list of plugins in the form $slug => $plugin
     * @example
     * $all_plugins = array(
     *    'query-monitor'    => 'query-monitor/query-monitor.php',
     *    'debug-bar'        => 'debug-bar/debug-bar.php',
     *    'woocommerce'      => 'woocommerce/woocommerce.php'
     *    );
     * and then we make the array of the values, keeping only those that are in the $development_plugins
     * @example
     * $plugins = array(
     *    'query-monitor/query-monitor.php',
     *    'debug-bar/debug-bar.php',
     *    );
     * @return array
     */
    private static function _get_plugins() {
        /**
         * When on front, the WordPress Plugin Administration API is not available by default
         */
        if (!function_exists('get_plugins')) {
            /** @noinspection PhpIncludeInspection */
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        $all_plugins = array();
        foreach (array_keys(get_plugins()) as $plugin) {
            $all_plugins[dirname($plugin)] = $plugin;
        }

        $plugins = array();
        foreach (self::$development_plugins as $plugin_info) {
            if (!empty($all_plugins[$plugin_info['slug']])) {
                $plugins[] = $all_plugins[$plugin_info['slug']];
            }
        }
        return $plugins;
    }

    /**
     * Pass the list of development plugins to the (de)activation function.
     * @param string $action Activate or Deactivate
     */
    private static function _switch_all_development_plugins($action = self::ACTION_ACTIVATE) {

        $plugins = self::_get_plugins();
        $redirect = '';
        $network_wide = false;
        $silent = false;
        switch ($action) {
            case self::ACTION_ACTIVATE:
                activate_plugins($plugins, $redirect, $network_wide, $silent);
                break;
            case self::ACTION_DEACTIVATE:
                deactivate_plugins($plugins, $silent, $network_wide);
                break;
        }
    }

}

# --- EOF
