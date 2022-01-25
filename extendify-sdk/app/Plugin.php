<?php
// phpcs:ignoreFile
// This class was copied from JetPack (mostly)
// so will be a bit of work to refactor
/**
 * Manage plugin dependencies
 */

namespace Extendify\Library;

class Plugin
{
    /**
     * Install and activate a plugin.
     *
     * @since 5.8.0
     *
     * @param string $slug Plugin slug.
     *
     * @return bool|WP_Error True if installation succeeded, error object otherwise.
     */
    public static function install_and_activate_plugin($slug)
    {
        $plugin_id = self::get_plugin_id_by_slug($slug);
        if (! $plugin_id) {
            $installed = self::install_plugin($slug);
            if (is_wp_error($installed)) {
                return $installed;
            }
            $plugin_id = self::get_plugin_id_by_slug($slug);
        } elseif (is_plugin_active($plugin_id)) {
            return true; // Already installed and active.
        }

        if (! current_user_can('activate_plugins')) {
            return new \WP_Error('not_allowed', __('You are not allowed to activate plugins on this site.', 'jetpack'));
        }
        $activated = activate_plugin($plugin_id);
        if (is_wp_error($activated)) {
            return $activated;
        }

        return true;
    }

    /**
     * Install a plugin.
     *
     * @since 5.8.0
     *
     * @param string $slug Plugin slug.
     *
     * @return bool|WP_Error True if installation succeeded, error object otherwise.
     */
    public static function install_plugin($slug)
    {
        if (is_multisite() && ! current_user_can('manage_network')) {
            return new \WP_Error('not_allowed', __('You are not allowed to install plugins on this site.', 'jetpack'));
        }

        $skin     = new PluginUpgraderSkin();
        $upgrader = new \Plugin_Upgrader($skin);
        $zip_url  = self::generate_wordpress_org_plugin_download_link($slug);

        $result = $upgrader->install($zip_url);

        if (is_wp_error($result)) {
            return $result;
        }

        $plugin     = self::get_plugin_id_by_slug($slug);
        $error_code = 'install_error';
        if (! $plugin) {
            $error = __('There was an error installing your plugin', 'jetpack');
        }

        if (! $result) {
            $error_code = $upgrader->skin->get_main_error_code();
            $message    = $upgrader->skin->get_main_error_message();
            $error      = $message ? $message : __('An unknown error occurred during installation', 'jetpack');
        }

        if (! empty($error)) {
            if ('download_failed' === $error_code) {
                // For backwards compatibility: versions prior to 3.9 would return no_package instead of download_failed.
                $error_code = 'no_package';
            }

            return new \WP_Error($error_code, $error, 400);
        }

        return (array) $upgrader->skin->get_upgrade_messages();
    }

    /**
     * Get WordPress.org zip download link from a plugin slug
     *
     * @param string $plugin_slug Plugin slug.
     */
    protected static function generate_wordpress_org_plugin_download_link($plugin_slug)
    {
        return "https://downloads.wordpress.org/plugin/$plugin_slug.latest-stable.zip";
    }

    /**
     * Get the plugin ID (composed of the plugin slug and the name of the main plugin file) from a plugin slug.
     *
     * @param string $slug Plugin slug.
     */
    public static function get_plugin_id_by_slug($slug)
    {
        // Check if get_plugins() function exists. This is required on the front end of the
        // site, since it is in a file that is normally only loaded in the admin.
        if (! function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        /** This filter is documented in wp-admin/includes/class-wp-plugins-list-table.php */
        $plugins = apply_filters('all_plugins', get_plugins());
        if (! is_array($plugins)) {
            return false;
        }

        foreach ($plugins as $plugin_file => $plugin_data) {
            if (self::get_slug_from_file_path($plugin_file) === $slug) {
                return $plugin_file;
            }
        }

        return false;
    }

    /**
     * Get the plugin slug from the plugin ID (composed of the plugin slug and the name of the main plugin file)
     *
     * @param string $plugin_file Plugin file (ID -- e.g. hello-dolly/hello.php).
     */
    protected static function get_slug_from_file_path($plugin_file)
    {
        // Similar to get_plugin_slug() method.
        $slug = dirname($plugin_file);
        if ('.' === $slug) {
            $slug = preg_replace('/(.+)\.php$/', '$1', $plugin_file);
        }

        return $slug;
    }

    /**
     * Get the activation status for a plugin.
     *
     * @since 8.9.0
     *
     * @param string $plugin_file The plugin file to check.
     * @return string Either 'network-active', 'active' or 'inactive'.
     */
    public static function get_plugin_status($plugin_file)
    {
        if (is_plugin_active_for_network($plugin_file)) {
            return 'network-active';
        }

        if (is_plugin_active($plugin_file)) {
            return 'active';
        }

        return 'inactive';
    }

    /**
     * Returns a list of all plugins in the site.
     *
     * @since 8.9.0
     * @uses get_plugins()
     *
     * @return array
     */
    public static function get_plugins()
    {
        if (! function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        /** This filter is documented in wp-admin/includes/class-wp-plugins-list-table.php */
        $plugins = apply_filters('all_plugins', get_plugins());

        if (is_array($plugins) && ! empty($plugins)) {
            foreach ($plugins as $plugin_slug => $plugin_data) {
                $plugins[ $plugin_slug ]['active'] = in_array(
                    self::get_plugin_status($plugin_slug),
                    array( 'active', 'network-active' ),
                    true
                );
            }
            return $plugins;
        }

        return array();
    }
}

include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
include_once ABSPATH . 'wp-admin/includes/file.php';

/**
 * Allows us to capture that the site doesn't have proper file system access.
 * In order to update the plugin.
 */
class PluginUpgraderSkin extends \Automatic_Upgrader_Skin
{
    /**
     * Stores the last error key;
     **/
    protected $main_error_code = 'install_error';

    /**
     * Stores the last error message.
     **/
    protected $main_error_message = 'An unknown error occurred during installation';

    /**
     * Overwrites the set_upgrader to be able to tell if we e ven have the ability to write to the files.
     *
     * @param WP_Upgrader $upgrader
     *
     */
    public function set_upgrader(&$upgrader)
    {
        parent::set_upgrader($upgrader);

        // Check if we even have permission to.
        $result = $upgrader->fs_connect(array( WP_CONTENT_DIR, WP_PLUGIN_DIR ));
        if (! $result) {
            // set the string here since they are not available just yet
            $upgrader->generic_strings();
            $this->feedback('fs_unavailable');
        }
    }

    /**
     * Overwrites the error function
     */
    public function error($error)
    {
        if (is_wp_error($error)) {
            $this->feedback($error);
        }
    }

    private function set_main_error_code($code)
    {
        // Don't set the process_failed as code since it is not that helpful unless we don't have one already set.
        $this->main_error_code = ($code === 'process_failed' && $this->main_error_code ? $this->main_error_code : $code);
    }

    private function set_main_error_message($message, $code)
    {
        // Don't set the process_failed as message since it is not that helpful unless we don't have one already set.
        $this->main_error_message = ($code === 'process_failed' && $this->main_error_code ? $this->main_error_code : $message);
    }

    public function get_main_error_code()
    {
        return $this->main_error_code;
    }

    public function get_main_error_message()
    {
        return $this->main_error_message;
    }

    /**
     * Overwrites the feedback function
     *
     * @param string|array|WP_Error $data    Data.
     * @param mixed                 ...$args Optional text replacements.
     */
    public function feedback($data, ...$args)
    {
        $current_error = null;
        if (is_wp_error($data)) {
            $this->set_main_error_code($data->get_error_code());
            $string = $data->get_error_message();
        } elseif (is_array($data)) {
            return;
        } else {
            $string = $data;
        }

        if (! empty($this->upgrader->strings[$string])) {
            $this->set_main_error_code($string);

            $current_error = $string;
            $string        = $this->upgrader->strings[$string];
        }

        if (strpos($string, '%') !== false) {
            if (! empty($args)) {
                $string = vsprintf($string, $args);
            }
        }

        $string = trim($string);
        $string = wp_kses(
            $string,
            array(
            'a'      => array(
                'href' => true
            ),
            'br'     => true,
            'em'     => true,
            'strong' => true,
        )
        );

        $this->set_main_error_message($string, $current_error);
        $this->messages[] = $string;
    }
}
