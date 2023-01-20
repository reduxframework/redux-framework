<?php
/**
 * Assist admin page.
 */

namespace Extendify\Assist;

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
    public $slug = 'extendify-assist';

    /**
     * Adds various actions to set up the page
     *
     * @return void
     */
    public function __construct()
    {
        \add_action(
            'in_admin_header',
            function () {
                // phpcs:ignore WordPress.Security.NonceVerification
                if (isset($_GET['page']) && $_GET['page'] === 'extendify-assist') {
                    \remove_all_actions('admin_notices');
                    \remove_all_actions('all_admin_notices');
                }
            },
            1000
        );
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
        <div
            id="extendify-assist-landing-page"
            class="extendify-assist"
            data-test="assist-landing">
        </div>
        <?php
    }
}
