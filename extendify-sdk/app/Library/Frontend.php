<?php
/**
 * Manage any frontend related tasks here.
 */

namespace Extendify\Library;

use Extendify\Config;

/**
 * This class handles any file loading for the frontend of the site.
 */
class Frontend
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
    }

    /**
     * Adds scripts and styles to every page is enabled
     *
     * @return void
     */
    public function loadScripts()
    {
        \add_action(
            'wp_enqueue_scripts',
            function () {
                // TODO: Determine a way to conditionally load assets (https://github.com/extendify/company-product/issues/72).
                $this->addStylesheets();
            }
        );
    }

    /**
     * Adds stylesheets as needed
     *
     * @return void
     */
    public function addStylesheets()
    {
    }
}
