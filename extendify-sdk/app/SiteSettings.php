<?php
/**
 * Helper class for interacting with the user
 */

namespace Extendify\Library;

/**
 * Helper class for interacting with the user
 */
class SiteSettings
{

    /**
     * SiteSettings option_name - For historical reasons do not change.
     *
     * @var string
     */
    protected $key = 'extendifysdk_sitesettings';

    /**
     * SiteSettings default value
     *
     * @var Json
     */
    protected $default = '{"state":{"enabled":true}}';

    /**
     * The class instance.
     *
     * @var $instance
     */
    protected static $instance = null;

    /**
     * Returns Setting
     * Use it like Setting::data()
     *
     * @return mixed - Setting Data
     */
    private function dataHandler()
    {
        return \get_option($this->key, $this->default);
    }

    /**
     * Returns Setting Key
     * Use it like Setting::key()
     *
     * @return string - Setting key
     */
    private function keyHandler()
    {
        return $this->key;
    }

    /**
     * Use it like Setting::method() e.g. Setting::data()
     *
     * @param string $name      - The name of the method to call.
     * @param array  $arguments - The arguments to pass in.
     *
     * @return mixed
     */
    public static function __callStatic($name, array $arguments)
    {
        $name = "{$name}Handler";
        self::$instance = new static();
        $r = self::$instance;
        return $r->$name(...$arguments);
    }
}
