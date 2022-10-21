<?php
/**
 * Obnboarding admin page.
 */

namespace Extendify\Onboarding;

/**
 * This class handles the Assist admin page.
 */
class AdminPage
{
    /**
     * The admin page slug
     *
     * @var $string
     */
    public $slug = 'extendify-launch';

    /**
     * Adds various actions to set up the page
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Settings page output
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function pageContent()
    {
        ?>
        <div id="extendify-onboarding-page" class="extendify-onboarding" style="position:fixed;background:white;top:0;left:0;right:0;bottom:0;z-index:99999"></div>
        <?php
    }
}
