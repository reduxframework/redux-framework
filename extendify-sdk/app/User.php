<?php
/**
 * Helper class for interacting with the user
 */

namespace Extendify\ExtendifySdk;

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
     * The DB key for scoping
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
     * Returns the application state for he current user
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

        // Get the current default number of imports allowed.
        if (!isset($userData['state']['allowedImports'])) {
            $currentImports = Http::get('/max-free-imports');
            $userData['state']['allowedImports'] = is_numeric($currentImports) && $currentImports > 0 ? $currentImports : 3;
        }

        $userData['state']['uuid'] = self::data('uuid');
        $userData['state']['canInstallPlugins'] = \current_user_can('install_plugins');
        $userData['state']['canActivatePlugins'] = \current_user_can('activate_plugins');

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
