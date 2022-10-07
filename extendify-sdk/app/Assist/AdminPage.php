<?php
/**
 * Assist admin page.
 */

namespace Extendify\Assist;

use Extendify\Config;

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

        \add_action(
            'admin_enqueue_scripts',
            function () {
                // phpcs:ignore WordPress.Security.NonceVerification
                if (isset($_GET['page']) && $_GET['page'] === 'extendify-assist') {
                    // TODO: Remove this dependency.
                    \wp_enqueue_style(
                        'extendify-assist',
                        EXTENDIFY_URL . 'public/admin-page/welcome.css',
                        [],
                        Config::$environment === 'PRODUCTION' ? Config::$version : uniqid()
                    );
                }
            }
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
        <div class="extendify-outer-container">
            <div class="wrap" style="max-width:1000px;margin:-16px auto 24px;">
                <ul class="extendify-welcome-tabs">
                    <li class="active"><a href="<?php echo \esc_url(\admin_url('admin.php?page=extendify-assist')); ?>">Assist</a></li>
                    <li><a href="<?php echo \esc_url(\admin_url('admin.php?page=extendify-welcome')); ?>">Library</a></li>
                    <li class="cta-button"><a href="<?php echo \esc_url_raw(\get_home_url()); ?>" target="_blank"><?php echo \esc_html(__('View Site', 'extendify')); ?></a></li>
                </ul>
                <div id="extendify-assist-landing-page" class="extendify-assist"></div>
            </div>
        </div>
        <?php
    }
}
