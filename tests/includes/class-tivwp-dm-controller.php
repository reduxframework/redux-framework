<?php

require_once 'class-tivwp-dm.php';
require_once 'class-tivwp-dm-notices.php';

/**
 * Class TIVWP_DM_Controller
 * @package TIVWP_DM
 * @author  tivnet
 */
class TIVWP_DM_Controller {

    /**
     * Constructor
     */
    public static function construct() {

        /**
         * The auto-switch should always work
         */
        self::_load_plugin_list();
        self::_setup_automatic_switch();

        /**
         * The rest relies on user privileges, and therefore must wait until necessary WP functions are loaded
         */
        if (!did_action('plugins_loaded')) {
            _doing_it_wrong(__METHOD__, __('Must call in of after the "plugins_loaded" action.', 'tivwp-dm'), '14.03.19');
            return;
        }

        /**
         * Low-level users won't see anything
         */
        if (!current_user_can(TIVWP_DM::MIN_CAPABILITY)) {
            return;
        }

        /**
         * The main actions happen in the admin area
         */
        if (is_admin()) {
            self::_setup_i18n();
            self::_setup_plugin_composer();
            self::_setup_admin_interface();
        }
    }

    /**
     * Initialize plugin list
     * @see TIVWP_DM::load_plugin_list
     */
    private static function _load_plugin_list() {
        add_action('init', array(
            'TIVWP_DM',
            'load_plugin_list'
                ), 0);
    }

    /**
     * Setup action call for internationalization
     * @see TIVWP_DM::action_init_load_plugin_textdomain
     */
    private static function _setup_i18n() {
        add_action('init', array(
            'TIVWP_DM',
            'action_init_load_plugin_textdomain'
        ));
    }

    /**
     * Prompt administrator to install and activate plugins
     */
    private static function _setup_plugin_composer() {

        if (!class_exists('TGM_Plugin_Activation')) {
            /**
             * @author    Thomas Griffin <thomas@thomasgriffinmedia.com>
             * @author    Gary Jones <gamajo@gamajo.com>
             * @link      https://github.com/thomasgriffin/TGM-Plugin-Activation
             */
            require_once dirname(__FILE__) . '/../vendor/class-tgm-plugin-activation.php';
        }

        /**
         * This happens on
         * @wp-hook init
         * @see     TGM_Plugin_Activation::init
         * @see     TIVWP_DM::action_tgmpa_register
         */
        add_action('tgmpa_register', array(
            'TIVWP_DM',
            'action_tgmpa_register'
        ));
    }

    /**
     * Setup admin area menus to bulk switch development plugins on/off
     * @see TIVWP_DM::turn_output_buffering
     * @see TIVWP_DM::action_admin_menu
     */
    private static function _setup_admin_interface() {

        add_action('admin_init', array(
            'TIVWP_DM',
            'turn_output_buffering'
        ));

        add_action('admin_menu', array(
            'TIVWP_DM',
            'action_admin_menu'
        ));
    }

    /**
     * Setup automatic plugins on/off switching depending on the defined TIVWP_DM_AUTO constant
     * @see TIVWP_DM::activate_all_development_plugins
     * @see TIVWP_DM::deactivate_all_development_plugins
     */
    private static function _setup_automatic_switch() {

        if (!defined('TIVWP_DM_AUTO')) {
            return;
        }

        $action = TIVWP_DM_AUTO;

        /**
         * If there is no callable method corresponding to the TIVWP_DM_AUTO set,
         * notify admin and set a fallback action
         */
        if (!is_callable(array(
                    'TIVWP_DM',
                    "{$action}_all_development_plugins"
                ))
        ) {

            TIVWP_DM_Notices::add(sprintf(__('Unknown action "%1$s" specified for %2$s', 'tivwp-dm'), $action, 'TIVWP_DM_AUTO'), TIVWP_DM_Notices::WITH_TRIGGER_ERROR
            );

            $action = TIVWP_DM::ACTION_DEACTIVATE;

            TIVWP_DM_Notices::add(sprintf(__('The unknown action has been replaced with "%s"', 'tivwp-dm'), $action), TIVWP_DM_Notices::WITH_TRIGGER_ERROR);
        }

        /**
         * Silently (de)activate all development plugins
         * @todo admin bar on front is shown before this, so it will be updated only on the next screen refresh
         */
        add_action('init', array(
            'TIVWP_DM',
            "{$action}_all_development_plugins"
                ), 20);

        /**
         * Enqueue admin notice about the action performed
         */
        TIVWP_DM_Notices::add(sprintf(__('Automatic action performed: "%s"', 'tivwp-dm'), $action, TIVWP_DM_Notices::WITHOUT_TRIGGER_ERROR));
    }

}

// class

# --- EOF
