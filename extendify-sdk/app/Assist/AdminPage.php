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
     * Adds various actions to set up the page
     *
     * @return void
     */
    public function __construct()
    {
        if (Config::$showAssist) {
            \add_action('admin_menu', [ $this, 'addAdminMenu' ]);
            $this->loadScripts();
        }
    }

    /**
     * Adds Extendify menu to admin panel.
     *
     * @return void
     */
    public function addAdminMenu()
    {
        add_menu_page(
            'Extendify',
            'Extendify',
            Config::$requiredCapability,
            'extendify-assist',
            [
                $this,
                'createAdminPage',
            ],
            // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
            'data:image/svg+xml;base64,' . base64_encode('<svg width="20" height="20" viewBox="0 0 60 62" fill="black" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M36.0201 0H49.2377C52.9815 0 54.3365 0.391104 55.7061 1.12116C57.0756 1.85412 58.1469 2.92893 58.8795 4.29635C59.612 5.66666 60 7.02248 60 10.7684V23.9935C60 27.7394 59.6091 29.0952 58.8795 30.4655C58.1469 31.8358 57.0727 32.9078 55.7061 33.6407C55.0938 33.9684 54.4831 34.2381 53.661 34.4312V44.9564C53.661 50.7417 53.0573 52.8356 51.9305 54.952C50.7991 57.0683 49.1401 58.7238 47.0294 59.8558C44.9143 60.9878 42.8215 61.5873 37.0395 61.5873H16.626C10.844 61.5873 8.75122 60.9833 6.63608 59.8558C4.52094 58.7238 2.86639 57.0638 1.73504 54.952C0.603687 52.8401 0 50.7417 0 44.9564V24.5358C0 18.7506 0.603687 16.6566 1.73057 14.5403C2.86192 12.424 4.52094 10.764 6.63608 9.63201C8.74675 8.5045 10.844 7.90047 16.626 7.90047H25.3664C25.5303 6.18172 25.8724 5.24393 26.3754 4.29924C27.1079 2.92893 28.1821 1.85412 29.5517 1.12116C30.9183 0.391104 32.2763 0 36.0201 0ZM29.2266 8.41812C29.2266 5.96352 31.2155 3.97368 33.6689 3.97368H51.5859C54.0393 3.97368 56.0282 5.96352 56.0282 8.41812V26.3438C56.0282 28.7984 54.0393 30.7882 51.5859 30.7882H33.6689C31.2155 30.7882 29.2266 28.7984 29.2266 26.3438V8.41812Z" fill="black"/></svg>')
        );

        if (Config::$environment === 'PRODUCTION') {
            add_submenu_page(
                'extendify-assist',
                'Assist',
                'Assist',
                Config::$requiredCapability,
                'extendify-assist',
                '',
                300
            );
        }
    }

    /**
     * Settings page output
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function createAdminPage()
    {
        ?>
        <div class="extendify-outer-container">
            <div class="wrap welcome-container">
                <ul class="extendify-welcome-tabs">
                    <li class="active"><a href="<?php echo \esc_url(\admin_url('admin.php?page=extendify-assist')); ?>">Assist</a></li>
                    <li><a href="<?php echo \esc_url(\admin_url('admin.php?page=extendify-welcome')); ?>">Library</a></li>
                </ul>
                <div id="extendify-assist-landing-page" class="extendify-assist"></div>
            </div>
        </div>
        <?php
    }

    /**
     * Adds admin styles if on the assist page
     *
     * @return void
     */
    public function loadScripts()
    {
        // No nonce for _GET.
        // phpcs:ignore WordPress.Security.NonceVerification
        if (isset($_GET['page']) && $_GET['page'] === 'extendify-assist') {
            \add_action(
                'in_admin_header',
                function () {
                    \remove_all_actions('admin_notices');
                    \remove_all_actions('all_admin_notices');
                },
                1000
            );

            \add_action(
                'admin_enqueue_scripts',
                function () {
                    \wp_enqueue_style(
                        'extendify-assist',
                        EXTENDIFY_URL . 'public/admin-page/welcome.css',
                        [],
                        Config::$environment === 'PRODUCTION' ? Config::$version : uniqid()
                    );
                }
            );
        }//end if
    }
}
