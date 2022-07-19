<?php
/**
 * Helper class for interacting with the user
 */

namespace Extendify;

use Extendify\Config;

/**
 * Helper class for interacting with the user
 */
class User
{

    /**
     * User unique, anonymous identifier
     *
     * @var string
     */
    public $uuid = '';

    /**
     * A WP user
     *
     * @var \WP_User
     */
    protected $user = null;

    /**
     * The DB key for scoping. For historical reasons do not change
     *
     * @var string
     */
    protected $key = 'extendifysdk_';

    /**
     * The class instance.
     *
     * @var $instance
     */
    protected static $instance = null;

    /**
     * Set up the user
     *
     * @param WP_User $user - A WP User object.
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Return the user ID
     *
     * @return void
     */
    private function setupUuid()
    {
        $uuid = \get_user_meta($this->user->ID, $this->key . 'uuid', true);
        if (!$uuid) {
            $id = \wp_hash(\wp_json_encode($this->user));
            \update_user_meta($this->user->ID, $this->key . 'uuid', $id);
        }

        $this->uuid = $uuid;
    }

    /**
     * Returns data about the user
     * Use it like User::data('ID') to get the user id
     *
     * @param string $arguments - Right now a string of arguments, like ID.
     * @return mixed - Data about the user.
     */
    private function dataHandler($arguments)
    {
        // Right now assume a single argument, but could expand to multiple.
        if (isset($this->user->$arguments)) {
            return $this->user->$arguments;
        }

        return \get_user_meta($this->user->ID, $this->key . $arguments, true);
    }

    /**
     * Returns application state for the current user
     * Use it like User::data('ID') to get the user id
     *
     * @return string - JSON representation of the current state
     */
    private function stateHandler()
    {
        $state = \get_user_meta($this->user->ID, $this->key . 'user_data');

        // Add some state boilerplate code for the first load.
        if (!isset($state[0])) {
            $state[0] = '{}';
        }

        $userData = json_decode($state[0], true);
        if (!isset($userData['version'])) {
            $userData['version'] = 0;
        }

        // This will reset the allowed max imports to 0 once a week which will force the library to re-check.
        if (!get_transient('extendify_import_max_check_' . $this->user->ID)) {
            set_transient('extendify_import_max_check_' . $this->user->ID, time(), strtotime('1 week', 0));
            $userData['state']['allowedImports'] = 0;
        }

        // Similar to above, this will give the user free imports once a month just for logging in.
        if (!get_transient('extendify_free_extra_imports_check_' . $this->user->ID)) {
            set_transient('extendify_free_extra_imports_check_' . $this->user->ID, time(), strtotime('first day of next month', 0));
            $userData['state']['runningImports'] = 0;
        }

        if (!isset($userData['state']['sdkPartner']) || !$userData['state']['sdkPartner']) {
            $userData['state']['sdkPartner'] = Config::$sdkPartner;
        }

        $userData['state']['uuid'] = self::data('uuid');
        $userData['state']['canInstallPlugins'] = \current_user_can('install_plugins');
        $userData['state']['canActivatePlugins'] = \current_user_can('activate_plugins');
        $userData['state']['isAdmin'] = \current_user_can('create_users');

        // If the license key is set on the server, force use it.
        if (defined('EXTENDIFY_SITE_LICENSE')) {
            $userData['state']['apiKey'] = constant('EXTENDIFY_SITE_LICENSE');
        }

        // This probably shouldn't have been wrapped in wp_json_encode,
        // but needs to remain until we can safely log pro users out,
        // as changing this now would erase all user data.
        return \wp_json_encode($userData);
    }

    /**
     * Allows to dynamically setup the user with uuid
     * Use it like User::data('ID') to get the user id
     *
     * @param string $name      - The name of the method to call.
     * @param array  $arguments - The arguments to pass in.
     *
     * @return mixed
     */
    public static function __callStatic($name, array $arguments)
    {
        $name = "{$name}Handler";
        if (is_null(self::$instance)) {
            require_once ABSPATH . 'wp-includes/pluggable.php';
            self::$instance = new static(\wp_get_current_user());
            $r = self::$instance;
            $r->setupUuid();
        }

        $r = self::$instance;
        return $r->$name(...$arguments);
    }
}
